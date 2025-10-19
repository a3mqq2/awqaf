@extends('layouts.app')

@section('title', 'خيارات الطباعة والتصدير')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('examinees.index') }}">الممتحنين</a></li>
    <li class="breadcrumb-item active">خيارات الطباعة</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <!-- Info Alert -->
                <div class="alert alert-info d-flex align-items-center">
                    <i class="ti ti-info-circle me-2 fs-4"></i>
                    <div>
                        سيتم طباعة/تصدير <strong class="fs-5">{{ $count }}</strong> ممتحن بناءً على الفلاتر المطبقة
                    </div>
                </div>
                
                <form id="exportForm" method="GET">
                    <!-- Select Columns Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="ti ti-columns me-2"></i>
                                اختر الحقول المراد طباعتها
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Select All Checkbox -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="selectAll" checked>
                                        <label class="form-check-label fw-bold fs-6" for="selectAll">
                                            <i class="ti ti-checkbox me-1"></i>
                                            تحديد/إلغاء تحديد الكل
                                        </label>
                                    </div>
                                    <hr class="mt-2">
                                </div>
                            </div>
                            
                            <!-- Columns Grid -->
                            <div class="row g-3">
                                <!-- ID -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="id" id="col_id" checked>
                                        <label class="form-check-label" for="col_id">
                                            <i class="ti ti-hash me-1 text-primary"></i>
                                            التسلسل #
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Full Name -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="full_name" id="col_full_name" checked>
                                        <label class="form-check-label" for="col_full_name">
                                            <i class="ti ti-user me-1 text-success"></i>
                                            الاسم الكامل
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- First Name -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="first_name" id="col_first_name">
                                        <label class="form-check-label" for="col_first_name">
                                            <i class="ti ti-user-circle me-1 text-info"></i>
                                            الاسم الأول
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Father Name -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="father_name" id="col_father_name">
                                        <label class="form-check-label" for="col_father_name">
                                            <i class="ti ti-user-circle me-1 text-info"></i>
                                            اسم الأب
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Grandfather Name -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="grandfather_name" id="col_grandfather_name">
                                        <label class="form-check-label" for="col_grandfather_name">
                                            <i class="ti ti-user-circle me-1 text-info"></i>
                                            اسم الجد
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Last Name -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="last_name" id="col_last_name">
                                        <label class="form-check-label" for="col_last_name">
                                            <i class="ti ti-user-circle me-1 text-info"></i>
                                            اللقب
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- National ID -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="national_id" id="col_national_id" checked>
                                        <label class="form-check-label" for="col_national_id">
                                            <i class="ti ti-id-badge me-1 text-warning"></i>
                                            الرقم الوطني
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Passport Number -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="passport_no" id="col_passport_no">
                                        <label class="form-check-label" for="col_passport_no">
                                            <i class="ti ti-passport me-1 text-danger"></i>
                                            رقم الجواز
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Phone -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="phone" id="col_phone" checked>
                                        <label class="form-check-label" for="col_phone">
                                            <i class="ti ti-phone me-1 text-primary"></i>
                                            الهاتف
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- WhatsApp -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="whatsapp" id="col_whatsapp">
                                        <label class="form-check-label" for="col_whatsapp">
                                            <i class="ti ti-brand-whatsapp me-1 text-success"></i>
                                            واتساب
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Email -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="email" id="col_email">
                                        <label class="form-check-label" for="col_email">
                                            <i class="ti ti-mail me-1 text-info"></i>
                                            البريد الإلكتروني
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Gender -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="gender" id="col_gender">
                                        <label class="form-check-label" for="col_gender">
                                            <i class="ti ti-gender-male me-1 text-secondary"></i>
                                            الجنس
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Birth Date -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="birth_date" id="col_birth_date">
                                        <label class="form-check-label" for="col_birth_date">
                                            <i class="ti ti-calendar me-1 text-warning"></i>
                                            تاريخ الميلاد
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Nationality -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="nationality" id="col_nationality">
                                        <label class="form-check-label" for="col_nationality">
                                            <i class="ti ti-flag me-1 text-danger"></i>
                                            الجنسية
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Current Residence -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="current_residence" id="col_current_residence">
                                        <label class="form-check-label" for="col_current_residence">
                                            <i class="ti ti-map-pin me-1 text-primary"></i>
                                            مكان الإقامة
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Office -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="office" id="col_office" checked>
                                        <label class="form-check-label" for="col_office">
                                            <i class="ti ti-building-store me-1 text-info"></i>
                                            المكتب
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Cluster -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="cluster" id="col_cluster" checked>
                                        <label class="form-check-label" for="col_cluster">
                                            <i class="ti ti-users-group me-1 text-success"></i>
                                            التجمع
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Narration -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="narration" id="col_narration" checked>
                                        <label class="form-check-label" for="col_narration">
                                            <i class="ti ti-book me-1 text-warning"></i>
                                            الرواية
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Drawing -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="drawing" id="col_drawing" checked>
                                        <label class="form-check-label" for="col_drawing">
                                            <i class="ti ti-brush me-1 text-danger"></i>
                                            الرسم
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Status -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="status" id="col_status" checked>
                                        <label class="form-check-label" for="col_status">
                                            <i class="ti ti-circle-check me-1 text-primary"></i>
                                            الحالة
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Notes -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[]" value="notes" id="col_notes">
                                        <label class="form-check-label" for="col_notes">
                                            <i class="ti ti-note me-1 text-secondary"></i>
                                            الملاحظات
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                        <a href="{{ route('examinees.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i>
                            رجوع
                        </a>
                        
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btn-lg" id="exportExcel">
                                <i class="ti ti-file-spreadsheet me-2"></i>
                                تصدير Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-lg" id="printPdf">
                                <i class="ti ti-printer me-2"></i>
                                طباعة PDF
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const columnCheckboxes = document.querySelectorAll('.column-checkbox');
    const exportForm = document.getElementById('exportForm');
    const exportExcelBtn = document.getElementById('exportExcel');
    const printPdfBtn = document.getElementById('printPdf');
    
    // Select/Deselect All
    selectAllCheckbox.addEventListener('change', function() {
        columnCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Update Select All state when individual checkboxes change
    columnCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
        });
    });
    
    function updateSelectAllState() {
        const totalCheckboxes = columnCheckboxes.length;
        const checkedCheckboxes = document.querySelectorAll('.column-checkbox:checked').length;
        
        selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0;
        selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
    }
    
    // Validate at least one column is selected
    function validateSelection() {
        const checkedColumns = Array.from(columnCheckboxes).filter(cb => cb.checked);
        
        if (checkedColumns.length === 0) {
            alert('⚠️ الرجاء تحديد حقل واحد على الأقل للطباعة/التصدير');
            return false;
        }
        return true;
    }
    
    // Export Excel
    exportExcelBtn.addEventListener('click', function() {
        if (!validateSelection()) {
            return;
        }
        
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري التصدير...';
        
        const queryParams = new URLSearchParams(window.location.search);
        exportForm.action = '{{ route("examinees.export.excel") }}?' + queryParams.toString();
        exportForm.target = '_self';
        exportForm.submit();
        
        // Re-enable button after a delay
        setTimeout(() => {
            this.disabled = false;
            this.innerHTML = '<i class="ti ti-file-spreadsheet me-2"></i>تصدير Excel';
        }, 3000);
    });
    
    // Print PDF - Opens print page in new tab
    printPdfBtn.addEventListener('click', function() {
        if (!validateSelection()) {
            return;
        }
        
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري التحضير...';
        
        const queryParams = new URLSearchParams(window.location.search);
        exportForm.action = '{{ route("examinees.print") }}?' + queryParams.toString();
        exportForm.target = '_blank';
        exportForm.submit();
        
        // Re-enable button after a delay
        setTimeout(() => {
            this.disabled = false;
            this.innerHTML = '<i class="ti ti-printer me-2"></i>طباعة PDF';
        }, 2000);
    });
    
    // Initialize select all state on page load
    updateSelectAllState();
});
</script>
@endpush