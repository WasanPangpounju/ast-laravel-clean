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
    // ---------- 1) สต็อกเข้า (normalize + group โดยใช้ expression จริง) ----------
    $insCustomerExpr = "COALESCE(NULLIF(TRIM(customer), ''), 'AST')";
    $insStructExpr   = "TRIM(fabricStruct)";
    $insPatternExpr  = "TRIM(fabricPattern)";
    $insWidthExpr    = "TRIM(fabricW)";

    $stockInsQuery = \DB::table('stockfabrics')
        ->selectRaw("
            {$insCustomerExpr} AS customer,
            fabricId,
            {$insStructExpr}   AS fabricStruct,
            {$insPatternExpr}  AS fabricPattern,
            {$insWidthExpr}    AS fabricW,
            COUNT(*)  AS foldCountIn,
            SUM(sumYard) AS sumYardIn
        ")
        ->groupByRaw("{$insCustomerExpr}, fabricId, {$insStructExpr}, {$insPatternExpr}, {$insWidthExpr}");

    // เงื่อนไขค้นหา (ฟิลเตอร์บนคอลัมน์ที่ normalize แล้ว)
    if ($customer)      $stockInsQuery->whereRaw("{$insCustomerExpr} LIKE ?", ['%'.$customer.'%']);
    if ($fabricStruct)  $stockInsQuery->whereRaw("{$insStructExpr}   LIKE ?", ['%'.$fabricStruct.'%']);
    if ($fabricId)      $stockInsQuery->where('fabricId', 'like', '%'.$fabricId.'%');
    if ($fabricPattern) $stockInsQuery->whereRaw("{$insPatternExpr}  LIKE ?", ['%'.$fabricPattern.'%']);
    if ($fabricW)       $stockInsQuery->whereRaw("{$insWidthExpr}    LIKE ?", ['%'.$fabricW.'%']);

    $stockIns = $stockInsQuery->get();

    // ---------- 2) สต็อกออก: ทำ subquery ให้ได้คีย์ normalize แล้วค่อย group ----------
    $outCustomerExpr = "COALESCE(NULLIF(TRIM(CASE WHEN stockCustomer IS NULL OR stockCustomer = '' THEN customerName ELSE stockCustomer END), ''), 'AST')";
    $outStructExpr   = "TRIM(CASE WHEN stockFabricStruct  IS NULL OR stockFabricStruct  = '' THEN fabricStruct  ELSE stockFabricStruct  END)";
    $outPatternExpr  = "TRIM(CASE WHEN stockFabricPattern IS NULL OR stockFabricPattern = '' THEN fabricPattern ELSE stockFabricPattern END)";
    $outWidthExpr    = "TRIM(CASE WHEN stockFabricW       IS NULL OR stockFabricW       = '' THEN fabricW       ELSE stockFabricW       END)";

    // subquery: สร้างคอลัมน์ normalize แล้ว
    $outsSub = \DB::table('fabricouts')
        ->selectRaw("
            {$outCustomerExpr} AS customer,
            {$outStructExpr}   AS fabricStruct,
            {$outPatternExpr}  AS fabricPattern,
            {$outWidthExpr}    AS fabricW,
            fold,
            sumYard
        ");

    // outer query: group บนคอลัมน์ที่คำนวณแล้ว (เลี่ยง ONLY_FULL_GROUP_BY)
    $stockOutsQuery = \DB::query()
        ->fromSub($outsSub, 'fo')
        ->selectRaw("
            customer, fabricStruct, fabricPattern, fabricW,
            COUNT(*) AS foldCountOut,
            SUM(sumYard) AS sumYardOut
        ")
        ->groupBy('customer','fabricStruct','fabricPattern','fabricW');

    // ฟิลเตอร์บนคอลัมน์ normalize แล้ว (ใน subquery)
    if ($customer)      $stockOutsQuery->where('customer',      'like', '%'.$customer.'%');
    if ($fabricStruct)  $stockOutsQuery->where('fabricStruct',  'like', '%'.$fabricStruct.'%');
    if ($fabricPattern) $stockOutsQuery->where('fabricPattern', 'like', '%'.$fabricPattern.'%');
    if ($fabricW)       $stockOutsQuery->where('fabricW',       'like', '%'.$fabricW.'%');

    $stockOuts = $stockOutsQuery->get();

    // ---------- 3) รวมผล ----------
    // ทำดัชนีฝั่งออกเพื่อจับคู่เร็ว
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

    // ถ้าต้องการโชว์เฉพาะที่เหลือ > 0:
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
}
