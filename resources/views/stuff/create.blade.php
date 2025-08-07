@extends('layouts.astmanufacturing')

@section('content')
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
						<h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i>เพิ่มชื่อพนักงาน</h1>
				    </div>
			    </div><!-- /.row -->
		    </div><!-- /.container-fluid -->
		</div>
        <div class="container mt-2">
        @if(session('status'))
        <div class="alert alert-success mb-1 mt-1">
            {{ session('status') }}
        </div>
        @endif
        <form action="{{ route('stuff.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>ชื่อ:</label>
                        <input type="text" name="Fname" class="form-control" placeholder="ชื่อ">
                        @error('name')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>นามสกุล:</label>
                        <input type="text" name="Lname" class="form-control" placeholder="นามสกุล">
                        @error('email')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- <button type="submit" class="btn btn-primary ml-3">เพิ่มพนักงาน</button>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('stuff.index') }}">ยกเลิก</a>
                </div> -->
                <div class="line_btn">
                    <button name="submit" value="checkdata" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="20"> เพิ่มพนักงาน</button>
					<button class="btn b_order"><a href="{{route('stuff.index')}}"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>" width="15"> ยกเลิก</a></button>
					
				</div><!--line_btn-->
            </div>
        </form>
    </div>
	</div><!-- /.content-wrapper -->
    @endsection