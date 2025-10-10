@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper">
  <div class="">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
      <li class="breadcrumb-item active">ตรวจสอบคีย์ผ้าซื้อเข้าสต็อก</li>
    </ol>
  </div>

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <h1 class="m-0"><i class="nav-icon fas fa fa-arrow-circle-right"></i> ตรวจสอบคีย์ผ้าซื้อเข้าสต็อก</h1>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="box-from">

      @if (session('status'))
        <div class="container mt-3">
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
          </div>
        </div>
      @endif

      <div class="container mt-2">
        <div class="card">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered mb-0">
                <thead class="thead-light">
                  <tr class="text-center">
                    <th style="width:60px;">NO</th>
                    <th>ผู้คีย์</th>
                    <th>ลูกค้า</th>
                    <th>รหัส</th>
                    <th>โครงสร้าง</th>
                    <th>ลายผ้า</th>
                    <th>หน้ากว้าง</th>
                    <th>ผู้ขาย</th>
                    <th>เลขที่บิล</th>
                    <th>SONumber</th>
                    <th>พับ</th>
                    <th>รวม(หลา)</th>
                    <th>ราคา/หลา</th>
                    <th>วันที่คีย์</th>
                    <th style="width:120px;">การดำเนินการ</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($rows as $r)
                    <tr>
                      <td class="text-center">{{ ($rows->currentPage()-1)*$rows->perPage() + $loop->iteration }}</td>
                      <td>{{ $r->emp }}</td>
                      <td>{{ $r->customer }}</td>
                      <td class="text-nowrap">{{ $r->fabricId }}</td>
                      <td>{{ $r->fabricStruct }}</td>
                      <td>{{ $r->fabricPattern }}</td>
                      <td class="text-nowrap">{{ $r->fabricW }}</td>
                      <td>{{ $r->supplier_name }}</td>
                      <td>{{ $r->invoice_no }}</td>
                      <td>{{ $r->SONumber }}</td>
                      <td class="text-right">{{ number_format($r->folds) }}</td>
                      <td class="text-right">{{ number_format($r->yards, 2) }}</td>
                      <td class="text-right">
                        {{ $r->unit_price !== null ? number_format($r->unit_price, 2) : '-' }}
                      </td>
                      <td class="text-nowrap text-center">
                        {{ \Carbon\Carbon::parse($r->key_date)->format('d/m/Y') }}
                      </td>
                      <td class="text-center">
                        <a class="btn btn-primary btn-sm" href="{{ route('fabricimport.check.show', $r->refId) }}">
                          ดูรายละเอียด
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="15" class="text-center text-muted p-4">ยังไม่มีข้อมูล</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="btn-group">
              @if ($rows->onFirstPage())
                <button class="btn btn-outline-secondary btn-sm" disabled>&laquo; ย้อนกลับ</button>
              @else
                <a class="btn btn-outline-secondary btn-sm" href="{{ $rows->previousPageUrl() }}">&laquo; ย้อนกลับ</a>
              @endif

              <span class="mx-2 align-self-center">หน้า {{ $rows->currentPage() }}</span>

              @if ($rows->hasMorePages())
                <a class="btn btn-primary btn-sm" href="{{ $rows->nextPageUrl() }}">ถัดไป &raquo;</a>
              @else
                <button class="btn btn-primary btn-sm" disabled>ถัดไป &raquo;</button>
              @endif
            </div>

            <small class="text-muted">
              แสดง: {{ $rows->count() }} กลุ่ม / หน้า (ล่าสุดก่อน)
            </small>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
