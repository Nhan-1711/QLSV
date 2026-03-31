@extends('layouts.lecturer')

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
            <form action="{{ route('lecturer.notifications.store') }}" method="POST" enctype="multipart/form-data">
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
                        <option value="my_classes">Tất cả lớp tôi dạy</option>
                        <option value="specific_class">Lớp cụ thể</option>
                        <option value="specific_student">Sinh viên cụ thể (Mã SV)</option>
                    </select>
                </div>

                <div class="mb-3 d-none" id="class_id_group">
                    <label for="class_id" class="form-label">Chọn lớp</label>
                    <select class="form-select" id="class_id" name="class_id">
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->course->name }} - {{ $class->classroom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 d-none" id="student_code_group">
                    <label for="student_code" class="form-label">Mã Sinh Viên</label>
                    <input type="text" class="form-control" id="student_code" name="student_code">
                </div>

                <div class="mb-3">
                    <label for="attachment" class="form-label">Đính kèm tệp (Tùy chọn)</label>
                    <input class="form-control" type="file" id="attachment" name="attachment">
                </div>

                <button type="submit" class="btn btn-primary">Gửi thông báo</button>
                <a href="{{ route('lecturer.notifications.index') }}" class="btn btn-secondary">Hủy</a>
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
            const classGroup = document.getElementById('class_id_group');
            const studentGroup = document.getElementById('student_code_group');

            classGroup.classList.add('d-none');
            studentGroup.classList.add('d-none');
            document.getElementById('class_id').required = false;
            document.getElementById('student_code').required = false;

            if (type === 'specific_class') {
                classGroup.classList.remove('d-none');
                document.getElementById('class_id').required = true;
            } else if (type === 'specific_student') {
                studentGroup.classList.remove('d-none');
                document.getElementById('student_code').required = true;
            }
        }
    </script>
@endsection
