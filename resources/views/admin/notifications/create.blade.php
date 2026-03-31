@extends('layouts.admin')

@section('title', 'Tạo thông báo mới')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Tạo thông báo mới</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Nội dung</label>
                    <textarea class="form-control" id="message" name="message" rows="10"></textarea>
                </div>
                <div class="mb-3">
                    <label for="recipient_type" class="form-label">Gửi đến</label>
                    <select class="form-select" id="recipient_type" name="recipient_type" required
                        onchange="toggleRecipientInput()">
                        <option value="all_students">Tất cả sinh viên</option>
                        <option value="all_lecturers">Tất cả giảng viên</option>
                        <option value="specific_user">Người dùng cụ thể (Email/Mã/ID)</option>
                    </select>
                </div>
                <div class="mb-3 d-none" id="user_id_group">
                    <label for="user_identifier" class="form-label">Email, Mã Sinh viên, Mã Giảng viên, hoặc ID</label>
                    <input type="text" class="form-control" id="user_identifier" name="user_identifier"
                        placeholder="Nhập email, mã SV, mã GV hoặc ID user">
                </div>
                <div class="mb-3">
                    <label for="attachment" class="form-label">Đính kèm tệp (Tùy chọn)</label>
                    <input class="form-control" type="file" id="attachment" name="attachment">
                </div>
                <button type="submit" class="btn btn-primary">Gửi thông báo</button>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let editor;
        ClassicEditor
            .create(document.querySelector('#message'))
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error(error);
            });

        // Sync data before submit
        document.querySelector('form').addEventListener('submit', (e) => {
            if (editor) {
                const messageData = editor.getData();
                document.querySelector('#message').value = messageData;

                if (!messageData) {
                    e.preventDefault();
                    alert('Vui lòng nhập nội dung thông báo.');
                }
            }
        });

        function toggleRecipientInput() {
            const type = document.getElementById('recipient_type').value;
            const group = document.getElementById('user_id_group');
            if (type === 'specific_user') {
                group.classList.remove('d-none');
                document.getElementById('user_identifier').required = true;
            } else {
                group.classList.add('d-none');
                document.getElementById('user_identifier').required = false;
            }
        }
    </script>
@endsection
