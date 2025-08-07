

<?php $__env->startSection('content'); ?>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<div class="">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
				<li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
				<li class="breadcrumb-item active">เบิกวัตถุดิบใช้ภายใน</li>
			</ol>
		</div>
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2 Header">
					<div class="col">
						<h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> เบิกวัตถุดิบใช้ภายใน</h1>
				    </div>
			    </div><!-- /.row -->
		    </div><!-- /.container-fluid -->
		</div>
		<div class="content">
			<div class="box-from">

			  <form method="post" action="<?php echo e(route('materialstore.store')); ?>">
			  						<?php echo csrf_field(); ?>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="department">เบิกวัตถุดิบใช้ที่</label>
							<select name="department" class="form-control">
								<option value="ห้องสืบผ้า">ห้องสืบผ้า</option>
								<option value="ห้องทอผ้า">ห้องทอผ้า</option>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
<?php
    $empMaterial = App\Http\Controllers\materialstoreController::empData();
//print(count($empMaterial) );

?>
							<label for="emp">พนักงาน</label>
							<select name="emp" class="form-control">
              <?php $__currentLoopData = $stuff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stuffSelect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option><?php echo e($stuffSelect->Fname); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
						</div>
					</div>
				</div><!--row-->

				<fieldset>

				  <div class="box_add">
					  <div class="left">
						<button name="submit" value="addemp" class="btn_add"><a href="<?php echo e(route('stuff.index')); ?>"> <i class="nav-icon fas fa fa-plus-circle"></i>&nbsp; เพิ่มชื่อพนักงาน</a></button>
					</div>
				  </div>

				  <div class="row" id="listwithdraw">

					  <div class="col-md-3">
						   <div class="form-group">
							<label for="supId">บริษัท</label>
							<input type="text" name ="supplierName[0]" onkeyup="supplierFunction('supId')" class="form-control" id="supId" placeholder="บริษัท" required >

<?php $suppliershow = 'test'; 
    $supplier = App\Http\Controllers\MaterialController::supData();
?>
				<ul id="supp">
					<?php $__currentLoopData = $supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><a href="javascript:setsupplierFunction('supp', '<?php echo e($sup->name); ?>');"><?php echo e($sup->name); ?></a></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul> 

						   </div>
					  </div>

					  <div class="col-md-3">
						   <div class="form-group">
							<label for="yarntype">ชนิดด้าย</label>
							<input type="text" name="yarnType[0]" onkeyup="yarntypeFunction('yarntype')" class="form-control" id="yarntype" placeholder="ชนิดด้าย" required >

				<ul id="yarnty">
					<?php $__currentLoopData = $stockYarns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yarntype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><a href="javascript:setyarntypeFunction('yarnty', '<?php echo e($key); ?>');"><?php echo e($key); ?></a></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul> 

						   </div>
					  </div>

					  <div class="col-md-3">
						   <div class="form-group">
							<label for="spool">จำนวน (ลูก)</label>
							<input type="text" name="spool[0]" class="form-control" id="spool" placeholder="จำนวน" required >
						   </div>
					  </div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="createDate">วันที่</label>
							<div class="input-group date" id="reservationdate" data-target-input="nearest">
								<input type="text" name="createDate[0]" class="form-control datetimepicker-input" data-target="#reservationdate" />
								<div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
						</div>
					</div>

				  </div>

				  <div class="line_btn">
<button type="button" onclick="removewithdraw()" class="btn btn-etc"> <i class="fas fa-pencil-alt"></i> ลบรายการล่าสุด</button>
<button type="button" id="add" class="btn btn-etc"> <i class="fas fa-folder"></i> เพิ่มรายการใหม่</button>
</div>

			    </fieldset>

				<div class="line_btn">
					<button type="button" onclick="cleanform()" class="btn b_order clean"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>" width="15"> เคลียร์ข้อมูล</button>
					<button name="submit" value="checkdata" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="20"> ตรวจสอบ</button>
				</div><!--line_btn-->
			</div><!--box-from-->

			  </form>


		</div><!--content-->
	</div><!-- /.content-wrapper -->

<script>
    var input, filter, ul, li, a, i, txtValue;

    ul = document.getElementById("supp");
    li = ul.getElementsByTagName("li");
//alert(li.length);

    for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }

</script>

<script>
function supplierFunction(id) {
    var input, filter, ul, li, a, i, txtValue;

    input = document.getElementById(id);
    filter = input.value.toUpperCase();
    ul = document.getElementById("supp");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) 
	{
        if(filter  == ""){
    	for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }
break;
}

a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}


function setsupplierFunction(t, t1) {
  document.getElementById("supId").value = t1;
ul = document.getElementById(t);
  //ul.style.display = "none";  
    li = ul.getElementsByTagName("li");
//alert(li.length);

    for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }

}

</script>

<script>
    var input, filter, ul, li, a, i, txtValue;

    ul = document.getElementById("yarnty");
    li = ul.getElementsByTagName("li");
//alert(li.length);

    for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }

</script>

