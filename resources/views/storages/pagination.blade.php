@if ($databases->hasPages())
  <nav>
    <ul class="pagination justify-content-end">
      <li class="page-item {{ $databases->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $databases->previousPageUrl() }}">
          <i class="bx bx-chevron-left"></i>
        </a>
      </li>
      @for ($page = 1; $page <= $databases->lastPage(); $page++)
        <li class="page-item {{ $page == $databases->currentPage() ? 'active' : '' }}">
          <a class="page-link" href="{{ $databases->url($page) }}">
            {{ $page }}
          </a>
        </li>
      @endfor
      <li class="page-item {{ $databases->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $databases->nextPageUrl() }}">
          <i class="bx bx-chevron-right"></i>
        </a>
      </li>
    </ul>
  </nav>
@endif
