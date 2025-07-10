<aside class="col-2 menu position-relative">
    @can('dashboard')
    <a href="{{ route('dashboard') }}" class="item-sidebar d-flex justify-content-between " style="
    height: 40px;" id="menu-main-screen">
        <div class="text-service">
            <p class="text-main-1 mb-0">Màn hình chính</p>
            <p class="text-main-2 mb-0">Danh sách hàng đợi chờ xử lý</p>
        </div>
        <div class="icon-wrapper">
            <ion-icon class="btn-sidebar {{ request()->routeIs('dashboard') ? 'active' : '' }}" name="chevron-forward-outline"></ion-icon>
        </div>
    </a>
    @endcan
    @can('service-configurations.index')
    <a href="{{ route('service-configurations.index') }} "
        class="d-flex justify-content-between item-sidebar" >
        <div class="text-service">
            <p class="text-service-1 mb-0">Cấu hình dịch vụ</p>
            <p class="text-service-2 mb-0">
                Danh sách các dịch vụ trực tuyến
            </p>
        </div>
        <div class="icon-wrapper">
            <ion-icon class="btn-sidebar {{ request()->routeIs('service-configurations.*') ? 'active' : '' }}" name="chevron-forward-outline"></ion-icon>
        </div>
    </a>
    @endcan
    @can('posts.index')
        <a href="{{ route('posts.index') }}" class=" d-flex justify-content-between item-sidebar">
            <div class="text-service">
                <p class="text-service-1 mb-0">Quản lý tin tức</p>
                <p class="text-service-2 mb-0">
                    Tin tức hiển thị trên trang chủ
                </p>
            </div>
            <div class="icon-wrapper">
                <ion-icon class="btn-sidebar {{ request()->routeIs('posts.*') ? 'active' : '' }}" name="chevron-forward-outline"></ion-icon>
            </div>
        </a>
    @endcan
    @can('accounts-manager.index')   
        <a href="{{ route('accounts-manager.index') }}" class="d-flex justify-content-between item-sidebar">
            <div class="text-service">
                <p class="text-service-1 mb-0">Quản lý tài khoản</p>
                <p class="text-service-2 mb-0">
                    Danh sách người dùng
                </p>
            </div>
            <div class="icon-wrapper">
                <ion-icon class="btn-sidebar {{ request()->routeIs('accounts-manager.*') ? 'active' : '' }}" name="chevron-forward-outline"></ion-icon>
            </div>
        </a>
    @endcan
    @can('user-roles-manager.index')
    <a href="{{ route('user-roles-manager.index') }}" class="d-flex justify-content-between item-sidebar">
        <div class="text-service">
            <p class="text-service-1 mb-0">Quản lý vai trò</p>
            <p class="text-service-2 mb-0">
                Danh sách vai trò người dùng
            </p>
        </div>
        <div class="icon-wrapper">
            <ion-icon class="btn-sidebar {{ request()->routeIs('user-roles-manager.*') ? 'active' : '' }}" name="chevron-forward-outline"></ion-icon>
        </div>
    </a>
    @endcan
    <a href="{{ route('user-logs.index') }}" class="d-flex justify-content-between item-sidebar">
        <div class="text-service">
            <p class="text-service-1 mb-0">Lịch sử người dùng</p>
            <p class="text-service-2 mb-0">
                Nhật ký sử dụng người dùng
            </p>
        </div>
        <div class="icon-wrapper">
            <ion-icon class="btn-sidebar {{ request()->routeIs('user-logs.*') ? 'active' : '' }}" name="chevron-forward-outline"></ion-icon>
        </div>
    </a>
    @can('request-history.index')
    <a href="{{ route('request-history.index') }}" class="d-flex justify-content-between item-sidebar">
        <div class="text-service">
            <p class="text-service-1 mb-0">Lịch sử yêu cầu</p>
            <p class="text-service-2 mb-0">
                Danh sách các yêu cầu đã xử lý
            </p>
        </div>
        <div class="icon-wrapper">
            <ion-icon class="btn-sidebar {{ request()->routeIs('request-history.*') ? 'active' : '' }}" name="chevron-forward-outline"></ion-icon>
        </div>
    </a>
    @endcan
    @can('settings.index')
    <a href="{{ route('settings.index') }}" class="d-flex justify-content-between item-sidebar">
        <div class="text-service">
            <p class="text-service-1 mb-0">Cài đặt hệ thống</p>
            <p class="text-service-2 mb-0">
                Thiết lập các tham số hệ thống
            </p>
        </div>
        <div class="icon-wrapper">
            <ion-icon class="btn-sidebar {{ request()->routeIs('settings.*') ? 'active' : '' }}" name="chevron-forward-outline"></ion-icon>
        </div>
    </a>
     @endcan
    <div class="divider-vertical p-0"></div>
</aside>
