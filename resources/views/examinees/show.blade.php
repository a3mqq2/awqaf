@extends('layouts.app')

@section('title', 'تفاصيل الممتحن')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('examinees.index') }}">الممتحنين</a></li>
    <li class="breadcrumb-item active">تفاصيل الممتحن</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="ti ti-user fs-1"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $examinee->full_name ?? $examinee->first_name }}</h4>
                            <div class="d-flex gap-2">
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
                                <span class="badge bg-light-secondary text-secondary">
                                    <i class="ti ti-hash me-1"></i>
                                    {{ $examinee->id }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('examinees.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-right me-1"></i>
                            رجوع
                        </a>
                        <a href="{{ route('examinees.edit', $examinee) }}" class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i>
                            تعديل
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="ti ti-trash me-1"></i>
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- البيانات الشخصية -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-user me-2 text-primary"></i>
                            البيانات الشخصية
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">الاسم الأول</label>
                                <p class="fw-semibold mb-0">{{ $examinee->first_name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">اسم الأب</label>
                                <p class="fw-semibold mb-0">{{ $examinee->father_name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">اسم الجد</label>
                                <p class="fw-semibold mb-0">{{ $examinee->grandfather_name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">اللقب</label>
                                <p class="fw-semibold mb-0">{{ $examinee->last_name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-gender-male me-1"></i>
                                    الجنس
                                </label>
                                <p class="fw-semibold mb-0">
                                    @if($examinee->gender == 'male')
                                        ذكر
                                    @elseif($examinee->gender == 'female')
                                        أنثى
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-calendar-event me-1"></i>
                                    تاريخ الميلاد
                                </label>
                                <p class="fw-semibold mb-0">
                                    {{ $examinee->birth_date ? \Carbon\Carbon::parse($examinee->birth_date)->format('Y-m-d') : '-' }}
                                </p>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-flag me-1"></i>
                                    الجنسية
                                </label>
                                <p class="fw-semibold mb-0">{{ $examinee->nationality ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات الهوية والاتصال -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-id me-2 text-info"></i>
                            معلومات الهوية والاتصال
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-id-badge me-1"></i>
                                    الرقم الوطني
                                </label>
                                <p class="fw-semibold mb-0">{{ $examinee->national_id ?? '-' }}</p>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-passport me-1"></i>
                                    رقم الجواز
                                </label>
                                <p class="fw-semibold mb-0">{{ $examinee->passport_no ?? '-' }}</p>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-muted small mb-1">
                                    <i class="ti ti-phone me-1"></i>
                                    رقم الهاتف
                                </label>
                                <p class="fw-semibold mb-0">
                                    @if($examinee->phone)
                                        <a href="tel:{{ $examinee->phone }}" class="text-decoration-none">
                                            <i class="ti ti-phone-call me-1"></i>
                                            {{ $examinee->phone }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-muted small mb-1">
                                    <i class="ti ti-brand-whatsapp me-1"></i>
                                    رقم الواتساب
                                </label>
                                <p class="fw-semibold mb-0">
                                    @if($examinee->whatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $examinee->whatsapp) }}" target="_blank" class="text-decoration-none text-success">
                                            <i class="ti ti-brand-whatsapp me-1"></i>
                                            {{ $examinee->whatsapp }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-map-pin me-1"></i>
                                    مكان الإقامة الحالي
                                </label>
                                <p class="fw-semibold mb-0">{{ $examinee->current_residence ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- البيانات التنظيمية -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-building me-2 text-success"></i>
                            البيانات التنظيمية
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-building-store me-1"></i>
                                    المكتب
                                </label>
                                <p class="mb-0">
                                    @if($examinee->office)
                                        <span class="badge bg-light-info text-info fs-6">
                                            {{ $examinee->office->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-users me-1"></i>
                                    التجمع
                                </label>
                                <p class="mb-0">
                                    @if($examinee->cluster)
                                        <span class="badge bg-light-primary text-primary fs-6">
                                            {{ $examinee->cluster->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-circle-check me-1"></i>
                                    الحالة
                                </label>
                                <p class="mb-0">
                                    @if($examinee->status == 'confirmed')
                                        <span class="badge bg-light-success text-success fs-6">
                                            <i class="ti ti-circle-check me-1"></i>
                                            مؤكد
                                        </span>
                                    @elseif($examinee->status == 'pending')
                                        <span class="badge bg-light-warning text-warning fs-6">
                                            <i class="ti ti-clock me-1"></i>
                                            قيد التأكيد
                                        </span>
                                    @else
                                        <span class="badge bg-light-danger text-danger fs-6">
                                            <i class="ti ti-circle-x me-1"></i>
                                            منسحب
                                        </span>
                                    @endif
                                </p>
                            </div>
                            @if($examinee->notes)
                                <div class="col-md-12">
                                    <label class="form-label text-muted small">
                                        <i class="ti ti-notes me-1"></i>
                                        ملاحظات
                                    </label>
                                    <div class="alert alert-light border mb-0">
                                        <p class="mb-0">{{ $examinee->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row g-3">
                            @if($examinee->narration)
                            <div class="info-row">
                                <span class="info-label">الرواية:</span>
                                <span class="info-value">{{ $examinee->narration->name }}</span>
                            </div>
                            @endif

                            @if($examinee->drawing)
                            <div class="info-row">
                                <span class="info-label">الرسم:</span>
                                <span class="info-value">{{ $examinee->drawing->name }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- محاولات الامتحان -->
            {{-- @if($examinee->examAttempts && $examinee->examAttempts->count() > 0)
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-certificate me-2 text-warning"></i>
                                محاولات الامتحان
                                <span class="badge bg-light-warning text-warning ms-2">
                                    {{ $examinee->examAttempts->count() }}
                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                <i class="ti ti-calendar me-1"></i>
                                                السنة
                                            </th>
                                            <th>
                                                <i class="ti ti-book me-1"></i>
                                                الرواية
                                            </th>
                                            <th>
                                                <i class="ti ti-pencil me-1"></i>
                                                الرسم
                                            </th>
                                            <th>
                                                <i class="ti ti-layout-sidebar me-1"></i>
                                                الجانب
                                            </th>
                                            <th>
                                                <i class="ti ti-checkbox me-1"></i>
                                                النتيجة
                                            </th>
                                            <th>
                                                <i class="ti ti-percentage me-1"></i>
                                                النسبة المئوية
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($examinee->examAttempts as $attempt)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-light-secondary text-secondary">
                                                        {{ $attempt->year ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>{{ $attempt->narration->name ?? '-' }}</td>
                                                <td>{{ $attempt->drawing->name ?? '-' }}</td>
                                                <td>{{ $attempt->side ?? '-' }}</td>
                                                <td>
                                                    @if($attempt->result)
                                                        <span class="badge bg-light-info text-info">
                                                            {{ $attempt->result }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($attempt->percentage)
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                                <div class="progress-bar bg-primary" 
                                                                     role="progressbar" 
                                                                     style="width: {{ $attempt->percentage }}%"
                                                                     aria-valuenow="{{ $attempt->percentage }}" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <span class="fw-semibold">{{ $attempt->percentage }}%</span>
                                                        </div>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif --}}

            <!-- معلومات النظام -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-clock me-2 text-secondary"></i>
                            معلومات النظام
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-calendar-plus me-1"></i>
                                    تاريخ الإنشاء
                                </label>
                                <p class="fw-semibold mb-0">
                                    {{ $examinee->created_at->format('Y-m-d H:i') }}
                                    <small class="text-muted">({{ $examinee->created_at->diffForHumans() }})</small>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">
                                    <i class="ti ti-calendar-edit me-1"></i>
                                    آخر تحديث
                                </label>
                                <p class="fw-semibold mb-0">
                                    {{ $examinee->updated_at->format('Y-m-d H:i') }}
                                    <small class="text-muted">({{ $examinee->updated_at->diffForHumans() }})</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger" id="deleteModalLabel">
                    <i class="ti ti-alert-triangle me-2"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="ti ti-trash display-1 text-danger mb-3"></i>
                    <h6>هل أنت متأكد من حذف الممتحن؟</h6>
                    <p class="text-muted mb-0">
                        سيتم حذف الممتحن <strong>{{ $examinee->full_name ?? $examinee->first_name }}</strong> نهائياً ولا يمكن التراجع عن هذا الإجراء.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>
                    إلغاء
                </button>
                <form action="{{ route('examinees.destroy', $examinee) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>
                        حذف نهائي
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 2rem;
}
</style>
@endpush