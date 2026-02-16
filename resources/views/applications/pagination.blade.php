@if ($applications->hasPages())
  <nav>
    <ul class="pagination justify-content-end">
      <li class="page-item {{ $applications->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $applications->previousPageUrl() }}">
          <i class="bx bx-chevron-left"></i>
        </a>
      </li>
      @for ($page = 1; $page <= $applications->lastPage(); $page++)
        <li class="page-item {{ $page == $applications->currentPage() ? 'active' : '' }}">
          <a class="page-link" href="{{ $applications->url($page) }}">
            {{ $page }}
          </a>
        </li>
      @endfor
      <li class="page-item {{ $applications->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $applications->nextPageUrl() }}">
          <i class="bx bx-chevron-right"></i>
        </a>
      </li>
    </ul>
  </nav>
@endif
