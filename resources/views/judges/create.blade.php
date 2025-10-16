@extends('layouts.app')

@section('title', 'إضافة محكم جديد')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('judges.index') }}">المحكمين</a></li>
    <li class="breadcrumb-item active">إضافة محكم جديد</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    <i class="ti ti-user-plus me-2"></i>
                    إضافة محكم جديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('judges.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- اسم المحكم -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                اسم المحكم <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="أدخل اسم المحكم"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                البريد الإلكتروني <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" 
                                   placeholder="example@email.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- كلمة المرور -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                كلمة المرور <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="أدخل كلمة المرور"
                                   required>
                            <small class="text-muted">يجب أن تكون 8 أحرف على الأقل</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- تأكيد كلمة المرور -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                تأكيد كلمة المرور <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   class="form-control" 
                                   placeholder="أعد إدخال كلمة المرور"
                                   required>
                        </div>

                        <!-- التجمعات -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label">
                                <i class="ti ti-map-pin me-1"></i>
                                التجمعات <span class="text-danger">*</span>
                            </label>
                            <select name="clusters[]" 
                                    id="clusters" 
                                    class="form-select select2 @error('clusters') is-invalid @enderror" 
                                    multiple
                                    required>
                                @forelse($clusters as $cluster)
                                    <option value="{{ $cluster->id }}" 
                                            {{ in_array($cluster->id, old('clusters', [])) ? 'selected' : '' }}>
                                        {{ $cluster->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>لا توجد تجمعات مخصصة لك</option>
                                @endforelse
                            </select>
                            <small class="text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                يتم عرض التجمعات المخصصة لك فقط. يمكن للمحكم العمل في اللجان التابعة لهذه التجمعات
                            </small>
                            @error('clusters')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>ملاحظات مهمة:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>سيتم إنشاء حساب جديد للمحكم بدور "محكم"</li>
                                    <li>يمكن للمحكم تسجيل الدخول باستخدام البريد الإلكتروني وكلمة المرور</li>
                                    <li>يمكن إضافة المحكم إلى اللجان لاحقاً من صفحة تعديل اللجنة</li>
                                    <li>يمكن للمحكم رؤية الممتحنين المخصصين له في اللجان</li>
                                </ul>
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>حفظ المحكم
                            </button>
                            <a href="{{ route('judges.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>رجوع
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // تفعيل Select2 للتجمعات
    $('#clusters').select2({
        placeholder: "اختر التجمعات",
        allowClear: true,
        width: '100%',
        dir: 'rtl'
    });
});
</script>
@endpush