@extends('layouts.lecturer')

@section('title', 'Quản lý thông báo')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý thông báo</h2>
        <a href="{{ route('lecturer.notifications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo thông báo mới
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="notificationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="received-tab" data-bs-toggle="tab" data-bs-target="#received"
                        type="button" role="tab" aria-controls="received" aria-selected="true">Hộp thư đến</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button"
                        role="tab" aria-controls="sent" aria-selected="false">Đã gửi</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="notificationTabsContent">
                <!-- Received Notifications -->
                <div class="tab-pane fade show active" id="received" role="tabpanel" aria-labelledby="received-tab">
                    @if ($receivedNotifications->isEmpty())
                        <p class="text-muted text-center py-3">Bạn chưa nhận được thông báo nào.</p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($receivedNotifications as $notification)
                                <div
                                    class="list-group-item {{ $notification->read_at ? 'bg-light text-muted' : 'bg-white shadow-sm border-start border-primary border-4' }} border-0 mb-3 rounded">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h5 class="mb-1 {{ $notification->read_at ? 'text-muted' : 'fw-bold text-dark' }}">
                                            @if (!$notification->read_at)
                                                <i class="fas fa-circle text-primary me-2" style="font-size: 10px;"></i>
                                            @endif
                                            {{ $notification->data['title'] }}
                                        </h5>
                                        <small class="{{ $notification->read_at ? 'text-muted' : 'text-primary fw-bold' }}">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="mb-1 {{ $notification->read_at ? 'text-muted' : 'text-dark' }}">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($notification->data['message']), 150) }}
                                    </div>
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#receivedModal-{{ $notification->id }}">
                                            Xem chi tiết
                                        </button>
                                    </div>

                                    <small class="text-muted">Từ: {{ $notification->data['sender_name'] }}
                                        ({{ $notification->data['sender_role'] }})
                                    </small>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            {{ $receivedNotifications->appends(['sent_page' => $sentNotifications->currentPage()])->links() }}
                        </div>
                    @endif
                </div>

                <!-- Sent Notifications -->
                <div class="tab-pane fade" id="sent" role="tabpanel" aria-labelledby="sent-tab">
                    @if ($sentNotifications->isEmpty())
                        <p class="text-muted text-center py-3">Bạn chưa gửi thông báo nào.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Nội dung</th>
                                        <th>Ngày gửi</th>
                                        <th>Người nhận</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sentNotifications as $notification)
                                        @php
                                            $data = json_decode($notification->data, true);
                                        @endphp
                                        <tr>
                                            <td>{{ $data['title'] ?? 'N/A' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit(strip_tags($data['message'] ?? ''), 50) }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                                            </td>
                                            <td><span class="badge bg-info">{{ $notification->receiver_count }}
                                                    người</span></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal" data-bs-target="#sentModal-{{ $loop->index }}">
                                                    Xem
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $sentNotifications->appends(['received_page' => $receivedNotifications->currentPage()])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('modals')
        <!-- Modals (Received) -->
        @foreach ($receivedNotifications as $notification)
            <div class="modal fade" id="receivedModal-{{ $notification->id }}" tabindex="-1" aria-hidden="true"
                data-notification-id="{{ $notification->id }}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $notification->data['title'] }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <small class="text-muted">
                                    Từ: <strong>{{ $notification->data['sender_name'] }}</strong>
                                    ({{ $notification->data['sender_role'] }})
                                    -
                                    {{ $notification->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="notification-content">
                                {!! $notification->data['message'] !!}
                            </div>
                            @if (!empty($notification->data['attachment_url']))
                                <div class="mt-3 pt-3 border-top">
                                    <a href="{{ $notification->data['attachment_url'] }}" target="_blank"
                                        class="btn btn-primary">
                                        <i class="fas fa-paperclip"></i>
                                        Tải file đính kèm:
                                        {{ $notification->data['original_attachment_name'] ?? 'File đính kèm' }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modals (Sent) -->
        @foreach ($sentNotifications as $notification)
            @php
                $data = json_decode($notification->data, true);
            @endphp
            <div class="modal fade" id="sentModal-{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $data['title'] ?? 'Chi tiết' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <small class="text-muted">
                                    Gửi lúc:
                                    {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="notification-content">
                                {!! $data['message'] ?? '' !!}
                            </div>
                            @if (!empty($data['attachment_url']))
                                <div class="mt-3 pt-3 border-top">
                                    <a href="{{ $data['attachment_url'] }}" target="_blank" class="btn btn-primary">
                                        <i class="fas fa-paperclip"></i>
                                        Tải file:
                                        {{ $data['original_attachment_name'] ?? 'File đính kèm' }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endpush
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modals = document.querySelectorAll('.modal');
                modals.forEach(function(modal) {
                    modal.addEventListener('show.bs.modal', function(event) {
                        var notificationId = this.getAttribute('data-notification-id');
                        // Use relatedTarget to find the button
                        var btn = event.relatedTarget;
                        if (btn && notificationId) {
                            var listItem = btn.closest('.list-group-item');

                            if (listItem && !listItem.classList.contains('text-muted')) {
                                // Send AJAX request to mark as read
                                fetch('{{ route('lecturer.notifications.index') }}/' + notificationId +
                                        '/read', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            }
                                        })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Update UI to show as read
                                            // Remove unread styles
                                            listItem.classList.remove('bg-white', 'shadow-sm',
                                                'border-start', 'border-primary', 'border-4');

                                            // Add read styles
                                            listItem.classList.add('bg-light', 'text-muted');

                                            var title = listItem.querySelector('h5');
                                            if (title) {
                                                title.classList.remove('fw-bold', 'text-dark');
                                                title.classList.add('text-muted');

                                                // Remove Dot Icon
                                                var icon = title.querySelector('i.fa-circle');
                                                if (icon) icon.remove();
                                            }

                                            // Un-highlight time
                                            var time = listItem.querySelector('small');
                                            if (time) {
                                                time.classList.remove('text-primary', 'fw-bold');
                                                time.classList.add('text-muted');
                                            }

                                            var messageBody = listItem.querySelector('.mb-1');
                                            if (messageBody) {
                                                messageBody.classList.remove('text-dark');
                                                messageBody.classList.add('text-muted');
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
