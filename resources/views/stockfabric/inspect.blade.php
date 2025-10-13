{{-- resources/views/stockfabric/inspect.blade.php --}}
@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper">
  <div class="">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
      <li class="breadcrumb-item"><a href="{{ route('stockfabric.index') }}">สต็อกผ้า</a></li>
      <li class="breadcrumb-item active">ตรวจสอบสต็อก (รายการแถว)</li>
    </ol>
  </div>

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <h1 class="m-0">
          <i class="nav-icon fas fa fa-arrow-circle-right"></i>
          ตรวจสอบสต็อก: รายการที่ตรงเงื่อนไข
        </h1>
      </div>

      {{-- Flash messages --}}
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
    </div>
  </div>

  <div class="content">
    <div class="box-from">
      <h2 class="title"><i class="fa fa-caret-right"></i> รายการสต็อกเข้า (Stock-In)</h2>

      {{-- ฟอร์มเดียว ครอบทั้งส่วน “ลบทั้งหมด/ลบที่เลือก” + ตาราง --}}
      <form method="post" action="{{ route('stockfabric.destroyInBulk') }}"
            onsubmit="return confirm('ต้องการลบรายการที่เลือกทั้งหมดหรือไม่? การลบไม่สามารถย้อนกลับได้');">
        @csrf
        @method('DELETE')

        {{-- ส่ง key กลับไปด้วย เพื่อให้ controller redirect กลับมาดูผลเดิมได้ --}}
        <input type="hidden" name="customer"      value="{{ $key['customer']      ?? '' }}">
        <input type="hidden" name="fabricId"      value="{{ $key['fabricId']      ?? '' }}">
        <input type="hidden" name="fabricStruct"  value="{{ $key['fabricStruct']  ?? '' }}">
        <input type="hidden" name="fabricPattern" value="{{ $key['fabricPattern'] ?? '' }}">
        <input type="hidden" name="fabricW"       value="{{ $key['fabricW']       ?? '' }}">

        {{-- แถบเครื่องมือบนหัวตาราง --}}
        <div class="d-flex justify-content-between align-items-center mb-3" style="gap:.5rem;flex-wrap:wrap;">
          <div class="small text-muted">
            <strong>เงื่อนไข:</strong>
            @php
              $parts = [];
              if(!empty($key['customer']))      $parts[] = 'ลูกค้า: '.$key['customer'];
              if(!empty($key['fabricId']))      $parts[] = 'รหัสผ้า: '.$key['fabricId'];
              if(!empty($key['fabricStruct']))  $parts[] = 'โครงสร้าง: '.$key['fabricStruct'];
              if(!empty($key['fabricPattern'])) $parts[] = 'ลาย: '.$key['fabricPattern'];
              if(!empty($key['fabricW']))       $parts[] = 'หน้ากว้าง: '.$key['fabricW'];
            @endphp
            {{ count($parts) ? implode(' , ', $parts) : 'ทั้งหมด' }}
          </div>

          <div class="d-flex" style="gap:.5rem;">
            {{-- กดแล้วจะลบ “ทุกแถวที่ถูกเลือก” (เริ่มต้นเราเช็คไว้ทั้งหมดอยู่แล้ว) --}}
            <button class="btn btn-danger" type="submit">ลบทั้งหมดในชุดนี้</button>
            <a href="{{ route('stockfabric.index') }}" class="btn b_order">กลับ</a>
          </div>
        </div>

        {{-- ตารางรายการ --}}
        <div class="row">
          <div class="col-12 table-responsive">
            <table class="table table-bordered table-a" style="width:100%">
              <thead style="position: sticky;top:0;background-color:powderblue;">
                <tr>
                  <th style="width:2.5rem;text-align:center;">
                    <input type="checkbox" id="selectAll" checked>
                  </th>
                  <th style="width:6rem;">ID</th>
                  <th style="width:8rem;">วันที่</th>
                  <th>ลูกค้า</th>
                  <th>รหัสผ้า</th>
                  <th>โครงสร้าง</th>
                  <th>ลาย</th>
                  <th style="width:7rem;">หน้ากว้าง</th>
                  <th style="width:5rem;">พับ</th>
                  <th style="width:6rem;">หลา</th>
                  <th style="width:9rem;">ผู้บันทึก</th>
                  <th style="width:6rem;">ลบ</th>
                </tr>
              </thead>
              <tbody style="text-align:right;">
                @forelse ($ins as $row)
                  <tr>
                    <td style="text-align:center;">
                      {{-- ✅ ids[] ถูกส่งแน่นอน --}}
                      <input type="checkbox" class="row-check" name="ids[]" value="{{ $row->id }}" checked>
                    </td>
                    <td style="text-align:center;">{{ $row->id }}</td>
                    <td>{{ $row->createDate ? \Carbon\Carbon::parse($row->createDate)->format('d/m/Y') : '' }}</td>
                    <td style="text-align:left;">{{ $row->customer ?: 'AST' }}</td>
                    <td style="text-align:left;">{{ $row->fabricId }}</td>
                    <td style="text-align:left;">{{ $row->fabricStruct }}</td>
                    <td style="text-align:left;">{{ $row->fabricPattern }}</td>
                    <td style="text-align:center;">{{ $row->fabricW }}</td>
                    <td>{{ number_format((float)$row->fold, 0) }}</td>
                    <td>{{ rtrim(rtrim(number_format((float)$row->sumYard, 2, '.', ''), '0'), '.') }}</td>
                    <td style="text-align:left;">{{ $row->emp ?? '-' }}</td>
                    <td style="text-align:center;">
                      <form method="post"
                            action="{{ route('stockfabric.destroyIn', $row->id) }}"
                            onsubmit="return confirm('ต้องการลบรายการ ID {{ $row->id }} หรือไม่?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">ลบ</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="12" class="text-center">ไม่พบรายการ</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- เลือกทั้งหมด/ยกเลิกทั้งหมด --}}
<script>
  const selectAll = document.getElementById('selectAll');
  const rowChecks = document.querySelectorAll('.row-check');

  function syncSelectAllState() {
    const allChecked = Array.from(rowChecks).every(ch => ch.checked);
    selectAll.checked = allChecked;
  }

  // toggle ทั้งชุดจากหัวตาราง
  selectAll?.addEventListener('change', function() {
    rowChecks.forEach(ch => ch.checked = this.checked);
  });

  // อัปเดตสถานะหัวตารางเมื่อมีการติ๊ก/ยกเลิกทีละแถว
  rowChecks.forEach(ch => ch.addEventListener('change', syncSelectAllState));
</script>
@endsection
