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

    // สรุปตาม Ref — 500 กลุ่มล่าสุด/หน้า (คิวรีเร็ว + paginate)
    public function index(Request $request)
    {
        // เลือก 500 ref ล่าสุดก่อน
        $lastRefs = DB::table('stockfabrics')
            ->select('refId', DB::raw('MAX(id) AS last_id'))
            ->groupBy('refId')
            ->orderByDesc(DB::raw('MAX(id)'))
            ->limit(500);

        // รวมสรุปเฉพาะ 500 ref ล่าสุดนั้น
        $summary = DB::table('stockfabrics as s')
            ->joinSub($lastRefs, 'r', function ($j) {
                $j->on('s.refId', '=', 'r.refId');
            })
            ->select([
                's.refId',
                DB::raw('COUNT(*)              AS folds'),
                DB::raw('SUM(s.sumYard)        AS yards'),
                DB::raw('MAX(s.createDate)     AS key_date'),
                DB::raw('MAX(s.emp)            AS emp'),
                DB::raw('MAX(s.customer)       AS customer'),
                DB::raw('MAX(s.fabricId)       AS fabricId'),
                DB::raw('MAX(s.fabricStruct)   AS fabricStruct'),
                DB::raw('MAX(s.fabricPattern)  AS fabricPattern'),
                DB::raw('MAX(s.fabricW)        AS fabricW'),
                DB::raw('MAX(r.last_id)        AS last_id'),
            ])
            ->groupBy('s.refId', 'r.last_id')
            ->orderByDesc('r.last_id')
            ->paginate(500);

        return view('fabriccheck.index', ['rows' => $summary]);
    }

    // รายละเอียดตาม Ref
    public function show($fabriccheck)
    {
        $refId = $fabriccheck;

        $items = stockfabric::where('refId', $refId)
            // ✅ เรียงพับที่แบบตัวเลข (กัน 1,10,11,...,2,3)
            ->orderByRaw('CAST(fold AS UNSIGNED) ASC')
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

    // ลบ “รายแถว”
    public function destroyItem(Request $request, $refId, $id)
    {
        $row = stockfabric::where('refId', $refId)->where('id', $id)->firstOrFail();
        $row->delete();

        return redirect()
            ->route('fabriccheck.show', $refId)
            ->with('status', "ลบพับที่ {$row->fold} (ID:{$row->id}) เรียบร้อย");
    }

    // ลบ “ทั้ง ref” (resource:destroy)
    public function destroy($fabriccheck)
    {
        $refId = $fabriccheck;

        $count = stockfabric::where('refId', $refId)->count();
        stockfabric::where('refId', $refId)->delete();

        return redirect()
            ->route('fabriccheck.index')
            ->with('status', "ลบข้อมูลทั้ง Ref {$refId} จำนวน {$count} แถวเรียบร้อย");
    }
}
