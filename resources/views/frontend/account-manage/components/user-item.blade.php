<div class="card-content d-flex mb-1" id="news-template"
     onclick="window.location.href='{{ route('accounts-manager.edit', $id) }}?page={{ request('page', 1) }}&role={{ request('role', 'all') }}'">
    <img src="{{ $avatar }}" class="img-fluid" alt="avatar">
    <div class="card-body w-100">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <p class="card-text-1 mb-0">{{ $name }}</p>

            @if (!is_null($service_id))
                <a href="{{ route('number.counter', ['user_id' => $id, 'service_id' => $service_id]) }}"
                   class="btn btn-sm btn-primary"
                   onclick="event.stopPropagation();"> <!-- Ngăn click lan ra div -->
                    Hiển thị tại quầy
                </a>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex flex-column">
                <span class="card-text-2">Tạo ngày: {{ $date }}</span>
                <span class="card-text-2" style="max-width: 300px">Quyền: {{ $roles }}</span>
            </div>

            <input aria-label="checkbox" form="checkbox-role" class="news-checkbox" type="checkbox"
                   {{ $is_active ? 'checked' : '' }}
                   onclick="event.stopPropagation();"> <!-- Ngăn lan click -->
        </div>
    </div>
</div>