<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AstPurchaseorder;
use App\Models\FabricAst;
use App\Models\FabricAststructure;
use App\Models\Inventory;
use App\Models\orderdeadline;
use App\Models\stockfabric;

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

        $orders = AstPurchaseorder::select('id', 'customerName','createDate', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder')
            ->whereIn('id', $ecp)
            ->orderBy('created_at', 'desc')
            ->get();

        $inventorydata = Inventory::select('refId', Inventory::raw('SUM(fold) as foldSum'), Inventory::raw('SUM(sumYard) as sumYardSum'))
            ->groupBy('refId')
            ->get();
        // print_r($inventorydata);
        return view('inventory.index', compact('orders', 'inventorydata'));
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
        
        //check next data then save and set end count to session  and show create with end count
        if ($request->filled('submit') && $request->submit == 'nextData') {
            $oldEnd = session()->get('endCount');
            //remove session
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('fabricStruct');
            session()->forget('fabricW');

            //check data is null 
            // $foldData = $request->input('fold');
            // print($foldData[1]);

            $fabricData = $request->input('sumYard');

            $arr_data = array();

            foreach($fabricData as $data ){
// print($data);

if($data  != ''){

array_push($arr_data , $data );
}

            }

            //set session from input 
            // $endCount = $request->input('endCount') ? $request->input('endCount') : 0;
            $endCount =  $oldEnd + count($arr_data);
// print($endCount );
if($oldEnd == null ){
    $oldEnd  = 1;
}
            session()->put('endCount',  $endCount );
            $date = $request->input('dt') ? $request->input('dt') : date('Y-m-d');
session()->put('dt', $date);

            session()->put('fabricStruct',  $request->input('fabricStruct') );
            session()->put('fabricW', $request->input('fabricW') );


            //check input fabric 
            if(count($arr_data) > 0){
                //check key of fabric lot input
                if (session()->has('refId')) {
                    //get key from session to variable key
                    $key = session()->get('refId');

                                //save data
            $this->saveFabricData($arr_data , 
            $key , 
            auth()->user()->name , 
            session()->get('fabricStruct') , 
            session()->get('fabricW') , 
            $oldEnd , 
            $date );

                } else {
                    //generate key and set to session
                    $key = base64_encode(random_bytes(32)); // generates a 32-byte (256-bit) key
                    // echo $key;
            session()->put(['refId' => $key] );

            //save data
            $this->saveFabricData($arr_data , 
            $key , 
            auth()->user()->name , 
            session()->get('fabricStruct') , 
            session()->get('fabricW') , 
            $oldEnd , 
            $date );

                }
                
            }
            return $this->create();
        }

//save last record
        if ($request->filled('submit') && $request->submit == 'endData') {
            $oldEnd = session()->get('endCount');
            //remove session
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('fabricStruct');
            session()->forget('fabricW');

            //check data is null 
            // $foldData = $request->input('fold');
            // print($foldData[1]);

            $fabricData = $request->input('sumYard');

            $arr_data = array();

            foreach($fabricData as $data ){
// print($data);

if($data  != ''){

array_push($arr_data , $data );
}

            }

            //set session from input 
            // $endCount = $request->input('endCount') ? $request->input('endCount') : 0;
            $endCount =  $oldEnd + count($arr_data);
// print($endCount );
if($oldEnd == null ){
    $oldEnd  = 1;
}
            session()->put('endCount',  $endCount );
            $date = $request->input('dt') ? $request->input('dt') : date('Y-m-d');
session()->put('dt', $date);

            session()->put('fabricStruct',  $request->input('fabricStruct') );
            session()->put('fabricW', $request->input('fabricW') );


            //check input fabric 
            if(count($arr_data) > 0){
                //check key of fabric lot input
                if (session()->has('refId')) {
                    //get key from session to variable key
                    $key = session()->get('refId');

                                //save data
            $this->saveFabricData($arr_data , 
            $key , 
            auth()->user()->name , 
            session()->get('fabricStruct') , 
            session()->get('fabricW') , 
            $oldEnd , 
            $date );

                } else {
                    //generate key and set to session
                    $key = base64_encode(random_bytes(32)); // generates a 32-byte (256-bit) key
                    // echo $key;
            session()->put(['refId' => $key] );

            //save data
            $this->saveFabricData($arr_data , 
            $key , 
            auth()->user()->name , 
            session()->get('fabricStruct') , 
            session()->get('fabricW') , 
            $oldEnd , 
            $date );

                }
                
            }


                        //remove session
                        session()->forget('refId');
                        session()->forget('endCount');
                        session()->forget('dt');
                        session()->forget('fabricStruct');
                        session()->forget('fabricW');
            
            
            return $this->create();

        }

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
                'comment' => 'nullable'
            ]);
            //             print_r("test123");
            $show = Inventory::updateOrCreate($validatedData);
            //insert to db success
            if ($show) {
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

        // print_r("1351351");
        // $refId = $request->refId;
        $orderdata = AstPurchaseorder::find($id);
        // print_r($orderdata);
        $structuredata = FabricAststructure::where('purchaseOrder', $id)->get();
        $orderdeadline = orderdeadline::where('purchaseOrder', $id)->get();
        $fabricdata = FabricAst::where('purchaseOrder', $id)->get();
        $inventorydata = Inventory::where('refId', $id)->get();
        // $imdata = $request;
        // print_r($inventorydata);
        //print($imdata->supplierName );
        // print_r($refId);
        // print_r("5555555555555555555555555555555");
        return view('inventory.detailshow', compact('inventorydata', 'orderdata', 'structuredata', 'orderdeadline', 'fabricdata',));
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
        $orderdata = AstPurchaseorder::find($id);

        $structuredata = FabricAststructure::where('purchaseOrder', $id)->get();
        $orderdeadline = orderdeadline::where('purchaseOrder', $id)->get();
        $fabricdata = FabricAst::where('purchaseOrder', $id)->get();
        // $inventoryedit = Inventory::where('id', $id)->get();
        $inventoryedit = Inventory::find($id);
        // print_r($inventoryedit);
        return view('inventory.edit', compact('inventoryedit', 'orderdata', 'structuredata', 'orderdeadline', 'fabricdata',));
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
        // $request->validate([
        //     'refId' => 'required',
        //     'emp' => 'required',
        //     'inventoryName' => 'required',
        //     'orderId' => 'required',
        //     'createDate' => 'required',
        //     'fabricId' => 'required',
        //     'fabricStruct' => 'required',
        //     'fold' => 'required',
        //     'sumYard' => 'required',
        //     'sumM' => 'required',
        //     'fabricW' => 'required',
        //     'comment' => 'required'

        // ]);
        $dataUpdate = Inventory::find($id);
        // $inventoryedit = Inventory::where('refId', $id)->get();
        // $lastTenRecords = Material::latest()->take(10)->get();
        // Getting values from the blade template form
        $dataUpdate->refId = $request->get('refId');
        $dataUpdate->emp = $request->get('emp');
        $dataUpdate->inventoryName = $request->get('inventoryName');
        $dataUpdate->orderId = $request->get('orderId');
        $dataUpdate->createDate = $request->get('createDate');
        $dataUpdate->fabricId = $request->get('fabricId');
        $dataUpdate->fabricStruct = $request->get('fabricStruct');
        $dataUpdate->fold = $request->get('fold');
        $dataUpdate->sumYard = $request->get('sumYard');

        $dataUpdate->sumM = $request->get('sumM');
        $dataUpdate->fabricW = $request->get('fabricW');
        $dataUpdate->comment = $request->get('comment');
        $dataUpdate->save();

        // return redirect('/materialstore')->with('success', 'materialstore updated.'); // -> resources/views/stocks/index.blade.php
        // $lastTenRecords = Material::latest()->take(10)->get();
        $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();
        $orders = AstPurchaseorder::select('id', 'customerName', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder')
            ->whereIn('id', $ecp)
            ->orderBy('customerName')
            ->get();

        // return view('inventory.index' , compact('orders'));
        return $this->index();
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
        $data = Inventory::find($id);
        $data->delete();

        // $lastTenRecords = Material::latest()->take(10)->get();

        // return view('material.index', compact('lastTenRecords'));
        return $this->index();
    }

    private function saveFabricData($data , $refId , $emp, $fabricStruct , $fabricW , $start , $createDate){

        $c = $start;
foreach($data as $datasave){
$sf = stockfabric::create([
'refId' => $refId,
    'emp' => $emp,
    'fabricStruct' => $fabricStruct,
    'fabricW' => $fabricW,
    'fold' => $c,
    'sumYard' => $datasave,
    'createDate' => $createDate
]);

$c = $c+1;


}
    }

}
