<?php
//set value to Surcharge
$surcharge = $fabricStructureData['yarnWRatio4'];
// $sovat = $fabricpaymentEdit['vat'];
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
            {{-- <div class="row">
                <div class="col-md-2">
                    @if ($showproduction->vat != 'SOB')
                        <figure><img src="<?php echo asset('assets/images/logo-blue.png'); ?>" width="100"></figure>
                    @else
                        <h1>AST</h1>
                    @endif
                </div>
                <div class="col-md-10">
                    <div class="clr">
                        <div class="company-name">
                            @if ($showproduction->vat != 'SOB')
                                <h1>บริษัท เอเซียเท็กซ์ไทล์ จำกัด</h1>
                                <p>ASIA TEXTILE CO., LTD.</p>
                            @endif
                        </div>
                    </div>
                    <div class="address-company">
                        @if ($showproduction->vat != 'SOB')
                            <p>789 หมู่ที่ 2 ซอยบางเมฆขาว ถนนสุขุมวิท ตำบลท้ายบ้าน อำเภอเมืองสมุทปราการ
                                จังหวัดสมุทรปราการ 10280</p>
                            <p>เบอร์โทรศัพท์ : 0-2389-2281-3 Email : asiatestile@gmail.com</p>
                            <p>เลขประจำตัวผู้เสียภาษี : 0115531002270</p>
                        @endif
                    </div>

                </div>
            </div> --}}
            <div class="address-company" style="text-align: center;margin-top:2.5em;">
                <h2>ใบโครงสร้าง</h2>
            </div>
            <div class="purchase_order_info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>ชื่อลูกค้า </strong>
                            </div>
                            <div class="col-md-6">
                                {{ $showproduction->customerName }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>No. </strong>
                            </div>
                            <div class="col-md-6">
                                {{ $showproduction->no }}
                            </div>
                        </div>

                        <?php
                        //Get Customer data
                        $customerData = App\Http\Controllers\OrderController::customerData($showproduction->customerName);
                        ?>

                        {{-- <div class="row">
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
                        </div> --}}

                        {{-- <div class="row">
                            <div class="col-md-6">
                                <strong>ผู้ประสานงาน</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $showproduction->coname }}
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>เลขที่ใบโครงสร้าง</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $showproduction->purchaseOrder }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong> วันที่ </strong>
                            </div>
                            <div class="col-md-6">
                                <?php echo date('d/m/Y', strtotime($showproduction->createDate)); ?>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <strong> เงื่อนไขการชำระเงิน</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $showproduction->payment }}
                            </div>
                        </div> --}}
                    </div>
                </div>
                <!--row-->
            </div>
            <div class="purchase_order_info">
                <div class="detail" style="font-size: 1.05rem">
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-5 table-responsive">
                            {{-- <b>โครงสร้างผ้า : </b> <ins>{{ $showproduction->s1 }}</ins><br> --}}
                            {{-- <b>โครงสร้างผ้า : </b> <ins><?php echo str_replace('*', 'x', str_replace('undefined', '', $showproduction->s1)); ?></ins>
                            <br> --}}

                            {{-- <b style="margin-right: 6rem"></b>{{ $showproduction->s2 }}<br><br> --}}
                            {{-- <b style="margin-right: 6rem"></b><?php echo str_replace('*', 'x', str_replace('undefined', '', $showproduction->s2)); ?><br><br> --}}
                            <b>โครงสร้างผ้า : </b>
                            @if ($showproduction->yarnHRatio1 != 'no data' || $showproduction->yarnWRatio1 != 'no data')
                                <div class="row">
                                    <div class="col-5" style="display: flex; justify-content: center;">
                                        {{-- <div style="position: absolute;right: 0;"> --}}
                                        @if ($showproduction->yarnHRatio1 != 'no data')
                                            {{ $showproduction->yarnHRatio1 }}
                                        @endif
                                        {{-- </div> --}}
                                    </div>
                                    <div class="col-1"></div>
                                    <div class="col-5" style="display: flex; justify-content: center;">

                                        @if ($showproduction->yarnWRatio1 != 'no data')
                                            {{ $showproduction->yarnWRatio1 }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="row" style="border-bottom: 1px solid black">
                                <div class="col-5" style="display: flex; justify-content: center;">
                                    {{-- <div style="position: absolute;right: 0;"> --}}
                                    <?php
                                    $parts = explode('*', $showproduction->s1);
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
                                    $parts = explode('*', $showproduction->s1);
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
                                    $parts = explode('*', $showproduction->s2);
                                    
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
                                    $parts = explode('*', $showproduction->s2);
                                    if (count($parts) > 1) {
                                        $textAfterAsterisk = trim($parts[1]);
                                        echo $textAfterAsterisk;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 table-responsive">
                             {{ $showproduction->fabric_w }} <br><br>

                        </div>
                        <div class="col-2 table-responsive">
                            <b> ลายผ้า </b>
                            {{-- <?php echo preg_replace('/\([^)]+\)/', '', $showproduction->fabricPattern); ?> --}}
                            {{ $showproduction->fabricPattern }}
                            <br><br>
                            <b>รหัสผ้า </b> {{ $showproduction->fabricId }}<br><br>
                        </div>
                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-5 table-responsive">
                            <b>จำนวนด้ายยืน : </b> {{ $showproduction->yarn_h_count }}<br><br>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-10 table-responsive">
                            <b>ชนิดด้ายยืน : </b> {{ $showproduction->yarnHType1 }} <?php $subNameH1 = str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameH1); ?>
                            {{ $subNameH1 }}
                            <?php
                            if ($showproduction->subNameH2 != null) {
                                echo $showproduction->yarnHType2 . ',' . str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameH2);
                            }
                            ?>
                            <br><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-10 table-responsive">
                            <b>ชนิดด้ายพุ่ง : </b> {{ $showproduction->yarnWType1 }} <?php $subNameW1 = str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameW1); ?>
                            {{ $subNameW1 }}
                            <?php
                            if ($showproduction->subNameW2 != null) {
                                echo '+ ' . $showproduction->yarnWType2 . ' ' . str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameW2);
                            }
                            ?>
                            <br><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-4 table-responsive">
                            <b>จำนวนด้ายพุ่ง : </b> {{ $showproduction->yarnWCount1 }}<br><br>

                        </div>
                        <div class="col-4 table-responsive">
                            <b>หน้าผ้ากว้าง : </b> {{ $showproduction->fabric_w }} นิ้ว<br><br>

                        </div>
                        {{-- <div class="col-2 table-responsive">
                            <b>สืบ : </b> <br><br>

                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-4 table-responsive">
                            <b>ฟันหวีเบอร์ : </b> {{ $showproduction->phewNumber }}<br><br>

                        </div>
                        <div class="col-4 table-responsive">
                            <b>หน้าหวีกว้าง : </b> {{ $showproduction->phewW }} นิ้ว<br><br>

                        </div>
                        {{-- <div class="col-2 table-responsive">
                            <b>ไซร์ : </b> <br><br>

                        </div> --}}
                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-4 table-responsive">
                            {{-- <b>พ่นสีลงผ้า : </b> {{ $showproduction->vat }}<br><br> --}}
                            {{-- <b>พ่นสีลงผ้า : </b> <?php print $sovat; ?><br><br> --}}
                            <b>พ่นสีลงผ้า : </b> {{ $showproduction->vat }}<br><br>


                        </div>

                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-5 table-responsive">
                            @if ($showproduction->typrtag == 'm')
                                {{-- {{ $orderdata->fabricSPY * ($orderdata->orderSumYard / 100) + $orderdata->orderSumYard }}
                                <b>หลา</b>
                                {{ $orderdata->fabricSPY * ($orderdata->orderSumM / 100) + $orderdata->orderSumM }}
                                <b>เมตร</b> --}}
                                <b>ออร์เดอร์สืบ :
                                </b>{{ $showproduction->fabricSPY * ($showproduction->orderSumM / 100) + $showproduction->orderSumM }}
                                เมตร<br><br>
                            @else
                                <b>ออร์เดอร์สืบ :
                                </b>{{ $showproduction->fabricSPY * ($showproduction->orderSumYard / 100) + $showproduction->orderSumYard }}
                                หลา<br><br>
                            @endif
                        </div>
                        <div class="col-5 table-responsive">
                            <b>กำหนดส่ง : </b> <br> <br>
                        </div>
                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-2 table-responsive"></div>
                        <div class="col-5 table-responsive">
                            <b>ชนิดเครื่องทอ : </b> {{ $showproduction->typemachine }}<br> <br>
                        </div>
                        <div class="col-5 table-responsive">
                            <b>เบอร์เครื่อง : </b> {{ $showproduction->machinenumber }}<br> <br>
                        </div>
                    </div>
                    <!--row-->
                </div>
            </div>
            <div class="row">
                <div class="col-12 table-responsive">

                    <table class="table table-striped" id="dynamicAddRemove">

                        <thead>
                            <tr>
                                <th>กำหนดส่งครั้งที่</th>
                                <th>วันที่ *</th>
                                <th>จำนวน (หลา) *</th>
                                <th> % *</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderdeadline as $temp)
                                <tr>
                                    <td>{{ $temp->round }}</td>
                                    <td>{{ date('d/m/Y', strtotime($temp->dt)) }}</td>
                                    <td>{{ $temp->ordery }}หลา</td>
                                    <td>{{ $temp->orderp }}%</td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="purchase_order_info">
                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>หมายเหตุ</strong>
                            </div>
                            <div class="col-md-10">
                                @if ($showproduction->comment != 'no data')
                                    {{ $showproduction->comment2 }}
                                @endif
                                @if ($showproduction->vat == 'SOX')
                                    <strong> ราคานี้รวม VAT แล้ว</strong><br>
                                @endif
                                @if ($showproduction->po != 'no data')
                                    {{ $showproduction->po }}
                                @endif
                                @if ($showproduction->typrtag == 'm')
                                    <b>ออร์เดอร์ :
                                    </b>{{ $showproduction->orderSumM }}
                                    เมตร<br><br>
                                @else
                                    <b>ออร์เดอร์ :
                                    </b>{{ $showproduction->orderSumYard }}
                                    หลา<br><br>
                                @endif

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
                                {{-- {{ $showproduction->coname }} --}}
                            </div>
                            <p><strong>ผู้สั่งงาน</strong></p>
                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <div class="box_signature">
                            <div class="inner_signature">
                                {{-- {{ $showproduction->emp }} --}}
                            </div>
                            <p><strong>ผู้ตรวจสอบ </strong></p>
                        </div>
                    </div>
                </div>
                <!--row-->
            </div>
            <!--purchase_order_signature-->
            <div class="purchase_order_signature">
                <div class="row">
                    <div class="col-md-1">
                        <form action="{{ route('order.store') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="customerName" value="{{ $showproduction->customerName }}">
                            <input type="hidden" name="address" value="{{ $customerData[0]->address }}">
                            <input type="hidden" name="tax" value="{{ $customerData[0]->tax }}">
                            <input type="hidden" name="coname" value="{{ $showproduction->coname }}">
                            <input type="hidden" name="purchaseOrder" value="{{ $showproduction->purchaseOrder }}">
                            <input type="hidden" name="createDate" value="{{ $showproduction->createDate }}">
                            <input type="hidden" name="payment" value="{{ $showproduction->payment }}">
                            <input type="hidden" name="s1" value="{{ $showproduction->s1 }}">
                            <input type="hidden" name="s2" value="{{ $showproduction->s2 }}">
                            <input type="hidden" name="comment" value="{{ $showproduction->comment }}">
                            <input type="hidden" name="fabricPattern" value="{{ $showproduction->fabricPattern }}">
                            <input type="hidden" name="fabric_w" value="{{ $showproduction->fabric_w }}">
                            <input type="hidden" name="fabricId" value="{{ $showproduction->fabricId }}">
                            <input type="hidden" name="orderSumYard" value="{{ $showproduction->orderSumYard }}">
                            <input type="hidden" name="orderSumM" value="{{ $showproduction->orderSumM }}">
                            <input type="hidden" name="priceYard" value="{{ $showproduction->priceYard }}">
                            <input type="hidden" name="priceM" value="{{ $showproduction->priceM }}">
                            {{-- <input type="hidden" name="price " value="{{ $price }}"> --}}
                            <input type="hidden" name="vat" value="{{ $showproduction->vat }}">
                            <input type="hidden" name="po" value="{{ $showproduction->po }}">
                            <input type="hidden" name="discountP" value="{{ $showproduction->discountP }}">
                            <input type="hidden" name="emp" value="{{ $showproduction->emp }}">
                            <input type="hidden" name="yarnHRatio1" value="{{ $showproduction->yarnHRatio1 }}">
                            <input type="hidden" name="typrtag" value="{{ $showproduction->typrtag }}">
                            <input type="hidden" name="surcharge" value="{{ $surcharge }}">
                            {{-- <input type="hidden" name="first_operand" value="ได้ไหม"> --}}
                            {{-- <button type="button" class="btn b_order" name="submit" value="index"><a
                                    href="{{ route('order.index') }}">กลับหน้าหลัก</a></button> --}}
                        </form>
                    </div>

                    {{-- <button type="submit" class="btn b_order" name="submit" value="genPDF">Generate
                                PDF</button> --}}
                    <div class="col-md-2" style="margin-left:7em;">
                        <form method="POST" action="{{ route('order.store', $showproduction->refId) }}">
                            @csrf
                            {{-- @method('DELETE') --}}
                            {{-- <p>{{ $order->status }}</p> --}}
                            <input type="hidden" name="id" value="{{ $showproduction->refId }}">
                            <button name="submit" class="btn btn-secondary" value="editproduction">แก้ไข</button>

                            {{-- <button type="button" class="btn btn-danger"
                            onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button> --}}
                        </form>
                    </div>
                    <div class="col-md-2" style="margin-left:5em;">
                        <form method="post" action="{{ route('order.store') }}" target="_blank">
                            @csrf
                            <input type="hidden" id="" name="id"
                                value="{{ $showproduction->refId }}">
                            <button id="pdf-btn" name="submit" class="btn btn-secondary"
                                value="productionderdetailPdf">พิมพ์ใบโครงสร้าง</button>

                        </form>
                    </div>





                </div>
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
            </div>
            <!--row-->
        </div>
    </div>
    <!--inner_content-->
    </div>
    <!--content-->

</body>

</html>
