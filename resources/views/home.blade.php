@extends('layouts.astmanufacturing')

@section('content')
  <!-- Content Wrapper. Contains page content -->
 
  <div class="content-wrapper">
   <div class="">
       <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
        </ol>
   </div>
    <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
           <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> หน้าหลัก test</h1>
        </div>
      </div>
    </div>
    <!-- Main content -->
    <div class="content">
       
      
    </div>

  </div>
  <!-- /.content-wrapper -->


                    {{ __('You are logged in!') }}
                    {{ Auth::user()->name }}
: 
                    {{ Auth::user()->user_type }}

@endsection
