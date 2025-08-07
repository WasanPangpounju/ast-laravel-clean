<!doctype html>
<html>
<head>
<meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

<title>ASIA TEXTILE CO., LTD.</title>
<meta name="viewport" content="width=device-width,user-scalable=yes,initial-scale=1, maximum-scale=1, minimum-scale=1">

<link rel="stylesheet" type="text/css" href="<?php echo asset('assets/css/adminlte.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo asset('assets/css/stylesheet.css'); ?>">
  <!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="<?php echo asset('assets/css/bootstrap-colorpicker.min.css'); ?>">
  <!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="<?php echo asset('assets/css/tempusdominus-bootstrap-4.min.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('assets/fonts/fontawesome-free/css/all.min.css'); ?>">
<link rel="icon" href="<?php echo asset('assets/images/favicon.png'); ?>" type="image" sizes="16x16">
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper">


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
  $(function () {
    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
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

</body>
</html>
