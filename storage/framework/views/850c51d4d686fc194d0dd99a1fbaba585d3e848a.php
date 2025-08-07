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
        @media  print {
            @page  {
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
                            ชื่อลูกค้า: <?php echo e(str_replace('(สำนักงานใหญ่)', '', $showproduction->customerName)); ?>

                        </strong>
                        <strong style="text-align: center; margin-left: 1rem;">
                            วันที่: <?php echo e(date('d/m/Y', strtotime($showproduction->createDate))); ?>

                        </strong>
                        <strong style="text-align: right; margin-left: 2rem;">
                            No.: <?php echo e($showproduction->no); ?>

                        </strong>
                    </div>

                    
                    <?php
                    //Get Customer data
                    $customerData = App\Http\Controllers\OrderController::customerData($showproduction->customerName);
                    ?>
                    
                </div>
            </div>
        </div>
        
        <div class="" style="margin-top: 1.5rem;">

            <div class="detail" style="font-size: 1.3rem;margin-top: -1.3rem;">
                <div class="row" style="margin-top: 0.5rem;">
                    <div class="col-6 ">
                        

                        <?php if($showproduction->yarnHRatio1 != 'no data' || $showproduction->yarnWRatio1 != 'no data'): ?>
                            <div class="row">
                                <div class="col-5" style="display: flex; justify-content: center;">
                                    <?php if($showproduction->yarnHRatio1 != 'no data'): ?>
                                        <?php echo e($showproduction->yarnHRatio1); ?>

                                    <?php endif; ?>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-5" style="display: flex; justify-content: center;">

                                    <?php if($showproduction->yarnWRatio1 != 'no data'): ?>
                                        <?php echo e($showproduction->yarnWRatio1); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($showproduction->yarnHRatio1 != 'no data' && $showproduction->yarnWRatio1 != 'no data'): ?>
                            <div class="row" style="border-bottom: 1px solid black; margin-top: -1rem;">
                            <?php else: ?>
                                <div class="row" style="border-bottom: 1px solid black;">
                        <?php endif; ?>

                        
                        <div class="col-5" style="display: flex; justify-content: center;margin-top: 1rem;">
                            
                            <?php
                            $parts = explode('*', $showproduction->s1);
                            if (count($parts) > 0) {
                                $textBeforeAsterisk = trim($parts[0]);
                                // echo $textBeforeAsterisk;
                                echo str_replace('undefined', '', $textBeforeAsterisk);
                                // echo trim($textBeforeAsterisk, 'undefined');
                            }
                            ?>
                        </div>
                        <div class="col-1">x</div>
                        <div class="col-5" style="display: flex; justify-content: center;margin-top: 1rem;">

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
                            
                            <?php
                            $parts = explode('*', $showproduction->s2);
                            
                            if (count($parts) > 0) {
                                $textBeforeAsterisk = trim($parts[0]);
                                echo $textBeforeAsterisk;
                            }
                            ?>
                            
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
                <div class="col-1 ">
                    <?php echo e($showproduction->fabric_w); ?> "<br><br>
                    <br>
                </div>
                <div class="col-1">
                    <span class="headfont"> ลายผ้า :</span>
                </div>
                <div class="col-4">
                    
                    <?php echo e($showproduction->fabricPattern); ?>

                </div>
                

            </div>
            <!--row-->
            <br>
            <div class="row" style="margin-top: -2rem;">
                <div class="col-2 ">
                    <span class="headfont">จำนวนด้ายยืน :<br>
                </div>
                <div class="col-4 ">
                    <?php echo e($showproduction->yarn_h_count); ?><br>
                </div>
                <div class="col-1 ">
                    <span class="headfont">รหัสผ้า :
                </div>
                <div class="col-4 ">
                    <?php echo e($showproduction->fabricId); ?><br>
                </div>
            </div>
            
            <div class="row" style="margin-top: 0.5rem;">
                <div class="col-2 ">
                    <span class="headfont">ชนิดด้ายยืน : </span>
                    <br>
                </div>
                <div class="col-10 ">
                    <?php echo e($showproduction->yarnHType1); ?> <?php $subNameH1 = str_replace('บริษัท', '', $showproduction->subNameH1); ?>
                    <?php echo e($subNameH1); ?>

                    <?php
                    if ($showproduction->subNameH2 != null) {
                        echo ' + ' . $showproduction->yarnHType2 . ',' . str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameH2);
                    }
                    ?>
                    <br>
                </div>
            </div>
            
            <div class="row"style="margin-top: 0.5rem;">

                <div class="col-2 ">
                    <span class="headfont">ชนิดด้ายพุ่ง :
                        <br>
                </div>
                <div class="col-10 ">
                    <?php echo e($showproduction->yarnWType1); ?>

                    <?php $subNameW1 = str_replace('บริษัท', '', $showproduction->subNameW1); ?>
                    <?php echo e($subNameW1); ?>

                    <?php
                    if ($showproduction->subNameW2 != null) {
                        echo '+ ' . $showproduction->yarnWType2 . ' ' . str_replace(['บริษัท', 'จำกัด'], '', $showproduction->subNameW2);
                    }
                    ?>
                    <br>
                </div>
            </div>
            
            <div class="row"style="margin-top: 0.5rem;">

                <div class="col-2 ">
                    <span class="headfont">จำนวนด้ายพุ่ง :

                </div>
                <div class="col-4 ">
                    <?php echo e($showproduction->yarnWCount1); ?><br>

                </div>
                <div class="col-2 ">
                    <span class="headfont">หน้าผ้ากว้าง : </span>

                </div>
                <div class="col-4 ">
                    <?php echo e($showproduction->fabric_w); ?> นิ้ว<br>
                </div>
            </div>
            
            <div class="row"style="margin-top: 0.5rem;">

                <div class="col-2 ">
                    <span class="headfont">ฟันหวีเบอร์ : </span>

                </div>
                <div class="col-4 ">
                    <?php echo e($showproduction->phewNumber); ?><br>

                </div>
                <div class="col-2 ">
                    <span class="headfont">หน้าหวีกว้าง : </span>

                </div>
                <div class="col-4 ">
                    <?php echo e($showproduction->phewW); ?> นิ้ว<br>

                </div>
            </div>
            <!--row-->
            
            <div class="row"style="margin-top: 0.5rem;">

                <div class="col-2 ">
                    
                    <span class="headfont">พ่นสีลงผ้า : </span>

                </div>
                <div class="col-10 ">
                    
                    <?php echo e($showproduction->vat); ?><br>

                </div>

            </div>
            <!--row-->
            
            <div class="row"style="margin-top: 0.5rem;">
                <div class="col-2 ">
                    <?php if($showproduction->typrtag == 'm'): ?>
                        <span class="headfont">ออร์เดอร์สืบ :
                        </span>
                    <?php else: ?>
                        <span class="headfont">ออร์เดอร์สืบ :
                        </span>
                    <?php endif; ?>
                </div>
                <div class="col-4 ">
                    <?php if($showproduction->typrtag == 'm'): ?>
                        

                        
                        <?php
                        $order = $showproduction->fabricSPY * ($showproduction->orderSumM / 100) + $showproduction->orderSumM;
                        echo number_format($order, 0, '.', ',');
                        ?>

                        เมตร<br><br>
                    <?php else: ?>
                        
                        <?php
                        $order = $showproduction->fabricSPY * ($showproduction->orderSumYard / 100) + $showproduction->orderSumYard;
                        echo number_format($order, 0, '.', ',');
                        ?>

                        หลา<br><br>
                    <?php endif; ?>
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
                <div class="col-4 "style="margin-left: -1rem">
                    <?php echo e($showproduction->typemachine); ?><br> <br>
                </div>
                <div class="col-2 ">
                    <span class="headfont">เบอร์เครื่อง : </span>
                </div>
                <div class="col-4" style="margin-left: -1rem">
                    <?php
                    $machinenumber = $showproduction->machinenumber;
                    
                    if (strlen($machinenumber) > 10) {
                        echo '<span style="font-size: 16px;">' . $machinenumber . '</span>';
                    } else {
                        echo $machinenumber;
                    }
                    ?>
                </div>
            </div>
            <!--row-->
        </div>
        
        
        <div class=""style="margin-top: -2rem;;">

            <div class="row" style="margin-top: -1rem;">

                
                <div class="col-md-2">
                    <span>หมายเหตุ</span>
                </div>
                <div class="col-md-10">
                    <?php if($showproduction->comment != 'no data'): ?>
                        <?php echo e($showproduction->comment2); ?>

                    <?php endif; ?>
                    <?php if($showproduction->vat == 'SOX'): ?>
                        <span> ราคานี้รวม VAT แล้ว</span><br>
                    <?php endif; ?>
                    <?php if($showproduction->po != 'no data'): ?>
                        <?php echo e($showproduction->po); ?>

                    <?php endif; ?>
                    <?php if($showproduction->typrtag == 'm'): ?>
                        <span class="headfont">ออร์เดอร์ :
                        </span>
                        
                        <?php echo number_format($showproduction->orderSumM, 0, '.', ','); ?>
                        เมตร<br><br>
                    <?php else: ?>
                        <span class="headfont">ออร์เดอร์ :
                        </span>
                        <?php echo number_format($showproduction->orderSumYard, 0, '.', ','); ?>
                        
                        หลา<br><br>
                    <?php endif; ?>
                </div>
                
            </div>
            <!--row-->
        </div>
        <!--purchase_order_info-->

        
        <div class="row" style="margin-top: -1.8rem;">
            <div class="col-6 ">
                <p><span>ผู้สั่งงาน_______________________________________</span></p>
            </div>
            <div class="col-6 ">
                <p><span>ผู้ตรวจสอบ_____________________________________</span></p>
            </div>
        </div>
        
        <div class="row" style="margin-top: -32rem;">
            <div class="col-7 ">
            </div>
            <div class="col-5" id="bordereddashed">
            </div>
        </div>
        
        <br>
        <div class="row" style="margin-top: 5rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            
            <div class="col-5 "id="bordereddashed">

            </div>
        </div>
        
        <div class="row" style="margin-top: 2.5rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            
        </div>
        
        <div class="row" style="margin-top: 2.5rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            
        </div>
        
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
        
        <div class="row" style="margin-top: 2.5rem;">
            <div class="col-2 ">
            </div>
            <div class="col-4 "id="bordereddashed">
            </div>
            <div class="col-1 ">

            </div>
            <div class="col-1 ">

            </div>
            
        </div>
        
        <div class="row" style="margin-top: 2.25rem;">
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
        
        <div class="row" style="margin-top: 2rem;">
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
        
        <div class="row" style="margin-top: 3.5rem;">

        </div>
        <div class="row" >
            
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
<?php /**PATH /var/www/html/ast-menufacturing/resources/views/orders/showproductionpdf.blade.php ENDPATH**/ ?>