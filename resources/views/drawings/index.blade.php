@extends('layouts.app')

@section('title', 'الرسوم')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الرسوم</li>
@endsection

@section('content')
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="ti ti-brush me-2"></i> قائمة الرسوم</h5>
        <a href="{{ route('drawings.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> إضافة رسم
        </a>
    </div>

    <div class="card-body border-bottom">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="بحث بالاسم"
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">كل الحالات</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>مفعل</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>غير مفعل</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort" class="form-select">
                    <option value="latest" {{ request('sort')=='latest'?'selected':'' }}>الأحدث</option>
                    <option value="oldest" {{ request('sort')=='oldest'?'selected':'' }}>الأقدم</option>
                    <option value="name" {{ request('sort')=='name'?'selected':'' }}>الاسم</option>
                    <option value="examinees_desc" {{ request('sort')=='examinees_desc'?'selected':'' }}>الأكثر ممتحنين</option>
                    <option value="examinees_asc" {{ request('sort')=='examinees_asc'?'selected':'' }}>الأقل ممتحنين</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-secondary w-100">تصفية</button>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        @if($drawings->count())
        <div class="table-responsive">
            <table class="table table-hover text-center align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>عدد الممتحنين</th>
                        <th>الحالة</th>
                        <th>تاريخ الإضافة</th>
                        <th width="220">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drawings as $drawing)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $drawing->name }}</td>
                        <td>
                            <a href="{{ route('examinees.index', ['drawing_id' => $drawing->id]) }}" 
                               class="badge bg-primary text-white">
                                {{ $drawing->examinees_count }}
                            </a>
                        </td>
                        <td>
                            @if($drawing->is_active)
                                <span class="badge bg-success">مفعل</span>
                            @else
                                <span class="badge bg-danger">غير مفعل</span>
                            @endif
                        </td>
                        <td>{{ $drawing->created_at->format('Y-m-d') }}</td>
                        <td class="d-flex justify-content-center gap-2">
                            <a href="{{ route('examinees.index', ['drawing_id' => $drawing->id]) }}" 
                               class="btn btn-sm btn-outline-info" title="عرض الممتحنين">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('drawings.edit', $drawing) }}" class="btn btn-sm btn-warning" title="تعديل">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('drawings.toggle', $drawing) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-info" title="تغيير الحالة">
                                    <i class="ti ti-refresh"></i>
                                </button>
                            </form>
                            <form action="{{ route('drawings.destroy', $drawing) }}" method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من الحذف؟')" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted">
                عرض {{ $drawings->firstItem() }} إلى {{ $drawings->lastItem() }} من أصل {{ $drawings->total() }} نتيجة
            </div>
            {{ $drawings->appends(request()->query())->links() }}
        </div>
        @else
            <div class="alert alert-warning text-center mb-0">لا توجد رسوم حالياً</div>
        @endif
    </div>
</div>
@endsection
