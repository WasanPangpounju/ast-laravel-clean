@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0">
        <i class="nav-icon fas fa-search"></i>
        รายละเอียดการคีย์ — Ref: <span class="text-monospace">{{ $header->refId }}</span>
      </h1>
      <a href="{{ route('fabriccheck.index') }}" class="btn btn-outline-secondary btn-sm">ย้อนกลับ</a>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">

      {{-- สรุปหัวตาราง --}}
      <div class="card mb-3">
        <div class="card-body">
          <div class="row">
            <div class="col-md-3"><strong>ผู้คีย์:</strong> {{ $header->emp }}</div>
            <div class="col-md-3"><strong>ลูกค้า:</strong> {{ $header->customer }}</div>
            <div class="col-md-6">
              <strong>ผ้า:</strong>
              {{ $header->fabricId }} / {{ $header->fabricStruct }} / {{ $header->fabricPattern }} / {{ $header->fabricW }}
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-3"><strong>รวมพับ:</strong> {{ number_format($header->folds) }}</div>
            <div class="col-md-3"><strong>รวม(หลา):</strong> {{ number_format($header->yards, 2) }}</div>
            <div class="col-md-6">
              <strong>วันที่คีย์:</strong>
              {{ \Carbon\Carbon::parse($header->key_date)->format('d/m/Y') }}
            </div>
          </div>
        </div>
      </div>

      {{-- รายการพับทั้งหมดของ Ref --}}
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
                </tr>
              </thead>
              <tbody>
                @foreach ($items as $it)
                  <tr>
                    <td class="text-right">{{ number_format($it->fold) }}</td>
                    <td class="text-nowrap text-center">
                      {{ \Carbon\Carbon::parse($it->createDate ?? $it->created_at)->format('d/m/Y') }}
                    </td>
                    <td class="text-right">{{ number_format($it->sumYard, 2) }}</td>
                    <td>{{ $it->emp }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
