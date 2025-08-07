@extends('layouts.astmanufacturing')

@section('content')
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

                <form method="post" action="{{ route('package.store') }}" id="myForm">
                    @csrf

                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
                    <?php
                    // $s_name = 'PST. (แพรกษาเท็กซ์ไทล์)';
                    $s_name = $selectSupplier;
                    $package_data = App\Http\Controllers\PackageController::getData($s_name);
                    
                    // print($package_data );
                    
                    ?>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">บริษัท </th>
                                        <th colspan="11">ชนิด บรรจุภัณฑ์</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5">หลอด</th>
                                        <th colspan="1">กระสอบ</th>
                                        <th colspan="1">กล่อง</th>
                                        <th colspan="3">พาเลท</th>
                                        <th colspan="1">กระดาษกั้น</th>
                                    </tr>
                                    <tr>
                                        <th>{{ $selectSupplier }}</th>
                                        <th>กรวย กระดาษ</th>
                                        <th>กรวย พลาสติก</th>
                                        <th>กระบอก กระดาษ</th>
                                        <th>กระบอก พลาสติก</th>
                                        <th>ไม่ระบุชนิด</th>
                                        <th>พลาสติก</th>
                                        <th></th>
                                        <th>ไม้</th>
                                        <th>เหล็ก</th>
                                        <th>ไม่ระบุชนิด</th>
                                        <th>กระดาษกั้น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>นำเข้า</td>
                                        <td>{{ $spool_plasticImp[0]->spoolsum }}</td>
                                        <td>{{ $spool_paperImp[0]->spoolsum }}</td>
                                        <td>{{ $spoolC_plasticImp[0]->spoolsum }}</td>
                                        <td>{{ $spoolC_paperImp[0]->spoolsum }}</td>
                                        <td>{{ $sumPackage[0]->spoolsum -
                                            $spool_plasticImp[0]->spoolsum -
                                            $spool_paperImp[0]->spoolsum -
                                            $spoolC_plasticImp[0]->spoolsum -
                                            $spoolC_paperImp[0]->spoolsum }}
                                        </td>
                                        <td>{{ $sumPackage[0]->sacksum }}</td>
                                        <td>{{ $sumPackage[0]->boxsum }}</td>
                                        <td>{{ $palletWoodImp[0]->palletsum }}</td>
                                        <td>{{ $palletSteelImp[0]->palletsum }}</td>
                                        <td>{{ $sumPackage[0]->palletsum - $palletSteelImp[0]->palletsum - $palletWoodImp[0]->palletsum }}
                                        </td>
                                        <td>{{ $partitionImp }}</td>
                                    </tr>
                                    <tr>
                                        <td>ต้องส่งคืน</td>
                                        <td>{{ $spool_plasticRet[0]->spoolsum }}</td>
                                        <td>{{ $spool_paperRet[0]->spoolsum }}</td>
                                        <td>{{ $spoolC_plasticRet[0]->spoolsum }}</td>
                                        <td>{{ $spoolC_paperRet[0]->spoolsum }}</td>
                                        <td>{{ $returnPackage[0]->spoolsum -
                                            $spool_plasticRet[0]->spoolsum -
                                            $spool_paperRet[0]->spoolsum -
                                            $spoolC_plasticRet[0]->spoolsum -
                                            $spoolC_paperRet[0]->spoolsum }}
                                        </td>
                                        <td>{{ $returnPackage[0]->sacksum }}</td>
                                        <td>{{ $returnPackage[0]->boxsum }}</td>
                                        <td>{{ $palletWoodRet[0]->palletsum }}</td>
                                        <td>{{ $palletSteelRet[0]->palletsum }}</td>
                                        <td>{{ $returnPackage[0]->palletsum - $palletSteelRet[0]->palletsum - $palletWoodRet[0]->palletsum }}
                                        </td>
                                        <td>{{ $partitionRet }}</td>
                                    </tr>
                                    <tr>
                                        <td>คืนแล้ว</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->spool_paper }}</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->spool_plastic }}</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->spoolC_paper }}</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->spoolC_plastic }}</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->spoolsum }}</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->sacksum }}</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->boxsum }}</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->pallet_wood }}</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->pallet_steel }}</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->palletsum }}</td>
                                        <td>{{ $sumReturnPackageSuccess[0]->partitionsum }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier_name">ชื่อ   บริษัท * </label>

                                @if (isset($backdata))
                                    <input type="text" name="supplier_name" onkeyup="supplierFunction('supplier_name')"
                                        class="form-control" id="supplier_name" placeholder="ชื่อบริษัท"
                                        value="{{ $backdata->supplier_name }}" required>
                                @else
                                    <input type="text" name="supplier_name" onkeyup="supplierFunction('supplier_name')"
                                        class="form-control" id="supplier_name" placeholder="ชื่อบริษัท"
                                        value="{{ $selectSupplier }}" required>
                                @endif

                                <?php $suppliershow = 'test'; ?>
                                <ul id="supp">
                                    @foreach ($supplier as $sup)
                                        <li><a
                                                href="javascript:setsupplierFunction('supp', '{{ $sup->name }}');">{{ $sup->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                        <input type="hidden" id="supplierId" name="supplierId" value="{{ Auth::user()->id }}">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="createDate">วันที่</label>

                                @if (isset($backdata))
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" name="createDate" class="form-control datetimepicker-input"
                                            data-target="#reservationdate" value="{{ $backdata->createDate }} " />
                                        <div class="input-group-append" data-target="#reservationdate"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                            </div>
                        @else
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <input type="text" name="createDate" class="form-control datetimepicker-input"
                                    data-target="#reservationdate" />
                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
            </div>
        </div>
        <!--row-->
        <div class="form-group"><label>บรรจุภัณฑ์</label></div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="pallet" class=""> พาเลทไม้</label>
                    <div class="col-form">
                        @if (isset($backdata))
                            <input type="number" name="pallet_wood" class="form-control" id="inputEmail3"
                                placeholder="จำนวน" value="{{ $backdata->pallet }}">
                        @else
                            <input type="number" name="pallet_wood" class="form-control" id="inputEmail3"
                                placeholder="จำนวน">
                        @endif

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="pallet" class=""> พาเลทเหล็ก</label>
                    <div class="col-form">
                        @if (isset($backdata))
                            <input type="number" name="pallet_steel" class="form-control" id="inputEmail3"
                                placeholder="จำนวน" value="{{ $backdata->pallet }}">
                        @else
                            <input type="number" name="pallet_steel" class="form-control" id="inputEmail3"
                                placeholder="จำนวน">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="box" class=""> กล่อง</label>
                    <div class="col-form">
                        @if (isset($backdata))
                            <input type="number" name="box" class="form-control" id="inputEmail3"
                                placeholder="จำนวน" value="{{ $backdata->box }}">
                        @else
                            <input type="number" name="box" class="form-control" id="inputEmail3"
                                placeholder="จำนวน">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="sack" class="">กระสอบ</label>
                    <div class="col-form">

                        @if (isset($backdata))
                            <input type="number" name="sack" class="form-control" id="inputEmail3"
                                placeholder="จำนวน" value="{{ $backdata->sack }}">
                        @else
                            <input type="number" name="sack" class="form-control" id="inputEmail3"
                                placeholder="จำนวน">
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <!--row-->
        <div class="row">
            <div class="col-md-6 align-items-end">
                <div class="form-group">
                    <label for="partition">กระดาษกั้น</label>
                    @if (isset($backdata))
                        <input type="number" name="partition" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool"
                            placeholder="จำนวนกระดาษกั้น" value="{{ $backdata->spool }}" required>
                    @else
                        <input type="number" name="partition" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool"
                            placeholder="จำนวนกระดาษกั้น" >
                    @endif
                </div>
            </div>
        </div>
        <!--row-->
        <div class="row">
            <div class="col-md-3 align-items-end">
                <div class="form-group">
                    <label for="spool_paper">กรวย กระดาษ</label>

                    @if (isset($backdata))
                        <input type="number" name="spool_paper" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool_paper"
                            placeholder="กรวย กระดาษ" value="{{ $backdata->spool_paper }}" required>
                    @else
                        <input type="number" name="spool_paper" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool_paper"
                            placeholder="กรวย กระดาษ" >
                    @endif

                </div>
            </div>
            <div class="col-md-3 align-items-end">
                <div class="form-group">
                    <label for="spool_plastic">กรวย พลาสติก</label>

                    @if (isset($backdata))
                        <input type="number" name="spool_plastic" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool_plastic"
                            placeholder="กรวย พลาสติก" value="{{ $backdata->spool_plastic }}" required>
                    @else
                        <input type="number" name="spool_plastic" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool_plastic"
                            placeholder="กรวย พลาสติก" >
                    @endif

                </div>
            </div>
            <div class="col-md-3 align-items-end">
                <div class="form-group">
                    <label for="spoolC_paper">กระบอก กระดาษ</label>

                    @if (isset($backdata))
                        <input type="number" name="spoolC_paper" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spoolC_paper"
                            placeholder="กระบอก กระดาษ" value="{{ $backdata->spoolC_paper }}" required>
                    @else
                        <input type="number" name="spoolC_paper" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spoolC_paper"
                            placeholder="กระบอก กระดาษ" >
                    @endif

                </div>
            </div>
            <div class="col-md-3 align-items-end">
                <div class="form-group">
                    <label for="spoolC_plastic">กระบอก พลาสติก</label>

                    @if (isset($backdata))
                        <input type="number" name="spoolC_plastic" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spoolC_plastic"
                            placeholder="กระบอก พลาสติก" value="{{ $backdata->spoolC_plastic }}" required>
                    @else
                        <input type="number" name="spoolC_plastic" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spoolC_plastic"
                            placeholder="กระบอก พลาสติก" >
                    @endif

                </div>
            </div>
        </div>
        <!--row-->


        <div class="line_btn">
            <button name="submit" value="cleanForm" class="btn b_order clean"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>"
                    width="15"> เคลียร์ข้อมูล</button>
            <button name="submit" value="packagecheck" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>"
                    width="17">
                ตรวจสอบ</button>
            {{-- Htrpackagecreate --}}
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
@endsection
