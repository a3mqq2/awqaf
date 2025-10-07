@extends('layouts.app')

@section('title', 'لوحة التحكم')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #3c5e7f 0%, #2e4a67 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stats-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        position: relative;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--bs-primary), var(--bs-info));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .stats-card:hover::before {
        opacity: 1;
    }
    
    .stats-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        position: relative;
        overflow: hidden;
    }
    
    .stats-icon::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: inherit;
        opacity: 0.5;
        filter: blur(20px);
    }
    
    .stats-number {
        font-size: 32px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 8px;
        background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stats-label {
        font-size: 14px;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 0;
    }
    
    .stats-trend {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .chart-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .chart-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .welcome-banner {
        background: var(--primary-gradient);
        border: none;
        border-radius: 20px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -150px;
        right: -100px;
    }
    
    .welcome-banner::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        bottom: -100px;
        left: -50px;
    }
    
    .table thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #495057;
        font-weight: 600;
        border: none;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 16px;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }
    
    .mini-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    
    .mini-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }
    
    .progress {
        height: 8px;
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 10px;
        transition: width 1s ease;
    }
    
    .card-header {
        background: transparent;
        border-bottom: 2px solid #f0f0f0;
        padding: 20px 24px;
    }
    
    .badge {
        padding: 6px 12px;
        font-weight: 600;
        border-radius: 8px;
    }
    
    .avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        font-size: 16px;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .quick-action-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .quick-action-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="row mb-4 mt-3 animate-fade-in-up">
    <div class="col-12">
        <div class="card welcome-banner">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="mb-2 text-white fw-bold">
                            <i class="ti ti-sparkles me-2"></i>
                            مرحباً بك، {{ auth()->user()->name }}
                        </h2>
                        <p class="mb-3 opacity-90 fs-5">
                            <i class="ti ti-calendar-event me-2"></i>
                            {{ now()->translatedFormat('l، d F Y') }}
                        </p>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-white bg-opacity-25 text-white">
                                <i class="ti ti-users me-1"></i>
                                {{ number_format($totalExaminees) }} ممتحن
                            </span>
                            <span class="badge bg-white bg-opacity-25 text-white">
                                <i class="ti ti-trending-up me-1"></i>
                                {{ number_format($recentExaminees) }} جديد هذا الأسبوع
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-end">
                        <a href="{{ route('examinees.create') }}" class="btn btn-light btn-lg shadow">
                            <i class="ti ti-plus me-2"></i>
                            إضافة ممتحن جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Stats Cards -->
