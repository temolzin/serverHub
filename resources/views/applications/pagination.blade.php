@if ($applications->hasPages())
    @php
        $current = $applications->currentPage();
        $last = $applications->lastPage();
        $start = max($current - 2, 1);
        $end = min($current + 2, $last);
    @endphp
    <nav>
        <ul class="pagination justify-content-end">
            <li class="page-item {{ $applications->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $applications->onFirstPage() ? '#' : $applications->previousPageUrl() }}"><i class="bx bx-chevron-left"></i></a>
            </li>

            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $applications->url(1) }}">1</a></li>
            @endif

            @if ($start > 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            @for ($page = $start; $page <= $end; $page++)
                <li class="page-item {{ $page == $applications->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $applications->url($page) }}">{{ $page }}</a>
                </li>
            @endfor

            @if ($end < $last - 1)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            @if ($end < $last)
                <li class="page-item"><a class="page-link" href="{{ $applications->url($last) }}">{{ $last }}</a></li>
            @endif

            <li class="page-item {{ $applications->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $applications->hasMorePages() ? $applications->nextPageUrl() : '#' }}"><i class="bx bx-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
@endif
