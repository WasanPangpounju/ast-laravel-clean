@extends('layouts.astmanufacturing')
<?php
//coordinator name use = $orderdata->deadline
//การลงผ้า use = $fabricdata[0]->vat
//sercharge use = $structuredata[0]->yarnWRatio4
?>
@section('content')
    @if (Auth::user()->user_type != 'admin' && Auth::user()->user_type != 'supermaterialstaff')
        <div class="content-wrapper">
            <div class="">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
                    <li class="breadcrumb-item"><a href="#">ระบบซื้อขาย</a></li>
                    <li class="breadcrumb-item active">ใบคำสั่งซื้อ</li>
                </ol>
            </div>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ใบคำสั่งซื้อ</h1>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <div class="content">
                <div class="box-from">
                    <h2 class="title"><i class="fa fa-caret-right"></i> ขออภัยท่านไม่สามารถใช้งานส่วนนี้ได้</h2>
                    <p>กรุณาติดต่อ หัวหน้างานของท่านเพื่อเพิ่มสิทธิ์การใช้งาน</p>
                    <a href="/home">กลับหน้าหลัก</a>
                </div>
                <!--box-from-->
            </div>
            <!--content-->

        </div>
    @else
        <div class="content-wrapper">
            <div class="">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
                    <li class="breadcrumb-item"><a href="#">ระบบซื้อขาย</a></li>
                    <li class="breadcrumb-item active">รายละเอียดการสั่งซื้อ</li>
                </ol>
            </div>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ใบคำสั่งซื้อ</h1>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <div class="content">
                <div class="box-from">
                    <h2 class="title"><i class="fa fa-caret-right"></i> รายละเอียดคำสั่งซื้อ</h2>
                    <div class="date_order"><strong>สร้างเมื่อ
                        </strong>{{ $orderdata->createDate }}<span><strong> </strong> </span></div>


                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">

                            <p><b>ชื่อลูกค้า :</b> {{ $orderdata->customerName }}
                                @if ($orderdata->customerName != $orderdata->deadline)
                                    <b>ผู้ประสานงาน :</b> {{ $orderdata->deadline }}
                                @endif

                            </p>
                        </div>
                        <div class="col-md-5">

                            <p><b> ขายโดย :</b> {{ $orderdata->emp }} </p>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">

                            <p><b>รหัสผ้า :</b> {{ $orderdata->fabricId }} </p>
                            <p> <b>ลายผ้า :</b> {{ $orderdata->fabricPattern }} </p>

                        </div>
                        <div class="col-md-5">

                            <p> <b>โครงสร้างผ้า :</b> {{ $orderdata->fabricStructure }} </p>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">

                            <p><b>จำนวนด้ายยืน :</b> {{ $fabricdata[0]->yarn_h_count }} เส้น</p>
                            <p> <b>หน้าผ้ากว้าง :</b> {{ $fabricdata[0]->fabric_w }} นิ้ว</p>

                        </div>
                        <div class="col-md-5">

                            <!-- <p> <b>โครงสร้างผ้า :</b> {{ $orderdata->fabricStructure }} </p> -->

                        </div>
                    </div>

                    <div class="row">
                        <br>
                    </div>


                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p> <b>ชนิดด้ายยืน 1 : </b>{{ $structuredata[0]->yarnHType1 }} </p>
                        </div>
                        <div class="col-md-2">
                            <p> <b>บริษัท :</b> {{ $structuredata[0]->subNameH1 }}</p>
                        </div>
                        <div class="col-md-2">
                            <p><b> ด้ายยืน 1 :</b> {{ $structuredata[0]->yarnHCount1 }} <b>เส้น</b></p>
                        </div>
                        <div class="col-md-2">
                            <p> <b>อัตราส่วน :</b> {{ $structuredata[0]->yarnHRatio1 }}</p>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <!--row-->

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p><b> ชนิดด้ายยืน 2 :</b>
                                @if ($structuredata[0]->yarnHType2 != 'no data')
                                    {{ $structuredata[0]->yarnHType2 }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p><b> บริษัท : </b>
                                @if ($structuredata[0]->subNameH2 != 'no data')
                                    {{ $structuredata[0]->subNameH2 }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p><b> ด้ายยืน 2 :</b>
                                @if ($structuredata[0]->yarnHCount2 != 'no data')
                                    {{ $structuredata[0]->yarnHCount2 }} <b>เส้น</b>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <!--row-->

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p><b> ชนิดด้ายพุ่ง 1 : </b>
                                @if ($structuredata[0]->yarnWType1 != 'no data')
                                    {{ $structuredata[0]->yarnWType1 }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p> <b>บริษัท : </b>
                                @if ($structuredata[0]->subNameW1 != 'no data')
                                    {{ $structuredata[0]->subNameW1 }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p><b> ด้ายพุ่ง 1 : </b>
                                @if ($structuredata[0]->yarnWCount1 != 'no data')
                                    {{ $structuredata[0]->yarnWCount1 }}
                                    <b>เส้น</b>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <!--row-->

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p><b> ชนิดด้ายพุ่ง 2 : </b>
                                @if ($structuredata[0]->yarnWType2 != 'no data')
                                    {{ $structuredata[0]->yarnWType2 }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p><b> บริษัท : </b>
                                @if ($structuredata[0]->subNameW2 != 'no data')
                                    {{ $structuredata[0]->subNameW2 }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p> <b>ด้ายพุ่ง 2 :</b>
                                @if ($structuredata[0]->yarnWCount2 != 'no data')
                                    {{ $structuredata[0]->yarnWCount2 }} <b>เส้น</b>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <!--row-->

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p> <b>ชนิดด้ายพุ่ง 3 : </b>
                                @if ($structuredata[0]->yarnWType3 != 'no data')
                                    {{ $structuredata[0]->yarnWType3 }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p> <b>บริษัท :</b>
                                @if ($structuredata[0]->subNameW3 != 'no data')
                                    {{ $structuredata[0]->subNameW3 }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p> <b>ด้ายพุ่ง 3 : </b>
                                @if ($structuredata[0]->yarnWCount3 != 'no data')
                                    {{ $structuredata[0]->yarnWCount3 }}
                                    <b>เส้น</b>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <!--row-->

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p><b> ชนิดด้ายพุ่ง 4 : </b>
                                @if ($structuredata[0]->yarnWType4 != 'no data')
                                    {{ $structuredata[0]->yarnWType4 }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p><b> บริษัท : </b>
                                @if ($structuredata[0]->subNameW4 != 'no data')
                                    {{ $structuredata[0]->subNameW4 }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p> <b>ด้ายพุ่ง 4 : </b>
                                @if ($structuredata[0]->yarnWCount4 != 'no data')
                                    {{ $structuredata[0]->yarnWCount4 }}
                                    <b>เส้น</b>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <!--row-->

                    <div class="row">
                        <br>
                    </div>
                    <!--row-->

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <p> <b>เบอร์หวี :</b> {{ $fabricdata[0]->phewNumber }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><b> หน้าหวี :</b> {{ $fabricdata[0]->phewW }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><b> การลงผ้า :</b> {{ $fabricdata[0]->vat }}</p>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    <!--row-->
                    <!--end Structure -->


                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">
                            <p><b>จำนวนออเดอร์ :</b> {{ $orderdata->orderSumYard }} <b>หลา :</b>
                                {{ $orderdata->orderSumM }} <b>เมตร</b></p>
                        </div>
                        <div class="col-md-5">
                            <p><b>การสืบ :</b> {{ $orderdata->fabricSPY }} <b>%</b>
                                {{ $orderdata->fabricSPY * ($orderdata->orderSumYard / 100) + $orderdata->orderSumYard }}
                                <b>หลา</b>
                                {{ $orderdata->fabricSPY * ($orderdata->orderSumM / 100) + $orderdata->orderSumM }}
                                <b>เมตร</b>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">
                            <p><b>ราคาต่อหน่วย หลาละ :</b>***{{-- $orderdata->priceYard --}} <b>บาท เมตรละ :</b>
                                ***{{-- $orderdata->priceM --}} <b>บาท</b></p>
                        </div>
                        <div class="col-md-5">
                            <p><b>ส่วนลด :</b> ***{{-- $orderdata->discountP --}} % {{-- $orderdata->discountYard --}} บาท</p>
                            <p><b>คอมมิชชั่น :</b> ***{{-- $orderdata->commission --}}</p>
                            <p><b>SURCHARGE :</b> ***{{-- $structuredata[0]->yarnWRatio4 --}}</p>
                        </div>
                    </div>


                    <div class="row">
                        {{-- <div class="col-md-2"></div> --}}
                        <div class="col-md-2">
                        </div>
                        <p><b>เลขที่ใบสั่งซื้อ :</b> {{ $orderdata->purchaseOrder }}</p>
                        <div class="col-md-5">
                            <p><b>PO ลูกค้า :</b>
                                @if ($orderdata->po != 'no data')
                                    {{ $orderdata->po }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
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
                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p><b>หมายเหตุ</b> {{ $orderdata->comment }}</p>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p><b>เงื่อนไขการชำระเงิน</b> {{ $fabricdata[0]->payment }}</p>
                        </div>
                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <p><b>บันทึกที่คลังสินค้า :</b> {{ $imdata->inventoryName }}
                        </div>
                        <div class="col-md-3">
                            <p><b>วันที่ :</b> {{ date('d/m/Y', strtotime($imdata->createDate)) }} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <p><b>จำนวนพับ :</b> {{ $imdata->fold }} <b>พับ</b>
                        </div>
                        <div class="col-md-3">
                            <p><b>จำนวนหลา :</b> {{ $imdata->sumYard }} <b>หลา</b></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <p><b>หน้ากว้าง :</b> {{ $imdata->fabricW }}
                        </div>
                        <div class="col-md-3">
                            <p><b>จำนวนเมตร :</b> {{ $imdata->sumM }} <b>เมตร</b></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <p><b>หมายเหตุ</b> {{ $imdata->comment }}</p>
                        </div>

                    </div>
                </div>
                <!--box-from-->
                <div class="purchase_order_signature">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-3" style="margin-left:13em;">
                            <form action="{{ route('inventory.store', $imdata->refId) }}" method="post">
                                @csrf


                                <input type="hidden" name="refId" value="{{ $orderdata->id }}">
                                {{-- <input type="hidden" name="emp" value="{{ $orderdata->emp }}"> --}}
                                <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
                                <input type="hidden" name="inventoryName" value="{{ $imdata->inventoryName }}">
                                <input type="hidden" name="orderId" value="{{ $orderdata->purchaseOrder }}">
                                <input type="hidden" name="createDate"
                                    value="{{ date('d/m/Y', strtotime($imdata->createDate)) }}">
                                <input type="hidden" name="fabricId" value="{{ $orderdata->fabricId }}">
                                <input type="hidden" name="fabricStruct" value="{{ $orderdata->fabricStructure }}">
                                <input type="hidden" name="fold" value="{{ $imdata->fold }}">
                                <input type="hidden" name="sumYard" value="{{ $imdata->sumYard }}">
                                <input type="hidden" name="sumM" value="{{ $imdata->sumM }}">
                                <input type="hidden" name="fabricW" value="{{ $fabricdata[0]->fabric_w }}">
                                <input type="hidden" name="comment" value="{{ $imdata->comment }}">


                                <button type="button" class="btn b_order" name="submit" value="index"><a
                                        href="{{ route('order.index') }}">กลับหน้ารายการ</a></button>
                                <button type="submit" class="btn b_order" name="submit"
                                    value="savedata">บันทึก</button>
                                {{-- <button type="submit" class="btn b_order" name="submit" value="genPDF">Generate
                                    PDF</button> --}}
                            </form>
                        </div>
                    </div>
                    <!--row-->
                </div>
                <!--purchase_order_signature-->
            </div>
            <!--content-->

        </div><!-- /.content-wrapper -->
    @endif


    <script>
        function createFabricStructure() {
            s1 = "";
            s2 = "";

            yarnHType1 = document.getElementById("yarnHType1");
            yarnHCount1 = document.getElementById("yarnHCount1");
            yarnHType2 = document.getElementById("yarnHType2");
            yarnHCount2 = document.getElementById("yarnHCount2");
            yarnWType1 = document.getElementById("yarnWType1");
            yarnWCount1 = document.getElementById("yarnWCount1");
            yarnWType2 = document.getElementById("yarnWType2");
            yarnWCount2 = document.getElementById("yarnWCount2");
            yarnWType3 = document.getElementById("yarnWType3");
            yarnWCount3 = document.getElementById("yarnWCount3");
            yarnWType4 = document.getElementById("yarnWType4");
            yarnWCount4 = document.getElementById("yarnWCount4");
            fabricStruct = '';


            if (yarnHType1.value != "" && yarnHCount1.value != "") {
                fabricStruct = yarnHType1.value + ' / ' + yarnHCount1.value;
                s1 = yarnHType1.value;
                s2 = yarnHCount1.value;

                if (yarnHType2.value != "") {
                    fabricStruct = '(' + yarnHType1.value + ' + ' + yarnHType2.value + ')' + ' / ' + yarnHCount1.value;
                    s1 = '(' + yarnHType1.value + ' + ' + yarnHType2.value + ')';
                    s2 = yarnHCount1.value;

                }

                if (yarnWType1.value != "" && yarnWCount1.value != "") {
                    yw = yarnWType1.value + ' / ' + yarnWCount1.value;
                    s1 = s1 + ' * ';
                    s2 = yarnHCount1.value;

                    if (yarnWType2.value != "" || yarnWType3.value != "" || yarnWType4.value != "") {
                        yw = '(' + yarnWType1.value;
                        if (yarnWType2.value != "") {
                            yw = yw + ' + ' + yarnWType2.value;
                        }
                        if (yarnWType3.value != "") {
                            yw = yw + ' + ' + yarnWType3.value;
                        }
                        if (yarnWType4.value != "") {
                            yw = yw + ' + ' + yarnWType4.value;
                        }
                        s1 = s1 + yw + ' )';
                        s2 = s2 + ' * ' + yarnWCount1.value;

                        yw = yw + ') / ' + yarnWCount1.value;
                    } else {
                        s1 = s1 + yarnWType1.value;
                        s2 = s2 + ' * ' + yarnWCount1.value;
                    }

                    fabricStruct = fabricStruct + ' * ' + yw;

                } else {
                    alert('กรุณากรอก ชนิดด้ายพุ่ง 1 และ จำนวนด้ายพุ่ง 1');
                }

            } else {
                alert('กรุณากรอกข้อมูลชนิดด้ายยืน 1 และ จำนวนด้ายยืน 1');
            }

            document.getElementById("s1").value = s1;
            document.getElementById("s2").value = s2;

            document.getElementById("fabricStructure1").value = s1 + ' / ' + s2;
            //document.getElementById("fabricStructure1").value = fabricStruct;

        }
    </script>
    <script>
        $(function() {
            $('#reservationdate1').datetimepicker();
            $('#reservationdate2').datetimepicker(); // <<<<< add this line 
        });
    </script>

@endsection
