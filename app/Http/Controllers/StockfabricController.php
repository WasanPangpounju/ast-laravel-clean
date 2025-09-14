<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;     // ✅ ย้ายมาไว้ตรงนี้ (บนหัวไฟล์เท่านั้น)
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
    // public function index()
    // {
    //     //
    //     $records = StockFabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW', 'customer','fabricId'])
    //         ->selectRaw('fabricId,customer,fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
    //         ->orderBy('createDate', 'desc')
    //         ->get();
    //     // ->count();
    //     // var_dump($records );
    //     $sumStockfabric = $records;
    //     $record2 = fabricout::groupBy(['fabricStruct', 'fabricPattern', 'fabricW'])
    //         ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
    //         ->get();
    //     // ->count();
    //     // var_dump($records );
    //     $sumFabricout = $record2;
    //     // print_r($sumStockfabric);
    //     // print_r($sumFabricout);
    //     return view('stockfabric.index', compact('sumStockfabric', 'sumFabricout'));
    // }
// โค้ดใหม่ ใช้ ast เป็นค่า defause ของ ลูกค้าหากไม่มีชื่อลูกค้าตัด stock
use Illuminate\Support\Facades\DB;

public function index()
{
    // ========= 1) IN: stockfabrics =========
    // NOTE: ปรับชื่อ table ให้ตรงกับจริง ถ้าใช้ conventions มักจะเป็น "stock_fabrics"
    $sumStockfabric = DB::table('stockfabrics')
        ->selectRaw("
            fabricId,
            COALESCE(NULLIF(TRIM(customer), ''), 'AST')          AS customer,   -- ถ้าว่าง ให้เป็น 'AST'
            fabricStruct,
            fabricPattern,
            fabricW,
            COUNT(fold)                                          AS foldCount,
            SUM(sumYard)                                         AS sumYardSum,
            MAX(createDate)                                      AS lastDateIn
        ")
        ->groupBy(
            'fabricId',
            DB::raw("COALESCE(NULLIF(TRIM(customer), ''), 'AST')"),
            'fabricStruct',
            'fabricPattern',
            'fabricW'
        )
        ->orderByDesc('lastDateIn')
        ->get();

    // ========= 2) OUT: fabricouts =========
    // จับคู่คีย์ให้เหมือนฝั่ง IN และ default customerName ว่าง -> 'AST'
    $sumFabricout = DB::table('fabricouts')
        ->selectRaw("
            COALESCE(NULLIF(TRIM(customerName), ''), 'AST')       AS customer,
            fabricStruct,
            fabricPattern,
            fabricW,
            COUNT(fold)                                           AS foldCount,
            SUM(sumYard)                                          AS sumYardSum,
            MAX(createDate)                                       AS lastDateOut
        ")
        ->groupBy(
            DB::raw("COALESCE(NULLIF(TRIM(customerName), ''), 'AST')"),
            'fabricStruct',
            'fabricPattern',
            'fabricW'
        )
        ->get();

    // ========= 3) (ออปชัน) รวมเป็น “คงเหลือ” ไว้ใช้ในตารางหลัก =========
    // ถ้ายังไม่ต้องแสดงคงเหลือ สามารถคอมเมนต์บล็อกนี้ทิ้งได้
    $in  = DB::table('stockfabrics')
        ->selectRaw("
            fabricId,
            COALESCE(NULLIF(TRIM(customer), ''), 'AST') AS customer,
            fabricStruct, fabricPattern, fabricW,
            COUNT(fold)  AS in_foldCount,
            SUM(sumYard) AS in_sumYard,
            MAX(createDate) AS in_lastDate
        ")
        ->groupBy(
            'fabricId',
            DB::raw("COALESCE(NULLIF(TRIM(customer), ''), 'AST')"),
            'fabricStruct','fabricPattern','fabricW'
        );

    $out = DB::table('fabricouts')
        ->selectRaw("
            COALESCE(NULLIF(TRIM(customerName), ''), 'AST') AS customer,
            fabricStruct, fabricPattern, fabricW,
            COUNT(fold)  AS out_foldCount,
            SUM(sumYard) AS out_sumYard,
            MAX(createDate) AS out_lastDate
        ")
        ->groupBy(
            DB::raw("COALESCE(NULLIF(TRIM(customerName), ''), 'AST')"),
            'fabricStruct','fabricPattern','fabricW'
        );

    $inSql  = $in->toSql();
    $outSql = $out->toSql();

    $sumStock = DB::table(DB::raw("($inSql) AS i"))
        ->mergeBindings($in)
        ->leftJoin(DB::raw("($outSql) AS o"), function($j){
            $j->on('i.customer','=','o.customer')
              ->on('i.fabricStruct','=','o.fabricStruct')
              ->on('i.fabricPattern','=','o.fabricPattern')
              ->on('i.fabricW','=','o.fabricW');
        })
        ->mergeBindings($out)
        ->selectRaw("
            i.fabricId,
            i.customer,
            i.fabricStruct, i.fabricPattern, i.fabricW,
            COALESCE(i.in_foldCount,0)  AS in_foldCount,
            COALESCE(i.in_sumYard,0)    AS in_sumYard,
            COALESCE(o.out_foldCount,0) AS out_foldCount,
            COALESCE(o.out_sumYard,0)   AS out_sumYard,
            COALESCE(i.in_foldCount,0)  - COALESCE(o.out_foldCount,0) AS bal_fold,
            COALESCE(i.in_sumYard,0)    - COALESCE(o.out_sumYard,0)   AS bal_sumYard,
            GREATEST(
                COALESCE(i.in_lastDate,  '0000-00-00'),
                COALESCE(o.out_lastDate, '0000-00-00')
            ) AS last_movement
        ")
        ->orderByDesc('last_movement')
        ->paginate(20); // ถ้าอยากใช้ในหน้าแสดงรวม

    // ส่งค่ากลับ view
    return view('stockfabric.index', compact('sumStockfabric', 'sumFabricout', 'sumStock'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //searchImport
        if ($request->filled('submit') && $request->submit == 'searchImport') {
            $select_search = '';
            $searchInput = '';

            if ($request->filled('importId') && $request->filled('customerName') && $request->filled('yarnType') && $request->filled('imDate')) {
                //print('1 2 3 4');
            } elseif ($request->filled('importId') && $request->filled('customerName') && $request->filled('yarnType')) {
                //print('1 2 3');
            } elseif ($request->filled('customerName') && $request->filled('yarnType') && $request->filled('imDate')) {
                //print('2 3 4');
            } elseif ($request->filled('importId') && $request->filled('customerName')) {
                //print('1 2');
            } elseif ($request->filled('yarnType') && $request->filled('imDate')) {
                //print('3 4');
            } elseif ($request->filled('customerName') && $request->filled('yarnType')) {
                //print('2 3');
            } elseif ($request->filled('importId') && $request->filled('imDate')) {
                //print('1 4');
            } elseif ($request->filled('importId') && $request->filled('yarnType')) {
                //print('1 3');
            } elseif ($request->filled('customerName') && $request->filled('imDate')) {
                //print('2 4');
            } elseif ($request->filled('fabricStruct')) {
                $importorder = StockFabric::where('fabricStruct', 'LIKE', '%' . $request->fabricStruct . '%')
                    ->groupBy(['fabricId','fabricStruct', 'fabricPattern', 'fabricW', 'createDate', 'customer','fabricId'])
                    ->selectRaw('customer,fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum,createDate')
                    ->orderBy('createDate', 'desc')
                    ->get();

                $record2 = fabricout::groupBy(['fabricStruct', 'fabricPattern', 'fabricW'])
                    ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                    ->get();
                // ->count();
                // var_dump($records );
                $sumFabricout = $record2;

                //var_dump($importorder );
                //print($request->importId);
                $select_search = 'importId';
                $searchInput = $request->importId;
            } elseif ($request->filled('customer')) {
                $importorder = StockFabric::where('customer', 'LIKE', '%' . $request->customer . '%')
                    ->groupBy(['fabricStruct', 'fabricPattern', 'fabricW', 'createDate', 'customer','fabricId'])
                    ->selectRaw('fabricId,customer,fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum,createDate')
                    ->orderBy('createDate', 'desc')
                    ->get();

                $record2 = fabricout::groupBy(['fabricStruct', 'fabricPattern', 'fabricW'])
                    ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                    ->get();
                // ->count();
                // var_dump($records );
                $sumFabricout = $record2;

                //var_dump($importorder );
                //print($request->importId);
                $select_search = 'importId';
                $searchInput = $request->importId;
            }

            // print_r($request->imDate);
            // print_r($importorder);
            return view('stockfabric.index', compact('importorder', 'select_search', 'sumFabricout'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
