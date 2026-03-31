@extends('layouts.lecturer')

@section('title', 'Lịch dạy')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0 text-primary"><i class="fas fa-calendar-alt me-2"></i>Thời khóa biểu giảng dạy</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered text-center mb-0 align-middle">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th style="width: 100px;">Buổi / Tiết</th>
                            <th>Thứ 2</th>
                            <th>Thứ 3</th>
                            <th>Thứ 4</th>
                            <th>Thứ 5</th>
                            <th>Thứ 6</th>
                            <th>Thứ 7</th>
                            <th>Chủ nhật</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Morning Session (Periods 1-5) -->
                        <tr class="table-light">
                            <td colspan="8"
                                class="fw-bold text-start ps-4 text-uppercase text-primary bg-light border-bottom-0 py-3">
                                <i class="fas fa-sun me-2 text-warning"></i>Buổi Sáng
                            </td>
                        </tr>
                        @for ($i = 1; $i <= 5; $i++)
                            <tr>
                                <td class="fw-bold text-secondary bg-light align-middle" style="width: 100px;">Tiết
                                    {{ $i }}</td>
                                @foreach (['2', '3', '4', '5', '6', '7', 'CN'] as $day)
                                    @php
                                        $class = $classes->first(function ($c) use ($day, $i) {
                                            return $c->day_of_week == $day &&
                                                $i >= $c->period_from &&
                                                $i <= $c->period_to;
                                        });

                                        $isStart = $class && $class->period_from == $i;
                                        $isContinuation = $class && $class->period_from < $i;
                                    @endphp

                                    @if ($isStart)
                                        <td rowspan="{{ $class->period_to - $class->period_from + 1 }}"
                                            class="align-middle p-0 border position-relative" style="height: 1px;">
                                            <div class="d-flex flex-column justify-content-center p-2 h-100 w-100"
                                                style="background-color: #cffafe; color: #155e75; min-height: 100%;">
                                                <div class="fw-bold text-info-emphasis mb-1">{{ $class->course->name }}
                                                </div>
                                                <div class="small mb-1">
                                                    <span class="fw-semibold">{{ $class->course->code }}</span>
                                                    <span class="mx-1">|</span>
                                                    <span>Tiết {{ $class->period_from }}-{{ $class->period_to }}</span>
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $class->classroom }}
                                                </div>
                                            </div>
                                        </td>
                                    @elseif (!$isContinuation)
                                        <td class="align-middle border"></td>
                                    @endif
                                @endforeach
                            </tr>
                        @endfor

                        <!-- Afternoon Session (Periods 6-10) -->
                        <tr class="table-light">
                            <td colspan="8"
                                class="fw-bold text-start ps-4 text-uppercase text-primary bg-light border-bottom-0 py-3 border-top">
                                <i class="fas fa-moon me-2 text-info"></i>Buổi Chiều
                            </td>
                        </tr>
                        @for ($i = 6; $i <= 10; $i++)
                            <tr>
                                <td class="fw-bold text-secondary bg-light align-middle">Tiết {{ $i }}</td>
                                @foreach (['2', '3', '4', '5', '6', '7', 'CN'] as $day)
                                    @php
                                        $class = $classes->first(function ($c) use ($day, $i) {
                                            return $c->day_of_week == $day &&
                                                $i >= $c->period_from &&
                                                $i <= $c->period_to;
                                        });

                                        $isStart = $class && $class->period_from == $i;
                                        $isContinuation = $class && $class->period_from < $i;
                                    @endphp

                                    @if ($isStart)
                                        <td rowspan="{{ $class->period_to - $class->period_from + 1 }}"
                                            class="align-middle p-0 border position-relative" style="height: 1px;">
                                            <div class="d-flex flex-column justify-content-center p-2 h-100 w-100"
                                                style="background-color: #bae6fd; color: #075985; min-height: 100%;">
                                                <div class="fw-bold text-info-emphasis mb-1">{{ $class->course->name }}
                                                </div>
                                                <div class="small mb-1">
                                                    <span class="fw-semibold">{{ $class->course->code }}</span>
                                                    <span class="mx-1">|</span>
                                                    <span>Tiết {{ $class->period_from }}-{{ $class->period_to }}</span>
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $class->classroom }}
                                                </div>
                                            </div>
                                        </td>
                                    @elseif (!$isContinuation)
                                        <td class="align-middle border"></td>
                                    @endif
                                @endforeach
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
