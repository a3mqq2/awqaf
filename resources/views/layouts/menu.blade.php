<li class="pc-item">
  <a href="{{ route('dashboard') }}" class="pc-link">
    <span class="pc-micon">
      <svg class="pc-icon"><use xlink:href="#custom-home"></use></svg>
    </span>
    <span class="pc-mtext">الصفحة الرئيسة</span>
  </a>
</li>

@can('examinees')

<li class="pc-item">
  <a href="{{ route('examinees.index', ['status' => 'under_review']) }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-users"></i>
    </span>
    <span class="pc-mtext">طلبات الموقع الالكتروني</span>
  </a>
</li>

<li class="pc-item">
  <a href="{{ route('examinees.index') }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-users"></i>
    </span>
    <span class="pc-mtext">الممتحنين</span>
  </a>
</li>
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
