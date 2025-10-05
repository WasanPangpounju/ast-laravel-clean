@extends('layouts.astmanufacturing')

@section('content')
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" /> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
        rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="/stockfabric/">คลังสินค้า</a>
                </li>
                <li class="breadcrumb-item active">ตรวจสอบคีย์ผ้าเข้าสต็อก</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ตรวจสอบคีย์ผ้าเข้าสต็อก</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> ตรวจสอบคีย์ผ้าเข้าสต็อก</h2>
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn b_order" type="button"
                                style="width: 60%;margin:0.5rem;background-color: #ebd575;"><a
                                    href="{{ route('inventory.index') }}">ออร์เดอร์ลูกค้า</a></button>
                            <button class="btn b_order" type="button"
                                style="width: 60%;margin:0.5rem;background-color: #aca06e;"><a
                                    href="{{ route('fabricout.index') }}">พิมพ์บิลส่งของ</a></button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn b_order" type="button"
                                style="width: 70%;margin:0.5rem;background-color: #1bccbd;"><a
                                    href="{{ route('inventory.create') }}">คีย์ผ้าเข้าสต็อก
                                </a></button>
                            <button class="btn b_order" type="button"
                                style="width: 70%;margin:0.5rem;background-color: #8a8a8a;"><a
                                    href="{{ route('fabricout.create') }}">เปิดบิลผ้า</a></button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn b_order" type="button"
                                style="width: 55%;margin:0.5rem;background-color: #ec9c06;"><a
                                    href="{{ route('stockfabric.index') }}">สต็อกผ้า</a></button>
                            <button class="btn b_order" type="button"
                                style="width: 55%;margin:0.5rem;background-color: rgb(175, 163, 110);"><a
                                    href="{{ route('fabricdeposit.index') }}">สต็อกผ้าฝากจัดเก็บ</a></button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn b_order" type="button"
                                style="width: 70%;margin:0.5rem;background-color: #ec9c06;"><a
                                    href="{{ route('fabriccheck.index') }}">ตรวจสอบคีย์ผ้าเข้าสต็อก</a></button>
                        </div>
                    </div>
                </div>

                <form method="post" action="{{ route('fabriccheck.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fabricStruct">โครงสร้างผ้า </label>
                                @if (isset($backdata))
                                    <input type="text" name="fabricStruct" onkeyup="supplierFunction('fabricStruct')"
                                        class="form-control" id="fabricStruct" placeholder="โครงสร้างผ้า"
                                        value="{{ $backdata->fabricStruct }}">
                                @else
                                    <input type="text" name="fabricStruct" onkeyup="supplierFunction('fabricStruct')"
                                        class="form-control" id="fabricStruct" placeholder="โครงสร้างผ้า" value="">
                                @endif



                                <ul id="supp">
                                    @foreach ($stockFabricStruct as $stockFS)
                                        <li><a
                                                href="javascript:setsupplierFunction('supp', '{{ $stockFS->fabricStruct }}' , '{{ $stockFS->fabricPattern }}' , '{{ $stockFS->fabricW }}');">{{ $stockFS->fabricStruct }}
                                                {{ $stockFS->fabricPattern }} หน้ากว้าง {{ $stockFS->fabricW }} '</a>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="line_btn_search">
                        <button type="submit" name="submit" value="searchImport" class="btn_search"><i
                                class="fa fa-search"></i> ค้นหา</button>
                    </div>
                </form>
                @if (isset($importorder) && count($importorder) > 0)
                    <div class="row">
                        {{-- <div class="col-1"></div> --}}
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a" style="width:100%">
                                <thead style="position: sticky;top: 0;background-color:powderblue;">
                                    <tr>
                                        <th rowspan="2">วันที่ </th>
                                        <th rowspan="2">โครงสร้างผ้า </th>
                                        <th rowspan="2">ลายผ้า </th>
                                        <th rowspan="2">หน้ากว้าง</th>
                                        <th colspan="2">ผลิตแล้ว </th>
                                        <th rowspan="2">ลบ/แก้ไข</th>
                                        {{-- <th colspan="2">ใช้ไป </th>
                                <th colspan="2">คงเหลือ</th> --}}
                                    </tr>
                                    <tr>
                                        <th>จำนวนพับ </th>
                                        <th>จำนวนหลา </th>
                                        {{-- <th>จำนวนพับ </th>
                                <th>จำนวนหลา </th>
                                <th>จำนวนพับ </th>
                                <th>จำนวนหลา </th> --}}
                                    </tr>
                                <tbody style="text-align: right;">
                                    <?php
                                    // $c = $allfabricout->count();
                                    // $a = $sumFabricout->count();
                                    ?>
                                    {{-- @for ($i = 0; $i < $c; $i++) --}}
                                    @foreach ($importorder as $order)
                                        <tr>
                                            <td> {{ $date = date('d/m/Y', strtotime($order->lastCreateDate)) }}
                                            </td>
                                            {{-- {{ $date = date('d/m/Y', strtotime($allfabricout[$i]->lastCreateDate)) }} --}}
                                            <td>{{ $order->fabricStruct }}</td>
                                            <td>{{ $order->fabricPattern }}</td>
                                            <td>{{ $order->fabricW }}</td>
                                            <td>{{ $order->foldCount }}</td>
                                            <td>{{ $order->sumYardSum }}</td>
                                            <td>
                                                <form method="POST"
                                                    action="{{ route('fabriccheck.destroy', $order->refId) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" value="{{ $allfabricout[$i]->refId }}">
                                                    <div style="margin: 0.2rem">
                                                        <button class="btn btn-danger" type="button"
                                                            style="margin: 0.2rem"
                                                            onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                        <button class="btn b_order" type="button"
                                                            style="margin: 0.2rem"><a
                                                                href="{{ route('fabriccheck.edit', $order->refId) }}">แก้ไข</a></button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </thead>
                            </table>
                        </div>
                        {{-- ผ้า {{ $allfabricout[0]->fabricStruct }}
            <br> หน้ากว้าง
            {{ $allfabricout[0]->fabricW }}
            <br> จำนวนพับ
            {{ $allfabricout[0]->foldCount }}
            <br> จำนวนหลา
            {{ $allfabricout[0]->sumYardSum }} --}}

                    </div>
                @elseif(isset($importorder) && count($importorder) < 1)
                    <p>ไม่พบผลการค้นหา</p>
                @else
                    <div class="row">
                        {{-- <div class="col-1"></div> --}}
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a" style="width:100%">
                                <thead style="position: sticky;top: 0;background-color:powderblue;">
                                    <tr>
                                        <th rowspan="2">วันที่ </th>
                                        <th rowspan="2">โครงสร้างผ้า </th>
                                        <th rowspan="2">ลายผ้า </th>
                                        <th rowspan="2">หน้ากว้าง</th>
                                        <th colspan="2">ผลิตแล้ว </th>
                                        <th rowspan="2">ลบ/แก้ไข</th>
                                        {{-- <th colspan="2">ใช้ไป </th>
                                    <th colspan="2">คงเหลือ</th> --}}
                                    </tr>
                                    <tr>
                                        <th>จำนวนพับ </th>
                                        <th>จำนวนหลา </th>
                                        {{-- <th>จำนวนพับ </th>
                                    <th>จำนวนหลา </th>
                                    <th>จำนวนพับ </th>
                                    <th>จำนวนหลา </th> --}}
                                    </tr>
                                <tbody style="text-align: right;">
                                    <?php
                                    $c = $allfabricout->count();
                                    // $a = $sumFabricout->count();
                                    ?>
                                    @for ($i = 0; $i < $c; $i++)
                                        <tr>
                                            <td> {{ $date = date('d/m/Y', strtotime($allfabricout[$i]->lastCreateDate)) }}
                                            </td>
                                            {{-- {{ $date = date('d/m/Y', strtotime($allfabricout[$i]->lastCreateDate)) }} --}}
                                            <td>{{ $allfabricout[$i]->fabricStruct }}</td>
                                            <td>{{ $allfabricout[$i]->fabricPattern }}</td>
                                            <td>{{ $allfabricout[$i]->fabricW }}</td>
                                            <td>{{ $allfabricout[$i]->foldCount }}</td>
                                            <td>{{ $allfabricout[$i]->sumYardSum }}</td>
                                            <td>
                                                <form method="POST"
                                                    action="{{ route('fabriccheck.destroy', $allfabricout[$i]->refId) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" value="{{ $allfabricout[$i]->refId }}">
                                                    <div style="margin: 0.2rem">
                                                        <button class="btn btn-danger" type="button"
                                                            style="margin: 0.2rem"
                                                            onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                        <button class="btn b_order" type="button"
                                                            style="margin: 0.2rem"><a
                                                                href="{{ route('fabriccheck.edit', $allfabricout[$i]->refId) }}">แก้ไข</a></button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                                </thead>
                            </table>
                        </div>
                        {{-- ผ้า {{ $allfabricout[0]->fabricStruct }}
                <br> หน้ากว้าง
                {{ $allfabricout[0]->fabricW }}
                <br> จำนวนพับ
                {{ $allfabricout[0]->foldCount }}
                <br> จำนวนหลา
                {{ $allfabricout[0]->sumYardSum }} --}}

                    </div>
                @endif
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
            // alert(input );
            filter = input.value.toUpperCase();
            filter = filter.replace(/\s/g, "");

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
                txtValue = txtValue.replace(/\s/g, "");

                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }


        // function setsupplierFunction(t, t1, t2) {
        function setsupplierFunction(t, t1) {
            document.getElementById("fabricStruct").value = t1;

            // document.getElementById("fabricPattern").value = t2;

            ul = document.getElementById(t);
            //ul.style.display = "none";  
            li = ul.getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

        }

        // function supplierFunction(id) {
        //     var input, filter, ul, li, a, i, txtValue;

        //     input = document.getElementById('orderId');
        //     filter = input.value.toUpperCase();
        //     ul = document.getElementById("supp");
        //     li = ul.getElementsByTagName("li");
        //     for (i = 0; i < li.length; i++) {
        //         if (filter == "") {
        //             for (i = 0; i < li.length; i++) {
        //                 li[i].style.display = "none";
        //             }
        //             break;
        //         }

        //         a = li[i].getElementsByTagName("a")[0];
        //         txtValue = a.textContent || a.innerText;
        //         if (txtValue.toUpperCase().indexOf(filter) > -1) {
        //             li[i].style.display = "";
        //         } else {
        //             li[i].style.display = "none";
        //         }
        //     }
        // }


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
@endsection
