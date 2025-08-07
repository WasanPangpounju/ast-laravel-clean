

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
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="/inventory/">คลังสินค้า</a>
                </li>
                <li class="breadcrumb-item active">ออร์เดอร์ลูกค้า</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ออร์เดอร์ลูกค้า</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> ออร์เดอร์ลูกค้า</h2>

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
                <form method="post" action="<?php echo e(route('inventory.store')); ?>" id="myForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="refId" name="refId" value="">
                    <input type="hidden" id="emp" name="emp" value="<?php echo e(Auth::user()->name); ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="customerName">ลูกค้า</label>
                                <?php if(isset($backdata)): ?>
                                    <input type="text" name="customerName" class="form-control" id="customerName"
                                        placeholder="ลูกค้า" value="<?php echo e($backdata->customerName); ?>">
                                <?php else: ?>
                                    <input type="text" name="customerName" class="form-control" id="customerName"
                                        placeholder="ลูกค้า">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="orderId">เลขที่ใบสั่งซื้อ</label>

                                <?php if(isset($backdata)): ?>
                                    <input type="text" name="orderId" class="form-control" id="orderId"
                                        placeholder="เลขที่ใบสั่งซื้อ" value="<?php echo e($backdata->orderId); ?>">
                                <?php else: ?>
                                    <input type="text" name="orderId" onkeyup="supplierFunction('orderId')"
                                        class="form-control" id="orderId" placeholder="เลขที่ใบสั่งซื้อ">
                                <?php endif; ?>

                                <ul id="supp">
                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a
                                                href="javascript:setOrderIdFunction('orderId', '<?php echo e($order->purchaseOrder); ?>' ,'<?php echo e($order->fabricId); ?>' , '<?php echo e($order->fabricStructure); ?>' , '<?php echo e($order->id); ?>');">
                                                <?php echo e($order->purchaseOrder); ?> <?php echo e($order->customerName); ?>

                                                <?php echo e($order->fabricId); ?> : <?php echo e($order->orderSumYard); ?> หลา</a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="createDate">วันที่</label>

                                <input type="text" class="date form-control" name="imDate" autocomplete="off" />

                                <script type="text/javascript">
                                    $(".date").datepicker({
                                        format: "dd/mm/yyyy",
                                        orientation: "bottom",
                                    });
                                </script>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fabricId">รหัสผ้า </label>
                                <?php if(isset($backdata)): ?>
                                    <input type="text" name="fabricId" class="form-control" id="fabricId"
                                        placeholder="รหัสผ้า" value="<?php echo e($backdata->fabricId); ?>">
                                <?php else: ?>
                                    <input type="text" name="fabricId" class="form-control" id="fabricId"
                                        placeholder="รหัสผ้า">
                                <?php endif; ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fabricStruct">โครงสร้างผ้า </label>
                                <?php if(isset($backdata)): ?>
                                    <input type="text" name="fabricStruct" class="form-control" id="fabricStruct"
                                        placeholder="โครงสร้างผ้า" value="<?php echo e($backdata->fabricStruct); ?>">
                                <?php else: ?>
                                    <input type="text" name="fabricStruct" class="form-control" id="fabricStruct"
                                        placeholder="โครงสร้างผ้า">
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <!--row-->
                    <div class="line_btn">
                        <button name="submit" value="cleanForm" class="btn b_order clean"><img
                                src="<?php echo asset('assets/images/xmark-solid.png'); ?>" width="15"> เคลียร์ข้อมูล</button>
                        <button name="submit" value="searchImport" class="btn_search"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>"
                                width="17">
                            ค้นหา</button>
                    </div>

                </form>
                <!--row-->
                <?php if(isset($importorder) && count($importorder) > 0): ?>
                    <div class="List_table">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-a">
                                    <thead style="position: sticky;top: 0">
                                        <tr>
                                            <th rowspan="2">วันที่ </th>
                                            <th rowspan="2">ลูกค้า </th>
                                            <th rowspan="2">รหัสผ้า</th>
                                            <th rowspan="2">โครงสร้างผ้า</th>
                                            <th rowspan="2">ลายผ้า</th>
                                            <th rowspan="2">หน้ากว้าง</th>
                                            <th rowspan="2">จำนวน Order (หลา) </th>
                                            <th colspan="2">จัดส่งแล้ว </th>
                                            <th rowspan="2">คงค้าง</th>
                                            <th rowspan="2">รายละเอียด </th>
                                            <th rowspan="2">จัดส่ง </th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2">หลา </th>
                                            <th rowspan="2">พับ </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php $__currentLoopData = $importorder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withorders1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $check = 0; ?>
                                            
                                            <?php $__currentLoopData = $inventorydata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventorySum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <?php if($withorders1->id == $inventorySum->refId): ?>
                                                    <?php $check = 1; ?>
                                                    <tr>
                                                        <td rowspan=“10”>
                                                            <?php echo e($date = date('d/m/Y', strtotime($withorders1->createDate))); ?>

                                                            
                                                        </td>
                                                        
                                                        <td><?php echo e($withorders1->customerName); ?></td>
                                                        <td><?php echo e($withorders1->fabricId); ?></td>
                                                        <td><?php echo e($withorders1->fabricStructure); ?></td>
                                                        <td><?php echo e($withorders1->fabricPattern); ?></td>
                                                        
                                                        <?php $check = 0; ?>
                                                        <?php for($z = 0; $z < count($fabricoutdata2); $z++): ?>
                                                            <?php if($fabricoutdata2[$z]->purchaseOrder == $withorders1->id): ?>
                                                                <td><?php echo e($fabricoutdata2[$z]->fabric_w); ?>

                                                                    <?php $check = 1; ?>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                        <?php if($check == 0): ?>
                                                            <td></td>
                                                        <?php endif; ?>
                                                        <td><?php echo e($withorders1->orderSumYard); ?></td>

                                                        <?php $check = 0; ?>
                                                        <?php for($j = 0; $j < count($fabricoutdata); $j++): ?>
                                                            <?php if($fabricoutdata[$j]->orderId == $withorders1->id): ?>
                                                                <td><?php echo e($fabricoutdata[$j]->sumYardSum); ?></td>
                                                                <td><?php echo e($fabricoutdata[$j]->foldCount); ?></td>
                                                                <?php $check = 1; ?>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                        <?php if($check == 0): ?>
                                                            <td></td>
                                                            <td></td>
                                                        <?php endif; ?>

                                                        <?php $check = 0; ?>
                                                        <?php for($j = 0; $j < count($fabricoutdata); $j++): ?>
                                                            <?php if($fabricoutdata[$j]->orderId == $withorders1->id): ?>
                                                                <td><?php echo e($withorders1->orderSumYard - $fabricoutdata[$j]->sumYardSum); ?>

                                                                </td>
                                                                <?php $check = 1; ?>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                        <?php if($check == 0): ?>
                                                            <td></td>
                                                        <?php endif; ?>
                                                        <td>
                                                            <a
                                                                href="<?php echo e(route('inventory.show', $withorders1->id)); ?>">รายละเอียด</a>
                                                        </td>
                                                        <td>
                                                            <form method="post" action="<?php echo e(route('fabricout.store')); ?>"
                                                                id="myForm">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="orderId"
                                                                    value="<?php echo e($withorders1->id); ?>">
                                                                <input type="hidden" name="customerName"
                                                                    value="<?php echo e($withorders1->customerName); ?>                                                        ">
                                                                <input type="hidden" name="fabricStruct"
                                                                    value="<?php echo e($withorders1->fabricStructure); ?>">


                                                                <button name="submit" value="generateByOrder"
                                                                    class="btn b_save">
                                                                    
                                                                    จัดส่งสินค้า</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php break; ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($check != 1): ?>
                                            <tr>
                                                <td rowspan=“10”>
                                                    <?php echo e($date = date('d/m/Y', strtotime($withorders1->createDate))); ?>

                                                    
                                                </td>
                                                
                                                <td><?php echo e($withorders1->customerName); ?></td>
                                                <td><?php echo e($withorders1->fabricId); ?></td>
                                                <td><?php echo e($withorders1->fabricStructure); ?></td>
                                                <td><?php echo e($withorders1->fabricPattern); ?></td>
                                                
                                                <?php $check = 0; ?>
                                                <?php for($z = 0; $z < count($fabricoutdata2); $z++): ?>
                                                    <?php if($fabricoutdata2[$z]->purchaseOrder == $withorders1->id): ?>
                                                        <td><?php echo e($fabricoutdata2[$z]->fabric_w); ?>

                                                            <?php $check = 1; ?>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                <?php if($check == 0): ?>
                                                    <td></td>
                                                <?php endif; ?>
                                                <td><?php echo e($withorders1->orderSumYard); ?></td>

                                                <?php $check = 0; ?>
                                                <?php for($z = 0; $z < count($fabricoutdata); $z++): ?>
                                                    <?php if($fabricoutdata[$z]->orderId == $withorders1->id): ?>
                                                        <td><?php echo e($fabricoutdata[$z]->sumYardSum); ?></td>
                                                        <td><?php echo e($fabricoutdata[$z]->foldCount); ?></td>
                                                        <?php $check = 1; ?>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                <?php if($check == 0): ?>
                                                    <td></td>
                                                    <td></td>
                                                <?php endif; ?>

                                                <?php $check = 0; ?>
                                                <?php for($j = 0; $j < count($fabricoutdata); $j++): ?>
                                                    <?php if($fabricoutdata[$j]->orderId == $withorders1->id): ?>
                                                        <td><?php echo e($withorders1->orderSumYard - $fabricoutdata[$j]->sumYardSum); ?>

                                                        </td>
                                                        <?php $check = 1; ?>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                <?php if($check == 0): ?>
                                                    <td></td>
                                                <?php endif; ?>
                                                <td>
                                                    <a
                                                        href="<?php echo e(route('inventory.show', $withorders1->id)); ?>">รายละเอียด</a>
                                                </td>
                                                <td>
                                                    <form method="post" action="<?php echo e(route('fabricout.store')); ?>"
                                                        id="myForm">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="orderId"
                                                            value="<?php echo e($withorders1->id); ?>">
                                                        <input type="hidden" name="customerName"
                                                            value="<?php echo e($withorders1->customerName); ?>                                                        ">
                                                        <input type="hidden" name="fabricStruct"
                                                            value="<?php echo e($withorders1->fabricStructure); ?>">


                                                        <button name="submit" value="generateByOrder"
                                                            class="btn b_save">
                                                            
                                                            จัดส่งสินค้า</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>
                <!--List_table-->
            <?php elseif(isset($importorder) && count($importorder) < 1): ?>
                <p>ไม่พบผลการค้นหา</p>
            <?php else: ?>
                <div class="List_table">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">วันที่ </th>
                                        <th rowspan="2">ลูกค้า </th>
                                        <th rowspan="2">รหัสผ้า</th>
                                        <th rowspan="2">โครงสร้างผ้า</th>
                                        <th rowspan="2">ลายผ้า</th>
                                        <th rowspan="2">หน้ากว้าง</th>
                                        <th rowspan="2">จำนวน Order (หลา) </th>
                                        <th colspan="2">จัดส่งแล้ว </th>
                                        <th rowspan="2">คงค้าง</th>
                                        <th rowspan="2">รายละเอียด </th>
                                        <th rowspan="2">จัดส่ง </th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">หลา </th>
                                        <th rowspan="2">พับ </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withorders1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $check = 0; ?>
                                        
                                        <?php $__currentLoopData = $inventorydata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventorySum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <?php if($withorders1->id == $inventorySum->refId): ?>
                                                <?php $check = 1; ?>
                                                <tr>
                                                    <td rowspan=“10”>
                                                        <?php echo e($date = date('d/m/Y', strtotime($withorders1->createDate))); ?>

                                                        
                                                    </td>
                                                    
                                                    <td><?php echo e($withorders1->customerName); ?></td>
                                                    <td><?php echo e($withorders1->fabricId); ?></td>
                                                    <td><?php echo e($withorders1->fabricStructure); ?></td>
                                                    <td><?php echo e($withorders1->fabricPattern); ?></td>
                                                    
                                                    <?php $check = 0; ?>
                                                    <?php for($z = 0; $z < count($fabricoutdata2); $z++): ?>
                                                        <?php if($fabricoutdata2[$z]->purchaseOrder == $withorders1->id): ?>
                                                            <td><?php echo e($fabricoutdata2[$z]->fabric_w); ?>

                                                                <?php $check = 1; ?>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                    <?php if($check == 0): ?>
                                                        <td></td>
                                                    <?php endif; ?>
                                                    <td><?php echo e($withorders1->orderSumYard); ?></td>

                                                    <?php $check = 0; ?>
                                                    <?php for($j = 0; $j < count($fabricoutdata); $j++): ?>
                                                        <?php if($fabricoutdata[$j]->orderId == $withorders1->id): ?>
                                                            <td><?php echo e($fabricoutdata[$j]->sumYardSum); ?></td>
                                                            <td><?php echo e($fabricoutdata[$j]->foldCount); ?></td>
                                                            <?php $check = 1; ?>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                    <?php if($check == 0): ?>
                                                        <td></td>
                                                        <td></td>
                                                    <?php endif; ?>

                                                    <?php $check = 0; ?>
                                                    <?php for($j = 0; $j < count($fabricoutdata); $j++): ?>
                                                        <?php if($fabricoutdata[$j]->orderId == $withorders1->id): ?>
                                                            <td><?php echo e($withorders1->orderSumYard - $fabricoutdata[$j]->sumYardSum); ?>

                                                            </td>
                                                            <?php $check = 1; ?>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                    <?php if($check == 0): ?>
                                                        <td></td>
                                                    <?php endif; ?>
                                                    <td>
                                                        <a
                                                            href="<?php echo e(route('inventory.show', $withorders1->id)); ?>">รายละเอียด</a>
                                                    </td>
                                                    <td>
                                                        <form method="post" action="<?php echo e(route('fabricout.store')); ?>"
                                                            id="myForm">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="orderId"
                                                                value="<?php echo e($withorders1->id); ?>">
                                                            <input type="hidden" name="customerName"
                                                                value="<?php echo e($withorders1->customerName); ?>                                                        ">
                                                            <input type="hidden" name="fabricStruct"
                                                                value="<?php echo e($withorders1->fabricStructure); ?>">


                                                            <button name="submit" value="generateByOrder"
                                                                class="btn b_save">
                                                                
                                                                จัดส่งสินค้า</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($check != 1): ?>
                                        <tr>
                                            <td rowspan=“10”>
                                                <?php echo e($date = date('d/m/Y', strtotime($withorders1->createDate))); ?>

                                                
                                            </td>
                                            
                                            <td><?php echo e($withorders1->customerName); ?></td>
                                            <td><?php echo e($withorders1->fabricId); ?></td>
                                            <td><?php echo e($withorders1->fabricStructure); ?></td>
                                            <td><?php echo e($withorders1->fabricPattern); ?></td>
                                            
                                            <?php $check = 0; ?>
                                            <?php for($z = 0; $z < count($fabricoutdata2); $z++): ?>
                                                <?php if($fabricoutdata2[$z]->purchaseOrder == $withorders1->id): ?>
                                                    <td><?php echo e($fabricoutdata2[$z]->fabric_w); ?>

                                                        <?php $check = 1; ?>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <?php if($check == 0): ?>
                                                <td></td>
                                            <?php endif; ?>
                                            <td><?php echo e($withorders1->orderSumYard); ?></td>

                                            <?php $check = 0; ?>
                                            <?php for($z = 0; $z < count($fabricoutdata); $z++): ?>
                                                <?php if($fabricoutdata[$z]->orderId == $withorders1->id): ?>
                                                    <td><?php echo e($fabricoutdata[$z]->sumYardSum); ?></td>
                                                    <td><?php echo e($fabricoutdata[$z]->foldCount); ?></td>
                                                    <?php $check = 1; ?>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <?php if($check == 0): ?>
                                                <td></td>
                                                <td></td>
                                            <?php endif; ?>

                                            <?php $check = 0; ?>
                                            <?php for($j = 0; $j < count($fabricoutdata); $j++): ?>
                                                <?php if($fabricoutdata[$j]->orderId == $withorders1->id): ?>
                                                    <td><?php echo e($withorders1->orderSumYard - $fabricoutdata[$j]->sumYardSum); ?>

                                                    </td>
                                                    <?php $check = 1; ?>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <?php if($check == 0): ?>
                                                <td></td>
                                            <?php endif; ?>
                                            <td>
                                                <a
                                                    href="<?php echo e(route('inventory.show', $withorders1->id)); ?>">รายละเอียด</a>
                                            </td>
                                            <td>
                                                <form method="post" action="<?php echo e(route('fabricout.store')); ?>"
                                                    id="myForm">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="orderId"
                                                        value="<?php echo e($withorders1->id); ?>">
                                                    <input type="hidden" name="customerName"
                                                        value="<?php echo e($withorders1->customerName); ?>                                                        ">
                                                    <input type="hidden" name="fabricStruct"
                                                        value="<?php echo e($withorders1->fabricStructure); ?>">


                                                    <button name="submit" value="generateByOrder"
                                                        class="btn b_save">
                                                        
                                                        จัดส่งสินค้า</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--row-->
            </div>
            <!--List_table-->
        <?php endif; ?>
    </div>
    <!--box-from-->
