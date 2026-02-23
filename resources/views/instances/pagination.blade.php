@if ($instances->hasPages())
  <nav>
    <ul class="pagination justify-content-end">
      {{-- Previous --}}
      <li class="page-item {{ $instances->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $instances->previousPageUrl() }}">
          <i class="bx bx-chevron-left"></i>
        </a>
      </li>
      @for ($page = 1; $page <= $instances->lastPage(); $page++)
        <li class="page-item {{ $page == $instances->currentPage() ? 'active' : '' }}">
          <a class="page-link" href="{{ $instances->url($page) }}">
            {{ $page }}
          </a>
        </li>
      @endfor
      <li class="page-item {{ $instances->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $instances->nextPageUrl() }}">
          <i class="bx bx-chevron-right"></i>
        </a>
      </li>
    </ul>
  </nav>
@endif
