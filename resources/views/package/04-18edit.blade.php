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
				<?php
				//  print_r($package);
				?>
				    <div class="List_table">
					   <div class="row">
					   <div class="col-4">
						</div>
						  <div class="col-4 table-responsive">
						  <table class="table table-bordered table-a">
							<thead style="position: sticky;top: 0">
							<tr>

							  <th rowspan="2">บริษัท </th>
							  <th colspan="4">ชนิดบรรจุภัณฑ์ </th>
							</tr>
							<tr>
							  <th>หลอด</th>
							  <th>กระสอบ</th>
							  <th>กล่อง</th>
							  <th>พาเลท</th>
							</tr>
							</thead>
							<tbody>
								<?php 
								$c1 = 0; 
								?>
								<tr>
									<?php
											// $supplier_name = $request->input('supplier_name');
											// $spoolsum = $request->input('spoolsum');
											// $sacksum = $request->input('sacksum');
											// $boxsum = $request->input('boxsum');
											// $palletsum = $request->input('palletsum');
									?>
									<td>{{ $dataEdit[0]->supplierName }}</td>
									@if(!isset($dataEdithtr[0]->spoolsum) ) 
									<td>{{ $dataEdit[0]->spoolsum }}</td>
									@else
									<td>{{ $dataEdit[0]->spoolsum - $dataEdithtr[0]->spoolsum }}</td>
									@endif

									@if(!isset($dataEdithtr[0]->sacksum ) ) 
									<td>{{ $dataEdit[0]->sacksum }}</td>
									@else
									<td>{{ $dataEdit[0]->sacksum - $dataEdithtr[0]->sacksum }}</td>
									@endif

									@if(!isset($dataEdithtr[0]->boxsum ) ) 
									<td>{{ $dataEdit[0]->boxsum  }}</td>
									@else
									<td>{{ $dataEdit[0]->boxsum - $dataEdithtr[0]->boxsum }}</td>
									@endif

									@if(!isset($dataEdithtr[0]->palletsum) ) 
									<td>{{ $dataEdit[0]->palletsum  }}</td>
									@else
									<td>{{ $dataEdit[0]->palletsum - $dataEdithtr[0]->palletsum }}</td>
									@endif

								</tr>
								<tr>
								<form method="POST" action="{{ route('package.store', $dataEdit[0]->supplier_name) }}">
									@csrf

									<input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
									<input type="hidden" name="supplier_name" class="form-control" value="{{ $dataEdit[0]->supplierName }}"> 

									<td>คืนบรรจุภัณฑ์</td>
									<td><input type="text" name="spool" class="form-control"  placeholder="หลอด"></td>
									<td><input type="text" name="sack" class="form-control"   placeholder="กระสอบ"></td>
									<td><input type="text" name="box" class="form-control"  placeholder="กล่อง"></td>
									<td><input type="text" name="pallet" class="form-control"  placeholder="pallet"></td>
								
								</tr>

							</tbody>
						  </table>
						</div>
             	   </div><!--row-->
				   </div><!--List_table-->
				   <div class="line_btn">
				    	<!-- <button class="btn b_order"><i class="nav-icon fa fa-chevron-left"></i>  ข้อมูลรายบริษัท</button> -->
				    	<button class="btn b_save" type="submit" name="submit" value="htrpackage"><img src="assets/images/circle-check-solid.png" width="17"> คืนบรรจุภัณฑ์</button>
			       </div>
				</form><!-- end form -->
            	</div><!-- inner_content -->
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
