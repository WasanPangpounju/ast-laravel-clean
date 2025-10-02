@extends('layouts.astmanufacturing')

@section('content')
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
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ซื้อผ้าเข้าสต็อก</h1>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="box-from">

                <h2 class="title"><i class="fa fa-caret-right"></i> ซื้อผ้าเข้าสต็อก</h2>
                <form method="post" action="{{ route('inventory.store') }}" id="myForm">
                    @csrf

                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">


                    <?php
                    //check end count
                    if (session()->has('endCount')) {
                        $c = session()->get('endCount');
                    } else {
                        $c = 0;
                    }
                    
                    // ดึงข้อมูลหลักจาก Session (สำหรับฟอร์มหลัก)
                    $dt = session()->get('dt') ?? '';
                    $fabricStruct = session()->get('fabricStruct') ?? '';
                    $fabricPattern = session()->get('fabricPattern') ?? '';
                    $fabricW = session()->get('fabricW') ?? '';
                    $customer = session()->get('customer') ?? '';
                    $fabricId = session()->get('fabricId') ?? '';

                    // ดึงข้อมูลใหม่จาก Session (สำหรับฟอร์มซื้อเข้า)
                    $supplierName = session()->get('supplierName') ?? '';
                    $invoiceNo = session()->get('invoiceNo') ?? '';
                    $unitPrice = session()->get('unitPrice') ?? '';
                    $dyeLot = session()->get('dyeLot') ?? '';
                    $location = session()->get('location') ?? '';
                    ?>


                    <div class="container">
                        <div class="row">
                            {{-- แถบเมนู (ไม่ได้แก้ไข) --}}
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

                    {{-- -------------------------------------------------------------------------------- --}}
                    {{-- ส่วนข้อมูลหลัก (วันที่, รหัสผ้า, ผู้ขาย, บิล) - แสดงอยู่ด้านบนสุด --}}
                    <div class="container mt-4 mb-4">
                        <div class="row">
                            
                            <div class="col-md-6 border-right">
                                <h4 class="mb-3" style="color: #1bccbd;">ข้อมูลผ้าและลูกค้า</h4>
                                <p>จำนวนรวม : <strong id="fabriccount"></strong> พับ <strong id="sum"></strong> หลา</p>
                                
                                {{-- โค้ดแสดงข้อมูลที่ถูกเก็บใน Session (Next Data) --}}
                                <?php if ($dt != '' && $fabricStruct != '' && $fabricW != '') {
                                    $date = date('d/m/Y', strtotime($dt));
                                    echo "<div><strong style='color:green;'>ข้อมูลที่บันทึกแล้ว:</strong></div>";
                                    echo "<div><strong>วันที่:</strong> $date</div>";
                                    echo "<div><strong>รหัสผ้า:</strong> $fabricId</div>";
                                    echo "<div><strong>โครงสร้าง/ลาย/หน้ากว้าง:</strong> $fabricStruct / $fabricPattern / $fabricW</div>";
                                    echo "<div><strong>ลูกค้า:</strong> $customer</div>";
                                    
                                    ?>
                                    {{-- Hidden Fields สำหรับส่งค่าซ้ำ --}}
                                    <input type="hidden" name="dt" class="form-control" value="<?php print $dt; ?>">
                                    <input type="hidden" name="fabricStruct" class="form-control" value="<?php print $fabricStruct; ?>">
                                    <input type="hidden" name="fabricPattern" class="form-control" value="<?php print $fabricPattern; ?>">
                                    <input type="hidden" name="fabricW" class="form-control" value="<?php print $fabricW; ?>">
                                    <input type="hidden" name="customer" class="form-control" value="<?php print $customer; ?>">
                                    <input type="hidden" name="fabricId" class="form-control" value="<?php print $fabricId; ?>">
                                    
                                    <input type="hidden" name="supplierName" class="form-control" value="{{ $supplierName }}">
                                    <input type="hidden" name="invoiceNo" class="form-control" value="{{ $invoiceNo }}">
                                    <input type="hidden" name="unitPrice" class="form-control" value="{{ $unitPrice }}">
                                    <input type="hidden" name="dyeLot" class="form-control" value="{{ $dyeLot }}">
                                    <input type="hidden" name="location" class="form-control" value="{{ $location }}">

                                {{-- ฟอร์มกรอกข้อมูลหลัก --}}
                                <?php } else { ?>

                                <div class="form-group">
                                    <label for="dt">วันที่ <span class="text-danger">*</span></label>
                                    <input class="date form-control" type="text" name="dt" autocomplete="off" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="fabricId">รหัสผ้า <span class="text-danger">*</span></label>
                                    <input type="text" name="fabricId" onkeyup="fabricIdFunction('fabricId')"
                                        list="brow" id="fabricIdInput" class="form-control" autocomplete="off"
                                        placeholder="รหัสผ้า" required>
                                    <datalist id="brow">
                                        @foreach ($orders as $stockFS)
                                            <option
                                                value="{{ $stockFS->fabricId }},{{ $stockFS->fabricStructure }},{{ $stockFS->fabricPattern }},{{ $stockFS->fabric_w }}">
                                        @endforeach
                                    </datalist>
                                </div>

                                <div class="form-group">
                                    <label for="fabricStruct">โครงสร้างผ้า <span class="text-danger">*</span></label>
                                    <input type="text" name="fabricStruct" class="form-control" id="fabricStructureInput" placeholder="โครงสร้างผ้า" value="{{ $fabricStruct }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="fabricPattern">ลายผ้า <span class="text-danger">*</span></label>
                                    <input type="text" name="fabricPattern" class="form-control" id="fabricPatternInput" placeholder="ลายผ้า" value="{{ $fabricPattern }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="fabricW">หน้ากว้าง <span class="text-danger">*</span></label>
                                    <input type="text" name="fabricW" class="form-control" id="fabricWidthInput" placeholder="หน้ากว้าง" value="{{ $fabricW }} " required>
                                </div>

                                <div class="form-group">
                                    <label for="customer">ลูกค้า (หากเป็นผ้าฝากผลิต)</label>
                                    <input type="text" name="customer" list="brow2" id="customerInput" class="form-control" placeholder="ลูกค้า" value="{{ $customer }}">
                                    <datalist id="brow2">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->name }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                
                                <script>
                                    function fabricIdFunction(attribute) {
                                        const inputElement = document.getElementById("fabricIdInput");
                                        const selectedValue = inputElement.value;
                                        const values = selectedValue.split(',');

                                        if (values.length >= 4) {
                                            const fabricId = values[0].trim();
                                            const fabricStructure = values[1].trim();
                                            const fabricPattern = values[2].trim();
                                            const fabricWidth = values[3].trim();

                                            if (attribute === 'fabricId') {
                                                document.getElementById("fabricIdInput").value = fabricId;
                                                document.getElementById("fabricStructureInput").value = fabricStructure;
                                                document.getElementById("fabricPatternInput").value = fabricPattern;
                                                document.getElementById("fabricWidthInput").value = fabricWidth;
                                            }
                                        }
                                    }
                                </script>

                                <?php } ?>
                            </div>
                            
                            {{-- COLUMN 2: ข้อมูลการซื้อเข้าใหม่ (Supplier, Invoice, Price, Lot) 🏭💵 --}}
                            <div class="col-md-6">
                                <h4 class="mb-3" style="color: #ec9c06;"><i class="fas fa-warehouse"></i> ข้อมูลการซื้อเข้า</h4>
                                
                                <div class="form-group">
                                    <label for="supplierName">ชื่อผู้ขาย / โรงงาน 🏭 <span class="text-danger">*</span></label>
                                    <input type="text" name="supplierName" class="form-control" id="supplierNameInput" 
                                           placeholder="ผู้ขายผ้า" value="{{ $supplierName }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="invoiceNo">เลขที่บิล / ใบส่งของ 📄 <span class="text-danger">*</span></label>
                                    <input type="text" name="invoiceNo" class="form-control" id="invoiceNoInput" 
                                           placeholder="เลขที่เอกสารซื้อเข้า" value="{{ $invoiceNo }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="unitPrice">ราคาซื้อต่อหลา 💵</label>
                                    <input type="number" step="0.01" name="unitPrice" class="form-control" id="unitPriceInput" 
                                           placeholder="0.00" value="{{ $unitPrice }}">
                                </div>

                                <div class="form-group">
                                    <label for="dyeLot">ล็อตย้อม/การผลิต (Dye Lot) 🧪</label>
                                    <input type="text" name="dyeLot" class="form-control" id="dyeLotInput" 
                                           placeholder="Lot A, B, C..." value="{{ $dyeLot }}">
                                </div>

                                <div class="form-group">
                                    <label for="location">ตำแหน่งจัดเก็บ (Location) 📍</label>
                                    <input type="text" name="location" class="form-control" id="locationInput" 
                                           placeholder="A1, B2/3" value="{{ $location }}">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    {{-- -------------------------------------------------------------------------------- --}}
                    
                    
                    {{-- ส่วนการกรอกจำนวนหลาแต่ละพับ (แสดงผลแบบเดิม: 8 คอลัมน์) --}}
                    <div class="container">
                        <div class="row">
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
                                                <input type="number" name="sumYard[{{ $j + 1 }}]" maxlength="3"
                                                    class="input-text" id="{{ $inputId }}"
                                                    style="width: 50%; font-size: 14pt; font-weight: bold; float: right;"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    @endfor
                                    <p id="show{{ $i + 1 }}"
                                        style="background-color: rgb(255, 238, 0);margin-top: 2rem;">
                                        รวมแถวที่ {{ $i + 1 }}</p>

                                </div>
                            @endfor
                        </div>
                    </div>
                    


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
            </div>
        </div> <script>
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
            document.getElementById('priceM').value = pyd;

        }

        function priceToY(valNum) {
            var orderyd = document.getElementById('orderSumYard').value;
            var sumPriceYd = orderyd * valNum;
            var pm = (sumPriceYd / (orderyd / 0.9144)).toFixed(2);
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

        for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
        }
    </script>

    <script>
        function supplierFunction(id) {
            var input, filter, ul, li, a, i, txtValue;

            input = document.getElementById(id);
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
            li = ul.getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

        }

        function setOrderIdFunction(t, t1, t2, t3, t4) {
            document.getElementById("orderId").value = t1;
            document.getElementById("fabricId").value = t2;
            document.getElementById("fabricStruct").value = t3;
            document.getElementById("refId").value = t4;

            ul = document.getElementById('supp');
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
        let sessionSum = parseFloat("{{ session('sum') }}") || 0;

        var c = <?php echo $c; ?>;
        let fabriccount = c;

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