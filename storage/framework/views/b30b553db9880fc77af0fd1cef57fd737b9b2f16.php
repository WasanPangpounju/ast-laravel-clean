

<?php $__env->startSection('content'); ?>

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
	   <div class="">
		   <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
				<li class="breadcrumb-item"><a href="#">ระบบซื้อขาย</a></li>
				<li class="breadcrumb-item active">ข้อมูลซัพพลายเออร์</li>
			</ol>
	   </div>
		<!-- Content Header (Page header) -->
	    <div class="content-header">
		  <div class="container-fluid">
			<div class="row mb-2">
			   <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ข้อมูลซัพพลายเออร์</h1>
			</div>
		  </div>
		</div>
		<!-- Main content -->
		<div class="content">
		    <div class="box-from">
			  <h2 class="title"><i class="fa fa-caret-right"></i> เพิ่มซัพพลายเออร์</h2>
			  <form method="post" action="<?php echo e(route('supplier.store')); ?>">
			  						<?php echo csrf_field(); ?>
			  <div class="form-group">
				   <label for= "name">ชื่อบริษัท *</label>
				   <input type="text" class="form-control" id="name" name="name" placeholder="ชื่อบริษัท" required >
			  </div>
			  <div class="form-group">
					<label for= "tax">เลขผู้เสียภาษี *</label>
					<input type="text" class="form-control" id="tax" name="tax" placeholder="เลขผู้เสียภาษี" required >
			  </div>
			  <div class="form-group">
					<label for= "address">ที่อยู่ *</label>
					<textarea class="form-control" id="address" name="address" placeholder="ที่อยู่" required ></textarea>
			  </div>
			  <div class="row">
			  <div class="col-md-6">
				  <div class="form-group">
					<label for= "tel">เบอร์โทรศัพท์ *</label>
					<input type="tel" class="form-control" id="tel" name="tel" placeholder="เบอร์โทรศัพท์" required >
			      </div>
			  </div>
			  <div class="col-md-6">
				  <div class="form-group">
					<label for= "email">อีเมล์</label>
					<input type="email" class="form-control" id="email" name="email" placeholder="อีเมล์">
			      </div>
			  </div>
			  </div>
			  <div class="row">
				  <div class="col-md-6">
					  <div class="form-group">
							<label for= "type">ประเภทสมาชิก</label>
							<select id="type" name="type" class="form-control">
								  <option value="ซัพพลายเออร์">ซัพพลายเออร์</option>
								  <option value="โบรกเกอร์">โบรกเกอร์</option>
							</select>
					  </div>
				  </div>
			  </div>
			  <fieldset>

                  <div class="row">
                  <div class="col-12 table-responsive">

            <table class="table table-striped" id="dynamicAddRemove">

                    <thead>
                    <tr>
                      <th>ผู้ประสานงาน *</th>
                      <th>ตำแหน่ง </th>
                      <th>เบอร์โทรศัพท์ </th>
                      <th>เพิ่ม/ลบ</th>
                    </tr>
                    </thead>
                    <tbody>

                <tr>
                    <td><input type="text" name="coordinator[0][name]" placeholder="ผู้ประสานงาน" class="form-control" required />
                    </td>
                    <td><input type="text" name="coordinator[0][jobTitle]" placeholder="ตำแหน่ง" class="form-control" />
                    </td>
                    <td><input type="text" name="coordinator[0][tel]" placeholder="เบอร์โทรศัพท์" class="form-control" />
                    </td>
<td><button type="button" name="add" id="dynamic-ar" class="btn btn-outline-primary">Add</button></td>
                </tr>
                    </tbody>
            </table>

                </div>
                <!-- /.col -->
              </div>

			  </fieldset>
			  <div class="line_btn">
                  <button class="btn b_save"><i class="nav-icon fas fa-save" type="submit" id="name" value="submit" name="submit"></i>บันทึกข้อมูล</button>
              </div>
			</form>
			</div><!--box-from-->
		</div><!--content-->
	 
  </div><!-- /.content-wrapper -->  

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    var i = 0;
    $("#dynamic-ar").click( function () {
        ++i;
        $("#dynamicAddRemove").append('<tr><td><input type="text" name="coordinator[' + i +
            '][name]" placeholder="ผู้ประสานงาน" class="form-control" /></td><td><input type="text" name="coordinator[' + i +
            '][jobTitle]" placeholder="ตำแหน่ง" class="form-control" /></td><td><input type="tel" name="coordinator[' + i +
            '][tel]"  placeholder="เบอร์โทรศัพท์" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-input-field">ลบ</button></td></tr>'
            );
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('tr').remove();
    });
</script>

<script>
function addcoorFunction() {
const node = document.createElement("li");
// Create a text node:
const textnode = document.createTextNode("Water");
// Append the text node to the "li" node:
node.appendChild(textnode);
// Append the "li" node to the list:
document.getElementById("cuscoor").appendChild(node);        
//alert(document.getElementById("cuscoor") );
}

</script>

  <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/supplier/create.blade.php ENDPATH**/ ?>