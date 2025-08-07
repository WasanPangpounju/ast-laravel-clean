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

        $records = StockFabric::groupBy(['refId','fabricStruct', 'fabricPattern', 'fabricW'])
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
        return view('fabricoutcheck.index', compact('allfabricout'));
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
