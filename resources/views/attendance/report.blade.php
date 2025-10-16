@extends('layouts.app')

@section('title', 'تقرير الحضور')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">تسجيل الحضور</a></li>
    <li class="breadcrumb-item active">تقرير الحضور</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <!-- معلومات اللجنة -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-clipboard-check me-2"></i>
                        تقرير حضور اللجنة: {{ $committee->name }}
                    </h5>
                    <a href="{{ route('attendance.print', ['committee_id' => $committee->id]) }}" 
                       class="btn btn-light btn-sm" 
                       target="_blank">
                        <i class="ti ti-printer me-1"></i>طباعة التقرير
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong><i class="ti ti-users-group me-1"></i>اسم اللجنة:</strong> 
                            {{ $committee->name }}
                        </p>
                        <p class="mb-0">
                            <strong><i class="ti ti-map-pin me-1"></i>التجمع:</strong> 
                            <span class="badge bg-primary">{{ $committee->cluster->name }}</span>
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p class="mb-2">
                            <strong><i class="ti ti-calendar me-1"></i>التاريخ:</strong> 
                            {{ now()->format('Y-m-d') }}
                        </p>
                        <p class="mb-0">
                            <strong><i class="ti ti-clock me-1"></i>الوقت:</strong> 
                            {{ now()->format('H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- إحصائيات الحضور -->
        <div class="row mb-3">
            <!-- إجمالي الممتحنين -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="ti ti-users display-4 text-primary mb-2"></i>
                        <h3 class="mb-1">{{ $totalExaminees }}</h3>
                        <p class="text-muted mb-0">إجمالي الممتحنين</p>
                    </div>
                </div>
            </div>

            <!-- الحاضرين -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="ti ti-circle-check display-4 text-success mb-2"></i>
                        <h3 class="mb-1">{{ $attendedCount }}</h3>
                        <p class="text-muted mb-0">حضر</p>
                    </div>
                </div>
            </div>

            <!-- الغائبين -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="ti ti-circle-x display-4 text-danger mb-2"></i>
                        <h3 class="mb-1">{{ $notAttendedCount }}</h3>
                        <p class="text-muted mb-0">لم يحضر</p>
                    </div>
                </div>
            </div>

            <!-- نسبة الحضور -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="ti ti-percentage display-4 text-info mb-2"></i>
                        <h3 class="mb-1">{{ $attendancePercentage }}%</h3>
                        <p class="text-muted mb-0">نسبة الحضور</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- قائمة الممتحنين -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="ti ti-list me-2"></i>
                    قائمة الممتحنين ({{ $examinees->total() }})
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الاسم الكامل</th>
                                <th>الرقم الوطني</th>
                                <th>رقم الهاتف</th>
                                <th>الرواية</th>
                                <th>الحالة</th>
                                <th>وقت الحضور</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examinees as $examinee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-user me-2 text-muted"></i>
                                            <strong>{{ $examinee->full_name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="ti ti-id-badge me-1 text-muted"></i>
                                        {{ $examinee->national_id ?? $examinee->passport_no }}
                                    </td>
                                    <td>
                                        <i class="ti ti-phone me-1 text-muted"></i>
                                        {{ $examinee->phone ?? 'غير متوفر' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light-info text-info">
                                            {{ $examinee->narration->name ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($examinee->is_attended)
                                            <span class="badge bg-success">
                                                <i class="ti ti-circle-check me-1"></i>حضر
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="ti ti-circle-x me-1"></i>لم يحضر
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->attended_at)
                                            <small>
                                                <i class="ti ti-clock me-1"></i>
                                                {{ $examinee->attended_at->format('Y-m-d H:i:s') }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="ti ti-users-off display-4 text-muted mb-2"></i>
                                        <p class="text-muted mb-0">لا يوجد ممتحنين مؤكدين في هذه اللجنة</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($examinees->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            عرض {{ $examinees->firstItem() }} إلى {{ $examinees->lastItem() }} من أصل {{ $examinees->total() }} نتيجة
                        </div>
                        <div>
                            {{ $examinees->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- أزرار العمليات -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>رجوع لتسجيل الحضور
                    </a>
                    <a href="{{ route('attendance.print', ['committee_id' => $committee->id]) }}" 
                       class="btn btn-primary" 
                       target="_blank">
                        <i class="ti ti-printer me-1"></i>طباعة التقرير
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection