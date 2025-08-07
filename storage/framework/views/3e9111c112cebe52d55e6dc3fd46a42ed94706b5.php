

<?php $__env->startSection('content'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home/">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="#">วัตถุดิบ</a>
                </li>
                <li class="breadcrumb-item active">คืนบรรจุภัณฑ์</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> บรรจุภัณฑ์</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> ส่งคืนบรรจุภัณฑ์</h2>

                <form method="post" action="<?php echo e(route('package.store')); ?>" id="myForm">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" id="emp" name="emp" value="<?php echo e(Auth::user()->name); ?>">
                    <?php
                    // $s_name = 'PST. (แพรกษาเท็กซ์ไทล์)';
                    // $s_name = $selectSupplier;
                    // $package_data = App\Http\Controllers\PackageController::getData($s_name);
                    
                    // print($package_data );
                    
                    ?>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">บริษัท </th>
                                        <th colspan="10">ชนิด บรรจุภัณฑ์</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4">หลอด</th>
                                        <th colspan="1">กระสอบ</th>
                                        <th colspan="1">กล่อง</th>
                                        <th colspan="2">พาเลท</th>
                                        <th colspan="1">กระดาษกั้น</th>
                                    </tr>
                                    <tr>
                                        <th><?php echo e($packdata->supplier_name); ?></th>
                                        
                                        <th>กรวย กระดาษ</th>
                                        <th>กรวย พลาสติก</th>
                                        <th>กระบอก กระดาษ</th>
                                        <th>กระบอก พลาสติก</th>
                                        <th></th>
                                        <th></th>
                                        <th>ไม้</th>
                                        <th>เหล็ก</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>คืน</td>
                                        <td><?php echo e($packdata->spool_paper); ?></td>
                                        <td><?php echo e($packdata->spool_plastic); ?></td>
                                        <td><?php echo e($packdata->spoolC_paper); ?></td>
                                        <td><?php echo e($packdata->spoolC_plastic); ?></td>
                                        <td><?php echo e($packdata->sack); ?></td>
                                        <td><?php echo e($packdata->box); ?></td>
                                        <td><?php echo e($packdata->pallet_wood); ?></td>
                                        <td><?php echo e($packdata->pallet_steel); ?></td>
                                        <td><?php echo e($packdata->partition); ?></td>
                                    </tr>
                                    <tr>
                                        <td>คืน</td>
                                        <td><?php echo e($packdata->createDate); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
        <!--row-->
        <div class="line_btn">
            <input type="hidden" name="createDate" value="<?php echo e($packdata->createDate); ?>">
            <input type="hidden" name="supplier_name" value="<?php echo e($packdata->supplier_name); ?>">
            <input type="hidden" name="spool_paper" value="<?php echo e($packdata->spool_paper); ?>">
            <input type="hidden" name="spool_plastic" value="<?php echo e($packdata->spool_plastic); ?>">
            <input type="hidden" name="spoolC_paper" value="<?php echo e($packdata->spoolC_paper); ?>">
            <input type="hidden" name="spoolC_plastic" value="<?php echo e($packdata->spoolC_plastic); ?>">
            <input type="hidden" name="sack" value="<?php echo e($packdata->sack); ?>">
            <input type="hidden" name="box" value="<?php echo e($packdata->box); ?>">
            <input type="hidden" name="pallet_wood" value="<?php echo e($packdata->pallet_wood); ?>">
            <input type="hidden" name="pallet_steel" value="<?php echo e($packdata->pallet_steel); ?>">
            <input type="hidden" name="partition" value="<?php echo e($packdata->partition); ?>">
            <button name="submit" value="Htrpackagecreate" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>"
                    width="17">
                ส่งคืน</button>
            
        </div>

        </form>

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
            document.getElementById("supplier_name").value = t1;
            ul = document.getElementById(t);
            ul.style.display = "none";
        }


        function setsupplierFunction(t, t1) {
            document.getElementById("supplier_name").value = t1;
            ul = document.getElementById(t);
            //ul.style.display = "none";  
            li = ul.getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

        }
    </script>

    <script>
        function weightConverter1(valNum, id) {
            //  document.getElementById("weight_kg_sum").innerHTML=valNum/2.2046;
            var p = (valNum * 2.2046).toFixed(4);
            document.getElementById(id).value = p;


            if (id ?? "weight_kg_package") {
                var net = 0;
                if (p > 0) {
                    var x = document.getElementById('weight_p_sum').value;
                    if (x > 0) {
                        net = (x - p).toFixed(4);

                        document.getElementById('weight_p_net').value = net;
                        document.getElementById('weight_kg_net').value = (net / 2.2046).toFixed(4);

                        var y = document.getElementById('spool').value;
                        var av = 0;
                        if (y > 0) {
                            av = (net / y).toFixed(4);
                        }
                        document.getElementById('average_p').value = av;
                        document.getElementById('average_kg').value = ((net / 2.2046) / y).toFixed(4);

                    }
                }
            }
        }

        function setCount(valNum, id) {
            //  document.getElementById("weight_kg_sum").innerHTML=valNum/2.2046;
            document.getElementById(id).value = valNum;
        }


        function weightConverter(valNum, id) {
            var k = (valNum / 2.2046).toFixed(4);
            document.getElementById(id).value = k;

            if (id ?? "weight_p_package") {
                var net = 0;
                if (k > 0) {
                    var x = document.getElementById('weight_p_sum').value;
                    if (x > 0) {
                        net = (x - valNum).toFixed(4);
                        //alert(net);

                        document.getElementById('weight_p_net').value = net;
                        document.getElementById('weight_kg_net').value = (net / 2.2046).toFixed(4);

                        var y = document.getElementById('spool').value;
                        var av = 0;
                        if (y > 0) {
                            av = (net / y).toFixed(4);
                        }
                        document.getElementById('average_p').value = av;
                        document.getElementById('average_kg').value = ((net / 2.2046) / y).toFixed(4);

                    }
                }


            }

        }
    </script>

    <script>
        var ul, li;

        ul = document.getElementsByClassName("yarntypeUL")[0];
        li = ul.getElementsByTagName("li");
        //alert(li.length);

        for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
        }

        function yarntypeFunction(id) {
            var input, filter, ul, li, a, i, txtValue;

            input = document.getElementById(id);
            filter = input.value.toUpperCase();
            //ul = document.getElementById("syt");
            ul = document.getElementsByClassName("yarntypeUL")[0];

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


        function setyarntypeFunction(t, t1) {
            document.getElementById("yarnType").value = t1;
            //ul = document.getElementById(t);
            ul = document.getElementsByClassName("yarntypeUL")[0];

            //ul.style.display = "none";  
            li = ul.getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

        }


        function cleanformFunction() {
            $("#myForm").trigger("reset");
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.astmanufacturing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ast-menufacturing/resources/views/package/packagecheck.blade.php ENDPATH**/ ?>