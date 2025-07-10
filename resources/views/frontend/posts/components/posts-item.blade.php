<div class="post-item {{ $id == request()->segment(2) ? 'active' : '' }}" id="post-{{ $id }}">
    <a href="{{ route('posts.edit', $id) }}?page={{ request('page', 1) }}&category={{ request('category', 'Lựa chọn tất cả') }}">
        <div class="card-news d-flex mb-3">
            <img src="{{ $thumbnail }}" class="img-fluid" alt="Tin tức">
            <div class="card-body">
                <p class="card-text-1 mb-2">{{ $title }}</p>
                <div class="d-flex align-items-center">
                    <div class="d-flex flex-column">
                        <span class="card-text-2 mr-2">Đăng ngày: {{ $date }}</span>
                        <span class="card-text-2">Bởi: {{ $author }}</span>
                    </div>
                    <div class="checkbox-wrapper d-flex justify-content-center align-items-center" style="margin-left: auto;">
                        <input aria-label="Chọn tin" class="news-checkbox" type="checkbox" {{ $post_publish ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

