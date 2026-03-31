<!DOCTYPE html>
<html>

<head>
    <title>Cảnh báo học vụ</title>
</head>

<body>
    <h1>Cảnh báo học vụ</h1>
    <p>Xin chào {{ $student->full_name }},</p>
    <p>Hệ thống nhận thấy điểm trung bình tích lũy (GPA) của bạn hiện tại là: <strong>{{ $gpa }}</strong>.</p>
    <p>Mức điểm này dưới ngưỡng quy định (2.0). Bạn đang nằm trong diện <strong>Cảnh báo học vụ</strong>.</p>
    <p>Vui lòng liên hệ với Cố vấn học tập hoặc Phòng đào tạo để được tư vấn và hỗ trợ kịp thời.</p>
    <p>Trân trọng,<br>Phòng Đào tạo</p>
</body>

</html>
