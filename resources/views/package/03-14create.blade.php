@extends('layouts.astmanufacturing')

@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
				<li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
				<li class="breadcrumb-item active">คืนบรรจุภัณฑ์</li>
			</ol>
		</div>
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2 Header">
					<div class="col">
						<h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i>คืนบรรจุภัณฑ์</h1>
				    </div>
			    </div><!-- /.row -->
		    </div><!-- /.container-fluid -->
		</div>
		<div class="content">
			<div class="box-from">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>บริษัท</label>
							<input type="" class="form-control" id="" placeholder="Supplier">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>บรรจุภัณฑ์</label>
							<select name="" onchange="myFunction(event)" class="form-control">
								<option value="pallet">พาเลท</option>
								<option value="box">กล่อง</option>
								<option value="sack">กระสอบ</option>
								<option value="spool">หลอด</option>
								<option>option 5</option>
							</select>
						</div>
					</div>
				</div>
				<!--row-->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>จำนวน</label>
							<input type="" name="" onchange="setcountFunction(event)" class="form-control" id="myText" placeholder="จำนวน">
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
				<div class="line_btn">
					<a href=""><button class="btn b_order clean"><img src="assets/images/xmark-solid.png" width="15">เคลียร์ข้อมูล</button></a>
					<a href="check_return_packaging.php"><button class="btn b_save"><img src="assets/images/circle-check-solid.png" width="20">ตรวจสอบ</button></a>
					<a href=""><button class="btn b_print"><img src="assets/images/print-solid.png" width="20">พิมพ์ใบส่งคืน</button></a>
				</div><!--line_btn-->
			</div><!--box-from-->
		</div><!--content-->
	</div><!-- /.content-wrapper -->
<script>
pallet = 0, box = 0, sack =0, spool =0;
s = '';

function myFunction(e) {
if(e.target.value == 'pallet'){
s = 'pallet';
    document.getElementById("myText").value = pallet
}
if(e.target.value == 'box'){
s = 'box';
    document.getElementById("myText").value = box
}
if(e.target.value == 'sack'){
s = 'sack';
    document.getElementById("myText").value = sack
}
if(e.target.value == 'spool'){
s = 'spool';
    document.getElementById("myText").value = spool
}

}

function setcountFunction(e) {
if(s = 'pallet'){
pallet = e.target.value 
}
if(s == 'box'){
box = e.target.value 
}
if(s == 'sack'){
sack = e.target.value 
}
if(s == 'spool'){
spool = e.target.value 
}

}

</script>

  @endsection
