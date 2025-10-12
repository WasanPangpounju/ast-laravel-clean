@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0">ตรวจสอบรายการสต็อกเข้า</h1>
      <p class="text-muted">
        ลูกค้า: <b>{{ $key['customer'] ?: '-' }}</b> |
        Fabric ID: <b>{{ $key['fabricId'] ?: '-' }}</b> |
        โครงสร้าง: <b>{{ $key['fabricStruct'] ?: '-' }}</b> |
        ลาย: <b>{{ $key['fabricPattern'] ?: '-' }}</b> |
        หน้ากว้าง: <b>{{ $key['fabricW'] ?: '-' }}</b>
      </p>

      {{-- ลบทั้งหมด --}}
      <form action="{{ route('stockfabric.destroyInBulk') }}" method="post" onsubmit="return confirm('ยืนยันลบทั้งหมด?')">
        @csrf
        @method('DELETE')
        <input type="hidden" name="customer"      value="{{ $key['customer'] }}">
        <input type="hidden" name="fabricId"      value="{{ $key['fabricId'] }}">
        <input type="hidden" name="fabricStruct"  value="{{ $key['fabricStruct'] }}">
        <input type="hidden" name="fabricPattern" value="{{ $key['fabricPattern'] }}">
        <input type="hidden" name="fabricW"       value="{{ $key['fabricW'] }}">
        <button type="submit" class="btn btn-danger mb-3">ลบทั้งหมด</button>
        <a href="{{ route('stockfabric.index') }}" class="btn btn-secondary mb-3">กลับหน้าเดิม</a>
      </form>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="card">
        <div class="card-body table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="bg-light">
              <tr>
                <th style="width:90px">ลบ</th>
                <th>ID</th>
                <th>วันที่</th>
                <th>ผู้บันทึก</th>
                <th>Customer</th>
                <th>Fabric ID</th>
                <th>โครงสร้าง</th>
                <th>ลาย</th>
                <th>หน้ากว้าง</th>
                <th>พับ</th>
                <th>หลา</th>
                <th>refId</th>
              </tr>
            </thead>
            <tbody style="text-align:right">
              @forelse($ins as $r)
                <tr>
                  <td style="text-align:center">
                    <form action="{{ route('stockfabric.destroyIn', $r->id) }}" method="post" onsubmit="return confirm('ลบแถวนี้?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">ลบ</button>
                    </form>
                  </td>
                  <td style="text-align:center">{{ $r->id }}</td>
                  <td>{{ \Carbon\Carbon::parse($r->createDate)->format('d/m/Y') }}</td>
                  <td style="text-align:left">{{ $r->emp }}</td>
                  <td style="text-align:left">{{ $r->customer ?: 'AST' }}</td>
                  <td style="text-align:left">{{ $r->fabricId }}</td>
                  <td style="text-align:left">{{ $r->fabricStruct }}</td>
                  <td style="text-align:left">{{ $r->fabricPattern }}</td>
                  <td style="text-align:left">{{ $r->fabricW }}</td>
                  <td>{{ number_format($r->fold) }}</td>
                  <td>{{ number_format($r->sumYard, 2) }}</td>
                  <td style="text-align:left">{{ $r->refId }}</td>
                </tr>
              @empty
                <tr><td colspan="12" class="text-center text-muted">ไม่พบรายการ</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
