<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\fabricout;
use App\Models\stockfabric;

class FabriccheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $records = StockFabric::groupBy(['refId','fabricStruct', 'fabricPattern', 'fabricW','createDate'])
        //     ->selectRaw('refId,fabricStruct,createDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum ')
        //     ->orderBy('createDate')
        //     ->get();
        //     // ->count();
        // // var_dump($records );
        // $allfabricout = $records;

        $records = StockFabric::groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
            ->selectRaw('refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
            ->orderByDesc('lastCreateDate')
            ->get();

        // $records = StockFabric::groupBy(['refId'])
        // ->select('refId')
        // // ->orderByDesc('lastCreateDate')
        // ->get();

        $allfabricout = $records;
        //     $records = stockfabric::select('id', 'refId', 'emp', 'fabricStruct', 'fabricW', 'sumYard','createDate','fabricPattern')
        //         ->orderBy('createDate')
        //         ->get();
        // // ->count();
        // // var_dump($records );
        // $allfabricout = $records;
        // print_r($sumfabricout);
        // $orders = fabricout::selectRaw('no,fold , sumYard')
        // ->where('no', 1016)
        // ->get();
        // print_r($allfabricout);
        $stockFabricStruct = stockfabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW',])
            ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as 
        lastDate')
            ->get();

        return view('fabricoutcheck.index', compact('allfabricout', 'stockFabricStruct'));
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
                ->groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
                ->selectRaw('refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                ->orderByDesc('lastCreateDate')
                ->get();
                $select_search = 'importId';
                $searchInput = $request->importId;
            } elseif ($request->filled('imDate')) {

                // Create a DateTime object from the original date format
                $dateObj = date_create_from_format('d/m/Y', $request->imDate);

                // Convert the DateTime object to the desired format
                $fixedValue = date_format($dateObj, 'Y-m-d');
                $importorder = StockFabric::where('createDate', 'LIKE', '%' . $fixedValue . '%')
                    ->groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
                    ->selectRaw('refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                    ->orderByDesc('lastCreateDate')
                    ->get();
                // $records = StockFabric::groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
                // ->selectRaw('refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                // ->orderByDesc('lastCreateDate')
                // ->get();
                // ->get();
                //print($request->imDate);
                $select_search = 'imDate';
                $searchInput = $request->imDate;
            } else {
                // $importorder = AstPurchaseorder::where('importStatus', 'LIKE', '%' . $request->importId . '%')->get();
                // $select_search = 'non data';
            }

            $orderlist = StockFabric::orderByDesc('createDate')->get();

            // $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $orderEdit->id)->get();
            // for ($i = 0; $i < count($orderlist); $i++) {
            //     $st = $this->getStatus($orderlist[$i]->id);
            //     if ($st == 'no data') {
            //         $orderlist[$i]->status = 'สร้างใบสั่งซื้อ';
            //     } else {
            //         $orderlist[$i]->status = $st;
            //     }
            // }
            // print_r($request->imDate);
            // print_r($searchInput);

            $records = StockFabric::groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
                ->selectRaw('refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                ->orderByDesc('lastCreateDate')
                ->get();

            // $records = StockFabric::groupBy(['refId'])
            // ->select('refId')
            // // ->orderByDesc('lastCreateDate')
            // ->get();

            $allfabricout = $records;

            $stockFabricStruct = stockfabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW',])
                ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as 
        lastDate')
                ->get();

            return view('fabricoutcheck.index', compact('importorder', 'select_search', 'searchInput', 'orderlist', 'stockFabricStruct', 'allfabricout'));
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
    public function destroy($refId)
    {
        //
        StockFabric::where('refId', $refId)->delete();

        return $this->index();
    }
}
