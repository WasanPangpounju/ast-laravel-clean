@extends('layouts.astmanufacturing')

@section('content')
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	   <div class="">
		   <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
				<li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
				<li class="breadcrumb-item active">รายการเบิกวัตถุดิบ {{ $data->createDate[0] }}</li>
			</ol>
	   </div>
		<!-- Content Header (Page header) -->
	    <div class="content-header">
		  <div class="container-fluid">
			<div class="row mb-2 Header">
			  <div class="col">
				<h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> เบิกวัตถุดิบ {{ $data->department }}</h1>
			  </div><!-- /.col -->
			</div><!-- /.row -->
		  </div><!-- /.container-fluid -->
		</div>
		<!-- Main content -->
		<div class="content">
		    <div class="box-from">

			  <form id="myForm" method="post" action="{{ route('materialstore.store') }}">
			 @csrf

               <div class="">
				    <div class="List_table">
					   <div class="row">
						  <div class="col-12 table-responsive">
						  <table class="table table-bordered table-a">
							<thead>
							<tr>
							  <th rowspan="2">บริษัท </th>
							  <th rowspan="2">ชนิดด้าย</th>
							  <th rowspan="2">LOT </th>
							  <th rowspan="2">จำนวนด้าย <br>(ลูก)</th>
							  <th colspan="2">น้ำหนักสุทธิ </th>
							  <th colspan="2">น้ำหนักเฉลี่ย </th>
							</tr>
							<tr>
							  <th>ปอนด์</th>
							  <th>กิโลกรัม</th>
							  <th>ปอนด์</th>
							  <th>กิโลกรัม</th>
							</tr>
							</thead>
							<tbody>

							@for($i =0; $i < count($data->supplierName); $i++)
							<tr>
							  <td>{{ $data->supplierName[$i] }}</td>
							  <td>{{ $data->yarnType[$i] }}</td>

<?php
  $yarntype[$i] = $data->yarnType[$i];
?>
							  <td>
							  <select name="selectlot[$i]" onchange="getlot({{ $i }})" id="select{{ $i }}" class="form-control">
@for($j = 0; $j < count($yarnlist[$data->yarnType[$i] ] ); $j ++ )
<option value="{{ $j }}"> {{ $yarnlist[$data->yarnType[$i] ][$j][0]}} </option>
@endfor

							</select>

							  </td>

							  <td id="spl{{ $i }}">{{ $data->spool[$i] }}</td>
							  <td id="tda{{ $i }}">
								{{ $yarnlist[$data->yarnType[$i] ][0][3] * $data->spool[$i] }}
							</td>
							  <td id="tdb{{ $i }}">
								{{ $yarnlist[$data->yarnType[$i] ][0][4] * $data->spool[$i] }}
							</td>
							  <td id="tdc{{ $i }}">{{ $yarnlist[$data->yarnType[$i] ][0][3]}}</td>
							  <td id="tdd{{ $i }}">{{ $yarnlist[$data->yarnType[$i] ][0][4]}}</td>
							</tr>


							<input type="hidden" id="withdrawId{{ $i }}" name="withdrawId[{{ $i }}]" value="{{ Auth::user()->name }}">
							<input type="hidden" id="department{{ $i }}" name="department[{{ $i }}]" value="{{ $data->department }}">
							<input type="hidden" id="emp{{ $i }}" name="emp[{{ $i }}]" value="{{ $data->emp }}">
							<input type="hidden" id="supplierName{{ $i }}" name="supplierName[{{ $i }}]" value="{{ $data->supplierName[ $i ] }}">
							<input type="hidden" id="yarnType{{ $i }}" name="yarnType[{{ $i }}]" value="{{ $data->yarnType[ $i ] }}">
							<input type="hidden" id="lot{{ $i }}" name="lot[{{ $i }}]" value="{{ $yarnlist[$data->yarnType[$i] ][0][0]}}">
							<input type="hidden" id="spool{{ $i }}" name="spool[{{ $i }}]" value="{{ $data->spool[$i] }}">
							<input type="hidden" id="weight_p_net{{ $i }}" name="weight_p_net[{{ $i }}]" value="{{ $yarnlist[$data->yarnType[$i] ][0][1]}}">
							<input type="hidden" id="weight_kg_net{{ $i }}" name="weight_kg_net[{{ $i }}]" value="{{ $yarnlist[$data->yarnType[$i] ][0][2]}}">
							<input type="hidden" id="average_p{{ $i }}" name="average_p[{{ $i }}]" value="{{ $yarnlist[$data->yarnType[$i] ][0][3]}}">
							<input type="hidden" id="average_kg{{ $i }}" name="average_kg[{{ $i }}]" value="{{ $yarnlist[$data->yarnType[$i] ][0][4]}}">
							<input type="hidden" id="createDate{{ $i }}" name="createDate[{{ $i }}]" value="{{ $data->createDate[$i] }}">

@endfor

							</tbody>
						  </table>
						</div>
             	   </div><!--row-->
				   </div><!--List_table-->
				   <div class="line_btn">


				    	<button name="submit" value="back" class="btn b_order"><i class="nav-icon fa fa-chevron-left"></i>  ย้อนกลับ</button>
				    	<button name="submit" value="save" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17"> เบิกวัตถุดิบ</button>

			       </div>
</form>

            	</div><!-- inner_content -->
		    </div><!--box-from-->
		</div><!--content-->
  </div><!-- /.content-wrapper -->

<script>
	var lotdata = {{ Js::from($yarnlist) }};
var yarntype = {{ Js::from($yarntype) }};

	function getlot(id ) {
		 //alert(id);
		 selectElement = document.querySelector('#select' + id);
//get spool 
		 spl = document.querySelector('#spl' + id).textContent;

		 output = selectElement.value;

		 //alert( lotdata[yarntype[id] ][output][1] );

         document.querySelector('#lot'+ id).value = lotdata[yarntype[id] ][output][0];

//          document.querySelector('#weight_p_net'+ id).value = lotdata[yarntype[id] ][output][1].toFixed(4);
        document.querySelector('#weight_p_net'+ id).value =  (lotdata[yarntype[id] ][output][3] * spl).toFixed(4);

//          document.querySelector('#weight_kg_net'+ id).value = lotdata[yarntype[id] ][output][2].toFixed(4);
         document.querySelector('#weight_kg_net'+ id).value = (lotdata[yarntype[id] ][output][4] * spl ).toFixed(4);

         document.querySelector('#average_p'+ id).value = lotdata[yarntype[id] ][output][3].toFixed(4);
         document.querySelector('#average_kg'+ id).value = lotdata[yarntype[id] ][output][4].toFixed(4);

//          document.querySelector('#tda'+ id).textContent = lotdata[yarntype[id] ][output][1].toFixed(4);
         document.querySelector('#tda'+ id).textContent = (lotdata[yarntype[id] ][output][3] * spl).toFixed(4);
 
//         document.querySelector('#tdb' + id).textContent = lotdata[yarntype[id] ][output][2].toFixed(4);
        document.querySelector('#tdb' + id).textContent = (lotdata[yarntype[id] ][output][4] * spl ).toFixed(4);

        document.querySelector('#tdc' + id).textContent = lotdata[yarntype[id] ][output][3].toFixed(4);
        document.querySelector('#tdd' + id).textContent = lotdata[yarntype[id] ][output][4].toFixed(4);

	}
	</script>
  @endsection
