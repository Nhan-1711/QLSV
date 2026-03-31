@extends('layouts.student')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Thông báo</h1>
        </div>

        <!-- Filters/Tabs -->
        <div class="card mb-4">
            <div class="card-header">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ $type === 'all' ? 'active' : '' }}"
                            href="{{ route('student.notifications.index', ['type' => 'all']) }}">Tất cả</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $type === 'admin' ? 'active' : '' }}"
                            href="{{ route('student.notifications.index', ['type' => 'admin']) }}">Từ Khoa/Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $type === 'lecturer' ? 'active' : '' }}"
                            href="{{ route('student.notifications.index', ['type' => 'lecturer']) }}">Từ Giảng viên</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                @if ($notifications->isEmpty())
                    <p class="text-muted text-center py-5">
                        @if ($type == 'admin')
                            Không có thông báo nào từ Khoa/Admin.
                        @elseif($type == 'lecturer')
                            Không có thông báo nào từ Giảng viên.
                        @else
                            Bạn chưa có thông báo nào.
                        @endif
                    </p>
                @else
                    <div class="list-group">
                        @foreach ($notifications as $notification)
                            <div
                                class="list-group-item list-group-item-action flex-column align-items-start border-0 mb-3 rounded {{ $notification->read_at ? 'bg-light text-muted' : 'bg-white shadow-sm border-start border-primary border-4' }}">
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
                                <div class="mb-1 mt-2 {{ $notification->read_at ? 'text-muted' : 'text-dark' }}">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($notification->data['message']), 150) }}
                                </div>
                                <div class="mt-2 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#notificationModal-{{ $notification->id }}">
                                        Xem chi tiết
                                    </button>
                                </div>

                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-user-circle me-1"></i>
                                        {{ $notification->data['sender_name'] }}
                                        <span
                                            class="badge bg-secondary ms-1">{{ $notification->data['sender_role'] }}</span>
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('modals')
        <!-- Modals (Placed outside the card/list-group to avoid z-index clipping) -->
        @foreach ($notifications as $notification)
            <div class="modal fade" id="notificationModal-{{ $notification->id }}" tabindex="-1" aria-hidden="true"
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
                                        Tải thệp tin đính kèm:
                                        {{ $notification->data['original_attachment_name'] ?? 'Tệp tin' }}
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
                        // Use relatedTarget to find the button that triggered the modal
                        var btn = event.relatedTarget;
                        if (btn) {
                            var listItem = btn.closest('.list-group-item');

                            if (listItem && !listItem.classList.contains('text-muted')) {
                                // Send AJAX request to mark as read
                                fetch('{{ route('student.notifications.index') }}/' + notificationId +
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

                                            var messageBody = listItem.querySelector('.mb-1.mt-2');
                                            if (messageBody) {
                                                messageBody.classList.remove('text-dark');
                                                messageBody.classList.add('text-muted');
                                            }

                                            // Update Badge Count
                                            var badge = document.getElementById(
                                                'notification-count-badge');
                                            if (badge) {
                                                var count = parseInt(badge.innerText);
                                                if (count > 0) {
                                                    count--;
                                                    if (count === 0) {
                                                        badge.remove();
                                                    } else {
                                                        badge.innerText = count;
                                                    }
                                                }
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
