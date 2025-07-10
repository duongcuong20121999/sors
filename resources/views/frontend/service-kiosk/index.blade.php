
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="{{ asset('frontend/assets/css/management-kiosk.css') }}"> --}}
    @vite('resources/css/management-kiosk.css')
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500&display=swap" rel="stylesheet">
    <title>Quản lý dịch vụ một cửa</title>
</head>

<?php $setting = App\Models\Setting::first(); ?>

<body>
    <header class="header">
        <div class="container-fluid d-flex justify-content-lg-between">
            <div class="header-left d-flex align-items-center">
                <div class="logo-wrapper me-3">
                    <img src="{{ !empty($setting->logo) ? asset($setting->logo) : asset('frontend/assets/images/logo.png') }}" alt="Logo" class="logo" />
                </div>
                <div class="header-text d-flex flex-column">
                    <p class="header-title mb-0">
                        @if (!empty($setting->system_name))
                        {{-- Nếu có chữ SOSR thì đưa vào <span> --}}
                        {!! str_contains($setting->system_name, 'SOSR')
                        ? str_replace('SOSR', '<span class="text-dark">SOSR</span>', $setting->system_name)
                        : $setting->system_name !!}
                        @else
                        HỆ THỐNG ĐĂNG KÍ DỊCH VỤ MỘT CỬA
                        <span class="text-dark">SOSR</span>
                        @endif
                    </p>
                    <span class="address mt-1"> {{ $setting->ward_name ?? 'PHƯỜNG QUANG TRUNG - TỈNH NGHỆ AN' }}</span>
                </div>
            </div>
            <div class="header-right d-flex align-items-center gap-2">
                <div class=" instruct-zalo d-flex justify-content-between">
                    <p class="ms-3 fw-bold m-0 d-flex justify-content-center align-items-center">LẤY SỐ VỚI ZALO <br>
                        MINI
                        APP TẠI ĐÂY</p>
                    <div class="mt-1 image-wrap d-flex align-items-center justify-content-center">
                        <img class="arrow-loop" src="{{ asset('frontend/assets/images/arrow.avif') }}" alt=""
                            style="max-height: 40px;">
                    </div>
                </div>
                <div class="logo-qr">
                    <img src="{{ asset($setting->qr_code ?? '') }}" alt="logo-qr">
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="display-kiosk mt-5 mx-4 ">
            <div class="container-fluid">
                <div class="row">
                    @foreach ($services as $service)

                    @if ($service->is_active != 0 && $service->code != 'HDDV')

                    <div class="col-12 col-sm-6 col-md-4 pt-4 pb-3 px-4">
                        <div class="kiosk text-center">
                            <input type="hidden" class="id-service-kiosk" value="{{ $service->id }}">
                            <div class="kiosk-wrapper my-4">
                                <img src="{{ asset($service->icon) }}" alt="{{ $service->name }}">
                            </div>

                            <span class="mb-3 d-block">
                                QUẦY
                                {{ is_numeric($service->order) ? str_pad($service->order, 2, '0', STR_PAD_LEFT) : $service->order }}:
                                {{ mb_strtoupper($service->name, 'UTF-8') }}
                            </span>
                        </div>
                    </div>
                    @endif
                    @endforeach


                </div>
            </div>

        </div>

    </main>
    <div class="footer-kiosk d-flex align-items-center px-5">
        <div id="fullscreen-trigger" style="cursor: pointer;">
            <img src="{{ asset('frontend/assets/images/zoom.png') }}" alt="full-screen">
        </div>
        <div class="d-flex justify-content-end ms-auto">
            <div class="py-1 d-flex align-items-center gap-4">
                <div class="footer-kiosk-wrapper d-flex justify-content-center align-items-center">
                    <img src="{{ asset('frontend/assets/images/service5.png') }}" alt="">
                </div>
                <label for="">Hỏi đáp pháp luật</label>
            </div>
            <div class="py-1 ps-5 d-flex align-items-center gap-4">
                <div class="footer-kiosk-wrapper d-flex justify-content-center align-items-center">
                    <img src="{{ asset('frontend/assets/images/service6.png') }}" alt="">

                </div>
                <label for="">Hướng dẫn</label>
            </div>
        </div>
    </div>
    <?php $setting = App\Models\Setting::first(); ?>
    <!-- Modal instruct zalo-->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="fw-bold m-0">HƯỚNG DẪN SỬ DỤNG ZALO APP</p>
                </div>
                <div class="modal-body d-flex">
                    <div class="content-guide">
                        {!! $setting->instruction ?? '' !!}
                    </div>
                    <div class="d-flex justify-content-center align-items-center ms-auto mx-auto">
                        <img src="{{ $setting->qr_code ?? '' }}" alt="qr" style="width: 300px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal">Đóng lại</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal open kiosk -->
    <div class="modal fade" id="kioskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <input type="hidden" id="selected-service-id">
                <div class="modal-header">
                    <p class="m-0 service-title fw-bold"></p>
                </div>
                <div class="modal-body d-flex justify-content-evenly">
                    <div class="task-modal" id="task-modal" style="overflow-y: auto;">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{ asset('frontend/assets/images/misson.jpg') }}" alt="">
                        </div>
                        <p class="m-0 fw-bold">NHIỆM VỤ</p>
                        <div id="service-tasks" class="service-tasks">
                            <!-- Nội dung nhiệm vụ sẽ được render vào đây -->
                        </div>
                    </div>

                    <div class="line-modal mx-4"></div>

                    <div class="note-kiosk" id="note-kiosk" style="overflow-y: auto;">
                        <div class="d-flex justify-content-center align-items-center">
                            <img id="note-image" src="{{ asset('frontend/assets/images/note.png') }}" alt="">
                        </div>
                        <p class="m-0 fw-bold">LƯU Ý</p>
                        <div id="service-notes">
                            <!-- Nội dung lưu ý sẽ được render vào đây -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-start align-items-center">
                        <div class="logo-qr" style="cursor: pointer;">
                            <img style="width: 48px;height: 48px;" src="{{ asset($setting->qr_code ?? '') }}"
                                alt="logo-qr">
                        </div>
                        <div class="instruct-zalo d-flex justify-content-between" style="cursor: pointer;width: 200px">
                            <div class="image-wrap d-flex align-items-center justify-content-center">
                                <img class="arrow-loop2" src="{{ asset('frontend/assets/images/arrow-right.avif') }}" alt=""
                                    style="width: 35px;height: 35px;">
                            </div>
                            <p class=" fw-bold m-0 d-flex justify-content-center align-items-center">Kích hoạt Zalo app
                                <br> để lấy số trực tuyến tại đây
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end ms-auto gap-3">
                        <button class="button-stt" type="button" id="btn-get-number">Lấy số thứ tự</button>
                        <button class="button-close" type="button" data-bs-dismiss="modal">Đóng lại</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal open get number -->
    <div class="modal fade" id="numberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="m-0 fw-bold"></p>
                </div>
                <div class="modal-body d-flex">
                    <!-- Cột trái -->
                    <div class="w-50 d-flex flex-column justify-content-center align-items-center">
                        <p class="m-0 fw-bold">Số thứ tự của bạn</p>
                        <div class="modal-body-1">
                            <span style="font-size: 20px;"
                                class="fw-bold mt-2 d-flex justify-content-center align-items-center"></span>
                            <p style="font-size: 48px;"
                                class="fw-bold mt-4 d-flex justify-content-center align-items-center m-0">1008</p>
                        </div>
                        <p class="mt-1 fw-bold">Còn <span id="count-ahead">xx</span> số thự tự trước bạn</p>
                    </div>

                    <!-- Line ở giữa -->
                    <div class="line-modal mx-3" style="width: 1px; background: #ccc;"></div>

                    <!-- Cột phải -->
                    <div class="w-50" style="width: 400px;">
                        <div class="d-flex justify-content-center align-items-center">
                            <img id="note-image2" src="{{ asset('frontend/assets/images/note.png') }}" alt="">
                        </div>
                        <ul>
                            <p class="m-0 fw-bold">LƯU Ý:</p>
                            <li>1. Hưởng ứng văn phòng không giấy và nhu cầu chuyển đổi số. Số thứ tự của bạn sẽ không
                                được in ra.</li>
                            <li>2. Vui lòng ghi lại hoặc chụp lại số thứ tự của mình.</li>
                            <li>3. Khuyến cáo lấy số qua Zalo app sẽ nhanh hơn trong quá trình chứng thực. Hãy kích hoạt
                                Zalo app để lấy số trực tuyến.</li>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-start align-items-center">
                        <div class="logo-qr" style="cursor: pointer;">
                            <img style="width: 48px;height: 48px;" src="{{ asset($setting->qr_code ?? '') }}"
                                alt="logo-qr">
                        </div>
                        <div class="instruct-zalo d-flex justify-content-between" style="cursor: pointer;width: 200px">
                            <div class=" image-wrap d-flex align-items-center justify-content-center">
                                <img class="arrow-loop2" src="{{ asset('frontend/assets/images/arrow-right.avif') }}" alt=""
                                    style="width: 35px;height: 35px;">
                            </div>
                            <p class=" fw-bold m-0 d-flex justify-content-center align-items-center">Kích hoạt Zalo app
                                <br> để lấy số trực tuyến tại đây
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end ms-auto gap-3">
                        <button class="button-close" type="button" data-bs-dismiss="modal">Đóng lại</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const KIOSK_PRINTER_URL = '{{ url("/printer/print") }}';

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

        function openModalById(modalId) {
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                modal.show();
            }
        }

        //open infoModal
        ['.instruct-zalo', '.logo-qr', '#kioskModal .instruct-zalo', '#kioskModal .logo-qr'].forEach(selector => {
            document.querySelectorAll(selector).forEach(el => {
                el.addEventListener('click', () => openModalById('infoModal'));
            });
        });


        document.querySelectorAll('.kiosk').forEach(kiosk => {
            kiosk.addEventListener('click', () => {
                swingNoteOnce('note-image', 3000);
                const serviceId = kiosk.querySelector('.id-service-kiosk').value;
                document.querySelectorAll('.kiosk.selected').forEach(el => el.classList.remove('selected'));
                kiosk.classList.add('selected');
                document.getElementById('selected-service-id').value = serviceId;
                const basePath = window.location.pathname.split('/service-kiosk-manager')[0];

                fetch(`${basePath}/service-kiosk-manager/${serviceId}`)
                    .then(response => response.json())
                    .then(data => {

                        document.querySelector('.service-title').textContent =
                            `DỊCH VỤ: ${data.name.toUpperCase()}`;



                        const tasksContainer = document.getElementById('service-tasks');
                        tasksContainer.innerHTML = data
                            .tasks;


                        const notesContainer = document.getElementById('service-notes');
                        notesContainer.innerHTML = data
                            .notes;


                        const modal = new bootstrap.Modal(document.getElementById('kioskModal'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error("Lỗi khi gọi API: ", error);
                    });
            });
        });

        document.getElementById('btn-get-number').addEventListener('click', function() {
            swingNoteOnce('note-image2', 3000);
            const serviceId = document.getElementById('selected-service-id').value;

            // Kiểm tra xem có ID không
            if (!serviceId) {
                alert("Không tìm thấy ID dịch vụ!");
                return;
            }
            const kioskModal = bootstrap.Modal.getInstance(document.getElementById('kioskModal'));
            if (kioskModal) {
                kioskModal.hide();
            }

            const basePath = window.location.pathname.split('/service-kiosk-manager')[0];
            // Gọi API để lấy thông tin số thứ tự
            fetch(`${basePath}/service-kiosk-manager/get-number/${serviceId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const printData = {
                            sequence_number: data.sequence_number,
                            count_ahead: data.count_ahead,
                            appointment_date: new Date(new Date().getTime() + (7 * 60 * 60 * 1000)).toISOString().replace('T', ' ').slice(0, 19)
                        };

                        console.log('Sending data to printer:', printData);

                        const printTicket = async () => {
                            try {
                                const response = await fetch(KIOSK_PRINTER_URL, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify(printData)
                                });

                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }

                                const result = await response.json();
                                console.log('Call API printer success');
                                return result;
                            } catch (error) {
                                console.error('Cannot call API printer');
                                
                                try {
                                    console.log('Sending error message to server...');
                                    const errorResponse = await fetch(KIOSK_PRINTER_URL, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify({
                                            ...printData,
                                            error: error.message,
                                            error_time: new Date(new Date().getTime() + (7 * 60 * 60 * 1000)).toISOString().replace('T', ' ').slice(0, 19)
                                        })
                                    });

                                    if (!errorResponse.ok) {
                                        throw new Error(`Error report failed with status: ${errorResponse.status}`);
                                    }

                                    const errorResult = await errorResponse.json();
                                    console.log('Error report sent to server successfully');
                                } catch (reportError) {
                                    console.error('Unable to send error report to server:', reportError);
                                }
                            }
                        };

                        // Execute print function
                        printTicket().then(result => {
                            // Hiển thị thông tin vào modal `numberModal`
                            document.querySelector('#numberModal .modal-header p').textContent =
                                `DỊCH VỤ: ${result.service_name.toUpperCase()}`;
                            document.querySelector('#numberModal .modal-body-1 span').textContent =
                                `Quầy số ${String(result.counter).padStart(2, '0')}`;

                            document.querySelector('#numberModal .modal-body-1 p').textContent = result.sequence_number;

                            document.getElementById('count-ahead').textContent = result.count_ahead;

                            // Mở modal
                            const numberModal = new bootstrap.Modal(document.getElementById('numberModal'), {
                                keyboard: false
                            });
                            numberModal.show();
                        }).catch(error => {
                            console.error('Unexpected error in print process:', error);
                        });
                    } else {
                        alert(data.message || "Không thể lấy số thứ tự. Vui lòng thử lại sau.");
                    }
                })
                .catch(error => {
                    console.error("Lỗi khi gọi API:", error);
                });
        });
        document.addEventListener("DOMContentLoaded", function() {
            document.addEventListener('hide.bs.modal', function(event) {
                if (document.activeElement) {
                    document.activeElement.blur();
                }
            });
        });

        function swingNoteOnce(id, duration = 2500) {
            const note = document.getElementById(id);
            if (!note) return;

            note.classList.add('swinging');

            setTimeout(() => {
                note.classList.remove('swinging');
                note.style.transform = '';
            }, duration);
        }


        document.getElementById('kioskModal').addEventListener('show.bs.modal', function() {
            setTimeout(() => {
                const taskModal = document.getElementById('task-modal');
                const noteModal = document.getElementById('note-kiosk');
                if (taskModal) taskModal.scrollTop = 0;
                if (noteModal) noteModal.scrollTop = 0;

            }, 10); // Delay nhỏ để đảm bảo phần tử hiển thị
        });
    </script>


</body>

</html>