@extends('layouts.astmanufacturing')

@section('content')
<div class="content-wrapper always-show-actions">

  {{-- CSS: ให้ปุ่ม/แอ็กชันแสดงตลอดเวลา (override ธีมที่ซ่อนตอนยังไม่ hover) --}}
  <style>
    /* ส่วนหัว */
    .always-show-actions .header-actions .btn,
    .always-show-actions .header-actions form {
      opacity: 1 !important;
      visibility: visible !important;
      display: inline-block !important;
    }
    .always-show-actions .header-actions form { margin: 0 4px; }

    /* ในตาราง */
    .always-show-actions .table tbody tr td { overflow: visible !important; }
    .always-show-actions .action-cell .row-actions {
      display: inline-flex !important;
      gap: .25rem;
      opacity: 1 !important;
      visibility: visible !important;
    }
    .always-show-actions .action-cell .btn,
    .always-show-actions .action-cell form {
      display: inline-block !important;
      opacity: 1 !important;
      visibility: visible !important;
    }
    .always-show-actions .action-cell form { margin: 0 4px; }

    /* กันเคสธีมที่ใช้ตัวเลือกแบบ hover เพื่อซ่อนปุ่ม */
    .always-show-actions .table-hover tbody tr td .btn,
    .always-show-actions .table-hover tbody tr td .btn-group,
    .always-show-actions .table-hover tbody tr td .row-actions {
      opacity: 1 !important;
      visibility: visible !important;
      display: inline-flex !important;
    }
    .always-show-actions .table-hover tbody tr:hover td .btn,
    .always-show-actions .table-hover tbody tr:hover td .btn-group,
    .always-show-actions .table-hover tbody tr:hover td .row-actions {
      opacity: 1 !important;
      visibility: visible !important;
    }
  </style>

  {{-- Breadcrumb --}}
  <div class="">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">หน้าหลัก</a></li>
      <li class="breadcrumb-item"><a href="{{ route('fabriccheck.index') }}">ตรวจสอบคีย์ผ้าเข้าสต็อก</a></li>
      <li class="breadcrumb-item active">รายละเอียด</li>
    </ol>
  </div>

  {{-- Header --}}
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center header-actions">
      <h1 class="m-0">
        <i class="nav-icon fas fa-search"></i>
        รายละเอียดการคีย์
      </h1>

      <div>
        {{-- ลบทั้งชุด --}}
        <form method="POST" action="{{ route('fabriccheck.destroy', $refId) }}"
              onsubmit="return confirm('ยืนยันลบรายการคีย์ชุดนี้ทั้งหมดหรือไม่?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">
            ลบรายการนี้ทั้งหมด
          </button>
        </form>

        {{-- ปุ่มกลับ --}}
        <a href="{{ route('fabriccheck.index') }}" class="btn btn-outline-secondary btn-sm">กลับ</a>
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

      {{-- สรุปหัวตาราง (ไม่แสดง ref) --}}
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

      {{-- รายการพับทั้งหมด --}}
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
                @foreach ($items as $it)
                  <tr>
                    <td class="text-right">{{ number_format($it->fold) }}</td>
                    <td class="text-nowrap text-center">
                      {{ \Carbon\Carbon::parse($it->createDate ?? $it->created_at)->format('d/m/Y') }}
                    </td>
                    <td class="text-right">{{ number_format($it->sumYard, 2) }}</td>
                    <td>{{ $it->emp }}</td>
                    <td class="text-center action-cell">
                      <div class="row-actions">
                        <form method="POST"
                              action="{{ route('fabriccheck.item.destroy', ['refId' => $refId, 'id' => $it->id]) }}"
                              onsubmit="return confirm('ยืนยันลบพับที่ {{ $it->fold }} หรือไม่?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-outline-danger btn-sm">ลบ</button>
                        </form>
                      </div>
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
