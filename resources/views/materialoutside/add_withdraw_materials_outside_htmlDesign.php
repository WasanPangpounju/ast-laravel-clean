<?php include("include/header.php");?>
<div class="wrapper">
	<!-- Navbar -->
	<?php include("include/top.php");?>
	<!-- /.navbar -->
	<?php include("include/aside_left.php");?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a>
				</li>
				<li class="breadcrumb-item"><a href="#">วัตถุดิบ</a>
				</li>
				<li class="breadcrumb-item active">เบิกวัตถุดิบออกภายนอก</li>
			</ol>
		</div>
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> เบิกวัตถุดิบออกภายนอก</h1>
				</div>
			</div>
		</div>
		<!-- Main content -->
		<div class="content">
			<div class="box-from">
				<h2 class="title"><i class="fa fa-caret-right"></i> เพิ่มวัตถุดิบออกภายนอก</h2>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>ชื่อบริษัท</label>
							<input type="" class="form-control" id="" placeholder="ชื่อบริษัท">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>วันที่</label>
							<div class="input-group date" id="reservationdate" data-target-input="nearest">
								<input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
								<div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
						</div>
					</div>
				</div><!--row-->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>ชนิดด้าย</label>
							<input type="" class="form-control" id="" placeholder="ชนิดด้าย">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>ล็อตที่</label>
							<input type="" class="form-control" id="" placeholder="ล็อตที่">
						</div>
					</div>
				</div><!--row-->
				<div class="form-group"><label>บรรจุภัณฑ์</label></div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="" class="col-txt-1"> ปอนด์</label>
							<div class="col-form">
								<input type="" class="form-control" id="inputEmail3" placeholder="ปอนด์">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="" class="col-txt-1"> กล่อง</label>
							<div class="col-form">
								<input type="" class="form-control" id="inputEmail3" placeholder="กล่อง">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="" class="col-txt-1">กระสอบ</label>
							<div class="col-form">
								<input type="" class="form-control" id="inputEmail3" placeholder="กระสอบ">
							</div>
						</div>
					</div>
				</div><!--row-->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>จำนวนหลอดทั้งหมด (หลอด)</label>
							<input type="" class="form-control" id="" placeholder="จำนวนหลอดทั้งหมด (หลอด)">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>จำนวนด้ายทั้งหมด (ลูก)</label>
							<input type="" class="form-control" id="" placeholder="จำนวนด้ายทั้งหมด (ลูก)">
						</div>
					</div>
				</div><!--row-->
				<div class="form-group">น้ำหนักรวมทั้งหมด </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">
								<input type="" class="form-control" id="inputEmail3" placeholder="ปอนด์">
							</div>
							<label for="" class="col-txt"> ปอนด์</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">
								<input type="" class="form-control" id="inputEmail3" placeholder=" กิโลกรัม">
							</div>
							<label for="" class="col-txt">  กิโลกรัม</label>
						</div>
					</div>
				</div><!--row-->
				<div class="form-group">น้ำหนักบรรจุภัณฑ์ </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">
								<input type="" class="form-control" id="inputEmail3" placeholder="ปอนด์">
							</div>
							<label for="" class="col-txt"> ปอนด์</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">
								<input type="" class="form-control" id="inputEmail3" placeholder=" กิโลกรัม">
							</div>
							<label for="" class="col-txt">  กิโลกรัม</label>
						</div>
					</div>
				</div><!--row-->
				<div class="form-group">น้ำหนักสุทธิ </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">
								<input type="" class="form-control" id="inputEmail3" placeholder="ปอนด์">
							</div>
							<label for="" class="col-txt"> ปอนด์</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">
								<input type="" class="form-control" id="inputEmail3" placeholder=" กิโลกรัม">
							</div>
							<label for="" class="col-txt">  กิโลกรัม</label>
						</div>
					</div>
				</div><!--row-->
			    <div class="row">
					<div class="col-md-2"><label for="" class="col-form-label">ส่งคืนบรรจุภัณฑ์</label></div>
					<div class="col-md-9" style="margin-top:10px;margin-bottom: 15px;">
						<div class="icheck-primary d-inline">
							<input type="radio" id="radioPrimary1" name="r1" checked=""> พาเลท
						</div>
						<div class="icheck-primary d-inline">
							<input type="radio" id="radioPrimary2" name="r1"> กล่อง
						</div>
						<div class="icheck-primary d-inline">
							<input type="radio" id="radioPrimary3" name="r1"> กระสอบ
						</div>
						<div class="icheck-primary d-inline">
							<input type="radio" id="radioPrimary4" name="r1"> หลอด
						</div>
						<div class="icheck-primary d-inline">
							<input type="radio" id="radioPrimary4" name="r1"> ทั้งหมด
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-txt-2">ผู้รับวัตถุดิบ</label>
					<div class="col-form-2">
						<input type="" class="form-control" id="" placeholder="ผู้รับวัตถุดิบ">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-txt-2">การนำไปใช้</label>
					<div class="col-form-2">
						<textarea type="" class="form-control" id="" placeholder="การนำไปใช้"></textarea>
					</div>
				</div>
				<div class="line_btn">
					<a href=""><button class="btn b_order clean"><img src="assets/images/xmark-solid.png" width="15">  เคลียร์ข้อมูล</button></a>
					<a href="check_import_materials.php"><button class="btn b_save"><img src="assets/images/circle-check-solid.png" width="17"> ตรวจสอบ</button></a>
				</div>
			</div><!--box-from-->
		</div><!--content-->
	</div>	<!-- /.content-wrapper -->

	<?php include("include/footer.php");?>
	</body>
	</html>