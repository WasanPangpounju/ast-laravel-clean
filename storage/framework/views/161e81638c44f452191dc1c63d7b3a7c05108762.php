

<?php $__env->startSection('content'); ?>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	   <div class="">
		   <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
				<li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
				<li class="breadcrumb-item active">รายการเบิกวัตถุดิบ <?php echo e($data->createDate[0]); ?></li>
			</ol>
	   </div>
		<!-- Content Header (Page header) -->
	    <div class="content-header">
		  <div class="container-fluid">
			<div class="row mb-2 Header">
			  <div class="col">
				<h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> เบิกวัตถุดิบ <?php echo e($data->department); ?></h1>
			  </div><!-- /.col -->
			</div><!-- /.row -->
		  </div><!-- /.container-fluid -->
		</div>
		<!-- Main content -->
		<div class="content">
		    <div class="box-from">

			  <form id="myForm" method="post" action="<?php echo e(route('materialstore.store')); ?>">
			 <?php echo csrf_field(); ?>

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

							<?php for($i =0; $i < count($data->supplierName); $i++): ?>
							<tr>
							  <td><?php echo e($data->supplierName[$i]); ?></td>
							  <td><?php echo e($data->yarnType[$i]); ?></td>

<?php
  $yarntype[$i] = $data->yarnType[$i];
?>
							  <td>
							  <select name="selectlot[$i]" onchange="getlot(<?php echo e($i); ?>)" id="select<?php echo e($i); ?>" class="form-control">
<?php for($j = 0; $j < count($yarnlist[$data->yarnType[$i] ] ); $j ++ ): ?>
<option value="<?php echo e($j); ?>"> <?php echo e($yarnlist[$data->yarnType[$i] ][$j][0]); ?> </option>
<?php endfor; ?>

							</select>

							  </td>

							  <td id="spl<?php echo e($i); ?>"><?php echo e($data->spool[$i]); ?></td>
							  <td id="tda<?php echo e($i); ?>">
								<?php echo e($yarnlist[$data->yarnType[$i] ][0][3] * $data->spool[$i]); ?>

							</td>
							  <td id="tdb<?php echo e($i); ?>">
								<?php echo e($yarnlist[$data->yarnType[$i] ][0][4] * $data->spool[$i]); ?>

							</td>
							  <td id="tdc<?php echo e($i); ?>"><?php echo e($yarnlist[$data->yarnType[$i] ][0][3]); ?></td>
							  <td id="tdd<?php echo e($i); ?>"><?php echo e($yarnlist[$data->yarnType[$i] ][0][4]); ?></td>
							</tr>


							<input type="hidden" id="withdrawId<?php echo e($i); ?>" name="withdrawId[<?php echo e($i); ?>]" value="<?php echo e(Auth::user()->name); ?>">
							<input type="hidden" id="department<?php echo e($i); ?>" name="department[<?php echo e($i); ?>]" value="<?php echo e($data->department); ?>">
							<input type="hidden" id="emp<?php echo e($i); ?>" name="emp[<?php echo e($i); ?>]" value="<?php echo e($data->emp); ?>">
							<input type="hidden" id="supplierName<?php echo e($i); ?>" name="supplierName[<?php echo e($i); ?>]" value="<?php echo e($data->supplierName[ $i ]); ?>">
							<input type="hidden" id="yarnType<?php echo e($i); ?>" name="yarnType[<?php echo e($i); ?>]" value="<?php echo e($data->yarnType[ $i ]); ?>">
							<input type="hidden" id="lot<?php echo e($i); ?>" name="lot[<?php echo e($i); ?>]" value="<?php echo e($yarnlist[$data->yarnType[$i] ][0][0]); ?>">
							<input type="hidden" id="spool<?php echo e($i); ?>" name="spool[<?php echo e($i); ?>]" value="<?php echo e($data->spool[$i]); ?>">
							<input type="hidden" id="weight_p_net<?php echo e($i); ?>" name="weight_p_net[<?php echo e($i); ?>]" value="<?php echo e($yarnlist[$data->yarnType[$i] ][0][1]); ?>">
							<input type="hidden" id="weight_kg_net<?php echo e($i); ?>" name="weight_kg_net[<?php echo e($i); ?>]" value="<?php echo e($yarnlist[$data->yarnType[$i] ][0][2]); ?>">
							<input type="hidden" id="average_p<?php echo e($i); ?>" name="average_p[<?php echo e($i); ?>]" value="<?php echo e($yarnlist[$data->yarnType[$i] ][0][3]); ?>">
							<input type="hidden" id="average_kg<?php echo e($i); ?>" name="average_kg[<?php echo e($i); ?>]" value="<?php echo e($yarnlist[$data->yarnType[$i] ][0][4]); ?>">
							<input type="hidden" id="createDate<?php echo e($i); ?>" name="createDate[<?php echo e($i); ?>]" value="<?php echo e($data->createDate[$i]); ?>">

<?php endfor; ?>

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
	var lotdata = <?php echo e(Js::from($yarnlist)); ?>;
var yarntype = <?php echo e(Js::from($yarntype)); ?>;

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
  <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/materialstore/checkwithdraw.blade.php ENDPATH**/ ?>