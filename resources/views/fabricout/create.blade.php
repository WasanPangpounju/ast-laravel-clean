@extends('layouts.astmanufacturing')

@section('content')
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        #stockPicker option[hidden] { display:none; }
    </style>

    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="/inventory/">คลังสินค้า</a></li>
                <li class="breadcrumb-item active">เปิดบิลผ้า</li>
            </ol>
        </div>

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> เปิดบิลผ้า</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="box-from">

                <?php
                if (session()->has('no')) { $no = session()->get('no'); } else { $no = ''; }
                $vatNox = '';
                if (session()->has('vatNo')) { $vatNo = session()->get('vatNo'); } else { $vatNo = ''; }
                if (session()->has('vatType')) { $vatType = session()->get('vatType'); $vatNox = $vatType.'-'.$vatNo; } else { $vatType = ''; }
                if ($vatNox == '') { $vatNox = $vatNox.'A-'.$vatA; }
                ?>

                <h2 class="title">
                    <i class="fa fa-caret-right"></i>
                    บันทึกเปิดบิลผ้าเลขที่ <strong id="vatno">{{ $vatNox }}</strong>
                </h2>

                <button type="button" class="btn b_order" name="submit" value="index">
                    <a href="{{ route('inventory.index') }}">จัดส่งตามใบสั่งซื้อ</a>
                </button>
                <br><br>

                <form method="post" action="{{ route('fabricout.store') }}" id="myForm">
                    @csrf

                    @php
                        // แยก vat จากป้าย h2
                        $vatNoxParts = isset($vatNox) ? explode('-', $vatNox) : ['', ''];
                        $realVatNo   = empty($vatNo)   ? ($vatNoxParts[1] ?? '') : $vatNo;
                        $realVatType = empty($vatType) ? ($vatNoxParts[0] ?? '') : $vatType;

                        // ค่าเริ่มต้นสำหรับ stockKey (ถ้ามี snapshot เก็บไว้)
                        $selStockCustomer = old('stockCustomer',      session('fabricout_group.stockCustomer')      ?? (session('customerName') ?: 'AST'));
                        $selStockStruct   = old('stockFabricStruct',  session('fabricout_group.stockFabricStruct')  ?? session('fabricStruct'));
                        $selStockPattern  = old('stockFabricPattern', session('fabricout_group.stockFabricPattern') ?? session('fabricPattern'));
                        $selStockW        = old('stockFabricW',       session('fabricout_group.stockFabricW')       ?? session('fabricW'));
                    @endphp

                    {{-- ส่งเลขบิล/ประเภทบิลซ้ำน้อยสุดเท่าที่จำเป็น --}}
                    <input type="hidden" id="vatType1" name="vatType" value="{{ $realVatType }}">
                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">

                    {{-- hidden สำหรับคีย์ "ตัดจากสต็อก" --}}
                    <input type="hidden" id="stockCustomer"      name="stockCustomer"      value="{{ $selStockCustomer }}">
                    <input type="hidden" id="stockFabricStruct"  name="stockFabricStruct"  value="{{ $selStockStruct }}">
                    <input type="hidden" id="stockFabricPattern" name="stockFabricPattern" value="{{ $selStockPattern }}">
                    <input type="hidden" id="stockFabricW"       name="stockFabricW"       value="{{ $selStockW }}">

                    <div class="container">
                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn b_order" type="button" style="width: 60%;margin:0.5rem;background-color: #ebd575;">
                                    <a href="{{ route('inventory.index') }}">ออร์เดอร์ลูกค้า</a>
                                </button>
                                <button class="btn b_order" type="button" style="width: 60%;margin:0.5rem;background-color: #aca06e;">
                                    <a href="{{ route('fabricout.index') }}">พิมพ์บิลส่งของ</a>
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn b_order" type="button" style="width: 70%;margin:0.5rem;background-color: #1bccbd;">
                                    <a href="{{ route('inventory.create') }}">คีย์ผ้าเข้าสต็อก</a>
                                </button>
                                <button class="btn b_order" type="button" style="width: 70%;margin:0.5rem;background-color: #8a8a8a;">
                                    <a href="{{ route('fabricout.create') }}">เปิดบิลผ้า</a>
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn b_order" type="button" style="width: 55%;margin:0.5rem;background-color: #ec9c06;">
                                    <a href="{{ route('stockfabric.index') }}">สต็อกผ้า</a>
                                </button>
                                <button class="btn b_order" type="button" style="width: 55%;margin:0.5rem;background-color: rgb(175, 163, 110);">
                                    <a href="{{ route('fabricdeposit.index') }}">สต็อกผ้าฝากจัดเก็บ</a>
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn b_order" type="button" style="width: 70%;margin:0.5rem;background-color: #ec9c06;">
                                    <a href="{{ route('fabriccheck.index') }}">ตรวจสอบคีย์ผ้าเข้าสต็อก</a>
                                </button>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (session()->has('endCount')) { $c = session()->get('endCount'); } else { $c = 0; }
                    ?>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">

                                {{-- แสดงยอดรวม --}}
                                <p>จำนวนรวม : <strong id="fabriccount"></strong> พับ <strong id="sum"></strong> หลา</p>
                                <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">

                                <?php
                                $dt               = session('dt', '');
                                $customerName     = session('customerName', '');
                                $receiveName      = session('receiveName', '');
                                $fabricStruct     = session('fabricStruct', '');
                                $fabricPattern    = session('fabricPattern', '');
                                $fabricW          = session('fabricW', '');
                                $purchaseOrder    = session('purchaseOrder', '');
                                $comment          = session('comment', '');
                                $receiveType      = session('receiveType', '');
                                $order_id         = session('orderId', '');
                                $customerReplace  = session('customerReplace', '');
                                $fabricStructReplace = session('fabricStructReplace', '');
                                $vatType          = session('vatType', '');
                                ?>

                                {{-- ============ เลือก "ตัดจากสต็อก" (เสมอ) ============ --}}
                                
                                <div class="form-group">
                                    <label for="stockPicker" style="font-weight:700">ตัดจากสต็อก (เลือกรายการ)</label>
                                    <input type="text" id="stockFilter" class="form-control"
                                           placeholder="ค้นหา: ลูกค้า / โครงสร้าง / ลาย / หน้ากว้าง" style="margin-bottom:6px;">
