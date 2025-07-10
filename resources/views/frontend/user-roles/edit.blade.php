@extends('layouts.master')

@section('title', 'User Roles')

@section('content')

    <div class="container-role-management">
        <div class="header-news d-flex justify-content-between align-items-center">
            <p class="mb-0">Quản lý vai trò người dùng</p>
            <a href="{{ route('user-roles-manager.create') }}" class="btn-add">Thêm mới</a>
        </div>

        <div class="container">
            <div class="row mt-4">
                <!-- list -->
                <div class="list-news col-md-5 position-relative d-flex flex-column">
                    <!-- news -->
                    <div id="list-content-container">

                        @foreach ($roles as $role)
                            @include('frontend.user-roles.components.roles-item', [
                                'id' => $role->id,
                                'name' => $role->name,
                                'created_at' => $role->created_at,
                                'created_by' => $role->created_by,
                                'is_active' => $role->is_active,
                            ])
                        @endforeach

                    </div>

                    <div class="divider-line"></div>
                </div>


                <!-- Display content -->
                <div class="col-md-7 display-news px-4">
                    <form action="{{ route('user-roles-manager.update', $role_update->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id_role" value="{{ $role_update->id ?? '' }}">

                        <div class="mt-3">
                            <label class="mb-2">Tên vai trò:</label>
                            <input type="text" class="form-control" name="name" id="account-name"
                                value="{{ old('name', $role_update->name ?? '') }}">
                        </div>

                        <div class="mt-3">
                            <label class="mb-2">Mô tả:</label>
                            <input type="text" class="form-control" name="description" id="service-description"
                                value="{{ old('description', $role_update->description ?? '') }}">
                        </div>

                        <div class="content-checkbox mt-3">
                            <p>Chọn quyền:</p>
                            <div class="d-flex">
                                @foreach ($columns as $column)
                                    <div class="d-flex flex-column ms-5">
                                        @foreach ($column as $group)
                                            <div class="d-flex gap-2 mt-3 align-items-center">
                                                <!-- Checkbox cho nhóm quyền -->
                                                <input type="checkbox" id="permission-{{ Str::slug($group['name']) }}"
                                                    name="permission_groups[]" value="{{ $group['code'] }}"
                                                    {{ in_array($group['code'], old('permission_groups', [])) || $group['selected'] ? 'checked' : '' }}
                                                >
                                                <label for="permission-{{ Str::slug($group['name']) }}">
                                                    {{ $group['name'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="is_active" value="0">
                        <div class="content-checkbox mt-3">
                            <p>Trạng thái:</p>
                            <div class="d-flex gap-2 ms-5 mt-3 publish-articles">
                                <input id="active" type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $role_update->is_active) ? 'checked' : '' }}>
                                <label for="active">Hoạt động</label>
                            </div>
                        </div>

                        <div class="button-news d-flex justify-content-end gap-3">
                            <a href="{{ url()->current() }}" class="cancel-news me-2">Hủy</a>
                            <button type="submit" class="save-news">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>



@endsection
