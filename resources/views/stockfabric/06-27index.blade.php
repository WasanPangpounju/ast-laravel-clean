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
                <li class="breadcrumb-item active">รายการสต็อกผ้า</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> รายการสต็อกผ้า</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> รายการสต็อกผ้า</h2>
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
                <form method="post" action="{{ route('stockfabric.store') }}" id="myForm">
                    @csrf
                    <input type="hidden" id="refId" name="refId" value="">
                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fabricStruct">โครงสร้างผ้า</label>
                                @if (isset($backdata))
                                    <input type="text" name="fabricStruct" class="form-control" id="fabricStruct"
                                        placeholder="โครงสร้างผ้า" value="{{ $backdata->fabricStruct }}">
                                @else
                                    <input type="text" name="fabricStruct" class="form-control" id="fabricStruct"
                                        placeholder="โครงสร้างผ้า">
                                @endif
                            </div>
                        </div>
                        <!--row-->
                        <div class="col-md-2">
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
                <!--row-->
                @if (isset($importorder) && count($importorder) > 0)
                <div class="row">
                    {{-- <div class="col-2"></div> --}}
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered table-a" style="width:100%">
                            <thead style="position: sticky;top: 0;background-color:powderblue;">
                                <tr>
                                    <th rowspan="2">ลูกค้า </th>
                                    <th rowspan="2">โครงสร้างผ้า </th>
                                    <th rowspan="2">ลายผ้า </th>
                                    <th rowspan="2">หน้ากว้าง</th>
                                    <th colspan="2">ผลิตแล้ว </th>
                                    <th colspan="2">ใช้ไป </th>
                                    <th colspan="2">คงเหลือ</th>
                                </tr>
                                <tr>
                                    <th>จำนวนพับ </th>
                                    <th>จำนวนหลา </th>
                                    <th>จำนวนพับ </th>
                                    <th>จำนวนหลา </th>
                                    <th>จำนวนพับ </th>
                                    <th>จำนวนหลา </th>
                                </tr>
                            <tbody style="text-align: right;">
                                <?php
                                $c = $importorder->count();
                                $a = $sumFabricout->count();
                                ?>
                                @for ($i = 0; $i < $c; $i++)
                                    @for ($j = 0; $j < $a; $j++)
                                        <?php $check = 0; ?>
                                        <?php if ($importorder[$i]->fabricStruct === $sumFabricout[$j]->fabricStruct) { ?>
                                        <?php $check = 1; ?>
                                        <tr>
                                            {{-- <td>{{ $date = date('d/m/Y', strtotime($importorder[$i]->createDate)) }}</td> --}}
                                            <td></td>
                                            <td>{{ $importorder[$i]->fabricStruct }}</td>
                                            <td>{{ $importorder[$i]->fabricPattern }}</td>
                                            <td>{{ $importorder[$i]->fabricW }}</td>
                                            <td>{{ $importorder[$i]->foldCount }}</td>
                                            <td>{{ $importorder[$i]->sumYardSum }}</td>
                                            <td>{{ $sumFabricout[$j]->foldCount }}</td>
                                            <td>{{ $sumFabricout[$j]->sumYardSum }}</td>
                                            <td>{{ $importorder[$i]->foldCount - $sumFabricout[$j]->foldCount }}
                                            </td>
                                            <td>{{ $importorder[$i]->sumYardSum - $sumFabricout[$j]->sumYardSum }}
                                            </td>
                                        </tr>
                                    @break

                                    <?php } ?>
                                @endfor
                                <?php if ($check != 1) { ?>
                                <tr>
                                    {{-- <td>{{ $date = date('d/m/Y', strtotime($importorder[$i]->createDate)) }}</td> --}}
                                    <td></td>
                                    <td>{{ $importorder[$i]->fabricStruct }}</td>
                                    <td>{{ $importorder[$i]->fabricPattern }}</td>
                                    <td>{{ $importorder[$i]->fabricW }}</td>
                                    <td>{{ $importorder[$i]->foldCount }}</td>
                                    <td>{{ $importorder[$i]->sumYardSum }}</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $importorder[$i]->foldCount }}</td>
                                    <td>{{ $importorder[$i]->sumYardSum }}</td>
                                </tr>
                                <?php } ?>
                            @endfor
                        </tbody>
                        </thead>
                    </table>
                </div>
                {{-- ผ้า {{ $sumStockfabric[0]->fabricStruct }}
            <br> หน้ากว้าง
            {{ $sumStockfabric[0]->fabricW }}
            <br> จำนวนพับ
            {{ $sumStockfabric[0]->foldCount }}
            <br> จำนวนหลา
            {{ $sumStockfabric[0]->sumYardSum }} --}}

            </div>
                @elseif(isset($importorder) && count($importorder) < 1)
                    <p>ไม่พบผลการค้นหา</p>
                @else
                    <div class="row">
                        {{-- <div class="col-2"></div> --}}
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a" style="width:100%">
                                <thead style="position: sticky;top: 0;background-color:powderblue;">
                                    <tr>
                                        <th rowspan="2">ลูกค้า </th>
                                        <th rowspan="2">โครงสร้างผ้า </th>
                                        <th rowspan="2">ลายผ้า </th>
                                        <th rowspan="2">หน้ากว้าง</th>
                                        <th colspan="2">ผลิตแล้ว </th>
                                        <th colspan="2">ใช้ไป </th>
                                        <th colspan="2">คงเหลือ</th>
                                    </tr>
                                    <tr>
                                        <th>จำนวนพับ </th>
                                        <th>จำนวนหลา </th>
                                        <th>จำนวนพับ </th>
                                        <th>จำนวนหลา </th>
                                        <th>จำนวนพับ </th>
                                        <th>จำนวนหลา </th>
                                    </tr>
                                <tbody style="text-align: right;">
                                    <?php
                                    $c = $sumStockfabric->count();
                                    $a = $sumFabricout->count();
                                    ?>
                                    @for ($i = 0; $i < $c; $i++)
                                        @for ($j = 0; $j < $a; $j++)
                                            <?php $check = 0; ?>
                                            <?php if ($sumStockfabric[$i]->fabricStruct === $sumFabricout[$j]->fabricStruct) { ?>
                                            <?php $check = 1; ?>
                                            <tr>
                                                {{-- <td>{{ $date = date('d/m/Y', strtotime($sumStockfabric[$i]->createDate)) }}</td> --}}
                                                <td></td>
                                                <td>{{ $sumStockfabric[$i]->fabricStruct }}</td>
                                                <td>{{ $sumStockfabric[$i]->fabricPattern }}</td>
                                                <td>{{ $sumStockfabric[$i]->fabricW }}</td>
                                                <td>{{ $sumStockfabric[$i]->foldCount }}</td>
                                                <td>{{ $sumStockfabric[$i]->sumYardSum }}</td>
                                                <td>{{ $sumFabricout[$j]->foldCount }}</td>
                                                <td>{{ $sumFabricout[$j]->sumYardSum }}</td>
                                                <td>{{ $sumStockfabric[$i]->foldCount - $sumFabricout[$j]->foldCount }}
                                                </td>
                                                <td>{{ $sumStockfabric[$i]->sumYardSum - $sumFabricout[$j]->sumYardSum }}
                                                </td>
                                            </tr>
                                        @break

                                        <?php } ?>
                                    @endfor
                                    <?php if ($check != 1) { ?>
                                    <tr>
                                        {{-- <td>{{ $date = date('d/m/Y', strtotime($sumStockfabric[$i]->createDate)) }}</td> --}}
                                        <td></td>
                                        <td>{{ $sumStockfabric[$i]->fabricStruct }}</td>
                                        <td>{{ $sumStockfabric[$i]->fabricPattern }}</td>
                                        <td>{{ $sumStockfabric[$i]->fabricW }}</td>
                                        <td>{{ $sumStockfabric[$i]->foldCount }}</td>
                                        <td>{{ $sumStockfabric[$i]->sumYardSum }}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $sumStockfabric[$i]->foldCount }}</td>
                                        <td>{{ $sumStockfabric[$i]->sumYardSum }}</td>
                                    </tr>
                                    <?php } ?>
                                @endfor
                            </tbody>
                            </thead>
                        </table>
                    </div>
                    {{-- ผ้า {{ $sumStockfabric[0]->fabricStruct }}
                <br> หน้ากว้าง
                {{ $sumStockfabric[0]->fabricW }}
                <br> จำนวนพับ
                {{ $sumStockfabric[0]->foldCount }}
                <br> จำนวนหลา
                {{ $sumStockfabric[0]->sumYardSum }} --}}

                </div>
            @endif
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