<script>
function yarntypeFunction(id) {
    var input, filter, ul, li, a, i, txtValue;

    input = document.getElementById(id);
    filter = input.value.toUpperCase();
    ul = document.getElementById("yarnty");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) 
	{
        if(filter  == ""){
    	for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }
break;
}

a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}


function setyarntypeFunction(t, t1) {
  document.getElementById("yarntype").value = t1;
ul = document.getElementById(t);
  //ul.style.display = "none";  
  li = ul.getElementsByTagName("li");
//alert(li.length);

    for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
    }

}

function cleanform(){
	window.location.href = "https://ast-manufacturing.com/materialstore/create";
}


document.getElementById("add").addEventListener("click", function() {
	addwithdraw();
});

var supplierlist = <?php echo e(Js::from($supplier )); ?>;
var yarntypeList = <?php echo e(Js::from($stockYarns)); ?>;


var supId = 1;
async function addwithdraw() {
const container = document.getElementById('listwithdraw');
const addButton = document.getElementById('add-button');

  const newElement = document.createElement('div');
  newElement.setAttribute("class", "col-md-3");
  const newElement1 = document.createElement('div');
  newElement1.setAttribute("class", "form-group");
  const label = document.createElement('label');
  label.textContent = 'บริษัท';
  const input = document.createElement('input');
  input.setAttribute("type", "text");

 //input.setAttribute("value", "inp" + supId );
 input.setAttribute("name", "supplierName" + "[" + supId + "]");

  const showId = "showSupplierFunction" + "('" + "inp" + supId +"');";
  
  input.setAttribute("onkeyup", showId );

  input.setAttribute("id", "inp" + supId);
  input.setAttribute("class", "form-control");
  input.setAttribute("placeholder", "บริษัท");

const  choosesupplier = document.createElement('ul');
choosesupplier.setAttribute("id", "searchResults" + "inp" + supId);
choosesupplier.setAttribute("class", "searchsup");
//alert(supplierlist[0]['name']);

supplierlist.forEach(function(element) {
  const searchResult = document.createElement('li');
searchResult.setAttribute("class", "hidden");
const a = document.createElement('a');
x = element.name;
x1 = "inp" + supId;
a.setAttribute("href", "javascript:void(0); ");
x2 = "setsearchResult" + "('" + x1 + "','" + x + "');";
a.setAttribute("onclick", x2);
a.textContent = element.name; 

searchResult.appendChild(a);
choosesupplier.appendChild(searchResult);
});

/*for(i =0; i < supplierlist.length; i++){
  const searchResult = await document.createElement('li');
//searchResult.setAttribute("class", "hidden");
const a = await document.createElement('a');
x = supplierlist[i]['name'];
x1 = "inp" + supId;
a.setAttribute("href", "javascript:void(0); ");
//a.setAttribute("onclick", "setsearchResult(x1 , x2 ); ");
a.addEventListener("click", function() {
  setsearchResult(x1 , i);
});

a.textContent = supplierlist[i]['name'];
searchResult.appendChild(a);

choosesupplier.appendChild(searchResult);

}
*/


newElement1.appendChild(label);
newElement1.appendChild(input);
newElement1.appendChild(choosesupplier);

newElement.appendChild(newElement1);

container.appendChild(newElement);

  const newElement2 = document.createElement('div');
  newElement2.setAttribute("class", "col-md-3");
  const newElement3 = document.createElement('div');
  newElement3.setAttribute("class", "form-group");
  const label1 = document.createElement('label');
  label1.textContent = 'ชนิดด้าย';
  const input1 = document.createElement('input');
  input1.setAttribute("type", "text");
  input1.setAttribute("name", "yarnType" + "[" + supId + "]");
  input1.setAttribute("id", "inpy" + supId);

  const showyId = "showYarntypeFunction" + "('" + "inpy" + supId +"');";
  
  input1.setAttribute("onkeyup", showyId );

input1.setAttribute("class", "form-control");
input1.setAttribute("placeholder", "ชนิดด้าย");

const  chooseyarntype = await document.createElement('ul');
chooseyarntype.setAttribute("id", "searchYarntype" + "inpy" + supId);
chooseyarntype.setAttribute("class", "searchsup");
//alert(Object.keys(yarntypeList) );
//alert(JSON.stringify(yarntypeList, null, 2));


Object.keys(yarntypeList).forEach(function(element) {
  const searchlist = document.createElement('li');
searchlist.setAttribute("class", "hidden");
const a = document.createElement('a');
x = element;
x1 = "inpy" + supId;
a.setAttribute("href", "javascript:void(0); ");
x2 = "setyarntypeResult" + "('" + x1 + "','" + x + "');";
a.setAttribute("onclick", x2);
a.textContent = element; 

searchlist.appendChild(a);
chooseyarntype.appendChild(searchlist );
});

newElement3.appendChild(label1);
newElement3.appendChild(input1);
newElement3.appendChild(chooseyarntype );

newElement2.appendChild(newElement3);

  container.appendChild(newElement2);

  const newElement4 = document.createElement('div');
  newElement4.setAttribute("class", "col-md-3");
  const newElement5 = document.createElement('div');
  newElement5.setAttribute("class", "form-group");
  const label2 = document.createElement('label');
  label2.textContent = 'จำนวน (ลูก)';
  const input2 = document.createElement('input');
  input2.setAttribute("type", "text");
  input2.setAttribute("name", "spool"+ "[" + supId + "]" );
input2.setAttribute("class", "form-control");
input2.setAttribute("placeholder", "จำนวน");
newElement5.appendChild(label2);
newElement5.appendChild(input2);
newElement4.appendChild(newElement5);

  container.appendChild(newElement4);

  const newElement6 = document.createElement('div');
  newElement6.setAttribute("class", "col-md-3");
  const newElement7 = document.createElement('div');
  newElement7.setAttribute("class", "form-group");
  const label3 = document.createElement('label');
  label3.textContent = 'วันที่';
  const newElement8 = document.createElement('div');
  newElement8.setAttribute("class", "input-group date");

  const input3 = document.createElement('input');
  input3.setAttribute("type", "text");
  input3.setAttribute("name", "createDate" + "[" + supId + "]");
input3.setAttribute("class", "form-control datetimepicker-input");
var dt = document.getElementsByName('createDate[0]');
  var value = dt[0].value;
input3.setAttribute("value", value );

newElement8.appendChild(input3);

newElement7.appendChild(label3);
newElement7.appendChild(newElement8);
newElement6.appendChild(newElement7);

  container.appendChild(newElement6);

  supId = supId + 1;

}


