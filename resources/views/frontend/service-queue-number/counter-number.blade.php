<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    @vite('resources/css/style.css')
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&display=swap" rel="stylesheet">
    <title>Quần lý lấy số trực tuyến</title>
</head>

<?php $setting = App\Models\Setting::first(); ?>

<body class="d-flex flex-column min-vh-100">
    <header class="position-relative w-100 overflow-hidden py-3">
        <img class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover z-0"
            src="{{ asset('frontend/assets/images/background-header.png') }}" alt="background-header">

        <div style="height: 70px;"
            class="header-wrapper position-relative z-1 d-flex justify-content-center align-items-center w-100 flex-wrap py-3">
            <div class="logo-wrapper rounded-circle position-absolute start-0 top-50 translate-middle-y ms-4 z-3 p-3">
                <a class="cursor-pointer" href="#">
                    <img src="{{ !empty($setting->logo) ? asset($setting->logo) : asset('frontend/assets/images/logo.png') }}"
                        alt="logo" class="img-fluid logo">
                </a>
            </div>

            <div class="position-absolute start-50 top-50 translate-middle text-center z-1 w-100">
                <h1 class="fs-3 mb-0 fw-semibold py-4">
                    <span class="text-title">HỆ THỐNG ĐĂNG KÝ DỊCH VỤ MỘT CỬA </span>PHƯỜNG THÀNH VINH - NGHỆ AN
                </h1>
            </div>
        </div>
    </header>

    <main class="py-5 flex-grow-1 mt-5">
        <div class="row mx-2 pt-3">
            <div class="col-12 col-xl-5 d-flex justify-content-center align-items-center flex-column mt-4">
                <img class="img-user-order object-fit-cover img-fluid" src="{{ asset($user->avatar) }}"
                    alt="image user">
                <p class="mt-4 fw-medium" style="font-size: 24px;">{{ mb_strtoupper($user->name, 'UTF-8') }}</p>
            </div>
            <div class="col-12 col-xl-7">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <p class="text-black mb-1 card-order-title">
                        QUẦY {{ str_pad($service->order, 2, '0', STR_PAD_LEFT) }}: {{ strtoupper($service->name) }}
                    </p>
                    <hr class="w-50 mb-5">

                    <div class="queue-container" data-service-id="{{ $service->id }}">
                        <div class="border counter rounded-4 p-3 text-center position-relative mt-4">
                            {{-- Đang xử lý --}}
                            @if ($processing)
                                <p class="processing-order fs-96 fw-medium mb-3 mt-4">
                                    {{ $processing->sequence_number }}
                                </p>
                            @else
                                <p class="processing-order fs-96 fw-medium mb-3 mt-4">--</p>
                            @endif

                            {{-- Đang chờ --}}
                            @if ($waiting)
                                @php
                                    $waitTime = \Carbon\Carbon::parse($waiting->appointment_date)
                                        ->setTimezone('Asia/Ho_Chi_Minh')
                                        ->format('H:i');
                                @endphp
                                <p class="wait-order fs-40 fw-medium mb-0 pe-5 ps-3">
                                    Tiếp theo: {{ $waiting->sequence_number }} - {{ $waitTime }}
                                </p>
                            @else
                                <p class="wait-order fs-40 fw-medium mb-0 pe-5 ps-3">Không có lượt tiếp theo</p>
                            @endif

                            {{-- Số còn lại --}}
                            <div style="margin-top: 20px;"
                                class="remaining-box d-flex justify-content-center align-items-center position-absolute rounded-circle top-0 end-0 fs-24">
                                {{ str_pad($remaining, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

    <footer class="d-flex">
        <div class="marquee py-2 overflow-hidden">
            <div class="marquee-content text-white fs-16 fw-semibold">
                <span>
                    {{ $setting->news_ticker }} &nbsp;&nbsp;&nbsp;
                </span>
                <span>
                    {{ $setting->news_ticker }} &nbsp;&nbsp;&nbsp;
                </span>
            </div>
        </div>
        <div style="background-color: #D31717;cursor: pointer;" id="fullscreen-trigger"
            class="d-flex justify-content-center align-items-center ms-auto px-3 z-3">
            <img src="{{ asset('frontend/assets/images/zoom.png') }}" alt="image button">
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    @vite(['resources/js/app.js'])
    <script>
        const triggerElement = document.getElementById('fullscreen-trigger');

        if (triggerElement) {
            triggerElement.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    const elem = document.documentElement;
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.webkitRequestFullscreen) {
                        elem.webkitRequestFullscreen();
                    } else if (elem.msRequestFullscreen) {
                        elem.msRequestFullscreen();
                    }
                }
            });

            triggerElement.addEventListener('dblclick', () => {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[✔] Listening for counter updates...');

            window.Echo.channel('counter-channel')
                .listen('.counter.updated', (e) => {

                    const serviceId = e.serviceId;
                    const processing = e.processing;
                    const waiting = e.waiting;
                    const remaining = e.remaining;

                    const container = document.querySelector(`[data-service-id="${serviceId}"]`);
                    if (!container) return;

                    const processingEl = container.querySelector('.processing-order');
                    const waitingEl = container.querySelector('.wait-order');
                    const remainingEl = container.querySelector('.remaining-box');

                    // Cập nhật người đang xử lý
                    processingEl.textContent = processing ? processing.sequence_number : '--';

                    // Cập nhật người tiếp theo
                    if (waiting) {
                        waitingEl.textContent =
                            `Tiếp theo: ${waiting.sequence_number} - ${formatTime(waiting.appointment_date)}`;
                    } else {
                        waitingEl.textContent = 'Không có lượt tiếp theo';
                    }

                    // Cập nhật số còn lại
                    remainingEl.textContent = String(remaining).padStart(2, '0');
                });

            function formatTime(datetimeStr) {
                const date = new Date(datetimeStr);


                date.setHours(date.getHours() + 7);

                const hours = date.getHours().toString().padStart(2, '0');
                const minutes = date.getMinutes().toString().padStart(2, '0');

                return `${hours}:${minutes}`;
            }
        });
    </script>
</body>

</html>
