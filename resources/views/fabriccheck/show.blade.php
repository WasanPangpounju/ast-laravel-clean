@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0">
        <i class="nav-icon fas fa-search"></i>
        รายละเอียดการคีย์ — Ref: <span class="text-monospace">{{ $header->refId }}</span>
      </h1>

      <div class="d-flex gap-2">
        {{-- ลบทั้ง ref (ใช้ resource destroy) --}}
        <form method="POST" action="{{ route('fabriccheck.destroy', $header->refId) }}"
              onsubmit="return confirm('ยืนยันลบรายการคีย์ของ Ref นี้ทั้งหมดหรือไม่?');" class="mr-2">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">
            ลบรายการนี้ทั้งหมด
          </button>
        </form>

        <a href="{{ route('fabriccheck.index') }}" class="btn btn-outline-secondary btn-sm">กลับ</a>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">

      @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('status') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

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
              <strong>วันที่คีย์:</strong> {{ \Carbon\Carbon::parse($header->key_date)->format('d/m/Y') }}
            </div>
          </div>
        </div>
      </div>

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
                  <th style="width: 110px;">การดำเนินการ</th>
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
                    <td class="text-center">
                      <form method="POST"
                            action="{{ route('fabriccheck.item.destroy', ['refId' => $header->refId, 'id' => $it->id]) }}"
                            onsubmit="return confirm('ยืนยันลบพับที่ {{ $it->fold }} (ID:{{ $it->id }}) หรือไม่?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">ลบ</button>
                      </form>
                    </td>
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
