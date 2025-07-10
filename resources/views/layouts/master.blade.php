<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=1200">
    
    <title>Website đăng ký lấy số trực tuyến SOSR</title>
    <meta name="description" content="@yield('meta_description', 'Website đăng ký lấy số trực tuyến SOSR, hỗ trợ người dân lấy số và theo dõi thủ tục tại dịch vụ một cửa phường Quang Trung - Tp.Vinh - Nghệ An.')" />
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.quilljs.com">
    <link rel="preconnect" href="https://code.jquery.com">
    <link rel="preconnect" href="https://unpkg.com" crossorigin>

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset('frontend/assets/css/index.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive.css') }}"> --}}
    @vite([
        'resources/css/index.css',
        'resources/css/responsive.css'
    ])
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Preload critical image -->
    <link rel="preload" as="image" href="/frontend/assets/images/background-header.avif" fetchpriority="high" type="image/avif">

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>

<body style="zoom: 1;">

    @include('body.header')

    <div class="container-fluid mt-2 mb-3">
        <div class="row main-layout">
            @include('body.sidebar')

            <main class="col-10 display-container">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="position-fixed bottom-0 end-0 p-3 toast-wrapper" style="z-index: 1055">
        <div id="customToast" class="toast toast-animated align-items-center text-white bg-success border-0"
            role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Thông báo!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Đóng"></button>
            </div>
        </div>
    </div>


    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.vi.min.js"></script>

    {{-- <script src="{{ asset('frontend/assets/js/router.js') }}"></script> --}}
    @vite('resources/js/router.js')
    <script>

    </script>
    <script>
        $(document).ready(function() {
            toastr.options = {
                "positionClass": "toast-bottom-right",
                "timeOut": "4000",
                "closeButton": true,
                "progressBar": true
            };

            @if (Session::has('message'))
                var type = "{{ Session::get('alert-type', default: 'info') }}";
                // console.log(type);
                switch (type) {
                    case 'info':
                        toastr.info("{{ Session::get('message') }}");
                        break;
                    case 'success':
                        toastr.success("{{ Session::get('message') }}");
                        break;
                    case 'warning':
                        toastr.warning("{{ Session::get('message') }}");
                        break;
                    case 'error':
                        toastr.error("{{ Session::get('message') }}");
                        break;
                }
            @endif

            @if ($errors->any())

                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-bottom-right", // Góc dưới bên phải
                    "timeOut": "5000",
                    "extendedTimeOut": "1000"
                };
                toastr.error("{{ $errors->first() }}");
            @endif




        });

         window.addEventListener('beforeunload', function() {
           
            const currentPath = window.location.pathname;
            if (!currentPath.includes('/user-logs')) {
            
                localStorage.removeItem('selectedDate');
                localStorage.removeItem('selectedRole');
                localStorage.removeItem('selectedRole');
                
            }

            if (!currentPath.includes('/request-history')) {
            
                localStorage.removeItem('selectedDate');
                localStorage.removeItem('selectedService');
                localStorage.removeItem('selectedCitizen');
                
            }
        });
    </script>
    <script>

    </script>







</body>

</html>
