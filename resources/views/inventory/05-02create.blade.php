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
                <li class="breadcrumb-item active">บันทึกรายการสินค้า</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> บันทึกรายการสินค้า</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> บันทึกสินค้าคงคลัง</h2>
                <form method="post" action="{{ route('inventory.store') }}" id="myForm">
                    @csrf

                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">

                    <div class="row">
                        <?php
                        if (session()->has('dt')) {
                            $dt = session()->get('dt');
                        } else {
                            $dt = '';
                        }
                        
                        if (session()->has('fabricStruct')) {
                            $fabricStruct = session()->get('fabricStruct');
                        } else {
                            $fabricStruct = '';
                        }
                        
                        if (session()->has('fabricW')) {
                            $fabricW = session()->get('fabricW');
                        } else {
                            $fabricW = '';
                        }
                        
                        ?>
                        <?php if ($dt != '' && $fabricStruct != ''&& $fabricW != '') {
                        print($date = date('d/m/Y', strtotime($dt)));
                        print($fabricStruct);
                        print($fabricW);
                            ?>
                        <input type="hidden" name="dt" class="form-control" value="<?php print $dt; ?>">
                        <input type="hidden" name="fabricStruct" class="form-control" value="<?php print $fabricStruct; ?>">
                        <input type="hidden" name="fabricW" class="form-control" value="<?php print $fabricW; ?>">
                        <?php }else{ ?>
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
                    <label for="fabricStruct">โครงสร้างผ้า </label>
                    @if (isset($backdata))
                        <input type="text" name="fabricStruct" onkeyup="supplierFunction('fabricStruct')"  
                        class="form-control" id="fabricStruct"
                            placeholder="โครงสร้างผ้า" value="{{ $backdata->fabricStruct }}">
                    @else
                        <input type="text" name="fabricStruct" onkeyup="supplierFunction('fabricStruct')" 
                        class="form-control" id="fabricStruct"
                            placeholder="โครงสร้างผ้า" value="{{ $fabricStruct }}" required>
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
                    <label for="fabricW">หน้ากว้าง </label>

                    @if (isset($backdata))
                        <input type="text" name="fabricW" class="form-control" id="fabricW" placeholder="หน้ากว้าง"
                            value="{{ $backdata->fabricW }}" required>
                    @else
                        <input type="text" name="fabricW" class="form-control" id="fabricW" placeholder="หน้ากว้าง"
                            value="{{ $fabricW }} " required>
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
        ?>

        @for ($i = $c; $i < $c + 10; $i++)
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fold{{ $i + 1 }}">พับที่ {{ $i + 1 }}</label>
                        <label for="sumYard{{ $i + 1 }}"> จำนวนหลา </label>
                        <input type="text" name="sumYard[{{ $i + 1 }}]" 
                        class="input-text"
                            id="sumYard{{ $i + 1 }}" 

                            placeholder="จำนวนหลา">
                    </div>

                </div>
            </div>
        @endfor
 -->

 
 <?php
//check end count
if (session()->has('endCount')) {
    $c = session()->get('endCount');
} else {
    $c = 0;
}
?>

<div class="container">
    @for ($i = $c; $i < $c + 50; $i+=5)
    <div class="row">
        @for ($j = $i; $j < $i + 5 && $j < $c + 50; $j++)
        <div class="col-md-2">
            <div class="form-group">
                <label for="fold{{ $j + 1 }}">พับที่ {{ $j + 1 }}</label>
                <label for="sumYard{{ $j + 1 }}"> จำนวนหลา </label>
                <input type="text" name="sumYard[{{ $j + 1 }}]" class="input-text" id="sumYard{{ $j + 1 }}" placeholder="จำนวนหลา">
            </div>
        </div>
        @endfor
    </div>
    @endfor
</div>


    </div>
    <!--row-->

    <div class="line_btn">
        <button name="submit" value="gotoIndex" class="btn b_order clean"><img src="<?php echo asset('assets/images/xmark-solid.png'); ?>"
                width="15"> ยกเลิก</button>
        <button name="submit" value="nextData" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
            บันทึกรายการถัดไป</button>

        <button name="submit" value="endData" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
            บันทึกเสร็จสิ้น</button>
    </div>


    </form>
    <form action="{{ route('inventory.store') }}" method="post" target="_blank">
        @csrf
        <input type="hidden" name="customerName"
                                value="testttttttttttttttt">
        <button type="submit" class="btn b_order" name="submit" value="genPDF">Generate
            PDF</button>
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

@endsection
