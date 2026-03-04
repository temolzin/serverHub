@if ($gcpMachines->hasPages())
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-end">
      <li class="page-item {{ $gcpMachines->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $gcpMachines->previousPageUrl() }}">
          <i class="bx bx-chevron-left"></i>
        </a>
      </li>
      @php
        $current = $gcpMachines->currentPage();
        $last = $gcpMachines->lastPage();
        $window = 2;
        $start = max($current - $window, 1);
        $end = min($current + $window, $last);
      @endphp
      @for ($page = $start; $page <= $end; $page++)
        <li class="page-item {{ $page == $current ? 'active' : '' }}">
          <a class="page-link" href="{{ $gcpMachines->url($page) }}">
            {{ $page }}
          </a>
        </li>
      @endfor
      <li class="page-item {{ $gcpMachines->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $gcpMachines->nextPageUrl() }}">
          <i class="bx bx-chevron-right"></i>
        </a>
      </li>
    </ul>
  </nav>
@endif