function removewithdraw() {
const container = document.getElementById('listwithdraw');
const removeButton = document.getElementById('remove-button');
//alert(container.children.length );
if(container.children.length >4 ){
container.removeChild(container.lastChild);
container.removeChild(container.lastChild);
container.removeChild(container.lastChild);
container.removeChild(container.lastChild);
supId = supId - 1;

}

/*ulx = document.getElementById('searchResults');
lix = ulx.getElementsByTagName('li');
for (var i = 0; i < lix.length; i++) {
  // Get the current <li> element
  var li = lix[i];
  li.classList.remove('hidden');

  // Get the text content of the <li> element
  var liText = li.textContent;

  // Output the text content to the console
  console.log(liText);
}
*/
}

</script>


<script>
  const searchInput = document.getElementById('searchInput');
  const searchResults = document.getElementById('searchResults');

  searchInput.addEventListener('input', function() {
    // Get the search query
    const searchQuery = this.value.toLowerCase();
    // Loop through each list item
    for (let i = 0; i < searchResults.children.length; i++) {
      const listItem = searchResults.children[i];
      const listItemText = listItem.textContent.toLowerCase();

      // If the search query is not found in the list item, hide it
      if (listItemText.indexOf(searchQuery) === -1) {
        listItem.style.display = 'none';
      } else {
        listItem.style.display = '';
      }
    }
  });
  
  document.getElementById("selectsup").addEventListener("click", function() {
    //setsearchResult(inputId , resultNumber){
      alert('x');
});


  async function setsearchResult(inputId , resultNumber){
    //await alert(inputId);
//await alert(resultNumber);
document.getElementById(inputId).value = resultNumber;
 uid = "searchResults" + inputId;
const ul = await document.getElementById(uid);
const lix = await ul.getElementsByTagName('li');
for (var i = 0; i < lix.length; i++) {
  lix[i].setAttribute("class", "hidden");

}


  }

  function showSupplierFunction(Id){
    showId = "searchResults" + Id;
    //alert(showId);
    input = document.getElementById(Id);
    filter = input.value.toUpperCase();
    ul = document.getElementById(showId );
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
      if(filter  == ""){
    	for (i = 0; i < li.length; i++) {
            //li[i].style.display = "none";
//alert('blank');
li[i].classList.add('hidden');
    }
break;
}

a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            //li[i].style.display = "";
            li[i].classList.remove('hidden');
        } else {
            //li[i].classList.remove('hidden');
        }

    }

  }

  async function setyarntypeResult(inputId , resultNumber){
    //await alert(inputId);
//await alert(resultNumber);
document.getElementById(inputId).value = resultNumber;
 uid = "searchYarntype" + inputId;
const ul = await document.getElementById(uid);
const lix = await ul.getElementsByTagName('li');
for (var i = 0; i < lix.length; i++) {
  lix[i].setAttribute("class", "hidden");

}


  }

  function showYarntypeFunction(Id){
    showId = "searchYarntype" + Id;
    //alert(showId);
    input = document.getElementById(Id);
    filter = input.value.toUpperCase();
    ul = document.getElementById(showId );
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
      if(filter  == ""){
    	for (i = 0; i < li.length; i++) {
            //li[i].style.display = "none";
//alert('blank');
li[i].classList.add('hidden');
    }
break;
}

a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            //li[i].style.display = "";
            li[i].classList.remove('hidden');
        } else {
            //li[i].classList.remove('hidden');
        }

    }

  }

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/materialstore/create.blade.php ENDPATH**/ ?>