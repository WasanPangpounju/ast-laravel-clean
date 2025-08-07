<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Coordinator;

class SupplierController extends Controller
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
    public function index(Request $request)
    {
        $supplier = Supplier::all();

        return view('supplier.index' ,compact('supplier'));
    }

    public static function coordinatorData($taxData)
    {
$coorData = Coordinator::where('tax', '=', $taxData )->get();
//var_dump($coorData );
        return $coorData;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier.create');
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
    if(! $request->filled('email')) {
$request->request->add(['email' => 'Empty Your Email']); 
}
    if(! $request->filled('coor')) {
$request->request->add(['coor' => 'test']); 
}

        $validatedData = $request->validate([
            'name' => 'required',
            'tax' => 'required',
            'address' => 'required',
            'tel' => 'required',
            'email' => 'required',
            'type' => 'required',
            'coor' => 'required'
        ]);

        $show = supplier::create($validatedData);

if($show ){

//Copy coordinator to codata
$codata = $request->coordinator;
//var_dump($codata);

//Loop Coordinater
foreach($codata as $key => $value) {

//Add Tax field to value
$value = ["tax" => $request['tax'] ] + $value;

//Check Tel is emty 
if($value['tel'] == null) {
//Set "No Data to value
$value = ["tel" =>  'No Data'] + $value;
}

//Check jobTitle is emty
if($value['jobTitle'] == null) {
//set "No Data" to value
$value = ["jobTitle" =>  'No Data'] + $value;
}

//Insert Coordinator
        $co = Coordinator::create($value);

}


/*
        $validatedCoordinator = $request->validate([
            'coordinator.*.name' => 'required',
            'coordinator.*.jobTitle' => 'required',
            'coordinator.*.tel' => 'required',
        ]);

        foreach ($validatedCoordinator['coordinator'] as $key => $value) {
//print($key);
$value = ["tax" => $request['tax'] ] + $value;
//var_dump($value);
        $co = Coordinator::create($value);
        }
*/

}
   
   
        return redirect('/supplier')->with('success', 'customer is successfully saved');

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
        $dataEdit = supplier::find($id);
        //$coordinator = Coordinator::all();
        
        return view('supplier.edit', compact( 'dataEdit' ));
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
        $request->validate([
            'name' => 'required',
            'tax' => 'required',
            'address' => 'required',
            'tel' => 'required',
            'email' => 'required',
            'type' => 'required',        
        ]);
        
        $dataUpdate = supplier::find($id);

        // Getting values from the blade template form
        $dataUpdate->name = $request->get('name');
        $dataUpdate->tax = $request->get('tax');
        $dataUpdate->address = $request->get('address');
        $dataUpdate->tel = $request->get('tel');
        $dataUpdate->email = $request->get('email');
        $dataUpdate->type = $request->get('type');
        $dataUpdate->save();

        //update coordinator 
        $codata = $request->coordinator;
        foreach($codata  as $key => $value) {
            
            //print_r($value);
//print($value[0]['jobTitle']);
        $coordinatorUpdate = Coordinator::find($value['coID'] );

                // Getting values from the blade template form

$coordinatorUpdate->name = $value['name'];
$coordinatorUpdate->jobTitle = $value['jobTitle'];
$coordinatorUpdate->tel = $value['tel'];

$coordinatorUpdate->save();

        }


        // return redirect('/materialstore')->with('success', 'materialstore updated.'); // -> resources/views/stocks/index.blade.php
        $lastTenRecords = supplier::latest()->take(10)->get();
        $supplier = supplier::all();
        return view('supplier.index', compact('supplier'));
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
        $data = supplier::find($id);
        $data->delete();

        $lastTenRecords = supplier::latest()->take(10)->get();
        $supplier = supplier::all();
        return view('supplier.index', compact('lastTenRecords'));
    }
}