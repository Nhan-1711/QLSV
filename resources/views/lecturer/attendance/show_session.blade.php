@extends('layouts.lecturer')

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
                    <p><strong>Trạng thái:</strong> <span id="session-status"
                            class="badge {{ $session->status == 'open' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($session->status) }}</span>
                    </p>
                    <hr>
                    <h5>QR Code điểm danh</h5>
                    <div class="text-center mb-4" id="qr-container">
                        <div id="qr-svg-wrapper">
                            {!! $qrCode !!}
                        </div>
                        <p class="mt-2 text-muted small">Quét mã để điểm danh</p>
                        <div class="mt-2">
                            <a href="{{ route('attendance.checkin', ['token' => $session->qr_code_token]) }}"
                                target="_blank" class="btn btn-outline-primary btn-sm">
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
                        action="{{ route('lecturer.attendance.update_attendance', ['courseClass' => $courseClass->id, 'session' => $session->id]) }}"
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
                        <a href="{{ route('lecturer.attendance.index', $courseClass) }}" class="btn btn-secondary">Quay
                            lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrContainer = document.getElementById('qr-container');
            const statusElement = document.getElementById('session-status');

            // Function to fetch data
            function fetchAttendanceData() {
                // Add timestamp to prevent caching
                const url =
                    `{{ route('lecturer.attendance.get_data', ['courseClass' => $courseClass->id, 'session' => $session->id]) }}?t=${new Date().getTime()}`;

                fetch(url, {
                        headers: {
                            'Cache-Control': 'no-cache',
                            'Pragma': 'no-cache'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update Attendance List
                        data.attendances.forEach(att => {
                            // Find the radio button for this student and status
                            const radio = document.querySelector(
                                `input[name="attendance[${att.student_id}]"][value="${att.status}"]`
                            );
                            // Only update if server says present/late (prevent overwriting manual work)
                        if (radio && !radio.checked && att.status !== "absent") {
                                radio.checked = true;
                                // Force redraw of the label if needed (though CSS should handle it)
                                const label = document.querySelector(`label[for="${radio.id}"]`);
                                if (label) {
                                    // Toggle a class briefly to force repaint if necessary, but usually not needed
                                }
                            }
                        });

                        // Update Status Text
                        if (statusElement) {
                            statusElement.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(
                                1);
                            if (data.status === 'open') {
                                statusElement.className = 'badge bg-success';
                            } else {
                                statusElement.className = 'badge bg-secondary';
                            }
                        }

                        // Check Status for QR Code
                        if (data.status === 'closed' || data.is_expired) {
                            if (qrContainer && !qrContainer.innerHTML.includes('Đã hết thời gian')) {
                                qrContainer.innerHTML =
                                    '<div class="alert alert-warning">Đã hết thời gian điểm danh!</div>';
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching attendance data:', error));
            }

            // Function to refresh QR Code
            function refreshQrCode() {
                // Add timestamp to prevent caching
                const url =
                    `{{ route('lecturer.attendance.refresh_qr', ['courseClass' => $courseClass->id, 'session' => $session->id]) }}?t=${new Date().getTime()}`;

                fetch(url, {
                        headers: {
                            'Cache-Control': 'no-cache',
                            'Pragma': 'no-cache'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Session closed or error');
                        return response.json();
                    })
                    .then(data => {
                        if (data.qr_code && qrContainer) {
                            // Keep the "Quét mã..." text and button, just replace the SVG
                            // The SVG is usually the first child or we can wrap it. 
                            // Let's assume the server returns the raw SVG string.
                            // We need to be careful not to wipe the button.
                            // Best way: Wrap the QR code in a specific div in the HTML first.
                            const qrWrapper = document.getElementById('qr-svg-wrapper');
                            if (qrWrapper) {
                                qrWrapper.innerHTML = data.qr_code;
                            }
                        }
                    })
                    .catch(error => console.log('QR refresh stopped:', error));
            }

            // Poll attendance data every 3 seconds
            setInterval(fetchAttendanceData, 3000);

            // Refresh QR Code every 10 seconds
            setInterval(refreshQrCode, 10000);
        });
    </script>
@endsection
