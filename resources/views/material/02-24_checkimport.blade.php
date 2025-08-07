@extends('layouts.astmanufacturing')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	   <div class="">
		   <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
				<li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
			    <li class="breadcrumb-item"><a href="#">นำเข้าวัตถุดิบ</a></li>
				<li class="breadcrumb-item active">ตรวจสอบใบส่งสินค้า {{ $imdata->importStatus }}</li>
			</ol>
	   </div>
		<!-- Content Header (Page header) -->
	    <div class="content-header">
		  <div class="container-fluid">
			<div class="row mb-2">
			   <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> นำเข้าวัตถุดิบ</h1>
			</div>
		  </div>
		</div>
		<!-- Main content -->
		<div class="content">
			<div class="box-from">
				<div class="List_table">
					   <div class="row">
						  <div class="col-12 table-responsive">
						  <table class="table table-bordered table-a">
							<thead>
							<tr>
							  <th rowspan="2">บริษัท</th>
							  <th rowspan="2">วันที่ </th>
							  <th rowspan="2">ชนิดด้าย </th>
							  <th colspan="3">ประเภทบรรจุภัณฑ์ </th>
							  <th rowspan="2">จำนวน<br>หลอดด้าย </th>
							  <th rowspan="2">จำนวน<br>ด้าย(ลูก)</th>
							  <th colspan="2">น้ำหนักรวม </th>
							  <th colspan="2">น้ำหนักสุทธิ </th>
							  <th colspan="2">น้ำหนักเฉลี่ยต่อลูก </th>
							</tr>
							<tr>
							  <th>พาเลท</th>
							  <th>กล่อง</th>
							  <th>กระสอบ </th>
							  <th>ปอนด์</th>
							  <th>กิโลกรัม</th>
							  <th>ปอนด์ </th>
							  <th>กิโลกรัม </th>
							  <th>ปอนด์ </th>
							  <th>กิโลกรัม </th>
							</tr>
							</thead>
							<tbody>
							<tr>
							  <td>{{ $imdata->supplierName }}</td>
							  <td>{{ $imdata->createDate}}</td>
							  <td>{{ $imdata->yarnType}}</td>
							  <td>{{ $imdata->pallet}}</td>
							  <td>{{ $imdata->box}}</td>
							  <td>{{ $imdata->sack}}</td>
							  <td>{{ $imdata->spool}}</td>
							  <td>{{ $imdata->spool}}</td>
							  <td>{{ $imdata->weight_p_sum}}</td>
							  <td>{{ $imdata->weight_kg_sum}}</td>
							  <td>{{ $imdata->weight_p_net }}</td>
							  <td>{{ $imdata->weight_kg_net }}</td>
							  <td>{{ $imdata->average_p }}</td>
							  <td>{{ $imdata->average_kg }}</td>
							</tr>
							</tbody>
						  </table>
						</div>
             	   </div><!--row-->
				   </div><!--List_table-->

			 <form method="post" action="{{ route('material.store') }}" >
			@csrf

<input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
<input type="hidden" id="supplierName" name="supplierName" value="{{ $imdata->supplierName }}">
<input type="hidden" id="supplierId" name="supplierId" value="{{ $imdata->supplierId }}">
<input type="hidden" id="createDate" name="createDate" value="{{ $imdata->createDate }}">
<input type="hidden" id="yarnType" name="yarnType" value="{{ $imdata->yarnType }}">
<input type="hidden" id="lot" name="lot" value="{{ $imdata->lot }}">
<input type="hidden" id="pallet" name="pallet" value="{{ $imdata->pallet }}">
<input type="hidden" id="box" name="box" value="{{ $imdata->box }}">
<input type="hidden" id="sack" name="sack" value="{{ $imdata->sack }}">
<input type="hidden" id="spool" name="spool" value="{{ $imdata->spool }}">
<input type="hidden" id="weight_p_sum" name="weight_p_sum" value="{{ $imdata->weight_p_sum }}">
<input type="hidden" id="weight_kg_sum" name="weight_kg_sum" value="{{ $imdata->weight_kg_sum }}">
<input type="hidden" id="weight_p_package" name="weight_p_package" value="{{ $imdata->weight_p_package }}">
<input type="hidden" id="weight_kg_package" name="weight_kg_package" value="{{ $imdata->weight_kg_package }}">
<input type="hidden" id="weight_p_net" name="weight_p_net" value="{{ $imdata->weight_p_net }}">
<input type="hidden" id="weight_kg_net" name="weight_kg_net" value="{{ $imdata->weight_kg_net }}">
<input type="hidden" id="average_p" name="average_p" value="{{ $imdata->average_p }}">
<input type="hidden" id="average_kg" name="average_kg" value="{{ $imdata->average_kg }}">
<input type="hidden" id="importStatus" name="importStatus" value="{{ $imdata->importStatus }}">

<input type="hidden" id="packaging1" name="packaging1" value="{{ $imdata->packaging1 }}">
<input type="hidden" id="packaging2" name="packaging2" value="{{ $imdata->packaging2}}">
<input type="hidden" id="packaging3" name="packaging3" value="{{ $imdata->packaging3 }}">
<input type="hidden" id="packaging4" name="packaging4" value="{{ $imdata->packaging4 }}">

				   <div class="line_btn">
				   		 <button name="submit" value="back" class="btn b_order"><i class="nav-icon fa fa-chevron-left"></i>  ย้อนกลับ</button>
				   		 <button name="submit" value="save" class="btn b_save"><i class="nav-icon fas fa-save"></i> บันทึก</button>
			       </div>
			  </form>


			</div><!--box-from-->
		</div><!--content-->
	 
  </div><!-- /.content-wrapper -->

  
  @endsection
