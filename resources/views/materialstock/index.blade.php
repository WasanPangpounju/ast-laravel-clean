@extends('layouts.astmanufacturing')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
                <li class="breadcrumb-item active">สต๊อกวัตถุดิบคงเหลือ</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 Header">
                    <div class="col">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> สต๊อกวัตถุดิบ</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <div class="">

                    <form method="post" action="{{ route('materialstock.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="yarntype">ชนิดด้าย</label>
                                    <select name="yarnType" class="form-control">
                                        <option disabled selected value="">เลือกชนิดได้</option>
                                        @foreach ($stockYarns as $key => $stockYarn)
                                            <option value="{{ $key }}">{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="supplier">บริษัท</label>

                                    <select name="supplier" class="form-control">
                                        <option disabled selected value="">เลือกบริษัท</option>
                                        @foreach ($supplier as $sup)
                                            <option value="{{ $sup->name }}">{{ $sup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="submit" name="submit" value="search" id="search" class="btn btn-etc"
                                        style="margin-top:2em;"> <i class="fas fa-folder"></i> ค้นหา</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if (isset($sum_import_yarnType) && isset($sum_withdraw_yarnType))
                        <!--display data by search yarnType -->


                        <h2>{{ $yarnType }} คงเหลือในสต๊อก </h2>
<p>รายการล่าสุดเมื่อ {{ $stockLatest}}</p>

                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-a">
                                        <thead style="position: sticky;top: 0">
                                            <tr>
                                                <th rowspan="2">บริษัท</th>
                                                <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                                <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                                <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                            </tr>
                                            <tr>
                                                <th>ปอนด์</th>
                                                <th>กิโลกรัม</th>
                                                <th>ปอนด์</th>
                                                <th>กิโลกรัม</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <!-- //loop display supplier import yarn data -->
                                            @foreach ($sum_import_yarnType as $item)
                                                {{-- {{ $item->supplierName }} --}}
                                                <?php $check = 0; ?>
                                                <!-- //Check withdraw material-->
                                                @foreach ($sum_withdraw_yarnType as $item1)
                                                    {{-- *{{ $item1->supplierName }} --}}
                                                    
                                                    @if ($item->supplierName === $item1->supplierName)
                                                        <?php
                                                        $check = 1;
                                                        $tmp_spool = $item->sumspool - $item1->sumspool;
                                                        $tmp_sumweight_p_net = number_format($tmp_spool * $item->average_p, 4);
                                                        $tmp_sumweight_kg_net = number_format($tmp_spool * $item->average_kg, 4);
                                                        ?>
                                                        @foreach ($sum_without_yarnType as $item2)
                                                            @if ($item->supplierName === $item2->supplierName)
                                                                <?php
                                                                $tmp_spool = $tmp_spool - $item2->sumspool;
                                                                $tmp_sumweight_p_net = number_format($tmp_spool * $item->average_p, 4);
                                                                $tmp_sumweight_kg_net = number_format($tmp_spool * $item->average_kg, 4);
                                                                ?>
                                                            @endif
                                                        @endforeach
                                                        <tr>
                                                            <td>{{ $item->supplierName }}</td>
                                                            <!--ชื่อบริษัท-->
                                                            <td>{{ $tmp_spool }}</td>
                                                            <!--จำนวนด้ายรวม-->
                                                            <td>{{ $tmp_sumweight_p_net }} </td>
                                                            <!--น้ำหนักรวม p -->
                                                            <td>{{ $tmp_sumweight_kg_net }}</td>
                                                            <!--น้ำหนักรวม kg-->
                                                            <td>{{ number_format($item->average_p, 4) }} </td>
                                                            <!--น้ำหนักรวมเฉลี่ย p -->
                                                            <td>{{ number_format($item->average_kg, 4) }} </td>
                                                            <!--น้ำหนักรวมเฉลี่ย kg-->
                                                        </tr>
                                                    @break
                                                @endif
                                            @endforeach
                                            @if ($check != 1)
                                                <tr>
                                                    <td>{{ $item->supplierName }}</td>
                                                    <!--ชื่อบริษัท-->
                                                    <td>{{ $item->sumspool }}</td>
                                                    <!--จำนวนด้ายรวม-->
                                                    <td>{{ number_format($item->sumweight_p_net, 4) }} </td>
                                                    <!--น้ำหนักรวม p -->
                                                    <td>{{ number_format($item->sumweight_kg_net, 4) }}</td>
                                                    <!--น้ำหนักรวม kg-->
                                                    <td>{{ number_format($item->average_p, 4) }} </td>
                                                    <!--น้ำหนักรวมเฉลี่ย p -->
                                                    <td>{{ number_format($item->average_kg, 4) }} </td>
                                                    <!--น้ำหนักรวมเฉลี่ย kg-->
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--row-->
                    </div>


                    <h2>นำเข้าวัตถุดิบ {{ $yarnType }}</h2>

                    <div class="List_table">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-a">
                                    <thead style="position: sticky;top: 0">
                                        <tr>
                                            <th rowspan="2">บริษัท</th>
                                            <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                            <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                            <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                        </tr>
                                        <tr>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- //loop display supplier import yarn data -->
                                        @foreach ($sum_import_yarnType as $item)
                                            <tr>
                                                <td>{{ $item->supplierName }}</td>
                                                <!--ชื่อบริษัท-->
                                                <td>{{ $item->sumspool }}</td>
                                                <!--จำนวนด้ายรวม-->
                                                <td>{{ number_format($item->sumweight_p_net, 4) }} </td>
                                                <!--น้ำหนักรวม p -->
                                                <td>{{ number_format($item->sumweight_kg_net, 4) }}</td>
                                                <!--น้ำหนักรวม kg-->
                                                <td>{{ number_format($item->average_p, 4) }} </td>
                                                <!--น้ำหนักรวมเฉลี่ย p -->
                                                <td>{{ number_format($item->average_kg, 4) }} </td>
                                                <!--น้ำหนักรวมเฉลี่ย kg-->
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--row-->
                    </div>

                    <h2>เบิกวัตถุดิบ {{ $yarnType }} ใช้ภายใน</h2>

                    <div class="List_table">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-a">
                                    <thead style="position: sticky;top: 0">
                                        <tr>
                                            <th rowspan="2">บริษัท</th>
                                            <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                            <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                            <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                        </tr>
                                        <tr>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- //loop display supplier withdraw yarn data -->
                                        @foreach ($sum_withdraw_yarnType as $item)
                                            <tr>
                                                <td>{{ $item->supplierName }}</td>
                                                <!--ชื่อบริษัท-->
                                                <td>{{ $item->sumspool }}</td>
                                                <!--จำนวนด้ายรวม-->
                                                <td>{{ number_format($item->sumweight_p_net, 4) }} </td>
                                                <!--น้ำหนักรวม p -->
                                                <td>{{ number_format($item->sumweight_kg_net, 4) }}</td>
                                                <!--น้ำหนักรวม kg-->
                                                <td>{{ number_format($item->average_p, 4) }} </td>
                                                <!--น้ำหนักรวมเฉลี่ย p -->
                                                <td>{{ number_format($item->average_kg, 4) }} </td>
                                                <!--น้ำหนักรวมเฉลี่ย kg-->
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--row-->
                    </div>

                    <h2>เบิกวัตถุดิบ {{ $yarnType }} ใช้ภายนอก</h2>

                    <div class="List_table">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-a">
                                    <thead style="position: sticky;top: 0">
                                        <tr>
                                            <th rowspan="2">บริษัท</th>
                                            <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                            <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                            <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                        </tr>
                                        <tr>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- //loop display supplier withdraw yarn data -->
                                        @foreach ($sum_without_yarnType as $item)
                                            <tr>
                                                <td>{{ $item->supplierName }}</td>
                                                <!--ชื่อบริษัท-->
                                                <td>{{ $item->sumspool }}</td>
                                                <!--จำนวนด้ายรวม-->
                                                <td>{{ number_format($item->sumweight_p_net, 4) }} </td>
                                                <!--น้ำหนักรวม p -->
                                                <td>{{ number_format($item->sumweight_kg_net, 4) }}</td>
                                                <!--น้ำหนักรวม kg-->
                                                <td>{{ number_format($item->average_p, 4) }} </td>
                                                <!--น้ำหนักรวมเฉลี่ย p -->
                                                <td>{{ number_format($item->average_kg, 4) }} </td>
                                                <!--น้ำหนักรวมเฉลี่ย kg-->
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--row-->
                    </div>
                @elseif(isset($sum_import_supplier) && isset($sum_withdraw_supplier))
                    <!--display data by search yarnType -->
                    <h2>{{ $supplierName }} คงเหลือในสต๊อก </h2>
                    <p>รายการล่าสุดเมื่อ {{  $stockLatest }}</p>

                    <div class="List_table">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-a">
                                    <thead style="position: sticky;top: 0">
                                        <tr>
                                            <th rowspan="2">ชนิดด้าย</th>
                                            <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                            <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                            <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                        </tr>
                                        <tr>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                            <th>ปอนด์</th>
                                            <th>กิโลกรัม</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- //loop display supplier import yarn data -->
                                        @foreach ($sum_import_supplier as $item)
                                            {{-- {{ $item->yarnType }} --}}
                                            <!-- //Check withdraw material-->
                                            <?php $check = 0; ?>
                                            @foreach ($sum_withdraw_supplier as $item1)
                                                {{-- *{{ $item1->yarnType }} --}}
                                                

                                                @if ($item->yarnType === $item1->yarnType)
                                                    <?php
                                                    $check = 1;
                                                    $tmp_spool = $item->sumspool - $item1->sumspool;
                                                    $tmp_sumweight_p_net = number_format($tmp_spool * $item->average_p, 4);
                                                    $tmp_sumweight_kg_net = number_format($tmp_spool * $item->average_kg, 4);
                                                    ?>
                                                    @foreach ($sum_without_supplier as $item2)
                                                        @if ($item->yarnType === $item2->yarnType)
                                                            <?php
                                                            $tmp_spool = $tmp_spool - $item2->sumspool;
                                                            $tmp_sumweight_p_net = number_format($tmp_spool * $item->average_p, 4);
                                                            $tmp_sumweight_kg_net = number_format($tmp_spool * $item->average_kg, 4);
                                                            ?>
                                                        @endif
                                                    @endforeach
                                                    <tr>
                                                        <td>{{ $item->yarnType }}</td>
                                                        <!--ชื่อด้าย-->
                                                        <td>{{ $tmp_spool }}</td>
                                                        <!--จำนวนด้ายรวม-->
                                                        <td>{{ $tmp_sumweight_p_net }} </td>
                                                        <!--น้ำหนักรวม p -->
                                                        <td>{{ $tmp_sumweight_kg_net }}</td>
                                                        <!--น้ำหนักรวม kg-->
                                                        <td>{{ number_format($item->average_p, 4) }} </td>
                                                        <!--น้ำหนักรวมเฉลี่ย p -->
                                                        <td>{{ number_format($item->average_kg, 4) }} </td>
                                                        <!--น้ำหนักรวมเฉลี่ย kg-->
                                                    </tr>
                                                @break
                                            @endif
                                        @endforeach
                                        @if ($check != 1)
                                            <tr>
                                                <td>{{ $item->yarnType }}</td>
                                                <!--ชื่อด้าย-->
                                                <td>{{ $item->sumspool }}</td>
                                                <!--จำนวนด้ายรวม-->
                                                <td>{{ number_format($item->sumweight_p_net, 4) }} </td>
                                                <!--น้ำหนักรวม p -->
                                                <td>{{ number_format($item->sumweight_kg_net, 4) }}</td>
                                                <!--น้ำหนักรวม kg-->
                                                <td>{{ number_format($item->average_p, 4) }} </td>
                                                <!--น้ำหนักรวมเฉลี่ย p -->
                                                <td>{{ number_format($item->average_kg, 4) }} </td>
                                                <!--น้ำหนักรวมเฉลี่ย kg-->
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>


                <h2>นำเข้าวัตถุดิบ {{ $supplierName }}</h2>

                <div class="List_table">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">ชนิดด้าย</th>
                                        <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                        <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                        <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                    </tr>
                                    <tr>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- //loop display supplier import yarn data -->
                                    @foreach ($sum_import_supplier as $item)
                                        <tr>
                                            <td>{{ $item->yarnType }}</td>
                                            <!--ชื่อด้าย-->
                                            <td>{{ $item->sumspool }}</td>
                                            <!--จำนวนด้ายรวม-->
                                            <td>{{ number_format($item->sumweight_p_net, 4) }} </td>
                                            <!--น้ำหนักรวม p -->
                                            <td>{{ number_format($item->sumweight_kg_net, 4) }}</td>
                                            <!--น้ำหนักรวม kg-->
                                            <td>{{ number_format($item->average_p, 4) }} </td>
                                            <!--น้ำหนักรวมเฉลี่ย p -->
                                            <td>{{ number_format($item->average_kg, 4) }} </td>
                                            <!--น้ำหนักรวมเฉลี่ย kg-->
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>

                <h2>เบิกวัตถุดิบ {{ $supplierName }} ใช้ภายใน</h2>

                <div class="List_table">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">ชนิดด้าย</th>
                                        <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                        <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                        <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                    </tr>
                                    <tr>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- //loop display supplier withdraw yarn data -->
                                    @foreach ($sum_withdraw_supplier as $item)
                                        <tr>
                                            <td>{{ $item->yarnType }}</td>
                                            <!--ชื่อด้าย-->
                                            <td>{{ $item->sumspool }}</td>
                                            <!--จำนวนด้ายรวม-->
                                            <td>{{ number_format($item->sumweight_p_net, 4) }} </td>
                                            <!--น้ำหนักรวม p -->
                                            <td>{{ number_format($item->sumweight_kg_net, 4) }}</td>
                                            <!--น้ำหนักรวม kg-->
                                            <td>{{ number_format($item->average_p, 4) }} </td>
                                            <!--น้ำหนักรวมเฉลี่ย p -->
                                            <td>{{ number_format($item->average_kg, 4) }} </td>
                                            <!--น้ำหนักรวมเฉลี่ย kg-->
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>

                <h2>เบิกวัตถุดิบ {{ $supplierName }} ใช้ภายนอก</h2>

                <div class="List_table">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">ชนิดด้าย</th>
                                        <th rowspan="2">จำนวนด้ายรวม <br>(ลูก)</th>
                                        <th colspan="2">น้ำหนักรวมสุทธิ </th>
                                        <th colspan="2">น้ำหนักรวมเฉลี่ย </th>
                                    </tr>
                                    <tr>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- //loop display supplier withdraw yarn data -->
                                    @foreach ($sum_without_supplier as $item)
                                        <tr>
                                            <td>{{ $item->yarnType }}</td>
                                            <!--ชื่อด้าย-->
                                            <td>{{ $item->sumspool }}</td>
                                            <!--จำนวนด้ายรวม-->
                                            <td>{{ number_format($item->sumweight_p_net, 4) }} </td>
                                            <!--น้ำหนักรวม p -->
                                            <td>{{ number_format($item->sumweight_kg_net, 4) }}</td>
                                            <!--น้ำหนักรวม kg-->
                                            <td>{{ number_format($item->average_p, 4) }} </td>
                                            <!--น้ำหนักรวมเฉลี่ย p -->
                                            <td>{{ number_format($item->average_kg, 4) }} </td>
                                            <!--น้ำหนักรวมเฉลี่ย kg-->
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>
            @else

            <div class="List_table">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th rowspan="2">รายการล่าสุด</th>
                                        <th rowspan="2">ชนิดด้าย</th>
                                        <th rowspan="2">บริษัท </th>
                                        <th rowspan="2">จำนวนด้าย <br>(ลูก)</th>
                                        <th colspan="2">น้ำหนักสุทธิ </th>
                                        <th colspan="2">น้ำหนักเฉลี่ย </th>
                                    </tr>
                                    <tr>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                        <th>ปอนด์</th>
                                        <th>กิโลกรัม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $c1 = 0;
                                    ?>
                                    @foreach ($stockList as $tmp)
                                        <tr>
                                            <td rowspan=“10”>{{ $date = date('d/m/Y', strtotime($tmp[0]->createDate)) }}</td>
                                            <td rowspan=“10”>{{ $tmp[0]->yarnType }}</td>
                                            <td>
                                                <!-- /////////////////////////////////////////////////////////////////////////////////// -->
                                                <!-- <select class="selectVal" name="" id="" style="width: 300px"> -->
                                                <?php
                                                $c2 = 0;
                                                ?>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.655em;margin-bottom: -0.8em;"
                                                    cellspacing="0" cellpadding="0">

                                                    @foreach ($tmp as $selectSupplier)
                                                        <!-- <option value="{{ $c1 . '-' . $c2 }}">{{ $selectSupplier->supplierName }}</option> -->
                                                        <tr>
                                                            <td>
                                                                {{ $selectSupplier->supplierName }} <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    @endforeach
                                                    <!-- </select> -->
                                                </table>


                                                <?php
                                                $c1 += 1;
                                                ?>
                                                <!-- //////////////////////////////////////////////////////////////////////////////// -->
                                            </td>
                                            <!-- <td id="sp">{{ $tmp[0]->spool }}</td> -->
                                            <!-- <td id="wpn">{{ number_format($tmp[0]->weight_p_net, 4) }}</td>
        <td id="wkgn">{{ number_format($tmp[0]->weight_kg_net, 4) }}</td>
        <td id="ap">{{ number_format($tmp[0]->average_p, 4) }}</td>
        <td id="akg">{{ number_format($tmp[0]->average_kg, 4) }}</td> -->
                                            <td>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.65em;margin-bottom: -0.6em;">

                                                    @foreach ($tmp as $selectSpool)
                                                        <!-- <option value="{{ $c1 . '-' . $c2 }}">{{ $selectSupplier->supplierName }}</option> -->
                                                        <tr>
                                                            <td>
                                                                {{ $selectSpool->spool }} <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    @endforeach
                                                    <!-- </select> -->
                                                </table>
                                            </td>
                                            <td>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.65em;margin-bottom: -0.6em;">

                                                    @foreach ($tmp as $selectWeight_p_net)
                                                        <!-- <option value="{{ $c1 . '-' . $c2 }}">{{ $selectSupplier->supplierName }}</option> -->
                                                        <tr>
                                                            <td>
                                                                {{ number_format($selectWeight_p_net->weight_p_net, 4) }}
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    @endforeach
                                                    <!-- </select> -->
                                                </table>
                                            </td>
                                            <td>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.65em;margin-bottom: -0.6em;">

                                                    @foreach ($tmp as $selectWeight_kg_net)
                                                        <!-- <option value="{{ $c1 . '-' . $c2 }}">{{ $selectSupplier->supplierName }}</option> -->
                                                        <tr>
                                                            <td>
                                                                {{ number_format($selectWeight_kg_net->weight_kg_net, 4) }}
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    @endforeach
                                                    <!-- </select> -->
                                                </table>
                                            </td>
                                            <td>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.65em;margin-bottom: -0.6em;">

                                                    @foreach ($tmp as $selectAverage_p)
                                                        <!-- <option value="{{ $c1 . '-' . $c2 }}">{{ $selectSupplier->supplierName }}</option> -->
                                                        <tr>
                                                            <td>
                                                                {{ number_format($selectAverage_p->average_p, 4) }}
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    @endforeach
                                                    <!-- </select> -->
                                                </table>
                                            </td>
                                            <td>
                                                <table
                                                    style="width:103.5%; margin: -0.5em; margin-top: -0.65em;margin-bottom: -0.6em;">

                                                    @foreach ($tmp as $selectAverage_kg)
                                                        <!-- <option value="{{ $c1 . '-' . $c2 }}">{{ $selectSupplier->supplierName }}</option> -->
                                                        <tr>
                                                            <td>
                                                                {{ number_format($selectAverage_kg->average_kg, 4) }}
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $c2 += 1;
                                                        ?>
                                                    @endforeach
                                                    <!-- </select> -->
                                                </table>
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

            {{-- <div class="line_btn">
                    <button class="btn b_order"><i class="nav-icon fa fa-chevron-left"></i> ข้อมูลรายบริษัท</button>
                    <button class="btn b_save"><img src="assets/images/circle-check-solid.png" width="17">
                        ตรวจนับวัตถุดิบ</button>
                </div> --}}

        </div><!-- inner_content -->
    </div>
    <!--box-from-->
</div>
<!--content-->
</div><!-- /.content-wrapper -->

<script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
<script>
    var stocklist = {{ Js::from($stockList) }};

    $(document).ready(function() {
        $("select.selectVal").change(function() {
            let selectedItem = $(this).children("option:selected").val();
            //alert("You have selected the name - " + selectedItem);
            selectedItem = selectedItem.split('-');
            document.getElementById("sp").innerHTML = stocklist[selectedItem[0]][selectedItem[1]].spool;
            document.getElementById("wpn").innerHTML = stocklist[selectedItem[0]][selectedItem[1]]
                .weight_p_net.toFixed(4);
            document.getElementById("wkgn").innerHTML = stocklist[selectedItem[0]][selectedItem[1]]
                .weight_kg_net.toFixed(4);
            document.getElementById("ap").innerHTML = stocklist[selectedItem[0]][selectedItem[1]]
                .average_p.toFixed(4);
            document.getElementById("akg").innerHTML = stocklist[selectedItem[0]][selectedItem[1]]
                .average_kg.toFixed(4);
        });
    });
    //var test = {{ Js::from($stockList) }};
    //alert(test[0][1].supplierName);
    //var x = '1-2';
    //x = x.split('-');
    //alert(x[0]);
</script>
@endsection
