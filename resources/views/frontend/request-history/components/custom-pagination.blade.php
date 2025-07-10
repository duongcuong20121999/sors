<div class="mt-1">
    @if ($citizenServices->hasPages())
    <ul id="pagination" class="pagination justify-content-center">

        {{-- Nút prev --}}
        <li class="page-item {{ $citizenServices->onFirstPage() ? 'disabled' : '' }}">
            <a aria-label="prev" class="page-link" href="{{ $citizenServices->previousPageUrl() ?? '#' }}"
                data-page="{{ $citizenServices->currentPage() > 1 ? $citizenServices->currentPage() - 1 : 1 }}">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>

        @php
            $current = $citizenServices->currentPage();
            $last = $citizenServices->lastPage();
            $pages = [];

            if ($last <= 7) {
                for ($i = 1; $i <= $last; $i++) {
                    $pages[] = $i;
                }
            } else {
                if ($current <= 4) {
                    $pages = [1, 2, 3, 4, 5, '...', $last];
                } elseif ($current >= $last - 1) {
                    $pages = [1, '...', $last - 4, $last - 3, $last - 2, $last - 1, $last];
                } else {
                    $pages = [$current - 3, $current - 2, $current - 1, $current, $current + 1, '...', $last];
                }
            }
        @endphp

        {{-- Hiển thị các trang --}}
        @foreach ($pages as $page)
            @if ($page === '...')
                <li class="page-item disabled"><span class="page-link">…</span></li>
            @else
                <li class="page-item {{ $current == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $citizenServices->url($page) }}" data-page="{{ $page }}">{{ $page }}</a>
                </li>
            @endif
        @endforeach

        {{-- Nút next --}}
        <li class="page-item {{ !$citizenServices->hasMorePages() ? 'disabled' : '' }}">
            <a aria-label="next" class="page-link" href="{{ $citizenServices->nextPageUrl() ?? '#' }}"
                data-page="{{ $citizenServices->currentPage() < $last ? $citizenServices->currentPage() + 1 : $last }}">
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>

    </ul>
    @endif
</div>