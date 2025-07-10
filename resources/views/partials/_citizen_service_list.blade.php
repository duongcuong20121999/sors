@foreach ($citizenServices as $cs)
    <div class="display-section d-flex justify-content-between align-items-center ">
        <div class="d-flex align-items-center gap-3" style="flex: 3;">
            <input type="hidden" class="id-citizen-service" value="{{ $cs->id }}">

            <input type="checkbox" class="citizen-service-checkbox mr-3">
            <img src="{{ !empty($cs->citizen->avatar) ? asset($cs->citizen->avatar) : asset('frontend/assets/images/user.avif') }}"
                alt="logo" class="img-fluid rounded-circle" />
            <div>
                <p class="mb-0 name">{{ $cs->citizen->name }}</p>
                <p class="mb-0 location">
                    {{ $cs->citizen->address }}
                </p>
            </div>
        </div>
        <div class="line"></div>
        <div class="service-info" style="flex: 3.5;">
            <div class="mx-5">
                <p class="mb-0 name-service">{{ $cs->service->name }}</p>
                <span class="utc-time"
                    data-time="{{ \Carbon\Carbon::parse($cs->created_date)->setTimezone('Asia/Ho_Chi_Minh')->toIso8601String() }}"></span>
                    <br>
                {{-- <span class="utc-time"
                    data-time="{{ \Carbon\Carbon::parse($cs->appointment_date)->toIso8601String() }}"></span> --}}

            </div>
        </div>
        <div class="line"></div>
        <div style="flex: 2;" class="d-flex justify-content-center ">
            <span class="number-service {{ checkStatusClass($cs->status) }}">{{ $cs->sequence_number }}</span>
        </div>
        <div class="line"></div>
        <div style="flex: 1; font-weight: 400;">
            {!! getStatusCSName($cs->status) !!}
        </div>
        <div class="line"></div>
        <div class="button-main-screen d-flex flex-column justify-content-center align-items-center" style="flex: 1;">
            @if ($cs->status == 2)
                <button type="button" class="custom-btn-4" data-id="{{ $cs->id }}">Hoàn thành</button>
            @elseif ($cs->status == 3)
                <button type="button" class="custom-btn-3" data-id="{{ $cs->id }}">Đóng hồ sơ</button>
            @elseif ($cs->status == 0 || $cs->status == 1)
                <button type="button" class="custom-btn-1" data-id="{{ $cs->id }}">Gọi xử lý</button>
            @endif
            <button type="button" class="custom-btn-2" data-id="{{ $cs->id }}">Hủy yêu cầu</button>
        </div>
    </div>
@endforeach

<style>
    .badge{
        font-weight: normal;
    }
</style>

<script>
    function formatUtcTimes() {
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
    }

    // Gọi hàm này khi trang vừa load lần đầu
    document.addEventListener('DOMContentLoaded', formatUtcTimes);
</script>
