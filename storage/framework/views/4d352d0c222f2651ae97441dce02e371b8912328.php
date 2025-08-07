

<?php $__env->startSection('content'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="/stockfabric/">คลังสินค้า</a>
                </li>
                <li class="breadcrumb-item active">สต็อกผ้าฝากจัดเก็บ</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> สต็อกผ้าฝากจัดเก็บ</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> รายการสต็อกผ้าฝากจัดเก็บ</h2>
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
                <form method="post" action="<?php echo e(route('fabricdeposit.store')); ?>" id="myForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="refId" name="refId" value="">
                    <input type="hidden" id="emp" name="emp" value="<?php echo e(Auth::user()->name); ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fabricStruct">โครงสร้างผ้า</label>
                                <?php if(isset($backdata)): ?>
                                    <input type="text" name="fabricStruct" class="form-control" id="fabricStruct"
                                        placeholder="โครงสร้างผ้า" value="<?php echo e($backdata->fabricStruct); ?>">
                                <?php else: ?>
                                    <input type="text" name="fabricStruct" class="form-control" id="fabricStruct"
                                        placeholder="โครงสร้างผ้า">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fabricStruct">ลูกค้า</label>
                                <?php if(isset($backdata)): ?>
                                    <input type="text" name="customerName" class="form-control" id="customerName"
                                        placeholder="ลูกค้า" value="<?php echo e($backdata->customerName); ?>">
                                <?php else: ?>
                                    <input type="text" name="customerName" class="form-control" id="customerName"
                                        placeholder="ลูกค้า">
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!--row-->
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="text-right align-items-end" style="margin-top:1.5rem">
                                    <button name="submit" value="searchImport" class="btn_search"><img
                                            src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
                                        ค้นหา</button>
                                </div>
                            </div>
                            <!--row-->
                        </div>
                    </div>


                </form>
                <?php if(isset($importorder) && count($importorder) > 0): ?>
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a" style="width:100%">
                                <thead style="position: sticky;top: 0;background-color:powderblue;">
                                    <tr>
                                        <th rowspan="2">วันที่ </th>
                                        <th rowspan="2">ผู้สั่ง </th>
                                        
                                        <th rowspan="2">โครงสร้าง </th>
                                        <th rowspan="2">จำนวนพับ</th>
                                        <th rowspan="2">จำนวนหลา</th>
                                        <th colspan="2">จัดส่งแล้ว</th>
                                        <th colspan="2">คงเหลือ</th>
                                        <th rowspan="2">จัดส่ง</th>
                                        <th rowspan="2">ลบ</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">พับ </th>
                                        <th rowspan="2">หลา </th>
                                        <th rowspan="2">พับ </th>
                                        <th rowspan="2">หลา </th>
                                    </tr>
                                <tbody style="text-align: right;">
                                    <?php
                                    $c = $importorder->count();
                                    $a = $sumfabricoutdepo->count();
                                    ?>
                                    <?php for($i = 0; $i < $c; $i++): ?>
                                        <?php $check = 0; ?>
                                        <?php for($j = 0; $j < $a; $j++): ?>
                                            <?php $check = 0; ?>
                                            <?php if ($importorder[$i]->refId === $sumfabricoutdepo[$j]->orderId) { ?>
                                            <?php $check = 1; ?>
                                            <tr>
                                                
                                                <td><?php echo e($date = date('d/m/Y', strtotime($importorder[$i]->lastDate))); ?>

                                                </td>
                                                <td><?php echo e($importorder[$i]->customerName); ?>

                                                    
                                                </td>
                                                
                                                <td><?php echo e($importorder[$i]->fabricStruct); ?></td>
                                                <td><?php echo e($importorder[$i]->foldCount); ?></td>
                                                <td><?php echo e($importorder[$i]->sumYardSum); ?></td>

                                                <td><?php echo e($sumfabricoutdepo[$j]->foldCount); ?></td>
                                                <td><?php echo e($sumfabricoutdepo[$j]->sumYardSum); ?></td>

                                                <td><?php echo e($importorder[$i]->foldCount - $sumfabricoutdepo[$j]->foldCount); ?>

                                                </td>
                                                <td><?php echo e($importorder[$i]->sumYardSum - $sumfabricoutdepo[$j]->sumYardSum); ?>

                                                </td>

                                                <td>
                                                    <form method="post" action="<?php echo e(route('fabricoutdeposit.store')); ?>"
                                                        id="myForm">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="orderId"
                                                            value="<?php echo e($importorder[$i]->refId); ?>">
                                                        <input type="hidden" name="customerName"
                                                            value="<?php echo e($importorder[$i]->customerName); ?>                                                        ">
                                                        <input type="hidden" name="fabricStruct"
                                                            value="<?php echo e($importorder[$i]->fabricStruct); ?>">
                                                        <input type="hidden" name="fabricPattern"
                                                            value="<?php echo e($importorder[$i]->fabricPattern); ?>">
                                                        <input type="hidden" name="fabricW"
                                                            value="<?php echo e($importorder[$i]->fabricW); ?>">

                                                        <input type="hidden" name="no"
                                                            value="<?php echo e($importorder[$i]->no); ?>">
                                                        <input type="hidden" name="refId"
                                                            value="<?php echo e($importorder[$i]->refId); ?>">

                                                        <input type="hidden" name="receiveName"
                                                            value="<?php echo e($importorder[$i]->receiveName); ?>">


                                                        <button name="submit" value="generateByOrder"
                                                            class="btn b_save">
                                                            
                                                            จัดส่งสินค้า</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form method="POST"
                                                        action="<?php echo e(route('fabricdeposit.destroy', $importorder[$i]->no)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <div style="margin: 0.2rem">
                                                            <button class="btn btn-danger" type="button"
                                                                style="width:90%;margin: 0.2rem;font-size: 0.8rem"
                                                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                            
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php break; ?>

                                        <?php } ?>
                                    <?php endfor; ?>
                                    <?php if ($check != 1) { ?>
                                    <tr>
                                        
                                        <td><?php echo e($date = date('d/m/Y', strtotime($importorder[$i]->lastDate))); ?></td>
                                        <td><?php echo e($importorder[$i]->customerName); ?></td>
                                        
                                        <td><?php echo e($importorder[$i]->fabricStruct); ?></td>
                                        <td><?php echo e($importorder[$i]->foldCount); ?></td>
                                        <td><?php echo e($importorder[$i]->sumYardSum); ?></td>

                                        <td></td>
                                        <td></td>

                                        <td><?php echo e($importorder[$i]->foldCount); ?>

                                        </td>
                                        <td><?php echo e($importorder[$i]->sumYardSum); ?>

                                        </td>

                                        <td>
                                            <form method="post" action="<?php echo e(route('fabricoutdeposit.store')); ?>"
                                                id="myForm">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="orderId"
                                                    value="<?php echo e($importorder[$i]->refId); ?>">
                                                <input type="hidden" name="customerName"
                                                    value="<?php echo e($importorder[$i]->customerName); ?>                                                        ">
                                                <input type="hidden" name="fabricStruct"
                                                    value="<?php echo e($importorder[$i]->fabricStruct); ?>">
                                                <input type="hidden" name="fabricPattern"
                                                    value="<?php echo e($importorder[$i]->fabricPattern); ?>">
                                                <input type="hidden" name="fabricW"
                                                    value="<?php echo e($importorder[$i]->fabricW); ?>">

                                                <input type="hidden" name="no"
                                                    value="<?php echo e($importorder[$i]->no); ?>">
                                                <input type="hidden" name="refId"
                                                    value="<?php echo e($importorder[$i]->refId); ?>">

                                                <input type="hidden" name="receiveName"
                                                    value="<?php echo e($importorder[$i]->receiveName); ?>">


                                                <button name="submit" value="generateByOrder" class="btn b_save">
                                                    
                                                    จัดส่งสินค้า</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="POST"
                                                action="<?php echo e(route('fabricdeposit.destroy', $importorder[$i]->no)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <div style="margin: 0.2rem">
                                                    <button class="btn btn-danger" type="button"
                                                        style="width:90%;margin: 0.2rem;font-size: 0.8rem"
                                                        onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                    
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                <?php endfor; ?>

                            </tbody>
                            </thead>
                        </table>
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
                                        <th rowspan="2">ผู้สั่ง </th>
                                        
                                        <th rowspan="2">โครงสร้าง </th>
                                        <th rowspan="2">จำนวนพับ</th>
                                        <th rowspan="2">จำนวนหลา</th>
                                        <th colspan="2">จัดส่งแล้ว</th>
                                        <th colspan="2">คงเหลือ</th>
                                        <th rowspan="2">จัดส่ง</th>
                                        <th rowspan="2">ลบ</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">พับ </th>
                                        <th rowspan="2">หลา </th>
                                        <th rowspan="2">พับ </th>
                                        <th rowspan="2">หลา </th>
                                    </tr>
                                <tbody style="text-align: right;">
                                    <?php
                                    $c = $sumfabricdepo->count();
                                    $a = $sumfabricoutdepo->count();
                                    ?>
                                    <?php for($i = 0; $i < $c; $i++): ?>
                                        <?php $check = 0; ?>
                                        <?php for($j = 0; $j < $a; $j++): ?>
                                            <?php $check = 0; ?>
                                            <?php if ($sumfabricdepo[$i]->refId === $sumfabricoutdepo[$j]->orderId) { ?>
                                            <?php $check = 1; ?>
                                            <tr>
                                                
                                                <td><?php echo e($date = date('d/m/Y', strtotime($sumfabricdepo[$i]->lastDate))); ?>

                                                </td>
                                                <td><?php echo e($sumfabricdepo[$i]->customerName); ?>

                                                    
                                                </td>
                                                
                                                <td><?php echo e($sumfabricdepo[$i]->fabricStruct); ?></td>
                                                <td><?php echo e($sumfabricdepo[$i]->foldCount); ?></td>
                                                <td><?php echo e($sumfabricdepo[$i]->sumYardSum); ?></td>

                                                <td><?php echo e($sumfabricoutdepo[$j]->foldCount); ?></td>
                                                <td><?php echo e($sumfabricoutdepo[$j]->sumYardSum); ?></td>

                                                <td><?php echo e($sumfabricdepo[$i]->foldCount - $sumfabricoutdepo[$j]->foldCount); ?>

                                                </td>
                                                <td><?php echo e($sumfabricdepo[$i]->sumYardSum - $sumfabricoutdepo[$j]->sumYardSum); ?>

                                                </td>

                                                <td>
                                                    <form method="post"
                                                        action="<?php echo e(route('fabricoutdeposit.store')); ?>"
                                                        id="myForm">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="orderId"
                                                            value="<?php echo e($sumfabricdepo[$i]->refId); ?>">
                                                        <input type="hidden" name="customerName"
                                                            value="<?php echo e($sumfabricdepo[$i]->customerName); ?>                                                        ">
                                                        <input type="hidden" name="fabricStruct"
                                                            value="<?php echo e($sumfabricdepo[$i]->fabricStruct); ?>">
                                                        <input type="hidden" name="fabricPattern"
                                                            value="<?php echo e($sumfabricdepo[$i]->fabricPattern); ?>">
                                                        <input type="hidden" name="fabricW"
                                                            value="<?php echo e($sumfabricdepo[$i]->fabricW); ?>">

                                                        <input type="hidden" name="no"
                                                            value="<?php echo e($sumfabricdepo[$i]->no); ?>">
                                                        <input type="hidden" name="refId"
                                                            value="<?php echo e($sumfabricdepo[$i]->refId); ?>">

                                                        <input type="hidden" name="receiveName"
                                                            value="<?php echo e($sumfabricdepo[$i]->receiveName); ?>">


                                                        <button name="submit" value="generateByOrder"
                                                            class="btn b_save">
                                                            
                                                            จัดส่งสินค้า</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form method="POST"
                                                        action="<?php echo e(route('fabricdeposit.destroy', $sumfabricdepo[$i]->no)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <div style="margin: 0.2rem">
                                                            <button class="btn btn-danger" type="button"
                                                                style="width:90%;margin: 0.2rem;font-size: 0.8rem"
                                                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                            
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php break; ?>

                                        <?php } ?>
                                    <?php endfor; ?>
                                    <?php if ($check != 1) { ?>
                                    <tr>
                                        
                                        <td><?php echo e($date = date('d/m/Y', strtotime($sumfabricdepo[$i]->lastDate))); ?>

                                        </td>
                                        <td><?php echo e($sumfabricdepo[$i]->customerName); ?></td>
                                        
                                        <td><?php echo e($sumfabricdepo[$i]->fabricStruct); ?></td>
                                        <td><?php echo e($sumfabricdepo[$i]->foldCount); ?></td>
                                        <td><?php echo e($sumfabricdepo[$i]->sumYardSum); ?></td>

                                        <td></td>
                                        <td></td>

                                        <td><?php echo e($sumfabricdepo[$i]->foldCount); ?>

                                        </td>
                                        <td><?php echo e($sumfabricdepo[$i]->sumYardSum); ?>

                                        </td>

                                        <td>
                                            <form method="post" action="<?php echo e(route('fabricoutdeposit.store')); ?>"
                                                id="myForm">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="orderId"
                                                    value="<?php echo e($sumfabricdepo[$i]->refId); ?>">
                                                <input type="hidden" name="customerName"
                                                    value="<?php echo e($sumfabricdepo[$i]->customerName); ?>                                                        ">
                                                <input type="hidden" name="fabricStruct"
                                                    value="<?php echo e($sumfabricdepo[$i]->fabricStruct); ?>">
                                                <input type="hidden" name="fabricPattern"
                                                    value="<?php echo e($sumfabricdepo[$i]->fabricPattern); ?>">
                                                <input type="hidden" name="fabricW"
                                                    value="<?php echo e($sumfabricdepo[$i]->fabricW); ?>">

                                                <input type="hidden" name="no"
                                                    value="<?php echo e($sumfabricdepo[$i]->no); ?>">
                                                <input type="hidden" name="refId"
                                                    value="<?php echo e($sumfabricdepo[$i]->refId); ?>">

                                                <input type="hidden" name="receiveName"
                                                    value="<?php echo e($sumfabricdepo[$i]->receiveName); ?>">


                                                <button name="submit" value="generateByOrder"
                                                    class="btn b_save">
                                                    
                                                    จัดส่งสินค้า</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="POST"
                                                action="<?php echo e(route('fabricdeposit.destroy', $sumfabricdepo[$i]->no)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <div style="margin: 0.2rem">
                                                    <button class="btn btn-danger" type="button"
                                                        style="width:90%;margin: 0.2rem;font-size: 0.8rem"
                                                        onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                    
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                <?php endfor; ?>

                            </tbody>
                            </thead>
                        </table>
                    </div>
        <?php endif; ?>
        

    </div>
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

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/fabricdeposit/index.blade.php ENDPATH**/ ?>