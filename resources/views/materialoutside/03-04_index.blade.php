@extends('layouts.astmanufacturing')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="{{ route('material.index') }}">วัตถุดิบ</a></li>
                <li class="breadcrumb-item active">ตรวจสอบการเบิกวัตถุดิบ</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 Header">
                    <div class="col">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i>
                            ตรวจสอบการเบิกวัตถุดิบออกภายนอก</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <div class="">
                    <form method="post" action="{{ route('materialoutside.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supId">ชื่อบริษัท</label>
                                    <input type="text" name="supId" onkeyup="supplierFunction('supId')"
                                        class="form-control" id="supId" placeholder="ชื่อบริษัท">

                                    <?php $suppliershow = 'test';
                                    $supplier = App\Http\Controllers\MaterialController::supData();
                                    ?>
                                    <ul id="supp">
                                        @foreach ($supplier as $sup)
                                            <li><a
                                                    href="javascript:setsupplierFunction('supp', '{{ $sup->name }}');">{{ $sup->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="yarnType">ชนิดด้าย</label>
                                    <input type="text" name="yarnType" class="form-control" id="yarnType"
                                        placeholder="ชนิดด้าย">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imDate">วันที่</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" name="imDate" class="form-control datetimepicker-input"
                                            data-target="#reservationdate" />
                                        <div class="input-group-append" data-target="#reservationdate"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="line_btn_search">
                            <button type="submit" name="submit" value="searchwithdraw" class="btn_search"><i
                                    class="fa fa-search"></i> ค้นหา</button>
                        </div>

                    </form>


                    @if (isset($withdrawmaterial) && count($withdrawmaterial) > 0)
                        @if ($select_search == 'supId')
                            <div class="List_table">
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered table-a">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">วันที่ </th>
                                                    <th rowspan="2">บริษัท </th>
                                                    <th rowspan="2">ผู้รับวัตถุดิบ </th>
                                                    <th rowspan="2">นำไปใช้</th>
                                                    <th rowspan="2">ชนิดด้าย</th>
                                                    <th rowspan="2">LOT </th>
                                                    <th rowspan="2">จำนวนด้าย <br>(ลูก)</th>
                                                    <th colspan="2">น้ำหนักสุทธิ </th>
                                                    <th colspan="2">น้ำหนักเฉลี่ย </th>
                                                    <th rowspan="2">น้ำหนักเฉลี่ย </th>
                                                </tr>
                                                <tr>
                                                    <th>ปอนด์</th>
                                                    <th>กิโลกรัม</th>
                                                    <th>ปอนด์</th>
                                                    <th>กิโลกรัม</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($withdrawmaterial as $with1)
                                                    <tr>
                                                        <td rowspan=“10”>{{ $date = date('d/m/Y', strtotime($with1->createDate)) }}</td>
                                                        <td>{{ $with1->supplierName }}</td>
                                                        <td>{{ $with1->recipient }}</td>
                                                        <td>{{ $with1->comment }}</td>
                                                        <td>{{ $with1->yarnType }}</td>

                                                        <td>{{ $with1->lot }}</td>

                                                        <td>{{ $with1->spool }}</td>
                                                        <td>{{ $with1->weight_p_net }}</td>
                                                        <td>{{ $with1->weight_kg_net }}</td>
                                                        <td>{{ $with1->average_p }}</td>
                                                        <td>{{ $with1->average_kg }}</td>
                                                        <td>
                                                            <form method="POST"
                                                                action="{{ route('materialoutside.destroy', $with1->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-danger" type="button"
                                                                    onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button>
                                                                <button class="btn b_order" type="button"><a
                                                                        href="{{ route('materialoutside.edit', $with1->id) }}">แก้ไข</a></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--row-->
                            </div>
                            <!--List_table-->
                        @elseif($select_search == 'yarnType')
                            <div class="List_table">
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered table-a">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">วันที่ </th>
                                                    <th rowspan="2">บริษัท </th>
                                                    <th rowspan="2">ผู้รับวัตถุดิบ </th>
                                                    <th rowspan="2">นำไปใช้</th>
                                                    <th rowspan="2">ชนิดด้าย</th>
                                                    <th rowspan="2">LOT </th>
                                                    <th rowspan="2">จำนวนด้าย <br>(ลูก)</th>
                                                    <th colspan="2">น้ำหนักสุทธิ </th>
                                                    <th colspan="2">น้ำหนักเฉลี่ย </th>
                                                    <th rowspan="2">น้ำหนักเฉลี่ย </th>
                                                </tr>
                                                <tr>
                                                    <th>ปอนด์</th>
                                                    <th>กิโลกรัม</th>
                                                    <th>ปอนด์</th>
                                                    <th>กิโลกรัม</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($withdrawmaterial as $with1)
                                                    <tr>
                                                        <td rowspan=“10”>{{ $date = date('d/m/Y', strtotime($with1->createDate)) }}</td>
                                                        <td>{{ $with1->supplierName }}</td>
                                                        <td>{{ $with1->recipient }}</td>
                                                        <td>{{ $with1->comment }}</td>
                                                        <td>{{ $with1->yarnType }}</td>

                                                        <td>{{ $with1->lot }}</td>

                                                        <td>{{ $with1->spool }}</td>
                                                        <td>{{ $with1->weight_p_net }}</td>
                                                        <td>{{ $with1->weight_kg_net }}</td>
                                                        <td>{{ $with1->average_p }}</td>
                                                        <td>{{ $with1->average_kg }}</td>
                                                        <td>
                                                            <form method="POST"
                                                                action="{{ route('materialoutside.destroy', $with1->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-danger" type="button"
                                                                    onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button>
                                                                <button class="btn b_order" type="button"><a
                                                                        href="{{ route('materialoutside.edit', $with1->id) }}">แก้ไข</a></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--row-->
                            </div>
                            <!--List_table-->
                        @elseif($select_search == 'imDate')
                            <div class="List_table">
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered table-a">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">วันที่ </th>
                                                    <th rowspan="2">บริษัท </th>
                                                    <th rowspan="2">ผู้รับวัตถุดิบ </th>
                                                    <th rowspan="2">นำไปใช้</th>
                                                    <th rowspan="2">ชนิดด้าย</th>
                                                    <th rowspan="2">LOT </th>
                                                    <th rowspan="2">จำนวนด้าย <br>(ลูก)</th>
                                                    <th colspan="2">น้ำหนักสุทธิ </th>
                                                    <th colspan="2">น้ำหนักเฉลี่ย </th>
                                                    <th rowspan="2">น้ำหนักเฉลี่ย </th>
                                                </tr>
                                                <tr>
                                                    <th>ปอนด์</th>
                                                    <th>กิโลกรัม</th>
                                                    <th>ปอนด์</th>
                                                    <th>กิโลกรัม</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($withdrawmaterial as $with1)
                                                    <tr>
                                                        <td rowspan=“10”>{{ $date = date('d/m/Y', strtotime($with1->createDate)) }}</td>
                                                        <td>{{ $with1->supplierName }}</td>
                                                        <td>{{ $with1->recipient }}</td>
                                                        <td>{{ $with1->comment }}</td>
                                                        <td>{{ $with1->yarnType }}</td>

                                                        <td>{{ $with1->lot }}</td>

                                                        <td>{{ $with1->spool }}</td>
                                                        <td>{{ $with1->weight_p_net }}</td>
                                                        <td>{{ $with1->weight_kg_net }}</td>
                                                        <td>{{ $with1->average_p }}</td>
                                                        <td>{{ $with1->average_kg }}</td>
                                                        <td>
                                                            <form method="POST"
                                                                action="{{ route('materialoutside.destroy', $with1->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-danger" type="button"
                                                                    onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button>
                                                                <button class="btn b_order" type="button"><a
                                                                        href="{{ route('materialoutside.edit', $with1->id) }}">แก้ไข</a></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--row-->
                            </div>
                            <!--List_table-->
                        @else
                            <p>กรุณาใส่ ชื่อบริษัท , ชนิดด้าย หรือวันที่รับวัตถุดิบ เพื่อค้นหาข้อมูล
                            </p>
                        @endif
                    @elseif(isset($withdrawmaterial) && count($withdrawmaterial) < 1)
                        <p>ไม่พบผลการค้นหา</p>
                    @else
                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-a">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">ผู้รับวัตถุดิบ </th>
                                                <th rowspan="2">นำไปใช้</th>
                                                <th rowspan="2">บริษัท </th>
                                                <th rowspan="2">ชนิดด้าย</th>
                                                <th rowspan="2">LOT </th>
                                                <th rowspan="2">จำนวนด้าย <br>(ลูก)</th>
                                                <th colspan="2">น้ำหนักสุทธิ </th>
                                                <th colspan="2">น้ำหนักเฉลี่ย </th>
                                                <th rowspan="2">น้ำหนักเฉลี่ย </th>
                                            </tr>
                                            <tr>
                                                <th>ปอนด์</th>
                                                <th>กิโลกรัม</th>
                                                <th>ปอนด์</th>
                                                <th>กิโลกรัม</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($lastTenRecords as $with1)
                                                <tr>
                                                    <td>{{ $with1->recipient }}</td>
                                                    <td>{{ $with1->comment }}</td>
                                                    <td>{{ $with1->supplierName }}</td>
                                                    <td>{{ $with1->yarnType }}</td>
                                                    <td>{{ $with1->lot }}</td>

                                                    <td>{{ $with1->spool }}</td>
                                                    <td>{{ $with1->weight_p_net }}</td>
                                                    <td>{{ $with1->weight_kg_net }}</td>
                                                    <td>{{ $with1->average_p }}</td>
                                                    <td>{{ $with1->average_kg }}</td>
                                                    <td>
                                                        <form method="POST"
                                                            action="{{ route('materialoutside.destroy', $with1->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger" type="button"
                                                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button>
                                                            <button class="btn b_order" type="button"><a
                                                                    href="{{ route('materialoutside.edit', $with1->id) }}">แก้ไข</a></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--row-->
                        </div>
                        <!--List_table-->
                    @endif

                </div><!-- inner_content -->
            </div>
            <!--box-from-->

        </div>
        <!--content-->
    </div><!-- /.content-wrapper -->

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
            document.getElementById("supId").value = t1;
            ul = document.getElementById(t);
            ul.style.display = "none";
        }
    </script>
@endsection
