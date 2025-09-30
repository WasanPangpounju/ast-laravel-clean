<!doctype html>
<html>
<head>
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ASIA TEXTILE CO., LTD.</title>
    <meta name="viewport" content="width=device-width,user-scalable=yes,initial-scale=1, maximum-scale=1, minimum-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-free/css/all.min.css') }}">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png" sizes="16x16">

    <style>
        #myUL { list-style-type: none; padding: 0; margin: 0; }
        #myUL li { font-size: 14px !important; }
        #myUL li a {
            border: 1px solid #ddd; margin-top: -1px; background-color: #f6f6f6;
            padding: 12px; text-decoration: none; font-size: 14px !important; color: black; display: block
        }
        #myUL li a:hover:not(.header) { background-color: #eee; }

        #supp { list-style-type: none; padding: 0; margin: 0; }
        #supp li { font-size: 14px !important; }
        #supp li a {
            border: 1px solid #ddd; margin-top: -1px; background-color: #f6f6f6;
            padding: 12px; text-decoration: none; font-size: 14px !important; color: black; display: block
        }
        #supp li a:hover:not(.header) { background-color: #eee; }

        #yarnty { list-style-type: none; padding: 0; margin: 0; }
        #yarnty li a {
            border: 1px solid #ddd; margin-top: -1px; background-color: #f6f6f6;
            padding: 12px; text-decoration: none; font-size: 18px; color: black; display: block
        }
        #yarnty li a:hover:not(.header) { background-color: #eee; }

        .yarntypeUL { list-style-type: none; padding: 0; margin: 0; }
        .yarntypeUL li { font-size: 14px !important; }
        .yarntypeUL li a {
            border: 1px solid #ddd; margin-top: -1px; background-color: #f6f6f6;
            padding: 12px; text-decoration: none; font-size: 14px !important; color: black; display: block
        }
        .yarntypeUL li a:hover:not(.header) { background-color: #eee; }

        .searchsup { list-style-type: none; padding: 0; margin: 0; }
        .searchsup li { font-size: 14px !important; }
        .searchsup li a {
            border: 1px solid #ddd; margin-top: -1px; background-color: #f6f6f6;
            padding: 12px; text-decoration: none; font-size: 14px !important; color: black; display: block
        }
        .searchsup li a:hover:not(.header) { background-color: #eee; }

        .hidden { display: none; }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Top Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- fullscreen -->
                <li class="nav-item Fullscreen">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button" style="letter-spacing: 1px;">
                        ดูเต็มจอ <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <!-- logout (ตัด route('logout') ออกเพื่อไม่ให้ resolve route ตอนเรนเดอร์) -->
                <li class="nav-item logout">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        ออกจากระบบ &nbsp;<img src="{{ asset('assets/images/arrow-right-from-bracket-solid.png') }}" width="17" alt="">
                    </a>
                    <form id="logout-form" action="/logout" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Sidebar (เวอร์ชันเบา) -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="brand-image">
                <span class="brand-text font-weight-light">ASIA TEXTILE CO., LTD.</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('assets/images/admin.jpg') }}" class="img-circle" alt="User Image">
                    </div>
                    <div class="info">
                        @auth
                            <a href="#" class="d-block">
                                <p class="mb-0">{{ auth()->user()->name }}</p>
                                <small>{{ auth()->user()->user_type }}</small>
                            </a>
                        @else
                            <a href="#" class="d-block">ผู้ใช้งาน</a>
                        @endauth
                    </div>
                </div>

                {{-- 
                    ⚠ เมนูดั้งเดิมถูกปิดด้วย Blade comment เพื่อกันไม่ให้ Blade ประมวลผล route() ข้างใน

                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="/home" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>หน้าหลัก</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-cart"></i>
                                    <p>ระบบซื้อขาย <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('customer.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>ข้อมูลลูกค้า</p></a>
                                    </li>
                                    ...
                                    <li class="nav-item">
                                        <a href="{{ route('stockfabric.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>สต็อกผ้า</p></a>
                                    </li>
                                    ...
                                </ul>
                            </li>
                            ...
                        </ul>
                    </nav>
                --}}
            </div><!-- /.sidebar -->
        </aside>

        <!-- Page content -->
        @yield('content')

    </div><!-- /.wrapper -->

    <footer class="main-footer">
        Copyright © 2022 Asia Industrial Textile Co., Ltd. (AIT) All rights reserved.
    </footer>

    <!-- JS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('assets/js/adminlte.min.js') }}"></script>

    <script>
        $(function () {
            // Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#datemask2').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('[data-mask]').inputmask();

            // Date picker
            if (typeof $.fn.datetimepicker === 'function') {
                $('#reservationdate').datetimepicker({ format: 'L' });
            }
        });

        // ไฮไลต์เมนูที่ active (ตอนนี้เมนูหลักถูกปิดไว้ ฟังก์ชันนี้ไม่กระทบ)
        jQuery(function($){
            var path = window.location.href;
            $('ul li a').each(function() {
                if (this.href === path) $(this).addClass('active');
            });
        });
    </script>
</body>
</html>
