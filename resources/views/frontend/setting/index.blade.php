@extends('layouts.master')

@section('title', 'System Setting')

@section('content')
    <div class="container-fluid mt-2 mb-3">
        <div class="row main-layout">

            <!-- display -->
            <main class="col-10 display-container">
                <div class="system-parameter-configuration show">
                    <div class="header-system-parameter-configuration">
                        <p class="ms-4 d-flex align-items-center m-0 py-3">Cấu hình tham số hệ thống</p>
                    </div>

                    <div class="display-system-parameter-configuration p-4">
                        <form class="form-zalo-qr" method="POST" action="{{ route('settings.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-8">
                                    <div class="select-image-news d-flex align-items-center gap-4 mb-4">
                                        <div class="image-wrapper" id="imageWrapper">
                                            <img id="selected-image-news"
                                                src="{{ $setting->logo ?? asset('frontend/assets/images/photo.png') }}"
                                                alt="logo">
                                        </div>
                                        <input type="file" name="logo" id="file-input" style="display: none;" />
                                        <div>
                                            <p class="title-img mb-1">Logo của hệ thống</p>
                                            <a id="choose-image-btn-news">Chọn ảnh</a>

                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1" for="">Tên Hệ thống:</label>
                                        <input type="text" name="system_name" class="form-control system-name-input"
                                            placeholder="Tên Hệ thống"
                                            value="{{ old('system_name', $setting->system_name ?? '') }}" />

                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1" for="">Tên Phường:</label>
                                        <input type="text" name="ward_name" class="form-control ward-name-input"
                                            placeholder="Tên Phường"
                                            value="{{ old('system_name', $setting->ward_name ?? '') }}" />

                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1" for="">Mã QR liên kết tới Zalo mini App:</label>
                                        <div class="d-flex gap-3">
                                            <input aria-label="qr image" type="text" name="qr_url"
                                                class="form-control url-img-qr" value="{{ $setting->qr_code ?? '' }}" />
                                            <a id="choose-qr-btn">Chọn QR</a>
                                            <input type="file" id="qr-file-input" name="qr_code"
                                                style="display: none;" />

                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1" for="time-update">Thời gian cập nhật STT (giây):</label>
                                        <input type="number" name="time_update" id="time-update"
                                            class="form-control system-name-input"
                                            placeholder="Nhập thời gian (tính bằng giây)" min="1" step="1" 
                                             value="{{ old('time_update', $setting->time_update ?? '') }}"/>
                                    </div>
                                    <div>
                                        <label class="mb-1" for="">Hướng dẫn kích hoạt Zalo mini App:</label>
                                        <div id="qr-app" class="quill-qr">
                                            {!! $setting->instruction ?? '' !!}
                                        </div>
                                        <input type="hidden" name="instruction" id="instruction_input">
                                    </div>


                                </div>

                                <div class="display-qr col-4">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <img id="qr-preview-image" src="{{ $setting->qr_code ?? asset('/image/QR.png') }}"
                                            alt="QR">
                                        <span class="title-qr">Mã QR đang liên kết tới zalo app đang sử dụng</span>
                                    </div>
                                </div>
                            </div>
                            <div class="system-button d-flex justify-content-end gap-3 mt-3 ms-auto">
                                <a id="cancel-button"
                                    class="qr-button d-flex justify-content-center align-items-center">Hủy</a>
                                <button class="qr-button" type="submit">Lưu lại</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>


    <script>
        // select logo
        const resetLogoImages = initImageUploadSync({
            fileInputId: 'file-input',
            triggerButtonId: 'choose-image-btn-news',
            previewImageId: 'selected-image-news',
            targetImageSelector: '.logo-wrapper img'
        });

        document.addEventListener("DOMContentLoaded", function() {
            populateFormFromHeader({
                systemNameSelector: ".header-title",
                wardNameSelector: ".address",

            });

            initQRImageSelector({
                fileInputId: "qr-file-input",
                triggerButtonId: "choose-qr-btn",
                targetImageSelector: ".display-qr img",
                textInputSelector: ".url-img-qr"
            });

            const initialData = {
                system_name: "{{ old('system_name', $setting->system_name ?? '') }}",
                ward_name: "{{ old('system_name', $setting->ward_name ?? '') }}",
                qr_url: "{{ $setting->qr_code ?? '' }}",
                instruction: `{!! addslashes($setting->instruction ?? '') !!}`,
                logo_src: "{{ $setting->logo ?? asset('frontend/assets/images/photo.png') }}",
                qr_preview_src: "{{ $setting->qr_code ?? asset('/image/QR.png') }}",
                 time_update: "{{ old('time_update', $setting->time_update ?? '') }}"
            };

            const cancelButton = document.getElementById('cancel-button');

            cancelButton.addEventListener('click', function(e) {
                e.preventDefault();

                document.querySelector('.system-name-input').value = initialData.system_name;
                document.querySelector('.ward-name-input').value = initialData.ward_name;
                document.querySelector('.url-img-qr').value = initialData.qr_url;
                document.getElementById('time-update').value = initialData.time_update;
                // Reset image
                document.getElementById('selected-image-news').src = initialData.logo_src;
                document.getElementById('qr-preview-image').src = initialData.qr_preview_src;

                // Reset Quill editor
                if (typeof qr !== 'undefined') {
                    qr.root.innerHTML = initialData.instruction;
                }

                const hiddenInput = document.getElementById('instruction_input');
                if (hiddenInput) {
                    hiddenInput.value = initialData.instruction;
                }

                document.getElementById('file-input').value = "";
                document.getElementById('qr-file-input').value = "";
                resetLogoImages();
            });
        });


        // quill
        const qr = initCustomQuill('#qr-app');

        document.querySelector('.form-zalo-qr').addEventListener('submit', function() {
            document.getElementById('instruction_input').value = qr.root.innerHTML;
        });


        function initCustomQuill(selector) {
            const Size = Quill.import('attributors/style/size');
            Size.whitelist = ['8px', '9px', '10px', '11px', '12px', '13px', '14px', '15px', '16px', '17px', '18px'];
            Quill.register(Size, true);

            const quill = new Quill(selector, {
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
                            },
                        ],
                        [{
                            list: "ordered"
                        }, {
                            list: "bullet"
                        }],
                        [{
                            background: []
                        }],
                        ["image"],
                    ],
                },
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

            const toolbar = quill.getModule('toolbar');
            toolbar.addHandler('size', function(value) {
                if (value) {
                    quill.format('size', value + 'px');
                }
            });

            return quill;
        }
        const oldMission = {!! json_encode(old('mission', $service_update->mission ?? '')) !!};

        const quillMission = initCustomQuill('#editor-mission', oldMission);


        window.addEventListener('load', function() {
            var oldMissionContent = `{!! old('instruction') !!}`;


            if (oldMissionContent) quill.root.innerHTML = oldMissionContent;

        });


        // select logo
        function initImageUploadSync({
            fileInputId,
            triggerButtonId,
            previewImageId,
            targetImageSelector
        }) {
            const fileInput = document.getElementById(fileInputId);
            const triggerButton = document.getElementById(triggerButtonId);
            const previewImage = document.getElementById(previewImageId);
            const targetImage = document.querySelector(targetImageSelector);

            if (!fileInput || !triggerButton || !previewImage || !targetImage) {
                return;
            }

            const originalPreviewSrc = previewImage.src;
            const originalTargetSrc = targetImage.src;

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImage.src = event.target.result;
                        targetImage.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
            return function resetImages() {
                previewImage.src = originalPreviewSrc;
                targetImage.src = originalTargetSrc;
                fileInput.value = '';
            };
        }

        // system-name and address-name
        function populateFormFromHeader({
            systemNameSelector,
            wardNameSelector,
            systemInputSelector,
            wardInputSelector
        }) {
            const systemNameElem = document.querySelector(systemNameSelector);
            const wardNameElem = document.querySelector(wardNameSelector);
            const systemInput = document.querySelector(systemInputSelector);
            const wardInput = document.querySelector(wardInputSelector);

            if (systemNameElem && wardNameElem && systemInput && wardInput) {
                const rawSystemText = systemNameElem.textContent.trim().replace(/\s+/g, ' ');
                const rawWardText = wardNameElem.textContent.trim();

                systemInput.value = rawSystemText;
                wardInput.value = rawWardText;
            }
        }

        // select qr
        function initQRImageSelector({
            fileInputId,
            triggerButtonId,
            targetImageSelector,
            textInputSelector
        }) {
            const fileInput = document.getElementById(fileInputId);
            const triggerButton = document.getElementById(triggerButtonId);
            const targetImage = document.querySelector(targetImageSelector);
            const textInput = document.querySelector(textInputSelector);

            if (!fileInput || !triggerButton || !targetImage || !textInput) {
                return;
            }

            triggerButton.addEventListener("click", function(e) {
                e.preventDefault();
                fileInput.click();
            });

            fileInput.addEventListener("change", function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        targetImage.src = event.target.result;
                        textInput.value = file.name;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>


@endsection
