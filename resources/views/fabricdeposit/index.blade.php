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
                <form method="post" action="{{ route('fabricdeposit.store') }}" id="myForm">
                    @csrf
                    <input type="hidden" id="refId" name="refId" value="">
                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fabricStruct">ลูกค้า</label>
                                @if (isset($backdata))
                                    <input type="text" name="customerName" class="form-control" id="customerName"
                                        placeholder="ลูกค้า" value="{{ $backdata->customerName }}">
                                @else
                                    <input type="text" name="customerName" class="form-control" id="customerName"
                                        placeholder="ลูกค้า">
                                @endif
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
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
                        </div> --}}
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
                @if (isset($importorder) && count($importorder) > 0)
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a" style="width:100%">
                                <thead style="position: sticky;top: 0;background-color:powderblue;">
                                    <tr>
                                        <th rowspan="2">วันที่ </th>
                                        <th rowspan="2">ผู้สั่ง </th>
                                        {{-- <th>ผู้รับ</th> --}}
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
                                    @for ($i = 0; $i < $c; $i++)
                                        <?php $check = 0; ?>
                                        @for ($j = 0; $j < $a; $j++)
                                            <?php $check = 0; ?>
                                            <?php if ($importorder[$i]->refId === $sumfabricoutdepo[$j]->orderId) { ?>
                                            <?php $check = 1; ?>
                                            <tr>
                                                {{-- <td>{{ $importorder[$i]->lastDate }}</td> --}}
                                                <td>{{ $date = date('d/m/Y', strtotime($importorder[$i]->lastDate)) }}
                                                </td>
                                                <td>{{ $importorder[$i]->customerName }}
                                                    {{-- //
                                            {{ $importorder[$i]->refId }}//
                                            {{ $sumfabricoutdepo[$j]->orderId }} --}}
                                                </td>
                                                {{-- <td>{{ $importorder[$i]->receiveName }}</td> --}}
                                                <td>{{ $importorder[$i]->fabricStruct }}</td>
                                                <td>{{ $importorder[$i]->foldCount }}</td>
                                                <td>{{ $importorder[$i]->sumYardSum }}</td>

                                                <td>{{ $sumfabricoutdepo[$j]->foldCount }}</td>
                                                <td>{{ $sumfabricoutdepo[$j]->sumYardSum }}</td>

                                                <td>{{ $importorder[$i]->foldCount - $sumfabricoutdepo[$j]->foldCount }}
                                                </td>
                                                <td>{{ $importorder[$i]->sumYardSum - $sumfabricoutdepo[$j]->sumYardSum }}
                                                </td>

                                                <td>
                                                    <form method="post" action="{{ route('fabricoutdeposit.store') }}"
                                                        id="myForm">
                                                        @csrf
                                                        <input type="hidden" name="orderId"
                                                            value="{{ $importorder[$i]->refId }}">
                                                        <input type="hidden" name="customerName"
                                                            value="{{ $importorder[$i]->customerName }}                                                        ">
                                                        <input type="hidden" name="fabricStruct"
                                                            value="{{ $importorder[$i]->fabricStruct }}">
                                                        <input type="hidden" name="fabricPattern"
                                                            value="{{ $importorder[$i]->fabricPattern }}">
                                                        <input type="hidden" name="fabricW"
                                                            value="{{ $importorder[$i]->fabricW }}">

                                                        <input type="hidden" name="no"
                                                            value="{{ $importorder[$i]->no }}">
                                                        <input type="hidden" name="refId"
                                                            value="{{ $importorder[$i]->refId }}">

                                                        <input type="hidden" name="receiveName"
                                                            value="{{ $importorder[$i]->receiveName }}">


                                                        <button name="submit" value="generateByOrder"
                                                            class="btn b_save">
                                                            {{-- <img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17"> --}}
                                                            จัดส่งสินค้า</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form method="POST"
                                                        action="{{ route('fabricdeposit.destroy', $importorder[$i]->no) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div style="margin: 0.2rem">
                                                            <button class="btn btn-danger" type="button"
                                                                style="width:90%;margin: 0.2rem;font-size: 0.8rem"
                                                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                            {{-- <button class="btn b_order" type="button"><a
                                                        href="{{ route('fabricoutdeposit.create') }}">test</a></button> --}}
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @break

                                        <?php } ?>
                                    @endfor
                                    <?php if ($check != 1) { ?>
                                    <tr>
                                        {{-- <td>{{ $importorder[$i]->lastDate }}</td> --}}
                                        <td>{{ $date = date('d/m/Y', strtotime($importorder[$i]->lastDate)) }}</td>
                                        <td>{{ $importorder[$i]->customerName }}</td>
                                        {{-- <td>{{ $importorder[$i]->receiveName }}</td> --}}
                                        <td>{{ $importorder[$i]->fabricStruct }}</td>
                                        <td>{{ $importorder[$i]->foldCount }}</td>
                                        <td>{{ $importorder[$i]->sumYardSum }}</td>

                                        <td></td>
                                        <td></td>

                                        <td>{{ $importorder[$i]->foldCount }}
                                        </td>
                                        <td>{{ $importorder[$i]->sumYardSum }}
                                        </td>

                                        <td>
                                            <form method="post" action="{{ route('fabricoutdeposit.store') }}"
                                                id="myForm">
                                                @csrf
                                                <input type="hidden" name="orderId"
                                                    value="{{ $importorder[$i]->refId }}">
                                                <input type="hidden" name="customerName"
                                                    value="{{ $importorder[$i]->customerName }}                                                        ">
                                                <input type="hidden" name="fabricStruct"
                                                    value="{{ $importorder[$i]->fabricStruct }}">
                                                <input type="hidden" name="fabricPattern"
                                                    value="{{ $importorder[$i]->fabricPattern }}">
                                                <input type="hidden" name="fabricW"
                                                    value="{{ $importorder[$i]->fabricW }}">

                                                <input type="hidden" name="no"
                                                    value="{{ $importorder[$i]->no }}">
                                                <input type="hidden" name="refId"
                                                    value="{{ $importorder[$i]->refId }}">

                                                <input type="hidden" name="receiveName"
                                                    value="{{ $importorder[$i]->receiveName }}">


                                                <button name="submit" value="generateByOrder" class="btn b_save">
                                                    {{-- <img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17"> --}}
                                                    จัดส่งสินค้า</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('fabricdeposit.destroy', $importorder[$i]->no) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div style="margin: 0.2rem">
                                                    <button class="btn btn-danger" type="button"
                                                        style="width:90%;margin: 0.2rem;font-size: 0.8rem"
                                                        onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                    {{-- <button class="btn b_order" type="button"><a
                                                    href="{{ route('fabricoutdeposit.create') }}">test</a></button> --}}
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                @endfor

                            </tbody>
                            </thead>
                        </table>
                    </div>
                @elseif(isset($importorder) && count($importorder) < 1)
                    <p>ไม่พบผลการค้นหา</p>
                @else
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a" style="width:100%">
                                <thead style="position: sticky;top: 0;background-color:powderblue;">
                                    <tr>
                                        <th rowspan="2">วันที่ </th>
                                        <th rowspan="2">ผู้สั่ง </th>
                                        {{-- <th>ผู้รับ</th> --}}
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
                                    @for ($i = 0; $i < $c; $i++)
                                        <?php $check = 0; ?>
                                        @for ($j = 0; $j < $a; $j++)
                                            <?php $check = 0; ?>
                                            <?php if ($sumfabricdepo[$i]->refId === $sumfabricoutdepo[$j]->orderId) { ?>
                                            <?php $check = 1; ?>
                                            <tr>
                                                {{-- <td>{{ $sumfabricdepo[$i]->lastDate }}</td> --}}
                                                <td>{{ $date = date('d/m/Y', strtotime($sumfabricdepo[$i]->lastDate)) }}
                                                </td>
                                                <td>{{ $sumfabricdepo[$i]->customerName }}
                                                    {{-- //
                                                {{ $sumfabricdepo[$i]->refId }}//
                                                {{ $sumfabricoutdepo[$j]->orderId }} --}}
                                                </td>
                                                {{-- <td>{{ $sumfabricdepo[$i]->receiveName }}</td> --}}
                                                <td>{{ $sumfabricdepo[$i]->fabricStruct }}</td>
                                                <td>{{ $sumfabricdepo[$i]->foldCount }}</td>
                                                <td>{{ $sumfabricdepo[$i]->sumYardSum }}</td>

                                                <td>{{ $sumfabricoutdepo[$j]->foldCount }}</td>
                                                <td>{{ $sumfabricoutdepo[$j]->sumYardSum }}</td>

                                                <td>{{ $sumfabricdepo[$i]->foldCount - $sumfabricoutdepo[$j]->foldCount }}
                                                </td>
                                                <td>{{ $sumfabricdepo[$i]->sumYardSum - $sumfabricoutdepo[$j]->sumYardSum }}
                                                </td>

                                                <td>
                                                    <form method="post"
                                                        action="{{ route('fabricoutdeposit.store') }}"
                                                        id="myForm">
                                                        @csrf
                                                        <input type="hidden" name="orderId"
                                                            value="{{ $sumfabricdepo[$i]->refId }}">
                                                        <input type="hidden" name="customerName"
                                                            value="{{ $sumfabricdepo[$i]->customerName }}                                                        ">
                                                        <input type="hidden" name="fabricStruct"
                                                            value="{{ $sumfabricdepo[$i]->fabricStruct }}">
                                                        <input type="hidden" name="fabricPattern"
                                                            value="{{ $sumfabricdepo[$i]->fabricPattern }}">
                                                        <input type="hidden" name="fabricW"
                                                            value="{{ $sumfabricdepo[$i]->fabricW }}">

                                                        <input type="hidden" name="no"
                                                            value="{{ $sumfabricdepo[$i]->no }}">
                                                        <input type="hidden" name="refId"
                                                            value="{{ $sumfabricdepo[$i]->refId }}">

                                                        <input type="hidden" name="receiveName"
                                                            value="{{ $sumfabricdepo[$i]->receiveName }}">


                                                        <button name="submit" value="generateByOrder"
                                                            class="btn b_save">
                                                            {{-- <img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17"> --}}
                                                            จัดส่งสินค้า</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form method="POST"
                                                        action="{{ route('fabricdeposit.destroy', $sumfabricdepo[$i]->no) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div style="margin: 0.2rem">
                                                            <button class="btn btn-danger" type="button"
                                                                style="width:90%;margin: 0.2rem;font-size: 0.8rem"
                                                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                            {{-- <button class="btn b_order" type="button"><a
                                                            href="{{ route('fabricoutdeposit.create') }}">test</a></button> --}}
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @break

                                        <?php } ?>
                                    @endfor
                                    <?php if ($check != 1) { ?>
                                    <tr>
                                        {{-- <td>{{ $sumfabricdepo[$i]->lastDate }}</td> --}}
                                        <td>{{ $date = date('d/m/Y', strtotime($sumfabricdepo[$i]->lastDate)) }}
                                        </td>
                                        <td>{{ $sumfabricdepo[$i]->customerName }}</td>
                                        {{-- <td>{{ $sumfabricdepo[$i]->receiveName }}</td> --}}
                                        <td>{{ $sumfabricdepo[$i]->fabricStruct }}</td>
                                        <td>{{ $sumfabricdepo[$i]->foldCount }}</td>
                                        <td>{{ $sumfabricdepo[$i]->sumYardSum }}</td>

                                        <td></td>
                                        <td></td>

                                        <td>{{ $sumfabricdepo[$i]->foldCount }}
                                        </td>
                                        <td>{{ $sumfabricdepo[$i]->sumYardSum }}
                                        </td>

                                        <td>
                                            <form method="post" action="{{ route('fabricoutdeposit.store') }}"
                                                id="myForm">
                                                @csrf
                                                <input type="hidden" name="orderId"
                                                    value="{{ $sumfabricdepo[$i]->refId }}">
                                                <input type="hidden" name="customerName"
                                                    value="{{ $sumfabricdepo[$i]->customerName }}                                                        ">
                                                <input type="hidden" name="fabricStruct"
                                                    value="{{ $sumfabricdepo[$i]->fabricStruct }}">
                                                <input type="hidden" name="fabricPattern"
                                                    value="{{ $sumfabricdepo[$i]->fabricPattern }}">
                                                <input type="hidden" name="fabricW"
                                                    value="{{ $sumfabricdepo[$i]->fabricW }}">

                                                <input type="hidden" name="no"
                                                    value="{{ $sumfabricdepo[$i]->no }}">
                                                <input type="hidden" name="refId"
                                                    value="{{ $sumfabricdepo[$i]->refId }}">

                                                <input type="hidden" name="receiveName"
                                                    value="{{ $sumfabricdepo[$i]->receiveName }}">


                                                <button name="submit" value="generateByOrder"
                                                    class="btn b_save">
                                                    {{-- <img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17"> --}}
                                                    จัดส่งสินค้า</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('fabricdeposit.destroy', $sumfabricdepo[$i]->no) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div style="margin: 0.2rem">
                                                    <button class="btn btn-danger" type="button"
                                                        style="width:90%;margin: 0.2rem;font-size: 0.8rem"
                                                        onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button><br>
                                                    {{-- <button class="btn b_order" type="button"><a
                                                        href="{{ route('fabricoutdeposit.create') }}">test</a></button> --}}
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                @endfor

                            </tbody>
                            </thead>
                        </table>
                    </div>
        @endif
        {{-- ผ้า {{ $sumStockfabric[0]->fabricStruct }}
                <br> หน้ากว้าง
                {{ $sumStockfabric[0]->fabricW }}
                <br> จำนวนพับ
                {{ $sumStockfabric[0]->foldCount }}
                <br> จำนวนหลา
                {{ $sumStockfabric[0]->sumYardSum }} --}}

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
