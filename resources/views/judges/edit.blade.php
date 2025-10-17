@extends('layouts.app')

@section('title', 'تعديل المحكم')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('judges.index') }}">المحكمين</a></li>
    <li class="breadcrumb-item active">تعديل: {{ $judge->name }}</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    <i class="ti ti-edit me-2"></i>
                    تعديل المحكم: {{ $judge->name }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('judges.update', $judge) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- اسم المحكم -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                اسم المحكم <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $judge->name) }}" 
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
                                   value="{{ old('email', $judge->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- كلمة المرور -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                كلمة المرور الجديدة
                            </label>
                            <input type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="اتركها فارغة إذا لم ترد تغييرها">
                            <small class="text-muted">اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- تأكيد كلمة المرور -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                تأكيد كلمة المرور الجديدة
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   class="form-control">
                        </div>

                        <!-- الحالة -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                الحالة
                            </label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active', $judge->is_active) == 1 ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ old('is_active', $judge->is_active) == 0 ? 'selected' : '' }}>غير نشط</option>
                            </select>
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
                                @php
                                    $selectedClusters = old('clusters', $judge->clusters->pluck('id')->toArray());
                                @endphp
                                @forelse($clusters as $cluster)
                                    <option value="{{ $cluster->id }}" 
                                            {{ in_array($cluster->id, $selectedClusters) ? 'selected' : '' }}>
                                        {{ $cluster->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>لا توجد تجمعات مخصصة لك</option>
                                @endforelse
                            </select>
                            <small class="text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                يتم عرض التجمعات المخصصة لك فقط
                            </small>
                            @error('clusters')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- اللجان المخصصة -->
                        @if($judge->committees->count() > 0)
                        <div class="col-md-12 mb-3">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="ti ti-users-group me-2"></i>
                                        اللجان المخصصة ({{ $judge->committees->count() }})
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($judge->committees as $committee)
                                            <div class="col-md-4 mb-2">
                                                <div class="d-flex align-items-center p-2 border rounded bg-white">
                                                    <i class="ti ti-users-group text-info me-2"></i>
                                                    <div class="flex-grow-1">
                                                        <strong>{{ $committee->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="ti ti-map-pin me-1"></i>
                                                            {{ $committee->cluster->name }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- معلومات إضافية -->
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>معلومات المحكم:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>تاريخ الإنشاء: {{ $judge->created_at->format('Y-m-d H:i:s') }}</li>
                                    <li>آخر تحديث: {{ $judge->updated_at->format('Y-m-d H:i:s') }}</li>
                                    <li>عدد اللجان المخصصة: {{ $judge->committees->count() }}</li>
                                    <li>عدد التجمعات: {{ $judge->clusters->count() }}</li>
                                </ul>
                            </div>
                        </div>
                        <!-- الصلاحيات -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label">
                                <i class="ti ti-key me-1"></i>
                                الصلاحيات الممنوحة للمحكم
                            </label>

                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permission->name }}"
                                                id="perm_{{ $permission->id }}"
                                                {{ in_array($permission->name, $judgePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->name === 'exam.scientific' ? 'امتحان المنهج العلمي' : 'امتحان الشفوي' }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>حفظ التعديلات
                            </button>
                            <a href="{{ route('judges.show', $judge) }}" class="btn btn-info">
                                <i class="ti ti-eye me-1"></i>عرض المحكم
                            </a>
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