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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get combined data from both tables for initial view
        $combinedData = $this->getCombinedData();
        
        return view('stockfabric.index', compact('combinedData'));
    }

    /**
     * Store a newly created resource in storage (used for search).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // searchImport
        if ($request->filled('submit') && $request->submit == 'searchImport') {
            
            // Get combined data filtered by search criteria
            $combinedData = $this->getCombinedData(
                $request->get('customer'),
                $request->get('fabricStruct'),
                $request->get('fabricId'),
                $request->get('fabricPattern'),
                $request->get('fabricW')
            );

            return view('stockfabric.index', compact('combinedData'));
        }
    }

    /**
     * Helper method to get and combine data.
     *
     * @param string|null $customer
     * @param string|null $fabricStruct
     * @param string|null $fabricId
     * @param string|null $fabricPattern
     * @param string|null $fabricW
     * @return \Illuminate\Support\Collection
     */
private function getCombinedData($customer = null, $fabricStruct = null, $fabricId = null, $fabricPattern = null, $fabricW = null)
{
    /* ---------- 1) STOCK-IN: สร้าง subquery ให้ได้คีย์ normalize แล้วค่อย group ---------- */
    $insSub = \DB::table('stockfabrics')
        ->selectRaw("
            COALESCE(NULLIF(TRIM(customer), ''), 'AST') AS customer,
            TRIM(fabricStruct)  AS fabricStruct,
            TRIM(fabricPattern) AS fabricPattern,
            TRIM(fabricW)       AS fabricW,
            fabricId,
            fold,
            sumYard
        ");

    $stockInsQuery = \DB::query()
        ->fromSub($insSub, 'si')
        ->selectRaw("
            customer, fabricId, fabricStruct, fabricPattern, fabricW,
            COUNT(*)   AS foldCountIn,
            SUM(sumYard) AS sumYardIn
        ")
        ->groupBy('customer','fabricId','fabricStruct','fabricPattern','fabricW');

    // ฟิลเตอร์บนคอลัมน์ normalize แล้ว
    if ($customer)      $stockInsQuery->where('customer',      'like', '%'.$customer.'%');
    if ($fabricStruct)  $stockInsQuery->where('fabricStruct',  'like', '%'.$fabricStruct.'%');
    if ($fabricId)      $stockInsQuery->where('fabricId',      'like', '%'.$fabricId.'%');
    if ($fabricPattern) $stockInsQuery->where('fabricPattern', 'like', '%'.$fabricPattern.'%');
    if ($fabricW)       $stockInsQuery->where('fabricW',       'like', '%'.$fabricW.'%');

    $stockIns = $stockInsQuery->get();

    /* ---------- 2) STOCK-OUT: เลือกใช้ stock* ถ้ามี ไม่งั้น fallback ไปคอลัมน์เก่า แล้ว group ---------- */
    $outsSub = \DB::table('fabricouts')
        ->selectRaw("
            COALESCE(NULLIF(TRIM(CASE
                WHEN stockCustomer IS NULL OR stockCustomer = '' THEN customerName
                ELSE stockCustomer END), ''), 'AST') AS customer,
            TRIM(CASE
                WHEN stockFabricStruct IS NULL  OR stockFabricStruct  = '' THEN fabricStruct
                ELSE stockFabricStruct END)  AS fabricStruct,
            TRIM(CASE
                WHEN stockFabricPattern IS NULL OR stockFabricPattern = '' THEN fabricPattern
                ELSE stockFabricPattern END) AS fabricPattern,
            TRIM(CASE
                WHEN stockFabricW IS NULL       OR stockFabricW       = '' THEN fabricW
                ELSE stockFabricW END)         AS fabricW,
            fold,
            sumYard
        ");

    $stockOutsQuery = \DB::query()
        ->fromSub($outsSub, 'fo')
        ->selectRaw("
            customer, fabricStruct, fabricPattern, fabricW,
            COUNT(*)    AS foldCountOut,
            SUM(sumYard) AS sumYardOut
        ")
        ->groupBy('customer','fabricStruct','fabricPattern','fabricW');

    // ฟิลเตอร์บนคอลัมน์ normalize แล้ว
    if ($customer)      $stockOutsQuery->where('customer',      'like', '%'.$customer.'%');
    if ($fabricStruct)  $stockOutsQuery->where('fabricStruct',  'like', '%'.$fabricStruct.'%');
    if ($fabricPattern) $stockOutsQuery->where('fabricPattern', 'like', '%'.$fabricPattern.'%');
    if ($fabricW)       $stockOutsQuery->where('fabricW',       'like', '%'.$fabricW.'%');

    $stockOuts = $stockOutsQuery->get();

    /* ---------- 3) รวมผล: จับคู่ด้วยคีย์ customer/struct/pattern/width ---------- */
    $outsIndex = [];
    foreach ($stockOuts as $o) {
        $key = strtoupper(trim($o->customer)).'|'
             . strtoupper(trim($o->fabricStruct)).'|'
             . strtoupper(trim($o->fabricPattern)).'|'
             . strtoupper(trim($o->fabricW));
        $outsIndex[$key] = $o;
    }

    $combined = $stockIns->map(function ($in) use ($outsIndex) {
        $key = strtoupper(trim($in->customer)).'|'
             . strtoupper(trim($in->fabricStruct)).'|'
             . strtoupper(trim($in->fabricPattern)).'|'
             . strtoupper(trim($in->fabricW));

        $out = $outsIndex[$key] ?? null;

        $foldOut = $out->foldCountOut ?? 0;
        $yardOut = $out->sumYardOut   ?? 0;

        return (object)[
            'customer'            => $in->customer,
            'fabricId'            => $in->fabricId,
            'fabricStruct'        => $in->fabricStruct,
            'fabricPattern'       => $in->fabricPattern,
            'fabricW'             => $in->fabricW,
            'foldCountIn'         => (int)$in->foldCountIn,
            'sumYardIn'           => (float)$in->sumYardIn,
            'foldCountOut'        => (int)$foldOut,
            'sumYardOut'          => (float)$yardOut,
            'foldCountRemaining'  => max(0,   (int)$in->foldCountIn - (int)$foldOut),
            'sumYardRemaining'    => max(0.0, (float)$in->sumYardIn - (float)$yardOut),
        ];
    });

    // ถ้าต้องการแสดงเฉพาะที่ยังมีคงเหลือ:
    // $combined = $combined->filter(fn($r) => $r->foldCountRemaining > 0 || $r->sumYardRemaining > 0)->values();

    return $combined;
}

    
    private function getCombinedData_back($customer = null, $fabricStruct = null, $fabricId = null, $fabricPattern = null, $fabricW = null)
    {
        // Base query for stock-in data
        $stockInsQuery = \DB::table('stockfabrics')
            ->selectRaw("
                COALESCE(NULLIF(TRIM(customer), ''), 'AST') AS customer,
                fabricId,
                fabricStruct,
                fabricPattern,
                fabricW,
                COUNT(fold) as foldCountIn,
                SUM(sumYard) as sumYardIn
            ")
            ->groupBy('customer', 'fabricId', 'fabricStruct', 'fabricPattern', 'fabricW');

        // Apply filters if search criteria are provided
        if ($customer) {
            $stockInsQuery->where('customer', 'like', '%' . $customer . '%');
        }
        if ($fabricStruct) {
            $stockInsQuery->where('fabricStruct', 'like', '%' . $fabricStruct . '%');
        }
        if ($fabricId) {
            $stockInsQuery->where('fabricId', 'like', '%' . $fabricId . '%');
        }
        if ($fabricPattern) {
            $stockInsQuery->where('fabricPattern', 'like', '%' . $fabricPattern . '%');
        }
        if ($fabricW) {
            $stockInsQuery->where('fabricW', 'like', '%' . $fabricW . '%');
        }

        $stockIns = $stockInsQuery->get();

        // Base query for stock-out data
        $stockOutsQuery = \DB::table('fabricouts')
            ->selectRaw("
                COALESCE(NULLIF(TRIM(customerName), ''), 'AST') AS customer,
                fabricStruct,
                fabricPattern,
                fabricW,
                COUNT(fold) as foldCountOut,
                SUM(sumYard) as sumYardOut
            ")
            ->groupBy('customer', 'fabricStruct', 'fabricPattern', 'fabricW');
        
        // Apply filters to stock-out data as well
        if ($customer) {
             $stockOutsQuery->where('customerName', 'like', '%' . $customer . '%');
        }
        if ($fabricStruct) {
            $stockOutsQuery->where('fabricStruct', 'like', '%' . $fabricStruct . '%');
        }
        if ($fabricPattern) {
            $stockOutsQuery->where('fabricPattern', 'like', '%' . $fabricPattern . '%');
        }
        if ($fabricW) {
            $stockOutsQuery->where('fabricW', 'like', '%' . $fabricW . '%');
        }

        $stockOuts = $stockOutsQuery->get();

        // Combine and calculate remaining stock
        $combinedData = $stockIns->map(function ($in) use ($stockOuts) {
            $out = $stockOuts->first(function ($o) use ($in) {
                return $o->customer == $in->customer &&
                       $o->fabricStruct == $in->fabricStruct &&
                       $o->fabricPattern == $in->fabricPattern &&
                       $o->fabricW == $in->fabricW;
            });

            return (object)[
                'customer' => $in->customer,
                'fabricId' => $in->fabricId,
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

        return $combinedData;
    }


public function inspect(Request $request)
{
    // รับคีย์ที่ใช้ “เลือกชุดผ้า”
    $customer      = $request->input('customer');
    $fabricId      = $request->input('fabricId');
    $fabricStruct  = $request->input('fabricStruct');
    $fabricPattern = $request->input('fabricPattern');
    $fabricW       = $request->input('fabricW');

    // ทำให้คีย์อยู่ในรูป normalize เดียวกันกับตอนรวมสต็อก
    $normCustomer      = $customer ? trim($customer) : null;
    $normFabricId      = $fabricId ? trim($fabricId) : null;
    $normFabricStruct  = $fabricStruct ? trim($fabricStruct) : null;
    $normFabricPattern = $fabricPattern ? trim($fabricPattern) : null;
    $normFabricW       = $fabricW ? trim($fabricW) : null;

    // ดึง “สต็อกเข้า (stockfabrics)” รายแถว ที่เข้าข่ายคีย์นี้ทั้งหมด
    $ins = \DB::table('stockfabrics')
        ->select('id','customer','fabricId','fabricStruct','fabricPattern','fabricW','fold','sumYard','createDate','emp','refId')
        ->when($normCustomer,      fn($q) => $q->where(\DB::raw("COALESCE(NULLIF(TRIM(customer), ''), 'AST')"), 'LIKE', '%'.$normCustomer.'%'))
        ->when($normFabricId,      fn($q) => $q->where('fabricId',      'LIKE', '%'.$normFabricId.'%'))
        ->when($normFabricStruct,  fn($q) => $q->where(\DB::raw('TRIM(fabricStruct)'),  'LIKE', '%'.$normFabricStruct.'%'))
        ->when($normFabricPattern, fn($q) => $q->where(\DB::raw('TRIM(fabricPattern)'), 'LIKE', '%'.$normFabricPattern.'%'))
        ->when($normFabricW,       fn($q) => $q->where(\DB::raw('TRIM(fabricW)'),       'LIKE', '%'.$normFabricW.'%'))
        ->orderBy('createDate','desc')
        ->orderBy('id','desc')
        ->get();

    // (ถ้าต้องโชว์สต็อกออกด้วย ให้เพิ่มได้) — ตัวอย่าง comment ไว้ก่อน
    // $outs = \DB::table('fabricouts')
    //     ->select('id','refId','customerName','stockCustomer','fabricStruct','stockFabricStruct','fabricPattern','stockFabricPattern','fabricW','stockFabricW','fold','sumYard','createDate','emp')
    //     ->where(function($q) use ($normCustomer) {
    //         if ($normCustomer) {
    //             $q->where(\DB::raw("COALESCE(NULLIF(TRIM(stockCustomer), ''), COALESCE(NULLIF(TRIM(customerName), ''), 'AST'))"), 'LIKE', '%'.$normCustomer.'%');
    //         }
    //     })
    //     ->when($normFabricStruct,  fn($q) => $q->where(\DB::raw("TRIM(COALESCE(NULLIF(stockFabricStruct,''), fabricStruct))"),  'LIKE', '%'.$normFabricStruct.'%'))
    //     ->when($normFabricPattern, fn($q) => $q->where(\DB::raw("TRIM(COALESCE(NULLIF(stockFabricPattern,''), fabricPattern))"), 'LIKE', '%'.$normFabricPattern.'%'))
    //     ->when($normFabricW,       fn($q) => $q->where(\DB::raw("TRIM(COALESCE(NULLIF(stockFabricW,''), fabricW))"),            'LIKE', '%'.$normFabricW.'%'))
    //     ->orderBy('createDate','desc')->orderBy('id','desc')->get();

    // ส่งคีย์กลับไปที่ view เพื่อใช้ปุ่ม “ลบทั้งหมด”
    $key = [
        'customer'      => $customer,
        'fabricId'      => $fabricId,
        'fabricStruct'  => $fabricStruct,
        'fabricPattern' => $fabricPattern,
        'fabricW'       => $fabricW,
    ];

    return view('stockfabric.inspect', compact('ins','key'));
}

/** ลบสต็อกเข้าแบบรายแถว */
public function destroyIn($id)
{
    \DB::table('stockfabrics')->where('id',$id)->delete();
    return back()->with('success','ลบรายการเรียบร้อย');
}

/** ลบสต็อกเข้าแบบทั้งชุด (ตามคีย์ที่เลือก) */
public function destroyInBulk(Request $request)
{
    $deleted = 0; // กัน variable not defined
    $ids = $request->input('ids', []);
        // dd($ids); // ✅ หยุดการทำงานแล้วแสดงค่าออกมาในหน้าเว็บ
    if (is_array($ids) && count($ids) > 0) {
        $deleted = \DB::table('stockfabrics')->whereIn('id', $ids)->delete();
    } else {
        $customer      = $request->input('customer');
        $fabricId      = $request->input('fabricId');
        $fabricStruct  = $request->input('fabricStruct');
        $fabricPattern = $request->input('fabricPattern');
        $fabricW       = $request->input('fabricW');

        $q = \DB::table('stockfabrics');
        if ($customer)      $q->where(\DB::raw("COALESCE(NULLIF(TRIM(customer), ''), 'AST')"), 'LIKE', '%'.trim($customer).'%');
        if ($fabricId)      $q->where('fabricId', 'LIKE', '%'.trim($fabricId).'%');
        if ($fabricStruct)  $q->where(\DB::raw('TRIM(fabricStruct)'), 'LIKE', '%'.trim($fabricStruct).'%');
        if ($fabricPattern) $q->where(\DB::raw('TRIM(fabricPattern)'), 'LIKE', '%'.trim($fabricPattern).'%');
        if ($fabricW)       $q->where(\DB::raw('TRIM(fabricW)'), 'LIKE', '%'.trim($fabricW).'%');

        $deleted = $q->delete();
    }

    return redirect()
        ->route('stockfabric.inspect.get', [
            'customer'      => $request->input('customer'),
            'fabricId'      => $request->input('fabricId'),
            'fabricStruct'  => $request->input('fabricStruct'),
            'fabricPattern' => $request->input('fabricPattern'),
            'fabricW'       => $request->input('fabricW'),
        ])
        ->with($deleted > 0 ? 'success' : 'error', $deleted > 0
            ? "ลบรายการแล้ว ($deleted แถว)"
            : 'ไม่พบรายการที่จะลบ');
}

public function destroyInBulk_backup1(Request $request)
{
    $ids = $request->input('ids', []);

    if (empty($ids)) {
        return redirect()->route('stockfabric.index')
            ->with('error', 'ไม่พบรายการที่จะลบ');
    }

    $deleted = \DB::table('stockfabrics')
        ->whereIn('id', $ids)
        ->delete();

    return redirect()->route('stockfabric.index')
        ->with('success', "ลบรายการทั้งหมดแล้ว ($deleted แถว)");
}

public function destroyInBulk_backup(Request $request)
{
    $customer      = $request->input('customer');
    $fabricId      = $request->input('fabricId');
    $fabricStruct  = $request->input('fabricStruct');
    $fabricPattern = $request->input('fabricPattern');
    $fabricW       = $request->input('fabricW');

    $q = \DB::table('stockfabrics');

    if ($customer)      $q->where(\DB::raw("COALESCE(NULLIF(TRIM(customer), ''), 'AST')"), 'LIKE', '%'.trim($customer).'%');
    if ($fabricId)      $q->where('fabricId', 'LIKE', '%'.trim($fabricId).'%');
    if ($fabricStruct)  $q->where(\DB::raw('TRIM(fabricStruct)'), 'LIKE', '%'.trim($fabricStruct).'%');
    if ($fabricPattern) $q->where(\DB::raw('TRIM(fabricPattern)'), 'LIKE', '%'.trim($fabricPattern).'%');
    if ($fabricW)       $q->where(\DB::raw('TRIM(fabricW)'), 'LIKE', '%'.trim($fabricW).'%');

    $deleted = $q->delete();

    return back()->with('success', "ลบรายการทั้งหมดแล้ว ($deleted แถว)");
}

public function inspectGet(Request $request)
{
    // ป้องกันการเข้าตรงโดยไม่มี key
    if (!$request->hasAny(['customer','fabricId','fabricStruct','fabricPattern','fabricW'])) {
        return redirect()->route('stockfabric.index');
    }

    // reuse logic จาก inspect()
    return $this->inspect($request);
}

}
