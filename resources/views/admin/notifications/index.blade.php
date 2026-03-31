@extends('layouts.admin')

@section('title', 'Quản lý thông báo')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý thông báo</h2>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo thông báo mới
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Đã gửi</h5>
        </div>
        <div class="card-body">
            @if ($sentNotifications->isEmpty())
                <p class="text-muted text-center">Chưa có thông báo nào được gửi.</p>
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
                                    <td>{{ \Illuminate\Support\Str::limit(strip_tags($data['message'] ?? ''), 50) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}</td>
                                    <td><span class="badge bg-info">{{ $notification->receiver_count }} người</span></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                            data-bs-target="#sentModal-{{ $loop->index }}">
                                            Xem
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $sentNotifications->links() }}
            @endif
        </div>
    </div>

    @push('modals')
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
                                    Gửi lúc: {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="notification-content">
                                {!! $data['message'] ?? '' !!}
                            </div>
                            @if (!empty($data['attachment_url']))
                                <div class="mt-3 pt-3 border-top">
                                    <a href="{{ $data['attachment_url'] }}" target="_blank" class="btn btn-primary">
                                        <i class="fas fa-paperclip"></i>
                                        Tải file: {{ $data['original_attachment_name'] ?? 'File đính kèm' }}
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
@endsection
