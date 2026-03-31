<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatLog;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\CourseClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        $message = strtolower(trim($request->message));
        $user = Auth::user();

        if (!$user) {
            return response()->json(['response' => 'Bạn chưa đăng nhập!'], 401);
        }

        $student = $user->student;
        $response = '';
        $intent = 'general';
        $isAiResponse = false;

        try {
            if (str_contains($message, 'điểm')) {
                $intent = 'grade_query';
                if (!$student) {
                    $response = 'Tài khoản này không phải sinh viên, không xem điểm được.';
                } else {
                    $grades = Grade::where('student_id', $student->id)
                        ->with('courseClass.course')
                        ->latest('updated_at')
                        ->take(5)
                        ->get();

                    if ($grades->isEmpty()) {
                        $response = 'Bạn chưa có điểm môn nào.';
                    } else {
                        $response = "Điểm các môn gần nhất của bạn:<br>";
                        foreach ($grades as $grade) {
                            $score = $grade->total_score ?? 'Chưa có';
                            $courseName = $grade->courseClass->course->name ?? 'Môn học';
                            $response .= "- <b>$courseName</b>: $score<br>";
                        }
                    }
                }
            } else {
                $apiKey = env('GROQ_API_KEY');
                if (!$apiKey) {
                    return response()->json(['response' => 'Hệ thống chưa cấu hình AI Key (Thiếu GROQ_API_KEY).'], 500);
                }

                $isAiResponse = true;

                $now = Carbon::now();
                $currentTime = $now->format('l, d/m/Y H:i');
                $studentName = $user->name;

                $scheduleContext = 'Sinh viên hiện không có lịch học nào.';
                if ($student) {
                    $classes = $student->courseClasses()
                        ->where('course_classes.status', 'active')
                        ->with('course')
                        ->get();

                    if ($classes->isNotEmpty()) {
                        $scheduleList = [];
                        foreach ($classes as $c) {
                            $day = $c->day_of_week;
                            $name = $c->course->name ?? $c->name;
                            $scheduleList[] = "- Môn $name: Thứ $day, Tiết {$c->period_from}-{$c->period_to} (Phòng {$c->room})";
                        }
                        $scheduleContext = implode("\n", $scheduleList);
                    }
                }

                $systemPrompt = "Bạn là trợ lý ảo thông minh hỗ trợ sinh viên đại học tên là '$studentName'.\n"
                    . "Thời gian hiện tại: $currentTime.\n"
                    . "Ngôn ngữ trả lời: Tiếng Việt.\n\n"
                    . "Thời khóa biểu của sinh viên:\n$scheduleContext\n\n"
                    . "Hướng dẫn trả lời:\n"
                    . "- Trả lời các câu hỏi dựa trên thời khóa biểu và thời gian hiện tại được cung cấp ở trên.\n"
                    . "- Nếu được hỏi 'hôm nay là thứ mấy', hãy trả lời dựa vào Thời gian hiện tại.\n"
                    . "- Nếu được hỏi 'hôm nay có học không', hãy kiểm tra xem thứ trong tuần hiện tại có trùng với lịch học môn nào không.\n"
                    . "- Trả lời ngắn gọn, thân thiện, và xưng hô là 'mình' hoặc 'Chatbot'.";

                // WARNING: Chỉ dùng withoutVerifying() khi test local, không dùng production.
                $chatResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->withoutVerifying()
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $request->message],
                    ],
                    'temperature' => 0.5,
                ]);

                if (!$chatResponse->successful()) {
                    return response()->json([
                        'response' => 'Lỗi kết nối AI (' . $chatResponse->status() . '): ' . substr($chatResponse->body(), 0, 200)
                    ], 500);
                }

                $data = $chatResponse->json();
                $content = data_get($data, 'choices.0.message.content', 'Xin lỗi, AI không có câu trả lời.');
                $response = nl2br($content);
            }

            ChatLog::create([
                'user_id' => $user->id,
                'message' => $request->message,
                'response' => $response,
                'intent' => $intent,
                'is_ai_response' => $isAiResponse,
            ]);

            return response()->json(['response' => $response]);
        } catch (\Exception $e) {
            return response()->json(['response' => 'Lỗi Server: ' . $e->getMessage()], 500);
        }
    }

    public function index()
    {
        return view('student.chat_widget');
    }
}
