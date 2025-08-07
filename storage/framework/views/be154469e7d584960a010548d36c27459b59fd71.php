<?php
//set value to Surcharge
$surcharge = $fabricStructureData['yarnWRatio4'];
// $sovat = $fabricpaymentEdit['vat'];
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>ASIA TEXTILE CO., LTD.</title>
    <meta name="viewport"
        content="width=device-width,user-scalable=yes,initial-scale=1, maximum-scale=1, minimum-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo asset('assets/css/adminlte.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('assets/css/stylesheet.css'); ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/bootstrap-colorpicker.min.css'); ?>">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/tempusdominus-bootstrap-4.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/fonts/fontawesome-free/css/all.min.css'); ?>">
    <link rel="icon" href="<?php echo asset('assets/images/favicon.png'); ?>" type="image" sizes="16x16">

</head>
<style>
    /* #bath {
        text-align: right;
        color: blue;
    } */
</style>

<body class="hold-transition sidebar-mini">

    <div class="content">
        <div class="inner_content">
            
            <div class="address-company" style="text-align: center;margin-top:2.5em;">
                <h2>ใบโครงสร้าง</h2>
            </div>
            <div class="purchase_order_info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>ชื่อลูกค้า </strong>
                            </div>
                            <div class="col-md-6">
                                <?php echo e($showproduction->customerName); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>No. </strong>
                            </div>
                            <div class="col-md-6">
                                <?php echo e($showproduction->no); ?>

                            </div>
                        </div>

                        <?php
                        //Get Customer data
                        $customerData = App\Http\Controllers\OrderController::customerData($showproduction->customerName);
                        ?>

                        

                        
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>เลขที่ใบโครงสร้าง</strong>
                            </div>
                            <div class="col-md-6">
                                <?php echo e($showproduction->purchaseOrder); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong> วันที่ </strong>
                            </div>
                            <div class="col-md-6">
                                <?php echo date('d/m/Y', strtotime($showproduction->createDate)); ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!--row-->
            </div>
            <div class="purchase_order_info">
                <div class="detail" style="font-size: 1.05rem">
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-5 table-responsive">
                            
                            

                            
                            
                            <b>โครงสร้างผ้า : </b>
                            <?php if($showproduction->yarnHRatio1 != 'no data' || $showproduction->yarnWRatio1 != 'no data'): ?>
                                <div class="row">
                                    <div class="col-5" style="display: flex; justify-content: center;">
                                        
                                        <?php if($showproduction->yarnHRatio1 != 'no data'): ?>
                                            <?php echo e($showproduction->yarnHRatio1); ?>

                                        <?php endif; ?>
                                        
                                    </div>
                                    <div class="col-1"></div>
                                    <div class="col-5" style="display: flex; justify-content: center;">

                                        <?php if($showproduction->yarnWRatio1 != 'no data'): ?>
                                            <?php echo e($showproduction->yarnWRatio1); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row" style="border-bottom: 1px solid black">
                                <div class="col-5" style="display: flex; justify-content: center;">
                                    
                                    <?php
                                    $parts = explode('*', $showproduction->s1);
                                    if (count($parts) > 0) {
                                        $textBeforeAsterisk = trim($parts[0]);
                                        // echo $textBeforeAsterisk;
                                        echo str_replace('undefined', '', $textBeforeAsterisk);
                                        // echo trim($textBeforeAsterisk, 'undefined');
                                    }
                                    ?>
                                    
                                </div>
                                <div class="col-1">x</div>
                                <div class="col-5" style="display: flex; justify-content: center;">

                                    <?php
                                    $parts = explode('*', $showproduction->s1);
                                    if (count($parts) > 1) {
                                        $textAfterAsterisk = trim($parts[1]);
                                        // echo $textAfterAsterisk;
                                        echo str_replace('undefined', '', $textAfterAsterisk);
                                        // echo trim($textAfterAsterisk, 'undefined');
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-5" style="display: flex; justify-content: center;">
                                    
                                    <?php
                                    $parts = explode('*', $showproduction->s2);
                                    
                                    if (count($parts) > 0) {
                                        $textBeforeAsterisk = trim($parts[0]);
                                        echo $textBeforeAsterisk;
                                    }
                                    ?>
                                    
                                </div>
                                <div class="col-1">x</div>
                                <div class="col-5" style="display: flex; justify-content: center;">

                                    <?php
                                    $parts = explode('*', $showproduction->s2);
                                    if (count($parts) > 1) {
                                        $textAfterAsterisk = trim($parts[1]);
                                        echo $textAfterAsterisk;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 table-responsive">
                            <?php echo e($showproduction->fabric_w); ?> <br><br>

                        </div>
                        <div class="col-2 table-responsive">
                            <b> ลายผ้า </b>
                            
                            <?php echo e($showproduction->fabricPattern); ?>

                            <br><br>
                            <b>รหัสผ้า </b> <?php echo e($showproduction->fabricId); ?><br><br>
                        </div>
                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-5 table-responsive">
                            <b>จำนวนด้ายยืน : </b> <?php echo e($showproduction->yarn_h_count); ?><br><br>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-10 table-responsive">
                            <b>ชนิดด้ายยืน : </b> <?php echo e($showproduction->yarnHType1); ?> <?php $subNameH1 = str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameH1); ?>
                            <?php echo e($subNameH1); ?>

                            <?php
                            if ($showproduction->subNameH2 != null) {
                                echo $showproduction->yarnHType2 . ',' . str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameH2);
                            }
                            ?>
                            <br><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-10 table-responsive">
                            <b>ชนิดด้ายพุ่ง : </b> <?php echo e($showproduction->yarnWType1); ?> <?php $subNameW1 = str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameW1); ?>
                            <?php echo e($subNameW1); ?>

                            <?php
                            if ($showproduction->subNameW2 != null) {
                                echo '+ ' . $showproduction->yarnWType2 . ' ' . str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameW2);
                            }
                            ?>
                            <br><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-4 table-responsive">
                            <b>จำนวนด้ายพุ่ง : </b> <?php echo e($showproduction->yarnWCount1); ?><br><br>

                        </div>
                        <div class="col-4 table-responsive">
                            <b>หน้าผ้ากว้าง : </b> <?php echo e($showproduction->fabric_w); ?> นิ้ว<br><br>

                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-4 table-responsive">
                            <b>ฟันหวีเบอร์ : </b> <?php echo e($showproduction->phewNumber); ?><br><br>

                        </div>
                        <div class="col-4 table-responsive">
                            <b>หน้าหวีกว้าง : </b> <?php echo e($showproduction->phewW); ?> นิ้ว<br><br>

                        </div>
                        
                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-4 table-responsive">
                            
                            
                            <b>พ่นสีลงผ้า : </b> <?php echo e($showproduction->vat); ?><br><br>


                        </div>

                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-5 table-responsive">
                            <?php if($showproduction->typrtag == 'm'): ?>
                                
                                <b>ออร์เดอร์สืบ :
                                </b><?php echo e($showproduction->fabricSPY * ($showproduction->orderSumM / 100) + $showproduction->orderSumM); ?>

                                เมตร<br><br>
                            <?php else: ?>
                                <b>ออร์เดอร์สืบ :
                                </b><?php echo e($showproduction->fabricSPY * ($showproduction->orderSumYard / 100) + $showproduction->orderSumYard); ?>

                                หลา<br><br>
                            <?php endif; ?>
                        </div>
                        <div class="col-5 table-responsive">
                            <b>กำหนดส่ง : </b> <br> <br>
                        </div>
                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-5 table-responsive">
                            <b>ชนิดเครื่องทอ : </b> <?php echo e($showproduction->typemachine); ?><br> <br>
                        </div>
                        <div class="col-5 table-responsive">
                            <b>เบอร์เครื่อง : </b> <?php echo e($showproduction->machinenumber); ?><br> <br>
                        </div>
                    </div>
                    <!--row-->
                </div>
            </div>
            <div class="row">
                <div class="col-12 table-responsive">

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
            <div class="purchase_order_info">
                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>หมายเหตุ</strong>
                            </div>
                            <div class="col-md-10">
                                <?php if($showproduction->comment != 'no data'): ?>
                                    <?php echo e($showproduction->comment2); ?>

                                <?php endif; ?>
                                <?php if($showproduction->vat == 'SOX'): ?>
                                    <strong> ราคานี้รวม VAT แล้ว</strong><br>
                                <?php endif; ?>
                                <?php if($showproduction->po != 'no data'): ?>
                                    <?php echo e($showproduction->po); ?>

                                <?php endif; ?>
                                <?php if($showproduction->typrtag == 'm'): ?>
                                    <b>ออร์เดอร์ :
                                    </b><?php echo e($showproduction->orderSumM); ?>

                                    เมตร<br><br>
                                <?php else: ?>
                                    <b>ออร์เดอร์ :
                                    </b><?php echo e($showproduction->orderSumYard); ?>

                                    หลา<br><br>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
                <!--row-->
            </div>
            <!--purchase_order_info-->
            <div class="purchase_order_signature">
                <div class="row">
                    <div class="col-md-6 mt-5">
                        <div class="box_signature">
                            <div class="inner_signature">
                                
                            </div>
                            <p><strong>ผู้สั่งงาน</strong></p>
                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <div class="box_signature">
                            <div class="inner_signature">
                                
                            </div>
                            <p><strong>ผู้ตรวจสอบ </strong></p>
                        </div>
                    </div>
                </div>
                <!--row-->
            </div>
            <!--purchase_order_signature-->
            <div class="purchase_order_signature">
                <div class="row">
                    <div class="col-md-1">
                        
                            
                            
                            
                        
                    </div>

                    
                    <div class="col-md-2" style="margin-left:7em;">
                        <form method="POST" action="<?php echo e(route('order.store', $showproduction->refId)); ?>">
                            <?php echo csrf_field(); ?>
                            
                            
                            <input type="hidden" name="id" value="<?php echo e($showproduction->refId); ?>">
                            <button name="submit" class="btn btn-secondary" value="editproduction">แก้ไข</button>

                            
                        </form>
                    </div>
                    <div class="col-md-2" style="margin-left:5em;">
                        <form method="post" action="<?php echo e(route('order.store')); ?>" target="_blank">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="" name="id"
                                value="<?php echo e($showproduction->refId); ?>">
                            <button id="pdf-btn" name="submit" class="btn btn-secondary"
                                value="productionderdetailPdf">พิมพ์ใบโครงสร้าง</button>

                        </form>
                    </div>





                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-left:17rem;margin-top:1rem;">

                        <?php if(isset($oldSearch) && isset($selectSearch)): ?>
                            <form method="post" action="<?php echo e(route('order.store')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="Oldsearch" value="<?php echo e($oldSearch); ?>">
                                <input type="hidden" name="select_search" value="<?php echo e($selectSearch); ?>">
                                <button id="pdf-btn" name="submit" class="btn b_order"
                                    value="searchImport">กลับ</button>

                            </form>
                        <?php else: ?>
                            <button onclick="goBack()" class="btn b_order">กลับ</button>
                            <script>
                                function goBack() {
                                    window.history.back();
                                }
                            </script>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!--row-->
        </div>
    </div>
    <!--inner_content-->
    </div>
    <!--content-->

</body>

</html>
<?php /**PATH /var/www/html/ast-menufacturing/resources/views/orders/showproduction.blade.php ENDPATH**/ ?>