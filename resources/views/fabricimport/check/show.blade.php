{{-- resources/views/fabricimport/check/show.blade.php --}}
@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper always-show-actions">

  <style>
    .always-show-actions .header-actions .btn,
    .always-show-actions .action-cell .btn,
    .always-show-actions .action-cell .row-actions,
    .always-show-actions .table tbody tr td .btn,
    .always-show-actions .table tbody tr td .btn-group,
    .always-show-actions .table tbody tr td .row-actions {
      opacity: 1 !important;
      visibility: visible !important;
      filter: none !important;
    }
    .always-show-actions .header-actions form,
    .always-show-actions .action-cell form {
      display: inline-block !important;
      margin: 0 4px;
    }
    .always-show-actions .table .row-actions { display: inline-flex !important; gap: .25rem; }
    .always-show-actions .table tbody tr td { overflow: visible !important; }
  </style>

  {{-- Breadcrumb --}}
  <div class="">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
      <li class="breadcrumb-item"><a href="{{ route('fabricimport.check.index') }}">ตรวจสอบซื้อผ้าเข้าสต็อก</a></li>
      <li class="breadcrumb-item active">รายละเอียด</li>
    </ol>
  </div>

  {{-- Header --}}
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center header-actions">
      <h1 class="m-0">
        <i class="nav-icon fas fa-search"></i>
        รายละเอียดการซื้อผ้าเข้าสต็อก
      </h1>

      <div>
        {{-- ลบทั้งชุด --}}
        <form method="POST" action="{{ route('fabricimport.check.destroy', $refId) }}"
              onsubmit="return confirm('ยืนยันลบรายการชุดนี้ทั้งหมดหรือไม่?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">ลบรายการนี้ทั้งหมด</button>
        </form>

        {{-- ปุ่มกลับ --}}
        <a href="{{ route('fabricimport.check.index') }}" class="btn b_order">กลับ</a>

        {{-- (ตัวช่วย debug) เปิดหน้าแบบเบา ไม่ผ่าน layout --}}
{{--        <a href="{{ request()->url() }}?_plain=1" class="btn btn-secondary btn-sm">โหมดเบา (ทดสอบ)</a> --}}
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">

      {{-- Flash message --}}
      @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('status') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      {{-- สรุปหัวข้อ --}}
      <div class="card mb-3">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p class="mb-1"><strong>Ref ID:</strong> {{ $header->refId }}</p>
              <p class="mb-1">
                <strong>วันที่คีย์:</strong>
                @if(!empty($header->key_date))
                  {{ @date('d/m/Y', @strtotime($header->key_date)) }}
                @endif
              </p>
              <p class="mb-1"><strong>ผู้คีย์:</strong> {{ $header->emp }}</p>
              <p class="mb-1"><strong>ลูกค้า:</strong> {{ $header->customer }}</p>
            </div>
            <div class="col-md-6">
              <p class="mb-1">
                <strong>รหัสผ้า:</strong> {{ $header->fabricId }}
                <span class="ml-2"><strong>โครงสร้าง:</strong> {{ $header->fabricStruct }}</span>
              </p>
              <p class="mb-1"><strong>ลายผ้า:</strong> {{ $header->fabricPattern }}</p>
              <p class="mb-1"><strong>หน้ากว้าง:</strong> {{ $header->fabricW }}</p>
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-md-6">
              <p class="mb-1"><strong>ผู้ขาย/โรงงาน:</strong> {{ $header->supplier_name }}</p>
              <p class="mb-1"><strong>เลขที่บิล/ใบส่งของ:</strong> {{ $header->invoice_no }}</p>
              <p class="mb-1"><strong>ล็อตย้อม (Dye Lot):</strong> {{ $header->dye_lot }}</p>
              <p class="mb-1"><strong>ตำแหน่งจัดเก็บ:</strong> {{ $header->location }}</p>
            </div>
            <div class="col-md-6">
              <p class="mb-1"><strong>SO Number:</strong> {{ $header->SONumber }}</p>
              <p class="mb-1">
                <strong>ราคาซื้อต่อหลา:</strong>
                @if(!is_null($header->unit_price)) {{ number_format((float)$header->unit_price, 2) }} @endif
              </p>
              <p class="mb-1"><strong>รวมพับ:</strong> {{ (int)$header->folds }}</p>
              <p class="mb-1"><strong>รวมหลา:</strong> {{ number_format((float)$header->yards, 2) }}</p>
              <p class="mb-1">
                <strong>ราคารวม:</strong>
                @if(!is_null($header->total_cost)) {{ number_format((float)$header->total_cost, 2) }} @endif
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- ตารางรายการพับ --}}
      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered mb-0">
              <thead class="thead-light">
                <tr class="text-center">
                  <th style="width: 90px;">พับที่</th>
                  <th style="width: 140px;">วันที่คีย์</th>
                  <th>ยอด (หลา)</th>
                  <th>ผู้คีย์</th>
                  <th style="width: 140px;">การดำเนินการ</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($items as $it)
                  <tr>
                    <td class="text-right">
                      {{ (int)preg_replace('/\D+/', '', (string)$it->fold) }}
                    </td>
                    <td class="text-nowrap text-center">
                      {{ @date('d/m/Y', @strtotime($it->createDate ?? $it->created_at)) }}
                    </td>
                    <td class="text-right">
                      {{ number_format((float)str_replace(',', '', (string)$it->sumYard), 2) }}
                    </td>
                    <td>{{ $it->emp }}</td>
                    <td class="text-center action-cell">
                      <div class="row-actions">
                        <form method="POST"
                              action="{{ route('fabricimport.check.destroyItem', ['refId' => $refId, 'id' => $it->id]) }}"
                              onsubmit="return confirm('ยืนยันลบพับที่ {{ (int)preg_replace('/\D+/', '', (string)$it->fold) }} หรือไม่?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="5" class="text-center text-muted p-4">ไม่พบรายการ</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>
@endsection
