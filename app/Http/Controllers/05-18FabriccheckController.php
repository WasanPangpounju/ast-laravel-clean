<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\fabricout;

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
        $records = fabricout::groupBy(['fabricStruct', 'no', 'refId', 'customerName', 'receiveName','fabricPattern','fabricW'])
        ->selectRaw('fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
        ->get();
    // ->count();
    // var_dump($records );
    $sumfabricout = $records;
    // print_r($sumfabricout);
    // $orders = fabricout::selectRaw('no,fold , sumYard')
    // ->where('no', 1016)
    // ->get();
    // print_r($orders);
    return view('fabricoutcheck.index', compact('sumfabricout'));
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
    public function destroy($id)
    {
        //
    }
}
