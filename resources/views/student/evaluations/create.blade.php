@extends('layouts.student')

@section('title', 'Đánh giá học phần')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold">Đánh giá Học phần: {{ $courseClass->course->name }} - {{ $courseClass->name }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('student.evaluations.store', $courseClass->id) }}" method="POST">
                        @csrf
                        
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-1"></i> Ý kiến của bạn giúp Nhà trường nâng cao chất lượng giảng dạy.
                        </div>

                        <h6 class="fw-bold mb-3 text-secondary text-uppercase small">Tiêu chí đánh giá</h6>
                        
                        <!-- Teaching Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">1. Chất lượng giảng dạy</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rating-group">
                                    @for($i=1; $i<=5; $i++)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="teaching_rating" id="teach{{$i}}" value="{{$i}}" required>
                                            <label class="form-check-label" for="teach{{$i}}">{{$i}} <i class="fas fa-star text-warning"></i></label>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Support Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">2. Hỗ trợ sinh viên</label>
                            <div class="rating-group">
                                @for($i=1; $i<=5; $i++)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="support_rating" id="supp{{$i}}" value="{{$i}}" required>
                                        <label class="form-check-label" for="supp{{$i}}">{{$i}} <i class="fas fa-star text-warning"></i></label>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Material Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">3. Tài liệu học tập</label>
                            <div class="rating-group">
                                @for($i=1; $i<=5; $i++)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="material_rating" id="mat{{$i}}" value="{{$i}}" required>
                                        <label class="form-check-label" for="mat{{$i}}">{{$i}} <i class="fas fa-star text-warning"></i></label>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Góp ý chi tiết</label>
                            <textarea name="content" class="form-control" rows="4" placeholder="Nhập nhận xét của bạn về giảng viên và môn học... (Vui lòng dùng từ ngữ lịch sự)"></textarea>
                            <div class="form-text text-muted">Hệ thống sẽ tự động chặn các từ ngữ không phù hợp.</div>
                        </div>

                        <!-- Anonymous -->
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" name="is_anonymous" id="anon" value="1" checked>
                            <label class="form-check-label" for="anon">Gửi đánh giá ẩn danh (Giảng viên sẽ không thấy tên bạn)</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url()->previous() }}" class="btn btn-light">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-paper-plane me-1"></i> Gửi đánh giá</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection