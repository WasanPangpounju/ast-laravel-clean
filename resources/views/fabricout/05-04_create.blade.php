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
                <li class="breadcrumb-item active">บันทึกรายการจัดส่งสินค้า</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> บันทึกรายการจัดส่งสินค้า</h1>
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
                <h2 class="title"><i class="fa fa-caret-right"></i> บันทึกจัดส่งสินค้าเลขที่ {{ $no }}</h2>
                <button type="button" class="btn b_order" name="submit" value="index"><a
                        href="{{ route('inventory.index') }}">จัดส่งตามใบสั่งซื้อ</a></button><br><br>
                <form method="post" action="{{ route('fabricout.store') }}" id="myForm">
                    @csrf

                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
                    <p>จำนวนรวม : 00</p>
                    <div class="row">
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
print(' <b>หมายเหตุ : </b>'. $comment);
                            ?>
                        <input type="hidden" name="dt" class="form-control" value="<?php print $dt; ?>">
                        <input type="hidden" name="fabricStruct" class="form-control" value="<?php print $fabricStruct; ?>">
                        <input type="hidden" name="customerName" class="form-control" value="<?php print $customerName; ?>">
                        <input type="hidden" name="receiveType" class="form-control" value="<?php print $receiveType; ?>">
                        <input type="hidden" name="receiveName" class="form-control" value="<?php print $receiveName; ?>">


                        <input type="hidden" name="orderId" class="form-control" value="<?php print $order_id; ?>">
                        <input type="hidden" name="comment" class="form-control" value="<?php print $comment; ?>">



                        <?php print("</div>"); }else{ ?>

                        <input type="hidden" name="orderId" class="form-control" value="<?php print $order_id; ?>">

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
                                    data-target="#reservationdate" value="{{ $dt }}" />
                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label for="customerName">ผู้สั่ง</label>

                    @if (isset($backdata))
                        <input type="text" name="customerName" class="form-control" id="customerName"
                            placeholder="ผู้สั่ง" value="{{ $backdata->customerName }}" required>
                    @else
                        <input type="text" name="customerName" class="form-control" id="myInput"
                            onkeyup="myFunction()" placeholder="ผู้สั่ง" value="{{ $customer_name }}" required>
                    @endif


                    <ul id="myUL">
                        @foreach ($customers as $customer)
                            <li><a
                                    href="javascript:setcustomerFunction('{{ $customer->name }}');">{{ $customer->name }}</a>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    {{-- <label for="receive">ผู้รับ</label> --}}
                    <input type="radio" id="receiveType" name="receiveType" value="receiver" checked>
                    <label for="receiver">ผู้รับ</label>
                    <input type="radio" id="receiveType" name="receiveType" value="deposit">
                    <label for="receiveType">ฝากจัดเก็บ</label>
                    @if (isset($backdata))
                        <input type="text" name="receiveName" class="form-control" id="receiveName"
                            placeholder="ผู้รับ" value="{{ $backdata->receiveName }}">
                    @else
                        <input type="text" name="receiveName" class="form-control" id="receiveName"
                            placeholder="ผู้รับ" value="" required>
                    @endif

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
                            class="form-control" id="fabricStruct" placeholder="โครงสร้างผ้า"
                            value="{{ $fabric_struct }}" required>
                    @endif


                    <ul id="supp">
                        @foreach ($orders as $order)
                            <li><a
                                    href="javascript:setsupplierFunction('supp', '{{ $order->fabricStructure }}');">{{ $order->fabricStructure }}</a>
                            </li>
                        @endforeach
                    </ul>


                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label for="comment">หมายเหตุ</label>
                    @if (isset($backdata))
                        <input type="text" name="comment" class="form-control" id="comment" placeholder="หมายเหตุ"
                            value="{{ $backdata->comment }}">
                    @else
                        <input type="text" name="comment" class="form-control" id="comment" placeholder="หมายเหตุ"
                            value="">
                    @endif

                </div>
            </div>

            <?php } ?>
        </div>
        <!--row-->

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
                @for ($i = 0; $i < 4; $i++)
                    <div class="col-md-3">
                        @for ($k = 0; $k < 20; $k++)
                            <div class="form-group">
                                @php
                                    $j = $i * 20 + $k + $c;
                                @endphp
                                {{-- <label for="fold{{ $j + 1 }}">พับที่ {{ $j + 1 }}</label> --}}

                                <label for="sumYard{{ $j + 1 }}"></label>
                               <p> พับที่ {{ $j + 1 }} <input type="text" name="sumYard[{{ $j + 1 }}]"
                                    class="input-text" id="sumYard{{ $j + 1 }}"></p>
                            </div>
                        @endfor
                    </div>
                @endfor


                <div class="line_btn"> -->
                    <button name="submit" value="gotoIndex" class="btn b_order clean"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>"
                            width="15"> ยกเลิก</button>
                    <button name="submit" value="nextData" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>"
                            width="17">
                        บันทึกรายการถัดไป</button>

                    <button name="submit" value="endData" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>"
                            width="17">
                        บันทึกเสร็จสิ้น</button>
                </div>


                </form>
            </div>
            <!--box-from-->
        </div>
        <!--content-->
    </div> <!-- /.content-wrapper -->

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
                txtValue = a.textContent || a.innerText;
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
            document.getElementById("fabricStruct").value = t1;
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
