@extends('layouts.astmanufacturing')

@section('content')

    <head>
        {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" /> --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
            rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    </head>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="#">ระบบซื้อขาย</a></li>
                <li class="breadcrumb-item active">ตรวจสอบใบใบสั่งขาย</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 Header">
                    <div class="col">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ตรวจสอบรายการใบสั่งขาย</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <div class="">
                    <form method="post" action="{{ route('order.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="customerName">ชื่อบริษัท</label>
                                        <input type="text" name="customerName" onkeyup="supplierFunction('customerName')"
                                            class="form-control" id="customerName" placeholder="ชื่อบริษัท">
                                        <?php $suppliershow = 'test';
                                        $supplier = App\Http\Controllers\MaterialController::supData();
                                        ?>
                                        {{-- <ul id="supp">
                                        @foreach ($supplier as $sup)
                                            <li><a
                                                    href="javascript:setsupplierFunction('supp', '{{ $sup->name }}');">{{ $sup->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ชนิดด้าย</label>
                                    <input type="" class="form-control" id="" placeholder="ชนิดด้าย">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="imDate">วันที่</label>
                                    <input type="text" class="date form-control" name="imDate" autocomplete="off" />

                                    <script type="text/javascript">
                                        $(".date").datepicker({
                                            format: "dd/mm/yyyy",
                                            orientation: "bottom",
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">จำนวนด้ายยืน (เส้น)</label>
                                    <div class="col-sm-9">
                                        <input type="" class="form-control" id="inputEmail3"
                                            placeholder="จำนวนด้ายยืน (เส้น)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">จำนวนด้ายพุ่ง (เส้น)</label>
                                    <div class="col-sm-9">
                                        <input type="" class="form-control" id="inputEmail3"
                                            placeholder="จำนวนด้ายพุ่ง (เส้น)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="line_btn_search">
                            <button type="submit" name="submit" value="searchImport" class="btn_search"><i
                                    class="fa fa-search"></i> ค้นหา</button>
                        </div>
                    </form>
                    @if (isset($importorder) && count($importorder) > 0)
                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>วันที่ </th>
                                                <th>รายการ </th>
                                                <th>สถานะ </th>
                                                <th>แก้ไข / ลบ </th>
                                                <th>ใบใบสั่งขาย/สั่งผลิต</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($importorder as $order)
                                                <tr>
                                                    <td>{{ $date = date('d/m/Y', strtotime($order->createDate)) }}</td>
                                                    <td>{{ $order->customerName }} รหัสผ้า {{ $order->fabricId }}
                                                        {{ $order->orderSumYard }} หลา <a
                                                            href="{{ route('order.show', $order->id) }}">รายละเอียด</a></td>
                                                    <td>
                                                        <form method="POST"
                                                            action="{{ route('order.store', $order->id) }}">
                                                            @csrf
                                                            <p>{{ $order->status }}</p>
                                                            <input type="hidden" name="id"
                                                                value="{{ $order->id }}">
                                                            <input type="hidden" name="status" value="อนุมัติให้ผลิต">
                                                            <br><button name="submit" class="btn btn-secondary"
                                                                value="updateStatus">อนุมัติให้ผลิต</button>
                                                        </form>
                                                    </td>
                                                    <td>

                                                        <form method="POST"
                                                            action="{{ route('order.destroy', $order->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn b_order" type="button"
                                                                style="margin-bottom: 1.2em">
                                                                <a
                                                                    href="{{ route('order.edit', $order->id) }}">แก้ไข</a></button>
                                                            <br>
                                                            {{-- <button><a
                                                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</a></button> --}}
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form method="post" action="{{ route('order.store') }}">
                                                            @csrf
                                                            <input type="hidden" id="" name="id"
                                                                value="{{ $order->id }}">

                                                            <button name="submit" class="btn btn-secondary"
                                                                value="purchaseorderdetail">ใบสั่งขาย</button>
                                                            <br>
                                                            <br>
                                                            <button name="submit" class="btn btn-secondary"
                                                                value="productionderdetail">ใบโครงสร้าง</button>
                                                            {{-- <a href="">สั่งผลิต</a> --}}
                                                        </form>

                                                        <!-- <a href="">ใบใบสั่งขาย</a>  -->


                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--row-->
                        </div>
                        <!--List_table-->
                    @elseif(isset($importmaterial) && count($importmaterial) < 1)
                        <p>ไม่พบผลการค้นหา</p>
                    @else
                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>วันที่ </th>
                                                <th>รายการ </th>
                                                <th>สถานะ </th>
                                                <th>แก้ไข / ลบ </th>
                                                <th>ใบใบสั่งขาย/สั่งผลิต</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orderlist as $order)
                                                <tr>
                                                    <td>{{ $date = date('d/m/Y', strtotime($order->createDate)) }}</td>
                                                    <td>{{ $order->customerName }} รหัสผ้า {{ $order->fabricId }}
                                                        {{ $order->orderSumYard }} หลา <a
                                                            href="{{ route('order.show', $order->id) }}">รายละเอียด</a>
                                                    </td>
                                                    <td>
                                                        <form method="POST"
                                                            action="{{ route('order.store', $order->id) }}">
                                                            @csrf
                                                            <p>{{ $order->status }}</p>
                                                            <input type="hidden" name="id"
                                                                value="{{ $order->id }}">
                                                            <input type="hidden" name="status" value="อนุมัติให้ผลิต">
                                                            <br><button name="submit" class="btn btn-secondary"
                                                                value="updateStatus">อนุมัติให้ผลิต</button>
                                                        </form>
                                                    </td>
                                                    <td>

                                                        <form method="POST"
                                                            action="{{ route('order.destroy', $order->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn b_order" type="button"
                                                                style="margin-bottom: 1.2em">
                                                                <a
                                                                    href="{{ route('order.edit', $order->id) }}">แก้ไข</a></button>
                                                            <br>
                                                            {{-- <button><a
                                                            onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</a></button> --}}
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }">ลบ</button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form method="post" action="{{ route('order.store') }}">
                                                            @csrf
                                                            <input type="hidden" id="" name="id"
                                                                value="{{ $order->id }}">

                                                            <button name="submit" class="btn btn-secondary"
                                                                value="purchaseorderdetail">ใบสั่งขาย</button>
                                                            <br>
                                                            <br>
                                                            <button name="submit" class="btn btn-secondary"
                                                                value="productionderdetail">ใบโครงสร้าง</button>
                                                            {{-- <a href="">สั่งผลิต</a> --}}
                                                        </form>

                                                        <!-- <a href="">ใบใบสั่งขาย</a>  -->


                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--row-->
                        </div>
                        <!--List_table-->
                    @endif
                </div><!-- inner_content -->
            </div>
            <!--box-from-->
        </div>
        <!--content-->
    </div><!-- /.content-wrapper -->
@endsection
