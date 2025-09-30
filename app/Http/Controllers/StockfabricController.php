<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockfabricController extends Controller
{
    // --- หน้าแรก: ไม่คิวรีหนัก ---
    public function index(Request $request)
    {
        // เปิดหน้าเร็ว ๆ ก่อน ยังไม่ดึงข้อมูล
        return view('stockfabric.index', [
            'combinedData' => collect(),  // ว่าง = "กรุณาค้นหา"
        ]);
    }

    // --- ค้นหา: ยิงคิวรีเมื่อมีเงื่อนไขเท่านั้น ---
    public function store(Request $request)
    {
        // กัน timeout (กันเหนียว)
        @set_time_limit(0);
        @ini_set('max_execution_time', '0');

        if (!($request->filled('submit') && $request->submit === 'searchImport')) {
            return redirect()->route('stockfabric.index');
        }

        // รับเงื่อนไขจากฟอร์ม
        $customer      = trim((string) $request->input('customer', ''));
        $fabricId      = trim((string) $request->input('fabricId', ''));
        $fabricStruct  = trim((string) $request->input('fabricStruct', ''));
        $fabricPattern = trim((string) $request->input('fabricPattern', ''));
        $fabricW       = trim((string) $request->input('fabricW', ''));

        // ต้องมีอย่างน้อย 1 เงื่อนไข เพื่อหลีกเลี่ยง full-scan
        if ($customer === '' && $fabricId === '' && $fabricStruct === '' && $fabricPattern === '' && $fabricW === '') {
            return redirect()->route('stockfabric.index')->with('warn', 'กรุณาใส่เงื่อนไขอย่างน้อย 1 อย่าง');
        }

        /* -------------------------
         *  IN: stockfabrics (ผลิตแล้ว)
         *  ชั้นแรก where ด้วยคอลัมน์ตรง ๆ เพื่อใช้ index ให้คุ้ม
         * ------------------------- */
        $preIn = DB::table('stockfabrics as s');

        if ($customer !== '') {
            if (strtoupper($customer) === 'AST') {
                // AST = NULL/ว่าง/'AST'
                $preIn->where(function ($q) {
                    $q->whereNull('s.customer')->orWhere('s.customer', '')->orWhere('s.customer', 'AST');
                });
            } else {
                $preIn->where('s.customer', $customer);
            }
        }
        if ($fabricId      !== '') $preIn->where('s.fabricId',      $fabricId);
        if ($fabricStruct  !== '') $preIn->where('s.fabricStruct',  $fabricStruct);
        if ($fabricPattern !== '') $preIn->where('s.fabricPattern', $fabricPattern);
        if ($fabricW       !== '') $preIn->where('s.fabricW',       $fabricW);

        // ทำ normalization ใน subquery แล้วค่อย group ชั้นนอก (เลี่ยง ONLY_FULL_GROUP_BY)
        $baseIn = $preIn->selectRaw("
            COALESCE(NULLIF(TRIM(s.customer), ''), 'AST') AS customer,
            s.fabricId                                    AS fabricId,
            TRIM(s.fabricStruct)                          AS fabricStruct,
            TRIM(s.fabricPattern)                         AS fabricPattern,
            TRIM(s.fabricW)                               AS fabricW,
            s.sumYard                                     AS sumYard
        ");

        $inAgg = DB::query()->fromSub($baseIn, 't')
            ->selectRaw("
                customer, fabricId, fabricStruct, fabricPattern, fabricW,
                COUNT(*) AS foldCountIn,
                SUM(sumYard) AS sumYardIn
            ")
            ->groupBy('customer','fabricId','fabricStruct','fabricPattern','fabricW');

        /* -------------------------
         *  OUT: fabricouts (ใช้ไป)
         *  ฟิลด์มีทั้ง stock* และฟอลแบ็กไป field เดิม => normalize ใน subquery เช่นกัน
         *  ใส่ where ตามเงื่อนไขเท่าที่ทำได้ โดยไม่ใช้ฟังก์ชันใน WHERE เพื่อพยายามใช้ index
         * ------------------------- */
        $preOut = DB::table('fabricouts as f');

        if ($customer !== '') {
            if (strtoupper($customer) === 'AST') {
                $preOut->where(function ($q) {
                    $q->whereNull('f.stockCustomer')->orWhere('f.stockCustomer', '')->orWhere('f.stockCustomer', 'AST')
                      ->orWhere('f.customerName', 'AST');
                });
            } else {
                $preOut->where(function ($q) use ($customer) {
                    $q->where('f.stockCustomer', $customer)
                      ->orWhere(function ($qq) use ($customer) {
                          $qq->whereNull('f.stockCustomer')->orWhere('f.stockCustomer','')->where('f.customerName', $customer);
                      });
                });
            }
        }
        if ($fabricId !== '') {
            $preOut->where(function ($q) use ($fabricId) {
                $q->where('f.fabricId', $fabricId);
            });
        }
        if ($fabricStruct !== '') {
            $preOut->where(function ($q) use ($fabricStruct) {
                $q->where('f.stockFabricStruct', $fabricStruct)
                  ->orWhere(function ($qq) use ($fabricStruct) {
                      $qq->whereNull('f.stockFabricStruct')->orWhere('f.stockFabricStruct','')->where('f.fabricStruct', $fabricStruct);
                  });
            });
        }
        if ($fabricPattern !== '') {
            $preOut->where(function ($q) use ($fabricPattern) {
                $q->where('f.stockFabricPattern', $fabricPattern)
                  ->orWhere(function ($qq) use ($fabricPattern) {
                      $qq->whereNull('f.stockFabricPattern')->orWhere('f.stockFabricPattern','')->where('f.fabricPattern', $fabricPattern);
                  });
            });
        }
        if ($fabricW !== '') {
            $preOut->where(function ($q) use ($fabricW) {
                $q->where('f.stockFabricW', $fabricW)
                  ->orWhere(function ($qq) use ($fabricW) {
                      $qq->whereNull('f.stockFabricW')->orWhere('f.stockFabricW','')->where('f.fabricW', $fabricW);
                  });
            });
        }

        $baseOut = $preOut->selectRaw("
            COALESCE(NULLIF(TRIM(CASE WHEN f.stockCustomer IS NULL OR f.stockCustomer = '' THEN f.customerName ELSE f.stockCustomer END), ''), 'AST') AS customer,
            f.fabricId AS fabricId,
            TRIM(CASE WHEN f.stockFabricStruct  IS NULL OR f.stockFabricStruct  = '' THEN f.fabricStruct  ELSE f.stockFabricStruct  END) AS fabricStruct,
            TRIM(CASE WHEN f.stockFabricPattern IS NULL OR f.stockFabricPattern = '' THEN f.fabricPattern ELSE f.stockFabricPattern END) AS fabricPattern,
            TRIM(CASE WHEN f.stockFabricW       IS NULL OR f.stockFabricW       = '' THEN f.fabricW       ELSE f.stockFabricW       END) AS fabricW,
            f.sumYard AS sumYard,
            f.fold    AS fold
        ");

        $outAgg = DB::query()->fromSub($baseOut, 't')
            ->selectRaw("
                customer, fabricId, fabricStruct, fabricPattern, fabricW,
                COUNT(fold) AS foldCountOut,
                SUM(sumYard) AS sumYardOut
            ")
            ->groupBy('customer','fabricId','fabricStruct','fabricPattern','fabricW');

        /* -------------------------
         *  รวม IN/OUT แล้วคำนวณคงเหลือ
         * ------------------------- */
        $combined = DB::query()
            ->fromSub($inAgg, 'si')
            ->leftJoinSub($outAgg, 'so', function ($join) {
                $join->on('si.customer',      '=', 'so.customer')
                     ->on('si.fabricId',      '=', 'so.fabricId')
                     ->on('si.fabricStruct',  '=', 'so.fabricStruct')
                     ->on('si.fabricPattern', '=', 'so.fabricPattern')
                     ->on('si.fabricW',       '=', 'so.fabricW');
            })
            ->selectRaw("
                si.customer,
                si.fabricStruct,
                si.fabricId,
                si.fabricPattern,
                si.fabricW,
                si.foldCountIn,
                si.sumYardIn,
                COALESCE(so.foldCountOut, 0) AS foldCountOut,
                COALESCE(so.sumYardOut,  0) AS sumYardOut,
                (si.foldCountIn - COALESCE(so.foldCountOut, 0)) AS foldCountRemaining,
                (si.sumYardIn  - COALESCE(so.sumYardOut,  0)) AS sumYardRemaining
            ")
            ->orderBy('si.customer')
            ->orderBy('si.fabricStruct')
            ->limit(200);  // กันล้นหน้าจอ

        $combinedData = $combined->get();

        return view('stockfabric.index', compact('combinedData'));
    }
}
