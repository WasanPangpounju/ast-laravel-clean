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
// //โค้ดใหม่แก้ให้ตัด stock ลูกค้า AST หาก ชื่อลูกค้าเป็นค่าว่าง/*
// public function index()
// {
//     // ---------- IN: stockfabrics (normalize customer -> customer_norm) ----------
//     $sfSub = \DB::table('stockfabrics')
//         ->selectRaw("
//             fabricId,
//             fabricStruct,
//             fabricPattern,
//             fabricW,
//             COALESCE(NULLIF(TRIM(customer), ''), 'AST') AS customer_norm,
//             fold,
//             sumYard,
//             createDate
//         ");

//     $records = \DB::query()
//         ->fromSub($sfSub, 's')
//         ->selectRaw("
//             fabricId,
//             customer_norm AS customer,
//             fabricStruct,
//             fabricPattern,
//             fabricW,
//             COUNT(fold)  AS foldCount,
//             SUM(sumYard) AS sumYardSum,
//             MAX(createDate) AS lastDate
//         ")
//         ->groupBy('fabricStruct', 'fabricPattern', 'fabricW', 'customer', 'fabricId')
//         ->orderByDesc('lastDate')
//         ->get();

//     $sumStockfabric = $records;

//     // ---------- OUT: fabricouts (normalize customerName -> customer_norm) ----------
//     $foSub = \DB::table('fabricouts')
//         ->selectRaw("
//             fabricStruct,
//             fabricPattern,
//             fabricW,
//             COALESCE(NULLIF(TRIM(customerName), ''), 'AST') AS customer_norm,
//             fold,
//             sumYard
//         ");

//     $record2 = \DB::query()
//         ->fromSub($foSub, 'o')
//         ->selectRaw("
//             customer_norm AS customer,
//             fabricStruct,
//             fabricPattern,
//             fabricW,
//             COUNT(fold)  AS foldCount,
//             SUM(sumYard) AS sumYardSum
//         ")
//         ->groupBy('customer', 'fabricStruct', 'fabricPattern', 'fabricW')
//         ->get();

//     $sumFabricout = $record2;

//     return view('stockfabric.index', compact('sumStockfabric', 'sumFabricout'));
// }

public function index()
{
    // IN: รวมตามคีย์ 4 ตัว + normalize ลูกค้าว่างเป็น AST
    $sumStockfabric = \DB::table('stockfabrics')
        ->selectRaw("
            fabricStruct,
            fabricPattern,
            fabricW,
            COALESCE(NULLIF(TRIM(customer), ''), 'AST') AS customer,
            COUNT(DISTINCT fold) AS foldCount,
            SUM(sumYard)         AS sumYardSum,
            MAX(createDate)      AS lastDate
        ")
        ->groupBy('fabricStruct','fabricPattern','fabricW','customer')
        ->orderByDesc('lastDate')
        ->get();

    // OUT: รวมตามคีย์ 4 ตัวเดียวกัน + normalize ลูกค้าว่างเป็น AST
    $sumFabricout = \DB::table('fabricouts')
        ->selectRaw("
            fabricStruct,
            fabricPattern,
            fabricW,
            COALESCE(NULLIF(TRIM(customerName), ''), 'AST') AS customer,
            COUNT(DISTINCT fold) AS foldCount,
            SUM(sumYard)         AS sumYardSum
        ")
        ->groupBy('fabricStruct','fabricPattern','fabricW','customer')
        ->get();

    // ทำดัชนี OUT ล่วงหน้า -> lookup O(1) ใน Blade
    $outIndex = $sumFabricout->mapWithKeys(function ($r) {
        $key = implode('|', [
            $r->customer,
            $r->fabricStruct,
            $r->fabricPattern,
            $r->fabricW,
        ]);
        return [$key => (float)$r->sumYardSum];
    });

    return view('stockfabric.index', compact('sumStockfabric','sumFabricout','outIndex'));
}
//test
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
