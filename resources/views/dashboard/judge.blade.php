@extends('layouts.app')

@section('title', 'لوحة المحكم')

@push('styles')
<style>
    .stats-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .stats-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }
    
    .stats-number {
        font-size: 32px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 8px;
    }
    
    .stats-label {
        font-size: 14px;
        font-weight: 500;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="row mb-4 mt-3">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 20px; color: white;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="mb-2 text-white fw-bold">
                            <i class="ti ti-gavel me-2"></i>
                            مرحباً بك، {{ auth()->user()->name }}
                        </h2>
                        <p class="mb-0 opacity-90 fs-5">
                            لوحة تحكم المحكم - إدارة التقييمات
                        </p>
                    </div>
                    <div class="col-lg-4 text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('judge.dashboard') }}" class="btn btn-light">
                                <i class="ti ti-book me-1"></i>
                                المنهج العلمي
                            </a>
                            <a href="{{ route('judge.oral.dashboard') }}" class="btn btn-outline-light">
                                <i class="ti ti-microphone me-1"></i>
                                الشفهي
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Stats -->
<div class="row g-3 mb-4">
    <!-- Total Evaluations -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stats-icon bg-primary bg-opacity-10 text-white">
                        <i class="ti ti-clipboard-check text-primary"></i>
                    </div>
                </div>
                <div>
                    <div class="stats-number text-primary">{{ number_format($writtenEvaluations + $oralEvaluations) }}</div>
                    <div class="stats-label">إجمالي التقييمات</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Written Evaluations -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stats-icon bg-info bg-opacity-10">
                        <i class="ti ti-book text-info"></i>
                    </div>
                </div>
                <div>
                    <div class="stats-number text-info">{{ number_format($writtenEvaluations) }}</div>
                    <div class="stats-label">المنهج العلمي</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Oral Evaluations -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stats-icon bg-success bg-opacity-10">
                        <i class="ti ti-microphone text-success"></i>
                    </div>
                </div>
                <div>
                    <div class="stats-number text-success">{{ number_format($oralEvaluations) }}</div>
                    <div class="stats-label">الاختبار الشفهي</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stats-icon bg-warning bg-opacity-10">
                        <i class="ti ti-clock text-warning"></i>
                    </div>
                </div>
                <div>
                    <div class="stats-number text-warning">{{ number_format($availableForWritten + $availableForOral) }}</div>
                    <div class="stats-label">في الانتظار</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="ti ti-book me-2"></i>
                    المنهج العلمي
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <h3 class="text-success mb-1">{{ number_format($writtenCompleted) }}</h3>
                            <small>مكتمل</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <h3 class="text-info mb-1">{{ number_format($writtenInProgress) }}</h3>
                            <small>جاري التقييم</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                            <h3 class="text-warning mb-1">{{ number_format($writtenPending) }}</h3>
                            <small>قيد الانتظار</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                            <h3 class="text-primary mb-1">{{ number_format($averageWrittenScore, 1) }}</h3>
                            <small>متوسط الدرجات / 40</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="ti ti-microphone me-2"></i>
                    الاختبار الشفهي
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <h3 class="text-success mb-1">{{ number_format($oralCompleted) }}</h3>
                            <small>مكتمل</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <h3 class="text-info mb-1">{{ number_format($oralInProgress) }}</h3>
                            <small>جاري التقييم</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                            <h3 class="text-warning mb-1">{{ number_format($oralPending) }}</h3>
                            <small>قيد الانتظار</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                            <h3 class="text-primary mb-1">{{ number_format($averageOralScore, 1) }}</h3>
                            <small>متوسط الدرجات / 100</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Evaluations -->
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-book me-2"></i>
                    آخر تقييمات المنهج العلمي
                </h5>
                <a href="{{ route('judge.completed') }}" class="btn btn-sm btn-primary">عرض الكل</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <tbody>
                            @forelse($latestWrittenEvaluations as $eval)
                                <tr>
                                    <td>
                                        <strong>{{ $eval->examinee->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $eval->committee->name }}</small>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-primary">{{ number_format($eval->score, 1) }}</strong> / 40
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">
                                        لا توجد تقييمات
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-microphone me-2"></i>
                    آخر التقييمات الشفهية
                </h5>
                <a href="{{ route('judge.oral.completed') }}" class="btn btn-sm btn-success">عرض الكل</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <tbody>
                            @forelse($latestOralEvaluations as $eval)
                                <tr>
                                    <td>
                                        <strong>{{ $eval->examinee->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $eval->committee->name }}</small>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-success">{{ number_format($eval->final_score, 1) }}</strong> / 100
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">
                                        لا توجد تقييمات
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsectionm