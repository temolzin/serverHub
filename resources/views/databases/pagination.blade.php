@if ($databases->hasPages())
    @php
        $current = $databases->currentPage();
        $last = $databases->lastPage();
        $start = max($current - 2, 1);
        $end = min($current + 2, $last);
    @endphp
    <nav>
        <ul class="pagination justify-content-end">
            <li class="page-item {{ $databases->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $databases->onFirstPage() ? '#' : $databases->previousPageUrl() }}"><i class="bx bx-chevron-left"></i></a>
            </li>

            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $databases->url(1) }}">1</a></li>
            @endif

            @if ($start > 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            @for ($page = $start; $page <= $end; $page++)
                <li class="page-item {{ $page == $databases->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $databases->url($page) }}"> {{ $page }}</a></li>
            @endfor

            @if ($end < $last - 1)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            @if ($end < $last)
                <li class="page-item"><a class="page-link" href="{{ $databases->url($last) }}">{{ $last }}</a></li>
            @endif

            <li class="page-item {{ $databases->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $databases->hasMorePages() ? $databases->nextPageUrl() : '#' }}"><i class="bx bx-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
@endif
