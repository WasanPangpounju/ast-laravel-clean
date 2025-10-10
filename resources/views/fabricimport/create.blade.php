@extends('layouts.astmanufacturing')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>

    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="/inventory/">คลังสินค้า</a></li>
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

                {{-- เปลี่ยน action ให้ยิงเข้า fabricimport.store --}}
                <form method="post" action="{{ route('fabricimport.store') }}" id="myForm">
                    @csrf

                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">

                    <?php
                        //check end count
                        $c = session()->has('endCount') ? session()->get('endCount') : 0;

                        // ดึงข้อมูลหลักจาก Session (สำหรับฟอร์มหลัก)
                        $dt            = session('dt') ?? '';
                        $fabricStruct  = session('fabricStruct') ?? '';
                        $fabricPattern = session('fabricPattern') ?? '';
                        $fabricW       = session('fabricW') ?? '';
                        $customer      = session('customer') ?? '';
                        $fabricId      = session('fabricId') ?? '';

                        // ดึงข้อมูลซื้อเข้า (snake_case ให้ตรงกับ Controller)
                        $supplier_name = session('supplier_name') ?? '';
                        $invoice_no    = session('invoice_no') ?? '';
                        $unit_price    = session('unit_price') ?? '';
                        $dye_lot       = session('dye_lot') ?? '';
                        $location      = session('location') ?? '';
                        $SONumber      = session('SONumber') ?? '';
                    ?>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn b_order" type="button" style="width:60%;margin:0.5rem;background-color:#ebd575;">
                                    <a href="{{ route('inventory.index') }}">ออร์เดอร์ลูกค้า</a>
                                </button>
                                <button class="btn b_order" type="button" style="width:60%;margin:0.5rem;background-color:#aca06e;">
                                    <a href="{{ route('fabricout.index') }}">พิมพ์บิลส่งของ</a>
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn b_order" type="button" style="width:70%;margin:0.5rem;background-color:#1bccbd;">
                                    <a href="{{ route('inventory.create') }}">คีย์ผ้าเข้าสต็อก</a>
                                </button>
                                <button class="btn b_order" type="button" style="width:70%;margin:0.5rem;background-color:#8a8a8a;">
                                    <a href="{{ route('fabricout.create') }}">เปิดบิลผ้า</a>
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn b_order" type="button" style="width:55%;margin:0.5rem;background-color:#ec9c06;">
                                    <a href="{{ route('stockfabric.index') }}">สต็อกผ้า</a>
                                </button>
                                <button class="btn b_order" type="button" style="width:55%;margin:0.5rem;background-color:rgb(175,163,110);">
                                    <a href="{{ route('fabricdeposit.index') }}">สต็อกผ้าฝากจัดเก็บ</a>
                                </button>
                            </div>
                            <div class="col-md-3">
                                {{-- ปรับลิงก์ไปหน้าตรวจสอบ “ผ้าซื้อเข้า” --}}
                                <button class="btn b_order" type="button" style="width:70%;margin:0.5rem;background-color:#ec9c06;">
                                    <a href="{{ route('fabricimport.check.index') }}">ตรวจสอบคีย์ผ้าซื้อเข้าสต็อก</a>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- -------------------------------------------------------------------------------- --}}
                    {{-- ส่วนข้อมูลหลัก + ซื้อเข้า --}}
                    <div class="container mt-4 mb-4">
                        <div class="row">

                            {{-- Column 1: ข้อมูลผ้าและลูกค้า --}}
                            <div class="col-md-6 border-right">
                                <h4 class="mb-3" style="color:#1bccbd;">ข้อมูลผ้าและลูกค้า</h4>
                                <p>จำนวนรวม : <strong id="fabriccount"></strong> พับ <strong id="sum"></strong> หลา</p>

                                {{-- ถ้ามีค่าจาก Session (ตอนกด Next Data) --}}
                                <?php if ($dt !== '' && $fabricStruct !== '' && $fabricW !== '') {
                                    $date = date('d/m/Y', strtotime($dt));
                                    echo "<div><strong style='color:green;'>ข้อมูลที่บันทึกแล้ว:</strong></div>";
                                    echo "<div><strong>วันที่:</strong> {$date}</div>";
                                    echo "<div><strong>รหัสผ้า:</strong> {$fabricId}</div>";
                                    echo "<div><strong>โครงสร้าง/ลาย/หน้ากว้าง:</strong> {$fabricStruct} / {$fabricPattern} / {$fabricW}</div>";
                                    echo "<div><strong>ลูกค้า:</strong> {$customer}</div>";
                                ?>
                                    {{-- Hidden fields เพื่อส่งค่าซ้ำ --}}
                                    <input type="hidden" name="dt"            value="{{ $dt }}">
                                    <input type="hidden" name="fabricStruct"  value="{{ $fabricStruct }}">
                                    <input type="hidden" name="fabricPattern" value="{{ $fabricPattern }}">
                                    <input type="hidden" name="fabricW"       value="{{ $fabricW }}">
                                    <input type="hidden" name="customer"      value="{{ $customer }}">
                                    <input type="hidden" name="fabricId"      value="{{ $fabricId }}">

                                    <input type="hidden" name="supplier_name" value="{{ $supplier_name }}">
                                    <input type="hidden" name="invoice_no"    value="{{ $invoice_no }}">
                                    <input type="hidden" name="unit_price"    value="{{ $unit_price }}">
                                    <input type="hidden" name="dye_lot"       value="{{ $dye_lot }}">
                                    <input type="hidden" name="location"      value="{{ $location }}">
                                    <input type="hidden" name="SONumber"      value="{{ $SONumber }}">

                                <?php } else { ?>

                                    <div class="form-group">
                                        <label for="dt">วันที่ <span class="text-danger">*</span></label>
                                        <input class="date form-control" type="text" name="dt" id="dt" autocomplete="off" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="fabricId">รหัสผ้า <span class="text-danger">*</span></label>
                                        <input type="text" name="fabricId" onkeyup="fabricIdFunction('fabricId')"
                                               list="brow" id="fabricIdInput" class="form-control" autocomplete="off"
                                               placeholder="รหัสผ้า" required>
                                        <datalist id="brow">
                                            @foreach ($orders as $stockFS)
                                                <option value="{{ $stockFS->fabricId }},{{ $stockFS->fabricStructure }},{{ $stockFS->fabricPattern }},{{ $stockFS->fabric_w }}">
                                            @endforeach
                                        </datalist>
                                    </div>

                                    <div class="form-group">
                                        <label for="fabricStructureInput">โครงสร้างผ้า <span class="text-danger">*</span></label>
                                        <input type="text" name="fabricStruct" class="form-control" id="fabricStructureInput"
                                               placeholder="โครงสร้างผ้า" value="{{ $fabricStruct }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="fabricPatternInput">ลายผ้า <span class="text-danger">*</span></label>
                                        <input type="text" name="fabricPattern" class="form-control" id="fabricPatternInput"
                                               placeholder="ลายผ้า" value="{{ $fabricPattern }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="fabricWidthInput">หน้ากว้าง <span class="text-danger">*</span></label>
                                        <input type="text" name="fabricW" class="form-control" id="fabricWidthInput"
                                               placeholder="หน้ากว้าง" value="{{ $fabricW }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="customerInput">ลูกค้า (หากเป็นผ้าฝากผลิต)</label>
                                        <input type="text" name="customer" list="brow2" id="customerInput"
                                               class="form-control" placeholder="ลูกค้า" value="{{ $customer }}">
                                        <datalist id="brow2">
                                            @foreach ($customers as $cst)
                                                <option value="{{ $cst->name }}">
                                            @endforeach
                                        </datalist>
                                    </div>

                                    <script>
                                        function fabricIdFunction(attribute) {
                                            const inputElement   = document.getElementById("fabricIdInput");
                                            const selectedValue  = inputElement.value;
                                            const values         = selectedValue.split(',');

                                            if (values.length >= 4) {
                                                const fabricId        = values[0].trim();
                                                const fabricStructure = values[1].trim();
                                                const fabricPattern   = values[2].trim();
                                                const fabricWidth     = values[3].trim();

                                                if (attribute === 'fabricId') {
                                                    document.getElementById("fabricIdInput").value         = fabricId;
                                                    document.getElementById("fabricStructureInput").value   = fabricStructure;
                                                    document.getElementById("fabricPatternInput").value     = fabricPattern;
                                                    document.getElementById("fabricWidthInput").value       = fabricWidth;
                                                }
                                            }
                                        }
                                    </script>

                                <?php } ?>
                            </div>

                            {{-- Column 2: ข้อมูลการซื้อเข้า --}}
                            <div class="col-md-6">
                                <h4 class="mb-3" style="color:#ec9c06;"><i class="fas fa-warehouse"></i> ข้อมูลการซื้อเข้า</h4>

                                <div class="form-group">
                                    <label for="supplier_name">ชื่อผู้ขาย / โรงงาน 🏭 <span class="text-danger">*</span></label>
                                    <input type="text" name="supplier_name" class="form-control" id="supplier_name"
                                           placeholder="ผู้ขายผ้า" value="{{ $supplier_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="invoice_no">เลขที่บิล / ใบส่งของ 📄 <span class="text-danger">*</span></label>
                                    <input type="text" name="invoice_no" class="form-control" id="invoice_no"
                                           placeholder="เลขที่เอกสารซื้อเข้า" value="{{ $invoice_no }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="unit_price">ราคาซื้อต่อหลา 💵</label>
                                    <!-- <input type="number" step="0.01" name="unit_price" class="form-control" id="unit_price"
                                           placeholder="0.00" value="{{ $unit_price }}"> -->
                                           <!-- <input type="text" name="unitPrice" ...> -->

                                           <input
  type="number"
  step="0.01"
  name="unit_price"
  id="unit_price"
  class="form-control"
  placeholder="0.00"
  value="{{ $unit_price }}"
  inputmode="decimal"
  lang="en"
  maxlength="10"
  oninput="this.value = this.value.replace(',', '.')" />
 
                                </div>

                                <div class="form-group">
                                    <label for="dye_lot">ล็อตย้อม/การผลิต (Dye Lot) 🧪</label>
                                    <input type="text" name="dye_lot" class="form-control" id="dye_lot"
                                           placeholder="Lot A, B, C..." value="{{ $dye_lot }}">
                                </div>

                                <div class="form-group">
                                    <label for="location">ตำแหน่งจัดเก็บ (Location) 📍</label>
                                    <input type="text" name="location" class="form-control" id="location"
                                           placeholder="A1, B2/3" value="{{ $location }}">
                                </div>

                                <div class="form-group">
                                    <label for="SONumber">SO Number</label>
                                    <input type="text" name="SONumber" class="form-control" id="SONumber"
                                           placeholder="SO-xxxx" value="{{ $SONumber }}">
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- -------------------------------------------------------------------------------- --}}

                    {{-- ช่องคีย์ 8 x 20 --}}
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
                                                <div style="background-color: rgb(255, 238, 0); margin-bottom: -1.5rem; text-align: center; margin-right: 2.5rem;">
                                                    {{ $j + 1 }}
                                                </div>
                                                <input type="number" name="sumYard[{{ $j + 1 }}]" maxlength="3"
                                                       class="input-text" id="{{ $inputId }}"
                                                       style="width: 50%; font-size: 14pt; font-weight: bold; float: right;"
                                                       autocomplete="off">
                                            </div>
                                        </div>
                                    @endfor
                                    <p id="show{{ $i + 1 }}" style="background-color: rgb(255, 238, 0);margin-top: 2rem;">
                                        รวมแถวที่ {{ $i + 1 }}
                                    </p>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="line_btn">
                        <button name="submit" value="gotoIndex" class="btn b_order clean">
                            <img src="{{ asset('assets/images/xmark-solid.png') }}" width="15"> ยกเลิก
                        </button>

                        <button name="submit" value="nextData" class="btn b_save">
                            <img src="{{ asset('assets/images/circle-check-solid.png') }}" width="17"> บันทึกรายการถัดไป
                        </button>

                        <button name="submit" value="endData" class="btn b_save">
                            <img src="{{ asset('assets/images/circle-check-solid.png') }}" width="17"> บันทึกเสร็จสิ้น
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Utils --}}
    <script>
        $('.date').datepicker({
            format: 'dd/mm/yyyy',
            orientation: "bottom",
            autoclose: true
        });
    </script>

    <script>
        // ซ่อนไอเท็มใน ul#supp ถ้ามี
        var ul = document.getElementById("supp");
        if (ul) {
            var li = ul.getElementsByTagName("li");
            for (var i = 0; i < li.length; i++) { li[i].style.display = "none"; }
        }
    </script>

    <script>
        // Enter เพื่อโฟกัสช่องถัดไป
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
        // รวมยอดทั้งหมด + รวมแถวละคอลัมน์
        let inputBoxes = document.getElementsByClassName("input-text");
        let sessionSum = parseFloat("{{ session('sum') }}") || 0;

        var c = <?php echo (int) $c; ?>;
        let fabriccount = c;

        let sumElem = document.getElementById("sum");
        if (sumElem) sumElem.textContent = "" + sessionSum;

        let fabriccountsum = document.getElementById("fabriccount");
        if (fabriccountsum) fabriccountsum.textContent = "" + fabriccount;

        Array.from(inputBoxes).forEach(inputBox => {
            inputBox.addEventListener("change", function() {
                let numbers = Array.from(inputBoxes).map(b => {
                    let n = parseFloat(b.value);
                    return isNaN(n) ? 0 : n;
                });

                let sum = numbers.reduce((t, n) => t + n, 0);
                let sumback = "{{ session('sum') }}";
                sum = sum + Number(sumback);

                fabriccount = 0;
                for (let i = 0; i < inputBoxes.length; i++) {
                    if (inputBoxes[i].value.trim() !== '') {
                        fabriccount = fabriccount + 1;
                    }
                }
                fabriccount = fabriccount + c;

                if (sumElem) sumElem.textContent = "" + sum;
                if (fabriccountsum) fabriccountsum.textContent = "" + fabriccount;

                sessionStorage.setItem("sessionSum_" + Date.now(), sum);
            });
        });

        // sum per column (8 columns)
        function attachSumPerColumn(from, to, labelId) {
            const arr = [];
            for (let j = from; j <= to; j++) {
                const el = document.getElementById('sumYard' + j);
                if (el) arr.push(el);
            }
            arr.forEach(box => {
                box.addEventListener("change", function() {
                    let nums = arr.map(x => {
                        let n = parseFloat(x.value);
                        return isNaN(n) ? 0 : n;
                    });
                    let s = nums.reduce((t, n) => t + n, 0);
                    const lb = document.getElementById(labelId);
                    if (lb) lb.textContent = "รวม: " + s;
                });
            });
        }
        attachSumPerColumn(1, 20, "show1");
        attachSumPerColumn(21, 40, "show2");
        attachSumPerColumn(41, 60, "show3");
        attachSumPerColumn(61, 80, "show4");
        attachSumPerColumn(81, 100, "show5");
        attachSumPerColumn(101, 120, "show6");
        attachSumPerColumn(121, 140, "show7");
        attachSumPerColumn(141, 160, "show8");
    </script>

    <script>
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.oninput = () => {
                if (input.value.length > input.maxLength) input.value = input.value.slice(0, input.maxLength);
            };
        });
    </script>
@endsection
