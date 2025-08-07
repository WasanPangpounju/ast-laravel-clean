@extends('layouts.astmanufacturing')

@section('content')
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
        rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="/inventory/">คลังสินค้า</a>
                </li>
                <li class="breadcrumb-item active">เปิดบิลผ้า</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> เปิดบิลผ้า</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">

                <?php
                //check no from session
                if (session()->has('no')) {
                    $no = session()->get('no');
                } else {
                    $no = '';
                }
                
                ?>
                <h2 class="title"><i class="fa fa-caret-right"></i> บันทึกเปิดบิลผ้าเลขที่ {{ $no }}</h2>
                <button type="button" class="btn b_order" name="submit" value="index"><a
                        href="{{ route('inventory.index') }}">จัดส่งตามใบสั่งซื้อ</a></button><br><br>
                <form method="post" action="{{ route('fabricout.store') }}" id="myForm">
                    @csrf

                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
                    <?php
                    
                    ?>
                    {{-- <p>จำนวนรวม : <strong id="sum"></strong> หลา</p> --}}



                    <!-- <?php
                    //check end count
                    if (session()->has('endCount')) {
                        $c = session()->get('endCount');
                    } else {
                        $c = 0;
                    }
                    ?> -->

                    {{-- @for ($i = $c; $i < $c + 10; $i++)
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fold{{ $i + 1 }}">พับที่ </label>
                        <input type="text" name="fold[{{ $i + 1 }}]" class="form-control"
                            id="fold{{ $i + 1 }}" value="{{ $i + 1 }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sumYard{{ $i + 1 }}">จำนวนหลา </label>
                        <input type="text" name="sumYard[{{ $i + 1 }}]" class="input-text"
                            id="sumYard{{ $i + 1 }}" placeholder="จำนวนหลา">
                    </div>

                </div>
            </div>
        @endfor --}}

                    <?php
                    //check end count
                    if (session()->has('endCount')) {
                        $c = session()->get('endCount');
                    } else {
                        $c = 0;
                    }
                    ?>

                    {{-- <div class="content">
            <div class="box-from">
                @for ($i = 0; $i < 10; $i++)
                    <!-- loop for 10 rows -->
                    <div class="row">
                        @for ($j = 0; $j < 10; $j++)
                            <!-- loop for 10 columns -->
                            @php
                                $fieldNumber = $c + $i * 10 + $j + 1;
                            @endphp
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="fold{{ $fieldNumber }}">พับที่ {{ $fieldNumber }}</label>
                                    <input type="hidden" name="fold[{{ $fieldNumber }}]" class="form-control"
                                        id="fold{{ $fieldNumber }}" value="{{ $fieldNumber }}" required>
                                    <label for="sumYard{{ $fieldNumber }}"></label>
                                    <input type="text" name="sumYard[{{ $fieldNumber }}]" class="input-text"
                                        id="sumYard{{ $fieldNumber }}" placeholder="จำนวนหลา">
                                </div>
                            </div>
                        @endfor
                    </div>
                @endfor
            </div>
        </div> --}}
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
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <p>จำนวนรวม : <strong id="fabriccount"></strong> พับ <strong id="sum"></strong> หลา
                                </p>
                                <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
                                <?php
                                
                                //check create date from session
                                if (session()->has('dt')) {
                                    $dt = session()->get('dt');
                                } else {
                                    $dt = '';
                                }
                                
                                //check customer name from session
                                if (session()->has('customerName')) {
                                    $customerName = session()->get('customerName');
                                } else {
                                    $customerName = '';
                                }
                                
                                //check receive name from session
                                if (session()->has('receiveName')) {
                                    $receiveName = session()->get('receiveName');
                                } else {
                                    $receiveName = '';
                                }
                                
                                //check fabric struct from session
                                if (session()->has('fabricStruct')) {
                                    $fabricStruct = session()->get('fabricStruct');
                                } else {
                                    $fabricStruct = '';
                                }
                                
                                //check fabricPattern from session
                                if (session()->has('fabricPattern')) {
                                    $fabricPattern = session()->get('fabricPattern');
                                } else {
                                    $fabricPattern = '';
                                }
                                
                                //check fabricW from session
                                if (session()->has('fabricW')) {
                                    $fabricW = session()->get('fabricW');
                                } else {
                                    $fabricW = '';
                                }
                                
                                //check comment from session
                                if (session()->has('comment')) {
                                    $comment = session()->get('comment');
                                } else {
                                    $comment = '';
                                }
                                
                                //check type for send fabric to. from session
                                if (session()->has('receiveType')) {
                                    $receiveType = session()->get('receiveType');
                                } else {
                                    $receiveType = '';
                                }
                                
                                if (session()->has('orderId')) {
                                    $order_id = session()->get('orderId');
                                } else {
                                    $order_id = '';
                                }
                                
                                //check customerReplace from session
                                if (session()->has('customerReplace')) {
                                    $customerReplace = session()->get('customerReplace');
                                } else {
                                    $customerReplace = '';
                                }
                                
                                //check fabricStructReplace from session
                                if (session()->has('fabricStructReplace')) {
                                    $fabricStructReplace = session()->get('fabricStructReplace');
                                } else {
                                    $fabricStructReplace = '';
                                }
                                
                                //check vatType from session
                                if (session()->has('vatType')) {
                                    $vatType = session()->get('vatType');
                                } else {
                                    $vatType = '';
                                }
                                
                                ?>
                                <?php if ($dt != '' && $fabricStruct != '') {
                            print("<div style='margin-left: 1.5em;'>");
                        print($date = date('d/m/Y', strtotime($dt)).' <br>');
                        print('<b>ผู้สั่ง : </b>'. $customerName.' ');

                        //check receive type
                        if($receiveType == 'deposit') {
                            print(' <b>ฝากจัดเก็บสินค้า</b> ');
                        }else{
                            print('');
                        }
                        // print('  <b>123456 : </b>'. $receiveType. ' '.'<br>');
print('  <b>ผู้รับ : </b>'. $receiveName. ' '.'<br>');
                        print('<b>โครงสร้าง :</b> '.$fabricStruct.'<br>');
                        print('<b>ลายผ้า:</b> '.$fabricPattern .'<br>');
                        print('<b>หน้ากว้าง:</b> '.$fabricW .'<br>');

                        print('<b>แทนผู้สั่งซื้อ:</b> '.$customerReplace .'<br>');
                        print('<b>แทนโครงสร้าง:</b> '.$fabricStructReplace .'<br>');
                        print('<b>ประเภทบิล:</b> '.$vatType .'<br>');

print(' <b>หมายเหตุ : </b>'. $comment .'<br>');
                            ?>
                                <input type="hidden" name="dt" class="form-control" value="<?php print $dt; ?>">
                                <input type="hidden" name="fabricStruct" class="form-control" value="<?php print $fabricStruct; ?>">
                                <input type="hidden" name="fabricPattern" class="form-control" value="<?php print $fabricPattern; ?>">
                                <input type="hidden" name="fabricW" class="form-control" value="<?php print $fabricW; ?>">

                                <input type="hidden" name="fabricStructReplace" class="form-control" value="<?php print $fabricStructReplace; ?>">
                                <input type="hidden" name="customerReplace" class="form-control" value="<?php print $customerReplace; ?>">

                                <input type="hidden" name="customerName" class="form-control" value="<?php print $customerName; ?>">
                                <input type="hidden" name="receiveType" class="form-control" value="<?php print $receiveType; ?>">
                                <input type="hidden" name="receiveName" class="form-control" value="<?php print $receiveName; ?>">

                                <input type="hidden" name="orderId" class="form-control" value="<?php print $order_id; ?>">
                                <input type="hidden" name="comment" class="form-control" value="<?php print $comment; ?>">



                                <?php print("</div>"); }else{ ?>

                                <input type="hidden" name="orderId" class="form-control" value="<?php print $order_id; ?>">
                                <div class="form-group">
                                    <label for="createDate">วันที่</label>

                                    {{-- @if (isset($backdata))
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" name="createDate"
                                                class="form-control datetimepicker-input" data-target="#reservationdate"
                                                value="{{ $backdata->createDate }} " />
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>

                                </div>
                            @else
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" name="createDate" class="form-control datetimepicker-input"
                                        data-target="#reservationdate" value="{{ $dt }}" />
                                    <div class="input-group-append" data-target="#reservationdate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            @endif --}}
                                    <input class="date form-control" type="text" name="createDate" autocomplete="off">

                                    <script type="text/javascript">
                                        $('.date').datepicker({
                                            format: 'dd-mm-yyyy',
                                            orientation: "bottom",
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="fabricStruct">โครงสร้างผ้า </label>
                                    @if (isset($backdata))
                                        <input type="text" name="fabricStruct"
                                            onkeyup="supplierFunction('fabricStruct')" class="form-control"
                                            id="fabricStruct" placeholder="โครงสร้างผ้า"
                                            value="{{ $backdata->fabricStruct }}">
                                    @else
                                        <input type="text" name="fabricStruct"
                                            onkeyup="supplierFunction('fabricStruct')" class="form-control"
                                            id="fabricStruct" placeholder="โครงสร้างผ้า" value="{{ $fabricStruct }}"
                                            required>
                                    @endif

                                    <!-- <ul id="supp">
                                                                                            @foreach ($orders as $order)
    <li><a
                                                                                     href="javascript:setsupplierFunction('supp', '{{ $order->fabricStructure }}');">{{ $order->fabricStructure }}</a>
                                                                                                                                        </li>
    @endforeach
                                                                                                                                </ul> -->

                                    <ul id="supp">
                                        @foreach ($stockFabricStruct as $stockFS)
                                            <li><a
                                                    href="javascript:setsupplierFunction('supp', '{{ $stockFS->fabricStruct }}' , '{{ $stockFS->fabricPattern }}' , '{{ $stockFS->fabricW }}');">{{ $stockFS->fabricStruct }}
                                                    {{ $stockFS->fabricPattern }} หน้ากว้าง {{ $stockFS->fabricW }} '</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="fabricPattern">ลายผ้า</label>

                                            @if (isset($backdata))
                                                <input type="text" name="fabricPattern" class="form-control"
                                                    id="fabricPattern" placeholder="ลายผ้า"
                                                    value="{{ $backdata->fabricPattern }}" required>
                                            @else
                                                <input type="text" name="fabricPattern" class="form-control"
                                                    id="fabricPattern" placeholder="ลายผ้า" value="{{ $fabricPattern }}"
                                                    required>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label for="fabricW">หน้ากว้าง</label>

                                            @if (isset($backdata))
                                                <input type="text" name="fabricW" class="form-control" id="fabricW"
                                                    placeholder="หน้ากว้าง" value="{{ $backdata->fabricW }}" required>
                                            @else
                                                <input type="text" name="fabricW" class="form-control"
                                                    id="fabricW"placeholder="หน้ากว้าง" value="{{ $fabricW }}"
                                                    required>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                <label for="fabricW">หน้ากว้าง</label>

                                @if (isset($backdata))
                                    <input type="text" name="fabricW" class="form-control" id="fabricW"
                                        placeholder="หน้ากว้าง" value="{{ $backdata->fabricW }}" required>
                                @else
                                    <input type="text" name="fabricW" class="form-control"
                                        id="fabricW"placeholder="หน้ากว้าง" value="{{ $fabricW }}" required>
                                @endif


                            </div> --}}
                                <div class="form-group">
                                    <label for="customerName">ผู้สั่ง</label>

                                    @if (isset($backdata))
                                        <input type="text" name="customerName" class="form-control" id="customerName"
                                            placeholder="ผู้สั่ง" value="{{ $backdata->customerName }}" required>
                                    @else
                                        <input type="text" name="customerName" class="form-control" id="myInput"
                                            onkeyup="myFunction()" placeholder="ผู้สั่ง" value="{{ $customer_name }}"
                                            required>
                                    @endif


                                    <ul id="myUL">
                                        @foreach ($customers as $customer)
                                            <li><a
                                                    href="javascript:setcustomerFunction('{{ $customer->name }}');">{{ $customer->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                                <div class="form-group">
                                    {{-- <label for="receive">ผู้รับ</label> --}}
                                    <input type="radio" id="receiveType1" name="receiveType" value="receiver" checked>
                                    <label for="receiver1">ผู้รับ</label>
                                    <input type="radio" id="receiveType2" name="receiveType" value="deposit">
                                    <label for="receiveType2">ฝากจัดเก็บ</label>
                                    @if (isset($backdata))
                                        <input type="text" name="receiveName" class="form-control" id="receiveName"
                                            placeholder="ผู้รับ" value="{{ $backdata->receiveName }}">
                                    @else
                                        <input type="text" name="receiveName" class="form-control" id="receiveName"
                                            placeholder="ผู้รับ" value="" required>
                                    @endif

                                </div>
                                <div class="form-group">
                                    <label for="fabricStructReplace">แทนโครงสร้างผ้า</label>
                                    @if (isset($backdata))
                                        <input type="text" name="fabricStructReplace" class="form-control"
                                            id="fabricStructReplace" placeholder="แทนโครงสร้างผ้า"
                                            value="{{ $backdata->fabricStructReplace }}">
                                    @else
                                        <input type="text" name="fabricStructReplace" class="form-control"
                                            id="fabricStructReplace" placeholder="แทนโครงสร้างผ้า"
                                            value="{{ $fabricStructReplace }}">
                                    @endif

                                </div>
                                <div class="form-group">
                                    <label for="customerReplace">แทนผู้สั่งซื้อ</label>
                                    @if (isset($backdata))
                                        <input type="text" name="customerReplace" class="form-control"
                                            id="customerReplace" placeholder="แทนผู้สั่งซื้อ"
                                            value="{{ $backdata->customerReplace }}">
                                    @else
                                        <input type="text" name="customerReplace" class="form-control"
                                            id="customerReplace" placeholder="แทนผู้สั่งซื้อ"
                                            value="{{ $customerReplace }}">
                                    @endif

                                </div>
                                <div class="form-group">
                                    <label for="vatType">ประเภทบิล</label>
                                    @if (isset($backdata))
                                        <select name="vatType" class="form-control" id="vatType">
                                            <option value="A" selected>บิล A</option>
                                            <option value="B">บิล B</option>
                                        </select>
                                    @else
                                        <select name="vatType" class="form-control" id="vatType">
                                            <option value="A" selected>บิล A</option>
                                            <option value="B">บิล B</option>
                                        </select>
                                    @endif

                                </div>
                                <div class="form-group">
                                    <label for="comment">หมายเหตุ</label>
                                    @if (isset($backdata))
                                        <input type="text" name="comment" class="form-control" id="comment"
                                            placeholder="หมายเหตุ" value="{{ $backdata->comment }}">
                                    @else
                                        <input type="text" name="comment" class="form-control" id="comment"
                                            placeholder="หมายเหตุ"
                                            value="ได้รับผ้าตามรายการข้างบนนี้ไว้ถูกต้องและเรียบร้อยแล้ว">
                                    @endif

                                </div>
                                <?php } ?>
                            </div>
                            {{-- <div style="margin-top: 5 rem"></div> --}}
                            @for ($i = 0; $i < 8; $i++)
                                <div class="col-md-1" style="margin: -0.5em;">
                                    @for ($k = 0; $k < 20; $k++)
                                        <div class="form-group">
                                            @php
                                                $j = $i * 20 + $k + $c;
                                                $l = $i * 20 + $k;
                                                
                                                $inputId = 'sumYard' . ($l + 1);
                                                // $cookieName = 'myCookieName' . ($j + 1);
                                            @endphp

                                            <label for="{{ $inputId }}"></label>
                                            <div>
                                                <div
                                                    style="background-color: rgb(255, 238, 0);margin-bottom: -1.5rem;text-align:center;margin-right:2.5rem;">
                                                    {{ $j + 1 }}
                                                </div>

                                                <input type="number" name="sumYard[{{ $j + 1 }}]" maxlength="3"
                                                    class="input-text" id="{{ $inputId }}"
                                                    style="width: 50%;font-size:14pt;font-weight: bold;float:right;"
                                                    autocomplete="off">

                                                {{-- <script>
                                                const inputEl = document.getElementById('{{ $inputId }}');

                                                inputEl.addEventListener('focus', function() {
                                                    // Remove the cookie associated with this input
                                                    document.cookie = "{{ $cookieName }}=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
                                                });
                                            </script> --}}
                                            </div>
                                        </div>
                                    @endfor

                                    <p id="show{{ $i + 1 }}"
                                        style="background-color: rgb(255, 238, 0);margin-top: 2rem;">
                                        รวมแถวที่ {{ $i + 1 }}</p>

                                </div>
                            @endfor



                            <div class="line_btn">
                                <button name="submit" value="gotoIndex" class="btn b_order clean"><img
                                        src="<?php echo asset('assets/images/xmark-solid.png'); ?>" width="15"> ยกเลิก</button>
                                <button name="submit" value="nextData" class="btn b_save"><img
                                        src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
                                    บันทึกรายการถัดไป</button>

                                <button name="submit" value="endData" class="btn b_save"><img
                                        src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
                                    บันทึกเสร็จสิ้น</button>
                            </div>


                </form>
            </div>
            <!--box-from-->
        </div>
        <!--content-->
    </div> <!-- /.content-wrapper -->
    <script>
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.oninput = () => {
                if (input.value.length > input.maxLength) input.value = input.value.slice(0, input.maxLength);
            };
        });
    </script>
    <script>
        var input, filter, ul, li, a, i, txtValue;

        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
        }

        var input, filter, ul, li, a, i, txtValue;

        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
        }


        function myFunction() {
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                if (filter == "") {
                    for (i = 0; i < li.length; i++) {
                        li[i].style.display = "none";
                    }
                    break;
                }

                a = li[i].getElementsByTagName("a")[0];
                //txtValue = a.textContent || a.innerText;

                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";

                } else {
                    li[i].style.display = "none";
                }
            }
        }

        function setcustomerFunction(t) {
            document.getElementById("myInput").value = t;
            //ul = document.getElementById("myUL");
            //ul.style.display = "none";  
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

        }
    </script>

    <script>
        //java script for get enter then next focus text input 

        const inputs = document.getElementsByClassName('input-text');
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].addEventListener('keydown', function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    const nextIndex = (i === inputs.length - 1) ? 0 : i + 1;
                    inputs[nextIndex].focus();
                }
            });
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


        function setsupplierFunction(t, t1, t2, t3) {
            document.getElementById("fabricStruct").value = t1;
            document.getElementById("fabricPattern").value = t2;
            document.getElementById("fabricW").value = t3;

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


    {{-- <script>
        // Get all input text boxes with the class "input-text"
        let inputBoxes = document.getElementsByClassName("input-text");

        // Add an event listener to each input text box that updates the total sum whenever its value changes
        Array.from(inputBoxes).forEach(inputBox => {
            inputBox.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                let numbers = Array.from(inputBoxes).map(inputBox => {
                    let number = parseFloat(inputBox.value);
                    return isNaN(number) ? 0 : number;
                });

                // Compute the sum of the numbers
                let sum = numbers.reduce((total, number) => total + number, 0);

                // Display the total sum in the "sum" paragraph element
                let sumElem = document.getElementById("sum");
                sumElem.textContent = "" + sum;
            });
        });
    </script> --}}

    <script>
        // Get all input text boxes with the class "input-text"
        let inputBoxes = document.getElementsByClassName("input-text");
        var c = <?php echo $c; ?>;
        // alert(c);

        // Initialize the sum from the session storage, or 0 if it's not present
        // let sessionSum = parseFloat(sessionStorage.getItem("sessionSum")) || 0;
        let sessionSum = parseFloat("{{ session('sum') }}") || 0;
        let fabriccount = c;

        // sessionSum  = sessionSum  + sessionStorage.getItem('sum');
        let sumElem = document.getElementById("sum");
        sumElem.textContent = "" + sessionSum;

        let fabriccountsum = document.getElementById("fabriccount");
        fabriccountsum.textContent = "" + fabriccount;

        // Add an event listener to each input text box that updates the total sum whenever its value changes
        Array.from(inputBoxes).forEach(inputBox => {
            inputBox.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                let numbers = Array.from(inputBoxes).map(inputBox => {
                    let number = parseFloat(inputBox.value);
                    return isNaN(number) ? 0 : number;
                });


                // Compute the sum of the numbers
                let sum = numbers.reduce((total, number) => total + number, 0);
                let sumback = "{{ session('sum') }}";
                sum = sum + Number(sumback);
                // fabriccount = fabriccount + 1;
                fabriccount = 0;
                for (let i = 0; i < inputBoxes.length; i++) {
                    if (inputBoxes[i].value.trim() !== '') {
                        fabriccount = fabriccount + 1;

                    }
                }
                fabriccount = fabriccount + c;

                // Display the total sum in the "sum" paragraph element
                sumElem.textContent = "" + sum;
                fabriccountsum.textContent = "" + fabriccount;
                // Store the sum in the session storage with a unique key name
                sessionStorage.setItem("sessionSum_" + Date.now(), sum);
            });
        });

        //sum column1 input 1 to input 20

        const inputElements = [];
        for (let i = 1; i <= 20; i++) {
            const input = document.getElementById('sumYard' + i);
            inputElements.push(input);
        }

        // Add an event listener to each input text box that updates the total sum whenever its value changes
        inputElements.forEach(inputBox1 => {
            inputBox1.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                let numbers1 = Array.from(inputElements).map(inputBox1 => {
                    let number = parseFloat(inputBox1.value);
                    return isNaN(number) ? 0 : number;
                });
                let sum1 = numbers1.reduce((total1, number1) => total1 + number1, 0);
                let sumcolumn1 = document.getElementById("show1");
                sumcolumn1.textContent = "รวม: " + sum1;

                // Add an event listener to each input text box that updates the total sum whenever its value changes
                // Array.from(inputBoxes).forEach(inputBox => {
                // inputBox.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                // let numbers = Array.from(inputBoxes).map(inputBox => {
                // let number = parseFloat(inputBox.value);
                // return isNaN(number) ? 0 : number;
                // });


                // Compute the sum of the numbers
                // let sum = numbers.reduce((total, number) => total + number, 0);
                // let sumback = "{{ session('sum') }}";
                // sum = sum + Number(sumback);
                // fabriccount = fabriccount + 1;
                // Display the total sum in the "sum" paragraph element
                // sumElem.textContent = "" + sum;
                // fabriccountsum.textContent = "" + fabriccount;
                // Store the sum in the session storage with a unique key name
                // sessionStorage.setItem("sessionSum_" + Date.now(), sum);
            });
        });

        //sumcolumn2 input21 to 40
        const inputElements2 = [];
        for (let j = 21; j <= 40; j++) {
            const input2 = document.getElementById('sumYard' + j);
            inputElements2.push(input2);
        }

        // Add an event listener to each input text box that updates the total sum whenever its value changes
        inputElements2.forEach(inputBox2 => {
            inputBox2.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                let numbers2 = Array.from(inputElements2).map(inputBox2 => {
                    let number = parseFloat(inputBox2.value);
                    return isNaN(number) ? 0 : number;
                });

                let sum2 = numbers2.reduce((total2, number2) => total2 + number2, 0);
                let sumcolumn2 = document.getElementById("show2");
                sumcolumn2.textContent = "รวม: " + sum2;
            });
        });

        //sumcolumn3 input31 to 60
        const inputElements3 = [];
        for (let j = 41; j <= 60; j++) {
            const input3 = document.getElementById('sumYard' + j);
            inputElements3.push(input3);
        }

        // Add an event listener to each input text box that updates the total sum whenever its value changes
        inputElements3.forEach(inputBox3 => {
            inputBox3.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                let numbers3 = Array.from(inputElements3).map(inputBox3 => {
                    let number = parseFloat(inputBox3.value);
                    return isNaN(number) ? 0 : number;
                });

                let sum3 = numbers3.reduce((total3, number3) => total3 + number3, 0);
                let sumcolumn3 = document.getElementById("show3");
                sumcolumn3.textContent = "รวม: " + sum3;
            });
        });

        //sumcolumn 4 input61 to 80
        const inputElements4 = [];
        for (let j = 61; j <= 80; j++) {
            const input4 = document.getElementById('sumYard' + j);
            inputElements4.push(input4);
        }

        // Add an event listener to each input text box that updates the total sum whenever its value changes
        inputElements4.forEach(inputBox4 => {
            inputBox4.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                let numbers4 = Array.from(inputElements4).map(inputBox4 => {
                    let number = parseFloat(inputBox4.value);
                    return isNaN(number) ? 0 : number;
                });

                let sum4 = numbers4.reduce((total4, number4) => total4 + number4, 0);
                let sumcolumn4 = document.getElementById("show4");
                sumcolumn4.textContent = "รวม :" + sum4;
            });
        });

        //sumcolumn5 input 81 to 100
        const inputElements5 = [];
        for (let j = 81; j <= 100; j++) {
            const input5 = document.getElementById('sumYard' + j);
            inputElements5.push(input5);
        }

        // Add an event listener to each input text box that updates the total sum whenever its value changes
        inputElements5.forEach(inputBox5 => {
            inputBox5.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                let numbers5 = Array.from(inputElements5).map(inputBox5 => {
                    let number = parseFloat(inputBox5.value);
                    return isNaN(number) ? 0 : number;
                });

                let sum5 = numbers5.reduce((total5, number5) => total5 + number5, 0);
                let sumcolumn5 = document.getElementById("show5");
                sumcolumn5.textContent = "รวม :" + sum5;
            });
        });

        //sumcolumn6 input101 to 120
        const inputElements6 = [];
        for (let j = 101; j <= 120; j++) {
            const input6 = document.getElementById('sumYard' + j);
            inputElements6.push(input6);
        }

        // Add an event listener to each input text box that updates the total sum whenever its value changes
        inputElements6.forEach(inputBox6 => {
            inputBox6.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                let numbers6 = Array.from(inputElements6).map(inputBox6 => {
                    let number = parseFloat(inputBox6.value);
                    return isNaN(number) ? 0 : number;
                });

                let sum6 = numbers6.reduce((total6, number6) => total6 + number6, 0);
                let sumcolumn6 = document.getElementById("show6");
                sumcolumn6.textContent = "รวม :" + sum6;
            });
        });

        //sumcolumn7 input 121 to 140
        const inputElements7 = [];
        for (let j = 121; j <= 140; j++) {
            const input7 = document.getElementById('sumYard' + j);
            inputElements7.push(input7);
        }

        // Add an event listener to each input text box that updates the total sum whenever its value changes
        inputElements7.forEach(inputBox7 => {
            inputBox7.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                let numbers7 = Array.from(inputElements7).map(inputBox7 => {
                    let number = parseFloat(inputBox7.value);
                    return isNaN(number) ? 0 : number;
                });

                let sum7 = numbers7.reduce((total7, number7) => total7 + number7, 0);
                let sumcolumn7 = document.getElementById("show7");
                sumcolumn7.textContent = "รวม :" + sum7;
            });
        });

        //sumcolumn 8 input141 to 160
        const inputElements8 = [];
        for (let j = 141; j <= 160; j++) {
            const input8 = document.getElementById('sumYard' + j);
            inputElements8.push(input8);
        }

        // Add an event listener to each input text box that updates the total sum whenever its value changes
        inputElements8.forEach(inputBox8 => {
            inputBox8.addEventListener("change", function() {
                // Extract the numbers from all input text boxes
                let numbers8 = Array.from(inputElements8).map(inputBox8 => {
                    let number = parseFloat(inputBox8.value);
                    return isNaN(number) ? 0 : number;
                });

                let sum8 = numbers8.reduce((total8, number8) => total8 + number8, 0);
                let sumcolumn8 = document.getElementById("show8");
                sumcolumn8.textContent = "รวม :" + sum8;
            });
        });
    </script>

@endsection
