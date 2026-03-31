<!DOCTYPE html>
<html>

<head>
    <title>Thông báo cấm thi</title>
</head>

<body>
    <h2>Thông báo cấm thi</h2>
    <p>Xin chào {{ $student->full_name }},</p>
    <p>Bạn nhận được email này vì số buổi vắng của bạn trong lớp học phần
        <strong>{{ $courseClass->course->name }}</strong> ({{ $courseClass->name }}) đã vượt quá quy định.</p>
    <p>Số buổi vắng hiện tại: <strong>{{ $absentCount }}</strong> buổi.</p>
    <p>Theo quy định, bạn sẽ không đủ điều kiện tham gia kỳ thi kết thúc học phần này.</p>
    <p>Vui lòng liên hệ với giảng viên hoặc phòng đào tạo nếu có thắc mắc.</p>
    <p>Trân trọng,<br>Hệ thống Quản lý Sinh viên</p>
</body>

</html>
