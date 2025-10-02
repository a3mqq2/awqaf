@extends('layouts.app')

@section('title', 'إضافة مكتب جديد')

@section('content')
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body">
            <form action="{{ route('offices.store') }}" method="POST">
               @csrf
               <div class="row">
                  <div class="col-md-6 mt-2">
                     <label>اسم المكتب</label>
                     <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                     @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6 mt-2">
                     <label>الحالة</label>
                     <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active',1)==1?'selected':'' }}>مفعل</option>
                        <option value="0" {{ old('is_active')==0?'selected':'' }}>غير مفعل</option>
                     </select>
                  </div>
                  <div class="col-md-12 mt-4">
                     <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>حفظ</button>
                     <a href="{{ route('offices.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>رجوع</a>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection