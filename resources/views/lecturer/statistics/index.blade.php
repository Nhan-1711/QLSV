@extends('layouts.lecturer')

@section('title', 'Thống kê sinh viên nguy cơ')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-primary">Thống kê sinh viên vùng nguy cơ <i
                    class="fas fa-exclamation-triangle text-warning"></i></h2>
            <p class="text-muted">Danh sách sinh viên có điểm thấp, GPA thấp hoặc chuyên cần kém.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('lecturer.statistics.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="class_id" class="form-label fw-bold">Lọc theo lớp học phần:</label>
                    <select name="class_id" id="class_id" class="form-select">
                        <option value="">-- Tất cả các lớp --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->course->name }} - {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> Lọc</button>
                </div>
            </form>
        </div>
    </div>

    @if ($atRiskStudents->isEmpty())
        <div class="alert alert-success text-center py-5">
            <i class="fas fa-check-circle fa-3x mb-3"></i>
            <h4>Xin chúc mừng!</h4>
            <p>Không tìm thấy sinh viên nào trong vùng nguy cơ dựa trên tiêu chí (Điểm < 5, GPA < 2.0, Chuyên cần <
                    70%).</p>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold text-danger">Danh sách cảnh báo ({{ $atRiskStudents->count() }} sinh viên)</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>MSSV</th>
                            <th>Họ tên</th>
                            <th>Lớp học phần</th>
                            <th style="width: 30%;">Lý do cảnh báo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($atRiskStudents as $student)
                            <tr>
                                <td>{{ $student->student_code }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2 bg-secondary text-white d-flex align-items-center justify-content-center"
                                            style="width: 35px; height: 35px; border-radius: 50%;">
                                            {{ substr($student->full_name, 0, 1) }}
                                        </div>
                                        <div class="fw-bold">{{ $student->full_name }}</div>
                                    </div>
                                </td>
                                <td>{{ $student->course_class_name }}</td>
                                <td>
                                    @foreach ($student->risk_reasons as $reason)
                                        <div class="badge bg-danger mb-1 text-wrap text-start" style="width: 100%;">
                                            <i class="fas fa-exclamation-circle me-1"></i> {{ $reason }}
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- Send Notification --}}
                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                            onclick="openWarningModal({{ $student->id }}, '{{ $student->full_name }}', 'notification', {{ $student->course_class_id }})">
                                            <i class="fas fa-bell"></i> Thông báo
                                        </button>

                                        {{-- Send Email --}}
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="openWarningModal({{ $student->id }}, '{{ $student->full_name }}', 'email', {{ $student->course_class_id }})">
                                            <i class="fas fa-envelope"></i> Email
                                        </button>

                                        {{-- Meet --}}
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="openWarningModal({{ $student->id }}, '{{ $student->full_name }}', 'meeting', {{ $student->course_class_id }})">
                                            <i class="fas fa-user-friends"></i> Gặp gỡ
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection

@push('modals')
    <!-- Warning Modal -->
    <div class="modal fade" id="warningModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="warningModalLabel">Gửi cảnh báo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="warningForm">
                        @csrf
                        <input type="hidden" id="warning_student_id" name="student_id">
                        <input type="hidden" id="warning_type" name="type">
                        <input type="hidden" id="warning_class_id" name="class_id">

                        <div class="mb-3">
                            <label class="form-label">Sinh viên:</label>
                            <input type="text" class="form-control" id="warning_student_name" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình thức:</label>
                            <input type="text" class="form-control" id="warning_type_display" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="warning_message" class="form-label">Nội dung cảnh báo:</label>
                            <textarea class="form-control" id="warning_message" name="message" rows="4" required
                                placeholder="Nhập nội dung cảnh báo..."></textarea>
                            <div class="form-text">Gợi ý: "Bạn cần cải thiện điểm số/chuyên cần gấp nếu không sẽ bị cấm
                                thi."</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" onclick="submitWarning()">Gửi cảnh báo</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        var warningModal;

        document.addEventListener('DOMContentLoaded', function() {
            warningModal = new bootstrap.Modal(document.getElementById('warningModal'));
        });

        function openWarningModal(id, name, type, classId) {
            document.getElementById('warning_student_id').value = id;
            document.getElementById('warning_student_name').value = name;
            document.getElementById('warning_class_id').value = classId; // Restore missing line
            let displayType = 'Gửi Thông báo hệ thống';
            let msg =
                "Cảnh báo học tập: Bạn đang nằm trong danh sách nguy cơ (Điểm thấp/Vắng nhiều). Vui lòng liên hệ giảng viên để được hỗ trợ.";
            let submissionType = type;

            if (type === 'email') {
                displayType = 'Gửi Email';
            } else if (type === 'meeting') {
                displayType = 'Đề xuất gặp mặt';
                submissionType = 'notification'; // Backend handles notification
                msg =
                    "Mời em gặp giảng viên để trao đổi về tình hình học tập.\n\nThời gian: [Nhập thời gian]\nĐịa điểm: [Nhập địa điểm/Phòng]";
            }

            document.getElementById('warning_type').value = submissionType;
            document.getElementById('warning_type_display').value = displayType;
            document.getElementById('warning_message').value = msg;

            warningModal.show();
        }

        function submitWarning() {
            const formData = new FormData(document.getElementById('warningForm'));
            const data = Object.fromEntries(formData.entries());

            fetch('{{ route('lecturer.statistics.warning') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        warningModal.hide();
                        Swal.fire('Thành công', 'Đã gửi cảnh báo thành công', 'success');
                    } else {
                        Swal.fire('Lỗi', 'Có lỗi xảy ra', 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Lỗi', 'Có lỗi hệ thống', 'error');
                });
        }
    </script>
@endpush
