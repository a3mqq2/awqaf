@extends('layouts.app')

@section('title', 'إضافة لجنة جديدة')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('committees.index') }}">اللجان</a></li>
    <li class="breadcrumb-item active">إضافة لجنة جديدة</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    <i class="ti ti-plus me-2"></i>
                    إضافة لجنة جديدة
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('committees.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- اسم اللجنة -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                اسم اللجنة <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="مثال: لجنة حفص - المركز"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- التجمع -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                التجمع <span class="text-danger">*</span>
                            </label>
                            <select name="cluster_id" 
                                    class="form-select @error('cluster_id') is-invalid @enderror" 
                                    required>
                                <option value="">اختر التجمع...</option>
                                @forelse($clusters as $cluster)
                                    <option value="{{ $cluster->id }}" {{ old('cluster_id') == $cluster->id ? 'selected' : '' }}>
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
                            @error('cluster_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الروايات -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label">
                                <i class="ti ti-book me-1"></i>
                                الروايات المخصصة للجنة
                            </label>
                            <select name="narrations[]" 
                                    id="narrations" 
                                    class="form-select select2 @error('narrations') is-invalid @enderror" 
                                    multiple>
                                @foreach($narrations as $narration)
                                    <option value="{{ $narration->id }}" 
                                            {{ in_array($narration->id, old('narrations', [])) ? 'selected' : '' }}>
                                        {{ $narration->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                اختر الروايات التي ستمتحن بها هذه اللجنة
                            </small>
                            @error('narrations')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- المحكمين -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label">
                                <i class="ti ti-users me-1"></i>
                                المحكمين
                            </label>
                            <select name="judges[]" 
                                    id="judges" 
                                    class="form-select select2 @error('judges') is-invalid @enderror" 
                                    multiple>
                                @foreach($judges as $judge)
                                    <option value="{{ $judge->id }}" 
                                            {{ in_array($judge->id, old('judges', [])) ? 'selected' : '' }}>
                                        {{ $judge->name }} - {{ $judge->email }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                اختر المحكمين الذين سيعملون في هذه اللجنة
                            </small>
                            @error('judges')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>ملاحظات:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>يمكنك إضافة المحكمين والروايات لاحقاً من صفحة تعديل اللجنة</li>
                                    <li>سيتم تخصيص اللجنة للتجمع المحدد فقط</li>
                                    <li>يمكن للمحكمين المضافين رؤية الممتحنين المخصصين لهذه اللجنة</li>
                                </ul>
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>حفظ اللجنة
                            </button>
                            <a href="{{ route('committees.index') }}" class="btn btn-secondary">
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
    // تفعيل Select2 للروايات
    $('#narrations').select2({
        placeholder: "اختر الروايات",
        allowClear: true,
        width: '100%',
        dir: 'rtl'
    });

    // تفعيل Select2 للمحكمين
    $('#judges').select2({
        placeholder: "اختر المحكمين",
        allowClear: true,
        width: '100%',
        dir: 'rtl'
    });
});
</script>
@endpush