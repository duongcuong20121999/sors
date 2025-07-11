@extends('layouts.master')

@section('title', 'Dashboard Admin')

@section('meta_description', 'Trang quản trị dành cho cán bộ theo dõi, xử lý và giám sát đăng ký dịch vụ.')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    {{-- <div class="alert alert-danger">{{ session('message') }}</div> --}}
    <div class="main-screen show">
        <div class="header-main-screen d-flex justify-content-end">
            <div style="margin-right: 20px">
                <input type="checkbox" id="check-all-citizen-service">
            </div>

            <div style="margin-right: 10px">
                <a href="javascript:void(0);"
                    class="destroy-btn bg-danger d-flex justify-content-center align-items-center text-center"
                    id="destroyBtnCitizenService">
                    Huỷ
                </a>
            </div>
            <!-- Chọn trạng thái -->
            <div class="choose-status d-flex align-items-center" style="margin-right: 20px">
                <label class="m-2">Chọn trạng thái:</label>
                <p id="selected-status" class="mb-0"></p>

                <div class="dropdown ms-2">
                    <button class="btn btn-outline-secondary p-2 d-flex align-items-center" type="button"
                        id="statusDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <ion-icon name="chevron-down-outline" class="ms-2"></ion-icon>
                    </button>

                    <ul class="dropdown-menu" aria-labelledby="statusDropdownButton" id="status-dropdown-list">
                        <li><a class="dropdown-item1 status-option" href="#" data-status="">Tất cả trạng thái</a></li>
                        @foreach ($statusOptions as $key => $label)
                            <li style="margin-left: 10px">
                                <label class="d-flex align-items-center">
                                    <input class="form-check-input me-2 status-checkbox" type="checkbox"
                                        value="{{ $key }}">
                                    {{ $label }}
                                </label>


                            </li>
                        @endforeach
                    </ul>
                </div>

                <input type="hidden" id="value-status" name="value_status" value="">
            </div>

            <!-- Chọn dịch vụ -->
            <div class="choose-service d-flex align-items-center">
                <label for="choose-service" class="m-2">Chọn dịch vụ:</label>
                <p id="selected-service" class="mb-0"></p>

                <div class="dropdown ms-2">
                    <button class="btn btn-outline-secondary p-2 d-flex align-items-center" type="button"
                        id="serviceDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <ion-icon name="chevron-down-outline" class="ms-2" id="dropdown-icon"></ion-icon>
                    </button>

                    <ul class="dropdown-menu" aria-labelledby="serviceDropdownButton" id="dropdown-list">
                        <li><a class="dropdown-item service-filter-option" href="#" data-code="">Tất cả dịch vụ</a>
                        </li>
                        @foreach ($services as $s)
                            <li style="margin-left: 10px">
                                <label class="d-flex align-items-center">
                                    <input class=" me-2 service-checkbox" type="checkbox" value="{{ $s->code }}">
                                    {{ $s->name }} (Quầy số {{ $s->order }})
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <input type="hidden" id="service-code" name="service_code" value="">
            </div>


            <div class="search">
                <input placeholder="Tên Công dân cần tìm" class="input" id="search-citizen"
                    value="{{ request('service_code') }}">

                <svg class="icon search-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <g>
                        <path
                            d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                        </path>
                    </g>
                </svg>
            </div>
        </div>

        @if (isset($citizenServices))
            <div id="citizen-services-list" class="display-main-screen">
                <div id="citizen-service-list">
                    @include('partials._citizen_service_list', ['citizenServices' => $citizenServices])
                </div>
            </div>
        @endif

        @if (isset($citizenService))
            <div id="citizen-service-detail" class="display-detail-screen">
                @include('partials._citizen_service_detail', ['citizenService' => $citizenService])
            </div>
        @endif
    </div>

    </div>



    <div id="modalContainer">
        <form id="processForm" method="POST">
            @csrf
            <div class="modal fade" id="processModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                {{-- <div class="modal fade" id="processModal" tabindex="-1" aria-labelledby="processModalLabel"
                data-bs-backdrop="false"> --}}
                <div class="modal-dialog modal-dialog-centered modal-lg custom-modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: #000000" id="processModalLabel">
                            </h5>
                            <div class="close-modal-btn">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 d-flex align-items-center">
                                <input type="hidden" id="citizenServiceId">
                                <label for="citizenAddress" class="form-label me-2 mb-0" style="min-width: 70px">Địa
                                    chỉ:</label>
                                <input type="text" name="address" class="form-control" id="citizenAddress" />
                            </div>
                            <div class="mb-3">
                                <label for="processNote" class="form-label">Ghi chú:</label>
                                <div id="editor-done" class="quill-editor"></div>
                                <input type="hidden" name="citizen_note" id="citizen_note">
                            </div>
                            <input type="hidden" name="status" class="status" id="status">


                            {{-- <div class="form-check d-flex justify-content-end mt-3">
                                <input class="form-check-input" type="checkbox" id="startProcessing"
                                    name="start_processing">
                                <label class="form-check-label ms-2" for="startProcessing">
                                    Bắt đầu xử lý
                                </label>
                            </div> --}}

                        </div>



                        <div class="modal-footer custom-modal-footer">
                            <div class="note-footer">
                                <p class="mb-0">
                                    + Chọn “Hủy” để hủy yêu cầu dịch vụ.
                                </p>
                                <p class="mb-0">
                                    + Chọn “Lưu lại” để lưu ghi chú và bắt đầu quy
                                    trình xử lý hồ sơ.
                                </p>
                                <p class="mb-0">
                                    + Chọn “Đóng” để thoát cửa sổ và không lưu ghi
                                    chú.
                                </p>
                            </div>
                            <div class="d-flex gap-4">
                                <a href="javascript:void(0);"
                                    class="custom-cancel-btn d-flex justify-content-center align-items-center text-center"
                                    id="cancelButton">
                                    Hủy
                                </a>
                                <button type="submit" class="custom-save-btn" id="saveButton">
                                    Lưu lại
                                </button>
                                <button type="button" class="custom-close-btn" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="doneForm" method="POST">
            @csrf
            <div class="modal fade" id="doneModal" tabindex="-1" aria-labelledby="doneModalLabel"
                data-bs-backdrop="false">
                <div class="modal-dialog modal-dialog-centered modal-lg custom-modal">
                    <div class="modal-content">
                        <input type="hidden" name="status" class="status" id="status-complete">
                        <div class="modal-header">
                            <h5 class="modal-title" id="doneModalLabel"></h5>
                            <div class="close-modal-btn">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 d-flex align-items-center">
                                <input type="hidden" id="citizenServiceId_done">
                                <label for="citizenAddress_done" class="form-label me-2 mb-0" style="min-width: 70px">Địa
                                    chỉ:</label>
                                <input type="text" name="address" class="form-control ps-3 py-2"
                                    id="citizenAddress_done" />
                            </div>
                            <div class="mb-3">
                                <label for="processNote" class="form-label">Ghi chú:</label>
                                <div id="editor-complete" class="quill-editor"></div>
                                <input type="hidden" name="citizen_note" id="citizen_note_done">
                            </div>
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <input id="confirmed-completion" style="width: 25px; height: 25px;" type="checkbox"
                                    name="cf_completed">
                                <label style="color: #4F4F4F;" for="confirmed-completion">Xác nhận hoàn thành</label>
                            </div>
                        </div>
                        <div class="modal-footer custom-modal-footer d-flex justify-content-end">
                            <div class="d-flex gap-4">
                                {{-- <button type="button" class="custom-cancel-btn" onclick="openConfirmModal()">
                                    Hủy
                                </button> --}}
                                <button type="submit" class="custom-save-btn" id="saveDoneButton">
                                    Lưu lại
                                </button>
                                <button type="button" class="custom-close-btn" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="closeForm" method="POST">
            @csrf

            <div class="modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel"
                data-bs-backdrop="false">
                <div class="modal-dialog modal-dialog-centered modal-lg custom-modal">
                    <div class="modal-content">
                        <input type="hidden" name="status" class="status" id="status-close">
                        <input type="hidden" id="citizenServiceId_close">
                        <div class="modal-header">
                            <p class="modal-title" id="closeModalLabel">
                            </p>
                            <div class="close-modal-btn">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 d-flex align-items-center">
                                <label for="citizenAddress" class="form-label me-2 mb-0" style="min-width: 70px">Địa
                                    chỉ:</label>
                                <input type="text" name="address" class="form-control ps-3 py-2"
                                    id="citizenAddress_close" />
                            </div>
                            <div class="mb-3">
                                <label for="processNote" class="form-label">Ghi chú:</label>
                                <div id="editor-close" class="quill-editor"></div>
                                <input type="hidden" name="citizen_note" id="citizen_note_close">
                            </div>
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <input id="confirmed-close" style="width: 25px; height: 25px;" type="checkbox"
                                    name="cf_cancel">
                                <label style="color: #4F4F4F;" for="confirmed-close">Xác nhận
                                    đóng hồ sơ</label>
                            </div>
                        </div>
                        <div class="modal-footer custom-modal-footer d-flex justify-content-end">
                            <div class="d-flex gap-4">
                                {{-- <button type="button" class="custom-cancel-btn">
                                    Hủy
                                </button> --}}
                                <button type="submit" class="custom-save-btn" id="submit-close">
                                    Lưu lại
                                </button>

                                <button type="button" class="custom-close-btn" id="btn-close"
                                    data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <form action="">
        <div class="modal fade" id="doneModal" tabindex="-1" aria-labelledby="doneModalLabel"
            data-bs-backdrop="false">
            <div class="modal-dialog modal-dialog-centered modal-lg custom-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title" id="doneModal">
                        </p>
                        <div class="close-modal-btn">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 d-flex align-items-center">
                            <label for="citizenAddress" class="form-label me-2 mb-0" style="min-width: 70px">Địa
                                chỉ:</label>
                            <input type="text" class="form-control ps-3 py-2" id="citizenAddress_done" />
                        </div>
                        <div class="mb-3">
                            <label for="processNote" class="form-label">Ghi chú:</label>
                            <div id="editor-done" class="quill-editor"></div>
                        </div>
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <input id="confirmed-completion" style="width: 25px; height: 25px;" type="checkbox" checked
                                disabled>
                            <label style="color: #4F4F4F;" for="confirmed-completion">Xác nhận
                                hoàn thành</label>
                        </div>
                    </div>
                    <div class="modal-footer custom-modal-footer d-flex justify-content-end">
                        <div class="d-flex gap-4">
                            {{-- <button type="button" class="custom-cancel-btn" onclick="openConfirmModal()">
                                Hủy
                            </button> --}}
                            <button type="button" class="custom-save-btn" data-modal-id="doneModal"
                                data-toast-type="success" onclick="handleSave(this)">
                                Lưu lại
                            </button>
                            <button type="button" class="custom-close-btn" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>




    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true"
        data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-lg confirm-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="confirmModalLabel">Hủy yêu cầu</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="my-3">Bạn có chắc chắn muốn hủy yêu cầu từ :</p>
                    <div class="info-grid mt-2">
                        <div class="info-item">
                            <label>Họ và tên:</label>
                            <span id="confirm-name"></span>
                        </div>
                        <div class="info-item">
                            <label>STT:</label>
                            <span id="confirm-stt"></span>
                        </div>
                        <div class="info-item">
                            <label>SĐT:</label>
                            <span id="confirm-phone"></span>
                        </div>
                        <div class="info-item">
                            <label>Dịch vụ:</label>
                            <span id="confirm-service"></span>
                        </div>
                        <div class="info-item">
                            <label>Địa chỉ:</label>
                            <span id="confirm-location" class="location"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer mt-3">
                    <button type="button" class="yes-confirm" data-id="0">Đồng ý</button>
                    <button type="button" class="no-confirm" data-bs-dismiss="modal">Không</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        let allStatusSelected = false;

        $(document).on('click', '.status-option', function(e) {
            e.preventDefault();
            allStatusSelected = !allStatusSelected;

            $('.status-checkbox').prop('checked', allStatusSelected).trigger('change');
        });

        let allServicesSelected = false;

        $(document).on('click', '.service-filter-option', function(e) {
            e.preventDefault();
            allServicesSelected = !allServicesSelected;

            $('.service-checkbox').prop('checked', allServicesSelected).trigger('change');
        });

        $(document).on('change', '.status-checkbox', function() {
            const selectedLabels = $('.status-checkbox:checked').map(function() {
                return $(this).closest('label').text().trim();
            }).get();

            $('#selected-status').text(selectedLabels.join(', '));
        });

        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.service-filter-option').forEach(function(item) {
                item.addEventListener('click', function(event) {
                    event.preventDefault();


                    const status = item.getAttribute('data-status');


                    document.getElementById('value-status').value = status;

                });
            });

            document.querySelectorAll('.status-option').forEach(function(item) {
                item.addEventListener('click', function(event) {
                    event.preventDefault();


                    const serviceCode = item.getAttribute('data-code');


                    document.getElementById('service-code').value = serviceCode;

                });
            });
        });



        function getFilterValues() {
            const citizenName = $('#search-citizen').val();
            const serviceCode = $('#service-code').val(); // JSON string
            const status = $('#value-status').val(); // JSON string

            return {
                citizenName,
                serviceCode,
                status
            };
        }

        // Thay đổi checkbox dịch vụ
        $(document).on('change', '.service-checkbox', function() {
            const selectedServiceCodes = $('.service-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            const selectedServiceNames = $('.service-checkbox:checked').map(function() {
                return $(this).closest('label').text().trim();
            }).get();

            $('#selected-service').text(selectedServiceNames.join(', '));
            $('#service-code').val(JSON.stringify(selectedServiceCodes)); // set input ẩn

            const {
                citizenName,
                status
            } = getFilterValues();
            fetchCitizenServices(JSON.stringify(selectedServiceCodes), citizenName, status);
        });

        // Thay đổi checkbox trạng thái
        $(document).on('change', '.status-checkbox', function() {
            const selectedStatuses = $('.status-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            const selectedLabels = $('.status-checkbox:checked').map(function() {
                return $(this).closest('label').text().trim();
            }).get();

            $('#selected-status').text(selectedLabels.join(', '));
            $('#value-status').val(JSON.stringify(selectedStatuses)); // set input ẩn

            const {
                citizenName,
                serviceCode
            } = getFilterValues();
            fetchCitizenServices(serviceCode, citizenName, JSON.stringify(selectedStatuses));
        });

        // Nhấn icon tìm kiếm
        $('.search-icon').on('click', function() {
            const {
                citizenName,
                serviceCode,
                status
            } = getFilterValues();
            fetchCitizenServices(serviceCode, citizenName, status);
        });

        // Nhấn Enter trong ô tìm kiếm
        $('#search-citizen').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                const {
                    citizenName,
                    serviceCode,
                    status
                } = getFilterValues();
                fetchCitizenServices(serviceCode, citizenName, status);
            }
        });

        function fetchCitizenServices(serviceCode, citizenName, statusArray) {
            $.ajax({
                url: "{{ route('dashboard') }}",
                method: 'GET',
                data: {
                    service_code: JSON.stringify(serviceCodes),
                    citizen_name: citizenName,
                    value_status: JSON.stringify(statusArray)
                },
                success: function(res) {
                    $('#citizen-service-list').html(res);
                },
                error: function() {
                    alert('Có lỗi xảy ra!');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý khi nhấn nút Đóng (không còn cập nhật status tại đây nữa)
            // document.querySelectorAll('.custom-close-btn, .btn-close').forEach(function(btn) {
            //     btn.addEventListener('click', function() {
            //         // Chỉ đóng modal, không cập nhật gì
            //         console.log('Modal closed – no update triggered here.');
            //     });
            // });

            // Xử lý checkbox "Bắt đầu xử lý"
            const checkbox = document.getElementById('startProcessing');

            if (checkbox) {
                checkbox.addEventListener('click', function () {
                    const isChecked = this.checked ? 1 : 0;

                    const citizenServiceId = document.getElementById('citizenServiceId')?.value ||
                        document.getElementById('citizenServiceId_done')?.value ||
                        document.getElementById('citizenServiceId_close')?.value;

                    const serviceCode = document.getElementById('service-code')?.value || '';
                    const citizenName = document.getElementById('search-citizen')?.value || '';
                    const status = isChecked === 1 ? 1 : 0;

                    if (citizenServiceId) {
                        const basePath = window.location.pathname.split('/dashboard')[0];

                        fetch(`${basePath}/dashboard/citizen-service/update-status?service_code=${serviceCode}&citizen_name=${citizenName}&status=${status}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: JSON.stringify({
                                    id: citizenServiceId,
                                    start_processing: isChecked,
                                    status: status
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.querySelector('#citizen-service-list').innerHTML = data
                                        .updatedView;
                                    renderUtcTimes();
                                } else {
                                    console.error('Cập nhật thất bại:', data.message);
                                }
                            })
                            .catch(error => console.error('Lỗi:', error));
                    }
                });
            }

            // Xử lý hiển thị thời gian
            function renderUtcTimes() {
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

            // Gọi khi trang load
            renderUtcTimes();
        });





        function createQuillEditor(selector) {
            const editor = new Quill(selector, {
                theme: "snow",
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline"],
                        [{
                            size: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18']
                        }],
                        [{
                                align: ""
                            },
                            {
                                align: "center"
                            },
                            {
                                align: "right"
                            },
                            {
                                align: "justify"
                            }
                        ],
                        [{
                                list: "ordered"
                            },
                            {
                                list: "bullet"
                            }
                        ],
                        [{
                            background: []
                        }],
                        ["image"]
                    ]
                }
            });
            setTimeout(() => {
                document.querySelectorAll('a.ql-action, a.ql-remove').forEach(el => {
                    el.setAttribute('href', '#');
                    el.setAttribute('onclick', 'event.preventDefault()');
                    el.setAttribute('role', 'button');
                    el.setAttribute('tabindex', '0');
                    el.setAttribute('aria-label', 'Editor Action');
                });
            }, 500);

            // Xử lý chọn size -> thêm 'px'
            const toolbar = editor.getModule('toolbar');
            toolbar.addHandler('size', function(value) {
                if (value) {
                    const size = value + 'px';
                    editor.format('size', size);
                }
            });

            return editor;
        }
        const editorDone = createQuillEditor("#editor-done");
        const editorComplete = createQuillEditor("#editor-complete");
        const editorClose = createQuillEditor("#editor-close");
        document.querySelector('#saveButton').addEventListener('click', function(e) {
            const hiddenInput = document.querySelector("#citizen_note");
            hiddenInput.value = editorDone.root.innerHTML;
        });

        document.getElementById('doneForm').addEventListener('submit', function(e) {
            const noteInput = document.getElementById('citizen_note_done');
            noteInput.value = editorComplete.root.innerHTML;
        });
        document.getElementById('closeForm').addEventListener('submit', function(e) {
            const noteInput = document.getElementById('citizen_note_close');
            noteInput.value = editorClose.root.innerHTML;
        });


        async function handleFormSubmit(event, modalId) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const serviceCode = document.getElementById('service-code').value || '';
            const citizenName = document.getElementById('search-citizen').value || '';
            const status = document.getElementById('value-status').value || '';


            formData.append('service_code', serviceCode);
            formData.append('citizen_name', citizenName);
            formData.append('citizen_name', citizenName);
            formData.append('value_status', status);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                });

                if (response.ok) {
                    const data = await response.json();

                    if (data.success) {
                        toastr.options = {
                            "positionClass": "toast-bottom-right",
                            "timeOut": "4000",
                            "closeButton": true,
                            "progressBar": true
                        };

                        toastr[data.alertType](data.message);

                        // Cập nhật lại danh sách
                        document.getElementById('citizen-service-list').innerHTML = data.updatedListHtml;

                        // Đóng modal
                        const modalElement = document.getElementById(modalId);
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        modalInstance.hide();
                    } else {
                        toastr.error('Cập nhật thất bại!');
                    }
                } else {
                    toastr.error('Lỗi server khi cập nhật!');
                }
            } catch (err) {
                console.error(err);
                toastr.error('Lỗi khi cập nhật dữ liệu.');
            }
        }

        // Gắn sự kiện cho từng form
        document.getElementById('processForm').addEventListener('submit', (e) => handleFormSubmit(e, 'processModal'));
        document.getElementById('doneForm').addEventListener('submit', (e) => handleFormSubmit(e, 'doneModal'));
        document.getElementById('closeForm').addEventListener('submit', (e) => handleFormSubmit(e, 'closeModal'));


        document.body.addEventListener('click', async function(event) {
            if (event.target && event.target.classList.contains('yes-confirm')) {
                event.preventDefault();
                const id = event.target.getAttribute('data-id');

                if (id) {
                    const basePath = window.location.pathname.split('/dashboard')[0];
                    const serviceCode = document.getElementById('service-code').value || '';
                    const citizenName = document.getElementById('search-citizen').value || '';
                    const status = document.getElementById('value-status').value || '';


                    try {
                        const response = await fetch(
                            `${window.location.origin}${basePath}/dashboard/cancel-process/${id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                body: JSON.stringify({
                                    service_code: serviceCode,
                                    citizen_name: citizenName,
                                    value_status: status
                                })
                            });

                        const data = await response.json();


                        if (data.success) {
                            toastr.options = {
                                "positionClass": "toast-bottom-right",
                                "timeOut": "4000",
                                "closeButton": true,
                                "progressBar": true
                            };
                            toastr[data.alertType](data.message);

                            // Cập nhật danh sách
                            document.getElementById('citizen-service-list').innerHTML = data.updatedListHtml;
                            formatUtcTimes();
                            // Đóng modal
                            const modalElement = document.getElementById('confirmModal');
                            const modalInstance = bootstrap.Modal.getInstance(modalElement);
                            modalInstance.hide();
                        } else {
                            toastr.error(data.message);
                        }
                    } catch (err) {
                        console.error('Lỗi khi hủy dịch vụ:', err);
                        toastr.error('Lỗi khi gửi yêu cầu đến máy chủ.');
                    }
                } else {
                    alert('Không tìm thấy ID để huỷ yêu cầu!');
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('check-all-citizen-service').addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.citizen-service-checkbox');
                allCheckboxes.forEach(cb => cb.checked = this.checked);
            });


            document.getElementById('destroyBtnCitizenService').addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.citizen-service-checkbox'))
                    .filter(cb => cb.checked)
                    .map(cb => cb.closest('.display-section').querySelector('.id-citizen-service').value);

                if (selectedIds.length === 0) {
                    toastr.warning('Bạn chưa chọn yêu cầu nào để huỷ.');
                    return;
                }

                if (!confirm('Bạn có chắc chắn muốn huỷ các yêu cầu đã chọn?')) return;

                const basePath = window.location.pathname.split('/dashboard')[0];
                fetch(`${window.location.origin}${basePath}/dashboard/citizen-service/cancel-multiple`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            ids: selectedIds
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        toastr.options = {
                            "positionClass": "toast-bottom-right",
                            "timeOut": "4000",
                            "closeButton": true,
                            "progressBar": true
                        };

                        if (data.success) {
                            toastr.success(data.message);
                            document.getElementById('citizen-service-list').innerHTML = data
                                .updatedListHtml;
                            formatUtcTimes();
                        } else {
                            toastr.error(data.message || 'Huỷ thất bại.');
                        }
                    })
                    .catch(err => {
                        console.error('Lỗi:', err);
                        toastr.error('Có lỗi xảy ra khi huỷ.');
                    });
            });
        });


        function renderUtcTimes() {
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

        let updateInterval = 5000;

        async function getUpdateInterval() {
            try {
                const response = await fetch('/api/time-update');
                const data = await response.json();
                if (data && data.time_update) {
                    updateInterval = data.time_update * 1000;
                }
            } catch (error) {
                console.error('Lỗi khi lấy thời gian cập nhật:', error);
            }
        }

        function autoReloadCitizenServices() {
            // === B1: Lưu lại các checkbox đang được tick ===
            const checkedIds = Array.from(document.querySelectorAll('.citizen-service-checkbox:checked'))
                .map(cb => cb.closest('.display-section').querySelector('.id-citizen-service').value);

            let serviceCodeArray = [];
            $('#dropdown-list input.service-checkbox:checked').each(function() {
                serviceCodeArray.push($(this).val());
            });
            let serviceCode = serviceCodeArray.length === 0 ? '' : JSON.stringify(serviceCodeArray);
            let citizenName = $('#search-citizen').val() || '';
            let status = [];
            $('#status-dropdown-list input.status-checkbox:checked').each(function() {
                status.push($(this).val());
            });

            let statusJson = status.length === 0 ? '' : JSON.stringify(status);
            $('#value-status').val(statusJson);

            $.ajax({
                url: "{{ route('dashboard') }}",
                method: 'GET',
                data: {
                    service_code: serviceCode,
                    citizen_name: citizenName,
                    value_status: statusJson
                },
                success: function(res) {
                    $('#citizen-service-list').html(res);

                    // Gọi lại hàm xử lý thời gian
                    formatUtcTimes();

                    // === B2: Check lại những checkbox đã được tick trước khi reload ===
                    checkedIds.forEach(id => {
                        const checkbox = document.querySelector(
                            `.display-section input.id-citizen-service[value="${id}"]`);
                        if (checkbox) {
                            checkbox.closest('.display-section').querySelector(
                                '.citizen-service-checkbox').checked = true;
                        }
                    });
                }
            });
        }

            getUpdateInterval().then(() => {
                setInterval(autoReloadCitizenServices, updateInterval);
            });
    </script>





@endsection
