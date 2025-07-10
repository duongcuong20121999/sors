<?php $setting = \App\Models\Setting::first(); ?>
<header class="header">
    <img src="{{ asset('frontend/assets/images/background-header.avif') }}" alt="Banner" class="header-banner-img"
        fetchpriority="high" />
    <div class="header-content-wrapper container-fluid px-0 d-flex justify-content-center">
        <div style="width: 100%" class="row d-flex justify-content-between align-items-center mx-0">
            <div class="header-left col-6 col-lg-6 d-flex align-items-center mb-3 mb-md-0">
                <div class="logo-wrapper me-3">
                    <a href="{{ route('get.highest.permission.url') }}">
                        <img src="{{ !empty($setting->logo) ? asset($setting->logo) : asset('frontend/assets/images/Vinh-logo.avif') }}"
                            alt="Logo" class="logo" fetchpriority="high" />
                    </a>
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
                    <span class="address">
                        {{ $setting->ward_name ?? 'PHƯỜNG QUANG TRUNG - TỈNH NGHỆ AN' }}
                    </span>
                </div>
            </div>

            <div class="header-right mx-auto col-6 col-lg-6 d-flex justify-content-md-end justify-content-center align-items-center gap-3">

                @can('service-kiosk-manager.index')
                <a class="button-kiosk d-flex justify-content-center align-items-center"
                    href="{{ route('service-kiosk-manager.index') }}">Hiển thị màn hình trên KIOSK</a>
                @endcan

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" id="logout-button" class="logout-btn">Thoát</button>
                </form>
                <div class="user-info d-flex flex-column align-items-center text-center">
                    <div class="user-avatar-wrapper mb-1">
                        <img class="user-avatar"
                            src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('frontend/assets/images/user.avif') }}"
                            alt="Avatar" class="avatar" />
                    </div>
                    <span class="text-nowrap">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </div>
</header>