<select id="stockPicker" class="form-control" size="8">
  <option value="">— เลือกสต็อก —</option>
  @forelse(($stockLots ?? []) as $s)
    @php
      $isSelected =
        $selStockCustomer === $s->customer &&
        $selStockStruct   === $s->fabricStruct &&
        $selStockPattern  === $s->fabricPattern &&
        $selStockW        === $s->fabricW;
    @endphp
    <option
      value="{{ $s->customer }}|{{ $s->fabricStruct }}|{{ $s->fabricPattern }}|{{ $s->fabricW }}"
      data-customer="{{ $s->customer }}"
      data-struct="{{ $s->fabricStruct }}"
      data-pattern="{{ $s->fabricPattern }}"
      data-w="{{ $s->fabricW }}"
      {{ $isSelected ? 'selected' : '' }}
    >
      [{{ $s->customer }}] {{ $s->fabricStruct }} | {{ $s->fabricPattern }} | {{ $s->fabricW }}''
      @if(isset($s->yardsRemaining))
        — คงเหลือ {{ number_format($s->yardsRemaining,2) }} yd / {{ $s->foldsRemaining ?? 0 }} พับ
      @endif
    </option>
  @empty
    <option value="">(ไม่มีรายการให้เลือก)</option>
  @endforelse
</select>


                                    @if(!empty($selStockStruct))
                                    <small id="stockCurrentDisplay" class="text-muted d-block mt-1">
  @if(!empty($selStockStruct))
    สต็อกที่เลือกปัจจุบัน:
    <b>[{{ $selStockCustomer }}]</b>
    {{ $selStockStruct }} | {{ $selStockPattern }} | {{ $selStockW }}''
  @else
    สต็อกที่เลือกปัจจุบัน: <i>ยังไม่ได้เลือก</i>
  @endif
