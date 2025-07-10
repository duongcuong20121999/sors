<a href="{{ route('accounts-manager.edit', $id) }}?page={{ request('page', 1) }}&role={{ request('role', 'all') }}">
    <div class="card-content d-flex mb-1 " id="news-template">
        <img src="{{ $avatar }}" class="img-fluid" alt="avatar">
        <div class="card-body">
            <p class="card-text-1 mb-2">{{ $name }}</p>
            <div class="d-flex justify-content-between align-items-center">
                {{-- <span class="card-text-2">Tạo ngày: {{$date}} - Quyền: {{$roles}}</span> --}}
                <div class="d-flex flex-column">
                    <span class="card-text-2">Tạo ngày: {{$date}}</span>
                    <span class="card-text-2" style="max-width: 300px">Quyền: {{$roles}}</span>
                </div>
                <input aria-label="checkbox" form="checkbox-role" class="news-checkbox" type="checkbox" {{ $is_active ? 'checked' : '' }} >
            </div>
        </div>
    </div>
</a>
