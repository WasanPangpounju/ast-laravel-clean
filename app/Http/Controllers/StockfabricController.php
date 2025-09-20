<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\stockfabric;
use App\Models\fabricout;

class StockfabricController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // ---------- ดึงข้อมูลสต็อกผ้าเข้า (IN) ----------
        $stockIns = \DB::table('stockfabrics')
            ->selectRaw("
                COALESCE(NULLIF(TRIM(customer), ''), 'AST') AS customer,
                fabricStruct,
                fabricPattern,
                fabricW,
                COUNT(fold) as foldCountIn,
                SUM(sumYard) as sumYardIn
            ")
            ->groupBy('customer', 'fabricStruct', 'fabricPattern', 'fabricW')
            ->get();

        // ---------- ดึงข้อมูลสต็อกผ้าออก (OUT) ----------
        $stockOuts = \DB::table('fabricouts')
            ->selectRaw("
                COALESCE(NULLIF(TRIM(customerName), ''), 'AST') AS customer,
                fabricStruct,
                fabricPattern,
                fabricW,
                COUNT(fold) as foldCountOut,
                SUM(sumYard) as sumYardOut
            ")
            ->groupBy('customer', 'fabricStruct', 'fabricPattern', 'fabricW')
            ->get();

        // ---------- รวมข้อมูลทั้งสองเป็นตารางเดียวและคำนวณยอดคงเหลือ ----------
        $combinedData = $stockIns->map(function ($in) use ($stockOuts) {
            $out = $stockOuts->first(function ($o) use ($in) {
                return $o->customer == $in->customer &&
                       $o->fabricStruct == $in->fabricStruct &&
                       $o->fabricPattern == $in->fabricPattern &&
                       $o->fabricW == $in->fabricW;
            });

            // สร้าง Object สำหรับแถวในตาราง
            return (object)[
                'customer' => $in->customer,
                'fabricStruct' => $in->fabricStruct,
                'fabricPattern' => $in->fabricPattern,
                'fabricW' => $in->fabricW,
                'foldCountIn' => $in->foldCountIn,
                'sumYardIn' => $in->sumYardIn,
                'foldCountOut' => $out ? $out->foldCountOut : 0,
                'sumYardOut' => $out ? $out->sumYardOut : 0,
                'foldCountRemaining' => $in->foldCountIn - ($out ? $out->foldCountOut : 0),
                'sumYardRemaining' => $in->sumYardIn - ($out ? $out->sumYardOut : 0),
            ];
        });

        // ส่งข้อมูลที่ประมวลผลแล้วไปยัง View
        return view('stockfabric.index', compact('combinedData'));
    }

    /**
     * Store a newly created resource in storage.
     * ...
     */
     public function store(Request $request)
     {
         // โค้ดส่วนนี้ไม่ได้แก้ไข
         if ($request->filled('submit') && $request->submit == 'searchImport') {
             $importorder = StockFabric::where('fabricStruct', 'LIKE', '%' . $request->fabricStruct . '%')
                 ->groupBy(['fabricId','fabricStruct', 'fabricPattern', 'fabricW', 'createDate', 'customer','fabricId'])
                 ->selectRaw('customer,fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum,createDate')
                 ->orderBy('createDate', 'desc')
                 ->get();

             $record2 = fabricout::groupBy(['fabricStruct', 'fabricPattern', 'fabricW'])
                 ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                 ->get();
             // ส่งข้อมูลที่ประมวลผลแล้วไปยัง View
             return view('stockfabric.index', compact('importorder', 'sumFabricout'));
         }
         // ...
     }
}