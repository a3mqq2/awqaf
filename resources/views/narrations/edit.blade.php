@extends('layouts.app')

@section('title', 'تعديل رواية')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('narrations.index') }}">الروايات</a></li>
    <li class="breadcrumb-item active">تعديل رواية</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-edit me-2"></i>تعديل الرواية</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('narrations.update', $narration) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الرواية</label>
                        <input type="text" name="name" id="name" class="form-control" required 
                               value="{{ old('name', $narration->name) }}">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                               {{ $narration->is_active ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">مفعل</label>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('narrations.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> رجوع
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy"></i> تحديث
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
