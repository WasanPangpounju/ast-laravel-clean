@extends('layouts.astmanufacturing')

@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a>
				</li>
				<li class="breadcrumb-item"><a href="#">วัตถุดิบ</a>
				</li>
				<li class="breadcrumb-item"><a href="#">ตรวจสอบรายการนำเข้าวัตถุดิบ</a></li>
				<li class="breadcrumb-item active">แก้ไขรายการนำเข้าวัตถุดิบ</li>
			</ol>
		</div>
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> แก้ไขรายการนำเข้าวัตถุดิบ</h1>
				</div>
			</div>
		</div>
		<!-- Main content -->
		<div class="content">
			<div class="box-from">
				<h2 class="title"><i class="fa fa-caret-right"></i> แก้ไขรายการวัตถุดิบ</h2>

			  <form method="post" action="{{ route('material.update',$dataEdit->id) }}" id="myForm">
			  		@csrf
					@method('PATCH')

<input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="supplierName">ชื่อบริษัท * </label>

@if(isset($backdata))
	<input type="text" name="supplierName" onkeyup="supplierFunction('supplierName')" class="form-control" id="supplierName" placeholder="ชื่อบริษัท" value="{{ $backdata->supplierName }}" required>
@else
	<input type="text" name="supplierName" onkeyup="supplierFunction('supplierName')" class="form-control" id="supplierName" placeholder="ชื่อบริษัท" value="{{ $dataEdit->supplierName }}" required>
@endif

<?php $suppliershow = 'test'; ?>
				<ul id="supp">
					@foreach($supplier as $sup)
						<li><a href="javascript:setsupplierFunction('supp', '{{$sup->name}}');">{{$sup->name}}</a></li>
					@endforeach
				</ul> 

						</div>
					</div>
<input type="hidden" id="supplierId" name="supplierId" value="{{ Auth::user()->id }}">
					
					<div class="col-md-4">
						<div class="form-group">
							<label for="importStatus">เลขที่ใบส่งสินค้า</label>

@if(isset($backdata))
	<input type="text" name="importStatus" class="form-control" id="importStatus" placeholder="เลขที่ใบส่งสินค้า" value="{{ $backdata->importStatus }}">
@else
	<input type="text" name="importStatus" class="form-control" id="importStatus" placeholder="เลขที่ใบส่งสินค้า" value="{{ $dataEdit->importStatus }}">
@endif

						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="createDate">วันที่</label>

							@if(isset($backdata))
							<div class="input-group date" id="reservationdate" data-target-input="nearest">
								<input type="text" name="createDate" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{ $backdata->createDate }} "/>
								<div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
							</div>
@else
							<div class="input-group date" id="reservationdate" data-target-input="nearest">
								<input type="text" name="createDate" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{ $dataEdit->createDate }}"/>
								<div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
							</div>
@endif

						</div>
					</div>
				</div><!--row-->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="yarnType">ชนิดด้าย * </label>

							@if(isset($backdata))
								<input type="text" name="yarnType" onkeyup="yarntypeFunction('yarnType')" class="form-control" id="yarnType" placeholder="ชนิดด้าย" value="{{ $backdata->yarnType }}" required>
							@else
								<input type="text" name="yarnType" onkeyup="yarntypeFunction('yarnType')" class="form-control" id="yarnType" placeholder="ชนิดด้าย" value="{{ $dataEdit->yarnType }}" required>
							@endif

							<ul class="yarntypeUL">
@foreach($stockYarns as $key => $yarntype )
	 <li><a href="javascript:setyarntypeFunction('', '{{ $key }}');">{{ $key }}</a></li>
