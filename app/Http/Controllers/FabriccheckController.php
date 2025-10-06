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

    public function show($refId)
    {
        $items = stockfabric::where('refId', $refId)
            ->orderBy('fold', 'asc') // เรียงลำดับพับที่
            ->get();

        if ($items->isEmpty()) {
            abort(404, 'ไม่พบข้อมูล Ref นี้');
        }

        $first = $items->first();
        $header = (object)[
            'refId'         => $refId,
            'emp'           => $first->emp,
            'customer'      => $first->customer,
            'fabricId'      => $first->fabricId,
            'fabricStruct'  => $first->fabricStruct,
            'fabricPattern' => $first->fabricPattern,
            'fabricW'       => $first->fabricW,
            'folds'         => $items->count(),
            'yards'         => $items->sum('sumYard'),
            'key_date'      => $first->createDate ?? $first->created_at,
        ];

        return view('fabriccheck.show', compact('header', 'items'));
    }

    // ลบ “รายการเดี่ยว”
    public function destroyItem(Request $request, $refId, $id)
    {
        $row = stockfabric::where('refId', $refId)->where('id', $id)->firstOrFail();
        $row->delete();

        return redirect()
            ->route('fabriccheck.show', $refId)
            ->with('status', "ลบพับที่ {$row->fold} (ID:{$row->id}) เรียบร้อย");
    }

    // ลบ “ทั้ง ref”
    public function destroyRef(Request $request, $refId)
    {
        // (ทางเลือก) ตรวจสิทธิ์ก่อนลบทั้งหมด
        // $this->authorize('delete-all-stockfabric');

        $count = stockfabric::where('refId', $refId)->count();
        stockfabric::where('refId', $refId)->delete();

        return redirect()
            ->route('fabriccheck.index')
            ->with('status', "ลบข้อมูลทั้ง Ref {$refId} จำนวน {$count} แถวเรียบร้อย");
    }
}
