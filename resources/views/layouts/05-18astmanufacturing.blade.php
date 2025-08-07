<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ASIA TEXTILE CO., LTD.</title>
    <meta name="viewport"
        content="width=device-width,user-scalable=yes,initial-scale=1, maximum-scale=1, minimum-scale=1">

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet"
        href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"> -->


    <link rel="stylesheet" type="text/css" href="<?php echo asset('assets/css/adminlte.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('assets/css/stylesheet.css'); ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/bootstrap-colorpicker.min.css'); ?>">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/tempusdominus-bootstrap-4.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/fonts/fontawesome-free/css/all.min.css'); ?>">
    <link rel="icon" href="<?php echo asset('assets/images/favicon.png'); ?>" type="image" sizes="16x16">


    <style>
        #myUL {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #myUL li {
            font-size: 14px !important;
        }


        #myUL li a {
            border: 1px solid #ddd;
            margin-top: -1px;
            /* Prevent double borders */
            background-color: #f6f6f6;
            padding: 12px;
            text-decoration: none;
            font-size: 14px !important;
            color: black;
            display: block
        }

        #myUL li a:hover:not(.header) {
            background-color: #eee;
        }

        #supp {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }


        #supp li {
            font-size: 14px !important;
        }

        #supp li a {
            border: 1px solid #ddd;
            margin-top: -1px;
            /* Prevent double borders */
            background-color: #f6f6f6;
            padding: 12px;
            text-decoration: none;
            font-size: 14px !important;
            color: black;
            display: block
        }

        #supp li a:hover:not(.header) {
            background-color: #eee;
        }


        #yarnty {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #yarnty li a {
            border: 1px solid #ddd;
            margin-top: -1px;
            /* Prevent double borders */
            background-color: #f6f6f6;
            padding: 12px;
            text-decoration: none;
            font-size: 18px;
            color: black;
            display: block
        }

        #yarnty li a:hover:not(.header) {
            background-color: #eee;
        }

        .yarntypeUL {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .yarntypeUL li {
            font-size: 14px !important;
        }

        .yarntypeUL li a {
            border: 1px solid #ddd;
            margin-top: -1px;
            /* Prevent double borders */
            background-color: #f6f6f6;
            padding: 12px;
            text-decoration: none;
            font-size: 14px !important;
            color: black;
            display: block
        }

        .yarntypeUL li a:hover:not(.header) {
            background-color: #eee;
        }


        .searchsup {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .searchsup li {
            font-size: 14px !important;
        }


        .searchsup li a {
            border: 1px solid #ddd;
            margin-top: -1px;
            /* Prevent double borders */
            background-color: #f6f6f6;
            padding: 12px;
            text-decoration: none;
            font-size: 14px !important;
            color: black;
            display: block
        }

        .searchsup li a:hover:not(.header) {
            background-color: #eee;
        }

        .hidden {
            display: none;
        }
    </style>


</head>

<body class="hold-transition sidebar-mini"
    {{-- style="
  margin: 0;
  font-size: 1.3rem;
  font-weight: 400;
  line-height: 1.5;
  color: #212529;
  text-align: left;
  background-color: #fff;
" --}}
>

    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- fullscreen-->
                <li class="nav-item Fullscreen">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button"
                        style="letter-spacing: 1px;">
                        ดูเต็มจอ <i class="fas fa-expand-arrows-alt"></i> </a>
                </li>
                <li class="nav-item logout" >
                    <a class="nav-link" href="{{ route('logout') }}" 
                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" >
                        ออกจากระบบ &nbsp;<img src="<?php echo asset('assets/images/arrow-right-from-bracket-solid.png'); ?>" width="17"></i>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link">
                <img src="<?php echo asset('assets/images/logo.png'); ?>" alt="Logo" class="brand-image" style="">
                <span class="brand-text font-weight-light">ASIA TEXTILE CO., LTD.</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?php echo asset('assets/images/admin.jpg'); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            <p>{{ Auth::user()->name }}</p>{{ Auth::user()->user_type }}
                        </a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="/home" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>หน้าหลัก</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>ระบบซื้อขาย <i class="right fas fa-angle-left"></i><i class=""></i></p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('customer.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>ข้อมูลลูกค้า</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('supplier.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>ข้อมูลซัพพลายเออร์</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('order.create') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>ใบคำสั่งซื้อ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('order.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>ตรวจสอบใบสั่งซื้อ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="nav-icon fas fa fa-folder"></i>
                                <p> ระบบคลังสินค้า <i class="right fas fa-angle-left"></i><i class=""></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('inventory.create') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>บันทึกรายการสินค้า</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('fabricout.create') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>บันทึกการจัดส่งสินค้า</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('fabricout.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>ตรวจสอบการจัดส่ง</p>
                                    </a>
                                </li>
                                                                <li class="nav-item">
                                    <a href="{{ route('inventory.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>รายการผ้ารอผลิต</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('stockfabric.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>คลังผ้า</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('fabricdeposit.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>คลังผ้าฝากจัดเก็บ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="nav-icon fas fa fa-folder"></i>
                                <p> ระบบผลิตสินค้า</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>วัตถุดิบ <i class="right fas fa-angle-left"></i><i class=""></i></p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('material.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>ตรวจสอบนำเข้าวัตถุดิบ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('materialstore.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>ตรวจสอบเบิกวัตถุดิบออกภายใน</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('materialoutside.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>ตรวจสอบเบิกวัตถุดิบออกภายนอก</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('material.create') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>นำเข้าวัตถุดิบ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('materialoutside.create') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>เบิกวัตถุดิบออกภายนอก</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('materialstore.create') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>เบิกวัตถุดิบออกภายใน</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('package.index') }}" class="nav-link"><i
                                            class="far fa-circle nav-icon"></i>
                                        <p>คืนบรรจุภัณฑ์</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="nav-icon fas fa fa-user"></i>
                                <p>ผู้ตรวจสอบ<i class="right fas fa-angle-left"></i><i class=""></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="{{ route('materialstock.index') }}"
                                        class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>สต๊อกวัตถุดิบ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>ตรวจสอบคำสั่งซื้อ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="#" class="nav-link"><i
                                    class="nav-icon fas fa fa-cog"></i>
                                <p> ตั้งค่า</p>
                            </a></li>
                    </ul>
                </nav>
            </div><!-- /.sidebar -->
        </aside>


        @yield('content')


    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        Copyright © 2022 Asia Industrial Textile Co., Ltd. (AIT) All rights reserved.
    </footer>

    <!-- jQuery -->

    <script src="<?php echo asset('assets/js/jquery.min.js'); ?>"></script>
    <!-- Bootstrap 4 -->

    <!-- Select2 -->
    <script src="<?php echo asset('assets/js/select2.full.min.js'); ?>"></script>
    <!-- Bootstrap4 Duallistbox -->

    <!-- InputMask -->
    <script src="<?php echo asset('assets/js/moment.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/jquery.inputmask.min.js'); ?>"></script>
    <!-- date-range-picker -->
    <script src="<?php echo asset('assets/js/daterangepicker.js'); ?>"></script>
    <!-- bootstrap color picker -->

    <!-- Tempusdominus Bootstrap 4 -->
    <script src="<?php echo asset('assets/js/tempusdominus-bootstrap-4.min.js'); ?>"></script>
    <!-- Bootstrap Switch -->

    <!-- AdminLTE App -->
    <script src="<?php echo asset('assets/js/adminlte.min.js'); ?>"></script>
    <script>
        $(function() {
            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });
        })
    </script>


    <script>
        jQuery(function($) {
            var path = window.location.href;
            // because the 'href' property of the DOM element is the absolute path
            $('ul li a').each(function() {
                if (this.href === path) {
                    $(this).addClass('active');
                }
            });
        });
    </script>


    <!-- JavaScript -->
    <!-- <script src="//code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> -->
    <script>
        // $(document).ready(function() {
        //             // Initialize the first datepicker
        //             $('.datepicker').datepicker({
        //                 format: 'yyyy-mm-dd',
        //                 autoclose: true,
        //                 todayHighlight: true
        //             });

                    // Add a new datepicker when the "Add Date" button is clicked
                    // $('#add-date').click(function() {
                    // Generate a new unique ID for the input field
                    // var newId = "datepicker" + $('.datepicker').length;

                    // Create a new input field with the new ID and add it to the DOM
                    // $('<div class="form-group"><label for="' + newId + '">Select a date:</label><input type="text" class="form-control datepicker" id="' + newId + '" name="' + newId + '"></div>').insertBefore('#add-date');

                    // Initialize the new datepicker
                    // $('#' + newId).datepicker({
                    // format: 'yyyy-mm-dd',
                    // autoclose: true,
                    // todayHighlight: true
                    // });
                    // });
                    // });
    </script>

</body>

</html>
