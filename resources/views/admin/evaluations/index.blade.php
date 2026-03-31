@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800 fw-bold">Danh sách Đánh giá Học phần</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
                        <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.evaluations.index') }}" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="class_id" class="form-label fw-bold">Lọc theo Lớp học phần:</label>
                        <select name="class_id" id="class_id" class="form-select">
                            <option value="">-- Tất cả lớp --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    [{{ $class->course->code }}] {{ $class->course->name }} - {{ $class->name }} (ID: {{ $class->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Lọc
                        </button>
                    </div>
                </div>
            </form>

            <hr>
<div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sinh viên</th>
                            <th>Lớp học phần</th>
                            <th>Giảng viên</th>
                            <th>Điểm (Teaching/Support/Mat)</th>
                            <th>Nhận xét</th>
                            <th>Ngày</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evaluations as $eval)
                        <tr>
                            <td>{{ $eval->id }}</td>
                            <td>
                                {{ $eval->student->user->name ?? 'N/A' }} 
                                <small class="text-muted">({{ $eval->student->user->id ?? '' }})</small>
                            </td>
                            <td>
                                {{ $eval->courseClass->course->name ?? 'N/A' }}
                                <br>
                                <small>{{ $eval->courseClass->name ?? '' }}</small>
                            </td>
                            <td>{{ $eval->courseClass->lecturer->user->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $eval->teaching_rating }}</span>
                                <span class="badge bg-info">{{ $eval->support_rating }}</span>
                                <span class="badge bg-secondary">{{ $eval->material_rating }}</span>
                            </td>
                            <td>{{ Str::limit($eval->content, 50) }}</td>
                            <td>{{ $eval->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $evaluations->links() }}
        </div>
    </div>
</div>
@endsection