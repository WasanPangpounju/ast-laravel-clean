@extends('layouts.astmanufacturing')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	   <div class="">
		   <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
				<li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
				<li class="breadcrumb-item active">เบิกวัตถุดิบออกภายนอก</li>
			</ol>
	   </div>
		<!-- Content Header (Page header) -->
	    <div class="content-header">
		  <div class="container-fluid">
			<div class="row mb-2 Header">
			  <div class="col">
				<h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> เบิกวัตถุดิบออกภายนอก</h1>
			  </div><!-- /.col -->
			</div><!-- /.row -->
		  </div><!-- /.container-fluid -->
		</div>
		<!-- Main content -->
		<div class="content">
		    <div class="box-from">
               <div class="">
				    <div class="List_table">
					   <div class="row">
						  <div class="col-12 table-responsive">
						  <table class="table table-bordered table-a">
							<thead>
							<tr>
							  <th rowspan="2">วันที่ </th>
							  <th rowspan="2">ชนิดด้าย</th>
							  <th rowspan="2">LOT </th>
							  <th rowspan="2">จำนวนด้าย(ลูก)</th>
							  <th colspan="2">น้ำหนักรวม </th>
							  <th colspan="2">น้ำหนักบรรจุภัณฑ์ </th>
							  <th colspan="2">น้ำหนักสุทธิ </th>
							</tr>
							<tr>
							  <th>ปอนด์</th>
							  <th>กิโลกรัม</th>
							  <th>ปอนด์</th>
							  <th>กิโลกรัม</th>
							  <th>ปอนด์ </th>
							  <th>กิโลกรัม </th>
							</tr>
							</thead>
							<tbody>
							<tr>
							  <td>12/9/2565</td>
							  <td>xxxxxx</td>
							  <td>2</td>
							  <td>xxxxx</td>
							  <td>25</td>
							  <td>55</td>
							  <td>66</td>
							  <td>55</td>
							  <td>100</td>
							  <td>200</td>
							</tr>
							<tr>
							  <td>12/9/2565</td>
							  <td>xxxxxx</td>
							  <td>2</td>
							  <td>xxxxx</td>
							  <td>25</td>
							  <td>55</td>
							  <td>66</td>
							  <td>55</td>
							  <td>100</td>
							  <td>200</td>
							</tr>
							<tr>
							  <td>12/9/2565</td>
							  <td>xxxxxx</td>
							  <td>2</td>
							  <td>xxxxx</td>
							  <td>25</td>
							  <td>55</td>
							  <td>66</td>
							  <td>55</td>
							  <td>100</td>
							  <td>200</td>
							</tr>
							<tr>
							  <td>12/9/2565</td>
							  <td>xxxxxx</td>
							  <td>2</td>
							  <td>xxxxx</td>
							  <td>25</td>
							  <td>55</td>
							  <td>66</td>
							  <td>55</td>
							  <td>100</td>
							  <td>200</td>
							</tr>
							</tbody>
						  </table>
						</div>
             	   </div><!--row-->
				   </div><!--List_table-->
				   <div class="line_btn">
				    	<a href=""><button class="btn b_order"><i class="nav-icon fa fa-chevron-left"></i> ย้อนกลับ</button></a>
				    	<a href="inventory_stocks.php"><button class="btn b_save"><img src="assets/images/circle-check-solid.png" width="17"> เบิกวัตถุดิบ</button></a>
			       </div>
            	</div><!-- inner_content -->
		    </div><!--box-from-->
		</div><!--content-->
  </div><!-- /.content-wrapper -->

@endsection