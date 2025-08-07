@extends('layouts.astmanufacturing')

@section('content')

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
				<li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
                <li class="breadcrumb-item"><a href="#">เบิกวัตถุดิบใช้ภายใน</a></li>
				<li class="breadcrumb-item active">จัดการพนักงาน</li>
			</ol>
		</div>
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2 Header">
					<div class="col">
						<h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> จัดการพนักงาน</h1>
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
                    <div class="List_table">
					   <div class="row">
						  <div class="col-12 table-responsive">
						  <table class="table table-bordered table-a">
							<thead>
							<tr>
							  <th rowspan="2">ชื่อ</th>
							  <th rowspan="2">นามสกุล</th>
							  <th>ลบ</th>
							</tr>
							<tbody>
								@foreach($stuff as $stuffSelect)
                                    <tr>
                                        <td>{{ $stuffSelect->Fname }}</td>
                                        <td>{{ $stuffSelect->Lname }}</td>
										<td>
											<form method="POST" action="{{ route('stuff.destroy', $stuffSelect->id) }}">
												@csrf
												@method('DELETE')
												<button class="btn b_order" value="delete" type="button" onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }" >Delete</button>
											</form>
										</td>
                                    </tr>
                                @endforeach

							</tbody>
						  </table>
						</div>
             	   </div><!--row-->
				   </div><!--List_table-->
					</div>
				</div><!--row-->



				<div class="line_btn">
                    <button name="btn b_order" value="checkdata" class="btn_add" ><a href="{{route('stuff.create')}}"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="20"> เพิ่มพนักงาน</a></button>
					<button class="btn b_order"><a href="{{route('materialstore.create')}}"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>" width="15"> ยกเลิก</a></button>
				</div><!--line_btn-->
			</div><!--box-from-->

			  </form>

		</div><!--content-->
	</div><!-- /.content-wrapper -->

    @endsection