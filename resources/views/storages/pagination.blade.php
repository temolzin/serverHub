@if ($storages->hasPages())
    @php
        $current = $storages->currentPage();
        $last = $storages->lastPage();
        $start = max($current - 2, 1);
        $end = min($current + 2, $last);
    @endphp
    <nav>
        <ul class="pagination justify-content-end">
            <li class="page-item {{ $storages->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $storages->onFirstPage() ? '#' : $storages->previousPageUrl() }}"><i class="bx bx-chevron-left"></i></a>
            </li>

            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $storages->url(1) }}">1</a></li>
            @endif

            @if ($start > 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            @for ($page = $start; $page <= $end; $page++)
                <li class="page-item {{ $page == $storages->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $storages->url($page) }}">{{ $page }}</a></li>
            @endfor

            @if ($end < $last - 1)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            @if ($end < $last)
                <li class="page-item"><a class="page-link" href="{{ $storages->url($last) }}">{{ $last }}</a></li>
            @endif

            <li class="page-item {{ $storages->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $storages->hasMorePages() ? $storages->nextPageUrl() : '#' }}"><i class="bx bx-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
@endif
