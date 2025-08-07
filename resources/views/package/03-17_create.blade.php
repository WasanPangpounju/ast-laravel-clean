@extends('layouts.astmanufacturing')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="#">วัตถุดิบ</a>
                </li>
                <li class="breadcrumb-item active">นำเข้าวัตถุดิบ</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> นำเข้าวัตถุดิบ</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> เพิ่มรายการวัตถุดิบ</h2>

                <form method="post" action="{{ route('package.store') }}" id="myForm">
                    @csrf

                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">บริษัท </th>
                                        <th colspan="8">ชนิด บรรจุภัณฑ์</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4">หลอด</th>
                                        <th colspan="1">กระสอบ</th>
                                        <th colspan="1">กล่อง</th>
                                        <th colspan="2">พาเลท</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>กรวย กระดาษ</th>
                                        <th>กรวย พลาสติก</th>
                                        <th>กระบอก กระดาษ</th>
                                        <th>กระบอก พลาสติก</th>
                                        <th></th>
                                        <th></th>
                                        <th>ไม้</th>
                                        <th>พลาสติก</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>นำเข้า</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td>9</td>
                                    </tr>
                                    <tr>
                                        <td>ต้องคืน</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td>9</td>
                                    </tr>
                                    <tr>
                                        <td>คืนแล้ว</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td>9</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier_name">ชื่อบริษัท * </label>

                                @if (isset($backdata))
                                    <input type="text" name="supplier_name" onkeyup="supplierFunction('supplier_name')"
                                        class="form-control" id="supplier_name" placeholder="ชื่อบริษัท"
                                        value="{{ $backdata->supplier_name }}" required>
                                @else
                                    <input type="text" name="supplier_name" onkeyup="supplierFunction('supplier_name')"
                                        class="form-control" id="supplier_name" placeholder="ชื่อบริษัท" required>
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
                                <label for="importStatus">ผู้คืนบรรจุภัณฑ์</label>

                                {{-- @if (isset($backdata))
                                    <input type="text" name="stuff" class="form-control" id="stuff"
                                        placeholder="ผู้คืนบรรจุภัณฑ์" value="{{ $backdata->importStatus }}">
                                @else
                                    <input type="text" name="stuff" class="form-control" id="stuff"
                                        placeholder="ผู้คืนบรรจุภัณฑ์">
                                @endif --}}
                                <select name="stuff" class="form-control" id="typetag">
                                    @foreach ($stuff as $stuffSelect)
                                        <option value="{{ $stuffSelect->Fname }} {{ $stuffSelect->Lname }}">
                                            {{ $stuffSelect->Fname }} {{ $stuffSelect->Lname }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

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
                    <label for="pallet" class="col-txt-1"> พาเลท</label>
                    <div class="col-form">

                        @if (isset($backdata))
                            <input type="number" name="pallet" class="form-control" id="inputEmail3" placeholder="จำนวน"
                                value="{{ $backdata->pallet }}">
                        @else
                            <input type="number" name="pallet" class="form-control" id="inputEmail3"
                                placeholder="จำนวน">
                        @endif

                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <select name="typetag_pallet" class="form-control" id="typetag">
                        <option value="wood">ไม้</option>
                        <option value="steel">เหล็ก</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="box" class="col-txt-1"> กล่อง</label>
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
                    <label for="sack" class="col-txt-1">กระสอบ</label>
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
            <div class="col-md-1">
                <div class="form-group">
                    <select name="typetag_sack" class="form-control" id="typetag">
                        <option value="p">ปอ</option>
                        <option value="plastic">พลาสติก</option>
                    </select>
                </div>
            </div>
        </div>
        <!--row-->
        <div class="row">
            <div class="col-md-5 align-items-end">
                <div class="form-group">
                    <label for="paper_bar">กระดาษกั้น</label>
                    @if (isset($backdata))
                        <input type="number" name="paper_bar" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool"
                            placeholder="จำนวนกระดาษกั้น" value="{{ $backdata->spool }}" required>
                    @else
                        <input type="number" name="paper_bar" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool"
                            placeholder="จำนวนกระดาษกั้น" required>
                    @endif
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <div class="form-group">
                    <label for=""></label>
                    <select name="typetag_partition" class="form-control" id="typetag">
                        <option value="partition1">พลาสติก</option>
                        <option value="partition2">กระดาษ</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 align-items-end">
                <div class="form-group">
                    <label for="spool">จำนวนหลอดทั้งหมด (หลอด)</label>

                    @if (isset($backdata))
                        <input type="number" name="spool" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool"
                            placeholder="จำนวนหลอดทั้งหมด (หลอด)" value="{{ $backdata->spool }}" required>
                    @else
                        <input type="number" name="spool" oninput="setCount(this.value , 'yarnSum')"
                            onchange="setCount(this.value , 'yarnSum')" class="form-control" id="spool"
                            placeholder="จำนวนหลอดทั้งหมด (หลอด)" required>
                    @endif

                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <div class="form-group">
                    <label for=""></label>
                    <select name="typetag_spool" class="form-control" id="typetag">
                        <option value="spool_plastic">หลอดกรวย พลาสติก</option>
                        <option value="spool_paper">หลอดกรวย กระดาษ</option>
                        <option value="spoolC_plastic">หลอดทรงกระบอก พลาสติก</option>
                        <option value="spoolC_paper">หลอดทรงกระบอก กระดาษ</option>
                    </select>
                </div>
            </div>
        </div>
        <!--row-->


        <div class="line_btn">
            <button name="submit" value="cleanForm" class="btn b_order clean"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>"
                    width="15"> เคลียร์ข้อมูล</button>
            <button name="submit" value="Htrpackagecreate" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>"
                    width="17">
                ตรวจสอบ</button>
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
