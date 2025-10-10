@extends('layouts.app')

@section('title', 'التجمعات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">التجمعات</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-map-pin me-2"></i>
                    قائمة التجمعات
                </h5>
                <a href="{{ route('clusters.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    إضافة تجمع جديد
                </a>
            </div>

            <div class="card-body border-bottom">
                <form method="GET" action="{{ route('clusters.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">البحث</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="ابحث باسم التجمع..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>مفعل</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير مفعل</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">حالة الممتحن</label>
                        <select name="examinee_status" class="form-select">
                            <option value="">كل الحالات</option>
                            <option value="confirmed" {{ request('examinee_status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                            <option value="pending" {{ request('examinee_status') == 'pending' ? 'selected' : '' }}>قيد التأكيد</option>
                            <option value="withdrawn" {{ request('examinee_status') == 'withdrawn' ? 'selected' : '' }}>منسحب</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">ترتيب حسب</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم أ-ي</option>
                            <option value="examinees_desc" {{ request('sort') == 'examinees_desc' ? 'selected' : '' }}>الأكثر ممتحنين</option>
                            <option value="examinees_asc" {{ request('sort') == 'examinees_asc' ? 'selected' : '' }}>الأقل ممتحنين</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">عدد الصفوف</label>
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary flex-fill">
                            <i class="ti ti-filter me-1"></i>تصفية
                        </button>
                        <a href="{{ route('clusters.index') }}" class="btn btn-outline-secondary ms-2">
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
                                <th>اسم التجمع</th>
                                <th>الحالة</th>
                                <th>عدد الممتحنين</th>
                                <th width="200">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clusters as $cluster)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $cluster->name }}</td>
                                    <td>
                                        <span class="badge {{ $cluster->is_active ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                            <i class="ti {{ $cluster->is_active ? 'ti-circle-check' : 'ti-circle-x' }} me-1"></i>
                                            {{ $cluster->is_active ? 'مفعل' : 'غير مفعل' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('examinees.index', ['cluster_id' => $cluster->id, 'status' => request('examinee_status')]) }}" class="badge bg-primary text-white">
                                            {{ $cluster->examinees_count }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('examinees.index', ['cluster_id' => $cluster->id]) }}" class="btn btn-sm btn-outline-info" title="عرض الممتحنين">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('clusters.edit', $cluster) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="تعديل">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('clusters.toggle', $cluster) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $cluster->is_active ? 'warning' : 'success' }}" 
                                                    title="{{ $cluster->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                <i class="ti {{ $cluster->is_active ? 'ti-toggle-left' : 'ti-toggle-right' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('clusters.destroy', $cluster) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
                                        <i class="ti ti-map-off display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد تجمعات</h5>
                                        <a href="{{ route('clusters.create') }}" class="btn btn-primary mt-2">
                                            <i class="ti ti-plus me-1"></i>إضافة أول تجمع
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($clusters->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            عرض {{ $clusters->firstItem() }} إلى {{ $clusters->lastItem() }} من أصل {{ $clusters->total() }} نتيجة
                        </div>
                        <div>
                            {{ $clusters->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection