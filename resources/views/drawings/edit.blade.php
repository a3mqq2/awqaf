@extends('layouts.app')

@section('title', 'تعديل رسم')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('drawings.index') }}">الرسوم</a></li>
    <li class="breadcrumb-item active">تعديل رسم</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-edit me-2"></i> تعديل الرسم</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('drawings.update', $drawing) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الرسم</label>
                        <input type="text" name="name" id="name" class="form-control"
                               value="{{ old('name', $drawing->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                               {{ $drawing->is_active ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">مفعل</label>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('drawings.index') }}" class="btn btn-secondary">
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
