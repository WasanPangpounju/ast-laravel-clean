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
                <li class="breadcrumb-item active">บันทึกรายการส่งสินค้า</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> บันทึกรายการส่งสินค้า</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> บันทึกรายการส่งสินค้า</h2>
                <form method="post" action="{{ route('ordershipped.store') }}" id="myForm">
                    @csrf
                    <input type="hidden" id="refId" name="refId" value="">

                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">

                    <div class="row">
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
                                <label for="exceptName">ชื่อผู้รับ</label>
                                @if (isset($backdata))
                                    <input type="text" name="exceptName" class="form-control" id="exceptName"
                                        placeholder="่ชื่อผู้รับ" value="{{ $backdata->orderId }}">
                                @else
                                    <input type="text" name="exceptName" class="form-control" id="exceptName"
                                        placeholder="่ชื่อผู้รับ">
                                @endif


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
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fold">จำนวนพับ </label>

                    @if (isset($backdata))
                        <input type="text" name="fold" class="form-control" id="fold" placeholder="จำนวนพับ"
                            value="{{ $backdata->fold }}" required>
                    @else
                        <input type="text" name="fold" class="form-control" id="fold" placeholder="จำนวนพับ"
                            required>
                    @endif

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sumYard">จำนวนหลา </label>

                    @if (isset($backdata))
                        <input type="text" name="sumYard" class="form-control" id="sumYard" placeholder="จำนวนหลา"
                            oninput="yd(this.value)" onchange="yd(this.value )" value="{{ $backdata->sumYard }}"
                            required>
                    @else
                        <input type="text" name="sumYard" class="form-control" id="sumYard" placeholder="จำนวนหลา"
                            oninput="yd(this.value)" onchange="yd(this.value )" required>
                    @endif

                </div>
            </div>
        </div>
        <!--row-->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sumM">จำนวนเมตร </label>

                    @if (isset($backdata))
                        <input type="text" name="sumM" class="form-control" id="sumM"
                            oninput="ydToM(this.value)" onchange="ydToM(this.value )" placeholder="จำนวนเมตร"
                            value="{{ $backdata->sumM }}" required>
                    @else
                        <input type="text" name="sumM" class="form-control" id="sumM"
                            oninput="ydToM(this.value)" onchange="ydToM(this.value )" placeholder="จำนวนเมตร" required>
                    @endif

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fabricW">หน้ากว้าง </label>

                    @if (isset($backdata))
                        <input type="text" name="fabricW" class="form-control" id="fabricW"
                            placeholder="หน้ากว้าง" value="{{ $backdata->fabricW }}" required>
                    @else
                        <input type="text" name="fabricW" class="form-control" id="fabricW"
                            placeholder="หน้ากว้าง" required>
                    @endif

                </div>
            </div>
        </div>
        <!--row-->
        <div class="form-group">
            <label for="comment">หมายเหตุ</label>
            <textarea type="" name="comment" class="form-control" id="comment" placeholder="หมายเหตุ"></textarea>
        </div>
        <div class="line_btn">
            <button name="submit" value="cleanForm" class="btn b_order clean"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>"
                    width="15"> เคลียร์ข้อมูล</button>
            <button name="submit" value="checkdata" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
                ตรวจสอบ</button>
        </div>

        </form>
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
