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

    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-4">
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
                    <option value="">الأحدث</option>
                    <option value="oldest" {{ request('sort')=='oldest'?'selected':'' }}>الأقدم</option>
                    <option value="name" {{ request('sort')=='name'?'selected':'' }}>الاسم</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-secondary w-100">تصفية</button>
            </div>
        </form>

        @if($drawings->count())
        <div class="table-responsive">
            <table class="table table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الحالة</th>
                        <th>تاريخ الإضافة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drawings as $drawing)
                    <tr>
                        <td>{{ $drawing->id }}</td>
                        <td>{{ $drawing->name }}</td>
                        <td>
                            @if($drawing->is_active)
                                <span class="badge bg-success">مفعل</span>
                            @else
                                <span class="badge bg-danger">غير مفعل</span>
                            @endif
                        </td>
                        <td>{{ $drawing->created_at->format('Y-m-d') }}</td>
                        <td class="d-flex justify-content-center gap-2">
                            <a href="{{ route('drawings.edit', $drawing) }}" class="btn btn-sm btn-warning">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('drawings.toggle', $drawing) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-info">
                                    <i class="ti ti-refresh"></i>
                                </button>
                            </form>
                            <form action="{{ route('drawings.destroy', $drawing) }}" method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $drawings->links() }}
        @else
            <div class="alert alert-warning text-center">لا توجد رسوم حالياً</div>
        @endif
    </div>
</div>
@endsection