

<?php $__env->startSection('content'); ?>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
        rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="/stockfabric/">คลังสินค้า</a>
                </li>
                <li class="breadcrumb-item active">ตรวจสอบคีย์ผ้าเข้าสต็อก</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ตรวจสอบคีย์ผ้าเข้าสต็อก</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> ตรวจสอบคีย์ผ้าเข้าสต็อก</h2>
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn b_order" type="button"
                                style="width: 60%;margin:0.5rem;background-color: #ebd575;"><a
                                    href="<?php echo e(route('inventory.index')); ?>">ออร์เดอร์ลูกค้า</a></button>
                            <button class="btn b_order" type="button"
                                style="width: 60%;margin:0.5rem;background-color: #aca06e;"><a
                                    href="<?php echo e(route('fabricout.index')); ?>">พิมพ์บิลส่งของ</a></button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn b_order" type="button"
                                style="width: 70%;margin:0.5rem;background-color: #1bccbd;"><a
                                    href="<?php echo e(route('inventory.create')); ?>">คีย์ผ้าเข้าสต็อก
                                </a></button>
                            <button class="btn b_order" type="button"
                                style="width: 70%;margin:0.5rem;background-color: #8a8a8a;"><a
                                    href="<?php echo e(route('fabricout.create')); ?>">เปิดบิลผ้า</a></button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn b_order" type="button"
                                style="width: 55%;margin:0.5rem;background-color: #ec9c06;"><a
                                    href="<?php echo e(route('stockfabric.index')); ?>">สต็อกผ้า</a></button>
                            <button class="btn b_order" type="button"
                                style="width: 55%;margin:0.5rem;background-color: rgb(175, 163, 110);"><a
                                    href="<?php echo e(route('fabricdeposit.index')); ?>">สต็อกผ้าฝากจัดเก็บ</a></button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn b_order" type="button"
                                style="width: 70%;margin:0.5rem;background-color: #ec9c06;"><a
                                    href="<?php echo e(route('fabriccheck.index')); ?>">ตรวจสอบคีย์ผ้าเข้าสต็อก</a></button>
                        </div>
                    </div>
                </div>

                <form method="post" action="<?php echo e(route('fabriccheck.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="imDate">วันที่</label>
                                <input type="text" class="date form-control" name="imDate" autocomplete="off" />

                                <script type="text/javascript">
                                    $(".date").datepicker({
                                        format: "dd/mm/yyyy",
                                        orientation: "bottom",
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fabricStruct">โครงสร้างผ้า </label>
                                <?php if(isset($backdata)): ?>
                                    <input type="text" name="fabricStruct" onkeyup="supplierFunction('fabricStruct')"
                                        class="form-control" id="fabricStruct" placeholder="โครงสร้างผ้า"
                                        value="<?php echo e($backdata->fabricStruct); ?>">
                                <?php else: ?>
                                    <input type="text" name="fabricStruct" onkeyup="supplierFunction('fabricStruct')"
                                        class="form-control" id="fabricStruct" placeholder="โครงสร้างผ้า" value="">
                                <?php endif; ?>

                                <ul id="supp">
                                    <?php $__currentLoopData = $stockFabricStruct; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stockFS): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a
                                                href="javascript:setsupplierFunction('supp', '<?php echo e($stockFS->fabricStruct); ?>' , '<?php echo e($stockFS->fabricPattern); ?>' , '<?php echo e($stockFS->fabricW); ?>');"><?php echo e($stockFS->fabricStruct); ?>

                                                <?php echo e($stockFS->fabricPattern); ?> หน้ากว้าง <?php echo e($stockFS->fabricW); ?> '</a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="line_btn_search">
                        <button type="submit" name="submit" value="searchImport" class="btn_search"><i
                                class="fa fa-search"></i> ค้นหา</button>
                    </div>
                </form>
                <?php if(isset($importorder) && count($importorder) > 0): ?>
                    <div class="row">
                        
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a" style="width:100%">
                                <thead style="position: sticky;top: 0;background-color:powderblue;">
                                    <tr>
                                        <th rowspan="2">วันที่ </th>
                                        <th rowspan="2">ชื่อลูกค้า </th>
                                        <th rowspan="2">รหัสผ้า </th>
                                        <th rowspan="2">โครงสร้างผ้า </th>
                                        <th rowspan="2" style="width: 5rem">ลายผ้า </th>
                                        <th rowspan="2">หน้ากว้าง</th>
                                        <th colspan="2">ผลิตแล้ว </th>
                                        <th rowspan="2">ลบ/แก้ไข</th>
                                        
                                    </tr>
                                    <tr>
                                        <th>จำนวนพับ </th>
                                        <th>จำนวนหลา </th>
                                        
                                    </tr>
                                <tbody style="text-align: right;">
                                    <?php
                                    // $c = $allfabricout->count();
                                    // $a = $sumFabricout->count();
                                    ?>
                                    
                                    <?php $__currentLoopData = $importorder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td> <?php echo e($date = date('d/m/Y', strtotime($order->lastCreateDate))); ?>

                                            </td>
                                            
                                            <td><?php echo e($order->customer); ?></td>
                                            <td><?php echo e($order->fabricId); ?></td>
                                            <td><?php echo e($order->fabricStruct); ?></td>
                                            <td><?php echo e($order->fabricPattern); ?></td>
                                            <td><?php echo e($order->fabricW); ?></td>
                                            <td><?php echo e($order->foldCount); ?></td>
                                            <td><?php echo e($order->sumYardSum); ?></td>
                                            <td>
                                                <form method="POST"
                                                    action="<?php echo e(route('fabriccheck.destroy', $order->refId)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <input type="hidden" value="<?php echo e($order[$i]->refId); ?>">
                                                    <div style="margin: 0.2rem">
                                                        <button class="btn btn-danger" type="button"
                                                            style="margin: 0.2rem"
                                                            onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                        <button class="btn b_order" type="button"
                                                            style="margin: 0.2rem"><a
                                                                href="<?php echo e(route('fabriccheck.edit', $order->refId)); ?>">แก้ไข</a></button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                </thead>
                            </table>
                        </div>
                        

                    </div>
                <?php elseif(isset($importorder) && count($importorder) < 1): ?>
                    <p>ไม่พบผลการค้นหา</p>
                <?php else: ?>
                    <div class="row">
                        
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a" style="width:100%">
                                <thead style="position: sticky;top: 0;background-color:powderblue;">
                                    <tr>
                                        <th rowspan="2">วันที่ </th>
                                        <th rowspan="2">ชื่อลูกค้า </th>
                                        <th rowspan="2">รหัสผ้า </th>
                                        <th rowspan="2">โครงสร้างผ้า </th>
                                        <th rowspan="2" style="width: 6rem">ลายผ้า </th>
                                        <th rowspan="2">หน้ากว้าง</th>
                                        <th colspan="2">ผลิตแล้ว </th>
                                        <th rowspan="2">ลบ/แก้ไข</th>
                                        
                                    </tr>
                                    <tr>
                                        <th>จำนวนพับ </th>
                                        <th>จำนวนหลา </th>
                                        
                                    </tr>
                                <tbody style="text-align: right;">
                                    <?php
                                    $c = $allfabricout->count();
                                    // $a = $sumFabricout->count();
                                    ?>
                                    <?php for($i = 0; $i < $c; $i++): ?>
                                        <tr>
                                            <td> <?php echo e($date = date('d/m/Y', strtotime($allfabricout[$i]->lastCreateDate))); ?>

                                                
                                            </td>
                                            
                                            <td><?php echo e($allfabricout[$i]->customer); ?></td>
                                            <td><?php echo e($allfabricout[$i]->fabricId); ?></td>
                                            <td><?php echo e($allfabricout[$i]->fabricStruct); ?></td>
                                            <td><?php echo e($allfabricout[$i]->fabricPattern); ?></td>
                                            <td><?php echo e($allfabricout[$i]->fabricW); ?></td>
                                            <td><?php echo e($allfabricout[$i]->foldCount); ?></td>
                                            <td><?php echo e($allfabricout[$i]->sumYardSum); ?></td>
                                            <td>
                                                <form method="post"
                                                    action="<?php echo e(route('fabriccheck.destroy', $allfabricout[$i]->refId)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    
                                                    <input type="hidden" value="<?php echo e($allfabricout[$i]->refId); ?>"><br>
                                                    
                                                    <div style="margin: 0.2rem">
                                                        <button class="btn btn-danger" type="button"
                                                            style="margin: 0.2rem"
                                                            onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                        <button class="btn b_order" type="button"
                                                            style="margin: 0.2rem"><a
                                                                href="<?php echo e(route('fabriccheck.edit', $allfabricout[$i]->refId)); ?>">แก้ไข</a></button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                                </thead>
                            </table>


                            

                        </div>
                        

                    </div>
                <?php endif; ?>
            </div>
            <!--box-from-->
        </div>
        <!--content-->
    </div> <!-- /.content-wrapper -->
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
            // alert(input );
            filter = input.value.toUpperCase();
            filter = filter.replace(/\s/g, "");

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
                txtValue = txtValue.replace(/\s/g, "");

                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }


        // function setsupplierFunction(t, t1, t2) {
        function setsupplierFunction(t, t1) {
            document.getElementById("fabricStruct").value = t1;

            // document.getElementById("fabricPattern").value = t2;

            ul = document.getElementById(t);
            //ul.style.display = "none";  
            li = ul.getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

        }

        // function supplierFunction(id) {
        //     var input, filter, ul, li, a, i, txtValue;

        //     input = document.getElementById('orderId');
        //     filter = input.value.toUpperCase();
        //     ul = document.getElementById("supp");
        //     li = ul.getElementsByTagName("li");
        //     for (i = 0; i < li.length; i++) {
        //         if (filter == "") {
        //             for (i = 0; i < li.length; i++) {
        //                 li[i].style.display = "none";
        //             }
        //             break;
        //         }

        //         a = li[i].getElementsByTagName("a")[0];
        //         txtValue = a.textContent || a.innerText;
        //         if (txtValue.toUpperCase().indexOf(filter) > -1) {
        //             li[i].style.display = "";
        //         } else {
        //             li[i].style.display = "none";
        //         }
        //     }
        // }


        function setOrderIdFunction(t, t1, t2, t3, t4) {
            document.getElementById("orderId").value = t1;
            document.getElementById("fabricId").value = t2;
            document.getElementById("fabricStruct").value = t3;
            document.getElementById("refId").value = t4;

            ul = document.getElementById('supp');
            //ul.style.display = "none";  
            li = ul.getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/fabricoutcheck/index.blade.php ENDPATH**/ ?>