@endforeach
</ul> 

						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="lot">ล็อตที่</label>

							@if(isset($backdata))
								<input type="text" name="lot" class="form-control" id="lot" placeholder="ล็อตที่" value="{{ $backdata->lot }}">
							@else
								<input type="text" name="lot" class="form-control" id="lot" placeholder="ล็อตที่" value="{{ $dataEdit->lot }}">
							@endif

						</div>
					</div>
				</div><!--row-->
				<div class="form-group"><label>บรรจุภัณฑ์</label></div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="pallet" class="col-txt-1"> พาเลท</label>
							<div class="col-form">

							@if(isset($backdata))
								<input type="number" name="pallet" class="form-control" id="inputEmail3" placeholder="จำนวน" value="{{ $backdata->pallet }}">
							@else
								<input type="number" name="pallet" class="form-control" id="inputEmail3" placeholder="จำนวน" value="{{ $dataEdit->pallet }}">
							@endif

							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="box" class="col-txt-1"> กล่อง</label>
							<div class="col-form">

							@if(isset($backdata))
								<input type="number" name="box" class="form-control" id="inputEmail3" placeholder="จำนวน" value="{{ $backdata->box }}">
							@else
							<input type="number" name="box" class="form-control" id="inputEmail3" placeholder="จำนวน" value="{{ $dataEdit->box }}">
							@endif

						</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="sack" class="col-txt-1">กระสอบ</label>
							<div class="col-form">

							@if(isset($backdata))
								<input type="number" name="sack" class="form-control" id="inputEmail3" placeholder="จำนวน" value="{{ $backdata->sack }}">
							@else
								<input type="number" name="sack" class="form-control" id="inputEmail3" placeholder="จำนวน" value="{{ $dataEdit->sack }}">
							@endif

						</div>
						</div>
					</div>
				</div><!--row-->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="spool">จำนวนหลอดทั้งหมด (หลอด)</label>

							@if(isset($backdata))
								<input type="number" name="spool" oninput="setCount(this.value , 'yarnSum')" onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool" placeholder="จำนวนหลอดทั้งหมด (หลอด)" value="{{ $backdata->spool }}" required>
							@else
							<input type="number" name="spool" oninput="setCount(this.value , 'yarnSum')" onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool" placeholder="จำนวนหลอดทั้งหมด (หลอด)" value="{{ $dataEdit->spool }}" required>
							@endif

						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="yarnSum">จำนวนด้ายทั้งหมด (ลูก) * </label>

							@if(isset($backdata))
								<input type="number" name="yarnSum" oninput="setCount(this.value , 'spool')" onchange="setCount(this.value , 'spool')" class="form-control" id="yarnSum" placeholder="จำนวนด้ายทั้งหมด (ลูก)" value="{{ $backdata->spool }}" required>
							@else
								<input type="number" name="yarnSum" oninput="setCount(this.value , 'spool')" onchange="setCount(this.value , 'spool')" class="form-control" id="yarnSum" placeholder="จำนวนด้ายทั้งหมด (ลูก)" value="{{ $dataEdit->spool }}" required>
							@endif

						</div>
					</div>
				</div><!--row-->
				<div class="form-group">น้ำหนักรวมทั้งหมด </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">

							@if(isset($backdata))
								<input type="text" name="weight_p_sum" oninput="weightConverter(this.value , 'weight_kg_sum')" onchange="weightConverter(this.value , 'weight_kg_sum')" class="form-control" id="weight_p_sum" placeholder="ปอนด์" value="{{ $backdata->weight_p_sum }}" required>
							@else
								<input type="text" name="weight_p_sum" oninput="weightConverter(this.value , 'weight_kg_sum')" onchange="weightConverter(this.value , 'weight_kg_sum')" class="form-control" id="weight_p_sum" placeholder="ปอนด์" value="{{ $dataEdit->weight_p_sum }}" required>
							@endif

						</div>
							<label for="weight_p_sum" class="col-txt"> ปอนด์</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">

							@if(isset($backdata))
								<input type="text" name="weight_kg_sum" oninput="weightConverter1(this.value, 'weight_p_sum')" onchange="weightConverter1(this.value , 'weight_p_sum')" class="form-control" id="weight_kg_sum" placeholder=" กิโลกรัม" value="{{ $backdata->weight_kg_sum }}" required>
							@else
								<input type="text" name="weight_kg_sum" oninput="weightConverter1(this.value, 'weight_p_sum')" onchange="weightConverter1(this.value , 'weight_p_sum')" class="form-control" id="weight_kg_sum" placeholder=" กิโลกรัม" value="{{ $dataEdit->weight_kg_sum }}" required>
							@endif

							</div>
							<label for="weight_kg_sum" class="col-txt">  กิโลกรัม</label>
						</div>
					</div>
				</div><!--row-->
				<div class="form-group">น้ำหนักบรรจุภัณฑ์ </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">

							@if(isset($backdata))
								<input type="text" name="weight_p_package" oninput="weightConverter(this.value , 'weight_kg_package')" onchange="weightConverter(this.value , 'weight_kg_package')" class="form-control" id="weight_p_package" placeholder="ปอนด์" value="{{ $backdata->weight_p_package }}" required>
							@else
								<input type="text" name="weight_p_package" oninput="weightConverter(this.value , 'weight_kg_package')" onchange="weightConverter(this.value , 'weight_kg_package')" class="form-control" id="weight_p_package" placeholder="ปอนด์" value="{{ $dataEdit->weight_p_package }}" required>
							@endif

							</div>
							<label for="weight_p_package" class="col-txt"> ปอนด์</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">

							@if(isset($backdata))
								<input type="text" name="weight_kg_package" oninput="weightConverter1(this.value, 'weight_p_package')" onchange="weightConverter1(this.value , 'weight_p_package')" class="form-control" id="weight_kg_package" placeholder=" กิโลกรัม" value="{{ $backdata->weight_kg_package }}" required>
							@else
								<input type="text" name="weight_kg_package" oninput="weightConverter1(this.value, 'weight_p_package')" onchange="weightConverter1(this.value , 'weight_p_package')" class="form-control" id="weight_kg_package" placeholder=" กิโลกรัม" value="{{ $dataEdit->weight_kg_package }}" required>
							@endif

						</div>
							<label for="weight_kg_package" class="col-txt">  กิโลกรัม</label>
						</div>
					</div>
				</div><!--row-->
				<div class="form-group">น้ำหนักสุทธิ </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">

							@if(isset($backdata))
								<input type="text" name="weight_p_net" class="form-control" id="weight_p_net" placeholder="ปอนด์" value="{{ $backdata->weight_p_net }}" required>
							@else
								<input type="text" name="weight_p_net" class="form-control" id="weight_p_net" placeholder="ปอนด์" value="{{ $dataEdit->weight_p_net }}" required>
							@endif

						</div>
							<label for="weight_p_net" class="col-txt"> ปอนด์</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">

							@if(isset($backdata))
								<input type="text" name="weight_kg_net" class="form-control" id="weight_kg_net" placeholder=" กิโลกรัม" value="{{ $backdata->weight_kg_net }}" required>
							@else
								<input type="text" name="weight_kg_net" class="form-control" id="weight_kg_net" placeholder=" กิโลกรัม" value="{{ $dataEdit->weight_kg_net }}" required>
							@endif

						</div>
							<label for="weight_kg_net" class="col-txt">  กิโลกรัม</label>
						</div>
					</div>
				</div><!--row-->
				<div class="form-group">น้ำหนักเฉลี่ยต่อลูก </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">

							@if(isset($backdata))
								<input type="text" name="average_p" class="form-control" id="average_p" placeholder="ปอนด์" value="{{ $backdata->average_p }}" required>
							@else
								<input type="text" name="average_p" class="form-control" id="average_p" placeholder="ปอนด์" value="{{ $dataEdit->average_p }}" required>
							@endif

						</div>
							<label for="average_p" class="col-txt"> ปอนด์</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-form">

							@if(isset($backdata))
								<input type="text" name="average_kg"class="form-control" id="average_kg" placeholder=" กิโลกรัม" value="{{ $backdata->average_kg }}" required>
							@else
								<input type="text" name="average_kg"class="form-control" id="average_kg" placeholder=" กิโลกรัม" value="{{ $dataEdit->average_kg }}" required>
							@endif

						</div>
							<label for="average_kg" class="col-txt">  กิโลกรัม</label>
						</div>
					</div>
				</div><!--row-->

				<div class="row">
					<div class="col-md-2"><label for="" class="col-form-label">ส่งคืนบรรจุภัณฑ์</label></div>
					<div class="col-md-9" style="margin-top:10px;">
						<div class="icheck-primary d-inline">
						@if(isset($backdata) && $backdata->packaging1 == 'pallet')
								<input type="checkbox" id="radioPrimary1" name="packaging1" value="{{ $dataEdit->pallet }}" checked> พาเลท
							@else
								<input type="checkbox" id="radioPrimary1" name="packaging1" value="{{ $dataEdit->pallet }}"> พาเลท
							@endif
						</div>
						<div class="icheck-primary d-inline">
							@if(isset($backdata) && $backdata->packaging2 == 'box')
								<input type="checkbox" id="radioPrimary2" name="packaging2" value="{{ $dataEdit->box }}" checked> กล่อง
							@else
								<input type="checkbox" id="radioPrimary2" name="packaging2" value="{{ $dataEdit->box }}"> กล่อง
							@endif
						</div>
						<div class="icheck-primary d-inline">
							@if(isset($backdata) && $backdata->packaging3 == 'sack')
								<input type="checkbox" id="radioPrimary3" name="packaging3" value="{{ $dataEdit->sack }}" checked> กระสอบ
								@else
								<input type="checkbox" id="radioPrimary3" name="packaging3" value="{{ $dataEdit->sack }}"> กระสอบ
							@endif
						</div>
						<div class="icheck-primary d-inline">
							@if(isset($backdata) && $backdata->packaging4 == 'spool')
								<input type="checkbox" id="radioPrimary4" name="packaging4" value="{{ $dataEdit->spool }}" checked> หลอด
								@else
								<input type="checkbox" id="radioPrimary4" name="packaging4" value="{{ $dataEdit->spool }}"> หลอด
							@endif
						</div>
					</div>
				</div><!--row-->

				<div class="line_btn">
				<button name="submit" value="cleanForm" class="btn b_order clean"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>" width="15">  เคลียร์ข้อมูล</button> 
					<button name="submit" value="checkdata" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17"> แก้ไข</button>
				</div>

			</form>

			</div><!--box-from-->
		</div><!--content-->
	</div>	<!-- /.content-wrapper -->

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
  document.getElementById("supplierName").value = t1;