<div class="row g-3 mb-4">
    <!-- Total Examinees -->
    <div class="col-xl-3 col-md-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="card stats-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stats-icon bg-primary bg-opacity-10">
                        <i class="ti ti-users text-primary"></i>
                    </div>
                    <span class="stats-trend bg-success bg-opacity-10 text-success">
                        <i class="ti ti-arrow-up"></i>
                        12%
                    </span>
                </div>
                <div>
                    <div class="stats-number text-primary">{{ number_format($totalExaminees) }}</div>
                    <div class="stats-label">إجمالي الممتحنين</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmed Examinees -->
    <div class="col-xl-3 col-md-6 animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="card stats-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stats-icon bg-success bg-opacity-10">
                        <i class="ti ti-circle-check text-success"></i>
                    </div>
                    <span class="stats-trend bg-success bg-opacity-10 text-success">
                        <i class="ti ti-check"></i>
                        {{ $totalExaminees > 0 ? number_format(($confirmedExaminees / $totalExaminees) * 100, 1) : 0 }}%
                    </span>
                </div>
                <div>
                    <div class="stats-number text-success">{{ number_format($confirmedExaminees) }}</div>
                    <div class="stats-label">ممتحنين مؤكدين</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Examinees -->
    <div class="col-xl-3 col-md-6 animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="card stats-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stats-icon bg-warning bg-opacity-10">
                        <i class="ti ti-clock text-warning"></i>
                    </div>
                    <span class="stats-trend bg-warning bg-opacity-10 text-warning">
                        <i class="ti ti-clock"></i>
                        {{ $totalExaminees > 0 ? number_format(($pendingExaminees / $totalExaminees) * 100, 1) : 0 }}%
                    </span>
                </div>
                <div>
                    <div class="stats-number text-warning">{{ number_format($pendingExaminees) }}</div>
                    <div class="stats-label">قيد التأكيد</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawn Examinees -->
    <div class="col-xl-3 col-md-6 animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="card stats-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stats-icon bg-danger bg-opacity-10">
                        <i class="ti ti-circle-x text-danger"></i>
                    </div>
                    <span class="stats-trend bg-danger bg-opacity-10 text-danger">
                        <i class="ti ti-x"></i>
                        {{ $totalExaminees > 0 ? number_format(($withdrawnExaminees / $totalExaminees) * 100, 1) : 0 }}%
                    </span>
                </div>
                <div>
                    <div class="stats-number text-danger">{{ number_format($withdrawnExaminees) }}</div>
                    <div class="stats-label">منسحبين</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="row g-3 mb-4">
    <!-- Male Examinees -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="mini-card card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-primary bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px; font-size: 22px;">
                    <i class="ti ti-gender-male text-primary"></i>
                </div>
                <div class="stats-number text-primary" style="font-size: 22px;">{{ number_format($maleExaminees) }}</div>
                <div class="stats-label" style="font-size: 12px;">ذكور</div>
            </div>
        </div>
    </div>

    <!-- Female Examinees -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="mini-card card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-danger bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px; font-size: 22px;">
                    <i class="ti ti-gender-female text-danger"></i>
                </div>
                <div class="stats-number text-danger" style="font-size: 22px;">{{ number_format($femaleExaminees) }}</div>
                <div class="stats-label" style="font-size: 12px;">إناث</div>
            </div>
        </div>
    </div>

    <!-- Recent Examinees -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="mini-card card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-success bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px; font-size: 22px;">
                    <i class="ti ti-trending-up text-success"></i>
                </div>
                <div class="stats-number text-success" style="font-size: 22px;">{{ number_format($recentExaminees) }}</div>
                <div class="stats-label" style="font-size: 12px;">آخر 7 أيام</div>
            </div>
        </div>
    </div>

    <!-- Offices -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="mini-card card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-info bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px; font-size: 22px;">
                    <i class="ti ti-building-store text-info"></i>
                </div>
                <div class="stats-number text-info" style="font-size: 22px;">{{ number_format($totalOffices) }}</div>
                <div class="stats-label" style="font-size: 12px;">المكاتب</div>
            </div>
        </div>
    </div>

    <!-- Clusters -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="mini-card card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-warning bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px; font-size: 22px;">
                    <i class="ti ti-users-group text-warning"></i>
                </div>
                <div class="stats-number text-warning" style="font-size: 22px;">{{ number_format($totalClusters) }}</div>
                <div class="stats-label" style="font-size: 12px;">التجمعات</div>
            </div>
        </div>
    </div>

    <!-- Users -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="mini-card card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-secondary bg-opacity-10 mx-auto mb-2" style="width: 50px; height: 50px; font-size: 22px;">
                    <i class="ti ti-user-cog text-secondary"></i>
                </div>
                <div class="stats-number text-secondary" style="font-size: 22px;">{{ number_format($totalUsers) }}</div>
                <div class="stats-label" style="font-size: 12px;">المستخدمين</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
    <!-- Status Distribution Chart -->
    <div class="col-xl-6">
        <div class="card chart-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="ti ti-chart-pie me-2 text-primary"></i>
                        توزيع الممتحنين حسب الحالة
                    </h5>
                    <span class="badge bg-primary">{{ number_format($totalExaminees) }}</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Gender Distribution Chart -->
    <div class="col-xl-6">
        <div class="card chart-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="ti ti-chart-donut me-2 text-info"></i>
                        توزيع الممتحنين حسب الجنس
                    </h5>
                    <span class="badge bg-info">{{ number_format($maleExaminees + $femaleExaminees) }}</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="genderChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-3 mb-4">
