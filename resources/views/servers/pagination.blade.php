@if ($servers->hasPages())
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-end">
      <li class="page-item {{ $servers->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $servers->previousPageUrl() }}">
          <i class="bx bx-chevron-left"></i>
        </a>
      </li>
      @php
        $current = $servers->currentPage();
        $last = $servers->lastPage();
        $start = max($current - 2, 1);
        $end = min($current + 2, $last);
      @endphp
      @for ($page = $start; $page <= $end; $page++)
        <li class="page-item {{ $page == $current ? 'active' : '' }}">
          <a class="page-link" href="{{ $servers->url($page) }}">
            {{ $page }}
          </a>
        </li>
      @endfor
      <li class="page-item {{ $servers->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $servers->nextPageUrl() }}">
          <i class="bx bx-chevron-right"></i>
        </a>
      </li>
    </ul>
  </nav>
@endif
