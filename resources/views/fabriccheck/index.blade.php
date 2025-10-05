@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0"><i class="nav-icon fas fa-list"></i> ตรวจสอบคีย์ผ้าเข้าสต็อก</h1>
      <p class="text-muted mb-0">แสดง 500 รายการล่าสุด ต่อหน้า • ใช้ปุ่ม “ถัดไป” เพื่อดูชุดถัดไป</p>
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
                  <th style="white-space:nowrap;">#</th>
                  <th style="white-space:nowrap;">วันที่คีย์</th>
                  <th style="white-space:nowrap;">Ref</th>
                  <th style="white-space:nowrap;">ผู้คีย์</th>
                  <th style="white-space:nowrap;">รหัสผ้า</th>
                  <th style="white-space:nowrap;">โครงสร้าง</th>
                  <th style="white-space:nowrap;">ลาย</th>
                  <th style="white-space:nowrap;">หน้ากว้าง</th>
                  <th style="white-space:nowrap;">พับที่</th>
                  <th style="white-space:nowrap;">ยอด (หลา)</th>
                  <th style="white-space:nowrap;">ลูกค้า</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($rows as $i => $r)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-nowrap">
                      {{ \Carbon\Carbon::parse($r->created_at)->timezone('Asia/Bangkok')->format('d/m/Y H:i') }}
                    </td>
                    <td class="text-monospace">{{ $r->refId }}</td>
                    <td>{{ $r->emp }}</td>
                    <td class="text-nowrap">{{ $r->fabricId }}</td>
                    <td>{{ $r->fabricStruct }}</td>
                    <td>{{ $r->fabricPattern }}</td>
                    <td class="text-nowrap">{{ $r->fabricW }}</td>
                    <td class="text-right">{{ number_format($r->fold) }}</td>
                    <td class="text-right">{{ number_format($r->sumYard, 2) }}</td>
                    <td>{{ $r->customer }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="11" class="text-center text-muted p-4">ยังไม่มีข้อมูลคีย์ผ้าเข้าสต็อก</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        @if ($rows->hasPages())
          <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
              {{-- ปุ่มย้อนหน้า (ถ้ามี) --}}
              @if ($rows->previousCursor())
                <a class="btn btn-outline-secondary btn-sm"
                   href="{{ route('fabriccheck.index', ['cursor' => $rows->previousCursor()->encode()]) }}">
                  &laquo; ก่อนหน้า
                </a>
              @else
                <button class="btn btn-outline-secondary btn-sm" disabled>&laquo; ก่อนหน้า</button>
              @endif>

              {{-- กลับหน้าแรก --}}
              <a class="btn btn-outline-primary btn-sm" href="{{ route('fabriccheck.index') }}">หน้าแรก</a>

              {{-- ปุ่มถัดไป (ถ้ามี) --}}
              @if ($rows->nextCursor())
                <a class="btn btn-primary btn-sm"
                   href="{{ route('fabriccheck.index', ['cursor' => $rows->nextCursor()->encode()]) }}">
                  ถัดไป &raquo;
                </a>
              @else
                <button class="btn btn-primary btn-sm" disabled>ถัดไป &raquo;</button>
              @endif
            </div>

            <small class="text-muted">
              แสดงผล: {{ $rows->count() }} รายการ
            </small>
          </div>
        @endif
      </div>

    </div>
  </div>
</div>
@endsection
