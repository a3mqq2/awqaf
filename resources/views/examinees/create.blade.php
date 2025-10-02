@extends('layouts.app')

@section('title', 'إضافة ممتحن')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('examinees.index') }}">الممتحنين</a></li>
    <li class="breadcrumb-item active">إضافة ممتحن</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <form action="{{ route('examinees.store') }}" method="POST">
            @csrf

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="ti ti-user-plus me-2 text-primary"></i>
                                إضافة ممتحن جديد
                            </h4>
                            <p class="text-muted mb-0">قم بتعبئة البيانات المطلوبة لإضافة ممتحن جديد</p>
                        </div>
                        <div>
                            <a href="{{ route('examinees.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-right me-1"></i>
                                رجوع
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="examineeAccordion">

                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header" id="headingPersonal">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonal" aria-expanded="true" aria-controls="collapsePersonal">
                            <i class="ti ti-user me-2 text-primary fs-5"></i>
                            <span class="fw-semibold">البيانات الشخصية</span>
                            <span class="badge bg-light-primary text-primary ms-2">إلزامي</span>
                        </button>
                    </h2>
                    <div id="collapsePersonal" class="accordion-collapse collapse show" aria-labelledby="headingPersonal" data-bs-parent="#examineeAccordion">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="ti ti-user me-1"></i>
                                        الاسم الأول
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="ti ti-user me-1"></i>
                                        اسم الأب
                                    </label>
                                    <input type="text" name="father_name" class="form-control @error('father_name') is-invalid @enderror" value="{{ old('father_name') }}">
                                    @error('father_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="ti ti-user me-1"></i>
                                        اسم الجد
                                    </label>
                                    <input type="text" name="grandfather_name" class="form-control @error('grandfather_name') is-invalid @enderror" value="{{ old('grandfather_name') }}">
                                    @error('grandfather_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="ti ti-user me-1"></i>
                                        اللقب
                                    </label>
                                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header" id="headingIdentity">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIdentity" aria-expanded="false" aria-controls="collapseIdentity">
                            <i class="ti ti-id me-2 text-info fs-5"></i>
                            <span class="fw-semibold">معلومات الهوية والاتصال</span>
                        </button>
                    </h2>
                    <div id="collapseIdentity" class="accordion-collapse collapse" aria-labelledby="headingIdentity" data-bs-parent="#examineeAccordion">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">
                                        <i class="ti ti-id-badge me-1"></i>
                                        الرقم الوطني
                                    </label>
                                    <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id') }}">
                                    @error('national_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">
                                        <i class="ti ti-passport me-1"></i>
                                        رقم الجواز
                                    </label>
                                    <input type="text" name="passport_no" class="form-control @error('passport_no') is-invalid @enderror" value="{{ old('passport_no') }}">
                                    @error('passport_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">
                                        <i class="ti ti-phone me-1"></i>
                                        رقم الهاتف
                                    </label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-flag me-1"></i>
                                        الجنسية
                                    </label>
                                    <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror" value="{{ old('nationality') }}">
                                    @error('nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-map-pin me-1"></i>
                                        مكان الإقامة الحالي
                                    </label>
                                    <input type="text" name="current_residence" class="form-control @error('current_residence') is-invalid @enderror" value="{{ old('current_residence') }}">
                                    @error('current_residence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header" id="headingOrganization">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrganization" aria-expanded="false" aria-controls="collapseOrganization">
                            <i class="ti ti-building me-2 text-success fs-5"></i>
                            <span class="fw-semibold">البيانات التنظيمية</span>
                            <span class="badge bg-light-success text-success ms-2">إلزامي</span>
                        </button>
                    </h2>
                    <div id="collapseOrganization" class="accordion-collapse collapse" aria-labelledby="headingOrganization" data-bs-parent="#examineeAccordion">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="ti ti-gender-male me-1"></i>
                                        الجنس
                                    </label>
                                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                        <option value="">اختر...</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="ti ti-calendar-event me-1"></i>
                                        تاريخ الميلاد
                                    </label>
                                    <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="ti ti-circle-check me-1"></i>
                                        الحالة
                                    </label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>قيد التأكيد</option>
                                        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                        <option value="withdrawn" {{ old('status') == 'withdrawn' ? 'selected' : '' }}>منسحب</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="ti ti-building-store me-1"></i>
                                        المكتب
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="office_id" class="form-select @error('office_id') is-invalid @enderror" required>
                                        <option value="">اختر مكتب...</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                                {{ $office->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('office_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">
                                        <i class="ti ti-users me-1"></i>
                                        التجمع
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="cluster_id" class="form-select @error('cluster_id') is-invalid @enderror" required>
                                        <option value="">اختر تجمع...</option>
                                        @foreach($clusters as $cluster)
                                            <option value="{{ $cluster->id }}" {{ old('cluster_id') == $cluster->id ? 'selected' : '' }}>
                                                {{ $cluster->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cluster_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">
                                        <i class="ti ti-notes me-1"></i>
                                        ملاحظات
                                    </label>
                                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="أضف أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-book me-1"></i>
                                        الرواية
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="narration_id" class="form-select @error('narration_id') is-invalid @enderror" required>
                                        <option value="">اختر الرواية...</option>
                                        @foreach($narrations as $narration)
                                            <option value="{{ $narration->id }}" {{ old('narration_id') == $narration->id ? 'selected' : '' }}>
                                                {{ $narration->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('narration_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="ti ti-pencil me-1"></i>
                                        الرسم
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="drawing_id" class="form-select @error('drawing_id') is-invalid @enderror" required>
                                        <option value="">اختر الرسم...</option>
                                        @foreach($drawings as $drawing)
                                            <option value="{{ $drawing->id }}" {{ old('drawing_id') == $drawing->id ? 'selected' : '' }}>
                                                {{ $drawing->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('drawing_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{-- 
                <div class="accordion-item border-0 shadow-sm mb-3">
                    <h2 class="accordion-header" id="headingExams">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExams" aria-expanded="false" aria-controls="collapseExams">
                            <i class="ti ti-certificate me-2 text-warning fs-5"></i>
                            <span class="fw-semibold">محاولات الامتحان</span>
                            <span class="badge bg-light-warning text-warning ms-2">اختياري</span>
                        </button>
                    </h2>
                    <div id="collapseExams" class="accordion-collapse collapse" aria-labelledby="headingExams" data-bs-parent="#examineeAccordion">
                        <div class="accordion-body">
                            <div id="attemptsContainer">
                                <div class="card border mb-3 attempt-card">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="ti ti-certificate me-2"></i>
                                            <strong>محاولة رقم 1</strong>
                                        </span>
                                        <button type="button" class="btn btn-danger btn-sm remove-attempt" title="حذف المحاولة">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label">
                                                    <i class="ti ti-calendar me-1"></i>
                                                    السنة
                                                </label>
                                                <input type="number" name="attempts[0][year]" class="form-control" placeholder="2024" min="1900" max="2100">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">
                                                    <i class="ti ti-book me-1"></i>
                                                    الرواية
                                                </label>
                                                <select name="attempts[0][narration_id]" class="form-select">
                                                    <option value="">اختر الرواية...</option>
                                                    @foreach($narrations as $narration)
                                                        <option value="{{ $narration->id }}">{{ $narration->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">
                                                    <i class="ti ti-pencil me-1"></i>
                                                    الرسم
                                                </label>
                                                <select name="attempts[0][drawing_id]" class="form-select">
                                                    <option value="">اختر الرسم...</option>
                                                    @foreach($drawings as $drawing)
                                                        <option value="{{ $drawing->id }}">{{ $drawing->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">
                                                    <i class="ti ti-layout-sidebar me-1"></i>
                                                    الجانب
                                                </label>
                                                <input type="text" name="attempts[0][side]" class="form-control" placeholder="أدخل الجانب">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ti ti-checkbox me-1"></i>
                                                    النتيجة
                                                </label>
                                                <input type="text" name="attempts[0][result]" class="form-control" placeholder="أدخل النتيجة">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ti ti-percentage me-1"></i>
                                                    النسبة المئوية
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="attempts[0][percentage]" class="form-control" placeholder="0.00" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="addAttempt" class="btn btn-outline-primary">
                                <i class="ti ti-plus me-1"></i>
                                إضافة محاولة جديدة
                            </button>
                        </div>
                    </div>
                </div> --}}

            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('examinees.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-x me-1"></i>
                            إلغاء
                        </a>
                        <button type="reset" class="btn btn-outline-warning">
                            <i class="ti ti-refresh me-1"></i>
                            إعادة تعيين
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>
                            حفظ البيانات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let attemptIndex = 1;
    
    // Add new exam attempt card
    document.getElementById('addAttempt').addEventListener('click', function () {
        let container = document.getElementById('attemptsContainer');
        let cardCount = container.querySelectorAll('.attempt-card').length + 1;
        
        let newCard = `
            <div class="card border mb-3 attempt-card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span>
                        <i class="ti ti-certificate me-2"></i>
                        <strong>محاولة رقم ${cardCount}</strong>
                    </span>
                    <button type="button" class="btn btn-danger btn-sm remove-attempt" title="حذف المحاولة">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="ti ti-calendar me-1"></i>
                                السنة
                            </label>
                            <input type="number" name="attempts[${attemptIndex}][year]" class="form-control" placeholder="2024" min="1900" max="2100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="ti ti-book me-1"></i>
                                الرواية
                            </label>
                            <select name="attempts[${attemptIndex}][narration_id]" class="form-select">
                                <option value="">اختر الرواية...</option>
                                @foreach($narrations as $narration)
                                    <option value="{{ $narration->id }}">{{ $narration->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="ti ti-pencil me-1"></i>
                                الرسم
                            </label>
                            <select name="attempts[${attemptIndex}][drawing_id]" class="form-select">
                                <option value="">اختر الرسم...</option>
                                @foreach($drawings as $drawing)
                                    <option value="{{ $drawing->id }}">{{ $drawing->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="ti ti-layout-sidebar me-1"></i>
                                الجانب
                            </label>
                            <input type="text" name="attempts[${attemptIndex}][side]" class="form-control" placeholder="أدخل الجانب">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-checkbox me-1"></i>
                                النتيجة
                            </label>
                            <input type="text" name="attempts[${attemptIndex}][result]" class="form-control" placeholder="أدخل النتيجة">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ti ti-percentage me-1"></i>
                                النسبة المئوية
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="attempts[${attemptIndex}][percentage]" class="form-control" placeholder="0.00" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        
        container.insertAdjacentHTML('beforeend', newCard);
        attemptIndex++;
        
        // Update card numbers
        updateAttemptNumbers();
    });

    // Remove exam attempt card
    document.addEventListener('click', function(e) {
        if(e.target && (e.target.classList.contains('remove-attempt') || e.target.closest('.remove-attempt'))) {
            const card = e.target.closest('.attempt-card');
            const container = document.getElementById('attemptsContainer');
            
            // Don't allow removing the last card
            if(container.querySelectorAll('.attempt-card').length > 1) {
                card.remove();
                // Update card numbers after removal
                updateAttemptNumbers();
            } else {
                alert('يجب الاحتفاظ بمحاولة واحدة على الأقل');
            }
        }
    });

    // Update attempt card numbers
    function updateAttemptNumbers() {
        const cards = document.querySelectorAll('.attempt-card');
        cards.forEach((card, index) => {
            const header = card.querySelector('.card-header strong');
            header.textContent = `محاولة رقم ${index + 1}`;
        });
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush