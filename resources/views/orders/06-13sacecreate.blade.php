@extends('layouts.astmanufacturing')

@section('content')
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
        rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
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
                    <h2 class="title"><i class="fa fa-caret-right"></i> เพิ่มคำสั่งซื้อ</h2>
                    <div class="date_order"><strong>วันที่
                        </strong><?php echo date('d/m/Y'); ?><span><strong>NO.</strong>{{ $last + 1 }}</span></div>

                    <form onsubmit="createFabricStructure()" method="post" action="{{ route('order.store') }}">
                        @csrf
                        <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">

                        <input type="hidden" id="s1" name="s1" value="">
                        <input type="hidden" id="s2" name="s2" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <label>วันที่</label>
                                <div class="form-group">
                                    {{-- <div style="z-index: 9999;" class="input-group date" id="reservationdate"
                                        data-target-input="nearest">
                                        <input type="text" name="createDate" class="form-control datetimepicker-input"
                                            data-target="#reservationdate" />
                                        <div class="input-group-append" data-target="#reservationdate"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="form-group"> --}}
                                    {{-- <label for="startDate">Start Date</label> --}}
                                    {{-- <div class="input-group">
                                        <input type="date" name="createDate" id="startDate" class="form-control"
                                            date-format="d/m/Y">
                                    </div> --}}
                                    {{-- </div> --}}
                                    <input class="date form-control" type="text" name="createDate" autocomplete="off">
                                    {{-- $date = date('d/m/Y', strtotime($orderEdit->createDate)) --}}

                                    <script type="text/javascript">
                                        $('.date').datepicker({
                                            format: 'dd/mm/yyyy',
                                            orientation: "bottom",
                                        });
                                    </script>
                                </div>

                                {{-- <div class="form-group">
                                    <div style="z-index: 9999;" class="input-group date" id="reservationdate1"
                                        data-target-input="nearest">
                                        <input type="text" name="createDate" class="form-control datetimepicker-input"
                                            data-target="#reservationdate1" />
                                        <div class="input-group-append" data-target="#reservationdate1"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                            </div>
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="myInput">ชื่อลูกค้า *</label>
                                    <input type="text" name="customerName" class="form-control" id="myInput"
                                        onkeyup="myFunction()" placeholder="ชื่อลูกค้า" required>

                                    <?php $customershow = 'test'; ?>
                                    <ul id="myUL">
                                        @foreach ($customers as $customer)
                                            <li><a
                                                    href="javascript:setcustomerFunction('{{ $customer->name }}');">{{ $customer->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="coname">ผู้ประสานงาน*</label>
                                    <input type="text" name="coname" class="form-control" id="coname"
                                        placeholder="ชื่อผู้ประสานงาน">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fabricId">รหัสผ้า *</label>
                                    <input type="text" name="fabricId" class="form-control" id="fabricId"
                                        placeholder="รหัสผ้า" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fabricPattern">ลายผ้า *</label>
                                    <input type="text" name="fabricPattern" class="form-control" id="fabricPattern"
                                        placeholder="ลายผ้า" required>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="fabricStructure">โครงสร้างผ้า *</label>
                                    <input type="text" name="fabricStructure" class="form-control"
                                        id="fabricStructure1" placeholder="โครงสร้างผ้า" required>
                                </div>
                            </div>
                            <div class="col-md-2 text-right align-items-end" style="margin-top:1em">
                                <div class="form-group">
                                    <label for=""></label>
                                    <button type="button" name="fabricStructure2" onclick="createFabricStructure();"
                                        class="btn btn-primary" id="fabricStructure2" placeholder="สร้าง">สร้าง</button>
                                </div>
                            </div>
                        </div>
                        <!--Structure -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="yarn_h_count">จำนวนด้ายยืน (เส้น) * </label>
                                    <input type="number" name="yarn_h_count" class="form-control" id="yarn_h_count"
                                        placeholder="จำนวนด้ายยืน (เส้น)" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fabric_w">หน้าผ้า (นิ้ว) * </label>
                                    <input type="number" name="fabric_w" class="form-control" id="fabric_w"
                                        placeholder="หน้าผ้า (นิ้ว)" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnHType1">ชนิดด้ายยืน 1 *</label>
                                    <input type="text" name="yarnHType1" onkeyup="yarntypeFunction('yarnHType1' , 0)"
                                        class="form-control" id="yarnHType1" placeholder="ชนิดด้ายยืน 1" required>

                                    <ul name="yarnType" id="supp">
                                        @foreach ($yarnType as $yt)
                                            <li><a
                                                    href="javascript:setyarntypeFunction('yarnHType1', '{{ $yt[0]->yarnType }}');">{{ $yt[0]->yarnType }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subNameH1">บริษัท *</label>
                                    <input type="text" name="subNameH1" onkeyup="supplierFunction('subNameH1' , 0)"
                                        class="form-control" id="subNameH1" placeholder="บริษัท" required>

                                    <?php $suppliershow = 'test'; ?>
                                    <ul name="supp" id="supp">
                                        @foreach ($supplier as $sup)
                                            <li><a
                                                    href="javascript:setsupplierFunction('subNameH1', '{{ $sup->name }}');">{{ $sup->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnHCount1">จำนวน (เส้น) * </label>
                                    <input type="text" name="yarnHCount1" class="form-control" id="yarnHCount1"
                                        placeholder="" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnHRatio1">อัตราส่วน </label>
                                    <input type="text" name="yarnHRatio1" class="form-control" id="yarnHRatio1"
                                        placeholder="">
                                </div>
                            </div>
                        </div>
                        <!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnHType2">ชนิดด้ายยืน 2</label>
                                    <input type="text" name="yarnHType2" onkeyup="yarntypeFunction('yarnHType2' , 1)"
                                        class="form-control" id="yarnHType2" placeholder="">

                                    <ul name="yarnType" id="supp">
                                        @foreach ($yarnType as $yt)
                                            <li><a
                                                    href="javascript:setyarntypeFunction('yarnHType2', '{{ $yt[0]->yarnType }}');">{{ $yt[0]->yarnType }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subNameH2">บริษัท</label>
                                    <input type="text" name="subNameH2" onkeyup="supplierFunction('subNameH2' , 1)"
                                        class="form-control" id="subNameH2" placeholder="บริษัท">

                                    <ul name="supp" id="supp">
                                        @foreach ($supplier as $sup)
                                            <li><a
                                                    href="javascript:setsupplierFunction('subNameH2', '{{ $sup->name }}');">{{ $sup->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnHCount2">จำนวน (เส้น)</label>
                                    <input type="text" name="yarnHCount2" class="form-control" id="yarnHCount2"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnHRatio2">อัตราส่วน</label>
                                    <input type="text" name="yarnHRatio2" class="form-control" id="yarnHRatio2"
                                        placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                        <!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWType1">ชนิดด้ายพุ่ง 1 * </label>
                                    <input type="text" name="yarnWType1" onkeyup="yarntypeFunction('yarnWType1' , 2)"
                                        class="form-control" id="yarnWType1" placeholder="" required>

                                    <ul name="yarnType" id="supp">
                                        @foreach ($yarnType as $yt)
                                            <li><a
                                                    href="javascript:setyarntypeFunction('yarnWType1', '{{ $yt[0]->yarnType }}');">{{ $yt[0]->yarnType }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subNameW1">บริษัท * </label>
                                    <input type="text" name="subNameW1" onkeyup="supplierFunction('subNameW1' , 2)"
                                        class="form-control" id="subNameW1" placeholder="บริษัท" required>

                                    <ul name="supp" id="supp">
                                        @foreach ($supplier as $sup)
                                            <li><a
                                                    href="javascript:setsupplierFunction('subNameW1', '{{ $sup->name }}');">{{ $sup->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>


                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWCount1">จำนวน (เส้น) * </label>
                                    <input type="text" name="yarnWCount1" class="form-control" id="yarnWCount1"
                                        placeholder="" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWRatio1">อัตราส่วน </label>
                                    <input type="text" name="yarnWRatio1" class="form-control" id="yarnWRatio1"
                                        placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                        <!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWType2">ชนิดด้ายพุ่ง 2</label>
                                    <input type="text" name="yarnWType2" onkeyup="yarntypeFunction('yarnWType2' , 3)"
                                        class="form-control" id="yarnWType2" placeholder="">

                                    <ul name="yarnType" id="supp">
                                        @foreach ($yarnType as $yt)
                                            <li><a
                                                    href="javascript:setyarntypeFunction('yarnWType2', '{{ $yt[0]->yarnType }}');">{{ $yt[0]->yarnType }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subNameW2">บริษัท</label>
                                    <input type="text" name="subNameW2" onkeyup="supplierFunction('subNameW2' , 3)"
                                        class="form-control" id="subNameW2" placeholder="บริษัท">

                                    <ul name="supp" id="supp">
                                        @foreach ($supplier as $sup)
                                            <li><a
                                                    href="javascript:setsupplierFunction('subNameW2', '{{ $sup->name }}');">{{ $sup->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>


                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWCount2">จำนวน (เส้น)</label>
                                    <input type="text" name="yarnWCount2" class="form-control" id="yarnWCount2"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWRatio2">อัตราส่วน</label>
                                    <input type="text" name="yarnWRatio2" class="form-control" id="yarnWRatio2"
                                        placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                        <!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWType3">ชนิดด้ายพุ่ง 3</label>
                                    <input type="text" name="yarnWType3" onkeyup="yarntypeFunction('yarnWType3' , 4)"
                                        class="form-control" id="yarnWType3" placeholder="">

                                    <ul name="yarnType" id="supp">
                                        @foreach ($yarnType as $yt)
                                            <li><a
                                                    href="javascript:setyarntypeFunction('yarnWType3', '{{ $yt[0]->yarnType }}');">{{ $yt[0]->yarnType }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subNameW3">บริษัท</label>
                                    <input type="text" name="subNameW3" onkeyup="supplierFunction('subNameW3' , 4)"
                                        class="form-control" id="subNameW3" placeholder="บริษัท">

                                    <ul name="supp" id="supp">
                                        @foreach ($supplier as $sup)
                                            <li><a
                                                    href="javascript:setsupplierFunction('subNameW3', '{{ $sup->name }}');">{{ $sup->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>


                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWCount3">จำนวน (เส้น)</label>
                                    <input type="text" name="yarnWCount3" class="form-control" id="yarnWCount3"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWRatio3">อัตราส่วน</label>
                                    <input type="text" name="yarnWRatio3" class="form-control" id="yarnWRatio3"
                                        placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                        <!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWType4">ชนิดด้ายพุ่ง 4</label>
                                    <input type="text" name="yarnWType4" onkeyup="yarntypeFunction('yarnWType4' , 5)"
                                        class="form-control" id="yarnWType4" placeholder="">

                                    <ul name="yarnType" id="supp">
                                        @foreach ($yarnType as $yt)
                                            <li><a
                                                    href="javascript:setyarntypeFunction('yarnWType4', '{{ $yt[0]->yarnType }}');">{{ $yt[0]->yarnType }}</a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subNameW4">บริษัท</label>
                                    <input type="text" name="subNameW4" onkeyup="supplierFunction('subNameW4' , 5)"
                                        class="form-control" id="subNameW4" placeholder="บริษัท">

                                    <ul name="supp" id="supp">
                                        @foreach ($supplier as $sup)
                                            <li><a
                                                    href="javascript:setsupplierFunction('subNameW4', '{{ $sup->name }}');">{{ $sup->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>


                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWCount4">จำนวน (เส้น)</label>
                                    <input type="text" name="yarnWCount4" class="form-control" id="yarnWCount4"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWRatio4">อัตราส่วน</label>
                                    <input type="text" name="yarnWRatio4" class="form-control" id="yarnWRatio4"
                                        placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                        <!--row-->



                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phewNumber">เบอร์หวี * </label>
                                    <input type="text" name="phewNumber" class="form-control" id="phewNumber"
                                        placeholder="เบอร์หวี" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phewW">หน้าหวี (นิ้ว) * </label>
                                    <input type="text" name="phewW" class="form-control" id="phewW"
                                        placeholder="หน้าหวี (นิ้ว)" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fabriccomment">การลงผ้า </label>
                                    <input type="text" name="send_cloth" class="form-control" id="fabriccomment"
                                        placeholder="การลงผ้า">
                                </div>
                            </div>
                        </div>
                        <!--row-->
                        <!--end Structure -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="orderSumYard">จำนวนออเดอร์ (หลา) *</label>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" name="orderSumYard" oninput="yd(this.value)"
                                                onchange="yd(this.value )" class="form-control" id="orderSumYard"
                                                placeholder="หลา" required>
                                        </div>
                                        <div class="col-md-1">
                                            หรือ
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="orderSumM" oninput="ydToM(this.value)"
                                                onchange="ydToM(this.value )" class="form-control" id="orderSumM"
                                                placeholder="เมตร" required>
                                        </div>
                                        <div class="col-md-3">
                                            {{-- <select class="form-select" aria-label="Default select example">
                                                <option value="y">หลา</option>
                                                <option value="m">เมตร</option>
                                              </select> --}}
                                            <select name="typrtag" class="form-control" id="typetag">
                                                <option value="y">หลา</option>
                                                <option value="m">เมตร</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fabricSPY">การสืบ *</label>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" name="fabricSPY" oninput="pToYd(this.value)"
                                                onchange="pToYd(this.value )" class="form-control" id="fabricSPY"
                                                placeholder="%">
                                        </div>
                                        <div class="col-md-1">หรือ</div>
                                        <div class="col-md-6">
                                            <input type="text" name="fabricSpy" oninput="ydToP(this.value)"
                                                onchange="ydToP(this.value )" class="form-control" id="fabricSpy"
                                                placeholder="หลา">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--row-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priceYard">ราคาต่อหน่วย (บาท) *</label>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" name="priceYard" oninput="priceToM(this.value)"
                                                onchange="priceToM(this.value )" class="form-control" id="priceYard"
                                                placeholder="หลา" required>
                                        </div>
                                        <div class="col-md-1">
                                            หรือ
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="priceM" oninput="priceToY(this.value)"
                                                onchange="priceToY(this.value )" class="form-control" id="priceM"
                                                placeholder="เมตร" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discountP">ส่วนลด </label>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" name="discountP"
                                                oninput="discountToPriceYd(this.value)"
                                                onchange="discountToPriceYd(this.value )" class="form-control"
                                                id="discountP" placeholder="%">
                                        </div>
                                        <div class="col-md-1">หรือ</div>
                                        <div class="col-md-6">
                                            <input type="text" name="discountYard"
                                                oninput="discountToPriceP(this.value)"
                                                onchange="discountToPriceP(this.value )" class="form-control"
                                                id="discountYard" placeholder="หลา">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--row-->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="surcharge">SURCHARGE</label>
                                    <input type="text" name="surcharge" class="form-control" id="surcharge"
                                        placeholder="SURCHARGE">
                                </div>
                            </div>
                        </div>
                        <!--row-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="commission">คอมมิชชั่น</label>
                                    <input type="text" name="commission" class="form-control" id="commission"
                                        placeholder="คอมมิชชั่น">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vat">VAT</label>
                                    <select name="vat" class="form-control" id="selectVal">
                                        <!-- <option value="" disabled selected hidden>In VAT</option> -->
                                        <option value="SOX" selected>SO (including vat)</option>
                                        <option value="SO">SO (excluding vat)</option>
                                        <option value="SOB">SOB</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--row-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purchaseOrder">เลขที่ใบสั่งซื้อ</label>
                                    <input type="text" name="purchaseOrder" class="form-control" id="purchaseOrder"
                                        placeholder="เลขที่ใบสั่งซื้อ" value="{{ $so }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="po">PO ลูกค้า</label>
                                    <input type="text" name="po" class="form-control" id="po"
                                        placeholder="PO ลูกค้า">
                                </div>
                            </div>
                        </div>

                        <!--row-->


                        <div class="row">
                            <div class="col-12 table-responsive">

                                <table class="table table-striped" id="dynamicAddRemove">

                                    <thead>
                                        <tr>
                                            <th>กำหนดส่งครั้งที่</th>
                                            <th>วันที่ </th>
                                            <th>จำนวน (หลา หรือเมตร) </th>
                                            <th> % *</th>
                                            <th>เพิ่ม/ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td style="text-align: center">1</td>
                                            <td>
                                                {{-- <input type="text" class="form-control datepicker" id="datepicker1"
                                                    name="dl[0][dt]"> --}}

                                                {{-- <input type="date" name="dl[0][dt]" id="datepicker1"
                                                    class="form-control" placeholder="dd-mm-yyyy"> --}}

                                                <input class="date form-control" type="text" name="dl[0][dt]"
                                                    id="datepicker1" autocomplete="off">
                                                {{-- // $date = date('d/m/Y', strtotime($orderEdit->createDate)) --}}


                                                <script type="text/javascript">
                                                    $('.date').datepicker({
                                                        format: 'dd/mm/yyyy',
                                                        orientation: "bottom",
                                                    });
                                                </script>
                                                {{-- <div class="form-group">
                                                    <div style="z-index: 9999;" class="input-group date"
                                                        id="reservationdate" data-target-input="nearest">
                                                        <input type="text" name="dl[0][dt]"
                                                            class="form-control datetimepicker-input"
                                                            data-target="#reservationdate" />
                                                        <div class="input-group-append" data-target="#reservationdate"
                                                            data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </td>
                                            <td><input type="text" name="dl[0][ordery]" placeholder="จำนวน (หลา)"
                                                    class="form-control" />
                                            </td>
                                            <td><input type="text" name="dl[0][orderp]" placeholder="%"
                                                    class="form-control" />
                                            </td>

                                            <td>
                                                <button type="button" name="add" id="dynamic-ar"
                                                    class="btn btn-outline-primary">เพิ่ม</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    
                                </table>

                            </div>
                        </div>
                        <!--row-->
                        <div class="form-group">
                            <label for="comment">หมายเหตุ</label>
                            <textarea type="" name="comment" class="form-control" id="comment" placeholder="หมายเหตุ"></textarea>
                            <label for="comment2">หมายเหตุการผลิต</label>
                            <textarea type="" name="comment2" class="form-control" id="comment2" placeholder="หมายเหตุการผลิต"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="payment">เงื่อนไขการชำระเงิน</label>
                            <textarea type="" name="payment" class="form-control" id="payment"
                                placeholder="ชำระเงินภายใน 15 วันหลังได้รับสินค้า" value="ชำระเงินภายใน 15 วันหลังได้รับสินค้า"></textarea>
                        </div>

                        <div class="line_btn">
                            <button name="submit" value="purchaseorder" class="btn b_order"><img
                                    src="<?php echo asset('assets/images/clipboard-list-solid.png'); ?>" width="15"> ใบสั่งขาย</button>
                            <button name="submit" value="production" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>"
                                    width="17"> ใบโครงสร้าง</button>
                        </div>
                    </form>
                </div>
                <!--box-from-->
            </div>
            <!--content-->

        </div><!-- /.content-wrapper -->
        <script>
            var input, filter, ul, li, a, i, txtValue;

            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

            var input, filter, ul, li, a, i, txtValue;

            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                li[i].style.display = "none";
            }

            //hidden supplier list
            sups = document.getElementsByName("supp");
            //alert(sups.length);
            for (i = 0; i < sups.length; i++) {
                li = sups[i].getElementsByTagName("li");
                //alert(li.length);

                for (j = 0; j < li.length; j++) {
                    li[j].style.display = "none";
                }


            }

            //hidden yarnType list
            yts = document.getElementsByName("yarnType");
            for (i = 0; i < yts.length; i++) {
                li = yts[i].getElementsByTagName("li");

                for (j = 0; j < li.length; j++) {
                    li[j].style.display = "none";
                }

            }
        </script>

        <script>
            function myFunction() {
                var input, filter, ul, li, a, i, txtValue;
                input = document.getElementById("myInput");
                filter = input.value.toUpperCase();
                ul = document.getElementById("myUL");
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
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        li[i].style.display = "";
                    } else {
                        li[i].style.display = "none";
                    }
                }
            }

            function supplierFunction(id, uln) {
                var input, filter, ul, li, a, i, txtValue;
                //alert(id);
                //alert(uln);

                input = document.getElementById(id);
                filter = input.value.toUpperCase();

                ul = document.getElementsByName("supp");
                //document.getElementById('sup1');
                li = ul[uln].getElementsByTagName("li");
                //alert(li.length);

                for (i = 0; i < li.length; i++) {
                    if (filter == "") {
                        for (j = 0; j < li.length; j++) {
                            li[j].style.display = "none";
                        }
                        break;
                    }

                    a = li[i].getElementsByTagName("a")[0];
                    txtValue = a.textContent || a.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        li[i].style.display = "";
                    } else {
                        li[i].style.display = "none";
                    }
                }
            }

            function yarntypeFunction(id, uln) {
                var input, filter, ul, li, a, i, txtValue;

                input = document.getElementById(id);
                filter = input.value.toUpperCase();
                filter = filter.replace(/\s/g, "");

                ul = document.getElementsByName("yarnType");
                li = ul[uln].getElementsByTagName("li");

                for (i = 0; i < li.length; i++) {
                    if (filter == "") {
                        for (j = 0; j < li.length; j++) {
                            li[j].style.display = "none";
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

            function setcustomerFunction(t) {
                document.getElementById("myInput").value = t;
                //ul = document.getElementById("myUL");
                //ul.style.display = "none";  
                ul = document.getElementById("myUL");
                li = ul.getElementsByTagName("li");
                for (i = 0; i < li.length; i++) {
                    li[i].style.display = "none";
                }

            }

            function setsupplierFunction(t, t1) {
                //Set supplier to textbox by id
                document.getElementById(t).value = t1;

                //hidden Supplier list
                sups = document.getElementsByName("supp");
                for (i = 0; i < sups.length; i++) {
                    li = sups[i].getElementsByTagName("li");

                    for (j = 0; j < li.length; j++) {
                        li[j].style.display = "none";
                    }
                }

            }

            function setyarntypeFunction(t, t1) {
                //Set supplier to textbox by id
                document.getElementById(t).value = t1;

                //hidden Supplier list
                yts = document.getElementsByName("yarnType");
                for (i = 0; i < yts.length; i++) {
                    li = yts[i].getElementsByTagName("li");

                    for (j = 0; j < li.length; j++) {
                        li[j].style.display = "none";
                    }
                }

            }
        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function() {
                var i = 0;
                var j = 1;
                var newId = 'datepicker';

                $("#dynamic-ar").click(function() {
                    ++i;
                    ++j;
                    newId = newId + j;

                    var row = '<tr>' +
                        '<td style="text-align: center">' + j + '</td>' +
                        '<td><input type="text" autocomplete="off" class="form-control datepicker" id="' +
                        newId + '" name="dl[' + i + '][dt]"></td>' +
                        '<td><input type="text" name="dl[' + i +
                        '][ordery]" placeholder="จำนวน (หลา)" class="form-control"></td>' +
                        '<td><input type="text" name="dl[' + i +
                        '][orderp]" placeholder="%" class="form-control"></td>' +
                        '<td><button type="button" class="btn btn-danger remove-input-field">ลบ</button></td>' +
                        '</tr>';

                    $('#dynamicAddRemove tbody').append(row);

                    $('#' + newId).datepicker({
                        // format: 'yyyy-mm-dd',
                        autoclose: true,
                        todayHighlight: true,
                        format: 'dd/mm/yyyy',
                        orientation: "bottom"
                    });
                });

                $(document).on('click', '.remove-input-field', function() {
                    $(this).closest('tr').remove();
                    --i;
                    --j;
                });

                // $('#' + newId).datepicker({
                //     format: 'dd/mm/yyyy',
                //     orientation: "bottom"
                // });
            });
        </script>



        <script>
            function yd(valNum) {
                var m = (valNum * 0.9144).toFixed(4);
                document.getElementById('orderSumM').value = m;
            }

            function ydToM(valNum) {
                var yd = (valNum / 0.9144).toFixed(4);
                document.getElementById('orderSumYard').value = yd;
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

        <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $("select#selectVal").change(function() {
                    let selectedItem = $(this).children("option:selected").val();
                    //alert("You have selected the name - " + selectedItem);
                    if (selectedItem == 'SO') {
                        document.getElementById("purchaseOrder").value = {{ Js::from($so) }};
                    }
                    if (selectedItem == 'SOX') {
                        document.getElementById("purchaseOrder").value = {{ Js::from($sox) }};
                    }
                    if (selectedItem == 'SOB') {
                        document.getElementById("purchaseOrder").value = {{ Js::from($sob) }};
                    }

                    //document.getElementById("purchaseOrder").value = selectedItem;
                });
            });
        </script>
    @endif


    <script>
        function subyarntype(yt) {
            // var text = "CP60 Compack";
            var text = yt;
            var strArray = text.split(" ");
            text = strArray[0] + ' ' + strArray[1];
            return text;
            // var lastNumPos = null;

            // for (var i = 0; i < text.length; i++) {
            //     var char = text.charAt(i);
            //     if (!isNaN(char)) {
            //         lastNumPos = i;
            //     }
            // }

            // if (lastNumPos !== null) {
            //     var subtext = text.substring(0, lastNumPos + 1);
            //     console.log(subtext); // output: "CP60"
            //     return subtext;
            // } else {
            //     console.log("No number found in text.");
            // }

        }

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
                s1 = subyarntype(yarnHType1.value);
                s2 = yarnHCount1.value;

                if (yarnHType2.value != "") {
                    fabricStruct = '(' + yarnHType1.value + ' + ' + yarnHType2.value + ')' + ' / ' + yarnHCount1.value;
                    s1 = '(' + subyarntype(yarnHType1.value) + ' + ' + subyarntype(yarnHType2.value) + ')';
                    s2 = yarnHCount1.value;

                }

                if (yarnWType1.value != "" && yarnWCount1.value != "") {
                    yw = subyarntype(yarnWType1.value) + ' / ' + subyarntype(yarnWCount1.value);
                    s1 = s1 + ' * ';
                    s2 = yarnHCount1.value;

                    if (yarnWType2.value != "" || yarnWType3.value != "" || yarnWType4.value != "") {
                        yw = '(' + subyarntype(yarnWType1.value);
                        if (yarnWType2.value != "") {
                            yw = yw + ' + ' + subyarntype(yarnWType2.value);
                        }
                        if (yarnWType3.value != "") {
                            yw = yw + ' + ' + subyarntype(yarnWType3.value);
                        }
                        if (yarnWType4.value != "") {
                            yw = yw + ' + ' + subyarntype(yarnWType4.value);
                        }
                        s1 = s1 + yw + ' )';
                        s2 = s2 + ' * ' + yarnWCount1.value;

                        yw = yw + ') / ' + yarnWCount1.value;
                    } else {
                        s1 = s1 + subyarntype(yarnWType1.value);
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
    {{-- <script>
        $(function() {
            $('#reservationdate1').datetimepicker();
            $('#reservationdate2').datetimepicker(); // <<<<< add this line 
        });
    </script> --}}


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            let counter = 2; // counter for unique id

            $('#add-datepicker').click(function() {
                const inputId = 'datepicker' + counter;
                const inputHtml = '<input type="text" "id="' + inputId + '">';

                $('#datepicker-container').append(inputHtml);

                $('#' + inputId).datepicker({
                    dateFormat: 'dd/mm/yy'
                });

                counter++;
            });

            $('#datepicker1').datepicker({
                dateFormat: 'dd/mm/yy'
            });
        });
    </script>
    <script type="text/javascript">
        $('.date').datepicker({
            format: 'dd/mm/yyyy',
            orientation: "bottom",
        });
    </script>


@endsection
