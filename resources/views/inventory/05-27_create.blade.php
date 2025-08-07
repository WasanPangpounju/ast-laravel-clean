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

                <?php
                //check no from session
                // if (session()->has('no')) {
                //     $no = session()->get('no');
                // } else {
                //     $no = '';
                // }
                ?>
                <h2 class="title"><i class="fa fa-caret-right"></i> บันทึกสินค้าคงคลัง</h2>
                {{-- <button type="button" class="btn b_order" name="submit" value="index"><a
                        href="{{ route('inventory.index') }}">จัดส่งตามใบสั่งซื้อ</a></button><br><br> --}}
                <form method="post" action="{{ route('inventory.store') }}" id="myForm">
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
                                        href="{{ route('inventory.index') }}">รายการผ้ารอผลิต</a></button>
                                <button class="btn b_order" type="button"
                                    style="width: 60%;margin:0.5rem;background-color: #aca06e;"><a
                                        href="{{ route('fabricout.index') }}">ตรวจสอบการจัดส่ง</a></button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn b_order" type="button"
                                    style="width: 70%;margin:0.5rem;background-color: #1bccbd;"><a
                                        href="{{ route('inventory.create') }}">บันทึกรายการสินค้า
                                    </a></button>
                                <button class="btn b_order" type="button"
                                    style="width: 70%;margin:0.5rem;background-color: #8a8a8a;"><a
                                        href="{{ route('fabricout.create') }}">บันทึกรายการจัดส่งสินค้า</a></button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn b_order" type="button"
                                    style="width: 55%;margin:0.5rem;background-color: #ec9c06;"><a
                                        href="{{ route('stockfabric.index') }}">คลังผ้า</a></button>
                                <button class="btn b_order" type="button"
                                    style="width: 55%;margin:0.5rem;background-color: rgb(175, 163, 110);"><a
                                        href="{{ route('fabricdeposit.index') }}">คลังผ้าฝากจัดเก็บ</a></button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn b_order" type="button"
                                    style="width: 70%;margin:0.5rem;background-color: #ec9c06;"><a
                                        href="{{ route('fabriccheck.index') }}">ตรวจสอบบันทึกรายการ</a></button>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <p>จำนวนรวม : <strong id="sum"></strong> หลา</p>
                                <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
                                <?php
                                
                                //check create date from session
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
                                
                                if (session()->has('fabricPattern')) {
                                    $fabricPattern = session()->get('fabricPattern');
                                } else {
                                    $fabricPattern = '';
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
                             print($fabricPattern );
                                    print($fabricW);
                                        ?>
                                <input type="hidden" name="dt" class="form-control" value="<?php print $dt; ?>">
                                <input type="hidden" name="fabricStruct" class="form-control" value="<?php print $fabricStruct; ?>">
                                <input type="hidden" name="fabricPattern" class="form-control" value="<?php print $fabricPattern; ?>">
                                <input type="hidden" name="fabricW" class="form-control" value="<?php print $fabricW; ?>">
                                <?php }else{ ?>

                                {{-- <input type="hidden" name="orderId" class="form-control" value="<?php print $order_id; ?>"> --}}
                                <div class="form-group">
                                    <label for="createDate">วันที่</label>

                                    @if (isset($backdata))
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
                            @endif
                            <div class="form-group">
                                <label for="fabricStruct">โครงสร้างผ้า </label>
                                @if (isset($backdata))
                                    <input type="text" name="fabricStruct" onkeyup="supplierFunction('fabricStruct')"
                                        class="form-control" id="fabricStruct" placeholder="โครงสร้างผ้า"
                                        value="{{ $backdata->fabricStruct }}">
                                @else
                                    <input type="text" name="fabricStruct" onkeyup="supplierFunction('fabricStruct')"
                                        class="form-control" id="fabricStruct" placeholder="โครงสร้างผ้า"
                                        value="{{ $fabricStruct }}" required>
                                @endif

                                <ul id="supp">
                                    @foreach ($orders as $order)
                                        <li><a
                                                href="javascript:setsupplierFunction('supp', '{{ $order->fabricStructure }}' , '{{ $order->fabricPattern }}');">{{ $order->fabricStructure }}
                                                {{ $order->fabricPattern }}</a>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>

                            <div class="form-group">
                                <label for="fabricPattern">ลายผ้า </label>
                                @if (isset($backdata))
                                    <input type="text" name="fabricPattern" class="form-control" id="fabricPattern"
                                        placeholder="" value="{{ $backdata->fabricPattern }}">
                                @else
                                    <input type="text" name="fabricPattern" class="form-control" id="fabricPattern"
                                        placeholder="ลายผ้า" value="{{ $fabricPattern }}" required>
                                @endif

                            </div>

                            <div class="form-group">
                                <label for="fabricW">หน้ากว้าง </label>

                                @if (isset($backdata))
                                    <input type="text" name="fabricW" class="form-control" id="fabricW"
                                        placeholder="หน้ากว้าง" value="{{ $backdata->fabricW }}" required>
                                @else
                                    <input type="text" name="fabricW" class="form-control" id="fabricW"
                                        placeholder="หน้ากว้าง" value="{{ $fabricW }} " required>
                                @endif

                            </div>
                            <?php } ?>
                        </div>
                        <!-- Your input fields go here -->
                        @for ($i = 0; $i < 8; $i++)
                            <div class="col-md-1" style="margin: -0.5em;">
                                @for ($k = 0; $k < 20; $k++)
                                    <div class="form-group">
                                        @php
                                            $j = $i * 20 + $k + $c;
                                        @endphp
                                        <label for="sumYard{{ $j + 1 }}"></label>
                                        <div>
                                            <div
                                                style="background-color: rgb(255, 238, 0); margin-bottom: -1.5rem; text-align: center; margin-right: 2.5rem;">
                                                {{ $j + 1 }}
                                            </div>
                                            <input type="text" name="sumYard[{{ $j + 1 }}]" class="input-text"
                                                id="sumYard{{ $j + 1 }}"
                                                style="width: 50%; font-size: 14pt; font-weight: bold; float: right;"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        @endfor




                        <div class="line_btn">
                            <button name="submit" value="gotoIndex" class="btn b_order clean"><img
                                    src="<?php echo asset('assets/images/xmark-solid.png'); ?>" width="15"> ยกเลิก</button>
                            <button name="submit" value="nextData" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>"
                                    width="17">
                                บันทึกรายการถัดไป</button>

                            <button name="submit" value="endData" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>"
                                    width="17">
                                บันทึกเสร็จสิ้น</button>
                        </div>



                </form>
                {{-- <h1>Enter 10 Numbers to Add</h1>
    <form>
      <div id="inputs"></div>
      <br />
      <label for="sum1">Sum:</label>
      <input type="text" id="sum1" name="sum1" readonly />
    </form> --}}
                {{-- <script>
      function createInputs() {
        var inputsDiv = document.getElementById("inputs");
        for (var i = 1; i <= 10; i++) {
          var inputLabel = document.createElement("label");
          inputLabel.setAttribute("for", "num" + i);
          inputLabel.innerText = "Number " + i + ":";
          var inputField = document.createElement("input");
          inputField.setAttribute("type", "number");
          inputField.setAttribute("id", "num" + i);
          inputField.setAttribute("name", "num" + i);
          inputField.setAttribute("oninput", "calculateSum()");
          inputsDiv.appendChild(inputLabel);
          inputsDiv.appendChild(inputField);
          inputsDiv.appendChild(document.createElement("br"));
        }
      }

      function calculateSum() {
        var sum1 = 0;
        for (var i = 1; i <= 10; i++) {
          var num = document.getElementById("num" + i).value;
          sum1 += parseInt(num);
        }
        document.getElementById("sum1").value = sum1;
      }

      createInputs();
    </script> --}}
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


        function setsupplierFunction(t, t1, t2) {
            document.getElementById("fabricStruct").value = t1;

            document.getElementById("fabricPattern").value = t2;

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
    <script>
        // Get all input text boxes with the class "input-text"
        let inputBoxes = document.getElementsByClassName("input-text");

        // Initialize the sum from the session storage, or 0 if it's not present
        // let sessionSum = parseFloat(sessionStorage.getItem("sessionSum")) || 0;
        let sessionSum = parseFloat("{{ session('sum') }}") || 0;


        // sessionSum  = sessionSum  + sessionStorage.getItem('sum');
        let sumElem = document.getElementById("sum");
        sumElem.textContent = "" + sessionSum;

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
                // Display the total sum in the "sum" paragraph element
                sumElem.textContent = "" + sum;

                // Store the sum in the session storage with a unique key name
                sessionStorage.setItem("sessionSum_" + Date.now(), sum);
            });
        });
    </script>


@endsection