ul = document.getElementById(t);
  //ul.style.display = "none";  
  li = ul.getElementsByTagName("li");

    for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }

}

</script>

<script>
function weightConverter1(valNum , id) {
//  document.getElementById("weight_kg_sum").innerHTML=valNum/2.2046;
var p = (valNum*2.2046).toFixed(4);
  document.getElementById(id).value = p;


if(id ?? "weight_kg_package") {
var net = 0;
if(p > 0) {
    var x = document.getElementById('weight_p_sum').value;
if(x > 0){
net = (x - p).toFixed(4);

  document.getElementById('weight_p_net').value = net;
  document.getElementById('weight_kg_net').value = (net/2.2046).toFixed(4);

    var y = document.getElementById('spool').value;
var av =0;
if(y > 0){
av = (net / y).toFixed(4);
}
  document.getElementById('average_p').value = av;
  document.getElementById('average_kg').value = ((net/2.2046)/y).toFixed(4);

}
}
}
}

function setCount(valNum , id) {
//  document.getElementById("weight_kg_sum").innerHTML=valNum/2.2046;
  document.getElementById(id).value = valNum;
}


function weightConverter(valNum ,id) {
var k = (valNum/2.2046).toFixed(4);
  document.getElementById(id).value = k;

if(id ?? "weight_p_package") {
var net = 0;
if(k > 0) {
    var x = document.getElementById('weight_p_sum').value;
if(x > 0){
net = (x - valNum ).toFixed(4);
//alert(net);

  document.getElementById('weight_p_net').value = net;
  document.getElementById('weight_kg_net').value = (net/2.2046).toFixed(4);

    var y = document.getElementById('spool').value;
var av =0;
if(y > 0){
av = (net / y).toFixed(4);
}
  document.getElementById('average_p').value = av;
  document.getElementById('average_kg').value = ((net/2.2046)/y).toFixed(4);

}
}


}

}

</script>

<script>
    var ul, li;

ul  = document.getElementsByClassName("yarntypeUL")[0];
li = ul.getElementsByTagName("li");
//alert(li.length);

for (i = 0; i < li.length; i++) {
		li[i].style.display = "none";
}

function yarntypeFunction(id) {
    var input, filter, ul, li, a, i, txtValue;

    input = document.getElementById(id);
    filter = input.value.toUpperCase();
    //ul = document.getElementById("syt");
ul  = document.getElementsByClassName("yarntypeUL")[0];

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
  document.getElementById("yarnType").value = t1;
//ul = document.getElementById(t);
ul  = document.getElementsByClassName("yarntypeUL")[0];

  //ul.style.display = "none";  
  li = ul.getElementsByTagName("li");

    for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }

}


function cleanformFunction(){
	$("#myForm").trigger("reset");
}

</script>

  @endsection
