@extends('layouts.astmanufacturing')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
            <li class="breadcrumb-item"><a href="#">ระบบซื้อขาย</a></li>
            <li class="breadcrumb-item active">ข้อมูลซัพพลายเออร์</li>
        </ol>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 Header">
                    <div class="col">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i>ข้อมูลซัพพลายเออร์</h1>
                    </div><!-- /.col -->
                    <div class="col">
                        <div style="text-align: right;">
                            <button class="btn_add"><a href="{{ route('supplier.create') }}"> <i
                                        class="nav-icon fas fa fa-plus-circle"></i>เพิ่มซัพพลายเออร์</a></button>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <div class="box_search">
                    <div class="container-fluid">
                        <h2 class="text-center display-4">ค้นหาข้อมูลซัพพลายเออร์</h2>
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <form action="{{ route('supplier.index') }}">
                                    @csrf

                                    <input type="text" id="myInput" onkeyup="myFunction()"
                                        class="form-control form-control-lg" placeholder="ค้นหาซัพพลายเออร์">

                                    <?php $suppliershow = 'test'; ?>
                                    <ul id="myUL">
                                        @foreach ($supplier as $supplierid)
                                            <li><a
                                                    href="{{ route('supplier.index', ['id' => $supplierid->id]) }}">{{ $supplierid->name }}</a>
                                            </li>
                                            @if (request()->id == $supplierid->id)
                                                <?php
                                                $suppliershow = $supplierid;
                                                ?>
                                            @endif
                                        @endforeach
                                    </ul>
                                    <div class="line_btn_search">
                                        <button type="submit" class="btn_search"><i class="fa fa-search"></i>
                                            &nbsp;ค้นหา</button>
                                    </div>
                                </form>

                                <!-- <form action="">
                    <div class="line_btn">
            <button type="submit" class="btn btn-etc"> <i class="fas fa-pencil-alt"></i> แก้ไขข้อมูลซัพพลายเออร์</button>
            <button  type="submit" class="btn btn-etc"> <i class="fas fa-folder"></i> ดูข้อมูลซัพพลายเออร์ทั้งหมด</button>
            </div>
                   </form> -->


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--box-from-->
        </div>

        <!--content-->
        @if (request()->id != '')
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <h2 class="title-result"><i class="nav-icon fa fa-file"></i> ผลการค้นหา</h2><i class=""></i>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">ชื่อลูกค้า/บริษัท</p>
                    </div>
                    <div class="col-md-7">
                        <p>{{ $suppliershow->name }}</p>
                    </div>
                    <div class="col-md-3 text-right">
                        <form method="POST" action="{{ route('supplier.destroy', $suppliershow->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="button"
                                onclick="if(confirm('ต้องการลบหรือไม่?')) { this.form.submit(); }"
                                style="">ลบ</button>
                            <button class="btn b_order" type="button"><a
                                    href="{{ route('supplier.edit', $suppliershow->id) }}">แก้ไข</a></button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">เลขผู้เสียภาษี</p>
                    </div>
                    <div class="col-md-8">
                        <p>{{ $suppliershow->tax }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">ที่อยู่</p>
                    </div>
                    <div class="col-md-8">
                        <p>{{ $suppliershow->address }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">เบอร์โทรศัพท์</p>
                    </div>
                    <div class="col-md-8">
                        <p>{{ $suppliershow->tel }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">อีเมล์ </p>
                    </div>
                    <div class="col-md-8">
                        <p>{{ $suppliershow->email }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p class="bold">ประเภทสมาชิก </p>
                    </div>
                    <div class="col-md-8">
                        <p>{{ $suppliershow->type }}</p>
                    </div>
                </div>

                <?php
                $cuscoorData = App\Http\Controllers\SupplierController::coordinatorData($suppliershow->tax);
                ?>


                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ผู้ประสานงาน</th>
                                    <th>ตำแหน่ง </th>
                                    <th>เบอร์โทรศัพท์ </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($cuscoorData as $data) {
                                    print '
                                                                                                                    <tr>
                                                                                                                      <td>' .
                                        $data->name .
                                        '</td>
                                                                                                                      <td>' .
                                        $data->jobTitle .
                                        '</td>
                                                                                                                      <td>' .
                                        $data->tel .
                                        '</td>
                                                                                                                    </tr>
                                                                                                ';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="d-grid gap-2 col-1 mx-auto">
                        <button type="button" class="btn btn-primary center"><a
                                href="{{ route('supplier.index') }}">กลับหน้ารายการ</a></button>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!--content-->
        @else
            <div class="content">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ซัพพลายเออร์</th>
                                                <th>หมายเลขโทรศัพท์</th>
                                                <th>รายละเอียด</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($supplier as $supplierid)
                                                <tr>
                                                    <td>{{ $supplierid->name }}</td>
                                                    <td>{{ $supplierid->tel }}</td>
                                                    <td><a
                                                            href="{{ route('supplier.index', ['id' => $supplierid->id]) }}">ดูรายละเอียด</a>
                                                    </td>
                                                </tr>

                                                @if (request()->id == $supplierid->id)
                                                    <?php
                                                    $suppliershow = $supplierid;
                                                    ?>
                                                @endif
                                            @endforeach

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div><!-- /.content-wrapper -->

    <script>
        var input, filter, ul, li, a, i, txtValue;
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            li[i].style.display = "none";
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
    </script>

@endsection
