@extends('layouts.admin')

@section('title', 'Quản lý Điểm')
@section('header', 'Quản lý Điểm: ' . $courseClass->course->name . ' - ' . $courseClass->classroom)

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('admin.course_classes.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                    Quay lại danh sách lớp</a>

                <div>
                    <a href="{{ route('admin.grades.export', $courseClass) }}" class="btn btn-success me-2">
                        <i class="fas fa-file-excel me-1"></i> Xuất Excel
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-import me-1"></i> Nhập Excel
                    </button>
                </div>
            </div>

            <!-- Import Modal -->
            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Nhập điểm từ Excel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.grades.import', $courseClass) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Chọn file Excel (.xlsx, .xls)</label>
                                    <input type="file" class="form-control" id="file" name="file" required
                                        accept=".xlsx, .xls">
                                </div>
                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i> File Excel cần có các cột:
                                        <strong>ma_sv</strong>, <strong>diem_cc_10</strong>, <strong>diem_gk_30</strong>,
                                        <strong>diem_ck_60</strong>.
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Nhập điểm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.grades.update', $courseClass) }}" method="POST">
                @csrf
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã SV</th>
                            <th>Họ tên</th>
                            <th>Điểm chuyên cần (10%)</th>
                            <th>Điểm giữa kỳ (30%)</th>
                            <th>Điểm cuối kỳ (60%)</th>
                            <th>Tổng kết</th>
                            <th>GPA (4.0)</th>
                            <th>Điểm chữ</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grades as $grade)
                            <tr>
                                <td>{{ $grade->student->student_code }}</td>
                                <td>{{ $grade->student->full_name }}</td>
                                <td>
                                    <input type="number" step="0.1" min="0" max="10" class="form-control"
                                        name="grades[{{ $grade->student_id }}][attendance_score]"
                                        value="{{ $grade->attendance_score }}">
                                </td>
                                <td>
                                    <input type="number" step="0.1" min="0" max="10" class="form-control"
                                        name="grades[{{ $grade->student_id }}][midterm_score]"
                                        value="{{ $grade->midterm_score }}">
                                </td>
                                <td>
                                    <input type="number" step="0.1" min="0" max="10" class="form-control"
                                        name="grades[{{ $grade->student_id }}][final_score]"
                                        value="{{ $grade->final_score }}">
                                </td>
                                <td>
                                    <strong>{{ number_format($grade->total_score, 1) }}</strong>
                                </td>
                                <td>
                                    <strong>{{ number_format($grade->gpa, 1) }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $grade->letter_grade }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $grade->total_score >= 4 ? 'success' : 'danger' }}">
                                        {{ $grade->total_score >= 4 ? 'Đạt' : 'Trượt' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($grade->gpa < 2.0)
                                        <button type="submit" form="send-warning-{{ $grade->id }}"
                                            class="btn btn-sm btn-warning" title="Gửi cảnh báo">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Lưu bảng điểm</button>
            </form>

            {{-- Hidden forms for sending warnings --}}
            @foreach ($grades as $grade)
                @if ($grade->gpa < 2.0)
                    <form id="send-warning-{{ $grade->id }}" action="{{ route('admin.grades.send_warning', $grade) }}"
                        method="POST" class="d-none">
                        @csrf
                    </form>
                @endif
            @endforeach
        </div>
    </div>
@endsection
