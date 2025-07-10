<div class="mt-1">
    @if ($posts->hasPages())
        @php
            $current = $posts->currentPage();
            $last = $posts->lastPage();
            $category = request('category', 'Lựa chọn tất cả');
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

        <ul id="pagination" class="pagination justify-content-center">

            {{-- Nút Prev --}}
            <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                <a aria-label="pre" class="page-link"
                    href="{{ $posts->previousPageUrl() ? $posts->previousPageUrl() . '&category=' . urlencode($category) : '#' }}"
                    data-page="{{ $posts->currentPage() > 1 ? $posts->currentPage() - 1 : 1 }}">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>

            {{-- Hiển thị các trang --}}
            @foreach ($pages as $page)
                @if ($page === '...')
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @else
                    <li class="page-item {{ $current == $page ? 'active' : '' }}">
                        <a class="page-link"
                           href="{{ $posts->url($page) . '&category=' . urlencode($category) }}"
                           data-page="{{ $page }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Nút Next --}}
            <li class="page-item {{ !$posts->hasMorePages() ? 'disabled' : '' }}">
                <a aria-label="next" class="page-link"
                    href="{{ $posts->nextPageUrl() ? $posts->nextPageUrl() . '&category=' . urlencode($category) : '#' }}"
                    data-page="{{ $posts->currentPage() < $last ? $posts->currentPage() + 1 : $last }}">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>

        </ul>
    @endif
</div>