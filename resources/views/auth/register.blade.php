<html>
<head>
<meta charset="utf-8">
<title>ASIA TEXTILE CO., LTD.</title>
<meta name="viewport" content="width=device-width,user-scalable=yes,initial-scale=1, maximum-scale=1, minimum-scale=1">

<link rel="stylesheet" type="text/css" href="assets/css/adminlte.css">
<link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
  <!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="assets/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="assets/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="assets/fonts/fontawesome-free/css/all.min.css">
<link rel="icon" href="assets/images/favicon.png" type="image" sizes="16x16">
</head>	

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="index.php" class="brand-link">
      <img src="assets/images/logo.png" alt="Logo" class="brand-image" style="">
      <span class="brand-text font-weight-light">ASIA TEXTILE CO., LTD.</span>
    </a>
	<!-- Sidebar -->
	<div class="sidebar">
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="assets/images/admin.jpg" class="img-circle" alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block"><p>ผู้ดูแลระบบ</p>Super Admin</a>
			</div>
		</div>
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="#" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i> <p>ลงทะเบียน</p></a>
				</li>
</aside>

  <div class="content-wrapper">
	   <div class="">
		   <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">หน้าลงทะเบียนสำหรับ Admin</a></li>
		
			</ol>
	   </div>
		<!-- Content Header (Page header) -->
	    <div class="content-header">
		  <div class="container-fluid">
			<div class="row mb-2">
			   <h1 class="m-0"></i>ลงทะเบียนผู้ใช้งาน</h1>
			</div>
		  </div>
		</div>
		
        <!-- Main content -->
		<div class="content">
		    <div class="box-from">
			  <div class="form-group">
              <form method="POST" action="{{ route('register') }}">
                        @csrf

				<label for="name" class="col-md-6 col-form-label text-md-end">ชื่อผู้ใช้งาน</label>
				   <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Username">

                        @error('name')
                                 <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                 </span>
                        @enderror
			  </div>
              <div class="row">
				  <div class="col-md-6">
					  <div class="form-group">
                        <label for= "user_type">ประเภทผู้ใช้งาน</label>
							<select id="user_type" name="user_type" class="form-control" value="{{ old('user_type') }}" required autocomplete="user_type">
                                    <option value="superadmin">ผู้บริหาร</option>    
                                    <option value="admin">แอดมิน</option>
                                    <option value="supermaterialstaff">หัวหน้าฝ่ายวัตถุดิบ</option>
                                    <option value="materialstaff">พนักงานฝ่ายวัตถุดิบ</option>
                            </select>
                        
                            @error('user_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
					  </div>
				  </div>
			  </div>
			  <div class="form-group">
                <label for="email" class="col-md-6 col-form-label text-md-end">Email Address</label>
				    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="อีเมล์">
			
                             @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
            </div>
			  <div class="form-group">
                <label for="password" class="col-md-6 col-form-label text-md-end">รหัสผ่าน</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">

			  </div>
              <div class="form-group">
                <label for="password-confirm" class="col-md-6 col-form-label text-md-end">ยืนยันรหัสผ่าน</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
			  </div>
			  </div>
			</div><!--box-from-->
            <div class="line_btn">
				  <button type="submit" class="btn b_save">ลงทะเบียน</button>
			</div>
        </form>
		</div><!--content-->
  </div><!-- /.content-wrapper -->  
</body>
</html>