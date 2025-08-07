

<?php $__env->startSection('content'); ?>
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
            <div class="box-from">
                <div class="List_table">

                    <form method="get" action="/package/create">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier">บริษัท</label>

                                    <select name="supplier" class="form-control">
                                        <option disabled selected value="">เลือกบริษัท</option>
                                        <?php $__currentLoopData = $supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($sup->name); ?>"><?php echo e($sup->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="line_btn_search">
                            <button type="button" class="btn b_order" name="back" value="index"><a
                                    href="<?php echo e(route('package.index')); ?>">กลับ</a></button>
                            <button type="submit" name="submit" value="searchImport" class="btn_search"><i
                                    class="fa fa-search"></i> ค้นหา</button>
                        </div>

                    </form>
                    <div class="row">

                        
                        <div class="col-12 table-responsive">
                            <button class="btn b_order" type="button"><a
                                    href="<?php echo e(route('package.create')); ?>">คืนบรรจุภัณฑ์แบบระบุเอง</a></button>
                            <div><br></div>
                            
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="3">วันที่ </th>
                                        <th rowspan="3" style="width:20%">บริษัท </th>
                                        <th colspan="11">ชนิด บรรจุภัณฑ์</th>
                                        <th rowspan="3">ลบ/แก้ไข</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5">หลอด</th>
                                        <th colspan="1">กระสอบ</th>
                                        <th colspan="1">กล่อง</th>
                                        <th colspan="3">พาเลท</th>
                                        <th colspan="1">กระดาษกั้น</th>
                                    </tr>
                                    <tr>
                                        
                                        
                                        <th>กรวย กระดาษ</th>
                                        <th>กรวย พลาสติก</th>
                                        <th>กระบอก กระดาษ</th>
                                        <th>กระบอก พลาสติก</th>
                                        <th>ไม่ระบุชนิด</th>
                                        <th>พลาสติก</th>
                                        <th></th>
                                        <th>ไม้</th>
                                        <th>เหล็ก</th>
                                        <th>ไม่ระบุชนิด</th>
                                        <th>กระดาษกั้น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $allpackage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $packageList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($date = date('d/m/Y', strtotime($packageList->createDate))); ?></td>
                                            
                                            
                                            <td><?php echo e($packageList->supplier_name); ?></td>
                                            <td><?php echo e($packageList->spool_paper); ?></td>
                                            <td><?php echo e($packageList->spool_plastic); ?></td>
                                            <td><?php echo e($packageList->spoolC_paper); ?></td>
                                            <td><?php echo e($packageList->spoolC_plastic); ?></td>
                                            <td><?php echo e($packageList->spool); ?></td>
                                            <td><?php echo e($packageList->sack); ?></td>
                                            <td><?php echo e($packageList->box); ?></td>
                                            <td><?php echo e($packageList->pallet_wood); ?></td>
                                            <td><?php echo e($packageList->pallet_steel); ?></td>
                                            <td><?php echo e($packageList->pallet); ?></td>
                                            <td><?php echo e($packageList->partition); ?></td>
                                            <td>
                                                <form method="POST"
                                                    action="<?php echo e(route('package.destroy', $packageList->id)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <div
                                                        style="margin:0.5rem;">
                                                        <button class="btn btn-danger" type="button" style="margin:0.2rem;"
                                                            onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button>
                                                            
                                                        <br><button class="btn b_order" type="button" style="margin:0.2rem;"><a
                                                                href="<?php echo e(route('package.edit', $packageList->id)); ?>">แก้ไข</a></button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>
                <!--List_table-->
                <div class="line_btn">
                    <button class="btn b_order"><i class="nav-icon fa fa-chevron-left"></i> ข้อมูลรายบริษัท</button>
                    <button class="btn b_save"><img src="assets/images/circle-check-solid.png" width="17">
                        ตรวจนับวัตถุดิบ</button>
                </div>

            </div><!-- inner_content -->
        </div>
        <!--box-from-->
    </div>
    <!--content-->

    </div><!-- /.content-wrapper -->
    <script>
        pallet = 0, box = 0, sack = 0, spool = 0;
        s = '';

        function myFunction(e) {
            if (e.target.value == 'pallet') {
                s = 'pallet';
                document.getElementById("myText").value = pallet
            }
            if (e.target.value == 'box') {
                s = 'box';
                document.getElementById("myText").value = box
            }
            if (e.target.value == 'sack') {
                s = 'sack';
                document.getElementById("myText").value = sack
            }
            if (e.target.value == 'spool') {
                s = 'spool';
                document.getElementById("myText").value = spool
            }

        }

        function setcountFunction(e) {
            if (s = 'pallet') {
                pallet = e.target.value
            }
            if (s == 'box') {
                box = e.target.value
            }
            if (s == 'sack') {
                sack = e.target.value
            }
            if (s == 'spool') {
                spool = e.target.value
            }

        }
    </script>
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
            for (i = 0; i < li.length; i++) {
                if (filter == "") {
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
            ul.style.display = "none";
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/package/index.blade.php ENDPATH**/ ?>