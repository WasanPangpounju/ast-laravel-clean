<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Fabricdeposit;
use App\Models\fabricout;
use App\Models\fabricoutdeposit;


class FabricdepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $records = fabricout::groupBy(['fabricPattern', 'fabricW', 'refId', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName'])
            ->where('receiveType', 'deposit')
            ->selectRaw('receiveName,fabricPattern,fabricW,refId,no,fabricStruct, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
            ->orderBy('lastDate', 'DESC')
            ->get();

        // $records = fabricout::join('fabricoutdeposit', 'fabricout.refId', '=', 'fabricoutdeposit.orderId')
        //     ->groupBy(['fabricPattern', 'fabricW', 'refId', 'fabricStruct', 'no', 'refId', 'customerName'])
        //     ->where('receiveType', 'deposit')
        //     ->selectRaw('fabricout.fabricPattern, fabricout.fabricW, fabricout.refId, fabricout.no,
        //      fabricout.fabricStruct, fabricout.customerName,

        //      COUNT(fabricout.fold) as foldAll,SUM(fabricout.sumYard) as YardAll,

        //     COUNT(fabricoutdeposit.fold) as foldminus,SUM(fabricoutdeposit.sumYard) as Yardminus,

        //     (COUNT(fabricout.fold) - COUNT(fabricoutdeposit.fold)) as foldCount,
        //      (SUM(fabricout.sumYard) - SUM(fabricoutdeposit.sumYard)) as sumYardSum,

        //       MAX(fabricout.createDate) as lastDate')
        //     ->get();



        $sumfabricdepo = $records;

        $records2 = fabricoutdeposit::groupBy(['orderId', 'fabricPattern', 'fabricW', 'refId', 'fabricStruct', 'no', 'refId', 'customerName'])
            ->where('receiveType', 'deposit')
            ->selectRaw('orderId,fabricPattern,fabricW,refId,no,fabricStruct, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
            ->get();

        $sumfabricoutdepo = $records2;


        // print_r($sumfabricout);
        return view('fabricdeposit.index', compact('sumfabricdepo', 'sumfabricoutdepo'));
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
            $importorder = fabricout::where('fabricStruct', 'LIKE', '%' . $request->fabricStruct . '%')
                ->groupBy(['fabricPattern', 'fabricW', 'refId', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName'])
                ->where('receiveType', 'deposit')
                ->selectRaw('receiveName,fabricPattern,fabricW,refId,no,fabricStruct, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                ->orderBy('lastDate', 'DESC')
                ->get();
            $records2 = fabricoutdeposit::groupBy(['orderId', 'fabricPattern', 'fabricW', 'refId', 'fabricStruct', 'no', 'refId', 'customerName'])
                ->where('receiveType', 'deposit')
                ->selectRaw('orderId,fabricPattern,fabricW,refId,no,fabricStruct, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                ->get();

            $sumfabricoutdepo = $records2;
            $select_search = 'fabricStruct';
            $searchInput = $request->importId;
        } elseif ($request->filled('customerName')) {
            $importorder = fabricout::where('customerName', 'LIKE', '%' . $request->customerName . '%')
                ->groupBy(['fabricPattern', 'fabricW', 'refId', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName'])
                ->where('receiveType', 'deposit')
                ->selectRaw('receiveName,fabricPattern,fabricW,refId,no,fabricStruct, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                ->orderBy('lastDate', 'DESC')
                ->get();
            $records2 = fabricoutdeposit::groupBy(['orderId', 'fabricPattern', 'fabricW', 'refId', 'fabricStruct', 'no', 'refId', 'customerName'])
                ->where('receiveType', 'deposit')
                ->selectRaw('orderId,fabricPattern,fabricW,refId,no,fabricStruct, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                ->get();

            $sumfabricoutdepo = $records2;
            // $records = StockFabric::groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
            // ->selectRaw('refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
            // ->orderByDesc('lastCreateDate')
            // ->get();
            // ->get();
            //print($request->imDate);
            $select_search = 'imDate';
            $searchInput = $request->imDate;
        } elseif ($request->filled('imDate')) {
            $importorder = fabricout::where('createDate', 'LIKE', '%' . $request->imDate . '%')
                ->groupBy(['fabricPattern', 'fabricW', 'refId', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName'])
                ->where('receiveType', 'deposit')
                ->selectRaw('receiveName,fabricPattern,fabricW,refId,no,fabricStruct, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                ->orderBy('lastDate', 'DESC')
                ->get();
            $records2 = fabricoutdeposit::groupBy(['orderId', 'fabricPattern', 'fabricW', 'refId', 'fabricStruct', 'no', 'refId', 'customerName'])
                ->where('receiveType', 'deposit')
                ->selectRaw('orderId,fabricPattern,fabricW,refId,no,fabricStruct, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                ->get();

            $sumfabricoutdepo = $records2;
            // $records = StockFabric::groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
            // ->selectRaw('refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
            // ->orderByDesc('lastCreateDate')
            // ->get();
            // ->get();
            //print($request->imDate);
            $select_search = 'imDate';
            $searchInput = $request->imDate;
        }
        // print_r($importorder);
        // print_r('55');
        // return view('fabricdeposit.index', compact('sumfabricdepo', 'sumfabricoutdepo'));
        return view('fabricdeposit.index', compact('importorder', 'select_search', 'searchInput', 'sumfabricoutdepo'));
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
        fabricout::where('no', $id)->delete();
        return $this->index();
    }
}
