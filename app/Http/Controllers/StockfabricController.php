<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockfabricController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // หน้าแรก: ยังไม่ดึงทั้งตาราง เพื่อกัน timeout
    public function index()
    {
        $combinedData = [];
        return view('stockfabric.index', compact('combinedData'));
    }

    public function store(Request $request)
    {
        if (!($request->filled('submit') && $request->submit === 'searchImport')) {
            return redirect()->route('stockfabric.index');
        }

        // รับค่าค้นหา
        $customer      = trim((string) $request->input('customer', ''));
        $fabricId      = trim((string) $request->input('fabricId', ''));
        $fabricStruct  = trim((string) $request->input('fabricStruct', ''));
        $fabricPattern = trim((string) $request->input('fabricPattern', ''));
        $fabricW       = trim((string) $request->input('fabricW', ''));

        // ต้องมีเงื่อนไขอย่างน้อย 1
        if ($customer === '' && $fabricId === '' && $fabricStruct === '' && $fabricPattern === '' && $fabricW === '') {
            return redirect()->route('stockfabric.index')->with('warn','กรุณาใส่เงื่อนไขอย่างน้อย 1 ช่อง');
        }

        // ---------- สร้างคิวรีฝั่ง STOCK-IN (stockfabrics) ----------
        $inBase = DB::table('stockfabrics as s');

        if ($customer !== '') {
            if (strtoupper($customer) === 'AST') {
                // ว่าง/NULL/AST → ถือเป็น AST
                $inBase->where(function ($q) {
                    $q->whereNull('s.customer')
                      ->orWhere('s.customer', '')
                      ->orWhere('s.customer', 'AST');
                });
            } else {
                $inBase->where('s.customer', $customer);
            }
        }
        if ($fabricId !== '')      $inBase->where('s.fabricId', $fabricId);
        if ($fabricStruct !== '')  $inBase->where('s.fabricStruct', $fabricStruct);
        if ($fabricPattern !== '') $inBase->where('s.fabricPattern', $fabricPattern);
        if ($fabricW !== '')       $inBase->where('s.fabricW', $fabricW);

        // สรุปยอด IN (กลุ่มกุญแจปกติ + normalize)
        $inAgg = DB::query()->fromSub(
            $inBase->selectRaw("
                COALESCE(NULLIF(TRIM(s.customer), ''), 'AST') AS customer,
                s.fabricId                                    AS fabricId,
                TRIM(s.fabricStruct)                          AS fabricStruct,
                TRIM(s.fabricPattern)                         AS fabricPattern,
                TRIM(s.fabricW)                               AS fabricW,
                COUNT(*)                                      AS foldCountIn,
                SUM(s.sumYard)                                AS sumYardIn
            ")->groupBy('customer','fabricId','fabricStruct','fabricPattern','fabricW'),
            'ins'
        );

        // ---------- สร้างคิวรีฝั่ง STOCK-OUT (fabricouts) ----------
        $outBase = DB::table('fabricouts as o');

        // กรองด้วยหลัก “ใช้ค่าจาก stock* ถ้ามี ไม่งั้น fallback ไปคอลัมน์เดิม”
        if ($customer !== '') {
            if (strtoupper($customer) === 'AST') {
                $outBase->where(function ($q) {
                    $q->whereNull('o.stockCustomer')->orWhere('o.stockCustomer', '')
                      ->orWhere('o.customerName', '')->orWhereNull('o.customerName')
                      ->orWhere('o.stockCustomer', 'AST')
                      ->orWhere('o.customerName', 'AST');
                });
            } else {
                $outBase->where(function ($q) use ($customer) {
                    $q->where('o.stockCustomer', $customer)
                      ->orWhere('o.customerName', $customer);
                });
            }
        }
        if ($fabricStruct !== '') {
            $outBase->where(function ($q) use ($fabricStruct) {
                $q->where('o.stockFabricStruct', $fabricStruct)
                  ->orWhere('o.fabricStruct', $fabricStruct);
            });
        }
        if ($fabricPattern !== '') {
            $outBase->where(function ($q) use ($fabricPattern) {
                $q->where('o.stockFabricPattern', $fabricPattern)
                  ->orWhere('o.fabricPattern', $fabricPattern);
            });
        }
        if ($fabricW !== '') {
            $outBase->where(function ($q) use ($fabricW) {
                $q->where('o.stockFabricW', $fabricW)
                  ->orWhere('o.fabricW', $fabricW);
            });
        }

        // สรุปยอด OUT (normalize เหมือนฝั่ง IN)
        $outAgg = DB::query()->fromSub(
            $outBase->selectRaw("
                COALESCE(
                    NULLIF(TRIM(CASE WHEN o.stockCustomer IS NULL OR o.stockCustomer='' THEN o.customerName ELSE o.stockCustomer END), ''),
                    'AST'
                ) AS customer,
                TRIM(CASE WHEN o.stockFabricStruct  IS NULL OR o.stockFabricStruct  = '' THEN o.fabricStruct  ELSE o.stockFabricStruct  END) AS fabricStruct,
                TRIM(CASE WHEN o.stockFabricPattern IS NULL OR o.stockFabricPattern = '' THEN o.fabricPattern ELSE o.stockFabricPattern END) AS fabricPattern,
                TRIM(CASE WHEN o.stockFabricW       IS NULL OR o.stockFabricW       = '' THEN o.fabricW       ELSE o.stockFabricW       END) AS fabricW,
                COUNT(*) AS foldCountOut,
                SUM(o.sumYard) AS sumYardOut
            ")->groupBy('customer','fabricStruct','fabricPattern','fabricW'),
            'outs'
        );

        // ---------- JOIN สองฝั่งเข้าด้วยกัน ----------
        // หมายเหตุ: ฝั่ง OUT ไม่มีกลุ่มตาม fabricId จึง join ตาม customer+struct+pattern+width
        $rows = $inAgg
            ->leftJoinSub($outAgg, 'outs', function ($j) {
                $j->on('outs.customer',     '=', 'ins.customer')
                  ->on('outs.fabricStruct',  '=', 'ins.fabricStruct')
                  ->on('outs.fabricPattern', '=', 'ins.fabricPattern')
                  ->on('outs.fabricW',       '=', 'ins.fabricW');
            })
            ->selectRaw("
                ins.customer, ins.fabricId, ins.fabricStruct, ins.fabricPattern, ins.fabricW,
                ins.foldCountIn, ins.sumYardIn,
                COALESCE(outs.foldCountOut,0) AS foldCountOut,
                COALESCE(outs.sumYardOut,0)   AS sumYardOut,
                (ins.foldCountIn - COALESCE(outs.foldCountOut,0)) AS foldCountRemaining,
                (ins.sumYardIn  - COALESCE(outs.sumYardOut ,0))   AS sumYardRemaining
            ")
            ->orderBy('ins.customer')
            ->orderBy('ins.fabricStruct')
            ->orderBy('ins.fabricPattern')
            ->orderBy('ins.fabricW')
            ->limit(500) // กันผลลัพธ์ล้น
            ->get();

        $combinedData = $rows;

        return view('stockfabric.index', compact('combinedData'));
    }
}