</div>
<!--content-->
</div> <!-- /.content-wrapper -->
<script>
    function yd(valNum) {
        var m = (valNum * 0.9144).toFixed(4);
        document.getElementById('sumM').value = m;
    }

    function ydToM(valNum) {
        var yd = (valNum / 0.9144).toFixed(4);
        document.getElementById('sumYard').value = yd;
    }

    function pToYd(valNum) {
        var orderyd = document.getElementById('orderSumYard').value;
        let ordernum = parseInt(orderyd);
        var sppYd = ((orderyd * (valNum / 100)) + ordernum).toFixed(4);
        document.getElementById('fabricSpy').value = sppYd;
    }

    function ydToP(valNum) {
        var orderp = document.getElementById('orderSumYard').value;
        var sppP = (orderp / valNum).toFixed(4);
        document.getElementById('fabricSPY').value = sppP;
    }

    function priceToM(valNum) {
        var orderp = document.getElementById('orderSumM').value;
        var sumPriceP = orderp * valNum;
        var pyd = (sumPriceP / (orderp * 0.9144)).toFixed(2);

        /*                var orderyd = document.getElementById('orderSumYard').value;
                        var sumPriceYd = orderyd * valNum;
                        var pm = (sumPriceYd / (orderyd / 0.9144)).toFixed(2);
                        */
        document.getElementById('priceM').value = pyd;

    }

    function priceToY(valNum) {
        var orderyd = document.getElementById('orderSumYard').value;
        var sumPriceYd = orderyd * valNum;
        var pm = (sumPriceYd / (orderyd / 0.9144)).toFixed(2);

        /*                var orderp = document.getElementById('orderSumM').value;
                        var sumPriceP = orderp * valNum;
                        var pyd = (sumPriceP / (orderp * 0.9144)).toFixed(2);
                        */
        document.getElementById('priceYard').value = pm;

    }

    function discountToPriceYd(valNum) {
        var pyd = document.getElementById('priceYard').value;
        var discountYd = ((pyd * valNum) / 100).toFixed(2);
        document.getElementById('discountYard').value = discountYd;
    }

    function discountToPriceP(valNum) {
        var pyd = document.getElementById('priceYard').value;
        var discountP = ((valNum * 100) / pyd).toFixed(2);
        document.getElementById('discountP').value = discountP;
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

        input = document.getElementById('orderId');
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

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/inventory/index.blade.php ENDPATH**/ ?>