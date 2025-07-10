@extends('layouts.master')

@section('title', 'User Logs')

@section('content')

    <div class="container-role-management">
        <div class="header-news d-flex justify-content-between align-items-center">
            <p class="mb-0">Nhật ký người dùng</p>
            <div class="d-flex gap-5">
                <!-- filter -->
                @can('user-logs.index')
                    <div class="user-log ms-3" style="width: 200px" data-bs-toggle="dropdown">
                        <span
                            id="footer-selected-news">{{ $roles->firstWhere('id', request('role_id'))?->name ?? 'Chọn nhóm quyền' }}</span>
                        <div class="dropdown-footer ms-auto">
                            <a aria-label="dropwdown logs" class="btn btn-outline-secondary  p-2 d-flex align-items-center"
                                href="#" role="button" id="dropdownMenuButton">
                                <ion-icon name="chevron-down-outline" id="dropdown-icon"></ion-icon>
                            </a>
                            <ul class="dropdown-menu" id="dropdown-list-footer" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a class="dropdown-item" href="#" data-group="all">Tất cả nhóm quyền</a>
                                </li>
                                @foreach ($roles as $role)
                                    <li>
                                        <a class="dropdown-item" href="#" data-group="{{ $role->id }}">
                                            {{ $role->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <form id="filter-form" method="GET" action="{{ route('user-logs.index') }}">
                                <input type="hidden" name="role_id" id="role_id_input" value="{{ request('role_id') }}">
                            </form>
                        </div>
                    </div>
                @endcan
                <div class="datepicker-wrapper position-relative">
                    <div class="input-group">
                        <input type="text" id="datepicker" class="form-control" placeholder="Chọn ngày" />
                        <span class="input-group-text" id="calendar-icon"><i class="bi bi-calendar3"></i></span>
                    </div>
                </div>
                <form id="delete-logs-form" action="{{ route('user-logs.delete-multiple') }}" method="POST">
                    @csrf
                    <input type="hidden" id="ids-input" name="ids" value="">
                    <button type="button" id="delete-logs-btn" class="btn btn-danger">Xoá log</button>
                </form>
            </div>
        </div>

        <div class="content">
            <div class="title-content d-flex mt-4">
                <div class="d-flex align-items-center " style="flex:1.5;">
                    <input aria-label="checkbox" class="ms-4" id="select-all" name=""
                        style="width: 25px; height: 25px;" value="" type="checkbox" />
                    <p class="ps-2">Người dùng</p>
                </div>
                <div style="height: 3rem;" class="line-content"></div>
                <div class="d-flex align-items-center " style="flex: 2;justify-content: start;">
                    <p class="ms-5">Tác vụ thực hiện</p>
                </div>
                <div style="height: 3rem;" class="line-content"></div>
                <div class="d-flex align-items-center justify-content-center" style="flex: 3;">
                    <p>Chi tiết</p>
                </div>
                <div style="height: 3rem;" class="line-content"></div>
                <div class="d-flex align-items-center justify-content-center" style="flex: 1.5;">
                    <p>Ngày tháng</p>
                </div>
            </div>
            <div id="log-container" class="display-content mb-3">
                @include('frontend.user-logs.components.user-logs-item', ['logs' => $logs])

            </div>

            <form id="delete-logs-form" action="{{ route('user-logs.delete-multiple') }}" method="POST"
                style="display:none;">
                @csrf
                <input type="hidden" name="ids" id="ids-input">
            </form>

            <!-- modal log -->
            <div id="modalContainer">
                <form action="">
                    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="processModalLabel"
                        data-bs-backdrop="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg custom-modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="color: #000000;font-weight: 500;" id="processModalLabel">
                                        Log: <span></span>
                                    </h5>
                                    <div class="close-modal-btn">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div style="color: #1A1B23;font-size: 15px;font-weight: 500;"
                                        class="mb-3 d-flex align-items-center">
                                        <label for="citizenAddress" class="form-label me-2 mb-0"
                                            style="min-width: 70px">Tác
                                            vụ:</label>
                                        <p>SN 17 - Ngõ 111 đường Nguyễn Chí Thanh, phường Quang Trung</p>
                                    </div>
                                    <div>
                                        <label style="font-weight: 500;color: #1A1B23;" for="detail"
                                            class="form-label">Chi tiết:</label>
                                        <p class="ms-5" id="detail"></p>
                                    </div>
                                </div>
                                <div class="modal-footer custom-modal-footer">
                                    <div class="d-flex gap-4 ms-auto">
                                        <button type="button" class="custom-close-btn"
                                            data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="footer-content mx-3 ms-3 mt-auto mb-1 d-flex justify-content-center flex-lg-wrap">
            <!-- pagination -->
            @include('frontend.user-logs.components.custom_pagination', ['logs' => $logs])
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.vi.min.js">
    </script>

    <script>
        $(document).ready(function() {
            // Khởi tạo datepicker
            $('#datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'vi'
            });

            // Xử lý khi nhấn vào icon calendar
            $('#calendar-icon').on('click', function() {
                $('#datepicker').datepicker('show');
            });

            // Gọi AJAX khi thay đổi ngày
            $('#datepicker').on('change', function() {
                const date = $('#datepicker').val();


                const roleId = $('#role_id_input').val() || '';



                fetchLogs(roleId, date);
            });

            $('#role-select').on('change', function() {
                const roleId = $(this).val() || '';
                const date = $('#datepicker').val() || '';

                fetchLogs(roleId, date);
            });
        });



        function convertTimesToLocal() {
            document.querySelectorAll('.log-time').forEach(function(el) {
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

        convertTimesToLocal();

        function fetchLogs(roleId, date, page = 1) {
            $.ajax({
                url: '{{ route('user-logs.index') }}',
                method: 'GET',
                data: {
                    role_id: roleId,
                    date: date,
                    page: page // ✅ Thêm page vào request
                },
                success: function(response) {
                    $('#log-container').html(response.userLogs);

                    if (response.pagination.trim() !== '') {
                        $('#pagination').html(response.pagination).show();
                    } else {
                        $('#pagination').empty().hide();
                    }

                    // Cập nhật thời gian
                    if (typeof convertTimesToLocal === 'function') {
                        convertTimesToLocal();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Có lỗi xảy ra:', error);
                }
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const basePath = window.location.pathname.split('/user-logs')[0];

            // Lắng nghe sự kiện click trên phần tử cha (Event Delegation)
            document.querySelector('#log-container').addEventListener('click', function(event) {
                const target = event.target;

                // Kiểm tra nếu phần tử được click có class .show-user-log
                if (target.classList.contains('show-user-log')) {
                    const id = target.getAttribute('data-id');

                    fetch(`${basePath}/user-logs/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            // Đổ dữ liệu vào modal
                            document.querySelector('#processModalLabel span').textContent = data.title;
                            document.querySelector('#detailModal .modal-body p').textContent = data
                                .action;
                            document.querySelector('#detail').textContent = JSON.stringify(data.detail);

                            // Hiển thị modal
                            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                            modal.show();
                        })
                        .catch(error => {
                            alert('Không thể lấy dữ liệu!');
                            console.error(error);
                        });
                }
            });
        });
    </script>

    <script>
        document.getElementById('delete-logs-btn').addEventListener('click', function() {
            const checkedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            const ids = Array.from(checkedCheckboxes).map(cb => cb.value); // Lấy ID của các checkbox đã chọn

            if (ids.length === 0) {
                alert('Vui lòng chọn ít nhất 1 log để xoá!');
                return;
            }

            // Đưa mảng ID vào input ẩn trong form
            document.getElementById('ids-input').value = JSON.stringify(ids);

            // Submit form bằng tay
            document.getElementById('delete-logs-form').submit();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleDropdownItems = document.querySelectorAll('#dropdown-list-footer a');
            const logContainer = document.getElementById('log-container');
            const footerSelected = document.getElementById('footer-selected-news');

            roleDropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedRoleId = this.getAttribute('data-group');

                    footerSelected.innerText = this.innerText;

                    const basePath = window.location.pathname.split('/user-logs')[0];
                    fetch(`${basePath}/user-logs/by-role/${selectedRoleId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.data) {
                                // Xóa log cũ
                                logContainer.innerHTML = '';

                                // Thêm log mới
                                data.data.forEach(log => {
                                    const logHtml = `
                                    <div class="d-flex mt-4 section-dislay-content">
                                        <div class="d-flex align-items-center" style="flex:1.5;">
                                            <div class="ms-4 d-flex align-items-start ">
                                                <input id="${log.id}" name="ids[]" value="${log.id}" type="checkbox" class="me-2" />
                                            </div>
                                            <label for="${log.id}" class="mb-0">${log.citizen_name}</label>
                                        </div>

                                        <div class="line-content"></div>

                                        <div class="d-flex align-items-center" style="flex: 2; justify-content: start;">
                                            <p style="color: #919191; font-weight: 400;" class="ms-5">${log.action}</p>
                                        </div>

                                        <div class="line-content"></div>

                                        <div class="d-flex align-items-center" style="flex: 3; overflow: hidden;">
                                            <p class="detail-content mx-5 ms-5" >
                                                [CitizenName: ${log.details?.CitizenName ?? ''}, ZaloId: ${log.details?.ZaloId ?? ''}]
                                            </p>
                                        </div>

                                        <div class="line-content"></div>

                                        <div class="d-flex flex-column justify-content-center align-items-center" style="flex: 1.5;">
                                            <p>${log.created_at}</p>
                                        </div>
                                    </div>
                                `;
                                    logContainer.innerHTML += logHtml;
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching logs:', error);
                        });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();


                    const roleId = this.getAttribute('data-group');


                    document.getElementById('role_id_input').value = roleId;


                    const date = document.getElementById('datepicker').value || '';



                    fetchLogs(roleId, date);
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.log-time').forEach(function(el) {
                const utcTime = el.dataset.time;
                if (utcTime) {
                    const localDate = new Date(utcTime);
                    // Format giờ: H:i - d/m/Y
                    const formatted = localDate.toLocaleString('vi-VN', {
                        hour: '2-digit',
                        minute: '2-digit',
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    }).replace(',', ' -');
                    el.textContent = formatted;
                }
            });
        });
        $('#select-all').on('change', function() {
            $('.child-checkbox').prop('checked', this.checked);
        });

        $(document).on('click', '#pagination a', function(e) {
            e.preventDefault();

            const page = $(this).attr('href').split('page=')[1];
            const roleId = $('#role_id_input').val();
            const date = $('#datepicker').val();

            fetchLogs(roleId, date, page);
        });
    </script>

@endsection