</small>

                                    @endif
                                </div>
                                {{-- ============ /เลือก "ตัดจากสต็อก" ============ --}}

                                <?php if ($dt != '' && $fabricStruct != '') { print("<div style='margin-left: 1.5em;'>");
                                    print($date = date('d/m/Y', strtotime($dt)).' <br>');
                                    print('<b>ผู้สั่ง : </b>'. $customerName.' ');
                                    if($receiveType == 'deposit') { print(' <b>ฝากจัดเก็บสินค้า</b> '); }
                                    print('  <b>ผู้รับ : </b>'. $receiveName. ' '.'<br>');
                                    print('<b>โครงสร้าง :</b> '.$fabricStruct.'<br>');
                                    print('<b>ลายผ้า:</b> '.$fabricPattern .'<br>');
                                    print('<b>หน้ากว้าง:</b> '.$fabricW .'<br>');
                                    print('<b>แทนผู้สั่งซื้อ:</b> '.$customerReplace .'<br>');
                                    print('<b>แทนโครงสร้าง:</b> '.$fabricStructReplace .'<br>');
                                    print('<b>ประเภทบิล:</b> '.$vatType .'<br>');
                                    print(' <b>หมายเหตุ : </b>'. $comment .'<br>');
                                ?>

                                    {{-- ให้ส่ง vatNo/vatType ทุกครั้ง --}}
                                    <input type="hidden" name="vatNo" id="vatNo1" value="{{ $realVatNo }}">
                                    <input type="hidden" name="vatType" id="vatType1" value="{{ $realVatType }}">

                                    {{-- ค่าหัวบิล --}}
                                    <input type="hidden" name="dt"           value="<?php print $dt; ?>">
                                    <input type="hidden" name="fabricStruct" value="<?php print $fabricStruct; ?>">
                                    <input type="hidden" name="fabricPattern"value="<?php print $fabricPattern; ?>">
                                    <input type="hidden" name="fabricW"      value="<?php print $fabricW; ?>">

                                    <input type="hidden" name="fabricStructReplace" value="<?php print $fabricStructReplace; ?>">
                                    <input type="hidden" name="customerReplace"     value="<?php print $customerReplace; ?>">

                                    <input type="hidden" name="customerName" value="<?php print $customerName; ?>">
                                    <input type="hidden" name="receiveType"  value="<?php print $receiveType; ?>">
                                    <input type="hidden" name="receiveName"  value="<?php print $receiveName; ?>">

                                    <input type="hidden" name="orderId"      value="<?php print $order_id; ?>">
                                    <input type="hidden" name="comment"      value="<?php print $comment; ?>">

                                    {{-- hidden ซ้ำให้แน่ใจว่า key สต็อกถูกส่ง --}}
                                    <input type="hidden" id="stockCustomer"      name="stockCustomer"      value="{{ $selStockCustomer }}">
                                    <input type="hidden" id="stockFabricStruct"  name="stockFabricStruct"  value="{{ $selStockStruct }}">
                                    <input type="hidden" id="stockFabricPattern" name="stockFabricPattern" value="{{ $selStockPattern }}">
                                    <input type="hidden" id="stockFabricW"       name="stockFabricW"       value="{{ $selStockW }}">

                                <?php print("</div>"); } else { ?>

                                    <input type="hidden" name="orderId" value="<?php print $order_id; ?>">

                                    <div class="form-group">
                                        <label for="vatNo1" class="d-block mt-2">เลขที่บิล</label>
                                        <input type="text" id="vatNo1" name="vatNo" class="form-control" style="max-width:80px"
                                               value="{{ $realVatNo }}" min="0" step="1">

                                        <label class="mt-4" for="createDate">วันที่</label>
                                        <input class="date form-control" type="text" name="dt" autocomplete="off">
                                        <script type="text/javascript">
                                            $('.date').datepicker({ format: 'dd-mm-yyyy', orientation: "bottom" });
                                        </script>
                                    </div>

                                    <div class="form-group">
                                        <label for="fabricStruct">โครงสร้างผ้า </label>
                                        <input type="text" name="fabricStruct" onkeyup="supplierFunction('fabricStruct')"
                                               class="form-control" id="fabricStruct" placeholder="โครงสร้างผ้า"
                                               value="{{ $fabricStruct }}" required>

                                        <ul id="supp">
                                            @foreach (($stockLots ?? []) as $s)
                                                <li>
                                                    <a href="javascript:setsupplierFunction('supp','{{ $s->fabricStruct }}','{{ $s->fabricPattern }}','{{ $s->fabricW }}');">
                                                        {{ $s->fabricStruct }} {{ $s->fabricPattern }} หน้ากว้าง {{ $s->fabricW }} '
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="fabricPattern">ลายผ้า</label>
                                                <input type="text" name="fabricPattern" class="form-control" id="fabricPattern"
                                                       placeholder="ลายผ้า" value="{{ $fabricPattern }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="fabricW">หน้ากว้าง</label>
                                                <input type="text" name="fabricW" class="form-control" id="fabricW"
                                                       placeholder="หน้ากว้าง" value="{{ $fabricW }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="customerName">ผู้สั่ง</label>
                                        <input type="text" name="customerName" class="form-control" id="myInput"
                                               onkeyup="myFunction()" placeholder="ผู้สั่ง" value="{{ $customer_name }}" required>

                                        <ul id="myUL">
                                            @foreach ($customers as $customer)
                                                <li><a href="javascript:setcustomerFunction('{{ $customer->name }}');">{{ $customer->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="form-group">
                                        <input type="radio" id="receiveType1" name="receiveType" value="receiver" checked>
                                        <label for="receiver1">ผู้รับ</label>
                                        <input type="radio" id="receiveType2" name="receiveType" value="deposit">
                                        <label for="receiveType2">ฝากจัดเก็บ</label>
                                        <input type="text" name="receiveName" class="form-control" id="receiveName"
                                               placeholder="ผู้รับ" value="" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="fabricStructReplace">แทนโครงสร้างผ้า</label>
                                        <input type="text" name="fabricStructReplace" class="form-control" id="fabricStructReplace"
                                               placeholder="แทนโครงสร้างผ้า" value="{{ $fabricStructReplace }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="customerReplace">แทนผู้สั่งซื้อ</label>
                                        <input type="text" name="customerReplace" class="form-control" id="customerReplace"
                                               placeholder="แทนผู้สั่งซื้อ" value="{{ $customerReplace }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="vatType">ประเภทบิล</label>
                                        <select name="vatType" class="form-control" id="vatType">
                                            <option value="A" selected>บิล A</option>
                                            <option value="B">บิล B</option>
                                            <option value="C">บิล C</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="comment">หมายเหตุ</label>
                                        <input type="text" name="comment" class="form-control" id="comment"
                                               placeholder="หมายเหตุ" value="ได้รับผ้าตามรายการข้างบนนี้ไว้ถูกต้องและเรียบร้อยแล้ว">
                                    </div>
                                <?php } ?>
                            </div>

                            {{-- ช่องกรอกหลา 8x20 = 160 --}}
                            @for ($i = 0; $i < 8; $i++)
                                <div class="col-md-1" style="margin: -0.5em;">
                                    @for ($k = 0; $k < 20; $k++)
                                        <div class="form-group">
                                            @php
                                                $j = $i * 20 + $k + $c;
                                                $l = $i * 20 + $k;
                                                $inputId = 'sumYard' . ($l + 1);
                                            @endphp
                                            <label for="{{ $inputId }}"></label>
                                            <div>
                                                <div style="background-color: rgb(255, 238, 0);margin-bottom: -1.5rem;text-align:center;margin-right:2.5rem;">
                                                    {{ $j + 1 }}
                                                </div>
                                                <input type="number" name="sumYard[{{ $j + 1 }}]" maxlength="3"
                                                       class="input-text" id="{{ $inputId }}"
                                                       style="width: 50%;font-size:14pt;font-weight: bold;float:right;" autocomplete="off">
                                            </div>
                                        </div>
                                    @endfor
                                    <p id="show{{ $i + 1 }}" style="background-color: rgb(255, 238, 0);margin-top: 2rem;">
                                        รวมแถวที่ {{ $i + 1 }}
                                    </p>
                                </div>
                            @endfor

                            <div class="line_btn">
                                <button name="submit" value="gotoIndex" class="btn b_order clean">
                                    <img src="<?php echo asset('assets/images/xmark-solid.png'); ?>" width="15"> ยกเลิก
                                </button>
                                <button name="submit" value="nextData" class="btn b_save">
                                    <img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17"> บันทึกรายการถัดไป
                                </button>
                                <button name="submit" value="endData" class="btn b_save">
                                    <img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17"> บันทึกเสร็จสิ้น
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!--box-from-->
        </div> <!--content-->
    </div> <!-- /.content-wrapper -->

    <script>
        const receiverRadio = document.getElementById("receiveType1");
        const depositRadio  = document.getElementById("receiveType2");
        const selectOption  = document.getElementById("vatType");

        receiverRadio?.addEventListener("click", function(){ selectOption.value = "A"; });
        depositRadio?.addEventListener("click",  function(){ selectOption.value = "C"; });
    </script>

    <script>
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.oninput = () => { if (input.value.length > input.maxLength) input.value = input.value.slice(0, input.maxLength); };
        });
    </script>

    <script>
        var ul = document.getElementById("myUL");
        if (ul) {
            var li = ul.getElementsByTagName("li");
            for (var i = 0; i < li.length; i++) { li[i].style.display = "none"; }
        }
        function myFunction() {
            var input = document.getElementById("myInput");
            var filter = (input.value || '').toUpperCase();
            var ul = document.getElementById("myUL");
            var li = ul.getElementsByTagName("li");
            for (var i = 0; i < li.length; i++) {
                if (filter == "") { for (var j = 0; j < li.length; j++) { li[j].style.display = "none"; } break; }
                var a = li[i].getElementsByTagName("a")[0];
                var txtValue = (a.textContent || a.innerText || '').toUpperCase();
                li[i].style.display = (txtValue.indexOf(filter) > -1) ? "" : "none";
            }
        }
        function setcustomerFunction(t) {
            document.getElementById("myInput").value = t;
            var ul = document.getElementById("myUL");
            var li = ul.getElementsByTagName("li");
            for (var i = 0; i < li.length; i++) { li[i].style.display = "none"; }
        }
    </script>

    <script>
        const inputs = document.getElementsByClassName('input-text');
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].addEventListener('keydown', function(event) {
                if (event.keyCode === 13) { event.preventDefault(); const nextIndex = (i === inputs.length - 1) ? 0 : i + 1; inputs[nextIndex].focus(); }
            });
        }
    </script>

    <script>
        var ul2 = document.getElementById("supp");
        if (ul2) {
            var li2 = ul2.getElementsByTagName("li");
            for (var i = 0; i < li2.length; i++) { li2[i].style.display = "none"; }
        }
        function supplierFunction(id) {
            var input = document.getElementById(id);
            var filter = (input.value || '').toUpperCase().replace(/\s/g, "");
            var ul = document.getElementById("supp");
            var li = ul.getElementsByTagName("li");
            for (var i = 0; i < li.length; i++) {
                if (filter == "") { for (var j = 0; j < li.length; j++) { li[j].style.display = "none"; } break; }
                var a = li[i].getElementsByTagName("a")[0];
                var txtValue = (a.textContent || a.innerText || '').replace(/\s/g, "").toUpperCase();
                li[i].style.display = (txtValue.indexOf(filter) > -1) ? "" : "none";
            }
        }
        function setsupplierFunction(t, t1, t2, t3) {
            document.getElementById("fabricStruct").value  = t1;
            document.getElementById("fabricPattern").value = t2;
            document.getElementById("fabricW").value       = t3;
            var ul = document.getElementById(t);
            var li = ul.getElementsByTagName("li");
            for (var i = 0; i < li.length; i++) { li[i].style.display = "none"; }
        }
    </script>

    <script>
        // รวมค่าหลา/พับทั้งหมด (รองรับ session sum)
        let inputBoxes = document.getElementsByClassName("input-text");
        var c = <?php echo $c; ?>;
        let sessionSum = parseFloat("{{ session('sum') }}") || 0;
        let fabriccount = c;

        document.getElementById("sum").textContent = "" + sessionSum;
        document.getElementById("fabriccount").textContent = "" + fabriccount;

        Array.from(inputBoxes).forEach(inputBox => {
            inputBox.addEventListener("change", function() {
                let numbers = Array.from(inputBoxes).map(inputBox => {
                    let number = parseFloat(inputBox.value);
                    return isNaN(number) ? 0 : number;
                });
                let sum = numbers.reduce((total, number) => total + number, 0);
                let sumback = "{{ session('sum') }}";
                sum = sum + Number(sumback);

                fabriccount = 0;
                for (let i = 0; i < inputBoxes.length; i++) {
                    if (inputBoxes[i].value.trim() !== '') { fabriccount = fabriccount + 1; }
                }
                fabriccount = fabriccount + c;

                document.getElementById("sum").textContent = "" + sum;
                document.getElementById("fabriccount").textContent = "" + fabriccount;
                sessionStorage.setItem("sessionSum_" + Date.now(), sum);
            });
        });

        // รวมรายแถว (8 แถว)
        function sumRange(from, to, showId){
            const arr = [];
            for (let j = from; j <= to; j++) { const el = document.getElementById('sumYard' + j); if (el) arr.push(el); }
            arr.forEach(inp => {
                inp.addEventListener("change", function() {
                    let nums = arr.map(x => { const n = parseFloat(x.value); return isNaN(n) ? 0 : n; });
                    const s = nums.reduce((t, n) => t + n, 0);
                    const lbl = document.getElementById(showId);
                    if (lbl) lbl.textContent = "รวม: " + s;
                });
            });
        }
        sumRange(  1,  20, "show1");
        sumRange( 21,  40, "show2");
        sumRange( 41,  60, "show3");
        sumRange( 61,  80, "show4");
        sumRange( 81, 100, "show5");
        sumRange(101, 120, "show6");
        sumRange(121, 140, "show7");
        sumRange(141, 160, "show8");
    </script>

    <script>
    var selectElement   = document.getElementById('vatType');
    var vatnoElement    = document.getElementById('vatno');
    var vatNoElement1   = document.getElementById('vatNo1');
    var vatTypeElement1 = document.getElementById('vatType1');

    var vatA = <?php echo (int)$vatA; ?>;
    var vatB = <?php echo (int)$vatB; ?>;
    var vatC = <?php echo (int)$vatC; ?>;

    selectElement?.addEventListener('change', function(event) {
        var selectedValue = event.target.value;
        var data = '';
        if (selectedValue == 'A') { data = 'A-' + vatA; vatNoElement1.value = vatA; vatTypeElement1.value = 'A'; }
        if (selectedValue == 'B') { data = 'B-' + vatB; vatNoElement1.value = vatB; vatTypeElement1.value = 'B'; }
        if (selectedValue == 'C') { data = 'C-' + vatC; vatNoElement1.value = vatC; vatTypeElement1.value = 'C'; }
        vatnoElement.textContent = data;
    });

    var radioButtons = document.querySelectorAll('input[type="radio"]');
    radioButtons.forEach(function(radioButton) {
        radioButton.addEventListener('change', function(event) {
            var selectedValue = event.target.value;
            var data = vatnoElement.textContent || '';
            if (selectedValue == 'deposit') { data = 'C-' + vatC; vatNoElement1.value = vatC; vatTypeElement1.value = 'C'; }
            vatnoElement.textContent = data;
        });
    });
    </script>

    <script>
    (function(){
      const input = document.getElementById('vatNo1');
      const label = document.getElementById('vatno');
      const sel   = document.getElementById('vatType');
      const hid   = document.getElementById('vatType1');

      const digits   = s => String(s ?? '').replace(/\D/g, '');
      const getType  = () => (sel?.value) || (hid?.value) || 'A';

      function syncHeadingFromInput(){
        if (!input || !label) return;
        input.value = digits(input.value);
        label.textContent = getType() + '-' + (input.value || '');
      }
      function syncInputFromType(){
        if (!input || !label) return;
        const vA = <?php echo (int)($vatA ?? 0); ?>;
        const vB = <?php echo (int)($vatB ?? 0); ?>;
        const vC = <?php echo (int)($vatC ?? 0); ?>;
        let n = '';
        const t = getType();
        if (t === 'A') n = vA;
        if (t === 'B') n = vB;
        if (t === 'C') n = vC;
        input.value = digits(n);
        label.textContent = t + '-' + input.value;
      }
      ['input','change','blur'].forEach(ev => input?.addEventListener(ev, syncHeadingFromInput));
      sel?.addEventListener('change', syncInputFromType);
      document.getElementById('receiveType1')?.addEventListener('click', () => setTimeout(syncInputFromType, 0));
      document.getElementById('receiveType2')?.addEventListener('click', () => setTimeout(syncInputFromType, 0));
      (function initFromHeading(){
        if (!input || !label) return;
        const m = (label.textContent || '').match(/^[A-Z]\s*-\s*(\d+)/i);
        if (m) { input.value = digits(m[1]); } else { syncInputFromType(); }
        label.textContent = getType() + '-' + (input.value || '');
      })();
    })();
    </script>

    {{-- ===== ซิงก์ตัวเลือก "ตัดจากสต็อก" -> hidden fields + ช่องค้นหา ===== --}}
    <script>
    (function(){
      const picker = document.getElementById('stockPicker');
      const filter = document.getElementById('stockFilter');

      const fC = document.getElementById('stockCustomer');
      const fS = document.getElementById('stockFabricStruct');
      const fP = document.getElementById('stockFabricPattern');
      const fW = document.getElementById('stockFabricW');

      function syncHiddenFromSelected(){
        if (!picker) return;
        const opt = picker.options[picker.selectedIndex];
        if (!opt || !opt.dataset) return;
        fC.value = opt.dataset.customer || '';
        fS.value = opt.dataset.struct   || '';
        fP.value = opt.dataset.pattern  || '';
        fW.value = opt.dataset.w        || '';
      }

      function applyFilter(){
        const q = (filter.value || '').toLowerCase().trim();
        Array.from(picker.options).forEach((opt, idx) => {
          if (idx === 0) return; // เว้น placeholder
          const txt = (opt.text || '').toLowerCase();
          opt.hidden = q && !txt.includes(q);
        });
      }

      picker?.addEventListener('change', syncHiddenFromSelected);
      filter?.addEventListener('input', applyFilter);

      // sync ครั้งแรกตาม option ที่เลือกไว้ (ถ้ามี)
      syncHiddenFromSelected();
    })();
    </script>

    <script>
