@extends('layouts.app')

@section('title','استيراد الممتحنين')

@section('content')
<div class="container mt-3">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('examinees.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">اختر ملف Excel</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="ti ti-file-import me-1"></i> استيراد
                </button>
                <a href="{{ route('examinees.index') }}" class="btn btn-secondary">رجوع</a>
            </form>
        </div>
    </div>
</div>
@endsection
