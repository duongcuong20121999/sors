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
            <div class="d-flex align-items-center gap-2">
                <div>
                    <p class="mb-0 name-service">{{ $cs->service->name }}</p>

                    @php
                        $date = \Carbon\Carbon::parse($cs->appointment_date)->setTimezone('Asia/Ho_Chi_Minh');
                    @endphp

                    <span class="time-part">{{ $date->format('H:i') }}</span> -
                    <span class="date-part" style="font-size: 13px;">{{ $date->format('d/m/Y') }}</span>
                </div>

                @if ($cs->citizen->zalo_id != 0000)
                    <img src="{{ asset('frontend/assets/images/zalo.png') }}" alt="Zalo" class="ms-auto"
                        style="width: 25px; height: 25px; border: none; box-shadow: none; opacity: 1;">
                @endif
            </div>
        </div>
        <div class="line"></div>
        <div style="flex: 2;" class="d-flex justify-content-center ">
            <span class="number-service {{ checkStatusClass($cs->status) }}">{{ $cs->sequence_number }}</span>
            <i class="fa-solid fa-volume-high volume-icon"></i>
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
                <button type="button" class="custom-btn-1" data-id="{{ $cs->id }}">Cập nhật</button>
            @endif
            <button type="button" class="custom-btn-2" data-id="{{ $cs->id }}">Hủy yêu cầu</button>
        </div>
    </div>
@endforeach

<style>
    .badge {
        font-weight: normal;
    }
</style>

<script>
    function formatUtcTimes() {
        document.querySelectorAll('.utc-time').forEach(function(el) {
            const utcTime = el.dataset.time;
            const localDate = new Date(utcTime); // JavaScript hiểu đây là UTC
            const formatted = localDate.toLocaleString('vi-VN', {
                timeZone: 'Asia/Ho_Chi_Minh', // 👈 BẮT BUỘC phải có dòng này
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

    document.addEventListener('DOMContentLoaded', formatUtcTimes);
</script>
