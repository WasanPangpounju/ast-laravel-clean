@extends('layouts.astmanufacturing')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="/stockfabric/">คลังสินค้า</a>
                </li>
                <li class="breadcrumb-item active">ตรวจสอบบันทึกรายการ</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ตรวจสอบบันทึกรายการ</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> ตรวจสอบบันทึกรายการ</h2>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn b_order" type="button"
                                style="width: 50%;margin:0.5rem;background-color: #ebd575;"><a
                                    href="{{ route('inventory.index') }}">รายการสินค้าสั่งผลิต</a></button>
                            <button class="btn b_order" type="button"
                                style="width: 50%;margin:0.5rem;background-color: #aca06e;"><a
                                    href="{{ route('fabricout.index') }}">รายการสินค้าคงคลัง</a></button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn b_order" type="button"
                                style="width: 50%;margin:0.5rem;background-color: #1bccbd;"><a
                                    href="{{ route('inventory.create') }}">บันทึกรายการสินค้า
                                </a></button>
                            <button class="btn b_order" type="button"
                                style="width: 50%;margin:0.5rem;background-color: #8a8a8a;"><a
                                    href="{{ route('fabricout.create') }}">บันทึกรายการจัดส่งสินค้า</a></button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn b_order" type="button"
                                style="width: 55%;margin:0.5rem;background-color: #ec9c06;"><a
                                    href="{{ route('stockfabric.index') }}">รายการผ้าผลิตแล้วในสต๊อก</a></button>
                            <button class="btn b_order" type="button"
                                style="width: 55%;margin:0.5rem;background-color: rgb(175, 163, 110);"><a
                                    href="{{ route('fabricdeposit.index') }}">รายการผ้าผลิตฝากจัดเก็บ</a></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-9 table-responsive">
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
                                        <td> {{ $date = date('d/m/Y', strtotime($allfabricout[$i]->lastCreateDate)) }}</td>
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
                                                <div style="margin: 0.2rem">
                                                    <button class="btn btn-danger" type="button" style="margin: 0.2rem"
                                                        onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                    <button class="btn b_order" type="button" style="margin: 0.2rem"><a
                                                            href="{{-- route('fabriccheck.edit', $allfabricout[$i]->refId) --}}">แก้ไข</a></button>
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
@endsection
