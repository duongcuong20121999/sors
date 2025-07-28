<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    @vite('resources/css/style.css')
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500&display=swap" rel="stylesheet">
    <title>Quản lý lấy số trực tuyến</title>
</head>

<style>
    .processing-order.text-danger {
        color: red;
    }
</style>

<?php $setting = App\Models\Setting::first(); ?>

<body class="d-flex flex-column min-vh-100">
    <header class="position-relative w-100 overflow-hidden py-3">
        <img class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover z-0"
            src="{{ asset('frontend/assets/images/background-header.png') }}" alt="background-header">

        <div
            class="header-wrapper position-relative z-1 d-flex justify-content-center align-items-center w-100 h-100 flex-wrap py-3">
            <div class="logo-wrapper rounded-circle position-absolute start-0 top-50 translate-middle-y ms-4 z-3 p-3">
                <a class="cursor-pointer" href="#">
                    <img src="{{ !empty($setting->logo) ? asset($setting->logo) : asset('frontend/assets/images/logo.png') }}"
                        alt="logo" class="img-fluid logo">
                </a>
            </div>

            <div class="position-absolute start-50 top-50 translate-middle text-center z-1 w-100">
                <h1 class="fs-3 mb-0 fw-semibold">
                    <span class="text-title">HỆ THỐNG ĐĂNG KÝ DỊCH VỤ MỘT CỬA </span>PHƯỜNG THÀNH VINH - NGHỆ AN
                </h1>
            </div>

            <div id="fullscreen-trigger" class="ms-auto me-4 z-3" style="cursor: pointer;">
                <img src="{{ asset('frontend/assets/images/zoom.png') }}" alt="image button">
            </div>

        </div>
    </header>

    <main class="container-fluid pt-5 mt-5 flex-grow-1">
        @foreach ($services->chunk(3) as $serviceChunk)
            <div class="row mb-5 mx-2">
                @foreach ($serviceChunk as $service)
                    <div class="col-12 col-xl-4" data-service-id="{{ $service['id'] }}">
                        <div class="border counter rounded-4 p-3 text-center position-relative h-100">
                            <p class="text-black mb-0 fs-16">
                                QUẦY {{ str_pad($service['order'], 2, '0', STR_PAD_LEFT) }}:
                                {{ strtoupper($service['name']) }}
                            </p>
                            <hr class="m-1">
                            <div class="d-flex flex-column queue-data">
                                @foreach ($service['queue'] as $item)
                                    @php
                                        $time = \Carbon\Carbon::parse($item->appointment_date)->setTimezone(
                                            'Asia/Ho_Chi_Minh',
                                        );
                                    @endphp
                                    <p
                                        class="{{ $item->status === 1 ? 'processing-order text-danger' : 'wait-order' }} fs-40 fw-medium mb-0">
                                        {{ $item->sequence_number }} - {{ $time->format('H:i') }}
                                    </p>
                                @endforeach
                            </div>

                            <div
                                class="remaining-box d-flex justify-content-center align-items-center position-absolute rounded-circle bottom-0 end-0 fs-24">
                                {{ str_pad($service['remaining'], 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </main>

    <footer class="marquee py-2 overflow-hidden">
        <div class="marquee-content text-white fs-16 fw-semibold">
            <span>
                {{$setting->news_ticker}} &nbsp;&nbsp;&nbsp;
            </span>
            <span>
                {{$setting->news_ticker}} &nbsp;&nbsp;&nbsp;
            </span>
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


        document.addEventListener('DOMContentLoaded', function() {
            window.Echo.channel('service-queue')
                .listen('.queue.updated', (e) => {
                    const serviceId = e.serviceId;
                    const queueData = e.queueData;
                    const remaining = e.remaining;

                    const container = document.querySelector(`[data-service-id="${serviceId}"]`);
                    if (!container) return;

                    const queueBox = container.querySelector('.queue-data');
                    const remainingBox = container.querySelector('.remaining-box');

                    queueBox.innerHTML = '';

                    queueData.forEach(item => {
                        const isProcessing = item.status === 1;
                        queueBox.innerHTML += `
                    <p class="${isProcessing ? 'processing-order text-danger' : 'wait-order'} fs-40 fw-medium mb-0">
                        ${item.sequence_number} - ${formatTime(item.appointment_date)}
                    </p>
                `;
                    });

                    if (remainingBox) {
                        remainingBox.textContent = String(remaining).padStart(2, '0');
                    }
                });

            function formatTime(datetimeStr) {
                const date = new Date(datetimeStr);
                date.setHours(date.getHours() + 7); // ép GMT+7
                return `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
            }
        });
    </script>


</body>

</html>
