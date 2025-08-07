<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AstPurchaseorder;
use App\Models\FabricAst;
use App\Models\FabricAststructure;

use App\Models\orderdeadline;

class InventoryController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //get all order status is except to manufacture.
        $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2' , 'อนุมัติให้ผลิต')->get();

        $orders = AstPurchaseorder::select('id' , 'customerName' , 'fabricId' ,'fabricStructure' , 'orderSumYard' , 'purchaseOrder')
        ->whereIn('id', $ecp )
        ->orderBy('customerName')
        ->get();
        
        return view('inventory.create', compact('orders'));

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
