@if ($users->hasPages())
  <nav>
    <ul class="pagination justify-content-end">
      <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $users->previousPageUrl() }}">
          <i class="bx bx-chevron-left"></i>
        </a>
      </li>
      @for ($page = 1; $page <= $users->lastPage(); $page++)
        <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">
          <a class="page-link" href="{{ $users->url($page) }}">
            {{ $page }}
          </a>
        </li>
      @endfor
      <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $users->nextPageUrl() }}">
          <i class="bx bx-chevron-right"></i>
        </a>
      </li>
    </ul>
  </nav>
@endif
