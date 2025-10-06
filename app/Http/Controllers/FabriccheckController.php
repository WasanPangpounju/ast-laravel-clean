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

    // 🧾 หน้าแสดงสรุปตาม Ref
    public function index(Request $request)
    {
        $summary = stockfabric::select([
                'refId',
                DB::raw('COUNT(*)        AS folds'),
                DB::raw('SUM(sumYard)    AS yards'),
                DB::raw('MAX(createDate) AS key_date'),
                DB::raw('MAX(id)         AS last_id'),
                DB::raw('MAX(emp)        AS emp'),
                DB::raw('MAX(customer)   AS customer'),
                DB::raw('MAX(fabricId)   AS fabricId'),
                DB::raw('MAX(fabricStruct)   AS fabricStruct'),
                DB::raw('MAX(fabricPattern)  AS fabricPattern'),
                DB::raw('MAX(fabricW)    AS fabricW'),
            ])
            ->groupBy('refId')
            ->orderByDesc('last_id')
            ->cursorPaginate(500);

        return view('fabriccheck.index', ['rows' => $summary]);
    }

    // 📄 หน้าแสดงรายละเอียดของแต่ละ Ref
    public function show($refId)
    {
        $items = stockfabric::where('refId', $refId)
            ->orderBy('fold')
            ->get();

        if ($items->isEmpty()) {
            abort(404, 'ไม่พบข้อมูล Ref นี้');
        }

        $header = (object)[
            'refId'         => $refId,
            'emp'           => $items->first()->emp,
            'customer'      => $items->first()->customer,
            'fabricId'      => $items->first()->fabricId,
            'fabricStruct'  => $items->first()->fabricStruct,
            'fabricPattern' => $items->first()->fabricPattern,
            'fabricW'       => $items->first()->fabricW,
            'folds'         => $items->count(),
            'yards'         => $items->sum('sumYard'),
            'key_date'      => $items->first()->createDate ?? $items->first()->created_at,
        ];

        return view('fabriccheck.show', compact('header', 'items'));
    }
}
