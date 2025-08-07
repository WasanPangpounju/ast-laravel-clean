@extends('layouts.astmanufacturing')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="#">วัตถุดิบ</a></li>
                <li class="breadcrumb-item active">คืนบรรจุภัณฑ์</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 Header">
                    <div class="col">
                        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i>คืนบรรจุภัณฑ์</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content">
            <div class="box-from">
                <!--line_btn-->
                <?php
                //  print_r($package);
                ?>
                </form>
                <div class="List_table">
                    <div class="row">
                        <div class="col-3">
                        </div>
                        <div class="col-6 table-responsive">
                            <table class="table table-bordered table-a">
                                <thead style="position: sticky;top: 0">
                                    <tr>

                                        <th rowspan="2">บริษัท </th>
                                        <th colspan="4">ชนิด บรรจุภัณฑ์</th>
                                        <th rowspan="2">คืนบรรจุภัณฑ์</th>

                                    </tr>
                                    <tr>
                                        <th>หลอด</th>
                                        <th>กระสอบ</th>
                                        <th>กล่อง</th>
                                        <th>พาเลท</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $c1 = 0;
                                    ?>
                                    @foreach ($duplicate_data as $packageList)
                                        @foreach ($duplicate_datahtr as $packageListhtr)
										<?php $check = 0; ?>
                                            <?php  if ($packageList->supplier_name === $packageListhtr->supplier_name) { 
												$check = 1;
												?>
                                            <tr>
                                                <td>{{ $packageList->supplier_name }}</td>
                                                <td>{{ $packageList->spoolsum - $packageListhtr->spoolsum }}</td>
                                                <td>{{ $packageList->sacksum - $packageListhtr->sacksum }}</td>
                                                <td>{{ $packageList->boxsum - $packageListhtr->boxsum }}</td>
                                                <td>{{ $packageList->palletsum - $packageListhtr->palletsum }}</td>
                                                <td>
                                                    <div
                                                        style="  display: flex;justify-content: center;align-items: center;">
                                                        <button class="btn b_order" type="button"><a
                                                                href="{{ route('package.edit', $packageList->supplier_name) }}">คืนบรรจุภัณฑ์</a></button>
                                                    </div>
                                                </td>

                                            </tr>
											@break
                                            <?php } ?>
											@endforeach
                                            <?php   if ($check != 1) { ?>
                                            <tr>
                                                <td>{{ $packageList->supplier_name }}</td>
                                                <td>{{ $packageList->spoolsum }}</td>
                                                <td>{{ $packageList->sacksum }}</td>
                                                <td>{{ $packageList->boxsum }}</td>
                                                <td>{{ $packageList->palletsum }}</td>
                                                <td>
                                                    <div
                                                        style="  display: flex;justify-content: center;align-items: center;">
                                                        <button class="btn b_order" type="button"><a
                                                                href="{{ route('package.edit', $packageList->supplier_name) }}">คืนบรรจุภัณฑ์</a></button>
                                                    </div>
                                                </td>

                                            </tr>
                                            <?php  } ?>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--row-->
                </div>
                <!--List_table-->
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
    <script>
        pallet = 0, box = 0, sack = 0, spool = 0;
        s = '';

        function myFunction(e) {
            if (e.target.value == 'pallet') {
                s = 'pallet';
                document.getElementById("myText").value = pallet
            }
            if (e.target.value == 'box') {
                s = 'box';
                document.getElementById("myText").value = box
            }
            if (e.target.value == 'sack') {
                s = 'sack';
                document.getElementById("myText").value = sack
            }
            if (e.target.value == 'spool') {
                s = 'spool';
                document.getElementById("myText").value = spool
            }

        }

        function setcountFunction(e) {
            if (s = 'pallet') {
                pallet = e.target.value
            }
            if (s == 'box') {
                box = e.target.value
            }
            if (s == 'sack') {
                sack = e.target.value
            }
            if (s == 'spool') {
                spool = e.target.value
            }

        }
    </script>
@endsection
