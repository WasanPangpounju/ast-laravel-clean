<?php
//set value to Surcharge
$surcharge = $fabricStructureData['yarnWRatio4'];
$sovat = $fabricStructureData['vat'];
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
    <style>
        @media print {
            @page {
                size: 10in 6.5in;
                margin: 0;
            }

            body {
                /* margin-top: -1rem; */
                margin-top: 1rem;
                margin-left: 1rem;
                font-size: 1.5rem;
            }

            .headfont {
                font-size: 1.2rem;
                /* margin-right: 1.5rem; */
            }

            #bordereddashed {
                border-bottom: 1px dashed rgb(122, 111, 111);
                /* Adjust width, style, and color as needed */
                /* display: none; */

            }


            .content2 {
                margin-top: -41rem;


            }


        }
    </style>
</head>
<style>
    /* #bath {
        text-align: right;
        color: blue;
    } */
</style>

<body class="hold-transition sidebar-mini" onload="printPdf()">

    <div class="content">

        <div class="">
            <div class="detail" style="font-size: 1.5rem">
                <div class="row">


                    <div class="col-md-12" style="margin-top: 0rem;">
                        <strong style="text-align: left;">
                            ชื่อลูกค้า: {{ str_replace('(สำนักงานใหญ่)', '', $showproduction->customerName) }}
                        </strong>
                        <strong style="text-align: center; margin-left: 1rem;">
                            วันที่: {{ date('d/m/Y', strtotime($showproduction->createDate)) }}
                        </strong>
                        <strong style="text-align: right; margin-left: 2rem;">
                            No.: {{ $showproduction->no }}
                        </strong>
                    </div>

                    {{-- <div class="col-md-3"style="margin-top: 0rem;">
                        <strong> วันที่ : <?php echo date('d/m/Y', strtotime($showproduction->createDate)); ?> </strong>
                    </div>
                    <div class="col-md-3"style="margin-top: 0rem;">
                        <strong> No. : {{ $showproduction->no }} </strong>
                    </div> --}}
                    <?php
                    //Get Customer data
                    $customerData = App\Http\Controllers\OrderController::customerData($showproduction->customerName);
                    ?>
                    {{-- <div class="col-md-6">

                      
                    </div> --}}
                </div>
            </div>
        </div>
        {{-- <div class="purchase_order_info"> --}}
        <div class="" style="margin-top: 1.5rem;">

            <div class="detail" style="font-size: 1.5rem;margin-top: -1.5rem;">
                <div class="row" style="margin-top: 0.5rem;">
                    <div class="col-5 ">
                        {{-- <span class="headfont">โครงสร้างผ้า : </span> {{ $showproduction->s1 }}<br>
                        <b style="margin-left: 8rem;margin-right: 2rem">:</span>{{ $showproduction->s2 }}<br><br> --}}
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
                        @if ($showproduction->yarnHRatio1 != 'no data' || $showproduction->yarnWRatio1 != 'no data')
                            <div class="row" style="border-bottom: 1px solid black; margin-top: -1rem;">
                            @else
                                <div class="row" style="border-bottom: 1px solid black;">
                        @endif
                        {{-- <div class="row" style="border-bottom: 1px solid black; margin-top: -1rem;"> --}}
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
                <div class="col-2 ">
                    {{ $showproduction->fabric_w }} <br><br>
                    <br>
                </div>
                <div class="col-1">
                    <span class="headfont"> ลายผ้า :</span>
                </div>
                <div class="col-4">
                    {{-- <?php echo preg_replace('/\([^)]+\)/', '', $showproduction->fabricPattern); ?> --}}
                    {{ $showproduction->fabricPattern }}
                </div>
                {{-- 2 --}}

            </div>
            <!--row-->
            <div class="row" style="margin-top: -2rem;">
                <div class="col-2 ">
                    <span class="headfont">จำนวนด้ายยืน :<br>
                </div>
                <div class="col-4 ">
                    {{ $showproduction->yarn_h_count }}<br>
                </div>
                <div class="col-1 ">
                </div>
                <div class="col-1 ">
                    <span class="headfont">รหัสผ้า :
                </div>
                <div class="col-4 ">
                    {{ $showproduction->fabricId }}<br>
                </div>
            </div>
            {{-- <div class="row" style="margin-top: -2rem;"> --}}
            <div class="row" style="margin-top: 0.5rem;">
                <div class="col-2 ">
                    <span class="headfont">ชนิดด้ายยืน : </span>
                    <br>
                </div>
                <div class="col-10 ">
                    {{ $showproduction->yarnHType1 }} <?php $subNameH1 = str_replace('บริษัท', '', $showproduction->subNameH1); ?>
                    {{ $subNameH1 }}
                    <?php
                    if ($showproduction->subNameH2 != null) {
                        echo $showproduction->yarnHType2 . ',' . str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameH2);
                    }
                    ?>
                    <br>
                </div>
            </div>
            {{-- <div class="row" style="margin-top: -1.5rem;"> --}}
            <div class="row"style="margin-top: 0.5rem;">

                <div class="col-2 ">
                    <span class="headfont">ชนิดด้ายพุ่ง :
                        <br>
                </div>
                <div class="col-10 ">
                    {{ $showproduction->yarnWType1 }}
                    <?php $subNameW1 = str_replace('บริษัท', '', $showproduction->subNameW1); ?>
                    {{ $subNameW1 }}
                    <?php
                    if ($showproduction->subNameW2 != null) {
                        echo '+ ' . $showproduction->yarnWType2 . ' ' . str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameW2);
                    }
                    ?>
                    <br>
                </div>
            </div>
            {{-- <div class="row" style="margin-top: -1.5rem;"> --}}
            <div class="row"style="margin-top: 0.5rem;">

                <div class="col-2 ">
                    <span class="headfont">จำนวนด้ายพุ่ง :

                </div>
                <div class="col-4 ">
                    {{ $showproduction->yarnWCount1 }}<br>

                </div>
                <div class="col-2 ">
                    <span class="headfont">หน้าผ้ากว้าง : </span>

                </div>
                <div class="col-4 ">
                    {{ $showproduction->fabric_w }} นิ้ว<br>
                </div>
            </div>
            {{-- <div class="row" style="margin-top:-1.5rem;"> --}}
            <div class="row"style="margin-top: 0.5rem;">

                <div class="col-2 ">
                    <span class="headfont">ฟันหวีเบอร์ : </span>

                </div>
                <div class="col-4 ">
                    {{ $showproduction->phewNumber }}<br>

                </div>
                <div class="col-2 ">
                    <span class="headfont">หน้าหวีกว้าง : </span>

                </div>
                <div class="col-4 ">
                    {{ $showproduction->phewW }} นิ้ว<br>

                </div>
            </div>
            <!--row-->
            {{-- <div class="row" style="margin-top: -1.5rem;"> --}}
            <div class="row"style="margin-top: 0.5rem;">

                <div class="col-2 ">
                    {{-- <span class="headfont">พ่นสีลงผ้า : </span> {{ $showproduction->vat }}<br><br> --}}
                    <span class="headfont">พ่นสีลงผ้า : </span>

                </div>
                <div class="col-10 ">
                    {{-- <span class="headfont">พ่นสีลงผ้า : </span> {{ $showproduction->vat }}<br><br> --}}
                    {{ $showproduction->vat }}<br>

                </div>

            </div>
            <!--row-->
            {{-- <div class="row" style="margin-top: -1.5rem;"> --}}
            <div class="row"style="margin-top: 0.5rem;">
                <div class="col-2 ">
                    @if ($showproduction->typrtag == 'm')
                        <span class="headfont">ออร์เดอร์สืบ :
                        </span>
                    @else
                        <span class="headfont">ออร์เดอร์สืบ :
                        </span>
                    @endif
                </div>
                <div class="col-4 ">
                    @if ($showproduction->typrtag == 'm')
                        {{-- {{ $orderdata->fabricSPY * ($orderdata->orderSumYard / 100) + $orderdata->orderSumYard }}
                                <span class="headfont">หลา</span>
                                {{ $orderdata->fabricSPY * ($orderdata->orderSumM / 100) + $orderdata->orderSumM }}
                                <span class="headfont">เมตร</span> --}}

                        {{-- {{ $showproduction->fabricSPY * ($showproduction->orderSumM / 100) + $showproduction->orderSumM }} --}}
                        <?php
                        $order = $showproduction->fabricSPY * ($showproduction->orderSumM / 100) + $showproduction->orderSumM;
                        echo number_format($order, 0, '.', ',');
                        ?>

                        เมตร<br><br>
                    @else
                        {{-- {{ $showproduction->fabricSPY * ($showproduction->orderSumYard / 100) + $showproduction->orderSumYard }} --}}
                        <?php
                        $order = $showproduction->fabricSPY * ($showproduction->orderSumYard / 100) + $showproduction->orderSumYard;
                        echo number_format($order, 0, '.', ',');
                        ?>

                        หลา<br><br>
                    @endif
                </div>
                <div class="col-5 ">
                    <span class="headfont">กำหนดส่ง : </span> <br> <br>
                </div>
            </div>
            <!--row-->
            <div class="row" style="margin-top: -2rem;;">
                <div class="col-2 ">
                    <span class="headfont">ชนิดเครื่องทอ : </span>
                </div>
                <div class="col-4 ">
                    {{ $showproduction->typemachine }}<br> <br>
                </div>
                <div class="col-2 ">
                    <span class="headfont">เบอร์เครื่อง : </span>
                </div>
                <div class="col-4 ">
                    {{ $showproduction->machinenumber }}<br> <br>
                </div>
            </div>
            <!--row-->
        </div>
        {{-- <div class="row" style="margin-top: -2rem;">
            <div class="col-8 ">

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
        </div> --}}
        {{-- <div class="purchase_order_info"style="margin-top: -2rem;;"> --}}
        <div class=""style="margin-top: -2rem;;">

            <div class="row" style="margin-top: -1rem;">

                {{-- <div class="col-md-7">
                    <div class="row"> --}}
                <div class="col-md-2">
                    <span>หมายเหตุ</span>
                </div>
                <div class="col-md-10">
                    @if ($showproduction->comment != 'no data')
                        {{ $showproduction->comment2 }}
                    @endif
                    @if ($showproduction->vat == 'SOX')
                        <span> ราคานี้รวม VAT แล้ว</span><br>
                    @endif
                    @if ($showproduction->po != 'no data')
                        {{ $showproduction->po }}
                    @endif
                    @if ($showproduction->typrtag == 'm')
                        <span class="headfont">ออร์เดอร์ :
                        </span>
                        {{-- {{ $showproduction->orderSumM }} --}}
                        <?php echo number_format($showproduction->orderSumM, 0, '.', ','); ?>
                        เมตร<br><br>
                    @else
                        <span class="headfont">ออร์เดอร์ :
                        </span>
                        <?php echo number_format($showproduction->orderSumYard, 0, '.', ','); ?>
                        {{-- {{ $showproduction->orderSumYard }} --}}
                        หลา<br><br>
                    @endif
                </div>
                {{-- </div>
                </div> --}}
            </div>
            <!--row-->
        </div>
        <!--purchase_order_info-->

        {{-- <div class="purchase_order_signature">
            <div class="row">
                <div class="col-md-3">
                    <p><strong>ผู้สั่งงาน</strong></p>
                </div>
                <div class="col-md-3">
                    <p><strong>ผู้ตรวจสอบ </strong></p>
                </div>
                <!--row-->
            </div>
            <!--purchase_order_signature-->
        </div>
        <!--inner_content--> --}}
        <div class="row" style="margin-top: -1.8rem;">
            <div class="col-6 ">
                <p><span>ผู้สั่งงาน_______________________________________</span></p>
            </div>
            <div class="col-6 ">
                <p><span>ผู้ตรวจสอบ_____________________________________</span></p>
            </div>
        </div>
        {{-- 1 --}}
        <div class="row" style="margin-top: -32rem;">
            <div class="col-8 ">
            </div>
            <div class="col-4" id="bordereddashed">
            </div>
        </div>
        {{-- 2 --}}
        <div class="row" style="margin-top: 5rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            <div class="col-4 "id="bordereddashed">

            </div>
        </div>
        {{-- 3 --}}
        <div class="row" style="margin-top: 2.5rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            {{-- <div class="col-4 "id="bordereddashed">
            </div> --}}
        </div>
        {{-- 4 --}}
        <div class="row" style="margin-top: 2.5rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            {{-- <div class="col-4 "id="bordereddashed">
            </div> --}}
        </div>
        {{-- 5 --}}
        <div class="row" style="margin-top: 2.75rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
        </div>
        {{-- 6 --}}
        <div class="row" style="margin-top: 2.5rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
        </div>
        {{-- 7 --}}
        <div class="row" style="margin-top: 2.75rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            {{-- <div class="col-4 "id="bordereddashed">
            </div> --}}
        </div>
        {{-- 8 --}}
        <div class="row" style="margin-top: 2.75rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
        </div>
        {{-- 9 --}}
        <div class="row" style="margin-top: 2.5rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
        </div>
        {{-- 10 --}}
        <div class="row" style="margin-top: 2.8rem;">

        </div>
        <div class="row" style="margin-top: 1.8rem;">
            {{-- <div class="col-2 ">
            </div> 
            <div class="col-10 "id="bordereddashed">
            </div> --}}
            <div class="col-12 "id="bordereddashed">
            </div>
        </div>
    </div>

    <!--content-->


    <!--content2-->



    <script>
        function printPdf() {
            // Set the page size to 9x5.5 inches
            document.body.style.width = '9in';
            document.body.style.height = '5.5in';
            window.print();
        }
    </script>
</body>

</html>
