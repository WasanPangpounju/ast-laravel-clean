<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\stockfabric;

class FabriccheckController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // หน้า “สรุปตาม Ref” — 500 กลุ่มล่าสุด/ครั้ง (cursor pagination)
    public function index(Request $request)
    {
        // สรุปตาม refId พร้อมรวมพับและหลา และดึง field ที่ช่วย preview (เลือก MAX/MIN แบบเร็ว)
        $summary = stockfabric::select([
                'refId',
                DB::raw('COUNT(*)                       AS folds'),
                DB::raw('SUM(sumYard)                  AS yards'),
                DB::raw('MIN(createDate)               AS first_date'),
                DB::raw('MAX(createDate)               AS last_date'),
                DB::raw('MAX(id)                       AS last_id'),
                DB::raw('MAX(emp)                      AS emp'),
                DB::raw('MAX(fabricId)                 AS fabricId'),
                DB::raw('MAX(fabricStruct)             AS fabricStruct'),
                DB::raw('MAX(fabricPattern)            AS fabricPattern'),
                DB::raw('MAX(fabricW)                  AS fabricW'),
                DB::raw('MAX(customer)                 AS customer'),
            ])
            ->groupBy('refId')
            ->orderByDesc('last_id')  // ล่าสุดก่อน
            ->cursorPaginate(500);

        return view('fabriccheck.index', [
            'rows' => $summary,
        ]);
    }

    // หน้า “รายละเอียดตาม Ref”
    public function show(string $refId)
    {
        $items = stockfabric::where('refId', $refId)
            ->orderBy('fold') // พับเรียงสวย ๆ
            ->get();

        // สรุปหัวตาราง (header)
        $header = stockfabric::where('refId', $refId)
            ->select([
                'refId',
                DB::raw('COUNT(*)       AS folds'),
                DB::raw('SUM(sumYard)    AS yards'),
                DB::raw('MIN(createDate) AS first_date'),
                DB::raw('MAX(createDate) AS last_date'),
                DB::raw('MAX(emp)        AS emp'),
                DB::raw('MAX(fabricId)   AS fabricId'),
                DB::raw('MAX(fabricStruct) AS fabricStruct'),
                DB::raw('MAX(fabricPattern) AS fabricPattern'),
                DB::raw('MAX(fabricW)    AS fabricW'),
                DB::raw('MAX(customer)   AS customer'),
            ])
            ->first();

        abort_if(!$header, 404);

        return view('fabriccheck.show', [
            'header' => $header,
            'items'  => $items,
        ]);
    }
}
