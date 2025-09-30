<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ASIA TEXTILE CO., LTD.</title>
    <meta name="viewport" content="width=device-width,user-scalable=yes,initial-scale=1, maximum-scale=1, minimum-scale=1">

    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-free/css/all.min.css') }}">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png" sizes="16x16">

    <style>
        #myUL,#supp,.yarntypeUL,.searchsup {list-style-type:none;padding:0;margin:0}
        #myUL li,#supp li,.yarntypeUL li,.searchsup li {font-size:14px !important}
        #myUL li a,#supp li a,.yarntypeUL li a,.searchsup li a{
            border:1px solid #ddd;margin-top:-1px;background:#f6f6f6;padding:12px;
            text-decoration:none;font-size:14px !important;color:#000;display:block
        }
        #myUL li a:hover,#supp li a:hover,.yarntypeUL li a:hover,.searchsup li a:hover{background:#eee}
        .hidden{display:none}
    </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item Fullscreen">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button" style="letter-spacing:1px;">
                    ดูเต็มจอ <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item logout">
                <a class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    ออกจากระบบ &nbsp;<img src="{{ asset('assets/images/arrow-right-from-bracket-solid.png') }}" width="17" alt="">
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/home" class="brand-link">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="brand-image">
            <span class="brand-text font-weight-light">ASIA TEXTILE CO., LTD.</span>
        </a>

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
                            <li class="nav-item">
                                <a href="{{ route('supplier.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>ข้อมูลซัพพลายเออร์</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('order.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>ใบคำสั่งขาย</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('order.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>ตรวจสอบใบสั่งขาย</p></a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-folder"></i><p>ระบบคลังสินค้า <i class="right fas fa-angle-left"></i></p></a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('inventory.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>คีย์ผ้าเข้าสต็อก</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('fabricimport.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>คีย์ผ้าซื้อเข้า</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('fabricout.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>เปิดบิลผ้า</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('fabricout.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>พิมพ์บิลส่งของ</p></a>
                            </li>

                            {{-- ปิดเฉพาะเมนู “ตรวจสอบคีย์ผ้าเข้าสต็อก” --}}

                            <li class="nav-item">
                                <a href="{{ route('inventory.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>ออร์เดอร์ลูกค้า</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('stockfabric.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>สต็อกผ้า</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('fabricdeposit.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>สต็อกผ้าฝากจัดเก็บ</p></a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-folder"></i><p>ระบบผลิตสินค้า</p></a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>วัตถุดิบ <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('material.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>ตรวจสอบนำเข้าวัตถุดิบ</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('materialstore.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>ตรวจสอบเบิกวัตถุดิบออกภายใน</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('materialoutside.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>ตรวจสอบเบิกวัตถุดิบออกภายนอก</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('material.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>นำเข้าวัตถุดิบ</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('materialoutside.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>เบิกวัตถุดิบออกภายนอก</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('materialstore.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>เบิกวัตถุดิบออกภายใน</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('package.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>คืนบรรจุภัณฑ์</p></a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon fas fa-cog"></i><p>ตั้งค่า</p></a></li>
                </ul>
            </nav>
        </div>
    </aside>

    @yield('content')

</div>

<footer class="main-footer">
    Copyright © 2022 Asia Industrial Textile Co., Ltd. (AIT) All rights reserved.
</footer>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
<script>
    $(function () {
        $('#datemask').inputmask('dd/mm/yyyy', { placeholder: 'dd/mm/yyyy' });
        $('#datemask2').inputmask('dd/mm/yyyy', { placeholder: 'dd/mm/yyyy' });
        $('[data-mask]').inputmask();
        if (typeof $.fn.datetimepicker === 'function') {
            $('#reservationdate').datetimepicker({ format: 'L' });
        }
    });
    jQuery(function($){
        var path = window.location.href;
        $('ul li a').each(function(){ if (this.href === path) $(this).addClass('active'); });
    });
</script>
</body>
</html>
