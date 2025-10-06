<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\stockfabric;

class FabriccheckController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // หน้า index: แบ่งหน้า 500 ชุด/หน้า พร้อมปุ่ม ก่อนหน้า/ถัดไป
    public function index(Request $request)
    {
        $perPage = 500;
        $page    = max(1, (int)$request->query('page', 1));

        // จำนวนกลุ่มทั้งหมด (นับ refId ที่ไม่ซ้ำ)
        $totalGroups = DB::table('stockfabrics')->distinct('refId')->count('refId');

        // 1) ดึงรายชื่อ refId สำหรับ "หน้านี้" เรียงจากล่าสุด (MAX(id)) และตัดหน้าเอง
        $refChunk = DB::table('stockfabrics')
            ->select('refId', DB::raw('MAX(id) AS last_id'))
            ->groupBy('refId')
            ->orderByDesc(DB::raw('MAX(id)'))
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        if ($refChunk->isEmpty()) {
            $paginator = new LengthAwarePaginator(
                collect(),
                $totalGroups,
                $perPage,
                $page,
                ['path' => route('fabriccheck.index')]
            );
            return view('fabriccheck.index', ['rows' => $paginator]);
        }

        $refIds     = $refChunk->pluck('refId')->all();
        $lastIdMap  = $refChunk->pluck('last_id', 'refId'); // [refId => last_id]

        // 2) รวมสรุปเฉพาะ ref ของหน้านี้
        $rawSummary = DB::table('stockfabrics as s')
            ->select([
                's.refId',
                DB::raw('COUNT(*)            AS folds'),
                DB::raw('SUM(s.sumYard)      AS yards'),
                DB::raw('MAX(s.createDate)   AS key_date'),
                DB::raw('MAX(s.emp)          AS emp'),
                DB::raw('MAX(s.customer)     AS customer'),
                DB::raw('MAX(s.fabricId)     AS fabricId'),
                DB::raw('MAX(s.fabricStruct) AS fabricStruct'),
                DB::raw('MAX(s.fabricPattern)AS fabricPattern'),
                DB::raw('MAX(s.fabricW)      AS fabricW'),
            ])
            ->whereIn('s.refId', $refIds)
            ->groupBy('s.refId')
            ->get();

        // เรียงผลรวมตาม last_id ให้ตรงกับลำดับใหม่→เก่า
        $rows = $rawSummary->map(function ($row) use ($lastIdMap) {
                $row->last_id = $lastIdMap[$row->refId] ?? null;
                return $row;
            })
            ->sortByDesc('last_id')
            ->values();

        // สร้าง paginator ด้วยมือ (จะมี previous/next page URL)
        $paginator = new LengthAwarePaginator(
            $rows,
            $totalGroups,
            $perPage,
            $page,
            ['path' => route('fabriccheck.index')]
        );

        return view('fabriccheck.index', ['rows' => $paginator]);
    }

    // รายละเอียด (ยังไม่แสดง ref บน UI)
    public function show($fabriccheck)
    {
        $refId = $fabriccheck;

        $items = stockfabric::where('refId', $refId)
            ->orderByRaw('CAST(fold AS UNSIGNED) ASC') // เรียงพับที่แบบตัวเลข
            ->get();

        if ($items->isEmpty()) {
            abort(404, 'ไม่พบข้อมูลชุดนี้');
        }

        $first  = $items->first();
        $header = (object)[
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

        return view('fabriccheck.show', compact('header', 'items', 'refId'));
    }

    // ลบ “รายการเดี่ยว”
    public function destroyItem(Request $request, $refId, $id)
    {
        $row = stockfabric::where('refId', $refId)->where('id', $id)->firstOrFail();
        $row->delete();

        return redirect()
            ->route('fabriccheck.show', $refId)
            ->with('status', "ลบพับที่ {$row->fold} เรียบร้อย");
    }

    // ลบ “ทั้งชุด” (resource:destroy)
    public function destroy($fabriccheck)
    {
        $refId = $fabriccheck;

        $count = stockfabric::where('refId', $refId)->count();
        stockfabric::where('refId', $refId)->delete();

        return redirect()
            ->route('fabriccheck.index')
            ->with('status', "ลบข้อมูลทั้งชุด จำนวน {$count} แถวเรียบร้อย");
    }
}
