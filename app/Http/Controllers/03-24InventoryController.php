<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AstPurchaseorder;
use App\Models\FabricAst;
use App\Models\FabricAststructure;
use App\Models\Inventory;
use App\Models\orderdeadline;
use PhpParser\Node\Expr\Print_;

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
                //get all order status is except to manufacture.
                $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();

                $orders = AstPurchaseorder::select('id', 'customerName', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder')
                    ->whereIn('id', $ecp)
                    ->orderBy('customerName')
                    ->get();
        
                return view('inventory.index', compact('orders'));
        
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
        $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();

        $orders = AstPurchaseorder::select('id', 'customerName', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder')
            ->whereIn('id', $ecp)
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
        if ($request->filled('submit') && $request->submit == 'checkdata') {
            //print($request->submit);
            if (!$request->filled('createDate')) {
                $request->request->add(['createDate' => date("m/d/Y")]);
            }
            if (!$request->filled('lot')) {
                $request->request->add(['lot' => date("Y") . '1']);
            }
            if (!$request->filled('pallet')) {
                $request->request->add(['pallet' => '0']);
            }
            if (!$request->filled('box')) {
                $request->request->add(['box' => '0']);
            }
            if (!$request->filled('sack')) {
                $request->request->add(['sack' => '0']);
            }
            if (!$request->filled('orderId')) {
                $request->request->add(['importStatus' => 'no import number']);
            }
            $refId = $request->refId;
            $orderdata = AstPurchaseorder::find($refId);
            // print_r($orderdata);
            $structuredata = FabricAststructure::where('purchaseOrder', $refId)->get();
            $orderdeadline = orderdeadline::where('purchaseOrder', $refId)->get();
            $fabricdata = FabricAst::where('purchaseOrder', $refId)->get();

            $imdata = $request;
            //print($imdata->supplierName );
            // print_r($refId);
            // print_r("5555555555555555555555555555555");
            return view('inventory.detail', compact('imdata', 'orderdata', 'structuredata', 'orderdeadline', 'fabricdata',));
        }

        if ($request->filled('submit') && $request->submit == 'savedata') {
            $validatedData = $request->validate([
                'refId' => 'required',
'emp' => 'required',
'inventoryName' => 'required',
'orderId' => 'required',
'createDate' => 'required',
'fabricId' => 'required',
'fabricStruct' => 'required',
'fold' => 'required',
'sumYard' => 'required',
'sumM' => 'required',
'fabricW' => 'required',
'comment' => 'required'
            ]);
//             print_r("test123");
            $show = Inventory::updateOrCreate($validatedData);
            //insert to db success
            if($show ){
                return $this->create();
            }

            // return view('inventory.create');
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
        // $orderdata = AstPurchaseorder::find($id);

        // if ($orderdata) {
        //     //print($orderdata);
        //     $structuredata = FabricAststructure::where('purchaseOrder', $id)->get();

        //     $orderdeadline = orderdeadline::where('purchaseOrder', $id)->get();

        //     $fabricdata = FabricAst::where('purchaseOrder', $id)->get();

        //     return view('inventory.checkdata', compact('orderdata', 'structuredata', 'orderdeadline', 'fabricdata'));
        // }
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
