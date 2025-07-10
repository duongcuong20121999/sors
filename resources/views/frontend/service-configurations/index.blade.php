@extends('layouts.master')

@section('title', 'Dashboard Admin')

@section('content')


    <div class="container-service-configuration show">
        <div class="header-service d-flex justify-content-between align-items-center">
            <p class="mb-0">Cấu hình dịch vụ</p>
            <a href="{{ route('service-configurations.create') }}" id="ajax-submit-btn" type="submit" form="service-form"
                class="btn-add">Thêm mới</a>
        </div>
        <!-- <hr> -->
        <div class="content-service d-flex">
            <div class="service col-5">
                <div class="row">
                    @php
                        $chunks = $services->chunk(ceil($services->count() / 2));
                    @endphp
                    @foreach ($chunks as $chunk)
                        <div class="col-5">
                            @foreach ($chunk as $service)
                                <a href="{{ route('service-configurations.edit', $service->id) }}"
                                    data-id="{{ $service->id }}">
                                    <div>

                                    </div>
                                    <div
                                        class="box-service text-center mb-4 {{ $service->is_active ? '' : 'disabled-box' }}">
                                        <img style="width: 57px; height: 57px;" src="{{ asset($service->icon) }}"
                                            alt="logo">
                                        <p class="mb-0">{{ $service->name }}</p>


                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div class="vertical-line-service"></div>
            </div>


            <div class="display-service">


                <form id="service-config-form" action="{{ route('service-configurations.store', 1) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    {{-- @method('PUT') --}}


                    <div class="select-image-news d-flex align-items-center">
                        <img id="selected-image" src="{{ asset('frontend/assets/images/photo.png') }}" alt="logo">
                        <input type="file" name="icon" id="file-input" style="display: none;">
                        <a style="width: 86px" class="btn-style" id="choose-image-btn">Chọn ảnh</a>
                    </div>

                    <div class="service-name">
                        {{-- <p>Tên dịch vụ:</p> --}}
                        <label for="service1">Tên dịch vụ:</label>
                        <input id="service1" type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>

                    <div style="margin-top: 30px;">
                        <div style="display: inline-block; margin-right: 20px; text-align: left;">
                            {{-- <p style="margin-bottom: 5px;">Mã dịch vụ:</p> --}}
                            <label style="margin-bottom: 5px" for="service2">Mã dịch vụ:</label>
                            <input id="service2" type="text" name="code" class="form-control" value="{{ old('code') }}">
                        </div>

                        <div style="display: inline-block; text-align: left;">
                            {{-- <p style="margin-bottom: 5px;">Số thứ tự:</p> --}}
                            <label for="service3">Số thứ tự:</label>
                            <input id="service3" type="number" name="order" min="0" class="form-control"
                                value="{{ old('order') }}">
                        </div>
                    </div>


                    <div class="service-mission" style="margin-top: 20px;">
                        {{-- <p>Nhiệm vụ:</p> --}}
                        <label for="editor-mission">Nhiệm vụ:</label>
                        <div id="editor-mission" class="quill-editor service-quill"></div>
                        <input type="hidden" name="mission" id="quill-content-mission">
                    </div>

                    <div class="service-description" style="margin-top: 20px;">
                        {{-- <p>Gợi ý tài liệu đính kèm hồ sơ:</p> --}}
                        <label for="editor-description">Gợi ý tài liệu đính kèm hồ sơ:</label>
                        <div id="editor-description" class="quill-editor"></div>
                        <input type="hidden" name="description" id="quill-content-description">
                    </div>
                    <div class="process-time">
                        {{-- <p>Thời gian xử lý một hồ sơ:</p> --}}
                        <label>Thời gian xử lý một hồ sơ:</label>
                        <div class="d-flex align-items-center">
                            <input class="form-control hour-service" name="process_hours" type="text"
                                value="{{ old('process_hours') }}" id="hour">
                            <label class="ms-2" style="color: #1A1B23;" for="hour">giờ</label>
                            <input class="form-control minutes-service ms-2" name="process_minutes" type="text"
                                value="{{ old('process_minutes') }}" id="minutes">
                            <label class="ms-2" style="color: #1A1B23;" for="minutes">phút</label>
                            <input id="undetermined" class="undetermined-service ms-2" type="checkbox"
                                name="unlimited_duration" {{ old('unlimited_duration') ? 'checked' : '' }}>
                            <label class="ms-2" style="color: #4F4F4F;" for="undetermined">Không xác định</label>
                        </div>
                    </div>
                    <div class="off-service d-flex align-items-center">
                        <input name="is_active" type="checkbox" class="ms-2" id="off-service">
                        <label style="color: #4F4F4F;" for="off-service" class="ms-2">Tắt dịch vụ này</label>
                    </div>
                    <div class="button-service d-flex justify-content-end">
                        <a href="{{ url()->current() }}" class="cancel-news me-2">Hủy</a>
                        <button type="submit" class="save-service add-new-service-btn" id="">Lưu lại</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
    </div>
    </div>


    <script>
        const checkbox = document.getElementById('undetermined');
        const hourInput = document.querySelector('.hour-service');
        const minuteInput = document.querySelector('.minutes-service');

        function toggleTimeInputs() {
            const isChecked = checkbox.checked;
            hourInput.disabled = isChecked;
            minuteInput.disabled = isChecked;
        }

        toggleTimeInputs();

        checkbox.addEventListener('change', toggleTimeInputs);

        const SizeList = Quill.import('attributors/style/size');
        SizeList.whitelist = ['8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18'];
        Quill.register(SizeList, true);

        function initializeQuill(selector) {
            const quillInstance = new Quill(selector, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline"],
                        [{
                            size: SizeList.whitelist
                        }],
                        [{
                            align: ""
                        }, {
                            align: "center"
                        }, {
                            align: "right"
                        }, {
                            align: "justify"
                        }],
                        [{
                            list: "ordered"
                        }, {
                            list: "bullet"
                        }],
                        [{
                            background: []
                        }],
                        ["image"]
                    ],
                },
            });


            const toolbar = quillInstance.getModule('toolbar');
            toolbar.addHandler('size', function(value) {
                if (value) {
                    quillInstance.format('size', value + 'px');
                }
            });

            return quillInstance;
        }

        const quillMission = initializeQuill('#editor-mission');
        const quillDescription = initializeQuill('#editor-description');




        window.addEventListener('load', function() {
            var oldMissionContent = `{!! old('mission') !!}`;
            var oldDescriptionContent = `{!! old('description') !!}`;

            if (oldMissionContent) quillMission.root.innerHTML = oldMissionContent;
            if (oldDescriptionContent) quillDescription.root.innerHTML = oldDescriptionContent;
        });

        // ✅ Sự kiện khi nhấn nút "Lưu lại"
        document.querySelector('.add-new-service-btn').addEventListener('click', function() {

            document.querySelector("#quill-content-mission").value = quillMission.root.innerHTML;
            document.querySelector("#quill-content-description").value = quillDescription.root.innerHTML;

            // Submit form
            document.querySelector("#service-config-form").submit();
        });

        function checkVerticalLineOnScroll() {
            const serviceContainer = document.querySelector(".service");
            const serviceContent = document.querySelector(".service .row");
            const verticalLine = document.querySelector(".vertical-line-service");

            if (serviceContent && serviceContainer && verticalLine) {
                const isOverflowing = serviceContent.scrollHeight > serviceContainer.clientHeight;
                verticalLine.style.display = isOverflowing ? "none" : "block";
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            checkVerticalLineOnScroll();
            window.addEventListener("resize", checkVerticalLineOnScroll);

            const serviceContainer = document.querySelector(".service");
            if (window.ResizeObserver && serviceContainer) {
                const observer = new ResizeObserver(checkVerticalLineOnScroll);
                observer.observe(serviceContainer);
            }
        });
    </script>


@endsection
