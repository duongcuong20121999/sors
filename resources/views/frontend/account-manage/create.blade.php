@extends('layouts.master')

@section('title', 'Account Manage')

@section('content')
    <div class="container-account-management">
        <div class="header-news d-flex justify-content-between align-items-center">
            <p class="mb-0">Quản lý tài khoản</p>
            <a href="{{ route('accounts-manager.create') }}" class="btn-add">Thêm mới</a>
        </div>
        @auth
            @php
                $currentUser = Auth::user();
                if ($currentUser) {
                    $userModel = App\Models\User::find($currentUser->id);
                    if ($userModel) {
                        $userModel->hasRole('Soạn văn bản');
                    }
                }
            @endphp
        @endauth
        <div class="container">
            <div class="row mt-4">
                <!-- list -->
                <div class="list-news col-md-5 position-relative d-flex flex-column">
                    <!-- news -->
                    <div id="users-list">
                        @foreach ($users as $user)
                            @include('frontend.account-manage.components.user-item', [
                                'id' => $user->id,
                                'avatar' => asset($user->avatar ?? 'frontend/assets/images/user.avif'),
                                'name' => $user->name,
                                'service_id' => $user->service_id,
                                'date' => $user->created_at->format('d/m/Y'),
                                'roles' => $user->roles->pluck('name')->join(', ') ?: 'Chưa có',
                                'is_active' => $user->is_active,
                            ])
                        @endforeach


                    </div>

                    <div class="footer-content mt-auto mb-1 justify-content-center flex-lg-wrap">
                        <!-- filter the news -->
                        <div class="footer-choose-news mt-1 mb-1 mx-auto" data-bs-toggle="dropdown">
                            <p id="footer-selected-news" class="mb-0">
                                {{ $currentRole !== 'all' ? $roles->where('id', $currentRole)->first()->name : 'Tất cả' }}
                            </p>

                            <div class="dropdown-footer ms-auto dropup">
                                <a aria-label="dropdown footer"
                                    class="btn btn-outline-secondary p-2 d-flex align-items-center" href="#"
                                    role="button" id="dropdownMenuButton">
                                    <ion-icon name="chevron-down-outline" id="dropdown-icon"></ion-icon>
                                </a>
                                <ul class="dropdown-menu" id="dropdown-list-footer" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#" data-group="all"
                                            {{ $currentRole === 'all' ? 'class=active' : '' }}>Chọn nhóm quyền</a></li>
                                    @foreach ($roles as $role)
                                        <li>
                                            <a class="dropdown-item {{ $currentRole == $role->id ? 'active' : '' }}"
                                                href="#" data-group="{{ $role->id }}">
                                                {{ $role->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- pagination -->
                        @include('frontend.account-manage.components.custom-pagination', [
                            'users' => $users,
                        ])

                    </div>

                    <div class="divider-line"></div>
                </div>


                <!-- Display content -->
                <div class="col-md-7 display-news px-4">
                    <form action="{{ route('accounts-manager.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- <div class="select-image-news d-flex align-items-center">
                            <img id="selected-image-news" src="{{ asset('frontend/assets/images/user.png') }}"
                    alt="logo">
                    <input type="file" name="avatar" id="file-input" style="display: none;">
                    <a id="choose-image-btn-account">Chọn ảnh đại diện</a>
            </div> --}}

                        <div class="select-image-news d-flex align-items-center">
                            <img id="selected-image-account" src="{{ asset('frontend/assets/images/user.avif') }}"
                                alt="logo">
                            <input type="file" name="avatar" id="file-input-account" style="display: none;">
                            <a id="choose-image-btn-account">Chọn ảnh đại diện</a>
                        </div>

                        <div class="mt-3">
                            <label for="account1" class="mb-2">Họ và tên:</label>
                            <input id="account1" type="text" name="name" class="form-control"
                                value="{{ old('name') }}">
                        </div>
                        <div class="mt-3">
                            <label for="account2" class="mb-2">Email:</label>
                            <input id="account2" type="text" autocomplete="username" name="email" class="form-control"
                                value="{{ old('email') }}">
                        </div>
                        <div class="mt-3">
                            <label for="account3" class="mb-2">Zalo ID:</label>
                            <input id="account3" type="text" name="zalo_id" class="form-control" id="zalo-id"
                                value="{{ old('zalo_id') }}">
                        </div>
                        <div class="mt-3">
                            <label for="account4" class="mb-2">Mật khẩu:</label>
                            <input id="account4" type="password" autocomplete="new-password" name="password"
                                class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="account5" class="mb-2">Nhập lại mật khẩu:</label>
                            <input id="account5" type="password" autocomplete="new-password" name="cf_password"
                                class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="account6" class="mb-2">Thông tin ghi chú:</label>
                            <input id="account6" type="text" name="description_service" class="form-control"
                                id="service-description" value="{{ old('description_service') }}">
                        </div>
                        <div class="mt-3">
                            <p class="mb-2">Chọn vai trò:</p>
                            <div class="content-checkbox ms-5">
                                <div class="row">
                                    @foreach ($roles->chunk(2) as $rolePair)
                                        <div class="d-flex justify-content-between mb-3">
                                            @foreach ($rolePair as $role)
                                                <div class="publish-articles d-flex align-items-center gap-3"
                                                    style="width: 48%;">
                                                    <input type="checkbox" name="roles[]" id="{{ $role->name }}"
                                                        value="{{ $role->name }}"
                                                        {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}
                                                        onchange="toggleSelector(this)">
                                                    <label for="{{ $role->name }}">{{ $role->name }}</label>
                                                </div>
                                            @endforeach

                                            @if ($rolePair->count() === 1)
                                                <div style="width: 48%;"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Vùng chứa select --}}
                        <div id="selector-container" class="mt-3" style="display: none; padding-bottom: 100px;">
                            <label class="mb-2" for="service_id">Chọn quầy dịch vụ:</label>
                            <select id="service_id" name="service_id" class="form-select" style="width: 50%;">
                                <option value="">-- Chọn quầy --</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ old('service_id', $user_data->service_id ?? '') == $service->id ? 'selected' : '' }}>
                                        Quầy {{ $service->order }}: {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <p class="mb-2 mt-3">Trạng thái:</p>
                            <div class="publish-articles gap-3 d-flex content-checkbox ms-5 mt-3">
                                <input id="action" type="checkbox" name="is_active"
                                    {{ old('is_active') ? 'checked' : '' }}>
                                <label for="action">Hoạt động</label>
                            </div>
                        </div>

                        <div class="button-news d-flex justify-content-end">
                            <a href="{{ url()->current() }}" class=" cancel-news me-2">Hủy</a>
                            <button type="submit" class=" save-news">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentRole = 'all';

        $(document).on('click', '.dropdown-item', function(e) {
            e.preventDefault();

            currentRole = $(this).data('group');
            fetchFilteredUsers(1); // Reset về page 1 khi đổi role
        });

        // Bắt sự kiện click vào phân trang
        // $(document).on('click', '.pagination .page-link', function(e) {
        //     e.preventDefault();

        //     const page = $(this).data('page');
        //     if (page) {
        //         fetchFilteredUsers(page);
        //     }
        // });

        $(document).on('click', '.pagination .page-link', function(e) {
            e.preventDefault();

            const page = $(this).data('page');
            if (page) {
                const newUrl = updateQueryStringParameter(window.location.href, 'page', page);
                window.history.pushState({
                    path: newUrl
                }, '', newUrl);

                fetchFilteredUsers(page);
                $('.pagination .page-item').removeClass('active');
                $(this).parent().addClass('active');
                updateActivePage(page);
            }
        });

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                return uri + separator + key + "=" + value;
            }
        }

        function fetchFilteredUsers(page) {
            $.ajax({
                url: "{{ route('accountsmanager.filter') }}",
                method: 'GET',
                data: {
                    role: currentRole,
                    page: page
                },
                success: function(data) {
                    $('#users-list').html(data.users);

                    if (data.pagination.trim() !== '') {
                        $('#pagination').html(data.pagination).show();
                    } else {
                        $('#pagination').empty().hide();
                    }

                    // 🔄 Cập nhật URL khi filter thay đổi
                    const newUrl = updateQueryStringParameter(window.location.href, 'role', currentRole);
                    window.history.pushState({
                        path: newUrl
                    }, '', newUrl);
                },
                error: function() {
                    alert('Có lỗi xảy ra khi tải danh sách người dùng!');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('choose-image-btn-account').addEventListener('click', function() {
                document.getElementById('file-input-account').click();
            });

            document.getElementById('file-input-account').addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('selected-image-account').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const currentRole = "{{ $currentRole }}";

            if (currentRole !== 'all') {
                $('#footer-selected-news').text($(`#dropdown-list-footer a[data-group="${currentRole}"]`).text());
            } else {
                $('#footer-selected-news').text('Chọn nhóm quyền');
            }
        });

        function toggleSelector(checkbox) {
            const container = document.getElementById('selector-container');
            if (checkbox.value === "Nhân viên 1 cửa" && checkbox.checked) {
                container.style.display = "block";
            } else if (checkbox.value === "Nhân viên 1 cửa" && !checkbox.checked) {
                container.style.display = "none";
            }
        }

        // Nếu muốn giữ lại dropdown khi reload (khi form bị lỗi validate chẳng hạn)
        window.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.querySelector('input[type="checkbox"][value="Nhân viên 1 cửa"]');
            if (checkbox && checkbox.checked) {
                document.getElementById('selector-container').style.display = "block";
            }
        });
    </script>


@endsection
