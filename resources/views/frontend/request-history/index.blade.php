@extends('layouts.master')

@section('title', 'Request History')

@section('content')
    <div class="main-screen show">
        <div class="header-main-screen d-flex justify-content-between">
            <div class="datepicker-wrapper position-relative">
                <div class="input-group ms-5">
                    <input type="text" id="datepicker" class="form-control" placeholder="Chọn ngày" />
                    <span class="input-group-text" id="calendar-icon"><i class="bi bi-calendar3"></i></span>
                </div>
            </div>


            <div class="choose-service d-flex align-items-center" data-bs-toggle="dropdown" >
                <label for="choose-service" class="me-2">Chọn dịch vụ:</label>
                <p id="selected-service" class="mb-0 "></p>
                <!-- Dropdown dịch vụ -->
                <div class="dropdown ms-2">
                    <button aria-label="dropdown request history" class="btn btn-outline-secondary  p-2 d-flex align-items-center" type="button"
                        id="dropdownMenuButton">
                        <ion-icon name="chevron-down-outline" class="ms-2" id="dropdown-icon"></ion-icon>
                    </button>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdown-list">
                        <li><a class="dropdown-item service-filter-option" href="#" data-code="">Tất cả dịch vụ</a>
                        </li>
                        @foreach ($services as $s)
                            <li>
                                <a class="dropdown-item service-filter-option" href="#"
                                    data-code="{{ $s->code }}">{{ $s->name }} (Quầy số {{ $s->order }})</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <input type="hidden" id="service-code" name="service_code" value="">
            </div>

            <div class="search">
                <input placeholder="Tên Công dân cần tìm" class="input" id="search-citizen"
                    value="">
                <svg class="icon search-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <g>
                        <path
                            d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                        </path>
                    </g>
                </svg>
            </div>
        </div>

        <div class="display-main-screen d-flex flex-column justify-content-between">
            <div id="history-request-list" class="content-request-history">
                @include('frontend.request-history.components.request-history-item', [
                    'citizenServices' => $citizenServices,
                ])
            </div>
            <!-- pagination -->
            @include('frontend.request-history.components.custom-pagination', [
                'citizenServices' => $citizenServices,
            ])

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="serviceInfoModal" tabindex="-1" aria-labelledby="serviceInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceInfoModalLabel">Thông tin chi tiết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <img id="modal-avatar" src="" alt="avatar" class="img-fluid rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0" id="modal-name"></h6>
                            <small class="text-muted" id="modal-address"></small>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-auto" style="width: 180px;">Số thứ tự:</div>
                        <div class="col fw-semibold" id="modal-request-code"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-auto" style="width: 180px;">Dịch vụ:</div>
                        <div class="col fw-semibold" id="modal-service"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-auto" style="width: 180px;">Thời gian tiếp nhận:</div>
                        <div class="col fw-semibold" id="modal-created-date"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-auto" style="width: 180px;">Thời gian cập nhật:</div>
                        <div class="col fw-semibold" id="modal-updated-date"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-auto" style="width: 180px;">Trạng thái:</div>
                        <div class="col fw-semibold" id="modal-status"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="custom-close-btn" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.vi.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
    // 🗓️ Khởi tạo Datepicker
    $('#datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'vi'
    });

    $('#calendar-icon').on('click', function () {
        $('#datepicker').datepicker('show');
    });

    // 📝 Load lại filter từ LocalStorage khi trang load
    const savedDate = localStorage.getItem('selectedDate');
    const savedService = localStorage.getItem('selectedService');
    const savedCitizen = localStorage.getItem('selectedCitizen');

    if (savedDate) {
        $('#datepicker').val(savedDate);
    }
    if (savedService) {
        $(`.service-filter-option[data-code="${savedService}"]`).addClass('active');
        $('#selected-service-text').text($(`.service-filter-option[data-code="${savedService}"]`).text());
    }
    if (savedCitizen) {
        $('#search-citizen').val(savedCitizen);
    }

    // 🖱️ Bắt sự kiện chọn ngày
    $('#datepicker').on('changeDate', function () {
        const selectedDate = $(this).val();
        localStorage.setItem('selectedDate', selectedDate);
        fetchCitizenServices();
    });

    // 🖱️ Bắt sự kiện chọn dịch vụ
    $(document).on('click', '.service-filter-option', function (e) {
        e.preventDefault();
        const code = $(this).data('code');
        const name = $(this).text();
        $('#selected-service-text').text(name);

        // ✅ Lưu vào LocalStorage
        localStorage.setItem('selectedService', code);

        $('.service-filter-option').removeClass('active');
        $(this).addClass('active');

        fetchCitizenServices();
    });

    // 🔎 Tìm kiếm
    $('.search-icon').on('click', function () {
        const citizenName = $('#search-citizen').val();
        localStorage.setItem('selectedCitizen', citizenName);
        fetchCitizenServices();
    });

    $('#search-citizen').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            const citizenName = $('#search-citizen').val();
            localStorage.setItem('selectedCitizen', citizenName);
            fetchCitizenServices();
        }
    });


    function fetchCitizenServices(page = 1) {
        const localDateStr = $('#datepicker').val();
        const serviceCode = localStorage.getItem('selectedService') || '';
        const citizenName = localStorage.getItem('selectedCitizen') || '';

        let createdDateUtc = '';
        if (localDateStr) {
            const [day, month, year] = localDateStr.split('/');
            const localDate = new Date(year, month - 1, day, 0, 0, 0);
            createdDateUtc = new Date(Date.UTC(localDate.getFullYear(), localDate.getMonth(), localDate.getDate()))
                .toISOString();
        }

        $.ajax({
            url: '{{ route('request-history.index') }}',
            method: 'GET',
            data: {
                service_code: serviceCode,
                citizen_name: citizenName,
                created_date: createdDateUtc,
                page: page 
            },
            success: function (res) {
                $('#history-request-list').html(res.requestHistory);

                if (res.pagination.trim() !== '') {
                    $('#pagination').html(res.pagination).show();
                } else {
                    $('#pagination').empty().hide();
                }
            },
            error: function () {
                alert('Có lỗi xảy ra!');
            }
        });
    }

    // 🔄 Sự kiện click vào phân trang
    $(document).on('click', '#pagination .page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        fetchCitizenServices(page); 
    });

    $(document).on('click', '.service-info', function() {
        const $row = $(this).closest('.display-section');

        const avatar = $row.find('img').attr('src');
        const name = $row.find('.name').text();
        const address = $row.find('.location').text();
        const service = $row.find('.name-service').text();
        const requestCode = $row.find('.request-code').text();
        const createdDate = $row.find('.service-info span').text();
        const status = $row.find('.status').text();
        const updatedDate = $row.data('updated-at') ? new Date($row.data('updated-at')).toLocaleString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit',
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour12: false,
        }).replace(',', ' -') : 'Chưa cập nhật';

        $('#modal-avatar').attr('src', avatar);
        $('#modal-name').text(name);
        $('#modal-address').text(address);
        $('#modal-service').text(service);
        $('#modal-request-code').text(requestCode);
        $('#modal-created-date').text(createdDate);
        $('#modal-status').text(status);
        $('#modal-updated-date').text(updatedDate);

        const modal = new bootstrap.Modal(document.getElementById('serviceInfoModal'));
        modal.show();
    });
});
    </script>

@endsection
