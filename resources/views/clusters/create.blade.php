@extends('layouts.app')

@section('title', 'إضافة تجمع جديد')

@section('content')
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body">
               <form action="{{ route('clusters.store') }}" method="POST">
                  @csrf
                  @method('POST')
                  <div class="row">
                     
                     <div class="col-md-6 mt-2">
                        <label for="">اسم التجمع</label>
                        <input type="text" name="name" required class="form-control" value="{{ old('name') }}">
                        @error('name')
                           <div class="text-danger">{{ $message }}</div>
                        @enderror
                     </div>

                     <div class="col-md-6 mt-2">
                        <label for="">الحالة</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>مفعل</option>
                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>غير مفعل</option>
                        </select>
                        @error('is_active')
                           <div class="text-danger">{{ $message }}</div>
                        @enderror
                     </div>

                     <div class="col-md-12 mt-4">
                        <button type="submit" class="btn btn-primary text-light">
                           <i class="fas fa-save me-2"></i>حفظ
                        </button>
                        <a href="{{ route('clusters.index') }}" class="btn btn-secondary">
                           <i class="fas fa-arrow-left me-2"></i>رجوع
                        </a>
                     </div>
                  </div>
               </form>
         </div>
      </div>
   </div>
</div>
@endsection
