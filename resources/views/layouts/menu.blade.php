<li class="pc-item">
  <a href="{{ route('dashboard') }}" class="pc-link">
    <span class="pc-micon">
      <svg class="pc-icon"><use xlink:href="#custom-home"></use></svg>
    </span>
    <span class="pc-mtext">الصفحة الرئيسة</span>
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



<li class="pc-item">
  <a href="{{ route('clusters.index') }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-map-pin"></i>
    </span>
    <span class="pc-mtext">التجمعات</span>
  </a>
</li>

<li class="pc-item">
  <a href="{{ route('offices.index') }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-building"></i>
    </span>
    <span class="pc-mtext">المكاتب</span>
  </a>
</li>

<li class="pc-item">
  <a href="{{ route('narrations.index') }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-book"></i>
    </span>
    <span class="pc-mtext">الروايات</span>
  </a>
</li>


<li class="pc-item">
  <a href="{{ route('drawings.index') }}" class="pc-link">
    <span class="pc-micon">
      <i class="ti ti-brush"></i>
    </span>
    <span class="pc-mtext">رسوم المصاحف</span>
  </a>
</li>



<li class="pc-item">
  <a href="{{ route('logout') }}" class="pc-link">
    <span class="pc-micon"><i class="ti ti-power"></i></span>
    <span class="pc-mtext">تسجيل خروج</span>
  </a>
</li>
