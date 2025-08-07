

<?php $__env->startSection('content'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
            <li class="breadcrumb-item"><a href="#">ระบบซื้อขาย</a></li>
            <li class="breadcrumb-item active">ข้อมูลซัพพลายเออร์</li>
        </ol>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 Header">
                    <div class="col">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i>ข้อมูลซัพพลายเออร์</h1>
                    </div><!-- /.col -->
                    <div class="col">
                        <div style="text-align: right;">
                            <button class="btn_add"><a href="<?php echo e(route('supplier.create')); ?>"> <i
                                        class="nav-icon fas fa fa-plus-circle"></i>เพิ่มซัพพลายเออร์</a></button>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <div class="box_search">
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn b_order" type="button"
                                style="width: 60%;margin:0.5rem;background-color: #ebd575;"><a
                                    href="<?php echo e(route('customer.index')); ?>">ข้อมูลลูกค้า</a></button>
                            <button class="btn b_order" type="button"
                                style="width: 60%;margin:0.5rem;background-color: #aca06e;"><a
                                    href="<?php echo e(route('supplier.index')); ?>">ข้อมูลซัพพลายเออร์</a></button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn b_order" type="button"
                                style="width: 70%;margin:0.5rem;background-color: #1bccbd;"><a
                                    href="<?php echo e(route('order.create')); ?>">ใบคำสั่งขาย
                                </a></button>
                            <button class="btn b_order" type="button"
                                style="width: 70%;margin:0.5rem;background-color: #8a8a8a;"><a
                                    href="<?php echo e(route('order.index')); ?>">ตรวจสอบใบสั่งขาย</a></button>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="container">

                        </div>
                        <h2 class="text-center display-4">ค้นหาข้อมูลซัพพลายเออร์</h2>
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <form action="<?php echo e(route('supplier.index')); ?>">
                                    <?php echo csrf_field(); ?>

                                    <input type="text" id="myInput" onkeyup="myFunction()"
                                        class="form-control form-control-lg" placeholder="ค้นหาซัพพลายเออร์">

                                    <?php $suppliershow = 'test'; ?>
                                    <ul id="myUL">
                                        <?php $__currentLoopData = $supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplierid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><a
                                                    href="<?php echo e(route('supplier.index', ['id' => $supplierid->id])); ?>"><?php echo e($supplierid->name); ?></a>
                                            </li>
                                            <?php if(request()->id == $supplierid->id): ?>
                                                <?php
                                                $suppliershow = $supplierid;
                                                ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <div class="line_btn_search">
                                        <button type="submit" class="btn_search"><i class="fa fa-search"></i>
                                            &nbsp;ค้นหา</button>
                                    </div>
                                </form>

                                <!-- <form action="">
                            <div class="line_btn">
                    <button type="submit" class="btn btn-etc"> <i class="fas fa-pencil-alt"></i> แก้ไขข้อมูลซัพพลายเออร์</button>
                    <button  type="submit" class="btn btn-etc"> <i class="fas fa-folder"></i> ดูข้อมูลซัพพลายเออร์ทั้งหมด</button>
                    </div>
                           </form> -->


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--box-from-->
        </div>

        <!--content-->
        <?php if(request()->id != ''): ?>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <h2 class="title-result"><i class="nav-icon fa fa-file"></i> ผลการค้นหา</h2><i class=""></i>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">ชื่อลูกค้า/บริษัท</p>
                    </div>
                    <div class="col-md-7">
                        <p><?php echo e($suppliershow->name); ?></p>
                    </div>
                    <div class="col-md-3 text-right">
                        <form method="POST" action="<?php echo e(route('supplier.destroy', $suppliershow->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-danger" type="button"
                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }"
                                style="">ลบ</button>
                            <button class="btn b_order" type="button"><a
                                    href="<?php echo e(route('supplier.edit', $suppliershow->id)); ?>">แก้ไข</a></button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">เลขผู้เสียภาษี</p>
                    </div>
                    <div class="col-md-8">
                        <p><?php echo e($suppliershow->tax); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">ที่อยู่</p>
                    </div>
                    <div class="col-md-8">
                        <p><?php echo e($suppliershow->address); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">เบอร์โทรศัพท์</p>
                    </div>
                    <div class="col-md-8">
                        <p><?php echo e($suppliershow->tel); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">อีเมล์ </p>
                    </div>
                    <div class="col-md-8">
                        <p><?php echo e($suppliershow->email); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">ประเภทสมาชิก </p>
                    </div>
                    <div class="col-md-8">
                        <p><?php echo e($suppliershow->type); ?></p>
                    </div>
                </div>

                <?php
                $cuscoorData = App\Http\Controllers\SupplierController::coordinatorData($suppliershow->tax);
                ?>


                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ผู้ประสานงาน</th>
                                    <th>ตำแหน่ง </th>
                                    <th>เบอร์โทรศัพท์ </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($cuscoorData as $data) {
                                    print '
                                                                                                                                                                                    <tr>
                                                                                                                                                                                      <td>' .
                                        $data->name .
                                        '</td>
                                                                                                                                                                                      <td>' .
                                        $data->jobTitle .
                                        '</td>
                                                                                                                                                                                      <td>' .
                                        $data->tel .
                                        '</td>
                                                                                                                                                                                    </tr>
                                                                                                                                                                ';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="d-grid gap-2 col-1 mx-auto">
                        <button type="button" class="btn btn-primary center"><a
                                href="<?php echo e(route('supplier.index')); ?>">กลับหน้ารายการ</a></button>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!--content-->
        <?php else: ?>
            <div class="content">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ซัพพลายเออร์</th>
                                                <th>หมายเลขโทรศัพท์</th>
                                                <th>รายละเอียด</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php $__currentLoopData = $supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplierid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($supplierid->name); ?></td>
                                                    <td><?php echo e($supplierid->tel); ?></td>
                                                    <td><a
                                                            href="<?php echo e(route('supplier.index', ['id' => $supplierid->id])); ?>">ดูรายละเอียด</a>
                                                    </td>
                                                </tr>

                                                <?php if(request()->id == $supplierid->id): ?>
                                                    <?php
                                                    $suppliershow = $supplierid;
                                                    ?>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div><!-- /.content-wrapper -->

    <script>
        var input, filter, ul, li, a, i, txtValue;
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
        }
    </script>

    <script>
        function myFunction() {
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                if (filter == "") {
                    for (i = 0; i < li.length; i++) {
                        li[i].style.display = "none";
                    }
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
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/supplier/index.blade.php ENDPATH**/ ?>