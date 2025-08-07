@extends('layouts.astmanufacturing')

@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
	   <div class="">
		   <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
				<li class="breadcrumb-item"><a href="#">ระบบซื้อขาย</a></li>
				<li class="breadcrumb-item active">ข้อมูลลูกค้า</li>
			</ol>
	   </div>
		<!-- Content Header (Page header) -->
	    <div class="content-header">
		  <div class="container-fluid">
			<div class="row mb-2">
			   <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ข้อมูลลูกค้า</h1>
			</div>
		  </div>
		</div>
		<!-- Main content -->
		<div class="content">
		    <div class="box-from">
			  <h2 class="title"><i class="fa fa-caret-right"></i> เพิ่มลูกค้า</h2>
			  <form method="post" action="{{ route('customer.update' , $dataEdit->id) }}" >

			  @csrf
									  @method('PATCH')
			  <div class="form-group">
				   <label for= "name">ชื่อลูกค้า/บริษัท *</label>
				   <input type="text" class="form-control" id="name" name="name" placeholder="ชื่อลูกค้า/บริษัท" value="{{ $dataEdit->name }}" required>
			  </div>
			  <div class="form-group">
					<label for= "tax">เลขผู้เสียภาษี *</label>
					<input type="text" class="form-control" id="tax" name="tax" placeholder="เลขผู้เสียภาษี" value="{{ $dataEdit->tax }}" required>
			  </div>
			  <div class="form-group">
					<label for= "address">ที่อยู่ *</label>
					<textarea class="form-control" id="address" name="address" placeholder="ที่อยู่" required>{{ $dataEdit->address }}</textarea>
			  </div>
			  <div class="row">
			  <div class="col-md-6">
				  <div class="form-group">
					<label for= "tel">เบอร์โทรศัพท์ *</label>
					<input type="tel" class="form-control" id="tel" name="tel" placeholder="เบอร์โทรศัพท์" value="{{ $dataEdit->tel }}" required>
			      </div>
			  </div>
			  <div class="col-md-6">
				  <div class="form-group">
					<label for= "email">อีเมล์</label>
					<input type="email" class="form-control" id="email" name="email" placeholder="อีเมล์" value="{{ $dataEdit->email }}">
			      </div>
			  </div>
			  </div>
			  <div class="row">
				  <div class="col-md-6">
					  <div class="form-group">
							<label for= "type">ประเภทสมาชิก</label>
							<select id="type" name="type" class="form-control">
								  <option  selected value="$dataEdit->type"></option>
								  <option value="ลูกค้า">ลูกค้า</option>
								  <option value="โบรกเกอร์">โบรกเกอร์</option>
						</select>
					  </div>
				  </div>
			  </div>

			  <fieldset>


                  <div class="row">
                  <div class="col-12 table-responsive">

				  <?php
						$cuscoorData = App\Http\Controllers\customerController::coordinatorData( $dataEdit->tax );
				  ?>
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
					@for($i = 0; $i < count($cuscoorData); $i++) 
						<input type="hidden" name="coordinator[{{ $i }} ][coID]" value="{{ $cuscoorData[$i]->id }}">
					<tr>
						<td><input type="text" name="coordinator[{{ $i }}][name]" placeholder="ผู้ประสานงาน" class="form-control" value="{{ $cuscoorData[$i]->name }}" required />
						</td>
						<td><input type="text" name="coordinator[{{ $i }}][jobTitle]" placeholder="ตำแหน่ง" class="form-control" value="{{ $cuscoorData[$i]->jobTitle }}" />
						</td>
						<td><input type="text" name="coordinator[{{ $i }}][tel]" placeholder="เบอร์โทรศัพท์" class="form-control" value="{{ $cuscoorData[$i]->tel }}" />
						</td>
						<td><button type="button" name="add" id="dynamic-ar" class="btn btn-outline-primary">Add</button></td>
                	</tr>
				@endfor

				</tbody>
            </table>

                </div>
                <!-- /.col -->
              </div>


			  </fieldset>
			  <div class="line_btn">
				  <button class="btn b_save"><i class="nav-icon fas fa-save" type="submit" id="name" value="submit" name="submit"></i>  บันทึกข้อมูล</button>
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
            '][name]" placeholder="ผู้ประสานงาน" class="form-control" required /></td><td><input type="text" name="coordinator[' + i +
            '][jobTitle]" placeholder="ตำแหน่ง" class="form-control" /></td><td><input type="text" name="coordinator[' + i +
            '][tel]" placeholder="เบอร์โทรศัพท์" class="form-control" /></td><td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td></tr>'
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

  @endsection
