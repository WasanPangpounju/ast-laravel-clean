@extends('layouts.astmanufacturing')

@section('content')
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
                    <li class="breadcrumb-item active">ใบสั่งผลิต</li>
                </ol>
            </div>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ใบสั่งผลิต</h1>
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
                    <li class="breadcrumb-item active">ใบสั่งผลิต</li>
                </ol>
            </div>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> สั่งผลิต</h1>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <div class="content">
                <div class="box-from">
                    <h2 class="title"><i class="fa fa-caret-right"></i> ออกใบสั่งผลิต</h2>
                    <div class="date_order"><strong>สร้างเมื่อ
                        </strong>{{ date('l, j F Y H:i A', strtotime($productionEdit[0]->created_at)) }}<span><strong></strong></span>
                    </div>

                    <form onsubmit="createFabricStructure()" method="post"
                        action="{{ route('order.update', $productionEdit[0]->id) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="refId" name="refId" value="{{ $productionEdit[0]->refId }}">

                        <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">

                        <input type="hidden" id="s1" name="s1" value="">
                        <input type="hidden" id="s2" name="s2" value="">

                        <div class="row">
                            <div class="col-md-6">
                                <label>วันที่ </label>
                                <div class="form-group">
                                    <div class="input-group">
                                        {{-- <input type="date" name="createDate" id="startDate"
                                            value="{{ $flipped_date = date('Y-m-d', strtotime(str_replace('/', '-', $productionEdit[0]->createDate))) }}"
                                            class="form-control" date-format="d/m/Y"> --}}
                                        {{-- yyyy-mm-dd to dd/mm/yyyy --}}
                                        {{-- {{ $formatted_date = date('d/m/Y', strtotime($productionEdit[0]->createDate)) }}  --}}
                                        {{-- {{-- dd/mm/yyyy to yyyy-mm-dd --}}
                                        {{-- {{ $flipped_date = date('Y-m-d', strtotime(str_replace('/', '-', $formatted_date))) }} --}}

                                        <input class="date form-control" type="text" name="createDate"
                                            value="{{ $flipped_date = date('d/m/Y', strtotime(str_replace('/', '-', $productionEdit[0]->createDate))) }}"
                                            autocomplete="off">
                                        {{-- $date = date('d/m/Y', strtotime($orderEdit->createDate)) --}}

                                        <script type="text/javascript">
                                            $('.date').datepicker({
                                                format: 'dd/mm/yyyy',
                                                orientation: "bottom",
                                            });
                                        </script>
                                    </div>
                                </div>

                            </div>
                        </div><!-- row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="myInput">ชื่อลูกค้า *</label>
                                    <input type="text" name="customerName" class="form-control" id="myInput"
                                        onkeyup="myFunction()" placeholder="ชื่อลูกค้า"
                                        value="{{ $productionEdit[0]->customerName }}" required>

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
                                        placeholder="ชื่อผู้ประสานงาน" value="{{ $productionEdit[0]->deadline }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fabricId">รหัสผ้า *</label>
                                    <input type="text" name="fabricId" class="form-control" id="fabricId"
                                        placeholder="รหัสผ้า" value="{{ $productionEdit[0]->fabricId }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fabricPattern">ลายผ้า *</label>
                                    <input type="text" name="fabricPattern" class="form-control" id="fabricPattern"
                                        placeholder="ลายผ้า" value="{{ $productionEdit[0]->fabricPattern }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="fabricStructure">โครงสร้างผ้า *</label>
                                    <input type="text" name="fabricStructure" class="form-control"
                                        id="fabricStructure1" placeholder="โครงสร้างผ้า"
                                        value="{{ $productionEdit[0]->fabricStructure }}" required>
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
                                        placeholder="จำนวนด้ายยืน (เส้น)"
                                        value="{{ $fabricpaymentEdit[0]->yarn_h_count }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fabric_w">หน้าผ้า (นิ้ว) * </label>
                                    <input type="number" name="fabric_w" class="form-control" id="fabric_w"
                                        placeholder="หน้าผ้า (นิ้ว)" value="{{ $fabricpaymentEdit[0]->fabric_w }}"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnHType1">ชนิดด้ายยืน 1 *</label>
                                    <input type="text" name="yarnHType1" onkeyup="yarntypeFunction('yarnHType1' , 0)"
                                        class="form-control" id="yarnHType1" placeholder="ชนิดด้ายยืน 1"
                                        value="{{ $fabricStructureEdit[0]->yarnHType1 }}" required>

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
                                        class="form-control" id="subNameH1" placeholder="บริษัท"
                                        value="{{ $fabricStructureEdit[0]->subNameH1 }}" required>

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
                                        placeholder="" value="{{ $fabricStructureEdit[0]->yarnHCount1 }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnHRatio1">อัตราส่วน </label>
                                    <input type="text" name="yarnHRatio1" class="form-control" id="yarnHRatio1"
                                        placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnHRatio1 != 'no data' ? $fabricStructureEdit[0]->yarnHRatio1 : null }}">
                                </div>
                            </div>
                        </div>
                        <!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnHType2">ชนิดด้ายยืน 2</label>
                                    <input type="text" name="yarnHType2" onkeyup="yarntypeFunction('yarnHType2' , 1)"
                                        class="form-control" id="yarnHType2" placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnHType2 != 'no data' ? $fabricStructureEdit[0]->yarnHType2 : null }}">

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
                                        class="form-control" id="subNameH2" placeholder="บริษัท"
                                        value="{{ $fabricStructureEdit[0]->subNameH2 != 'no data' ? $fabricStructureEdit[0]->subNameH2 : null }}">

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
                                        placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnHCount2 != 'no data' ? $fabricStructureEdit[0]->yarnHCount2 : null }}">
                                </div>
                            </div>
                        </div>
                        <!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWType1">ชนิดด้ายพุ่ง 1 * </label>
                                    <input type="text" name="yarnWType1" onkeyup="yarntypeFunction('yarnWType1' , 2)"
                                        class="form-control" id="yarnWType1" placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnWType1 != 'no data' ? $fabricStructureEdit[0]->yarnWType1 : null }}"
                                        required>

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
                                        class="form-control" id="subNameW1" placeholder="บริษัท"
                                        value="{{ $fabricStructureEdit[0]->subNameW1 != 'no data' ? $fabricStructureEdit[0]->subNameW1 : null }}"
                                        required>

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
                                        placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnWCount1 != 'no data' ? $fabricStructureEdit[0]->yarnWCount1 : null }}"
                                        required>
                                </div>
                            </div>
                        </div>
                        <!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWType2">ชนิดด้ายพุ่ง 2</label>
                                    <input type="text" name="yarnWType2" onkeyup="yarntypeFunction('yarnWType2' , 3)"
                                        class="form-control" id="yarnWType2" placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnWType2 != 'no data' ? $fabricStructureEdit[0]->yarnWType2 : null }}">

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
                                        class="form-control" id="subNameW2" placeholder="บริษัท"
                                        value="{{ $fabricStructureEdit[0]->subNameW2 != 'no data' ? $fabricStructureEdit[0]->subNameW2 : null }}">

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
                                        placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnWCount2 != 'no data' ? $fabricStructureEdit[0]->yarnWCount2 : null }}">
                                </div>
                            </div>
                        </div>
                        <!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWType3">ชนิดด้ายพุ่ง 3</label>
                                    <input type="text" name="yarnWType3" onkeyup="yarntypeFunction('yarnWType3' , 4)"
                                        class="form-control" id="yarnWType3" placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnWType3 != 'no data' ? $fabricStructureEdit[0]->yarnWType3 : null }}">

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
                                        class="form-control" id="subNameW3" placeholder="บริษัท"
                                        value="{{ $fabricStructureEdit[0]->subNameW3 != 'no data' ? $fabricStructureEdit[0]->subNameW3 : null }}">

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
                                        placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnWCount3 != 'no data' ? $fabricStructureEdit[0]->yarnWCount3 : null }}">
                                </div>
                            </div>
                        </div>
                        <!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yarnWType4">ชนิดด้ายพุ่ง 4</label>
                                    <input type="text" name="yarnWType4" onkeyup="yarntypeFunction('yarnWType4' , 5)"
                                        class="form-control" id="yarnWType4" placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnWType4 != 'no data' ? $fabricStructureEdit[0]->yarnWType4 : null }}">

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
                                        class="form-control" id="subNameW4" placeholder="บริษัท"
                                        value="{{ $fabricStructureEdit[0]->subNameW4 != 'no data' ? $fabricStructureEdit[0]->subNameW4 : null }}">

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
                                        placeholder=""
                                        value="{{ $fabricStructureEdit[0]->yarnWCount4 != 'no data' ? $fabricStructureEdit[0]->yarnWCount4 : null }}">
                                </div>
                            </div>
                        </div>
                        <!--row-->



                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phewNumber">เบอร์หวี * </label>
                                    <input type="text" name="phewNumber" class="form-control" id="phewNumber"
                                        placeholder="เบอร์หวี"
                                        value="{{ $fabricpaymentEdit[0]->phewNumber != 'no data' ? $fabricpaymentEdit[0]->phewNumber : null }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phewW">หน้าหวี (นิ้ว) * </label>
                                    <input type="text" name="phewW" class="form-control" id="phewW"
                                        placeholder="หน้าหวี (นิ้ว)"
                                        value="{{ $fabricpaymentEdit[0]->phewW != 'no data' ? $fabricpaymentEdit[0]->phewW : null }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fabriccomment">การลงผ้า </label>
                                    <input type="text" name="fabriccomment" class="form-control" id="fabriccomment"
                                        placeholder="การลงผ้า"
                                        value="{{ $fabricpaymentEdit[0]->vat != 'no data' ? $fabricpaymentEdit[0]->vat : null }}">
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
                                                placeholder="หลา"
                                                value="{{ $productionEdit[0]->orderSumYard != null ? $productionEdit[0]->orderSumYard : null }}"
                                                required>
                                        </div>
                                        <div class="col-md-1">
                                            หรือ
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="orderSumM" oninput="ydToM(this.value)"
                                                onchange="ydToM(this.value )" class="form-control" id="orderSumM"
                                                placeholder="เมตร"
                                                value="{{ $productionEdit[0]->orderSumM != null ? $productionEdit[0]->orderSumM : null }}"
                                                required>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="typrtag" class="form-control" id="typetag">
                                                @if ($fabricStructureEdit[0]->yarnWRatio3 == 'y')
                                                    <option value="y" selected>หลา</option>
                                                @else
                                                    <option value="y">หลา</option>
                                                @endif
                                                @if ($fabricStructureEdit[0]->yarnWRatio3 == 'm')
                                                    <option value="m" selected>เมตร</option>
                                                @else
                                                    <option value="m">เมตร</option>
                                                @endif
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
                                                placeholder="%"
                                                value="{{ $productionEdit[0]->fabricSPY != 'no data' ? $productionEdit[0]->fabricSPY : null }}">
                                        </div>
                                        <div class="col-md-1">หรือ</div>
                                        <div class="col-md-6">
                                            <input type="text" name="fabricSpP" oninput="ydToP(this.value)"
                                                onchange="ydToP(this.value )" class="form-control" id="fabricSpy"
                                                placeholder="หลา"
                                                value="{{ $productionEdit[0]->fabricSPY != 0 ? ($productionEdit[0]->fabricSPY / 100) * $productionEdit[0]->orderSumYard + $productionEdit[0]->orderSumYard : 0 }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--row-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="typemachine">No.</label>
                                    <input type="text" name="no" class="form-control" id="no"
                                        placeholder="" value="{{ $productionEdit[0]->no }}">
                                </div>
                            </div>
                        </div>
                        <!--row-->
                        <!--row-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="typemachine">ชนิดเครื่องทอ</label>
                                    <input type="text" name="typemachine" class="form-control" id="typemachine"
                                        placeholder="" value="{{ $productionEdit[0]->typemachine }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="machinenumber">เบอร์เครื่อง </label>
                                    <input type="text" name="machinenumber" class="form-control" id="machinenumber"
                                        placeholder="" value="{{ $productionEdit[0]->machinenumber }}">
                                </div>
                            </div>
                        </div>
                        <!--row-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purchaseOrder">เลขที่ใบสั่งซื้อ</label>
                                    <input type="text" name="purchaseOrder" class="form-control" id="purchaseOrder"
                                        placeholder="เลขที่ใบสั่งซื้อ" value="{{ $productionEdit[0]->purchaseOrder }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="po">PO ลูกค้า</label>
                                    <input type="text" name="po" class="form-control" id="po"
                                        placeholder="PO ลูกค้า"
                                        value="{{ $productionEdit[0]->po != 'no data' ? $productionEdit[0]->po : null }}">
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
                                            <th>วันที่ *</th>
                                            <th>จำนวน (หลา หรือเมตร) </th>
                                            <th> % *</th>
                                            <th>เพิ่ม/ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($deadlineEdit) <= 0)
                                            <tr>
                                                <td id="a1" style="text-align: center">1</td>
                                                <td>
                                                    {{-- <input type="text" class="form-control datepicker" id="datepicker1"
                                                    name="dl[0][dt]"> --}}
                                                    {{-- <input type="date" name="dl[0][dt]" id="datepicker1"
                                                        class="form-control" placeholder="dd-mm-yyyy"> --}}

                                                    <input class="date form-control" type="text" name="dl[0][dt]"
                                                        id="datepicker1" autocomplete="off">
                                                    {{-- $date = date('d/m/Y', strtotime($orderEdit->createDate)) --}}

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
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i>
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
                            @else
                                <?php $deadloop = 0; ?>

                                @foreach ($deadlineEdit as $showDeadline)
                                    @if ($deadloop < 1)
                                        <tr>
                                            <td id="a1" style="text-align: center">{{ $showDeadline->round }}</td>
                                            <td>
                                                {{-- <input type="text" class="form-control datepicker" id="datepicker1"
                                                    name="dl[0][dt]"> --}}
                                                {{-- <input type="date" name="dl[0][dt]" id="datepicker1"
                                                    class="form-control" placeholder="dd-mm-yyyy"
                                                    value="{{ $flipped_date = date('Y-m-d', strtotime($showDeadline->dt)) }}"> --}}

                                                <input class="date form-control" type="text" name="dl[0][dt]"
                                                    id="datepicker1" autocomplete="off"
                                                    value="{{ $flipped_date = date('d/m/Y', strtotime($showDeadline->dt)) }}">
                                                {{-- $date = date('d/m/Y', strtotime($orderEdit->createDate)) --}}

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
                                                    class="form-control" value="{{ $showDeadline->ordery }}" />
                                            </td>
                                            <td><input type="text" name="dl[0][orderp]" placeholder="%"
                                                    class="form-control" value="{{ $showDeadline->orderp }}" />
                                            </td>

                                            <td>
                                                <button type="button" name="add" id="dynamic-ar"
                                                    class="btn btn-outline-primary">เพิ่ม</button>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td id="a1" style="text-align: center">{{ $showDeadline->round }}</td>
                                            <td>
                                                {{-- <input type="text" class="form-control datepicker" id="datepicker1"
                                                    name="dl[0][dt]"> --}}
                                                {{-- <input type="date" name="dl[0][dt]" id="datepicker1"
                                                    class="form-control" placeholder="dd-mm-yyyy"
                                                    value="{{ $flipped_date = date('Y-m-d', strtotime($showDeadline->dt)) }}"> --}}

                                                <input class="date form-control" type="text" name="dl[0][dt]"
                                                    id="datepicker1" autocomplete="off"
                                                    value="{{ $flipped_date = date('d/m/Y', strtotime($showDeadline->dt)) }}">
                                                {{-- $date = date('d/m/Y', strtotime($orderEdit->createDate)) --}}

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
                                                    class="form-control" value="{{ $showDeadline->ordery }}" />
                                            </td>
                                            <td><input type="text" name="dl[0][orderp]" placeholder="%"
                                                    class="form-control" value="{{ $showDeadline->orderp }}" />
                                            </td>

                                            <td>
                                                <button type="button"
                                                    class="btn btn-danger remove-input-field">ลบ</button>
                                            </td>
                                        </tr>
                                    @endif
                                    <?php $deadloop += 1; ?>
                                @endforeach
    @endif
    </tbody>
    </table>

    </div>
    </div>
    <!--row-->

    <div class="form-group">
        <label for="comment">หมายเหตุ</label>
        <textarea type="" name="comment" class="form-control" id="comment" placeholder="หมายเหตุ">{{ $productionEdit[0]->comment != 'no data' ? $productionEdit[0]->comment : null }}</textarea>
    </div>
    <div class="form-group">
        <label for="comment2">หมายเหตุการผลิต</label>
        <textarea type="" name="comment2" class="form-control" id="comment2" placeholder="หมายเหตุการผลิต">{{ $productionEdit[0]->comment2 != null ? $productionEdit[0]->comment2 : null }}</textarea>
    </div>
    <div class="line_btn">
        <button name="submit" value="purchaseorder" class="btn b_order"><img src="<?php echo asset('assets/images/clipboard-list-solid.png'); ?>"
                width="15"><a href="{{ route('order.index') }}">กลับ</a></button>
        <button name="submit" value="updateproduction" class="btn b_save"><img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>"
                width="17">
            บันทึก</button>
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
    <script type="text/javascript">
        var i = 0;
        var j = 1;
        var newId = 'datepicker';
        $("#dynamic-ar").click(function() {
            var a1Elements = document.querySelectorAll("#a1"); // Get all elements with ID "a1"
            var lastA1Element = a1Elements[a1Elements.length - 1]; // Get the last element with ID "a1"
            var lastA1Value = lastA1Element.textContent; // Get the value of the last element with ID "a1"
            i = lastA1Value;
            j = lastA1Value;

            ++i;
            ++j;
            newId = newId + j;
            // $("#dynamicAddRemove").append('<tr><td style="text-align: center">' + j +
            //     '</td><td><input type="text" class="form-control datepicker" id="' + newId + '" name="dl[' +
            //     i +
            //     '][dt]"></td><td><input type="text" name="dl[' +
            //     i +
            //     '][ordery]" placeholder="จำนวน (หลา)" class="form-control" /></td><td><input type="text" name="dl[' +
            //     i +
            //     '][orderp]" placeholder="%" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-input-field">ลบ</button></td></tr>'
            // );
            $("#dynamicAddRemove").append('<tr><td id="a1" style="text-align: center">' + j +
                '</td><td><input type="date" class="form-control" id="' + newId + '" name="dl[' +
                i +
                '][dt]" placeholder="dd-mm-yyyy"></td><td><input type="text" name="dl[' +
                i +
                '][ordery]" placeholder="จำนวน (หลา)" class="form-control" /></td><td><input type="text" name="dl[' +
                i +
                '][orderp]" placeholder="%" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-input-field">ลบ</button></td></tr>'
            );

            $('#' + newId).datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight: true
            });

        });

        // Initialize the new datepicker
        //         $('#' + newId).datepicker({
        //     format: 'yyyy-mm-dd',
        //     autoclose: true,
        //     todayHighlight: true
        // });



        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();
            --i;
            --j;
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
        //set full fabric structure
        document.addEventListener('DOMContentLoaded', function() {
            createFabricStructure()
        });

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
    <script>
        $(function() {
            $('#reservationdate1').datetimepicker();
            $('#reservationdate2').datetimepicker(); // <<<<< add this line 
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            let counter = 2; // counter for unique id

            $('#add-datepicker').click(function() {
                const inputId = 'datepicker' + counter;
                const inputHtml = '<input type="text" id="' + inputId + '">';

                $('#datepicker-container').append(inputHtml);

                $('#' + inputId).datepicker({
                    dateFormat: 'yy-mm-dd'
                });

                counter++;
            });

            $('#datepicker1').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>


@endsection
