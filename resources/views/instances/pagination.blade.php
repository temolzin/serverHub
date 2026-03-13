@if ($instances->hasPages())
    @php
        $current = $instances->currentPage();
        $last = $instances->lastPage();
        $start = max($current - 2, 1);
        $end = min($current + 2, $last);
    @endphp
    <nav>
        <ul class="pagination justify-content-end">
            <li class="page-item {{ $instances->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $instances->onFirstPage() ? '#' : $instances->previousPageUrl() }}"><i class="bx bx-chevron-left"></i></a>
            </li>

            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $instances->url(1) }}">1</a></li>
            @endif

            @if ($start > 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            @for ($page = $start; $page <= $end; $page++)
                <li class="page-item {{ $page == $instances->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $instances->url($page) }}"> {{ $page }}</a></li>
            @endfor

            @if ($end < $last - 1)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            @if ($end < $last)
                <li class="page-item"><a class="page-link" href="{{ $instances->url($last) }}">{{ $last }}</a></li>
            @endif

            <li class="page-item {{ $instances->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $instances->hasMorePages() ? $instances->nextPageUrl() : '#' }}"><i class="bx bx-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
@endif
