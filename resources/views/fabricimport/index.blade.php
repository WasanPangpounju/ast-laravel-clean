{{-- resources/views/fabricimport/index.blade.php --}}
@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper">

  {{-- Breadcrumb --}}
  <div class="">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
      <li class="breadcrumb-item active">ซื้อผ้าเข้าสต็อก (สรุปตามเอกสาร)</li>
    </ol>
  </div>

  {{-- Header --}}
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0">
        <i class="nav-icon fas fa-boxes"></i>
        รายการใบซื้อผ้าเข้าสต็อก
      </h1>
      <div>
        <a href="{{ route('fabricimport.create') }}" class="btn b_order">+ คีย์ซื้อเข้าใหม่</a>
        <a href="{{ route('fabricimport.check.index') }}" class="btn b_order" style="background:#ec9c06;">ตรวจสอบคีย์ซื้อเข้า</a>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered mb-0">
              <thead class="thead-light">
                <tr class="text-center">
                  <th style="width:60px;">#</th>
                  <th style="width:240px;">Ref ID</th>
                  <th style="width:120px;">วันที่คีย์</th>
                  <th style="width:120px;">รวมพับ</th>
                  <th style="width:140px;">รวมหลา</th>
                  <th>ผู้ขาย/โรงงาน</th>
                  <th style="width:180px;">เลขที่บิล</th>
                  <th style="width:130px;">การทำงาน</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($list as $i => $row)
                  <tr>
                    <td class="text-center">{{ ($list->currentPage() - 1) * $list->perPage() + $i + 1 }}</td>
                    <td class="text-monospace">{{ $row->refId }}</td>
                    <td class="text-center">
                      @if(!empty($row->date))
                        {{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}
                      @endif
                    </td>
                    <td class="text-right">{{ number_format($row->folds) }}</td>
                    <td class="text-right">{{ number_format($row->yards, 2) }}</td>
                    <td>{{ $row->supplier }}</td>
                    <td>{{ $row->invoice_no }}</td>
                    <td class="text-center">
                      {{-- ดูรายละเอียดฉบับย่อย (ตาม Controller::show) --}}
                      <a href="{{ route('fabricimport.show', $row->refId) }}" class="btn btn-primary btn-sm">ดูรายละเอียด</a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted p-4">ยังไม่มีข้อมูล</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        @if(method_exists($list, 'links'))
          <div class="card-footer">
            {{ $list->links() }}
          </div>
        @endif
      </div>

    </div>
  </div>
</div>
@endsection
