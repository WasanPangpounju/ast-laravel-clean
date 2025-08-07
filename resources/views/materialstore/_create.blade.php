@extends('layouts.astmanufacturing')

@section('content')

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
				<li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
				<li class="breadcrumb-item active">เบิกวัตถุดิบใช้ภายใน</li>
			</ol>
		</div>
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2 Header">
					<div class="col">
						<h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> เบิกวัตถุดิบใช้ภายใน</h1>
				    </div>
			    </div><!-- /.row -->
		    </div><!-- /.container-fluid -->
		</div>
		<div class="content">
			<div class="box-from">

			  <form method="post" action="{{ route('materialstore.store') }}">
			  						@csrf

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="department">เบิกวัตถุดิบใช้ที่</label>
							<select name="department" class="form-control">
								<option value="ห้องสืบผ้า">ห้องสืบผ้า</option>
								<option value="ห้องทอผ้า">ห้องทอผ้า</option>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
<?php
    $empMaterial = App\Http\Controllers\materialstoreController::empData();
//print(count($empMaterial) );

?>
							<label for="emp">พนักงาน</label>
							<select name="emp" class="form-control">
								<option value="ทดสอบ1">ลำพะไทย</option>
								<option value="ทดสอบ2">หิรัณย​์</option>
								<option value="ทดสอบ3">สิริสาคร</option>
								<option value="ทดสอบ4">ทดสอบ4</option>
								<option value="ทดสอบ5">ทดสอบ5</option>

							</select>
						</div>
					</div>
				</div><!--row-->

				<fieldset>

				  <div class="box_add">
					  <div class="left">
						<button name="submit" value="addemp" class="btn_add"> <i class="nav-icon fas fa fa-plus-circle"></i>&nbsp; เพิ่มชื่อพนักงาน</button>
					</div>
				  </div>

				  <div class="row">

					  <div class="col-md-3">
						   <div class="form-group">
							<label for="supId">บริษัท</label>
							<input type="text" name="supplierName" onkeyup="supplierFunction('supId')" class="form-control" id="supId" placeholder="บริษัท" required >

<?php $suppliershow = 'test'; 
    $supplier = App\Http\Controllers\MaterialController::supData();
?>
				<ul id="supp">
					@foreach($supplier as $sup)
						<li><a href="javascript:setsupplierFunction('supp', '{{$sup->name}}');">{{$sup->name}}</a></li>
					@endforeach
				</ul> 

						   </div>
					  </div>

					  <div class="col-md-3">
						   <div class="form-group">
							<label for="yarntype">ชนิดด้าย</label>
							<input type="text" name="yarnType" onkeyup="yarntypeFunction('yarntype')" class="form-control" id="yarntype" placeholder="ชนิดด้าย" required >

				<ul id="yarnty">
					@foreach($stockYarns as $key => $yarntype)
						<li><a href="javascript:setyarntypeFunction('yarnty', '{{ $key }}');">{{ $key }}</a></li>
					@endforeach
				</ul> 

						   </div>
					  </div>

					  <div class="col-md-3">
						   <div class="form-group">
							<label for="spool">จำนวน (ลูก)</label>
							<input type="text" name="spool" class="form-control" id="spool" placeholder="จำนวน" required >
						   </div>
					  </div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="createDate">วันที่</label>
							<div class="input-group date" id="reservationdate" data-target-input="nearest">
								<input type="text" name="createDate" class="form-control datetimepicker-input" data-target="#reservationdate" />
								<div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
						</div>
					</div>

				  </div>
			    </fieldset>

				<div class="line_btn">
					<button class="btn b_order clean"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>" width="15"> เคลียร์ข้อมูล</button>
					<button name="submit" value="checkdata" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="20"> ตรวจสอบ</button>
				</div><!--line_btn-->
			</div><!--box-from-->

			  </form>

		</div><!--content-->
	</div><!-- /.content-wrapper -->

<script>
    var input, filter, ul, li, a, i, txtValue;

    ul = document.getElementById("supp");
    li = ul.getElementsByTagName("li");
//alert(li.length);

    for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }

</script>

<script>
function supplierFunction(id) {
    var input, filter, ul, li, a, i, txtValue;

    input = document.getElementById(id);
    filter = input.value.toUpperCase();
    ul = document.getElementById("supp");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) 
	{
        if(filter  == ""){
    	for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }
break;
}

a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}


function setsupplierFunction(t, t1) {
  document.getElementById("supId").value = t1;
ul = document.getElementById(t);
  ul.style.display = "none";  
}

</script>

<script>
    var input, filter, ul, li, a, i, txtValue;

    ul = document.getElementById("yarnty");
    li = ul.getElementsByTagName("li");
//alert(li.length);

    for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }

</script>

<script>
function yarntypeFunction(id) {
    var input, filter, ul, li, a, i, txtValue;

    input = document.getElementById(id);
    filter = input.value.toUpperCase();
    ul = document.getElementById("yarnty");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) 
	{
        if(filter  == ""){
    	for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }
break;
}

a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}


function setyarntypeFunction(t, t1) {
  document.getElementById("yarntype").value = t1;
ul = document.getElementById(t);
  ul.style.display = "none";  
}

</script>


@endsection
