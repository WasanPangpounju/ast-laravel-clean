@extends('layouts.astmanufacturing')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="/inventory/">คลังสินค้า</a>
                </li>
                <li class="breadcrumb-item active">ออร์เดอร์ลูกค้า</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ออร์เดอร์ลูกค้า</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> ออร์เดอร์ลูกค้า</h2>
                <form method="post" action="{{ route('inventory.store') }}" id="myForm">
                    @csrf
                    <input type="hidden" id="refId" name="refId" value="">
                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
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
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="customerName">ลูกค้า</label>
                                @if (isset($backdata))
                                    <input type="text" name="customerName" class="form-control" id="customerName"
                                        placeholder="ลูกค้า" value="{{ $backdata->customerName }}">
                                @else
                                    <input type="text" name="customerName" class="form-control" id="customerName"
                                        placeholder="ลูกค้า">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="orderId">เลขที่ใบสั่งซื้อ</label>

                                @if (isset($backdata))
                                    <input type="text" name="orderId" class="form-control" id="orderId"
                                        placeholder="เลขที่ใบสั่งซื้อ" value="{{ $backdata->orderId }}">
                                @else
                                    <input type="text" name="orderId" onkeyup="supplierFunction('orderId')"
                                        class="form-control" id="orderId" placeholder="เลขที่ใบสั่งซื้อ">
                                @endif

                                <ul id="supp">
                                    @foreach ($orders as $order)
                                        <li><a
                                                href="javascript:setOrderIdFunction('orderId', '{{ $order->purchaseOrder }}' ,'{{ $order->fabricId }}' , '{{ $order->fabricStructure }}' , '{{ $order->id }}');">
                                                {{ $order->purchaseOrder }} {{ $order->customerName }}
                                                {{ $order->fabricId }} : {{ $order->orderSumYard }} หลา</a>
                                        </li>
                                    @endforeach
                                </ul>

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
                                <div class="input-group-append" data-target="#reservationdate"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
            </div>
        </div>
        <!--row-->

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fabricId">รหัสผ้า </label>
                    @if (isset($backdata))
                        <input type="text" name="fabricId" class="form-control" id="fabricId" placeholder="รหัสผ้า"
                            value="{{ $backdata->fabricId }}">
                    @else
                        <input type="text" name="fabricId" class="form-control" id="fabricId"
                            placeholder="รหัสผ้า">
                    @endif

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fabricStruct">โครงสร้างผ้า </label>
                    @if (isset($backdata))
                        <input type="text" name="fabricStruct" class="form-control" id="fabricStruct"
                            placeholder="โครงสร้างผ้า" value="{{ $backdata->fabricStruct }}">
                    @else
                        <input type="text" name="fabricStruct" class="form-control" id="fabricStruct"
                            placeholder="โครงสร้างผ้า">
                    @endif

                </div>
            </div>
        </div>
        <!--row-->
        <div class="line_btn">
            <button name="submit" value="cleanForm" class="btn b_order clean"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>"
                    width="15"> เคลียร์ข้อมูล</button>
            <button name="submit" value="search" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
                ค้นหา</button>
        </div>

        </form>
        <div class="List_table">
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-a">
                        <thead style="position: sticky;top: 0">
                            <tr>
                                <th rowspan="2">วันที่ </th>
                                <th rowspan="2">ลูกค้า </th>
                                <th rowspan="2">รหัสผ้า</th>
                                <th rowspan="2">โครงสร้างผ้า</th>
                                <th rowspan="2">ลายผ้า</th>
                                <th rowspan="2">หน้ากว้าง</th>
                                <th rowspan="2">จำนวน Order (หลา) </th>
                                <th colspan="2">จัดส่งแล้ว </th>
                                <th rowspan="2">คงค้าง</th>
                                <th rowspan="2">รายละเอียด </th>
                                <th rowspan="2">จัดส่ง </th>
                            </tr>
                            <tr>
                                <th rowspan="2">หลา </th>
                                <th rowspan="2">พับ </th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($orders as $withorders1)
                                <?php $check = 0; ?>
                                {{-- {{ $date = date('d/m/Y', strtotime($withorders1->createDate)) }} --}}
                                @foreach ($inventorydata as $inventorySum)
                                    {{-- {{ $withorders1->id }}::{{ $inventorySum->refId }}<br> --}}
                                    @if ($withorders1->id == $inventorySum->refId)
                                        <?php $check = 1; ?>
                                        <tr>
                                            <td rowspan=“10”>
                                                {{ $date = date('d/m/Y', strtotime($withorders1->createDate)) }}
                                                {{-- id', 'customerName', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder --}}
                                            </td>
                                            {{-- <td>{{ $withorders1->id }}::{{ $inventorySum->refId }}</td> --}}
                                            <td>{{ $withorders1->customerName }}</td>
                                            <td>{{ $withorders1->fabricId }}</td>
                                            <td>{{ $withorders1->fabricStructure }}</td>
                                            <td>{{ $withorders1->fabricPattern }}</td>
                                            {{-- <td>{{ $fabricoutdata2->fabric_w }}</td> --}}
                                            <?php $check = 0; ?>
                                            @for ($z = 0; $z < count($fabricoutdata2); $z++)
                                                @if ($fabricoutdata2[$z]->purchaseOrder == $withorders1->id)
                                                    <td>{{ $fabricoutdata2[$z]->fabric_w }}
                                                        <?php $check = 1; ?>
                                                @endif
                                            @endfor
                                            @if ($check == 0)
                                                <td></td>
                                            @endif
                                            <td>{{ $withorders1->orderSumYard }}</td>

                                            <?php $check = 0; ?>
                                            @for ($j = 0; $j < count($fabricoutdata); $j++)
                                                @if ($fabricoutdata[$j]->orderId == $withorders1->id)
                                                    <td>{{ $fabricoutdata[$j]->sumYardSum }}</td>
                                                    <td>{{ $fabricoutdata[$j]->foldCount }}</td>
                                                    <?php $check = 1; ?>
                                                @endif
                                            @endfor
                                            @if ($check == 0)
                                                <td></td>
                                                <td></td>
                                            @endif

                                            <?php $check = 0; ?>
                                            @for ($j = 0; $j < count($fabricoutdata); $j++)
                                                @if ($fabricoutdata[$j]->orderId == $withorders1->id)
                                                    <td>{{ $withorders1->orderSumYard - $fabricoutdata[$j]->sumYardSum }}
                                                    </td>
                                                    <?php $check = 1; ?>
                                                @endif
                                            @endfor
                                            @if ($check == 0)
                                                <td></td>
                                            @endif
                                            <td>
                                                <a href="{{ route('inventory.show', $withorders1->id) }}">รายละเอียด</a>
                                            </td>
                                            <td>
                                                <form method="post" action="{{ route('fabricout.store') }}"
                                                    id="myForm">
                                                    @csrf
                                                    <input type="hidden" name="orderId" value="{{ $withorders1->id }}">
                                                    <input type="hidden" name="customerName"
                                                        value="{{ $withorders1->customerName }}                                                        ">
                                                    <input type="hidden" name="fabricStruct"
                                                        value="{{ $withorders1->fabricStructure }}">


                                                    <button name="submit" value="generateByOrder" class="btn b_save">
                                                        {{-- <img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17"> --}}
                                                        จัดส่งสินค้า</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @break
                                @endif
                            @endforeach
                            @if ($check != 1)
                                <tr>
                                    <td rowspan=“10”>
                                        {{ $date = date('d/m/Y', strtotime($withorders1->createDate)) }}
                                        {{-- id', 'customerName', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder --}}
                                    </td>
                                    {{-- <td>{{ $withorders1->id }}::{{ $inventorySum->refId }}</td> --}}
                                    <td>{{ $withorders1->customerName }}</td>
                                    <td>{{ $withorders1->fabricId }}</td>
                                    <td>{{ $withorders1->fabricStructure }}</td>
                                    <td>{{ $withorders1->fabricPattern }}</td>
                                    {{-- <td>{{ $fabricoutdata2->fabric_w }}</td> --}}
                                    <?php $check = 0; ?>
                                    @for ($z = 0; $z < count($fabricoutdata2); $z++)
                                        @if ($fabricoutdata2[$z]->purchaseOrder == $withorders1->id)
                                            <td>{{ $fabricoutdata2[$z]->fabric_w }}
                                                <?php $check = 1; ?>
                                        @endif
                                    @endfor
                                    @if ($check == 0)
                                        <td></td>
                                    @endif
                                    <td>{{ $withorders1->orderSumYard }}</td>

                                    <?php $check = 0; ?>
                                    @for ($z = 0; $z < count($fabricoutdata); $z++)
                                        @if ($fabricoutdata[$z]->orderId == $withorders1->id)
                                            <td>{{ $fabricoutdata[$z]->sumYardSum }}</td>
                                            <td>{{ $fabricoutdata[$z]->foldCount }}</td>
                                            <?php $check = 1; ?>
                                        @endif
                                    @endfor
                                    @if ($check == 0)
                                        <td></td>
                                        <td></td>
                                    @endif

                                    <?php $check = 0; ?>
                                    @for ($j = 0; $j < count($fabricoutdata); $j++)
                                        @if ($fabricoutdata[$j]->orderId == $withorders1->id)
                                            <td>{{ $withorders1->orderSumYard - $fabricoutdata[$j]->sumYardSum }}</td>
                                            <?php $check = 1; ?>
                                        @endif
                                    @endfor
                                    @if ($check == 0)
                                        <td></td>
                                    @endif
                                    <td>
                                        <a href="{{ route('inventory.show', $withorders1->id) }}">รายละเอียด</a>
                                    </td>
                                    <td>
                                        <form method="post" action="{{ route('fabricout.store') }}" id="myForm">
                                            @csrf
                                            <input type="hidden" name="orderId" value="{{ $withorders1->id }}">
                                            <input type="hidden" name="customerName"
                                                value="{{ $withorders1->customerName }}                                                        ">
                                            <input type="hidden" name="fabricStruct"
                                                value="{{ $withorders1->fabricStructure }}">


                                            <button name="submit" value="generateByOrder" class="btn b_save">
                                                {{-- <img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17"> --}}
                                                จัดส่งสินค้า</button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!--row-->
    </div>
    <!--List_table-->
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
