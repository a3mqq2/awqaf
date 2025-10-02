@extends('layouts.app')

@section('title', 'لوحة التحكم')



@push('styles')
<style>
    .stats-card {
        border: none;
        border-radius: 12px;
        transition: transform 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .stats-number {
        font-size: 28px;
        font-weight: bold;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stats-label {
        font-size: 14px;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .chart-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #3c5e7f 0%, #2e4c6a 100%);
        border: none;
        border-radius: 12px;
        color: white;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="row mb-4 mt-3">
    <div class="col-12">
        <div class="card welcome-banner">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-2 text-white">السلام عليكم مرحباََ، {{ auth()->user()->name }}</h3>
                        <p class="mb-0 opacity-75">
                            <i class="ti ti-calendar me-2"></i>
                            {{ now()->translatedFormat('l، d F Y') }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('examinees.create') }}" class="btn btn-light">
                            <i class="ti ti-plus me-1"></i>
                            إضافة ممتحن جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <!-- Total Examinees -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-light-primary text-primary me-3">
                        <i class="ti ti-users"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stats-number text-primary">{{ number_format($totalExaminees) }}</div>
                        <div class="stats-label text-muted">إجمالي الممتحنين</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmed Examinees -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-light-success text-success me-3">
                        <i class="ti ti-circle-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stats-number text-success">{{ number_format($confirmedExaminees) }}</div>
                        <div class="stats-label text-muted">ممتحنين مؤكدين</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Examinees -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-light-warning text-warning me-3">
                        <i class="ti ti-clock"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stats-number text-warning">{{ number_format($pendingExaminees) }}</div>
                        <div class="stats-label text-muted">قيد التأكيد</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawn Examinees -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-light-danger text-danger me-3">
                        <i class="ti ti-circle-x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stats-number text-danger">{{ number_format($withdrawnExaminees) }}</div>
                        <div class="stats-label text-muted">منسحبين</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="row g-3 mb-4">
    <!-- Male Examinees -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-light-primary text-primary mx-auto mb-2" style="width: 45px; height: 45px; font-size: 20px;">
                    <i class="ti ti-gender-male"></i>
                </div>
                <div class="stats-number text-primary" style="font-size: 20px;">{{ number_format($maleExaminees) }}</div>
                <div class="stats-label text-muted" style="font-size: 12px;">ذكور</div>
            </div>
        </div>
    </div>

    <!-- Female Examinees -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-light-danger text-danger mx-auto mb-2" style="width: 45px; height: 45px; font-size: 20px;">
                    <i class="ti ti-gender-female"></i>
                </div>
                <div class="stats-number text-danger" style="font-size: 20px;">{{ number_format($femaleExaminees) }}</div>
                <div class="stats-label text-muted" style="font-size: 12px;">إناث</div>
            </div>
        </div>
    </div>

    <!-- Recent Examinees -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-light-success text-success mx-auto mb-2" style="width: 45px; height: 45px; font-size: 20px;">
                    <i class="ti ti-trending-up"></i>
                </div>
                <div class="stats-number text-success" style="font-size: 20px;">{{ number_format($recentExaminees) }}</div>
                <div class="stats-label text-muted" style="font-size: 12px;">آخر 7 أيام</div>
            </div>
        </div>
    </div>

    <!-- Offices -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-light-info text-info mx-auto mb-2" style="width: 45px; height: 45px; font-size: 20px;">
                    <i class="ti ti-building-store"></i>
                </div>
                <div class="stats-number text-info" style="font-size: 20px;">{{ number_format($totalOffices) }}</div>
                <div class="stats-label text-muted" style="font-size: 12px;">المكاتب</div>
            </div>
        </div>
    </div>

    <!-- Clusters -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-light-warning text-warning mx-auto mb-2" style="width: 45px; height: 45px; font-size: 20px;">
                    <i class="ti ti-users-group"></i>
                </div>
                <div class="stats-number text-warning" style="font-size: 20px;">{{ number_format($totalClusters) }}</div>
                <div class="stats-label text-muted" style="font-size: 12px;">التجمعات</div>
            </div>
        </div>
    </div>

    <!-- Users -->
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card">
            <div class="card-body text-center py-3">
                <div class="stats-icon bg-light-secondary text-secondary mx-auto mb-2" style="width: 45px; height: 45px; font-size: 20px;">
                    <i class="ti ti-user-cog"></i>
                </div>
                <div class="stats-number text-secondary" style="font-size: 20px;">{{ number_format($totalUsers) }}</div>
                <div class="stats-label text-muted" style="font-size: 12px;">المستخدمين</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Status Distribution Chart -->
    <div class="col-xl-6">
        <div class="card chart-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-chart-pie me-2"></i>
                    توزيع الممتحنين حسب الحالة
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Gender Distribution Chart -->
    <div class="col-xl-6">
        <div class="card chart-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-chart-donut me-2"></i>
                    توزيع الممتحنين حسب الجنس
                </h5>
            </div>
            <div class="card-body">
                <canvas id="genderChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Examinees by Office -->
    <div class="col-xl-6">
        <div class="card chart-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-building-store me-2"></i>
                    أكثر المكاتب نشاطاً
                </h5>
                <a href="{{ route('examinees.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>المكتب</th>
                                <th class="text-center">عدد الممتحنين</th>
                                <th width="200">النسبة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examineesByOffice as $office)
                                <tr>
                                    <td>{{ $office->office->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-light-primary text-primary">
                                            {{ number_format($office->total) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-primary" 
                                                 role="progressbar" 
                                                 style="width: {{ ($office->total / $totalExaminees) * 100 }}%"
                                                 aria-valuenow="{{ $office->total }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $totalExaminees }}">
                                                {{ number_format(($office->total / $totalExaminees) * 100, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">لا توجد بيانات</td>
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
                <h5 class="mb-0">
                    <i class="ti ti-users-group me-2"></i>
                    أكثر التجمعات نشاطاً
                </h5>
                <a href="{{ route('examinees.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>التجمع</th>
                                <th class="text-center">عدد الممتحنين</th>
                                <th width="200">النسبة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examineesByCluster as $cluster)
                                <tr>
                                    <td>{{ $cluster->cluster->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-light-info text-info">
                                            {{ number_format($cluster->total) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-info" 
                                                 role="progressbar" 
                                                 style="width: {{ ($cluster->total / $totalExaminees) * 100 }}%"
                                                 aria-valuenow="{{ $cluster->total }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $totalExaminees }}">
                                                {{ number_format(($cluster->total / $totalExaminees) * 100, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">لا توجد بيانات</td>
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
                <h5 class="mb-0">
                    <i class="ti ti-user-plus me-2"></i>
                    آخر الممتحنين المسجلين
                </h5>
                <a href="{{ route('examinees.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>المكتب</th>
                                <th>التجمع</th>
                                <th>الحالة</th>
                                <th>تاريخ التسجيل</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestExaminees as $examinee)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="ti ti-user"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $examinee->full_name ?? $examinee->first_name }}</strong>
                                                @if($examinee->national_id || $examinee->passport_no)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $examinee->national_id ?? $examinee->passport_no }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($examinee->office)
                                            <span class="badge bg-light-info text-info">
                                                {{ $examinee->office->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->cluster)
                                            <span class="badge bg-light-primary text-primary">
                                                {{ $examinee->cluster->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->status == 'confirmed')
                                            <span class="badge bg-light-success text-success">
                                                <i class="ti ti-circle-check me-1"></i>
                                                مؤكد
                                            </span>
                                        @elseif($examinee->status == 'pending')
                                            <span class="badge bg-light-warning text-warning">
                                                <i class="ti ti-clock me-1"></i>
                                                قيد التأكيد
                                            </span>
                                        @else
                                            <span class="badge bg-light-danger text-danger">
                                                <i class="ti ti-circle-x me-1"></i>
                                                منسحب
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $examinee->created_at->format('Y-m-d') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $examinee->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('examinees.show', $examinee) }}" class="btn btn-sm btn-outline-info">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('examinees.edit', $examinee) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
                    'rgba(40, 199, 111, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(234, 84, 85, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 13,
                            family: 'Cairo, sans-serif'
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
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(237, 100, 166, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 13,
                            family: 'Cairo, sans-serif'
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush