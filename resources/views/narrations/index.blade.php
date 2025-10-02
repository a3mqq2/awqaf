@extends('layouts.app')

@section('title', 'الروايات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الروايات</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-book me-2"></i>
                    قائمة الروايات
                </h5>
                <a href="{{ route('narrations.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    إضافة رواية جديدة
                </a>
            </div>

            <div class="card-body border-bottom">
                <form method="GET" action="{{ route('narrations.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">البحث</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="ابحث باسم الرواية..." 
                                   value="{{ request('search') }}">
                        </div>
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
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم أ-ي</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary flex-fill">
                                <i class="ti ti-filter me-1"></i>تصفية
                            </button>
                            <a href="{{ route('narrations.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-refresh"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>اسم الرواية</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($narrations as $narration)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $narration->name }}</td>
                                    <td>
                                        <span class="badge {{ $narration->is_active ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                            <i class="ti {{ $narration->is_active ? 'ti-circle-check' : 'ti-circle-x' }} me-1"></i>
                                            {{ $narration->is_active ? 'مفعل' : 'غير مفعل' }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="ti ti-calendar me-1 text-muted"></i>
                                        {{ $narration->created_at->format('Y-m-d') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('narrations.edit', $narration) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="تعديل">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('narrations.toggle', $narration) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $narration->is_active ? 'warning' : 'success' }}" 
                                                    title="{{ $narration->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                <i class="ti {{ $narration->is_active ? 'ti-toggle-left' : 'ti-toggle-right' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('narrations.destroy', $narration) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="ti ti-book-off display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد روايات</h5>
                                        <a href="{{ route('narrations.create') }}" class="btn btn-primary mt-2">
                                            <i class="ti ti-plus me-1"></i>إضافة أول رواية
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($narrations->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            عرض {{ $narrations->firstItem() }} إلى {{ $narrations->lastItem() }} من أصل {{ $narrations->total() }} نتيجة
                        </div>
                        <div>
                            {{ $narrations->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
