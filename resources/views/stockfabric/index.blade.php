@extends('layouts.astmanufacturing')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="/stockfabric/">คลังสินค้า</a></li>
                <li class="breadcrumb-item active">รายการสต็อกผ้า</li>
            </ol>
        </div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> รายการสต็อกผ้า</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="box-from">
                <h2 class="title"><i class="fa fa-caret-right"></i> รายการสต็อกผ้า</h2>
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn b_order" type="button" style="width: 60%;margin:0.5rem;background-color: #ebd575;">
                                <a href="{{ route('inventory.index') }}">ออร์เดอร์ลูกค้า</a>
                            </button>
                            <button class="btn b_order" type="button" style="width: 60%;margin:0.5rem;background-color: #aca06e;">
                                <a href="{{ route('fabricout.index') }}">พิมพ์บิลส่งของ</a>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn b_order" type="button" style="width: 70%;margin:0.5rem;background-color: #1bccbd;">
                                <a href="{{ route('inventory.create') }}">คีย์ผ้าเข้าสต็อก</a>
                            </button>
                            <button class="btn b_order" type="button" style="width: 70%;margin:0.5rem;background-color: #8a8a8a;">
                                <a href="{{ route('fabricout.create') }}">เปิดบิลผ้า</a>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn b_order" type="button" style="width: 55%;margin:0.5rem;background-color: #ec9c06;">
                                <a href="{{ route('stockfabric.index') }}">สต็อกผ้า</a>
                            </button>
                            <button class="btn b_order" type="button" style="width: 55%;margin:0.5rem;background-color: rgb(175, 163, 110);">
                                <a href="{{ route('fabricdeposit.index') }}">สต็อกผ้าฝากจัดเก็บ</a>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn b_order" type="button" style="width: 70%;margin:0.5rem;background-color: #ec9c06;">
                                <a href="{{ route('fabriccheck.index') }}">ตรวจสอบคีย์ผ้าเข้าสต็อก</a>
                            </button>
                        </div>
                    </div>
                </div>
                <form method="post" action="{{ route('stockfabric.store') }}" id="myForm">
                    @csrf
                    <input type="hidden" id="refId" name="refId" value="">
                    <input type="hidden" id="emp" name="emp" value="{{ Auth::user()->name }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fabricStruct">โครงสร้างผ้า</label>
                                @if (isset($backdata))
                                    <input type="text" name="fabricStruct" class="form-control" id="fabricStruct"
                                        placeholder="โครงสร้างผ้า" value="{{ $backdata->fabricStruct }}">
                                @else
                                    <input type="text" name="fabricStruct" class="form-control" id="fabricStruct"
                                        placeholder="โครงสร้างผ้า">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="customer">ลูกค้า</label>
                                @if (isset($backdata))
                                    <input type="text" name="customer" class="form-control" id="customer"
                                        placeholder="ลูกค้า" value="{{ $backdata->customer }}">
                                @else
                                    <input type="text" name="customer" class="form-control" id="customer"
                                        placeholder="ลูกค้า">
                                @endif
                            </div>
                        </div>
                        <!--row-->
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="text-right align-items-end" style="margin-top:1.5rem">
                                    <button name="submit" value="searchImport" class="btn_search">
                                        <img src="<?php echo asset('assets/images/circle-check-solid.png'); ?>" width="17">
                                        ค้นหา
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @php
                    $fs_norm = function($s) {
                        $s = (string) $s;
                        $s = str_replace('undefined', '', $s);
                        $s = preg_replace('/\s*([*x])\s*/i', ' x ', $s);
                        $s = preg_replace('/\s+/', ' ', trim($s));
                        return $s;
                    };
                @endphp

                <!-- ตาราง -->
                @if (isset($importorder) && count($importorder) > 0)
                    @include('stockfabric.partials._table', [
                        'data1' => $importorder,
                        'data2' => $sumFabricout,
                        'fs_norm' => $fs_norm
                    ])
                @elseif(isset($importorder) && count($importorder) < 1)
                    <p>ไม่พบผลการค้นหา</p>
                @else
                    @include('stockfabric.partials._table', [
                        'data1' => $sumStockfabric,
                        'data2' => $sumFabricout,
                        'fs_norm' => $fs_norm
                    ])
                @endif
            </div>
        </div>
    </div>
@endsection
