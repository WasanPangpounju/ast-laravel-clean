{{-- resources/views/fabricimport/show.blade.php --}}
@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper">
  {{-- Breadcrumb --}}
  <div class="">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
      <li class="breadcrumb-item"><a href="{{ route('fabricimport.index') }}">ซื้อผ้าเข้าสต็อก</a></li>
      <li class="breadcrumb-item active">รายละเอียดใบซื้อเข้า</li>
    </ol>
  </div>

  {{-- Header --}}
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <h1 class="m-0">
          <i class="nav-icon fas fa fa-arrow-circle-right"></i>
          รายละเอียดใบซื้อเข้า
        </h1>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="box-from">
      <div class="container">

        {{-- Summary / Header --}}
        <div class="card mb-3">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <p class="mb-1"><strong>Ref ID:</strong> {{ $header['refId'] }}</p>
                <p class="mb-1">
                  <strong>วันที่คีย์:</strong>
                  @if(!empty($header['createDate']))
                    {{ \Carbon\Carbon::parse($header['createDate'])->format('d/m/Y') }}
                  @endif
                </p>
                <p class="mb-1"><strong>ผู้คีย์:</strong> {{ $header['emp'] }}</p>
                <p class="mb-1"><strong>ลูกค้า:</strong> {{ $header['customer'] }}</p>
              </div>
              <div class="col-md-6">
                <p class="mb-1">
                  <strong>รหัสผ้า:</strong> {{ $header['fabricId'] }}
                  <span class="ml-2"><strong>โครงสร้าง:</strong> {{ $header['fabricStruct'] }}</span>
                </p>
                <p class="mb-1"><strong>ลายผ้า:</strong> {{ $header['fabricPattern'] }}</p>
                <p class="mb-1"><strong>หน้ากว้าง:</strong> {{ $header['fabricW'] }}</p>
              </div>
            </div>

            <hr>

            <div class="row">
              <div class="col-md-6">
                <p class="mb-1"><strong>ผู้ขาย/โรงงาน:</strong> {{ $header['supplier_name'] }}</p>
                <p class="mb-1"><strong>เลขที่บิล/ใบส่งของ:</strong> {{ $header['invoice_no'] }}</p>
                <p class="mb-1"><strong>ล็อตย้อม (Dye Lot):</strong> {{ $header['dye_lot'] }}</p>
                <p class="mb-1"><strong>ตำแหน่งจัดเก็บ:</strong> {{ $header['location'] }}</p>
              </div>
              <div class="col-md-6">
                <p class="mb-1"><strong>SO Number:</strong> {{ $header['SONumber'] }}</p>
                <p class="mb-1">
                  <strong>ราคาซื้อต่อหลา:</strong>
                  @if(!is_null($header['unit_price']))
                    {{ number_format($header['unit_price'], 2) }}
                  @endif
                </p>
                <p class="mb-1"><strong>รวมพับ:</strong> {{ number_format($header['total_folds']) }}</p>
                <p class="mb-1"><strong>รวมหลา:</strong> {{ number_format($header['total_yards'], 2) }}</p>
                <p class="mb-1">
                  <strong>ราคารวม:</strong>
                  @if(!is_null($header['total_cost']))
                    {{ number_format($header['total_cost'], 2) }}
                  @endif
                </p>
              </div>
            </div>
          </div>
        </div>

        {{-- Items table --}}
        <div class="card">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered mb-0">
                <thead class="thead-light">
                  <tr class="text-center">
                    <th style="width:80px;">#</th>
                    <th style="width:120px;">พับที่</th>
                    <th>จำนวน (หลา)</th>
                    <th style="width:180px;">บันทึกเมื่อ</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($rows as $i => $r)
                    <tr>
                      <td class="text-center">{{ $i + 1 }}</td>
                      <td class="text-center">{{ $r->fold }}</td>
                      <td class="text-right">{{ number_format($r->sumYard, 2) }}</td>
                      <td class="text-center">
                        {{ optional($r->created_at)->format('d/m/Y H:i') }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center text-muted p-4">ไม่พบรายการ</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('fabricimport.index') }}" class="btn btn-outline-secondary">
              &laquo; กลับหน้ารายการใบซื้อเข้า
            </a>
            <div>
              {{-- ปุ่มอื่น ๆ ที่ต้องการในอนาคต --}}
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
