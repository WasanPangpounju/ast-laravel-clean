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
                margin-left: 3rem;
                font-size: 1.5rem;
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
                    <div class="col-md-12"style="margin-top: 0rem;">
                        <strong>ชื่อลูกค้า : {{ $showproduction->customerName }} </strong>
                        <strong>No. : {{ $showproduction->no }} </strong>
                        <strong> วันที่ : <?php echo date('d/m/Y', strtotime($showproduction->createDate)); ?> </strong>

                        <?php
                        //Get Customer data
                        $customerData = App\Http\Controllers\OrderController::customerData($showproduction->customerName);
                        ?>
                    </div>
                    {{-- <div class="col-md-6">

                      
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="purchase_order_info">
            <div class="detail" style="font-size: 1.5rem;margin-top: -1.5rem;">
                <div class="row" style="margin-top: 0.5rem;">

                    <div class="col-5 table-responsive">
                        <b>โครงสร้างผ้า : </b> <ins>{{ $showproduction->s1 }}</ins><br>
                        <b style="margin-right: 6rem"></b>{{ $showproduction->s2 }}<br><br>
                    </div>
                    <div class="col-3 table-responsive">
                        <b>หน้าผ้ากว้าง : </b> {{ $showproduction->fabric_w }} นิ้ว<br><br>

                    </div>
                    <div class="col-4 table-responsive">
                        <b> ลายผ้า </b>
                        @if ($showproduction->yarnHRatio1 != 'no data')
                            {{ $showproduction->yarnHRatio1 }}
                        @endif <?php echo preg_replace('/\([^)]+\)/', '', $showproduction->fabricPattern); ?>
                        <br>
                        <b>รหัสผ้า </b> {{ $showproduction->fabricId }}<br><br>
                    </div>
                </div>
                <!--row-->
                <div class="row" style="margin-top: -2rem;;">
                    <div class="col-5 table-responsive">
                        <b>จำนวนด้ายยืน : </b> {{ $showproduction->yarn_h_count }}<br><br>
                    </div>
                </div>
                <div class="row" style="margin-top: -1.5rem;">

                    <div class="col-12 table-responsive">
                        <b>ชนิดด้ายยืน : </b> {{ $showproduction->yarnHType1 }} <?php $subNameH1 = str_replace('บริษัท', '', $showproduction->subNameH1); ?>
                        {{ $subNameH1 }}<br><br>
                    </div>
                </div>
                <div class="row" style="margin-top: -1.5rem;">

                    <div class="col-12 table-responsive">
                        <b>ชนิดด้ายพุ่ง : </b> {{ $showproduction->yarnWType1 }} <?php $subNameW1 = str_replace('บริษัท', '', $showproduction->subNameW1); ?>
                        {{ $subNameW1 }}<br><br>
                    </div>
                </div>
                <div class="row" style="margin-top: -1.5rem;">

                    <div class="col-4 table-responsive">
                        <b>จำนวนด้ายพุ่ง : </b> {{ $showproduction->yarnWCount1 }}<br><br>

                    </div>
                    <div class="col-4 table-responsive">
                        {{-- <b>หน้าผ้ากว้าง : </b> {{ $showproduction->fabric_w }} นิ้ว<br><br> --}}

                    </div>
                    <div class="col-2 table-responsive">
                        <b>สืบ : </b> <br><br>

                    </div>
                </div>
                <div class="row" style="margin-top:-1.5rem;">

                    <div class="col-4 table-responsive">
                        <b>ฟันหวีเบอร์ : </b> {{ $showproduction->phewNumber }}<br><br>

                    </div>
                    <div class="col-4 table-responsive">
                        <b>หน้าหวีกว้าง : </b> {{ $showproduction->phewW }} นิ้ว<br><br>

                    </div>
                    <div class="col-2 table-responsive">
                        <b>ไซร์ : </b> <br><br>

                    </div>
                </div>
                <!--row-->
                <div class="row" style="margin-top: -1.5rem;">

                    <div class="col-4 table-responsive">
                        {{-- <b>พ่นสีลงผ้า : </b> {{ $showproduction->vat }}<br><br> --}}
                        <b>พ่นสีลงผ้า : </b> {{ $showproduction->vat }}<br><br>

                    </div>

                </div>
                <!--row-->
                <div class="row" style="margin-top: -1.5rem;">

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
                <div class="row" style="margin-top: -1.5rem;;">

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
        {{-- <div class="row" style="margin-top: -2rem;">
            <div class="col-8 table-responsive">

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
        <div class="purchase_order_info"style="margin-top: -2rem;;">
            <div class="row" style="margin-top: -1rem;">
                {{-- <div class="col-md-7">
                    <div class="row"> --}}
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
                        <b>ออร์เดอร์สืบ :
                        </b>{{ $showproduction->orderSumM }}
                        เมตร<br><br>
                    @else
                        <b>ออร์เดอร์สืบ :
                        </b>{{ $showproduction->orderSumYard }}
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
        <div class="row" style="margin-top: -1.5rem;">

            <div class="col-6 table-responsive">
                <p><strong>ผู้สั่งงาน_______________________________________</strong></p>
            </div>
            <div class="col-6 table-responsive">
                <p><strong>ผู้ตรวจสอบ_____________________________________</strong></p>
            </div>
        </div>
    </div>
    <!--content-->

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
