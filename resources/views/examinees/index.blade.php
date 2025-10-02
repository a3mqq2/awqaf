@extends('layouts.app')

@section('title', 'الممتحنين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الممتحنين</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-users me-2"></i>
                    قائمة الممتحنين
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#advancedFilterModal">
                        <i class="ti ti-adjustments me-1"></i>
                        فلتر متقدم
                    </button>
                    <a href="{{ route('examinees.print') }}?{{ http_build_query(request()->except('page')) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="ti ti-printer me-1"></i>
                        طباعة
                    </a>
                    <a href="{{ route('examinees.import.form') }}" class="btn btn-success">
                        <i class="ti ti-file-import me-1"></i>
                        استيراد من Excel
                    </a>
                    <a href="{{ route('examinees.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        إضافة ممتحن جديد
                    </a>
                </div>
            </div>

            <!-- Quick Filter Bar -->
            <div class="card-body border-bottom">
                <form method="GET" action="{{ route('examinees.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="ti ti-search me-1"></i>
                            بحث بالاسم
                        </label>
                        <input type="text" name="name" class="form-control" 
                               placeholder="ابحث عن ممتحن..." 
                               value="{{ request('name') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="ti ti-id-badge me-1"></i>
                            الرقم الوطني
                        </label>
                        <input type="text" name="national_id" class="form-control" 
                               placeholder="الرقم الوطني" 
                               value="{{ request('national_id') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="ti ti-passport me-1"></i>
                            رقم الجواز
                        </label>
                        <input type="text" name="passport_no" class="form-control" 
                               placeholder="رقم الجواز" 
                               value="{{ request('passport_no') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="ti ti-circle-check me-1"></i>
                            الحالة
                        </label>
                        <select name="status" class="form-select">
                            <option value="">كل الحالات</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد التأكيد</option>
                            <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>منسحب</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary flex-fill">
                                <i class="ti ti-filter me-1"></i>
                                تصفية
                            </button>
                            <a href="{{ route('examinees.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-refresh"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Examinees Table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>الممتحن</th>
                                <th>معلومات الهوية</th>
                                <th>الهاتف</th>
                                <th>المكتب</th>
                                <th>التجمع</th>
                                <th>الحالة</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examinees as $examinee)
                                <tr>
                                    <td>
                                        <span class="badge bg-light-secondary text-secondary">
                                            {{ $examinee->id }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-user"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $examinee->full_name ?? $examinee->first_name }}</h6>
                                                @if($examinee->nationality)
                                                    <small class="text-muted">
                                                        <i class="ti ti-flag"></i>
                                                        {{ $examinee->nationality }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($examinee->national_id)
                                            <div class="mb-1">
                                                <i class="ti ti-id-badge me-1 text-muted"></i>
                                                <small>{{ $examinee->national_id }}</small>
                                            </div>
                                        @endif
                                        @if($examinee->passport_no)
                                            <div>
                                                <i class="ti ti-passport me-1 text-muted"></i>
                                                <small>{{ $examinee->passport_no }}</small>
                                            </div>
                                        @endif
                                        @if(!$examinee->national_id && !$examinee->passport_no)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->phone)
                                            <i class="ti ti-phone me-1 text-muted"></i>
                                            {{ $examinee->phone }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->office)
                                            <span class="badge bg-light-info text-info">
                                                <i class="ti ti-building-store me-1"></i>
                                                {{ $examinee->office->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->cluster)
                                            <span class="badge bg-light-primary text-primary">
                                                <i class="ti ti-users me-1"></i>
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
                                        <a href="{{ route('examinees.show', $examinee) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           data-bs-toggle="tooltip" 
                                           title="عرض التفاصيل">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('examinees.edit', $examinee) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="tooltip" 
                                           title="تعديل">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal"
                                                data-examinee-id="{{ $examinee->id }}"
                                                data-examinee-name="{{ $examinee->full_name ?? $examinee->first_name }}"
                                                title="حذف">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-users-off display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">لا توجد بيانات</h5>
                                            <p class="text-muted">لم يتم العثور على ممتحنين</p>
                                            <a href="{{ route('examinees.create') }}" class="btn btn-primary">
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
    </div>
</div>

<!-- Advanced Filter Modal -->
<div class="modal fade" id="advancedFilterModal" tabindex="-1" aria-labelledby="advancedFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="GET" action="{{ route('examinees.index') }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="advancedFilterModalLabel">
                        <i class="ti ti-adjustments me-2"></i>
                        فلتر متقدم
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Name Search -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-search me-1"></i>
                                بحث بالاسم
                            </label>
                            <input type="text" name="name" class="form-control" 
                                   placeholder="الاسم الأول، الأب، الجد، أو اللقب" 
                                   value="{{ request('name') }}">
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-phone me-1"></i>
                                رقم الهاتف
                            </label>
                            <input type="text" name="phone" class="form-control" 
                                   placeholder="أدخل رقم الهاتف" 
                                   value="{{ request('phone') }}">
                        </div>

                        <!-- National ID -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-id-badge me-1"></i>
                                الرقم الوطني
                            </label>
                            <input type="text" name="national_id" class="form-control" 
                                   placeholder="أدخل الرقم الوطني" 
                                   value="{{ request('national_id') }}">
                        </div>

                        <!-- Passport Number -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-passport me-1"></i>
                                رقم الجواز
                            </label>
                            <input type="text" name="passport_no" class="form-control" 
                                   placeholder="أدخل رقم الجواز" 
                                   value="{{ request('passport_no') }}">
                        </div>

                        <!-- Gender -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="ti ti-gender-male me-1"></i>
                                الجنس
                            </label>
                            <select name="gender" class="form-select">
                                <option value="">الكل</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="ti ti-circle-check me-1"></i>
                                الحالة
                            </label>
                            <select name="status" class="form-select">
                                <option value="">كل الحالات</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد التأكيد</option>
                                <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>منسحب</option>
                            </select>
                        </div>

                        <!-- Nationality -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="ti ti-flag me-1"></i>
                                الجنسية
                            </label>
                            <input type="text" name="nationality" class="form-control" 
                                   placeholder="أدخل الجنسية" 
                                   value="{{ request('nationality') }}">
                        </div>

                        <!-- Office -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-building-store me-1"></i>
                                المكتب
                            </label>
                            <select name="office_id" class="form-select">
                                <option value="">كل المكاتب</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}" {{ request('office_id') == $office->id ? 'selected' : '' }}>
                                        {{ $office->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cluster -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-users-group me-1"></i>
                                التجمع
                            </label>
                            <select name="cluster_id" class="form-select">
                                <option value="">كل التجمعات</option>
                                @foreach($clusters as $cluster)
                                    <option value="{{ $cluster->id }}" {{ request('cluster_id') == $cluster->id ? 'selected' : '' }}>
                                        {{ $cluster->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Current Residence -->
                        <div class="col-md-12">
                            <label class="form-label">
                                <i class="ti ti-map-pin me-1"></i>
                                مكان الإقامة
                            </label>
                            <input type="text" name="current_residence" class="form-control" 
                                   placeholder="أدخل مكان الإقامة" 
                                   value="{{ request('current_residence') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>
                        إلغاء
                    </button>
                    <a href="{{ route('examinees.index') }}" class="btn btn-outline-warning">
                        <i class="ti ti-refresh me-1"></i>
                        إعادة تعيين
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-filter me-1"></i>
                        تطبيق الفلتر
                    </button>
                </div>
            </form>
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
                        سيتم حذف الممتحن <strong id="deleteExamineeName"></strong> نهائياً ولا يمكن التراجع عن هذا الإجراء.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>
                    إلغاء
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Delete modal
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const examineeId = button.getAttribute('data-examinee-id');
            const examineeName = button.getAttribute('data-examinee-name');
            
            document.getElementById('deleteExamineeName').textContent = examineeName;
            document.getElementById('deleteForm').action = `/examinees/${examineeId}`;
        });
    }
});
</script>
@endpush