(function(){
  const picker = document.getElementById('stockPicker');

  // hidden fields (ต้องมีแค่ชุดเดียวบนหน้า)
  const fC = document.getElementById('stockCustomer');
  const fS = document.getElementById('stockFabricStruct');
  const fP = document.getElementById('stockFabricPattern');
  const fW = document.getElementById('stockFabricW');

  const currentEl = document.getElementById('stockCurrentDisplay');

  function renderCurrent(){
    if (!currentEl) return;
    const c = fC?.value || '';
    const s = fS?.value || '';
    const p = fP?.value || '';
    const w = fW?.value || '';
    currentEl.innerHTML = (c && s)
      ? `สต็อกที่เลือกปัจจุบัน: <b>[${c}]</b> ${s} | ${p} | ${w}''`
      : `สต็อกที่เลือกปัจจุบัน: <i>ยังไม่ได้เลือก</i>`;
  }

  function syncFromSelected(){
    const opt = picker?.options[picker.selectedIndex];
    if (!opt) return;
    fC.value = opt.dataset.customer || '';
    fS.value = opt.dataset.struct   || '';
    fP.value = opt.dataset.pattern  || '';
    fW.value = opt.dataset.w        || '';
    renderCurrent();
  }

  picker?.addEventListener('change', syncFromSelected);
  syncFromSelected(); // ให้ตรงกับ option เริ่มต้นตอนโหลดหน้า
})();
</script>

@endsection
