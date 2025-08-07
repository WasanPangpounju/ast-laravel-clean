@extends('layouts.astmanufacturing')

@section('content')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
        rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item"><a href="/inventory/">คลังสินค้า</a>
                </li>
                <li class="breadcrumb-item active">คีย์ผ้าเข้าสต็อก</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> คีย์ผ้าเข้าสต็อก</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">

                <h2 class="title"><i class="fa fa-caret-right"></i> คีย์ผ้าเข้าสต็อก</h2>
                <form method="post" action="{{ route('fabriccheck.store') }}" id="myForm">
                    @csrf

                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">


                    <?php
                    //check end count
                    if (session()->has('endCount')) {
                        $c = session()->get('endCount');
                    } else {
                        $c = 0;
                    }
                    ?>


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
                                if (session()->has('customer')) {
                                    $customer = session()->get('customer');
                                } else {
                                    $customer = '';
                                }
                                
                                ?>
                                <?php if ($dt != '' && $fabricStruct != ''&& $fabricW != '') {
                                    print($date = date('d/m/Y', strtotime($dt)));
                                    print($fabricStruct);
                             print($fabricPattern );
                                    print($fabricW);
                                    print($customer);
                                        ?>
                                <input type="hidden" name="dt" class="form-control" value="<?php print $dt; ?>">
                                <input type="hidden" name="fabricStruct" class="form-control" value="<?php print $fabricStruct; ?>">
                                <input type="hidden" name="fabricPattern" class="form-control" value="<?php print $fabricPattern; ?>">
                                <input type="hidden" name="fabricW" class="form-control" value="<?php print $fabricW; ?>">
                                <input type="hidden" name="customer" class="form-control" value="<?php print $customer; ?>">
                                <?php }else{ ?>

                                <div class="form-group">
                                    <label for="createDate">วันที่</label>
                                    @foreach ($StockFabricEdit2 as $StockEdit2)
                                        <input class="date form-control" type="text" name="dt" autocomplete="off"
                                            {{-- value="{{ $StockEdit2->lastCreateDate }} --}}
                                            value="{{ $date = date('d/m/Y', strtotime($StockEdit2->lastCreateDate)) }}
                                            ">
                                    @endforeach
                                    <script type="text/javascript">
                                        $('.date').datepicker({
                                            format: 'dd/mm/yyyy',
                                            orientation: "bottom",
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="fabricStruct">โครงสร้างผ้า </label>
                                    @if (isset($backdata))
                                        <input type="text" name="fabricStruct" onkeyup="supplierFunction('fabricStruct')"
                                            class="form-control" id="fabricStruct" placeholder="โครงสร้างผ้า"
                                            value="{{ $StockEdit2->fabricStruct }}">
                                    @else
                                        <input type="text" name="fabricStruct"
                                            onkeyup="supplierFunction('fabricStruct')" class="form-control"
                                            id="fabricStruct" placeholder="โครงสร้างผ้า"
                                            value="{{ $StockEdit2->fabricStruct }}" required>
                                    @endif

                                    {{-- <ul id="supp">
                                    @foreach ($orders as $order)
                                        <li><a
                                                href="javascript:setsupplierFunction('supp', '{{ $order->fabricStructure }}' , '{{ $order->fabricPattern }}');">{{ $order->fabricStructure }}
                                                {{ $order->fabricPattern }}</a>
                                        </li>
                                    @endforeach
                                </ul> --}}
                                    <ul id="supp">
                                        {{-- @foreach ($orders as $stockFS)
                                        <li><a
                                                href="javascript:setsupplierFunction('supp', '{{ $stockFS->fabricStructure }}' , '{{ $stockFS->fabricPattern }}' , '{{ $stockFS->fabric_w }}');">{{ $stockFS->fabricStructure }}
                                                {{ $stockFS->fabricPattern }} หน้ากว้าง {{ $stockFS->fabric_w }} '</a>
                                        </li>
                                    @endforeach --}}
                                    </ul>

                                </div>

                                <div class="form-group">
                                    <label for="fabricPattern">ลายผ้า </label>
                                    @if (isset($backdata))
                                        <input type="text" name="fabricPattern" class="form-control"
                                            id="fabricPattern" placeholder="" value="{{ $backdata->fabricPattern }}">
                                    @else
                                        <input type="text" name="fabricPattern" class="form-control"
                                            id="fabricPattern" placeholder="ลายผ้า"
                                            value="{{ $StockEdit2->fabricPattern }}" required>
                                    @endif

                                </div>

                                <div class="form-group">
                                    <label for="fabricW">หน้ากว้าง </label>

                                    @if (isset($backdata))
                                        <input type="text" name="fabricW" class="form-control" id="fabricW"
                                            placeholder="หน้ากว้าง" value="{{ $backdata->fabricW }}" required>
                                    @else
                                        <input type="text" name="fabricW" class="form-control" id="fabricW"
                                            placeholder="หน้ากว้าง" value="{{ $StockEdit2->fabricW }}" required>
                                    @endif

                                </div>
                                <div class="form-group">
                                    <label for="customer">ลูกค้า </label>

                                    @if (isset($backdata))
                                        <input type="text" name="customer" onkeyup="customerFunction('name')"
                                            list="brow" id="customer" class="form-control" id="customer"
                                            placeholder="customer" value="{{ $backdata->fabricW }}">
                                        <datalist id="brow">
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->name }}">
                                            @endforeach

                                        </datalist>
                                    @else
                                        <input type="text" name="customer" onkeyup="customerFunction('name')"
                                            list="brow" id="customer" class="form-control" id="customer"
                                            value="{{ $StockEdit2->customer }}" placeholder="customer">
                                        <datalist id="brow">
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->name }}">
                                            @endforeach
                                        </datalist>
                                    @endif

                                </div>
                                <?php } ?>
                            </div>

                            <?php
                            $Stockcount = count($StockFabricEdit);
                            $countF = $Stockcount / 160;
                            ?>

                            <!-- Your input fields go here -->
                            <?php $pageall = ceil($countF); ?>
                            {{-- @for ($a = 0; $a < $pageall; $a++) --}}
                            @for ($i = 0; $i < 8; $i++)
                                <div class="col-md-1" style="margin: -0.5em;">
                                    @for ($k = 0; $k < 20; $k++)
                                        <div class="form-group">
                                            @php
                                                $j = $i * 20 + $k + $c;
                                                $l = $i * 20 + $k;
                                                
                                                $inputId = 'sumYard' . ($l + 1);
                                            @endphp
                                            <label for="sumYard{{ $j + 1 }}"></label>
                                            <div>
                                                <div
                                                    style="background-color: rgb(255, 238, 0); margin-bottom: -1.5rem; text-align: center; margin-right: 2.5rem;">
                                                    {{ $j + 1 }}
                                                </div>
                                                {{-- @foreach ($StockFabricEdit as $key => $StockEdit) --}}
                                                @if ($j < $Stockcount)
                                                    <input type="number" name="sumYard[{{ $j + 1 }}]"
                                                        maxlength="3" class="input-text" id="{{ $inputId }}"
                                                        style="width: 50%; font-size: 14pt; font-weight: bold; float: right;"
                                                        value="{{ $StockFabricEdit[$j]->sumYard }}" autocomplete="off">
                                                @else
                                                    <input type="number" name="sumYard[{{ $j + 1 }}]"
                                                        maxlength="3" class="input-text" id="{{ $inputId }}"
                                                        style="width: 50%; font-size: 14pt; font-weight: bold; float: right;"
                                                        autocomplete="off">
                                                @endif
                                                {{-- @endforeach --}}
                                            </div>
                                        </div>
                                    @endfor
                                    <p id="show{{ $i + 1 }}"
                                        style="background-color: rgb(255, 238, 0);margin-top: 2rem;">
                                        รวมแถวที่ {{ $i + 1 }}</p>

                                </div>
                            @endfor
                            <br>


                            {{-- @endfor --}}


                            {{-- @php
                            $data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
                            $dataCount = count($data);
                        @endphp

                        <table border="1">
                            @for ($i = 0; $i < 8; $i++)
                                <tr>
                                    @for ($j = 0; $j < 20; $j++)
                                        @php
                                            $index = ($i + $j * 8) % $dataCount;
                                            $value = $index < $dataCount ? $data[$index] : '';
                                        @endphp
                                        <td>{{ $value }}</td>
                                    @endfor
                                </tr>
                            @endfor
                        </table> --}}



                            <div class="line_btn">
                                <button name="submit" value="gotoIndex" class="btn b_order clean"><img
                                        src="<?php echo asset('assets/images/xmark-solid.png'); ?>" width="15"> ยกเลิก</button>

                                <input type="text" name="sudorefId" id="fabricStruct"
                                    value="{{ $StockFabricEdit2[0]->refId }}">

                                <button name="submit" value="nextData" class="btn b_save"><img
                                        src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
                                    บันทึกรายการถัดไป</button>

                                <button name="submit" value="endData" class="btn b_save"><img
                                        src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
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


        // function setsupplierFunction(t, t1, t2) {
        //     document.getElementById("fabricStruct").value = t1;

        //     document.getElementById("fabricPattern").value = t2;

        //     ul = document.getElementById(t);
        //     //ul.style.display = "none";  
        //     li = ul.getElementsByTagName("li");

        //     for (i = 0; i < li.length; i++) {
        //         li[i].style.display = "none";
        //     }

        // }

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

        var c = <?php echo $c; ?>;
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

    <script>
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.oninput = () => {
                if (input.value.length > input.maxLength) input.value = input.value.slice(0, input.maxLength);
            };
        });
    </script>
@endsection
