<li class="pc-item">
  <a href="{{ route('dashboard') }}" class="pc-link">
    <span class="pc-micon">
      <svg class="pc-icon"><use xlink:href="#custom-home"></use></svg>
    </span>
    <span class="pc-mtext">الصفحة الرئيسة</span>
  </a>
</li>

@can('examinees.view')

@can('examinees.view')
<li class="pc-item pc-hasmenu">
  <a href="#!" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-users"></i>
    </span>
    <span class="pc-mtext">الممتحنين</span>
    <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
  </a>
  <ul class="pc-submenu">

    
    <li class="pc-item">
      <a class="pc-link" href="{{ route('examinees.index', ['status' => 'under_review']) }}">
        <i class="ti ti-hourglass me-2"></i>
        طلبات الموقع 
        @php
          $underReviewCount = \App\Models\Examinee::where('status', 'under_review')->count();
        @endphp
        @if($underReviewCount > 0)
          <span class="badge bg-info ms-2">{{ $underReviewCount }}</span>
        @endif
      </a>
    </li>
    
    <li class="pc-item">
      <a class="pc-link" href="{{ route('examinees.index', ['status' => 'confirmed']) }}">
        <i class="ti ti-circle-check me-2"></i>
        مؤكد
      </a>
    </li>
    
    <li class="pc-item">
      <a class="pc-link" href="{{ route('examinees.index', ['status' => 'pending']) }}">
        <i class="ti ti-clock me-2"></i>
        قيد التأكيد
      </a>
    </li>
    
    <li class="pc-item">
      <a class="pc-link" href="{{ route('examinees.index', ['status' => 'withdrawn']) }}">
        <i class="ti ti-circle-x me-2"></i>
        منسحب
      </a>
    </li>
    
    <li class="pc-item">
      <a class="pc-link" href="{{ route('examinees.index', ['status' => 'rejected']) }}">
        <i class="ti ti-ban me-2"></i>
        مرفوض
      </a>
    </li>
    <li class="pc-item">
      <a class="pc-link" href="{{ route('examinees.index') }}">
        <i class="ti ti-list me-2"></i>
        جميع الممتحنين
      </a>
    </li>
  </ul>
</li>
@endcan

@endcan

@can('clusters')
<li class="pc-item">
  <a href="{{ route('clusters.index') }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-map-pin"></i>
    </span>
    <span class="pc-mtext">التجمعات</span>
  </a>
</li>
@endcan

@can('offices')
<li class="pc-item">
  <a href="{{ route('offices.index') }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-building"></i>
    </span>
    <span class="pc-mtext">المكاتب</span>
  </a>
</li>
@endcan

@can('narrations')
<li class="pc-item">
  <a href="{{ route('narrations.index') }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-book"></i>
    </span>
    <span class="pc-mtext">الروايات</span>
  </a>
</li>
@endcan

@can('drawings')
<li class="pc-item">
  <a href="{{ route('drawings.index') }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-brush"></i>
    </span>
    <span class="pc-mtext">رسوم المصاحف</span>
  </a>
</li>
@endcan

@can('users')
<li class="pc-item">
  <a href="{{ route('users.index') }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-user"></i>
    </span>
    <span class="pc-mtext">المستخدمين</span>
  </a>
</li>
@endcan

<li class="pc-item">
  <a href="{{ route('logout') }}" class="pc-link">
    <span class="pc-micon"><i class="ti ti-power"></i></span>
    <span class="pc-mtext">تسجيل خروج</span>
  </a>
</li>
