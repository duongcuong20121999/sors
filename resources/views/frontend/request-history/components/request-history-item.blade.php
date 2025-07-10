@foreach ($citizenServices as $cs)
    <div class="display-section d-flex justify-content-between align-items-center" data-updated-at="{{ \Carbon\Carbon::parse($cs->updated_at)->setTimezone('Asia/Ho_Chi_Minh')->toIso8601String() }}">
        <!-- Cột ảnh + tên + địa chỉ -->
        <div class="d-flex align-items-center gap-3" style="flex: 3.5;">
            <img src="{{ $cs->citizen->avatar ? asset($cs->citizen->avatar) : asset('frontend/assets/images/user.png') }}" alt="logo" class="img-fluid rounded-circle" />
            <div>
                <p class="mb-0 name">{{ $cs->citizen->name }}</p>
                <p class="mb-0 location">{{ $cs->citizen->address }}</p>
            </div>
        </div>

        <div class="line"></div>

        <!-- Cột tên dịch vụ + thời gian -->
        <div class="service-info request" id="service-info" style="flex: 3.5;">
            <div class="mx-5">
                <p class="mb-0 name-service">{{ $cs->service->name }}</p>
                {{-- <span class="utc-time">{{convertDateToVn($cs->created_date) }}</span> --}}
                {{-- <span>{{convertDateToVn($cs->created_date) }}</span> --}}
                <span class="utc-time" data-time="{{ \Carbon\Carbon::parse($cs->created_date)->setTimezone('Asia/Ho_Chi_Minh')->toIso8601String() }}"></span>
            </div>
        </div>

        <div class="line"></div>

        <div class="d-flex flex-column align-items-center" style="flex: 2;">
            <span class="request-code fs-3">{{ $cs->sequence_number ?? 'N/A' }}</span>
        </div>

        <div class="line"></div>

        <!-- Cột trạng thái + cán bộ -->
        <div style="flex: 1.5;" class="d-flex justify-content-center flex-column align-items-center">
            <span class="status {{ checkStatusClass($cs->status) }}">
                {{
                    match($cs->status) {
                        0 => 'Mới',
                        1 => 'Đã xem',
                        2 => 'Đang xử lý',
                        3 => 'Đã hoàn thành',
                        4 => 'Đã đóng',
                        5 => 'Đã từ chối',
                        6 => 'Đã huỷ',
                        default => 'Không xác định',
                    }
                }}
            </span>
            {{-- <span class="officer-name">{{ $cs->officer_name ?? 'Chưa cập nhật' }}</span> --}}
        </div>
    </div>
@endforeach

<script>
    document.querySelectorAll('.utc-time').forEach(function(el) {
        const utcTime = el.dataset.time;
        const localDate = new Date(utcTime);
        const formatted = localDate.toLocaleString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit',
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour12: false,
        });
        el.textContent = formatted.replace(',', ' -');
    });
</script>
