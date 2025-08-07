

<?php $__env->startSection('content'); ?>

    <head>
        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
            rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    </head>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="#">ระบบซื้อขาย</a></li>
                <li class="breadcrumb-item active">ตรวจสอบใบสั่งขาย</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 Header">
                    <div class="col">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ตรวจสอบรายการใบสั่งขาย</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <div class="">
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
                    <form method="post" action="<?php echo e(route('order.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="customerName">ชื่อบริษัท</label>
                                        <input type="text" name="customerName" onkeyup="myFunction()"
                                            class="form-control" id="myInput" placeholder="ชื่อบริษัท">

                                        <ul id="myUL">
                                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><a href="javascript:setcustomerFunction('<?php echo e($customer->name); ?>');">
                                                        <?php echo e($customer->name); ?>

                                                    </a>

                                                    


                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="yarnHType1">ชนิดด้ายยืน 1 </label>
                                    <input type="text" name="yarnHType1" onkeyup="yarntypeFunction('yarnHType1' , 0)"
                                        class="form-control" id="yarnHType1" placeholder="ชนิดด้ายยืน 1" autocomplete="off">

                                    <ul name="yarnType" id="supp">
                                        <?php $__currentLoopData = $yarnType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><a
                                                    href="javascript:setyarntypeFunction('yarnHType1', '<?php $parts = explode(' ', $yt[0]->yarnType);
                                                    if (count($parts) >= 2) {
                                                        $trimmedText = $parts[0] . ' ' . $parts[1];
                                                        echo $trimmedText;
                                                    }
                                                    ?>');">
                                                    
                                                    <?php $parts = explode(' ', $yt[0]->yarnType);
                                                    if (count($parts) >= 2) {
                                                        $trimmedText = $parts[0] . ' ' . $parts[1];
                                                        echo $trimmedText;
                                                    }
                                                    ?>
                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="yarnWType1">ชนิดด้ายพุ่ง 1</label>
                                    <input type="text" name="yarnWType1" onkeyup="yarntypeFunction('yarnWType1' , 1)"
                                        class="form-control" id="yarnWType1" placeholder="ชนิดด้ายพุง" autocomplete="off">

                                    <ul name="yarnType" id="supp">
                                        <?php $__currentLoopData = $yarnType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><a
                                                    href="javascript:setyarntypeFunction('yarnWType1', '<?php $parts = explode(' ', $yt[0]->yarnType);
                                                    if (count($parts) >= 2) {
                                                        $trimmedText = $parts[0] . ' ' . $parts[1];
                                                        echo $trimmedText;
                                                    }
                                                    ?>');"><?php $parts = explode(' ', $yt[0]->yarnType);
                                                    if (count($parts) >= 2) {
                                                        $trimmedText = $parts[0] . ' ' . $parts[1];
                                                        echo $trimmedText;
                                                    }
                                                    ?></a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>

                                </div>

                            </div>
                            <div class="col-md-4">
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
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">จำนวนด้ายยืน (เส้น)</label>
                                    
                                    <input type="" class="form-control" id="inputEmail3" name="yarn_h_count"
                                        placeholder="จำนวนด้ายยืน (เส้น)">
                                    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">จำนวนด้ายพุ่ง (เส้น)</label>
                                    
                                    <input type="" class="form-control" id="inputEmail3" name="yarnWCount1"
                                        placeholder="จำนวนด้ายพุ่ง (เส้น)">
                                    
                                </div>
                            </div>
                            
                        </div>

                        <div class="line_btn_search">
                            <button type="submit" name="submit" value="searchImport" class="btn_search"><i
                                    class="fa fa-search"></i> ค้นหา</button>
                        </div>
                    </form>
                    <?php if(isset($importorder) && count($importorder) > 0): ?>
                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>วันที่ </th>
                                                <th>รายการ </th>
                                                <th>สถานะ </th>
                                                <th>แก้ไข / ลบ </th>
                                                <th>ใบสั่งขาย/ใบโครงสร้าง</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $importorder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($date = date('d/m/Y', strtotime($order->createDate))); ?></td>
                                                    <td><?php echo e($order->customerName); ?> รหัสผ้า <?php echo e($order->fabricId); ?>

                                                        <?php echo e($order->orderSumYard); ?> หลา
                                                        <br>
                                                        <?php echo e(// $order->fabricStructure
                                                            str_replace('undefined', '', $order->fabricStructure)); ?>

                                                        <a href="<?php echo e(route('order.show', $order->id)); ?>">รายละเอียด</a>
                                                    </td>
                                                    <td>
                                                        <?php if($order->status != 'อนุมัติให้ผลิต'): ?>
                                                            สร้างใบสั่งซื้อ
                                                            <form method="POST"
                                                                action="<?php echo e(route('order.store', $order->id)); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                
                                                                <input type="hidden" name="id"
                                                                    value="<?php echo e($order->id); ?>">
                                                                <input type="hidden" name="status"
                                                                    value="อนุมัติให้ผลิต">
                                                                <input type="hidden" name="Oldsearch"
                                                                    value="<?php echo e($searchInput); ?>">
                                                                <input type="hidden" name="select_search"
                                                                    value="<?php echo e($select_search); ?>">
                                                                <br><button name="submit" class="btn btn-secondary"
                                                                    value="updateStatus">อนุมัติให้ผลิต</button>
                                                            </form>
                                                        <?php else: ?>
                                                            อนุมัติให้ผลิต
                                                            <form method="POST"
                                                                action="<?php echo e(route('order.store', $order->id)); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                
                                                                <input type="hidden" name="id"
                                                                    value="<?php echo e($order->id); ?>">
                                                                <input type="hidden" name="status"
                                                                    value="กลับสร้างใบสั่งซื้อ">
                                                                <input type="hidden" name="Oldsearch"
                                                                    value="<?php echo e($searchInput); ?>">
                                                                <br><button name="submit" class="btn btn-success"
                                                                    value="updateStatus2">กลับสร้างใบสั่งซื้อ</button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <form method="POST"
                                                            action="<?php echo e(route('order.destroy', $order->id)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button class="btn b_order" type="button"
                                                                style="margin-bottom: 1.2em">
                                                                <a
                                                                    href="<?php echo e(route('order.edit', $order->id)); ?>">แก้ไข</a></button>
                                                            <br>
                                                            
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form method="post" action="<?php echo e(route('order.store')); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" id="" name="id"
                                                                value="<?php echo e($order->id); ?>">
                                                            <input type="hidden" name="Oldsearch"
                                                                value="<?php echo e($searchInput); ?>">
                                                            <input type="hidden" name="select_search"
                                                                value="<?php echo e($select_search); ?>">

                                                            <button name="submit" class="btn btn-secondary"
                                                                value="purchaseorderdetail">ใบสั่งขาย</button>
                                                            <br>
                                                            <br>
                                                            <button name="submit" class="btn btn-secondary"
                                                                value="productionderdetail">ใบโครงสร้าง</button>
                                                            
                                                        </form>
                                                        <!-- <a href="">ใบใบสั่งขาย</a>  -->
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
                    <?php elseif(isset($importmaterial) && count($importmaterial) < 1): ?>
                        <p>ไม่พบผลการค้นหา</p>
                    <?php else: ?>
                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>วันที่ </th>
                                                <th>รายการ </th>
                                                <th>สถานะ </th>
                                                <th>แก้ไข / ลบ </th>
                                                <th>ใบสั่งขาย/ใบโครงสร้าง</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $orderlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($date = date('d/m/Y', strtotime($order->createDate))); ?></td>
                                                    <td><?php echo e($order->customerName); ?> รหัสผ้า <?php echo e($order->fabricId); ?>

                                                        <?php echo e($order->orderSumYard); ?> หลา
                                                        <br>
                                                        <?php echo e(//  $order->fabricStructure
                                                            str_replace('undefined', '', $order->fabricStructure)); ?>

                                                        <a href="<?php echo e(route('order.show', $order->id)); ?>">รายละเอียด</a>
                                                    </td>
                                                    <td>
                                                        <?php if($order->status != 'อนุมัติให้ผลิต'): ?>
                                                            สร้างใบสั่งซื้อ
                                                            <form method="POST"
                                                                action="<?php echo e(route('order.store', $order->id)); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                
                                                                <input type="hidden" name="id"
                                                                    value="<?php echo e($order->id); ?>">
                                                                <input type="hidden" name="status"
                                                                    value="อนุมัติให้ผลิต">
                                                                <br><button name="submit" class="btn btn-secondary"
                                                                    value="updateStatus">อนุมัติให้ผลิต</button>
                                                            </form>
                                                        <?php else: ?>
                                                            อนุมัติให้ผลิต
                                                            <form method="POST"
                                                                action="<?php echo e(route('order.store', $order->id)); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                
                                                                <input type="hidden" name="id"
                                                                    value="<?php echo e($order->id); ?>">
                                                                <input type="hidden" name="status"
                                                                    value="กลับสร้างใบสั่งซื้อ">
                                                                <br><button name="submit" class="btn btn-success"
                                                                    value="updateStatus2">กลับสร้างใบสั่งซื้อ</button>
                                                            </form>
                                                        <?php endif; ?>

                                                    </td>
                                                    <td>

                                                        <form method="POST"
                                                            action="<?php echo e(route('order.destroy', $order->id)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button class="btn b_order" type="button"
                                                                style="margin-bottom: 1.2em">
                                                                <a
                                                                    href="<?php echo e(route('order.edit', $order->id)); ?>">แก้ไข</a></button>
                                                            <br>
                                                            
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form method="post" action="<?php echo e(route('order.store')); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" id="" name="id"
                                                                value="<?php echo e($order->id); ?>">

                                                            <button name="submit" class="btn btn-secondary"
                                                                value="purchaseorderdetail">ใบสั่งขาย</button>
                                                            <br>
                                                            <br>
                                                            <button name="submit" class="btn btn-secondary"
                                                                value="productionderdetail">ใบโครงสร้าง</button>
                                                            
                                                        </form>

                                                        <!-- <a href="">ใบใบสั่งขาย</a>  -->


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
                    <?php endif; ?>
                </div><!-- inner_content -->
            </div>
            <!--box-from-->
        </div>
        <!--content-->
    </div><!-- /.content-wrapper -->



    <script>
        var input, filter, ul, li, a, i, txtValue;

        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
        }

        var input, filter, ul, li, a, i, txtValue;

        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
        }

        //hidden supplier list
        sups = document.getElementsByName("supp");
        //alert(sups.length);
        for (i = 0; i < sups.length; i++) {
            li = sups[i].getElementsByTagName("li");
            //alert(li.length);

            for (j = 0; j < li.length; j++) {
                li[j].style.display = "none";
            }


        }

        //hidden yarnType list
        yts = document.getElementsByName("yarnType");
        for (i = 0; i < yts.length; i++) {
            li = yts[i].getElementsByTagName("li");

            for (j = 0; j < li.length; j++) {
                li[j].style.display = "none";
            }

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

        function supplierFunction(id, uln) {
            var input, filter, ul, li, a, i, txtValue;
            //alert(id);
            //alert(uln);

            input = document.getElementById(id);
            filter = input.value.toUpperCase();

            ul = document.getElementsByName("supp");
            //document.getElementById('sup1');
            li = ul[uln].getElementsByTagName("li");
            //alert(li.length);

            for (i = 0; i < li.length; i++) {
                if (filter == "") {
                    for (j = 0; j < li.length; j++) {
                        li[j].style.display = "none";
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

        function yarntypeFunction(id, uln) {
            var input, filter, ul, li, a, i, txtValue;

            input = document.getElementById(id);
            filter = input.value.toUpperCase();
            filter = filter.replace(/\s/g, "");

            ul = document.getElementsByName("yarnType");
            li = ul[uln].getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                if (filter == "") {
                    for (j = 0; j < li.length; j++) {
                        li[j].style.display = "none";
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

        function setcustomerFunction(t) {
            document.getElementById("myInput").value = t;
            //ul = document.getElementById("myUL");
            //ul.style.display = "none";  
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

        }

        function setsupplierFunction(t, t1) {
            //Set supplier to textbox by id
            document.getElementById(t).value = t1;

            //hidden Supplier list
            sups = document.getElementsByName("supp");
            for (i = 0; i < sups.length; i++) {
                li = sups[i].getElementsByTagName("li");

                for (j = 0; j < li.length; j++) {
                    li[j].style.display = "none";
                }
            }

        }

        function setyarntypeFunction(t, t1) {
            //Set supplier to textbox by id
            document.getElementById(t).value = t1;

            //hidden Supplier list
            yts = document.getElementsByName("yarnType");
            for (i = 0; i < yts.length; i++) {
                li = yts[i].getElementsByTagName("li");

                for (j = 0; j < li.length; j++) {
                    li[j].style.display = "none";
                }
            }

        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        var i = 0;
        var j = 1;
        var newId = 'datepicker';
        $("#dynamic-ar").click(function() {
            ++i;
            ++j;
            newId = newId + j;
            // <input type="date" name="dl[0][dt]" id="startDate"
            //                                     class="form-control">
            // $("#dynamicAddRemove").append('<tr><td style="text-align: center">' + j +
            //     '</td><td><input type="text" class="form-control datepicker" id="' + newId + '" name="dl[' +
            //     i +
            //     '][dt]"></td><td><input type="text" name="dl[' +
            //     i +
            //     '][ordery]" placeholder="จำนวน (หลา)" class="form-control" /></td><td><input type="text" name="dl[' +
            //     i +
            //     '][orderp]" placeholder="%" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-input-field">ลบ</button></td></tr>'
            // );
            $("#dynamicAddRemove").append('<tr><td style="text-align: center">' + j +
                '</td><td><input type="date" class="form-control" id="' + newId + '" name="dl[' +
                i +
                '][dt]" placeholder="dd-mm-yyyy"></td><td><input type="text" name="dl[' +
                i +
                '][ordery]" placeholder="จำนวน (หลา)" class="form-control" /></td><td><input type="text" name="dl[' +
                i +
                '][orderp]" placeholder="%" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-input-field">ลบ</button></td></tr>'
            );

            // $('#' + newId).datepicker({
            //     format: 'mm/dd/yyyy',
            //     autoclose: true,
            //     todayHighlight: true
            // });
            $('#' + newId).datepicker({
                format: 'yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('.date').datepicker({
                format: 'dd/mm/yyyy',
                orientation: "bottom",
            });

        });

        // Initialize the new datepicker
        //         $('#' + newId).datepicker({
        //     format: 'yyyy-mm-dd',
        //     autoclose: true,
        //     todayHighlight: true
        // });



        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();
            --i;
            --j;
        });
    </script>

    <script>
        function subyarntype(yt) {
            // var text = "CP60 Compack";
            var text = yt;
            var strArray = text.split(" ");
            text = strArray[0] + ' ' + strArray[1];
            return text;
            // var lastNumPos = null;


        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/orders/index.blade.php ENDPATH**/ ?>