<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #007bff; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .footer { text-align: center; margin-top: 20px; font-size: 0.8em; color: #666; }
        .credentials { background-color: #f9f9f9; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Chào mừng bạn đến với Hệ thống Quản lý Sinh viên</h2>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $student->full_name }}</strong>,</p>
            <p>Tài khoản của bạn đã được tạo thành công trên hệ thống. Dưới đây là thông tin đăng nhập của bạn:</p>
            
            <div class="credentials">
                <p><strong>Tài khoản đăng nhập (Email):</strong> {{ $student->email }}</p>
                <p><strong>Mật khẩu:</strong> {{ $password }}</p>
                <p><strong>Truy cập tại:</strong> <a href="{{ config('app.url') }}">{{ config('app.url') }}</a></p>
            </div>

            <p>Vui lòng đổi mật khẩu ngay sau khi đăng nhập lần đầu tiên để đảm bảo bảo mật.</p>
        </div>
        <div class="footer">
            <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        </div>
    </div>
</body>
</html>