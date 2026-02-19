@if ($storages->hasPages())
  <nav>
    <ul class="pagination justify-content-end">
      <li class="page-item {{ $storages->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $storages->previousPageUrl() }}">
          <i class="bx bx-chevron-left"></i>
        </a>
      </li>
      @for ($page = 1; $page <= $storages->lastPage(); $page++)
        <li class="page-item {{ $page == $storages->currentPage() ? 'active' : '' }}">
          <a class="page-link" href="{{ $storages->url($page) }}">
            {{ $page }}
          </a>
        </li>
      @endfor
      <li class="page-item {{ $storages->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $storages->nextPageUrl() }}">
          <i class="bx bx-chevron-right"></i>
        </a>
      </li>
    </ul>
  </nav>
@endif
