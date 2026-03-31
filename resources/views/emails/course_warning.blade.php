<!DOCTYPE html>
<html>

<head>
    <title>Cảnh báo kết quả học tập</title>
</head>

<body>
    <h1>Cảnh báo kết quả học tập</h1>
    <p>Xin chào {{ $grade->student->full_name }},</p>
    <p>Kết quả học tập của bạn trong học phần <strong>{{ $grade->courseClass->course->name }}
            ({{ $grade->courseClass->course->code }})</strong> hiện đang ở mức thấp.</p>
    <ul>
        <li><strong>Điểm tổng kết:</strong> {{ $grade->total_score }}</li>
        <li><strong>GPA:</strong> {{ $grade->gpa }}</li>
    </ul>
    <p>Mức điểm này (GPA < 2.0) có thể ảnh hưởng đến kết quả chung của bạn.</p>
            <p>Vui lòng xem lại phương pháp học tập và liên hệ giảng viên nếu cần hỗ trợ.</p>
            <p>Trân trọng,<br>Phòng Đào tạo</p>
</body>

</html>
