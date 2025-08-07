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
                        {{-- <head>{{ $supplierName }}</head> --}}
						{{-- <?php print_r($sum_import_yarnType) ?>
						<?php print_r($sum_withdraw_yarnType) ?> --}}
                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-a">
                                        <thead style="position: sticky;top: 0">
                                            <tr>
                                                {{-- <th rowspan="2">ตรวจนับเมื่อ</th> --}}
                                                <th rowspan="2">ชนิดด้าย</th>
                                                {{-- <th rowspan="2">บริษัท </th> --}}
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
                                            @foreach ($sum_import_yarnType as $tmp)
                                                @foreach ($sum_withdraw_yarnType as $tmpNon)
                                                    @if ($tmp->supplierName == $tmpNon->supplierName)
													<tr></tr>
                                                        <td rowspan=“10”>{{ $tmp->supplierName }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumspool - $tmpNon->sumspool }}</td>
                                                        <td rowspan=“10”>
                                                            {{ $tmp->sumweight_p_net - $tmpNon->sumweight_p_net }}</td>
                                                        <td rowspan=“10”>
                                                            {{ $tmp->sumweight_kg_net - $tmpNon->sumweight_kg_net }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumaverage_p - $tmpNon->sumaverage_p }}
                                                        </td>
                                                        <td rowspan=“10”>{{ $tmp->sumaverage_kg - $tmpNon->sumaverage_kg }}
                                                        </td>
													</tr>
                                                    @else
													<tr>
                                                        <td rowspan=“10”>{{ $tmp->supplierName }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumspool }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumweight_p_net }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumweight_kg_net }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumaverage_p }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumaverage_kg }}</td>
													</tr>
														@endif
                                                    @endforeach
                                                @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--row-->
                        </div>
                        {{-- <!--List_table @if (isset($sum_import_yarnType) && isset($sum_withdraw_yarnType))--> --}}
                    @elseif(isset($sum_import_supplier) && isset($sum_withdraw_supplier))
                        <!--display data by search yarnType -->
                        {{-- <head>{{ $yarnType }}</head> --}}
						{{-- <?php print_r($sum_import_supplier) ?>
						<?php print_r($sum_withdraw_supplier) ?> --}}
                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-a">
                                        <thead style="position: sticky;top: 0">
                                            <tr>
                                                {{-- <th rowspan="2">ตรวจนับเมื่อ</th> --}}
                                                {{-- <th rowspan="2">ชนิดด้าย</th> --}}
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
                                            @foreach ($sum_import_supplier as $tmp)
                                                @foreach ($sum_withdraw_supplier as $tmpNon)
                                                    @if ($tmp->yarnType == $tmpNon->yarnType)
													<tr>
                                                        <td rowspan=“10”>{{ $tmp->yarnType }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumspool - $tmpNon->sumspool }}</td>
                                                        <td rowspan=“10”>
                                                            {{ $tmp->sumweight_p_net - $tmpNon->sumweight_p_net }}</td>
                                                        <td rowspan=“10”>
                                                            {{ $tmp->sumweight_kg_net - $tmpNon->sumweight_kg_net }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumaverage_p - $tmpNon->sumaverage_p }}
                                                        </td>
                                                        <td rowspan=“10”>{{ $tmp->sumaverage_kg - $tmpNon->sumaverage_kg }}
                                                        </td>
													</tr>
                                                    @else
													<tr>
                                                        <td rowspan=“10”>{{ $tmp->yarnType }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumspool }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumweight_p_net }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumweight_kg_net }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumaverage_p }}</td>
                                                        <td rowspan=“10”>{{ $tmp->sumaverage_kg }}</td>
													</tr>
														@endif
                                                    @endforeach
                                                @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--row-->
                        </div>
                        {{-- <!--List_table  @elseif(isset($sum_import_supplier) && isset($sum_withdraw_supplier))--> --}}
                    @else
                        <div class="List_table">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-a">
                                        <thead style="position: sticky;top: 0">
                                            <tr>
                                                <th rowspan="2">ตรวจนับเมื่อ</th>
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
                                                    <td rowspan=“10”>{{ $tmp[0]->createDate }}</td>
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

                    <div class="line_btn">
                        <button class="btn b_order"><i class="nav-icon fa fa-chevron-left"></i> ข้อมูลรายบริษัท</button>
                        <button class="btn b_save"><img src="assets/images/circle-check-solid.png" width="17">
                            ตรวจนับวัตถุดิบ</button>
                    </div>

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
