@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0"><i class="nav-icon fas fa-clipboard-list"></i> ตรวจสอบคีย์ผ้าเข้าสต็อก (สรุปตาม Ref)</h1>
      <small class="text-muted">แสดง 500 Ref ล่าสุด</small>
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
                  <th>ผู้คีย์</th>
                  <th>ลูกค้า</th>
                  <th>รหัส</th>
                  <th>โครงสร้าง</th>
                  <th>ลายผ้า</th>
                  <th>หน้ากว้าง</th>
                  <th>รวมพับ</th>
                  <th>รวม (หลา)</th>
                  <th>วันที่คีย์</th>
                  <th style="width:120px;">การดำเนินการ</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($rows as $r)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $r->emp }}</td>
                    <td>{{ $r->customer }}</td>
                    <td class="text-nowrap">{{ $r->fabricId }}</td>
                    <td>{{ $r->fabricStruct }}</td>
                    <td>{{ $r->fabricPattern }}</td>
                    <td class="text-nowrap">{{ $r->fabricW }}</td>
                    <td class="text-right">{{ number_format($r->folds) }}</td>
                    <td class="text-right">{{ number_format($r->yards, 2) }}</td>
                    <td class="text-nowrap text-center">
                      {{ \Carbon\Carbon::parse($r->key_date)->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                      <a class="btn btn-primary btn-sm" href="{{ route('fabriccheck.show', $r->refId) }}">
                        ดูรายละเอียด
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="11" class="text-center text-muted p-4">ยังไม่มีข้อมูล</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        @if ($rows->hasPages())
          <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="btn-group">
              @if ($rows->previousCursor())
                <a class="btn btn-outline-secondary btn-sm"
                   href="{{ route('fabriccheck.index', ['cursor' => $rows->previousCursor()->encode()]) }}">
                  &laquo; ก่อนหน้า
                </a>
              @else
                <button class="btn btn-outline-secondary btn-sm" disabled>&laquo; ก่อนหน้า</button>
              @endif

              <a class="btn btn-outline-primary btn-sm" href="{{ route('fabriccheck.index') }}">หน้าแรก</a>

              @if ($rows->nextCursor())
                <a class="btn btn-primary btn-sm"
                   href="{{ route('fabriccheck.index', ['cursor' => $rows->nextCursor()->encode()]) }}">
                  ถัดไป &raquo;
                </a>
              @else
                <button class="btn btn-primary btn-sm" disabled>ถัดไป &raquo;</button>
              @endif
            </div>
            <small class="text-muted">แสดง: {{ $rows->count() }} Ref</small>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
