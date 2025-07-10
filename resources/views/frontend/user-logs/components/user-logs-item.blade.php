@foreach ($logs as $log)
    @php
        $details = $log->details;
    @endphp

    <div class="d-flex mt-4 section-dislay-content">
        <div class="d-flex align-items-center" style="flex:1.5;">
            <div class="ms-4 d-flex align-items-start ">
                <input id="{{ $log->id }}" name="ids[]" value="{{ $log->id }}" type="checkbox" class="me-2 child-checkbox" />
            </div>
            <label for="{{ $log->id }}" class="mb-0">{{ $log->citizen_name }}</label>
        </div>

        <div class="line-content"></div>

        <div class="d-flex align-items-center " style="flex: 2; justify-content: start;">
            <p style="color: #1A1B23; font-weight: 400;overflow-wrap: anywhere" class="ms-5 mx-5">
                {{ $log->action }}</p>
        </div>

        <div class="line-content"></div>

        <div class="d-flex align-items-center" style="flex: 3; overflow: hidden;">
            <p class="detail-content mx-5 ms-5 show-user-log" data-id="{{ $log->id }}">
                [CitizenName: {{ $details['CitizenName'] ?? '' }},
                ZaloId: {{ $details['ZaloId'] ?? '' }}...]
            </p>
        </div>

        <div class="line-content"></div>

        <div class="d-flex flex-column justify-content-center align-items-center" style="flex: 1.5;">
            <p class="log-time" data-time="{{ $log->created_at->toIso8601String() }}"></p>
        </div>
    </div>
@endforeach


