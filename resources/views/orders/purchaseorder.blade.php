<?php
//set value to Surcharge
$surcharge = $fabricStructureData['yarnWRatio4'];
$typetag = $fabricStructureData['yarnWRatio3'];
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>ASIA TEXTILE CO., LTD.</title>
    <meta name="viewport"
        content="width=device-width,user-scalable=yes,initial-scale=1, maximum-scale=1, minimum-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo asset('assets/css/adminlte.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('assets/css/stylesheet.css'); ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/bootstrap-colorpicker.min.css'); ?>">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/tempusdominus-bootstrap-4.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/fonts/fontawesome-free/css/all.min.css'); ?>">
    <link rel="icon" href="<?php echo asset('assets/images/favicon.png'); ?>" type="image" sizes="16x16">

</head>
<style>
    /* #bath {
        text-align: right;
        color: blue;
    } */
</style>

<body class="hold-transition sidebar-mini">

    <div class="content">
        <div class="inner_content">
            <div class="row">
                <div class="col-md-2">
                    @if ($showpurchaseorder->vat != 'SOB')
                        <figure><img src="<?php echo asset('assets/images/logo-blue.png'); ?>" width="100"></figure>
                    @else
                        <h1>AST</h1>
                    @endif
                </div>
                <div class="col-md-10">
                    <div class="clr">
                        <div class="company-name">
                            @if ($showpurchaseorder->vat != 'SOB')
                                <h1>บริษัท เอเซียเท็กซ์ไทล์ จำกัด
                                </h1>
                                <p>ASIA TEXTILE CO., LTD.</p>
                            @endif
                        </div>
                    </div>
                    <div class="address-company">
                        @if ($showpurchaseorder->vat != 'SOB')
                            <p>789 หมู่ที่ 2 ซอยบางเมฆขาว ถนนสุขุมวิท ตำบลท้ายบ้าน อำเภอเมืองสมุทปราการ
                                จังหวัดสมุทรปราการ 10280</p>
                            <p>เบอร์โทรศัพท์ : 02-3892281-3 แฟกซ์ 02-3892280 Email : s_asiatextile@gmail.com</p>
                            <p>เลขประจำตัวผู้เสียภาษี : 0115531002270</p>
                        @endif
                    </div>

                </div>
            </div>
            <div class="address-company" style="text-align: center;margin-top:2.5em;">
                <h2>ใบสั่งขาย</h2>
            </div>
            <div class="purchase_order_info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>ชื่อลูกค้า </strong>
                            </div>
                            <div class="col-md-6">
                                {{ $showpurchaseorder->customerName }}
                            </div>
                        </div>

                        <?php
                        //Get Customer data
                        $customerData = App\Http\Controllers\OrderController::customerData($showpurchaseorder->customerName);
                        ?>

                        <div class="row">
                            <div class="col-md-6">
                                <strong>ที่อยู่</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $customerData[0]->address }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>เลขประจำตัวผู้เสียภาษี </strong>
                            </div>
                            <div class="col-md-6">
                                {{ $customerData[0]->tax }}
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <strong>ผู้ประสานงาน</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $showpurchaseorder->coname }}
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>เลขที่ใบสั่งขาย</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $showpurchaseorder->purchaseOrder }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong> วันที่ </strong>
                            </div>
                            <div class="col-md-6">
                                <?php echo date('d/m/Y', strtotime($showpurchaseorder->createDate)); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong> เงื่อนไขการชำระเงิน</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $showpurchaseorder->payment }}
                            </div>
                        </div>
                    </div>
                </div>
                <!--row-->
            </div>
            <div class="List_table">
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-a" style="text-align: center">
                            <thead>
                                <tr>
                                    <th>ลำดับ </th>
                                    <th>รายการสินค้า</th>
                                    @if ($typetag == 'm')
                                        <th>จำนวนเมตร</th>
                                    @else
                                        <th>จำนวนหลา</th>
                                    @endif

                                    <th>หน่วยละ</th>
                                    <th>จำนวนเงิน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        {{-- <ins>{{ $showpurchaseorder->s1 }}</ins><br> --}}
                                        {{-- <ins>{{ str_replace('*', 'x', $showpurchaseorder->s1) }}</ins><br>
                                        {{ str_replace('*', 'x', $showpurchaseorder->s2) }} --}}
                                        @if ($showpurchaseorder->yarnHRatio1 != 'no data' || $showpurchaseorder->yarnWRatio1 != 'no data')
                                            <div class="row">
                                                <div class="col-5" style="display: flex; justify-content: center;">
                                                    {{-- <div style="position: absolute;right: 0;"> --}}
                                                    @if ($showpurchaseorder->yarnHRatio1 != 'no data')
                                                        {{ $showpurchaseorder->yarnHRatio1 }}
                                                    @endif
                                                    {{-- </div> --}}
                                                </div>
                                                <div class="col-1"></div>
                                                <div class="col-5" style="display: flex; justify-content: center;">

                                                    @if ($showpurchaseorder->yarnWRatio1 != 'no data')
                                                        {{ $showpurchaseorder->yarnWRatio1 }}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        {{-- {{ $fabricStructureData->yarnHRatio1[0] }} --}}
                                        <div class="row" style="border-bottom: 1px solid black">
                                            <div class="col-5" style="display: flex; justify-content: center;">
                                                {{-- <div style="position: absolute;right: 0;"> --}}
                                                <?php
                                                $parts = explode('*', $showpurchaseorder->s1);
                                                if (count($parts) > 0) {
                                                    $textBeforeAsterisk = trim($parts[0]);
                                                    // echo $textBeforeAsterisk;
                                                    echo str_replace('undefined', '', $textBeforeAsterisk);
                                                    // echo trim($textBeforeAsterisk, 'undefined');
                                                }
                                                ?>
                                                {{-- </div> --}}
                                            </div>
                                            <div class="col-1">x</div>
                                            <div class="col-5" style="display: flex; justify-content: center;">

                                                <?php
                                                $parts = explode('*', $showpurchaseorder->s1);
                                                if (count($parts) > 1) {
                                                    $textAfterAsterisk = trim($parts[1]);
                                                    // echo $textAfterAsterisk;
                                                    echo str_replace('undefined', '', $textAfterAsterisk);
                                                    // echo trim($textAfterAsterisk, 'undefined');
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-5" style="display: flex; justify-content: center;">
                                                {{-- <div style="position: absolute;right: 0;"> --}}
                                                <?php
                                                $parts = explode('*', $showpurchaseorder->s2);
                                                
                                                if (count($parts) > 0) {
                                                    $textBeforeAsterisk = trim($parts[0]);
                                                    echo $textBeforeAsterisk;
                                                }
                                                ?>
                                                {{-- </div> --}}
                                            </div>
                                            <div class="col-1">x</div>
                                            <div class="col-5" style="display: flex; justify-content: center;">

                                                <?php
                                                $parts = explode('*', $showpurchaseorder->s2);
                                                if (count($parts) > 1) {
                                                    $textAfterAsterisk = trim($parts[1]);
                                                    echo $textAfterAsterisk;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <br><br>

                                        @if ($showpurchaseorder->yarnHRatio1 != 'no data')
                                            {{ $showpurchaseorder->yarnHRatio1 }}
                                        @endif

                                        {{-- <?php $fabricPattern = $showpurchaseorder->fabricPattern; ?> --}}
                                        <?php echo preg_replace('/\([^)]+\)/', '', $showpurchaseorder->fabricPattern); ?>
                                        {{-- <?php echo preg_replace('/\([^)]+\)/', '', $showpurchaseorder->fabricPattern); ?> --}}
                                        {{-- {{ $showpurchaseorder->fabricPattern }} --}}

                                        {{ $showpurchaseorder->fabric_w }}''<br><br>
                                        <b>รหัสผ้า </b> {{ $showpurchaseorder->fabricId }}<br>
                                    </td>

                                    @if ($typetag == 'm')
                                        <td>
                                            {{-- {{ $showpurchaseorder->orderSumYard }} --}}
                                            <p id="orderSumYardM"></p>
                                            <script>
                                                const orderSumYardM = {{ $showpurchaseorder->orderSumM }};
                                                var formattedNumber = orderSumYardM.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                                document.getElementById("orderSumYardM").textContent = formattedNumber;
                                            </script>
                                        </td>
                                    @else
                                        <td>
                                            {{-- {{ $showpurchaseorder->orderSumYard }} --}}
                                            {{-- <p id="orderSumYard">{{ $showpurchaseorder->orderSumYard }}</p> --}}
                                            <p id="orderSumYard"></p>
                                            <script>
                                                const orderSumYard = {{ $showpurchaseorder->orderSumYard }};
                                                var formattedNumber = orderSumYard.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                                document.getElementById("orderSumYard").textContent = formattedNumber;
                                            </script>
                                            {{-- <script>
                                                const orderSumYard = {{ $showpurchaseorder->orderSumYard }};
                                                var formattedNumber = orderSumYard.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                                document.getElementById("orderSumYard").textContent = formattedNumber;
                                            </script> --}}
                                        </td>
                                    @endif

                                    @if ($typetag == 'm')
                                        <td>
                                            {{-- {{ $showpurchaseorder->priceM }} --}}
                                            <p id="priceM"> </p>
                                            <script>
                                                const priceM = {{ $showpurchaseorder->priceM }};
                                                var formattedNumberM = priceM.toFixed(3).toLocaleString();
                                                document.getElementById("priceM").textContent = formattedNumberM;
                                            </script>
                                        </td>
                                    @else
                                        <td>
                                            {{-- {{ $showpurchaseorder->priceYard }} --}}
                                            <p id="priceYard"> </p>
                                            <script>
                                                const priceYard = {{ $showpurchaseorder->priceYard }};
                                                var formattedNumber = priceYard.toFixed(3).toLocaleString();
                                                document.getElementById("priceYard").textContent = formattedNumber;
                                            </script>
                                        </td>
                                    @endif

                                    <?php
                                    // $price = $showpurchaseorder->orderSumYard * $showpurchaseorder->priceYard;
                                    $orderSumYard = floatval(str_replace(',', '', $showpurchaseorder->orderSumYard));
                                    $priceYard = floatval(str_replace(',', '', $showpurchaseorder->priceYard));
                                    
                                    $orderSumM = floatval(str_replace(',', '', $showpurchaseorder->orderSumM));
                                    $priceM = floatval(str_replace(',', '', $showpurchaseorder->priceM));
                                    if ($typetag == 'm') {
                                        $price = $orderSumM * $priceM;
                                    } else {
                                        $price = $orderSumYard * $priceYard;
                                    }
                                    // orderSumM
                                    // priceM
                                    ?>
                                    <td>
                                        {{-- {{ $price }} --}}
                                        {{-- <p id="price"></p>
                                        <script>
                                            const price = {{ $price }};
                                            document.getElementById("price").textContent = price.toLocaleString();
                                        </script> --}}
                                        <p id="price"></p>
                                        <script>
                                            const price = parseFloat({{ $price }});
                                            var formattedNumber = price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            document.getElementById("price").textContent = formattedNumber;
                                        </script>
                                    </td>

                                </tr>
                                @if (isset($surcharge))
                                    <tr>
                                        <td>2</td>
                                        <td>Surcharge</td>
                                        <td></td>
                                        <td></td>
                                        <td id="sur"><?php echo $surcharge; ?></td>
                                        <script>
                                            const sur = {{ $surcharge }};
                                            var formattedNumber = sur.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            document.getElementById("sur").textContent = formattedNumber;
                                        </script>
                                    </tr>
                                    <?php $price = $price + $surcharge; ?>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
                <!--row-->
            </div>
            <div class="purchase_order_info">
                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>หมายเหตุ</strong>
                            </div>
                            <div class="col-md-10">
                                @if ($showpurchaseorder->comment != 'no data')
                                    {{ $showpurchaseorder->comment }}
                                @endif
                                @if ($showpurchaseorder->vat == 'SOX')
                                    <strong> ราคานี้รวม VAT แล้ว</strong><br>
                                @endif
                                @if ($showpurchaseorder->po != 'no data')
                                    {{ $showpurchaseorder->po }}
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-6">
                                รวมเป็นเงิน
                            </div>
                            <div class="col-md-4">
                                <strong id="price2" style='float:right; margin-right:0.5em;'></strong>
                                <script>
                                    const price2 = parseFloat({{ $price }});
                                    var formattedNumber = price2.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    document.getElementById("price2").textContent = formattedNumber;
                                </script>
                            </div>
                            <div class="col-md-2">
                                <strong id="bath"> บาท</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                หักส่วนลด {{ $showpurchaseorder->discountP }} %
                            </div>
                            <div class="col-md-4">
                                {{-- <strong>{{ $price * ($showpurchaseorder->discountP / 100) }} </strong> --}}
                                <strong id="discountP" style='float:right; margin-right:0.5em;'></strong>
                                <script>
                                    // const discountP = {{ $price * ($showpurchaseorder->discountP / 100) }};
                                    // document.getElementById("discountP").textContent = discountP.toLocaleString();
                                    const discountP = parseFloat({{ $price * ($showpurchaseorder->discountP / 100) }});
                                    var formattedNumber = discountP.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    document.getElementById("discountP").textContent = formattedNumber;
                                </script>
                            </div>
                            <div class="col-md-2">
                                <strong id="bath"> บาท</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                จำนวนเงินหลังหักส่วนลด
                            </div>
                            <div class="col-md-4">
                                {{-- <strong>{{ $price - $price * ($showpurchaseorder->discountP / 100) }} บาท</strong> --}}
                                <strong id="discountP2" style='float:right; margin-right:0.5em;'></strong>
                                <script>
                                    // const discountP2 = {{ $price * ($showpurchaseorder->discountP / 100) }};
                                    // document.getElementById("discountP2").textContent = discountP2.toLocaleString();
                                    const discountP2 = parseFloat({{ $price - $price * ($showpurchaseorder->discountP / 100) }});
                                    var formattedNumber = discountP2.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    document.getElementById("discountP2").textContent = formattedNumber;
                                </script>
                            </div>
                            <div class="col-md-2">
                                <strong id="bath"> บาท</strong>
                            </div>
                        </div>

                        <?php
                        //price - showpurchaseorder
                        // $price = $price - $price * ($showpurchaseorder->discountP / 100);
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                จำนวนภาษีมูลค่าเพิ่ม
                            </div>
                            <div class="col-md-4">
                                @if ($showpurchaseorder->vat == 'SO')
                                    <strong>
                                        {{-- {{ ($price - $price * ($showpurchaseorder->discountP / 100)) * 0.07 }} --}}
                                        <strong id="discountP3" style='float:right; margin-right:0.5em;'></strong>
                                        <script>
                                            // const discountP3 = {{ ($price - $price * ($showpurchaseorder->discountP / 100)) * 0.07 }};
                                            // document.getElementById("discountP3").textContent = discountP3.toLocaleString();
                                            const discountP3 = parseFloat({{ ($price - $price * ($showpurchaseorder->discountP / 100)) * 0.07 }});
                                            var formattedNumber = discountP3.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            document.getElementById("discountP3").textContent = formattedNumber;
                                        </script>
                                        {{-- บาท --}}
                                    </strong>

                                    <?php
                                    //price + vat
                                    $price = $price + ($price - $price * ($showpurchaseorder->discountP / 100)) * 0.07;
                                    ?>
                                @elseif($showpurchaseorder->vat == 'SOX')
                                    <strong style='float:right; margin-right:0.5em;'>0.00</strong>
                                @else
                                    <strong style='float:right; margin-right:0.5em;'>0.00</strong>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <strong id="bath"> บาท</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                จำนวนเงินรวมทั้งสิ้น
                            </div>
                            <div class="col-md-4">
                                @if ($showpurchaseorder->vat == 'SO')
                                    <strong>
                                        {{-- {{ $price + ($price - $price * ($showpurchaseorder->discountP / 100)) * 0.07 }} --}}
                                        <strong id="discountP4" style='float:right; margin-right:0.5em;'></strong>
                                        <script>
                                            // const discountP4 = {{ $price + ($price - $price * ($showpurchaseorder->discountP / 100)) * 0.07 }};
                                            // document.getElementById("discountP4").textContent = discountP4.toLocaleString();
                                            //const discountP4 = parseFloat({{ $price + ($price - $price * ($showpurchaseorder->discountP / 100)) * 0.07 }});
                                            const discountP4 = parseFloat({{ $price }});

                                            var formattedNumber = discountP4.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            document.getElementById("discountP4").textContent = formattedNumber;
                                        </script>
                                        {{-- บาท
                                    </strong> --}}
                                    @elseif($showpurchaseorder->vat == 'SOX')
                                        {{-- <strong> {{ $price - $price * ($showpurchaseorder->discountP / 100) }} --}}
                                        <strong id="discountP5" style='float:right; margin-right:0.5em;'></strong>
                                        <script>
                                            // const discountP5 = {{ $price - $price * ($showpurchaseorder->discountP / 100) }};
                                            // document.getElementById("discountP5").textContent = discountP5.toLocaleString();
                                            // const discountP5 = parseFloat({{ $price - $price * ($showpurchaseorder->discountP / 100) }});
                                            const discountP5 = parseFloat({{ $price }});
                                            var formattedNumber = discountP5.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            document.getElementById("discountP5").textContent = formattedNumber;
                                        </script>
                                        {{-- บาท
                                    </strong> --}}
                                    @else
                                        {{-- <strong> {{ $price - $price * ($showpurchaseorder->discountP / 100) }}
                                        บาท</strong> --}}
                                        <strong id="discountP6" style='float:right; margin-right:0.5em;'></strong>
                                        <script>
                                            // const discountP6 = {{ $price - $price * ($showpurchaseorder->discountP / 100) }};
                                            // document.getElementById("discountP6").textContent = discountP6.toLocaleString();
                                            const discountP6 = parseFloat({{ $price }});
                                            var formattedNumber = discountP6.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            document.getElementById("discountP6").textContent = formattedNumber;
                                        </script>
                                @endif

                            </div>
                            <div class="col-md-2">
                                <strong id="bath"> บาท</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <!--row-->
            </div>
            <!--purchase_order_info-->
            <div class="purchase_order_signature">
                <div class="row">
                    <div class="col-md-6 mt-5">
                        <div class="box_signature">
                            <div class="inner_signature">
                                {{-- {{ $showpurchaseorder->coname }} --}}
                            </div>
                            <p><strong>ผู้สั่งสินค้า</strong></p>
                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <div class="box_signature">
                            <div class="inner_signature">
                                {{-- {{ $showpurchaseorder->emp }} --}}
                            </div>
                            <p><strong>ผู้ขายสินค้า</strong></p>
                        </div>
                    </div>
                </div>
                <!--row-->
            </div>
            <!--purchase_order_signature-->
            <div class="purchase_order_signature">
                <div class="row">
                    <div class="col-md-12" style="margin-left:8rem;">
                        <form action="{{ route('order.store') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="customerName"
                                value="{{ $showpurchaseorder->customerName }}">
                            <input type="hidden" name="address" value="{{ $customerData[0]->address }}">
                            <input type="hidden" name="tax" value="{{ $customerData[0]->tax }}">
                            <input type="hidden" name="coname" value="{{ $showpurchaseorder->coname }}">
                            <input type="hidden" name="purchaseOrder"
                                value="{{ $showpurchaseorder->purchaseOrder }}">
                            <input type="hidden" name="createDate" value="{{ $showpurchaseorder->createDate }}">
                            <input type="hidden" name="payment" value="{{ $showpurchaseorder->payment }}">
                            <input type="hidden" name="s1" value="{{ $showpurchaseorder->s1 }}">
                            <input type="hidden" name="s2" value="{{ $showpurchaseorder->s2 }}">
                            <input type="hidden" name="comment" value="{{ $showpurchaseorder->comment }}">
                            <input type="hidden" name="fabricPattern"
                                value="{{ $showpurchaseorder->fabricPattern }}">
                            <input type="hidden" name="fabric_w" value="{{ $showpurchaseorder->fabric_w }}">

                            <input type="hidden" name="fabricId" value="{{ $showpurchaseorder->fabricId }}">
                            <input type="hidden" name="orderSumYard"
                                value="{{ $showpurchaseorder->orderSumYard }}">
                            <input type="hidden" name="orderSumM" value="{{ $showpurchaseorder->orderSumM }}">
                            <input type="hidden" name="priceYard" value="{{ $showpurchaseorder->priceYard }}">
                            <input type="hidden" name="priceM" value="{{ $showpurchaseorder->priceM }}">
                            {{-- <input type="hidden" name="price " value="{{ $price }}"> --}}
                            <input type="hidden" name="vat" value="{{ $showpurchaseorder->vat }}">
                            <input type="hidden" name="po" value="{{ $showpurchaseorder->po }}">
                            <input type="hidden" name="discountP" value="{{ $showpurchaseorder->discountP }}">
                            <input type="hidden" name="emp" value="{{ $showpurchaseorder->emp }}">

                            <input type="hidden" name="yarnHRatio1" value="{{ $showpurchaseorder->yarnHRatio1 }}">
                            <input type="hidden" name="yarnWRatio1" value="{{ $showpurchaseorder->yarnWRatio1 }}">

                            {{-- <input type="text" name="typrtag" value="123{{ $showpurchaseorder->typrtag }}"> --}}
                            <input type="hidden" name="surcharge" value="{{ $surcharge }}">

                            <input type="hidden" name="typrtag" value="{{ $typetag }}">
                            {{-- <input type="text" name="surcharge" value="{{ $fabricStructureData->yarnWRatio3 }}"> --}}

                            {{-- <input type="hidden" name="first_operand" value="ได้ไหม"> --}}
                            {{-- <button type="button" class="btn b_order" name="submit" value="index"><a
                                    href="{{ route('order.index') }}">กลับหน้าหลัก</a></button> --}}
                            <button type="button" class="btn b_order" name="submit" value="new"><a
                                    href="{{ route('order.create') }}">สั่งใบใหม่</a></button>
                            {{-- <form method="post" action="{{ route('order.store') }}">
                                @csrf --}}
                            <input type="hidden" id="" name="id"
                                value="{{ $showpurchaseorder->id }}">
                            <button name="submit" class="btn btn-secondary"
                                value="productionderdetail">ใบโครงสร้าง</button>
                            {{-- <a href="">สั่งผลิต</a> --}}
                            {{-- </form> --}}
                            <button type="submit" class="btn b_order" name="submit" value="genPDF">Generate
                                PDF</button>
                        </form>
                    </div>
                </div>
                <!--row-->
                <div class="row">
                    <div class="col-md-12" style="margin-left:17rem;margin-top:1rem;">
                        <button onclick="goBack()" class="btn b_order">กลับ</button>
                        <script>
                            function goBack() {
                                window.history.back();
                            }
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-left:17rem;margin-top:1rem;">
                        <button type="button" class="btn b_order" name="submit" value="new"><a
                                href="{{ route('order.index') }}">กลับหน้ารายการ</a></button>
                    </div>
                </div>
            </div>
            <!--purchase_order_signature-->
        </div>
        <!--inner_content-->
    </div>
    <!--content-->

</body>

</html>
