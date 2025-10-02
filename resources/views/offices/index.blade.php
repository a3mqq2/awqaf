@extends('layouts.app')

@section('title', 'المكاتب')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">المكاتب</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-building me-2"></i> قائمة المكاتب</h5>
                <a href="{{ route('offices.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> إضافة مكتب جديد
                </a>
            </div>

            <div class="card-body border-bottom">
                <form method="GET" action="{{ route('offices.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">البحث</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="ابحث باسم المكتب..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>مفعل</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير مفعل</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">ترتيب حسب</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary flex-fill">
                            <i class="ti ti-filter me-1"></i> تصفية
                        </button>
                        <a href="{{ route('offices.index') }}" class="btn btn-outline-secondary ms-2">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>اسم المكتب</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($offices as $office)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $office->name }}</td>
                                    <td>
                                        <span class="badge {{ $office->is_active ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                            {{ $office->is_active ? 'مفعل' : 'غير مفعل' }}
                                        </span>
                                    </td>
                                    <td>{{ $office->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('offices.edit', $office) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('offices.toggle', $office) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $office->is_active ? 'warning' : 'success' }}">
                                                <i class="ti {{ $office->is_active ? 'ti-toggle-left' : 'ti-toggle-right' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('offices.destroy', $office) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="ti ti-building-off display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد مكاتب</h5>
                                        <a href="{{ route('offices.create') }}" class="btn btn-primary mt-2">
                                            <i class="ti ti-plus me-1"></i> إضافة أول مكتب
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($offices->hasPages())
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        عرض {{ $offices->firstItem() }} إلى {{ $offices->lastItem() }} من أصل {{ $offices->total() }} نتيجة
                    </div>
                    <div>{{ $offices->appends(request()->query())->links() }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
