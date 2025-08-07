
<?php
//coordinator name use = $orderdata->deadline
//การลงผ้า use = $fabricdata[0]->vat
//sercharge use = $structuredata[0]->yarnWRatio4
?>
<?php $__env->startSection('content'); ?>
    <?php if(Auth::user()->user_type != 'admin' && Auth::user()->user_type != 'supermaterialstaff'): ?>
        <div class="content-wrapper">
            <div class="">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
                    <li class="breadcrumb-item"><a href="#">ระบบซื้อขาย</a></li>
                    <li class="breadcrumb-item active">ใบคำสั่งซื้อ</li>
                </ol>
            </div>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ใบคำสั่งซื้อ</h1>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <div class="content">
                <div class="box-from">
                    <h2 class="title"><i class="fa fa-caret-right"></i> ขออภัยท่านไม่สามารถใช้งานส่วนนี้ได้</h2>
                    <p>กรุณาติดต่อ หัวหน้างานของท่านเพื่อเพิ่มสิทธิ์การใช้งาน</p>
                    <a href="/home">กลับหน้าหลัก</a>
                </div>
                <!--box-from-->
            </div>
            <!--content-->

        </div>
    <?php else: ?>
        <div class="content-wrapper">
            <div class="">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
                    <li class="breadcrumb-item"><a href="#">ระบบซื้อขาย</a></li>
                    <li class="breadcrumb-item active">รายละเอียดการสั่งขาย</li>
                </ol>
            </div>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ใบสั่งขาย</h1>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <div class="content">
                <div class="box-from">
                    <h2 class="title"><i class="fa fa-caret-right"></i> รายละเอียดใบสั่งขาย</h2>
                    <div class="date_order"><strong>สร้างเมื่อ
                        </strong><?php echo e($orderdata->createDate); ?><span><strong> </strong> </span></div>


                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">

                            <p><b>ชื่อลูกค้า :</b> <?php echo e($orderdata->customerName); ?>

                                <?php if($orderdata->customerName != $orderdata->deadline): ?>
                                    <b>ผู้ประสานงาน :</b> <?php echo e($orderdata->deadline); ?>

                                <?php endif; ?>

                            </p>
                        </div>
                        <div class="col-md-5">

                            <p><b> ขายโดย :</b> <?php echo e($orderdata->emp); ?> </p>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">

                            <p><b>รหัสผ้า :</b> <?php echo e($orderdata->fabricId); ?> </p>
                            <p> <b>ลายผ้า :</b> <?php echo e($orderdata->fabricPattern); ?> </p>

                        </div>
                        <div class="col-md-5">

                            <p> <b>โครงสร้างผ้า :</b>
                                <?php echo e(// $orderdata->fabricStructure
                                    str_replace('undefined', '', $orderdata->fabricStructure)); ?>

                            </p>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">

                            <p><b>จำนวนด้ายยืน :</b> <?php echo e($fabricdata[0]->yarn_h_count); ?> เส้น</p>
                            <p> <b>หน้าผ้ากว้าง :</b> <?php echo e($fabricdata[0]->fabric_w); ?> นิ้ว</p>

                        </div>
                        <div class="col-md-5">

                            <!-- <p> <b>โครงสร้างผ้า :</b> <?php echo e($orderdata->fabricStructure); ?> </p> -->

                        </div>
                    </div>

                    <div class="row">
                        <br>
                    </div>


                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p> <b>ชนิดด้ายยืน 1 : </b><?php echo e($structuredata[0]->yarnHType1); ?> </p>
                        </div>
                        <div class="col-md-2">
                            <p> <b>บริษัท :</b> <?php echo e($structuredata[0]->subNameH1); ?></p>
                        </div>
                        <div class="col-md-2">
                            <p><b> ด้ายยืน 1 :</b> <?php echo e($structuredata[0]->yarnHCount1); ?> <b>เส้น</b></p>
                        </div>
                        <?php if($structuredata[0]->yarnHRatio1 == 'no data'): ?>
                            <div class="col-md-2"></div>
                        <?php else: ?>
                            <div class="col-md-2">
                                <p> <b>อัตราส่วน :</b> <?php echo e($structuredata[0]->yarnHRatio1); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-2"></div>
                    </div>
                    <!--row-->
                    
                    <?php if(
                        $structuredata[0]->yarnHType2 == 'no data' &&
                            $structuredata[0]->subNameH2 == 'no data' &&
                            $structuredata[0]->yarnHCount2 == 'no data'): ?>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                                <p><b> ชนิดด้ายยืน 2 :</b>
                                    <?php if($structuredata[0]->yarnHType2 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->yarnHType2); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <p><b> บริษัท : </b>
                                    <?php if($structuredata[0]->subNameH2 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->subNameH2); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <p><b> ด้ายยืน 2 :</b>
                                    <?php if($structuredata[0]->yarnHCount2 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->yarnHCount2); ?> <b>เส้น</b>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <!--row-->
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p><b> ชนิดด้ายพุ่ง 1 : </b>
                                <?php if($structuredata[0]->yarnWType1 != 'no data'): ?>
                                    <?php echo e($structuredata[0]->yarnWType1); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p> <b>บริษัท : </b>
                                <?php if($structuredata[0]->subNameW1 != 'no data'): ?>
                                    <?php echo e($structuredata[0]->subNameW1); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p><b> ด้ายพุ่ง 1 : </b>
                                <?php if($structuredata[0]->yarnWCount1 != 'no data'): ?>
                                    <?php echo e($structuredata[0]->yarnWCount1); ?>

                                    <b>เส้น</b>
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php if($structuredata[0]->yarnWRatio1 == 'no data'): ?>
                            <div class="col-md-2"></div>
                        <?php else: ?>
                            <div class="col-md-2">
                                <p> <b>อัตราส่วน :</b> <?php echo e($structuredata[0]->yarnWRatio1); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-2"></div>
                    </div>
                    <!--row-->
                    <?php if(
                        $structuredata[0]->yarnWType2 == 'no data' &&
                            $structuredata[0]->subNameW2 == 'no data' &&
                            $structuredata[0]->yarnWCount2 == 'no data'): ?>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                                <p><b> ชนิดด้ายพุ่ง 2 : </b>
                                    <?php if($structuredata[0]->yarnWType2 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->yarnWType2); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <p><b> บริษัท : </b>
                                    <?php if($structuredata[0]->subNameW2 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->subNameW2); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <p> <b>ด้ายพุ่ง 2 :</b>
                                    <?php if($structuredata[0]->yarnWCount2 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->yarnWCount2); ?> <b>เส้น</b>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <!--row-->
                    <?php endif; ?>
                    <?php if(
                        $structuredata[0]->yarnWType3 == 'no data' &&
                            $structuredata[0]->subNameW3 == 'no data' &&
                            $structuredata[0]->yarnWCount3 == 'no data'): ?>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                                <p> <b>ชนิดด้ายพุ่ง 3 : </b>
                                    <?php if($structuredata[0]->yarnWType3 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->yarnWType3); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <p> <b>บริษัท :</b>
                                    <?php if($structuredata[0]->subNameW3 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->subNameW3); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <p> <b>ด้ายพุ่ง 3 : </b>
                                    <?php if($structuredata[0]->yarnWCount3 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->yarnWCount3); ?>

                                        <b>เส้น</b>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <!--row-->
                    <?php endif; ?>

                    <?php if(
                        $structuredata[0]->yarnWType4 == 'no data' &&
                            $structuredata[0]->subNameW4 == 'no data' &&
                            $structuredata[0]->yarnWCount4 == 'no data'): ?>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                                <p><b> ชนิดด้ายพุ่ง 4 : </b>
                                    <?php if($structuredata[0]->yarnWType4 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->yarnWType4); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <p><b> บริษัท : </b>
                                    <?php if($structuredata[0]->subNameW4 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->subNameW4); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <p> <b>ด้ายพุ่ง 4 : </b>
                                    <?php if($structuredata[0]->yarnWCount4 != 'no data'): ?>
                                        <?php echo e($structuredata[0]->yarnWCount4); ?>

                                        <b>เส้น</b>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <!--row-->
                    <?php endif; ?>

                    <div class="row">
                        <br>
                    </div>
                    <!--row-->

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <p> <b>เบอร์หวี :</b> <?php echo e($fabricdata[0]->phewNumber); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><b> หน้าหวี :</b> <?php echo e($fabricdata[0]->phewW); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><b> การลงผ้า :</b> <?php echo e($fabricdata[0]->vat); ?></p>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    <!--row-->
                    <!--end Structure -->


                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">
                            <p><b>จำนวนออเดอร์ :</b> <?php echo e(number_format($orderdata->orderSumYard)); ?> <b>หลา :</b>
                                <?php echo e(number_format($orderdata->orderSumM)); ?> <b>เมตร</b></p>
                        </div>
                        <div class="col-md-5">
                            <p><b>การสืบ :</b> <?php echo e($orderdata->fabricSPY); ?> <b>%</b>
                                <?php echo e($orderdata->fabricSPY * ($orderdata->orderSumYard / 100) + $orderdata->orderSumYard); ?>

                                <b>หลา</b>
                                <?php echo e($orderdata->fabricSPY * ($orderdata->orderSumM / 100) + $orderdata->orderSumM); ?>

                                <b>เมตร</b>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">
                            <p><b>ราคาต่อหน่วย หลาละ :</b><?php echo e($orderdata->priceYard); ?> <b>บาท เมตรละ :</b>
                                <?php echo e($orderdata->priceM); ?> <b>บาท</b></p>
                        </div>
                        <div class="col-md-5">
                            <p><b>ส่วนลด :</b> <?php echo e($orderdata->discountP); ?> % <?php echo e($orderdata->discountYard); ?> บาท</p>
                            <p><b>คอมมิชชั่น :</b> <?php echo e($orderdata->commission); ?></p>
                            <p><b>SURCHARGE :</b> <?php echo e($structuredata[0]->yarnWRatio4); ?></p>
                        </div>
                    </div>


                    <div class="row">
                        
                        <div class="col-md-2">
                        </div>
                        <p><b>เลขที่ใบสั่งซื้อ :</b> <?php echo e($orderdata->purchaseOrder); ?></p>
                        <div class="col-md-5">
                            <p><b>PO ลูกค้า :</b>
                                <?php if($orderdata->po != 'no data'): ?>
                                    <?php echo e($orderdata->po); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-8 table-responsive">

                            <table class="table table-striped" id="dynamicAddRemove">

                                <thead>
                                    <tr>
                                        <th>กำหนดส่งครั้งที่</th>
                                        <th>วันที่ *</th>
                                        <th>จำนวน (หลา) *</th>
                                        <th> % *</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $orderdeadline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $temp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($temp->round); ?></td>
                                            
                                            <td>
                                                <?php
                                                $string = $temp->dt;
                                                // $string = '2023/01/19';
                                                // Find the position of "/"
                                                $position = strpos($string, '/');
                                                if ($position === 2) {
                                                    // Extract substring "aa/bb/dddd"
                                                    // Reverse the order "bb/aa/dddd"
                                                    $components = explode('/', $string);
                                                    $result = $components[1] . '/' . $components[0] . '/' . $components[2];
                                                    echo $string;
                                                } elseif ($position === 4) {
                                                    // Reverse the order "dddd/bb/aa"
                                                    $components = explode('/', $string);
                                                    $result = implode('/', array_reverse($components));
                                                    echo $result;
                                                } else {
                                                    // Format date using date function
                                                    $result = date('d/m/Y', strtotime($temp->dt));
                                                    echo $result;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo e($temp->ordery); ?>หลา</td>
                                            <td><?php echo e($temp->orderp); ?>%</td>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p><b>หมายเหตุ</b> <?php echo e($orderdata->comment); ?></p>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p><b>เงื่อนไขการชำระเงิน</b> <?php echo e($fabricdata[0]->payment); ?></p>
                        </div>
                    </div>
                    <!--row-->
                </div>
                <!--box-from-->
                <div class="purchase_order_signature">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-6">
                            <form action="<?php echo e(route('order.store')); ?>" method="post" target="_blank">
                                <?php echo csrf_field(); ?>
                                <button type="button" class="btn b_order" name="submit" value="index"><a
                                        href="<?php echo e(route('order.index')); ?>">กลับหน้ารายการ</a></button>
                                <button type="button" class="btn b_order" name="submit" value="new"><a
                                        href="<?php echo e(route('order.edit', $orderdata->id)); ?>">แก้ไขใบสั่งขาย</a></button>
                                
                            </form>
                        </div>
                    </div>
                    <!--row-->
                </div>
                <!--purchase_order_signature-->
                <div class="purchase_order_signature">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-6">
                            <form method="post" action="<?php echo e(route('order.store')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" id="" name="id" value="<?php echo e($orderdata->id); ?>">

                                <button name="submit" class="btn btn-secondary"
                                    value="purchaseorderdetail">ใบสั่งขาย</button>
                                <button name="submit" class="btn btn-secondary"
                                    value="productionderdetail">ใบโครงสร้าง</button>
                                
                            </form>
                        </div>
                    </div>
                    <!--row-->
                </div>
                <!--purchase_order_signature-->
            </div>
            <!--content-->

        </div><!-- /.content-wrapper -->
    <?php endif; ?>


    <script>
        function createFabricStructure() {
            s1 = "";
            s2 = "";

            yarnHType1 = document.getElementById("yarnHType1");
            yarnHCount1 = document.getElementById("yarnHCount1");
            yarnHType2 = document.getElementById("yarnHType2");
            yarnHCount2 = document.getElementById("yarnHCount2");
            yarnWType1 = document.getElementById("yarnWType1");
            yarnWCount1 = document.getElementById("yarnWCount1");
            yarnWType2 = document.getElementById("yarnWType2");
            yarnWCount2 = document.getElementById("yarnWCount2");
            yarnWType3 = document.getElementById("yarnWType3");
            yarnWCount3 = document.getElementById("yarnWCount3");
            yarnWType4 = document.getElementById("yarnWType4");
            yarnWCount4 = document.getElementById("yarnWCount4");
            fabricStruct = '';


            if (yarnHType1.value != "" && yarnHCount1.value != "") {
                fabricStruct = yarnHType1.value + ' / ' + yarnHCount1.value;
                s1 = yarnHType1.value;
                s2 = yarnHCount1.value;

                if (yarnHType2.value != "") {
                    fabricStruct = '(' + yarnHType1.value + ' + ' + yarnHType2.value + ')' + ' / ' + yarnHCount1.value;
                    s1 = '(' + yarnHType1.value + ' + ' + yarnHType2.value + ')';
                    s2 = yarnHCount1.value;

                }

                if (yarnWType1.value != "" && yarnWCount1.value != "") {
                    yw = yarnWType1.value + ' / ' + yarnWCount1.value;
                    s1 = s1 + ' * ';
                    s2 = yarnHCount1.value;

                    if (yarnWType2.value != "" || yarnWType3.value != "" || yarnWType4.value != "") {
                        yw = '(' + yarnWType1.value;
                        if (yarnWType2.value != "") {
                            yw = yw + ' + ' + yarnWType2.value;
                        }
                        if (yarnWType3.value != "") {
                            yw = yw + ' + ' + yarnWType3.value;
                        }
                        if (yarnWType4.value != "") {
                            yw = yw + ' + ' + yarnWType4.value;
                        }
                        s1 = s1 + yw + ' )';
                        s2 = s2 + ' * ' + yarnWCount1.value;

                        yw = yw + ') / ' + yarnWCount1.value;
                    } else {
                        s1 = s1 + yarnWType1.value;
                        s2 = s2 + ' * ' + yarnWCount1.value;
                    }

                    fabricStruct = fabricStruct + ' * ' + yw;

                } else {
                    alert('กรุณากรอก ชนิดด้ายพุ่ง 1 และ จำนวนด้ายพุ่ง 1');
                }

            } else {
                alert('กรุณากรอกข้อมูลชนิดด้ายยืน 1 และ จำนวนด้ายยืน 1');
            }

            document.getElementById("s1").value = s1;
            document.getElementById("s2").value = s2;

            document.getElementById("fabricStructure1").value = s1 + ' / ' + s2;
            //document.getElementById("fabricStructure1").value = fabricStruct;

        }
    </script>
    <script>
        $(function() {
            $('#reservationdate1').datetimepicker();
            $('#reservationdate2').datetimepicker(); // <<<<< add this line 
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/orders/detail.blade.php ENDPATH**/ ?>