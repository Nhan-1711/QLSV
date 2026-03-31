@extends('layouts.admin')

@section('title', 'Chi tiết điểm danh')
@section('header', 'Chi tiết điểm danh: ' . \Carbon\Carbon::parse($session->session_date)->format('d/m/Y'))

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Thông tin buổi học</div>
                <div class="card-body">
                    <p><strong>Lớp:</strong> {{ $courseClass->course->name }}</p>
                    <p><strong>Thời gian:</strong> {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</p>
                    <p><strong>Trạng thái:</strong> {{ $session->status }}</p>
                    <hr>
                    <h5>QR Code điểm danh</h5>
                    <div class="text-center mb-4">
                        {!! $qrCode !!}
                        <p class="mt-2 text-muted small">Quét mã để điểm danh</p>
                        <div class="mt-2">
                            <a href="{{ route('attendance.checkin', ['token' => $session->qr_code_token]) }}" target="_blank"
                                class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-external-link-alt"></i> Link điểm danh (Test)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Danh sách sinh viên</div>
                <div class="card-body">
                    <form
                        action="{{ route('admin.attendance.update_attendance', ['courseClass' => $courseClass->id, 'session' => $session->id]) }}"
                        method="POST">
                        @csrf
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã SV</th>
                                    <th>Họ tên</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($session->attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->student->student_code }}</td>
                                        <td>{{ $attendance->student->full_name }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <input type="radio" class="btn-check"
                                                    name="attendance[{{ $attendance->student_id }}]"
                                                    id="present_{{ $attendance->id }}" value="present"
                                                    {{ $attendance->status == 'present' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-success btn-sm"
                                                    for="present_{{ $attendance->id }}">Có mặt</label>

                                                <input type="radio" class="btn-check"
                                                    name="attendance[{{ $attendance->student_id }}]"
                                                    id="late_{{ $attendance->id }}" value="late"
                                                    {{ $attendance->status == 'late' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-warning btn-sm"
                                                    for="late_{{ $attendance->id }}">Muộn</label>

                                                <input type="radio" class="btn-check"
                                                    name="attendance[{{ $attendance->student_id }}]"
                                                    id="excused_{{ $attendance->id }}" value="excused"
                                                    {{ $attendance->status == 'excused' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-info btn-sm"
                                                    for="excused_{{ $attendance->id }}">Có phép</label>

                                                <input type="radio" class="btn-check"
                                                    name="attendance[{{ $attendance->student_id }}]"
                                                    id="absent_{{ $attendance->id }}" value="absent"
                                                    {{ $attendance->status == 'absent' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-danger btn-sm"
                                                    for="absent_{{ $attendance->id }}">Vắng</label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Lưu điểm danh</button>
                        <a href="{{ route('admin.attendance.index', $courseClass) }}" class="btn btn-secondary">Quay
                            lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
