

<?php $__env->startSection('content'); ?>
    <style>
        /* table,
                                                                                                                                                        button {
                                                                                                                                                            font-size: 0.8rem;
                                                                                                                                                        } */
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="/stockfabric/">คลังสินค้า</a>
                </li>
                <li class="breadcrumb-item active">พิมพ์บิลส่งของ</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> พิมพ์บิลส่งของ</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> พิมพ์บิลส่งของ</h2>
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
                <div class="row">
                    <div class="col-md-6">
                        <form method="post" action="<?php echo e(route('fabricout.store')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="findNo">ค้นหา No</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="Notype" class="form-control" id="Notype">
                                                    <option value="non">---</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="findNo" onkeyup="supplierFunction('findNo')"
                                                    class="form-control" id="findNo" placeholder="No">
                                            </div>
                                        </div>
                                        <?php $suppliershow = 'test';
                                        $nofindsave = App\Http\Controllers\FabricoutController::findNo();
                                        ?>
                                        <ul id="supp">
                                            <?php $__currentLoopData = $nofindsave; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><a
                                                        href="javascript:setsupplierFunction('supp', '<?php echo e($sup->no); ?>');"><?php echo e($sup->no); ?></a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="line_btn_search">
                                <button type="submit" name="submit" value="searchImport" class="btn_search"><i
                                        class="fa fa-search"></i> ค้นหา</button>
                                <button type="submit" name="back" value="searchImport" class="btn_search"><i
                                        class="fa fa-search"></i><a href="<?php echo e(route('fabricout.index')); ?>"
                                        style="color: white"> กลับหน้าหลัก</a></button>
                            </div>

                        </form>
                        <!-- <form action="<?php echo e(route('customer.index')); ?>">
                                                                                                                                                               <?php echo csrf_field(); ?>
                                                                                                                                         <div class="line_btn">
                                                                                                                                         <input type="hidden" id="myInput" onkeyup="myFunction()" class="form-control form-control-lg" value="all">
                                                                                                                                              <button type="submit" class="btn btn-etc"> <i class="fas fa-pencil-alt"></i> แก้ไขข้อมูลลูกค้า</button>
                                                                                                                          <button  type="submit" class="btn btn-etc" id="all"> <i class="fas fa-folder"></i> ดูข้อมูลลูกค้าทั้งหมด</button>
                                                                                                                                              </form> -->




                    </div>
                    <!--box-from-->
                </div>
                <!--content-->
                <?php if(isset($importFabricout) && count($importFabricout) > 0): ?>
                    <div class="row">
                        
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a" style="width:100%">
                                <thead style="position: sticky;top: 0;background-color:powderblue;">
                                    <tr>
                                        <th>วันที่ </th>
                                        <th>No. </th>
                                        <th style="width: 15%">ผู้สั่ง </th>
                                        <th>ผู้รับ</th>
                                        <th style="width: 23%">โครงสร้าง </th>
                                        <th style="width: 6rem">ลายผ้า</th>
                                        <th style="width: 5rem">หน้ากว้าง</th>
                                        <th>จำนวนพับ</th>
                                        <th>จำนวนหลา</th>
                                        <th>สั่งใบส่งสินค้า</th>
                                        <th>ลบ/แก้ไข</th>
                                    </tr>
                                <tbody style="text-align: right;">
                                    <?php
                                    // $c = $sumfabricout->count();
                                    ?>
                                    
                                    <?php $__currentLoopData = $importFabricout; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $findFabric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            
                                            <td><?php echo e($date = date('d/m/Y', strtotime($findFabric->lastDate))); ?></td>
                                            
                                            <td><?php echo e($findFabric->vatType); ?> - <?php echo e($findFabric->vatNo); ?></td>

                                            <td><?php echo e($findFabric->customerName); ?></td>
                                            <td><?php echo e($findFabric->receiveName); ?></td>
                                            <td><?php echo e($findFabric->fabricStruct); ?></td>
                                            <td><?php echo e($findFabric->fabricPattern); ?></td>
                                            <td><?php echo e($findFabric->fabricW); ?></td>
                                            <td><?php echo e($findFabric->foldCount); ?></td>
                                            <td><?php echo e($findFabric->sumYardSum); ?></td>
                                            <td>
                                                <form action="<?php echo e(route('fabricout.store')); ?>" method="post"
                                                    target="_blank">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="fabricout_no"
                                                        value="<?php echo e($findFabric->no); ?>">
                                                    <button type="submit" class="btn b_order" name="submit"
                                                        style="width: 5rem;margin: 0.2rem;font-size: 0.8rem"
                                                        value="submitfabricout">สั่งใบส่ง</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="POST"
                                                    action="<?php echo e(route('fabricout.destroy', $findFabric->no)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <input type="text" value="<?php echo e($findFabric->refId); ?>"
                                                        name="refId">
                                                    <div style="margin: 0.2rem">
                                                        <button class="btn btn-danger" type="button"
                                                            style="width:90%;margin: 0.2rem;font-size: 0.8rem"
                                                            onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                        <button class="btn b_order" type="button"
                                                            style="width:90%;margin: 0.2rem;font-size: 0.8rem"><a
                                                                href="">แก้ไข</a></button>
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
                <?php elseif(isset($importFabricout) && count($importFabricout) < 1): ?>
                    <p>ไม่พบผลการค้นหา</p>
                <?php else: ?>
                    <div class="row">
                        
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a" style="width:100%">
                                <thead style="position: sticky;top: 0;background-color:powderblue;">
                                    <tr>
                                        <th>วันที่ </th>
                                        <th>No. </th>
                                        <th style="width: 15%">ผู้สั่ง </th>
                                        <th>ผู้รับ</th>
                                        <th style="width: 23%">โครงสร้าง </th>
                                        <th style="width: 6rem">ลายผ้า</th>
                                        <th style="width: 5rem">หน้ากว้าง</th>
                                        <th>จำนวนพับ</th>
                                        <th>จำนวนหลา</th>
                                        <th>สั่งใบส่งสินค้า</th>
                                        <th>ลบ/แก้ไข</th>
                                    </tr>
                                <tbody style="text-align: right;">
                                    <?php
                                    $c = $sumfabricout->count();
                                    // $c  = 1;
                                    print($c)

                                    ?>
                                    
                                                                        <?php for($i = 0; $i < $c; $i++): ?>
                                                                                                                <tr>
                                                                                                                                                                <td><?php echo e($date = date('d/m/Y', strtotime($sumfabricout[$i]->lastDate))); ?></td>
                                            <td><?php echo e($sumfabricout[$i]->vatType); ?> - <?php echo e($sumfabricout[$i]->vatNo); ?></td>
                                            <td><?php echo e($sumfabricout[$i]->customerName); ?></td>
                                            <td><?php echo e($sumfabricout[$i]->receiveName); ?></td>
                                            <td><?php echo e($sumfabricout[$i]->fabricStruct); ?></td>
                                            <td><?php echo e($sumfabricout[$i]->fabricPattern); ?></td>
                                            <td><?php echo e($sumfabricout[$i]->fabricW); ?></td>
                                            <td><?php echo e($sumfabricout[$i]->foldCount); ?></td>
                                            <td><?php echo e($sumfabricout[$i]->sumYardSum); ?></td>

                                                                                        <td>
                                                <form action="<?php echo e(route('fabricout.store')); ?>" method="post"
                                                    target="_blank">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="fabricout_no"
                                                        value="<?php echo e($sumfabricout[$i]->no); ?>">

                                                    <button type="submit" class="btn b_order" name="submit"
                                                        style="width: 5rem;margin: 0.2rem;font-size: 0.8rem"
                                                        value="submitfabricout">สั่งใบส่ง</button>
                                                </form>
                                            </td>

                                            <td>
                                                <form method="POST"
                                                    action="<?php echo e(route('fabricout.destroy', $sumfabricout[$i]->refId)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <div style="margin: 0.2rem">
                                                        <button class="btn btn-danger" type="button"
                                                            style="width:90%;margin: 0.2rem;font-size: 0.8rem"
                                                            onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>


                                                        <button class="btn b_order" type="button"
                                                            style="width:90%;margin: 0.2rem;font-size: 0.8rem"><a
                                                                href="<?php echo e(route('fabricout.edit', $sumfabricout[$i]->refId)); ?>">แก้ไข</a></button>
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

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/fabricout/index.blade.php ENDPATH**/ ?>