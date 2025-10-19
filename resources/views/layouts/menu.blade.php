<!-- الصفحة الرئيسية - للجميع -->
<li class="pc-item">
  <a href="{{ route('dashboard') }}" class="pc-link">
    <span class="pc-micon">
      <svg class="pc-icon"><use xlink:href="#custom-home"></use></svg>
    </span>
    <span class="pc-mtext">الصفحة الرئيسة</span>
  </a>
</li>

@php
    $user = Auth::user();
    $committees = $user->committees;
@endphp

{{-- ======= للمحكمين فقط ======= --}}
@if($user->getAllPermissions()->where('name', 'exam.scientific')->count() > 0)
<li class="pc-item">
  <a href="{{ route('judge.dashboard') }}" class="pc-link">
      <span class="pc-micon">
          <i class="ti ti-book"></i>
      </span>
      <span class="pc-mtext">المنهج العلمي</span>

      @php
          if ($committees->isNotEmpty()) {
              $evaluatedExamineeIds = \App\Models\ExamineeEvaluation::where('judge_id', $user->id)
                  ->pluck('examinee_id')
                  ->toArray();

              $writtenWaitingCount = \App\Models\Examinee::where('status', 'attended')
                  ->when(!empty($evaluatedExamineeIds), function($query) use ($evaluatedExamineeIds) {
                      return $query->whereNotIn('id', $evaluatedExamineeIds);
                  })
                  ->count();
          } else {
              $writtenWaitingCount = 0;
          }
      @endphp

      @if($writtenWaitingCount > 0)
          <span class="badge bg-warning rounded-pill ms-2">{{ $writtenWaitingCount }}</span>
      @endif
  </a>
</li>
@endif

@if($user->getAllPermissions()->where('name', 'exam.oral')->count() > 0)
<li class="pc-item">
  <a href="{{ route('judge.oral.dashboard') }}" class="pc-link">
      <span class="pc-micon">
          <i class="ti ti-microphone"></i>
      </span>
      <span class="pc-mtext">الاختبار الشفهي</span>

      @php
          if ($committees->isNotEmpty()) {
              $oralEvaluatedIds = \App\Models\OralEvaluation::where('judge_id', $user->id)
                  ->pluck('examinee_id')
                  ->toArray();

              $oralWaitingCount = \App\Models\Examinee::where('status', 'attended')
                  ->whereHas('evaluations', function($q) {
                      $q->where('status', 'completed')
                        ->where('score', '>=', 28);
                  })
                  ->when(!empty($oralEvaluatedIds), function($query) use ($oralEvaluatedIds) {
                      return $query->whereNotIn('id', $oralEvaluatedIds);
                  })
                  ->count();
          } else {
              $oralWaitingCount = 0;
          }
      @endphp

      @if($oralWaitingCount > 0)
          <span class="badge bg-info rounded-pill ms-2">{{ $oralWaitingCount }}</span>
      @endif
  </a>
</li>
@endif

{{-- ======= قائمة الممتحنين ======= --}}
@if($user->getAllPermissions()->where('name', 'examinees.view')->count() > 0)
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

    @if($user->getAllPermissions()->where('name', 'attendance.view')->count() > 0)
    <li class="pc-item">
      <a class="pc-link" href="{{ route('examinees.index', ['status' => 'attended']) }}">
        <i class="ti ti-user-check me-2"></i>
        حضر
        @php
          $attendedCount = \App\Models\Examinee::where('status', 'attended')->count();
        @endphp
        @if($attendedCount > 0)
          <span class="badge bg-success ms-2">{{ $attendedCount }}</span>
        @endif
      </a>
    </li>
    @endif
    
    <li class="pc-item"><a class="pc-link" href="{{ route('examinees.index', ['status' => 'pending']) }}"><i class="ti ti-clock me-2"></i>قيد التأكيد</a></li>
    <li class="pc-item"><a class="pc-link" href="{{ route('examinees.index', ['status' => 'withdrawn']) }}"><i class="ti ti-circle-x me-2"></i>منسحب</a></li>
    <li class="pc-item"><a class="pc-link" href="{{ route('examinees.index', ['status' => 'rejected']) }}"><i class="ti ti-ban me-2"></i>مرفوض</a></li>
    <li class="pc-item"><a class="pc-link" href="{{ route('examinees.index') }}"><i class="ti ti-list me-2"></i>جميع الممتحنين</a></li>
  </ul>
</li>
@endif

@if($user->getAllPermissions()->where('name', 'attendance.mark')->count() > 0)
<li class="pc-item">
  <a href="{{ route('attendance.index') }}" class="pc-link">
    <span class="pc-micon"><i class="ti ti-clipboard-check"></i></span>
    <span class="pc-mtext">تسجيل الحضور</span>
  </a>
</li>
@endif

@if($user->getAllPermissions()->where('name', 'reports.examinees')->count() > 0)
<li class="pc-item">
  <a href="{{ route('reports.examinees') }}" class="pc-link">
    <span class="pc-micon"><i class="ti ti-report"></i></span>
    <span class="pc-mtext">تقرير الممتحنين</span>
  </a>
</li>
@endif

@if($user->getAllPermissions()->where('name', 'committees.view')->count() > 0)
<li class="pc-item">
  <a href="{{ route('committees.index') }}" class="pc-link">
    <span class="pc-micon"><i class="ti ti-flag"></i></span>
    <span class="pc-mtext">اللجان</span>
  </a>
</li>
@endif

@if($user->getAllPermissions()->where('name', 'judges.view')->count() > 0)
<li class="pc-item">
  <a href="{{ route('judges.index') }}" class="pc-link">
    <span class="pc-micon"><i class="ti ti-user"></i></span>
    <span class="pc-mtext">المحكمين</span>
  </a>
</li>
@endif

@if($user->getAllPermissions()->where('name', 'clusters')->count() > 0)
<li class="pc-item"><a href="{{ route('clusters.index') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-map-pin"></i></span><span class="pc-mtext">التجمعات</span></a></li>
@endif

@if($user->getAllPermissions()->where('name', 'offices')->count() > 0)
<li class="pc-item"><a href="{{ route('offices.index') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-building"></i></span><span class="pc-mtext">المكاتب</span></a></li>
@endif

@if($user->getAllPermissions()->where('name', 'narrations')->count() > 0)
<li class="pc-item"><a href="{{ route('narrations.index') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-book"></i></span><span class="pc-mtext">الروايات</span></a></li>
@endif

@if($user->getAllPermissions()->where('name', 'drawings')->count() > 0)
<li class="pc-item"><a href="{{ route('drawings.index') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-brush"></i></span><span class="pc-mtext">رسوم المصاحف</span></a></li>
@endif

@if($user->getAllPermissions()->where('name', 'users')->count() > 0)
<li class="pc-item"><a href="{{ route('users.index') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-users"></i></span><span class="pc-mtext">المستخدمين</span></a></li>
@endif

@if($user->getAllPermissions()->where('name', 'backup')->count() > 0)
<li class="pc-item"><a href="{{ route('backup.download') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-database-export"></i></span><span class="pc-mtext">نسخة احتياطية</span></a></li>
@endif

@if($user->getAllPermissions()->where('name', 'system_logs')->count() > 0)
<li class="pc-item"><a href="{{ route('system_logs.index') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-file-text"></i></span><span class="pc-mtext">سجلات النظام</span></a></li>
@endif

<li class="pc-item">
  <a href="{{ route('logout') }}" class="pc-link">
    <span class="pc-micon"><i class="ti ti-power"></i></span>
    <span class="pc-mtext">تسجيل خروج</span>
  </a>
</li>
