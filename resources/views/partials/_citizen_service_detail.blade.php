    <div class="display-section d-flex justify-content-between align-items-center ">
        <div class="d-flex align-items-center gap-3" style="flex: 3;">
            @php

            @endphp
            <input type="hidden" class="id-citizen-service" value="{{ $citizenService->id }}">

            <img src="{{ asset($citizenService->citizen->avatar) }}" alt="logo" class="img-fluid rounded-circle" />
            <div>
                <p class="mb-0 name">{{ $citizenService->citizen->name }}</p>
                <p class="mb-0 location">
                    {{ $citizenService->citizen->address }}
                </p>
            </div>
        </div>
        <div class="line"></div>
        <div class="service-info d-flex justify-content-center" style="flex: 3.5;">
            <div>
                <p class="mb-0 name-service" style="max-width: 180px;">{{ $citizenService->service->name }}</p>
                <span class="utc-time"
                    data-time="{{ \Carbon\Carbon::parse($cs->created_date)->setTimezone('Asia/Ho_Chi_Minh')->toIso8601String() }}"></span>
                    <br>
                {{-- <span class="utc-time"
                    data-time="{{ \Carbon\Carbon::parse($cs->appointment_date)->toIso8601String() }}"></span> --}}

            </div>
        </div>
        <div class="line"></div>
        <div style="flex: 2;" class="d-flex justify-content-center ">
            <span class="number-service {{ checkStatusClass($citizenService->status) }}">{{ $citizenService->sequence_number }}</span>
        </div>
        <div class="button-main-screen d-flex flex-column justify-content-center align-items-center" style="flex: 1;">
            @if ($citizenService->status == 2)
            <button type="button" class="custom-btn-4" data-id="{{ $citizenService->id }}">Hoàn thành</button>
            @elseif ($citizenService->status == 3)
            <button type="button" class="custom-btn-3" data-id="{{ $citizenService->id }}">Đóng hồ sơ</button>
            @elseif ($citizenService->status == 0 || $citizenService->status == 1)
            <button type="button" class="custom-btn-1" data-id="{{ $citizenService->id }}">Gọi xử lý</button>
            @endif
            <button type="button" class="custom-btn-2" data-id="{{ $citizenService->id }}">Hủy yêu cầu</button>
        </div>
    </div>




    {{-- {{ route('dashboard.destroy.process', $citizenService->id) }} --}}
