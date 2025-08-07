

<?php $__env->startSection('content'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
                <li class="breadcrumb-item active">สต๊อกวัตถุดิบคงเหลือ</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 Header">
                    <div class="col">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> สต๊อกวัตถุดิบ</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <div class="">

                    <form method="post" action="<?php echo e(route('materialstock.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="yarntype">ชนิดด้าย</label>
                                    <select name="yarnType" class="form-control">
                                        <option disabled selected value="">เลือกชนิดได้</option>
                                        <?php $__currentLoopData = $stockYarns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $stockYarn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>"><?php echo e($key); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="submit" name="submit" value="search" id="search" class="btn btn-etc"
                                        style="margin-top:2em;"> <i class="fas fa-folder"></i> ค้นหา</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php if(isset($sum_import_yarnType) && isset($sum_withdraw_yarnType)): ?>
                        <!--display data by search yarnType -->


                        <h2><?php echo e($yarnType); ?> คงเหลือในสต๊อก </h2>
<p>รายการล่าสุดเมื่อ <?php echo e($stockLatest); ?></p>

                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-a">
                                        <thead style="position: sticky;top: 0">
                                            <tr>
                                                <th rowspan="2">บริษัท</th>
                                                <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                                <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                                <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                            </tr>
                                            <tr>
                                                <th>ปอนด์</th>
                                                <th>กิโลกรัม</th>
                                                <th>ปอนด์</th>
                                                <th>กิโลกรัม</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <!-- //loop display supplier import yarn data -->
                                            <?php $__currentLoopData = $sum_import_yarnType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <?php $check = 0; ?>
                                                <!-- //Check withdraw material-->
                                                <?php $__currentLoopData = $sum_withdraw_yarnType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    
                                                    
                                                    <?php if($item->supplierName === $item1->supplierName): ?>
                                                        <?php
                                                        $check = 1;
                                                        $tmp_spool = $item->sumspool - $item1->sumspool;
                                                        $tmp_sumweight_p_net = number_format($tmp_spool * $item->average_p, 4);
                                                        $tmp_sumweight_kg_net = number_format($tmp_spool * $item->average_kg, 4);
                                                        ?>
                                                        <?php $__currentLoopData = $sum_without_yarnType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($item->supplierName === $item2->supplierName): ?>
                                                                <?php
                                                                $tmp_spool = $tmp_spool - $item2->sumspool;
                                                                $tmp_sumweight_p_net = number_format($tmp_spool * $item->average_p, 4);
                                                                $tmp_sumweight_kg_net = number_format($tmp_spool * $item->average_kg, 4);
                                                                ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($item->supplierName); ?></td>
                                                            <!--ชื่อบริษัท-->
                                                            <td><?php echo e($tmp_spool); ?></td>
                                                            <!--จำนวนด้ายรวม-->
                                                            <td><?php echo e($tmp_sumweight_p_net); ?> </td>
                                                            <!--น้ำหนักรวม p -->
                                                            <td><?php echo e($tmp_sumweight_kg_net); ?></td>
                                                            <!--น้ำหนักรวม kg-->
                                                            <td><?php echo e(number_format($item->average_p, 4)); ?> </td>
                                                            <!--น้ำหนักรวมเฉลี่ย p -->
                                                            <td><?php echo e(number_format($item->average_kg, 4)); ?> </td>
                                                            <!--น้ำหนักรวมเฉลี่ย kg-->
                                                        </tr>
                                                    <?php break; ?>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($check != 1): ?>
                                                <tr>
                                                    <td><?php echo e($item->supplierName); ?></td>
                                                    <!--ชื่อบริษัท-->
                                                    <td><?php echo e($item->sumspool); ?></td>
                                                    <!--จำนวนด้ายรวม-->
                                                    <td><?php echo e(number_format($item->sumweight_p_net, 4)); ?> </td>
                                                    <!--น้ำหนักรวม p -->
                                                    <td><?php echo e(number_format($item->sumweight_kg_net, 4)); ?></td>
                                                    <!--น้ำหนักรวม kg-->
                                                    <td><?php echo e(number_format($item->average_p, 4)); ?> </td>
                                                    <!--น้ำหนักรวมเฉลี่ย p -->
                                                    <td><?php echo e(number_format($item->average_kg, 4)); ?> </td>
                                                    <!--น้ำหนักรวมเฉลี่ย kg-->
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--row-->
                    </div>


                    <h2>นำเข้าวัตถุดิบ <?php echo e($yarnType); ?></h2>

                    <div class="List_table">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-a">
                                    <thead style="position: sticky;top: 0">
                                        <tr>
                                            <th rowspan="2">บริษัท</th>
                                            <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                            <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                            <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                        </tr>
                                        <tr>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- //loop display supplier import yarn data -->
                                        <?php $__currentLoopData = $sum_import_yarnType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($item->supplierName); ?></td>
                                                <!--ชื่อบริษัท-->
                                                <td><?php echo e($item->sumspool); ?></td>
                                                <!--จำนวนด้ายรวม-->
                                                <td><?php echo e(number_format($item->sumweight_p_net, 4)); ?> </td>
                                                <!--น้ำหนักรวม p -->
                                                <td><?php echo e(number_format($item->sumweight_kg_net, 4)); ?></td>
                                                <!--น้ำหนักรวม kg-->
                                                <td><?php echo e(number_format($item->average_p, 4)); ?> </td>
                                                <!--น้ำหนักรวมเฉลี่ย p -->
                                                <td><?php echo e(number_format($item->average_kg, 4)); ?> </td>
                                                <!--น้ำหนักรวมเฉลี่ย kg-->
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--row-->
                    </div>

                    <h2>เบิกวัตถุดิบ <?php echo e($yarnType); ?> ใช้ภายใน</h2>

                    <div class="List_table">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-a">
                                    <thead style="position: sticky;top: 0">
                                        <tr>
                                            <th rowspan="2">บริษัท</th>
                                            <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                            <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                            <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                        </tr>
                                        <tr>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- //loop display supplier withdraw yarn data -->
                                        <?php $__currentLoopData = $sum_withdraw_yarnType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($item->supplierName); ?></td>
                                                <!--ชื่อบริษัท-->
                                                <td><?php echo e($item->sumspool); ?></td>
                                                <!--จำนวนด้ายรวม-->
                                                <td><?php echo e(number_format($item->sumweight_p_net, 4)); ?> </td>
                                                <!--น้ำหนักรวม p -->
                                                <td><?php echo e(number_format($item->sumweight_kg_net, 4)); ?></td>
                                                <!--น้ำหนักรวม kg-->
                                                <td><?php echo e(number_format($item->average_p, 4)); ?> </td>
                                                <!--น้ำหนักรวมเฉลี่ย p -->
                                                <td><?php echo e(number_format($item->average_kg, 4)); ?> </td>
                                                <!--น้ำหนักรวมเฉลี่ย kg-->
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--row-->
                    </div>

                    <h2>เบิกวัตถุดิบ <?php echo e($yarnType); ?> ใช้ภายนอก</h2>

                    <div class="List_table">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-a">
                                    <thead style="position: sticky;top: 0">
                                        <tr>
                                            <th rowspan="2">บริษัท</th>
                                            <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                            <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                            <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                        </tr>
                                        <tr>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- //loop display supplier withdraw yarn data -->
                                        <?php $__currentLoopData = $sum_without_yarnType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($item->supplierName); ?></td>
                                                <!--ชื่อบริษัท-->
                                                <td><?php echo e($item->sumspool); ?></td>
                                                <!--จำนวนด้ายรวม-->
                                                <td><?php echo e(number_format($item->sumweight_p_net, 4)); ?> </td>
                                                <!--น้ำหนักรวม p -->
                                                <td><?php echo e(number_format($item->sumweight_kg_net, 4)); ?></td>
                                                <!--น้ำหนักรวม kg-->
                                                <td><?php echo e(number_format($item->average_p, 4)); ?> </td>
                                                <!--น้ำหนักรวมเฉลี่ย p -->
                                                <td><?php echo e(number_format($item->average_kg, 4)); ?> </td>
                                                <!--น้ำหนักรวมเฉลี่ย kg-->
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--row-->
                    </div>
                <?php elseif(isset($sum_import_supplier) && isset($sum_withdraw_supplier)): ?>
                    <!--display data by search yarnType -->
                    <h2><?php echo e($supplierName); ?> คงเหลือในสต๊อก </h2>
                    <p>รายการล่าสุดเมื่อ <?php echo e($stockLatest); ?></p>

                    <div class="List_table">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-a">
                                    <thead style="position: sticky;top: 0">
                                        <tr>
                                            <th rowspan="2">ชนิดด้าย</th>
                                            <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                            <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                            <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                        </tr>
                                        <tr>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- //loop display supplier import yarn data -->
                                        <?php $__currentLoopData = $sum_import_supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <!-- //Check withdraw material-->
                                            <?php $check = 0; ?>
                                            <?php $__currentLoopData = $sum_withdraw_supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                
                                                

                                                <?php if($item->yarnType === $item1->yarnType): ?>
                                                    <?php
                                                    $check = 1;
                                                    $tmp_spool = $item->sumspool - $item1->sumspool;
                                                    $tmp_sumweight_p_net = number_format($tmp_spool * $item->average_p, 4);
                                                    $tmp_sumweight_kg_net = number_format($tmp_spool * $item->average_kg, 4);
                                                    ?>
                                                    <?php $__currentLoopData = $sum_without_supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($item->yarnType === $item2->yarnType): ?>
                                                            <?php
                                                            $tmp_spool = $tmp_spool - $item2->sumspool;
                                                            $tmp_sumweight_p_net = number_format($tmp_spool * $item->average_p, 4);
                                                            $tmp_sumweight_kg_net = number_format($tmp_spool * $item->average_kg, 4);
                                                            ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($item->yarnType); ?></td>
                                                        <!--ชื่อด้าย-->
                                                        <td><?php echo e($tmp_spool); ?></td>
                                                        <!--จำนวนด้ายรวม-->
                                                        <td><?php echo e($tmp_sumweight_p_net); ?> </td>
                                                        <!--น้ำหนักรวม p -->
                                                        <td><?php echo e($tmp_sumweight_kg_net); ?></td>
                                                        <!--น้ำหนักรวม kg-->
                                                        <td><?php echo e(number_format($item->average_p, 4)); ?> </td>
                                                        <!--น้ำหนักรวมเฉลี่ย p -->
                                                        <td><?php echo e(number_format($item->average_kg, 4)); ?> </td>
                                                        <!--น้ำหนักรวมเฉลี่ย kg-->
                                                    </tr>
                                                <?php break; ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($check != 1): ?>
                                            <tr>
                                                <td><?php echo e($item->yarnType); ?></td>
                                                <!--ชื่อด้าย-->
                                                <td><?php echo e($item->sumspool); ?></td>
                                                <!--จำนวนด้ายรวม-->
                                                <td><?php echo e(number_format($item->sumweight_p_net, 4)); ?> </td>
                                                <!--น้ำหนักรวม p -->
                                                <td><?php echo e(number_format($item->sumweight_kg_net, 4)); ?></td>
                                                <!--น้ำหนักรวม kg-->
                                                <td><?php echo e(number_format($item->average_p, 4)); ?> </td>
                                                <!--น้ำหนักรวมเฉลี่ย p -->
                                                <td><?php echo e(number_format($item->average_kg, 4)); ?> </td>
                                                <!--น้ำหนักรวมเฉลี่ย kg-->
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>


                <h2>นำเข้าวัตถุดิบ <?php echo e($supplierName); ?></h2>

                <div class="List_table">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">ชนิดด้าย</th>
                                        <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                        <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                        <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                    </tr>
                                    <tr>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- //loop display supplier import yarn data -->
                                    <?php $__currentLoopData = $sum_import_supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item->yarnType); ?></td>
                                            <!--ชื่อด้าย-->
                                            <td><?php echo e($item->sumspool); ?></td>
                                            <!--จำนวนด้ายรวม-->
                                            <td><?php echo e(number_format($item->sumweight_p_net, 4)); ?> </td>
                                            <!--น้ำหนักรวม p -->
                                            <td><?php echo e(number_format($item->sumweight_kg_net, 4)); ?></td>
                                            <!--น้ำหนักรวม kg-->
                                            <td><?php echo e(number_format($item->average_p, 4)); ?> </td>
                                            <!--น้ำหนักรวมเฉลี่ย p -->
                                            <td><?php echo e(number_format($item->average_kg, 4)); ?> </td>
                                            <!--น้ำหนักรวมเฉลี่ย kg-->
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>

                <h2>เบิกวัตถุดิบ <?php echo e($supplierName); ?> ใช้ภายใน</h2>

                <div class="List_table">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">ชนิดด้าย</th>
                                        <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                        <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                        <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                    </tr>
                                    <tr>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- //loop display supplier withdraw yarn data -->
                                    <?php $__currentLoopData = $sum_withdraw_supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item->yarnType); ?></td>
                                            <!--ชื่อด้าย-->
                                            <td><?php echo e($item->sumspool); ?></td>
                                            <!--จำนวนด้ายรวม-->
                                            <td><?php echo e(number_format($item->sumweight_p_net, 4)); ?> </td>
                                            <!--น้ำหนักรวม p -->
                                            <td><?php echo e(number_format($item->sumweight_kg_net, 4)); ?></td>
                                            <!--น้ำหนักรวม kg-->
                                            <td><?php echo e(number_format($item->average_p, 4)); ?> </td>
                                            <!--น้ำหนักรวมเฉลี่ย p -->
                                            <td><?php echo e(number_format($item->average_kg, 4)); ?> </td>
                                            <!--น้ำหนักรวมเฉลี่ย kg-->
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>

                <h2>เบิกวัตถุดิบ <?php echo e($supplierName); ?> ใช้ภายนอก</h2>

                <div class="List_table">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">ชนิดด้าย</th>
                                        <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                        <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                        <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                    </tr>
                                    <tr>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- //loop display supplier withdraw yarn data -->
                                    <?php $__currentLoopData = $sum_without_supplier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item->yarnType); ?></td>
                                            <!--ชื่อด้าย-->
                                            <td><?php echo e($item->sumspool); ?></td>
                                            <!--จำนวนด้ายรวม-->
                                            <td><?php echo e(number_format($item->sumweight_p_net, 4)); ?> </td>
                                            <!--น้ำหนักรวม p -->
                                            <td><?php echo e(number_format($item->sumweight_kg_net, 4)); ?></td>
                                            <!--น้ำหนักรวม kg-->
                                            <td><?php echo e(number_format($item->average_p, 4)); ?> </td>
                                            <!--น้ำหนักรวมเฉลี่ย p -->
                                            <td><?php echo e(number_format($item->average_kg, 4)); ?> </td>
                                            <!--น้ำหนักรวมเฉลี่ย kg-->
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>
            <?php else: ?>

            <div class="List_table">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">รายการล่าสุด</th>
                                        <th rowspan="2">ชนิดด้าย</th>
                                        <th rowspan="2">บริษัท </th>
                                        <th rowspan="2">จำนวนด้าย <br>(ลูก)</th>
                                        <th colspan="2">น้ำหนักสุทธิ </th>
                                        <th colspan="2">น้ำหนักเฉลี่ย </th>
                                    </tr>
                                    <tr>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $c1 = 0;
                                    ?>
                                    <?php $__currentLoopData = $stockList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tmp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td rowspan=“10”><?php echo e($date = date('d/m/Y', strtotime($tmp[0]->createDate))); ?></td>
                                            <td rowspan=“10”><?php echo e($tmp[0]->yarnType); ?></td>
                                            <td>
                                                <!-- /////////////////////////////////////////////////////////////////////////////////// -->
                                                <!-- <select class="selectVal" name="" id="" style="width: 300px"> -->
                                                <?php
                                                $c2 = 0;
                                                ?>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.655em;margin-bottom: -0.8em;"
                                                    cellspacing="0" cellpadding="0">

                                                    <?php $__currentLoopData = $tmp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectSupplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <!-- <option value="<?php echo e($c1 . '-' . $c2); ?>"><?php echo e($selectSupplier->supplierName); ?></option> -->
                                                        <tr>
                                                            <td>
                                                                <?php echo e($selectSupplier->supplierName); ?> <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <!-- </select> -->
                                                </table>


                                                <?php
                                                $c1 += 1;
                                                ?>
                                                <!-- //////////////////////////////////////////////////////////////////////////////// -->
                                            </td>
                                            <!-- <td id="sp"><?php echo e($tmp[0]->spool); ?></td> -->
                                            <!-- <td id="wpn"><?php echo e(number_format($tmp[0]->weight_p_net, 4)); ?></td>
        <td id="wkgn"><?php echo e(number_format($tmp[0]->weight_kg_net, 4)); ?></td>
        <td id="ap"><?php echo e(number_format($tmp[0]->average_p, 4)); ?></td>
        <td id="akg"><?php echo e(number_format($tmp[0]->average_kg, 4)); ?></td> -->
                                            <td>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.65em;margin-bottom: -0.6em;">

                                                    <?php $__currentLoopData = $tmp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectSpool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <!-- <option value="<?php echo e($c1 . '-' . $c2); ?>"><?php echo e($selectSupplier->supplierName); ?></option> -->
                                                        <tr>
                                                            <td>
                                                                <?php echo e($selectSpool->spool); ?> <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <!-- </select> -->
                                                </table>
                                            </td>
                                            <td>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.65em;margin-bottom: -0.6em;">

                                                    <?php $__currentLoopData = $tmp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectWeight_p_net): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <!-- <option value="<?php echo e($c1 . '-' . $c2); ?>"><?php echo e($selectSupplier->supplierName); ?></option> -->
                                                        <tr>
                                                            <td>
                                                                <?php echo e(number_format($selectWeight_p_net->weight_p_net, 4)); ?>

                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <!-- </select> -->
                                                </table>
                                            </td>
                                            <td>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.65em;margin-bottom: -0.6em;">

                                                    <?php $__currentLoopData = $tmp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectWeight_kg_net): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <!-- <option value="<?php echo e($c1 . '-' . $c2); ?>"><?php echo e($selectSupplier->supplierName); ?></option> -->
                                                        <tr>
                                                            <td>
                                                                <?php echo e(number_format($selectWeight_kg_net->weight_kg_net, 4)); ?>

                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <!-- </select> -->
                                                </table>
                                            </td>
                                            <td>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.65em;margin-bottom: -0.6em;">

                                                    <?php $__currentLoopData = $tmp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectAverage_p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <!-- <option value="<?php echo e($c1 . '-' . $c2); ?>"><?php echo e($selectSupplier->supplierName); ?></option> -->
                                                        <tr>
                                                            <td>
                                                                <?php echo e(number_format($selectAverage_p->average_p, 4)); ?>

                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <!-- </select> -->
                                                </table>
                                            </td>
                                            <td>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.65em;margin-bottom: -0.6em;">

                                                    <?php $__currentLoopData = $tmp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectAverage_kg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <!-- <option value="<?php echo e($c1 . '-' . $c2); ?>"><?php echo e($selectSupplier->supplierName); ?></option> -->
                                                        <tr>
                                                            <td>
                                                                <?php echo e(number_format($selectAverage_kg->average_kg, 4)); ?>

                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <!-- </select> -->
                                                </table>
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

<script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
<script>
    var stocklist = <?php echo e(Js::from($stockList)); ?>;

    $(document).ready(function() {
        $("select.selectVal").change(function() {
            let selectedItem = $(this).children("option:selected").val();
            //alert("You have selected the name - " + selectedItem);
            selectedItem = selectedItem.split('-');
            document.getElementById("sp").innerHTML = stocklist[selectedItem[0]][selectedItem[1]].spool;
            document.getElementById("wpn").innerHTML = stocklist[selectedItem[0]][selectedItem[1]]
                .weight_p_net.toFixed(4);
            document.getElementById("wkgn").innerHTML = stocklist[selectedItem[0]][selectedItem[1]]
                .weight_kg_net.toFixed(4);
            document.getElementById("ap").innerHTML = stocklist[selectedItem[0]][selectedItem[1]]
                .average_p.toFixed(4);
            document.getElementById("akg").innerHTML = stocklist[selectedItem[0]][selectedItem[1]]
                .average_kg.toFixed(4);
        });
    });
    //var test = <?php echo e(Js::from($stockList)); ?>;
    //alert(test[0][1].supplierName);
    //var x = '1-2';
    //x = x.split('-');
    //alert(x[0]);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/materialstock/index.blade.php ENDPATH**/ ?>