<!-- Examinees by Office -->
<div class="col-xl-6">
    <div class="card chart-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="ti ti-building-store me-2 text-success"></i>
                أكثر المكاتب نشاطاً
            </h5>
            <a href="{{ route('offices.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="ti ti-eye me-1"></i>
                عرض الكل
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">#</th>
                            <th>المكتب</th>
                            <th class="text-center">العدد</th>
                            <th width="200">النسبة</th>
                            <th class="text-center">عرض</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examineesByOffice as $index => $office)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                </td>
                                <td class="fw-semibold">
                                    <i class="ti ti-building-store me-2 text-muted"></i>
                                    {{ $office->office->name ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">
                                        {{ number_format($office->total) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1">
                                            <div class="progress-bar bg-gradient" 
                                                 style="width: {{ $totalExaminees > 0 ? ($office->total / $totalExaminees) * 100 : 0 }}%; background: linear-gradient(90deg, #667eea, #764ba2);"
                                                 role="progressbar">
                                            </div>
                                        </div>
                                        <span class="badge bg-light text-dark">
                                            {{ $totalExaminees > 0 ? number_format(($office->total / $totalExaminees) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('examinees.index', ['office_id' => $office->office_id]) }}" 
                                       class="btn btn-sm btn-outline-info rounded-pill"
                                       data-bs-toggle="tooltip"
                                       title="عرض الممتحنين">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="ti ti-inbox-off fs-1 d-block mb-2"></i>
                                    لا توجد بيانات
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Examinees by Cluster -->
<div class="col-xl-6">
    <div class="card chart-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="ti ti-users-group me-2 text-warning"></i>
                أكثر التجمعات نشاطاً
            </h5>
            <a href="{{ route('clusters.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="ti ti-eye me-1"></i>
                عرض الكل
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">#</th>
                            <th>التجمع</th>
                            <th class="text-center">العدد</th>
                            <th width="200">النسبة</th>
                            <th class="text-center">عرض</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examineesByCluster as $index => $cluster)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                </td>
                                <td class="fw-semibold">
                                    <i class="ti ti-users-group me-2 text-muted"></i>
                                    {{ $cluster->cluster->name ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info rounded-pill">
                                        {{ number_format($cluster->total) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1">
                                            <div class="progress-bar bg-gradient" 
                                                 style="width: {{ $totalExaminees > 0 ? ($cluster->total / $totalExaminees) * 100 : 0 }}%; background: linear-gradient(90deg, #4facfe, #00f2fe);"
                                                 role="progressbar">
                                            </div>
                                        </div>
                                        <span class="badge bg-light text-dark">
                                            {{ $totalExaminees > 0 ? number_format(($cluster->total / $totalExaminees) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('examinees.index', ['cluster_id' => $cluster->cluster_id]) }}" 
                                       class="btn btn-sm btn-outline-info rounded-pill"
                                       data-bs-toggle="tooltip"
                                       title="عرض الممتحنين">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="ti ti-inbox-off fs-1 d-block mb-2"></i>
                                    لا توجد بيانات
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

<!-- Latest Examinees -->
<div class="row">
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="ti ti-user-plus me-2 text-success"></i>
                    آخر الممتحنين المسجلين
                </h5>
                <a href="{{ route('examinees.index') }}" class="btn btn-sm btn-primary rounded-pill">
                    <i class="ti ti-list me-1"></i>
                    عرض جميع الممتحنين
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">الاسم</th>
                                <th>المكتب</th>
                                <th>التجمع</th>
                                <th>الحالة</th>
                                <th>تاريخ التسجيل</th>
                                <th class="text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestExaminees as $examinee)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3">
                                                <i class="ti ti-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $examinee->full_name }}</div>
                                                @if($examinee->national_id || $examinee->passport_no)
                                                    <small class="text-muted">
                                                        <i class="ti ti-id-badge me-1"></i>
                                                        {{ $examinee->national_id ?? $examinee->passport_no }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($examinee->office)
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="ti ti-building-store me-1"></i>
                                                {{ $examinee->office->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->cluster)
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                <i class="ti ti-users me-1"></i>
                                                {{ $examinee->cluster->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->status == 'confirmed')
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="ti ti-circle-check me-1"></i>
                                                مؤكد
                                            </span>
                                            @elseif($examinee->status == 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                <i class="ti ti-clock me-1"></i>
                                                قيد التأكيد
                                            </span>

                                            @elseif($examinee->status == 'under_review')
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                <i class="ti ti-clock me-1"></i>
                                                قيد المراجعه
                                            </span>
                                            @elseif($examinee->status == 'rejected')
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                <i class="ti ti-circle-x me-1"></i>
                                                مرفوض
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                <i class="ti ti-circle-x me-1"></i>
                                                منسحب
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ $examinee->created_at->format('Y-m-d') }}</div>
                                            <small class="text-muted">{{ $examinee->created_at->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('examinees.show', $examinee) }}" 
                                               class="btn btn-sm btn-outline-info rounded-start"
                                               data-bs-toggle="tooltip"
                                               title="عرض التفاصيل">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('examinees.edit', $examinee) }}" 
                                               class="btn btn-sm btn-outline-primary rounded-end"
                                               data-bs-toggle="tooltip"
                                               title="تعديل">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-users-off display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">لا توجد بيانات</h5>
                                            <p class="text-muted mb-3">لم يتم العثور على ممتحنين</p>
                                            <a href="{{ route('examinees.create') }}" class="btn btn-primary rounded-pill">
                                                <i class="ti ti-plus me-1"></i>
                                                إضافة أول ممتحن
                                            </a>
                                        </div>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    font: {
                        size: 13,
                        family: 'Cairo, sans-serif',
                        weight: '600'
                    },
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                titleFont: {
                    size: 14,
                    family: 'Cairo, sans-serif'
                },
                bodyFont: {
                    size: 13,
                    family: 'Cairo, sans-serif'
                }
            }
        }
    };

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['مؤكد', 'قيد التأكيد', 'منسحب'],
            datasets: [{
                data: [
                    {{ $statusData['confirmed'] }},
                    {{ $statusData['pending'] }},
                    {{ $statusData['withdrawn'] }}
                ],
                backgroundColor: [
                    'rgba(40, 199, 111, 0.85)',
                    'rgba(255, 193, 7, 0.85)',
                    'rgba(234, 84, 85, 0.85)'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            ...commonOptions,
            cutout: '65%',
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    ...commonOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Gender Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'pie',
        data: {
            labels: ['ذكور', 'إناث'],
            datasets: [{
                data: [
                    {{ $genderData['male'] }},
                    {{ $genderData['female'] }}
                ],
                backgroundColor: [
                    'rgba(102, 126, 234, 0.85)',
                    'rgba(237, 100, 166, 0.85)'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    ...commonOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Animate numbers on scroll
    const animateNumbers = () => {
        const numbers = document.querySelectorAll('.stats-number');
        numbers.forEach(num => {
            const finalValue = parseInt(num.textContent.replace(/,/g, ''));
            if (finalValue) {
                let currentValue = 0;
                const increment = finalValue / 50;
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        currentValue = finalValue;
                        clearInterval(timer);
                    }
                    num.textContent = Math.floor(currentValue).toLocaleString('en-US');
                }, 20);
            }
        });
    };

    // Trigger animation
    animateNumbers();
});
</script>
@endpush