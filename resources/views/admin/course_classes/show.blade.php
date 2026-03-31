@extends('layouts.admin')

@section('title', 'Chi tiết Lớp học phần')
@section('header', 'Chi tiết Lớp học phần')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Thông tin lớp học</div>
                <div class="card-body">
                    <p><strong>Học phần:</strong> {{ $courseClass->course->name }} ({{ $courseClass->course->code }})</p>
                    <p><strong>Giảng viên:</strong> {{ $courseClass->lecturer->full_name }}</p>
                    <p><strong>Phòng học:</strong> {{ $courseClass->classroom }}</p>
                    <p><strong>Thời gian:</strong> Thứ {{ $courseClass->day_of_week }}, Tiết
                        {{ $courseClass->period_from }}-{{ $courseClass->period_to }}</p>
                    <p><strong>Ngày học:</strong> {{ \Carbon\Carbon::parse($courseClass->start_date)->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($courseClass->end_date)->format('d/m/Y') }}</p>
                    <p><strong>Trạng thái:</strong>
                        <span
                            class="badge bg-{{ $courseClass->status == 'active' ? 'success' : ($courseClass->status == 'completed' ? 'primary' : 'secondary') }}">
                            {{ $courseClass->status == 'active' ? 'Đang học' : ($courseClass->status == 'completed' ? 'Đã kết thúc' : 'Đã hủy') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Danh sách sinh viên</span>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal"><i
                            class="fas fa-plus"></i> Thêm sinh viên</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã SV</th>
                                <th>Họ tên</th>
                                <th>Lớp</th>
                                <th>Ngày đăng ký</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courseClass->students as $student)
                                <tr>
                                    <td>{{ $student->student_code }}</td>
                                    <td>{{ $student->full_name }}</td>
                                    <td>{{ $student->class->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <form
                                            action="{{ route('admin.course_classes.remove_student', ['courseClass' => $courseClass->id, 'student' => $student->id]) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Xóa sinh viên khỏi lớp?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Student -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.course_classes.add_student', $courseClass) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm sinh viên vào lớp</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="student_code" class="form-label">Mã sinh viên</label>
                            <input type="text" class="form-control" id="student_code" name="student_code" required
                                placeholder="Nhập mã sinh viên">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
