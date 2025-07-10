
<a href="{{route('user-roles-manager.edit', $id )}}" >
    <div class="card-content d-flex mb-1" id="news-template">

            <div class="card-body">
                <p class="card-text-1 mb-2">{{ $name }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="card-text-2">
                        Tạo ngày: {{ $created_at->format('d/m/Y') }} - Bởi: {{ $created_by }}
                    </span>
                    <input aria-label="checkbox" class="news-checkbox" type="checkbox" name="selected_roles[]"
                        {{ $is_active ? 'checked' : '' }}>
                </div>
            </div>

    </div>
</a>
