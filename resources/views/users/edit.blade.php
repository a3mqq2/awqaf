@extends('layouts.app')
@section('title', 'تعديل المستخدم')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="row mt-3">
   <div class="col-md-12">
      <div class="card">
         <div class="card-header">
            <h5><i class="ti ti-edit me-2"></i>تعديل المستخدم: {{ $user->name }}</h5>
         </div>
         <div class="card-body">
               <form action="{{ route('users.update', $user) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="row">
                     <!-- الاسم -->
                     <div class="col-md-6 mb-3">
                        <label class="form-label">اسم المستخدم <span class="text-danger">*</span></label>
                        <input type="text" name="name" required class="form-control" value="{{ old('name', $user->name) }}">
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                     </div>
                     
                     <!-- البريد الإلكتروني -->
                     <div class="col-md-6 mb-3">
                        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" name="email" required class="form-control" value="{{ old('email', $user->email) }}">
                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                     </div>
                     
                     <!-- كلمة المرور -->
                     <div class="col-md-6 mb-3">
                        <label class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" name="password" class="form-control" placeholder="اتركها فارغة إذا لم ترد تغييرها">
                        <small class="text-muted">اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور</small>
                        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                     </div>
                     
                     <!-- تأكيد كلمة المرور -->
                     <div class="col-md-6 mb-3">
                        <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" name="password_confirmation" class="form-control">
                     </div>

                     <!-- الدور (Role) -->
                     <div class="col-md-6 mb-3">
                        <label class="form-label">الدور الوظيفي <span class="text-danger">*</span></label>
                        <select name="role" required class="form-select">
                           <option value="">اختر الدور...</option>
                           @php
                              $userRole = old('role', $user->roles->first()->name ?? '');
                           @endphp
                           @foreach($roles as $role)
                              <option value="{{ $role->name }}" {{ $userRole == $role->name ? 'selected' : '' }}>
                                 @if($role->name == 'admin')
                                    مدير النظام
                                 @elseif($role->name == 'committee_supervisor')
                                    مشرف لجنة
                                 @elseif($role->name == 'judge')
                                    محكم
                                 @elseif($role->name == 'committee_control')
                                    كنترول اللجنة
                                 @else
                                    {{ $role->name }}
                                 @endif
                              </option>
                           @endforeach
                        </select>
                        @error('role') <div class="text-danger">{{ $message }}</div> @enderror
                     </div>

                     <!-- الحالة -->
                     <div class="col-md-6 mb-3">
                        <label class="form-label">الحالة</label>
                        <select name="is_active" class="form-select">
                           <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>نشط</option>
                           <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>غير نشط</option>
                        </select>
                     </div>

                     <!-- التجمعات -->
                     <div class="col-md-12 mb-4">
                        <label class="form-label">التجمعات</label>
                        <select name="clusters[]" id="clusters" class="form-select select2" multiple>
                           @php
                               $userClusters = old('clusters', $user->clusters->pluck('id')->toArray());
                           @endphp
                           @foreach($clusters as $cluster)
                              <option value="{{ $cluster->id }}" {{ in_array($cluster->id, $userClusters) ? 'selected' : '' }}>
                                 {{ $cluster->name }}
                              </option>
                           @endforeach
                        </select>
                        <small class="text-muted">اختر التجمعات التي يمكن للمستخدم الوصول إليها</small>
                        @error('clusters') <div class="text-danger">{{ $message }}</div> @enderror
                     </div>

                     <!-- صلاحيات إضافية -->
                     <div class="col-md-12 mb-4">
                        <div class="card">
                           <div class="card-header bg-light">
                              <h6 class="mb-0">
                                 <i class="ti ti-shield-check me-2"></i>
                                 صلاحيات إضافية (اختياري)
                              </h6>
                              <small class="text-muted">سيحصل المستخدم على صلاحيات الدور المحدد + هذه الصلاحيات الإضافية</small>
                           </div>
                           <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                              @if($permissions->count() > 0)
                                 @php
                                    $userPermissions = old('permissions', $user->permissions->pluck('name')->toArray());
                                    
                                    $groupedPermissions = $permissions->groupBy(function($permission) {
                                        $parts = explode('.', $permission->name);
                                        return $parts[0] ?? 'other';
                                    });

                                    $moduleNames = [
                                        'examinees' => 'شؤون الممتحنين',
                                        'users' => 'المستخدمين',
                                        'clusters' => 'التجمعات',
                                        'offices' => 'المكاتب',
                                        'narrations' => 'الروايات',
                                        'drawings' => 'رسوم المصاحف',
                                        'committees' => 'اللجان',
                                        'judges' => 'المحكمين',
                                        'attendance' => 'الحضور',
                                        'system_logs' => 'سجلات النظام',
                                        'backup' => 'النسخ الاحتياطي',
                                    ];
                                 @endphp

                                 @foreach($groupedPermissions as $module => $modulePermissions)
                                    <div class="mb-3 pb-3 border-bottom">
                                       <h6 class="text-primary mb-3">
                                          <i class="ti ti-folder me-1"></i>
                                          {{ $moduleNames[$module] ?? $module }}
                                       </h6>
                                       <div class="row">
                                          @foreach($modulePermissions as $permission)
                                             <div class="col-md-4 col-lg-3 mb-2">
                                                <div class="form-check form-switch">
                                                   <input class="form-check-input" 
                                                          type="checkbox" 
                                                          name="permissions[]" 
                                                          value="{{ $permission->name }}" 
                                                          id="perm_{{ $permission->id }}"
                                                          {{ in_array($permission->name, $userPermissions) ? 'checked' : '' }}>
                                                   <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                      {{ $permission->name_ar ?? $permission->name }}
                                                   </label>
                                                </div>
                                             </div>
                                          @endforeach
                                       </div>
                                    </div>
                                 @endforeach
                              @else
                                 <p class="text-muted text-center mb-0">لا توجد صلاحيات متاحة</p>
                              @endif
                           </div>
                        </div>
                     </div>

                     <!-- معلومات إضافية -->
                     <div class="col-md-12 mb-3">
                        <div class="alert alert-info">
                           <strong><i class="ti ti-info-circle me-1"></i>معلومات إضافية:</strong>
                           <ul class="mb-0 mt-2">
                              <li>تاريخ الإنشاء: {{ $user->created_at->format('Y-m-d H:i:s') }}</li>
                              <li>آخر تحديث: {{ $user->updated_at->format('Y-m-d H:i:s') }}</li>
                           </ul>
                        </div>
                     </div>

                     <!-- أزرار الحفظ -->
                     <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                           <i class="ti ti-device-floppy me-1"></i>حفظ التعديلات
                        </button>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-info">
                           <i class="ti ti-eye me-1"></i>عرض المستخدم
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                           <i class="ti ti-arrow-left me-1"></i>رجوع
                        </a>
                     </div>
                  </div>
               </form>
         </div>
      </div>
   </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // تفعيل Select2 للتجمعات
    $('#clusters').select2({
        placeholder: "اختر التجمعات",
        allowClear: true,
        width: '100%',
        dir: 'rtl'
    });
});
</script>
@endpush