<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Coordinator;
use App\Models\Material;
use App\Models\Package;
use App\Models\Packageast;

class MaterialController extends Controller
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

        $supplier = Supplier::all();
        $lastTenRecords = Material::latest()->take(10)->get();
        return view('material.index' ,compact('supplier','lastTenRecords'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //get all supplier
        $supplier = Supplier::all();

        //Get all material import group by yarnType
$stockYarns  = Material::orderBy('yarnType')->get()->groupBy(function($data) {
    return $data->yarnType;
});

        return view('material.create' ,compact('supplier' , 'stockYarns'));
    }

    public static function supData()
    {
        $supplier = Supplier::all();

        return         $supplier;
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
    if($request->filled('submit') && $request->submit == 'searchImport') {
$select_search = '';
$searchInput = '';

if($request->filled('importId') && $request->filled('supId') && $request->filled('yarnType') && $request->filled('imDate') ){
//print('1 2 3 4');
}
elseif($request->filled('importId') && $request->filled('supId') && $request->filled('yarnType') ){
//print('1 2 3');
}
elseif($request->filled('supId') && $request->filled('yarnType') && $request->filled('imDate') ){
//print('2 3 4');
}
elseif($request->filled('importId') && $request->filled('supId') ){
//print('1 2');
}
elseif($request->filled('yarnType') && $request->filled('imDate') ){
//print('3 4');
}
elseif($request->filled('supId') && $request->filled('yarnType') ){
//print('2 3');
}
elseif($request->filled('importId') && $request->filled('imDate') ){
//print('1 4');
}
elseif($request->filled('importId') && $request->filled('yarnType') ){
//print('1 3');
}
elseif($request->filled('supId') && $request->filled('imDate') ){
//print('2 4');
}
    elseif($request->filled('importId') ) {
$importmaterial = Material::where('importStatus' , 'LIKE' , '%'. $request->importId .'%')->get();
//var_dump($importmaterial );
//print($request->importId);
$select_search = 'importId';
$searchInput = $request->importId;
}
elseif($request->filled('supId') ){
$importmaterial = Material::where('supplierName' , 'LIKE' , '%'. $request->supId .'%')->get();
//print($request->supId);
$select_search = 'supId';
$searchInput = $request->supId;
}
elseif($request->filled('yarnType') ){
$importmaterial = Material::where('yarnType' , 'LIKE' , '%'. $request->yarnType .'%')->get();
//print($request->yarnType );
$select_search = 'yarnType';
$searchInput = $request->yarnType;
}
elseif($request->filled('imDate') ){
$importmaterial = Material::where('createDate' , 'LIKE' , '%'. $request->imDate .'%')->get();
//print($request->imDate);
$select_search = 'imDate';
$searchInput = $request->imDate;
}
else {
$importmaterial = Material::where('importStatus' , 'LIKE' , '%'. $request->importId .'%')->get();
$select_search = 'non data';

}

        return view('material.index' ,compact('importmaterial' , 'select_search' , 'searchInput'));

}

if($request->filled('submit') && $request->submit == 'cleanForm') {
    $supplier = Supplier::all();

            //Get all material import group by yarnType
$stockYarns  = Material::orderBy('yarnType')->get()->groupBy(function($data) {
return $data->yarnType;
});

return view('material.create' ,compact('supplier' , 'stockYarns' ));
}

    if($request->filled('submit') && $request->submit == 'back') {
        $supplier = Supplier::all();

                //Get all material import group by yarnType
$stockYarns  = Material::orderBy('yarnType')->get()->groupBy(function($data) {
    return $data->yarnType;
});

//send import data to create page
$backdata = $request;

return view('material.create' ,compact('supplier' , 'stockYarns' , 'backdata'));
}

    if($request->filled('submit') && $request->submit == 'checkdata') {
//print($request->submit);
if(! $request->filled('createDate')){
$request->request->add(['createDate' => date("m/d/Y") ]); 
}

if(! $request->filled('lot')){
$request->request->add(['lot' => date("Y") .'1']); 
}
if(! $request->filled('pallet')){
$request->request->add(['pallet' => '0']); 
}
if(! $request->filled('box')){
$request->request->add(['box' => '0']); 
}
if(! $request->filled('sack')){
$request->request->add(['sack' => '0']); 
}
if(! $request->filled('importStatus')){
$request->request->add(['importStatus' => 'no import number']); 
}

$imdata = $request;
//print($imdata->supplierName );

        return view('material.checkimport' ,compact('imdata'));
}

    if($request->filled('submit') && $request->submit == 'save') 
{
        $validatedData = $request->validate([
'emp' => 'required',
'supplierName' => 'required',
'supplierId' => 'required',
'createDate' => 'required',
'yarnType' => 'required',
'lot' => 'required',
'pallet' => 'required',
'box' => 'required',
'sack' => 'required',
'spool' => 'required',
'weight_p_sum' => 'required',
'weight_kg_sum' => 'required',
'weight_p_package' => 'required',
'weight_kg_package' => 'required',
'weight_p_net' => 'required',
'weight_kg_net' => 'required',
'average_p' => 'required',
'average_kg' => 'required',
'importStatus' => 'required',
        ]);

        $show = Material::create($validatedData);

        if($show ){
//Copy submit data to packaging data
$packagingData = $request;
$packaging_return = [];
print($packagingData->packaging1 );
print($packagingData->packaging2 );
print($packagingData->packaging3 );
print($packagingData->packaging4 );

//variable package import and return
$astpackaging = [];
$astpackaging_return = [];

//set data package import
$astpackaging = ['emp' => $packagingData->emp ] + $astpackaging;
$astpackaging = ['ref_id' => $show->id ] + $astpackaging;
$astpackaging = ['supplier_name' => $packagingData->supplierName ] + $astpackaging;
$astpackaging = ['spool' => $packagingData->spool ] + $astpackaging;
$astpackaging = ['sack' => $packagingData->sack ] + $astpackaging;
$astpackaging = ['box' => $packagingData->box ] + $astpackaging;
$astpackaging = ['pallet' => $packagingData->pallet ] + $astpackaging;
$astpackaging = ['package_status' => 'packageImport'] + $astpackaging;

$astpackaging = ['spool_type' => $request->filled('typetag_spool') ? $request->typetag_spool : null ] + $astpackaging;
$astpackaging = ['sack_type' => $request->filled('typetag_sack') ? $request->typetag_sack : null ] + $astpackaging;
$astpackaging = ['box_type' => $request->filled('typetag_box') ? $request->typetag_box : null] + $astpackaging;
$astpackaging = ['pallet_type' => $request->filled('typetag_pallet') ? $request->typetag_pallet : null ] + $astpackaging;
$astpackaging = ['partition' => $request->filled('paper_bar') ? $request->paper_bar : null ] + $astpackaging;
$astpackaging = ['partition_type' => $request->filled('partition_type') ? $request->partition_type: null ] + $astpackaging;

//set data for return package
$astpackaging_return = ['emp' => $packagingData->emp ] + $astpackaging_return;
$astpackaging_return = ['ref_id' => $show->id] + $astpackaging_return;
$astpackaging_return = ['supplier_name' => $packagingData->supplierName ] + $astpackaging_return;
if($packagingData->packaging4  == 'spool'){
    $astpackaging_return = ['spool' => $packagingData->spool ] + $astpackaging_return;
    $astpackaging_return = ['spool_type' => $request->filled('typetag_spool') ? $request->typetag_spool : null ] + $astpackaging_return;
    } else {
        $astpackaging_return = ['spool' => 0 ] + $astpackaging_return;
        $astpackaging_return = ['spool_type' => $request->filled('typetag_spool') ? $request->typetag_spool : null ] + $astpackaging_return;
    }
    if($packagingData->packaging3  == 'sack'){
        $astpackaging_return = ['sack' => $packagingData->sack ] + $astpackaging_return;
        $astpackaging_return = ['sack_type' => $request->filled('typetag_sack') ? $request->typetag_sack : null ] + $astpackaging_return;
        } else {
            $astpackaging_return = ['sack' => 0 ] + $astpackaging_return;
            $astpackaging_return = ['sack_type' => $request->filled('typetag_sack') ? $request->typetag_sack : null ] + $astpackaging_return;
        }
        if($packagingData->packaging2  == 'box'){
            $astpackaging_return = ['box' => $packagingData->box ] + $astpackaging_return;
            $astpackaging_return = ['box_type' => $request->filled('typetag_box') ? $request->typetag_box : null] + $astpackaging_return;
            } else {
                $astpackaging_return = ['box' => 0 ] + $astpackaging_return;
                $astpackaging_return = ['box_type' => $request->filled('typetag_box') ? $request->typetag_box : null] + $astpackaging_return;
            }
            if($packagingData->packaging1  == 'pallet'){
                $astpackaging_return = ['pallet' => $packagingData->pallet ] + $astpackaging_return;
                $astpackaging_return = ['pallet_type' => $request->filled('typetag_pallet') ? $request->typetag_pallet : null ] + $astpackaging_return;
                } else {
                    $astpackaging_return = ['pallet' => 0 ] + $astpackaging_return;
                    $astpackaging_return = ['pallet_type' => $request->filled('typetag_pallet') ? $request->typetag_pallet : null ] + $astpackaging_return;
                }
                $astpackaging_return = ['package_status' => 'packageReturn'] + $astpackaging_return;
                $astpackaging_return = ['partition' => $request->filled('paper_bar') ? $request->paper_bar : null ] + $astpackaging_return;
                $astpackaging_return = ['partition_type' => $request->filled('partition_type') ? $request->partition_type: null ] + $astpackaging_return;
                
                                    
    
$packaging_return = ['emp' => $packagingData->emp ] +$packaging_return;
$packaging_return = ['supplier_id' => Date('m/d/Y') ] +$packaging_return;
$packaging_return = ['supplier_name' => $packagingData->supplierName ] +$packaging_return;

if($packagingData->packaging4  == 'spool'){
$packaging_return = ['spool' => $packagingData->spool ] +$packaging_return;
} else {
    $packaging_return = ['spool' => 0 ] +$packaging_return;
}
if($packagingData->packaging3  == 'sack'){
    $packaging_return = ['sack' => $packagingData->sack ] +$packaging_return;
    } else {
        $packaging_return = ['sack' => 0 ] +$packaging_return;
    }
    if($packagingData->packaging2  == 'box'){
        $packaging_return = ['box' => $packagingData->box ] +$packaging_return;
        } else {
            $packaging_return = ['box' => 0 ] +$packaging_return;
        }
        if($packagingData->packaging1  == 'pallet'){
            $packaging_return = ['pallet' => $packagingData->pallet ] +$packaging_return;
            } else {
                $packaging_return = ['pallet' => 0 ] +$packaging_return;
            }
            $packaging_return = ['package_status' => $packagingData->importStatus ] +$packaging_return;
                
//print_r($packaging_return );

$importPackagingImp = Packageast::create($astpackaging);
$importPackagingRet = Packageast::create($astpackaging_return );

$importPackaging = Package::create($packaging_return );
if($importPackaging ){
        return redirect('/material')->with('success', 'Material is successfully saved');
}
        }

        //return redirect('/material')->with('success', 'Material is successfully saved');
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
        $dataEdit = Material::find($id);
        $supplier = Supplier::all();
        $stockYarns = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
            //print($data->yarnType);
            return $data->yarnType;
        });
        return view('material.edit', compact('supplier', 'stockYarns', 'dataEdit'));
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
            'supplierName' => 'required',
            'yarnType' => 'required',
            'spool' => 'required',
            'createDate' => 'required',
            'lot' => 'required',
            'weight_p_net' => 'required',
            'weight_kg_net' => 'required',
            'average_p' => 'required',
            'average_kg' => 'required',
            'importStatus' => 'required',
            'pallet' => 'required',
            'box' => 'required',
            'sack' => 'required',
            'weight_p_sum' => 'required',
            'weight_kg_sum' => 'required',
            'weight_p_package' => 'required',
            'weight_kg_package' => 'required',
            
        ]);
        $dataUpdate = Material::find($id);
        $lastTenRecords = Material::latest()->take(10)->get();
        // Getting values from the blade template form
        $dataUpdate->supplierName = $request->get('supplierName');
        $dataUpdate->yarnType = $request->get('yarnType');
        $dataUpdate->spool = $request->get('spool');
        $dataUpdate->createDate = $request->get('createDate');
        $dataUpdate->lot = $request->get('lot');
        $dataUpdate->weight_p_net = $request->get('weight_p_net');
        $dataUpdate->weight_kg_net = $request->get('weight_kg_net');
        $dataUpdate->average_p = $request->get('average_p');
        $dataUpdate->average_kg = $request->get('average_kg');

        $dataUpdate->importStatus = $request->get('importStatus');
        $dataUpdate->pallet = $request->get('pallet');
        $dataUpdate->box = $request->get('box');
        $dataUpdate->sack = $request->get('sack');
        $dataUpdate->weight_p_sum = $request->get('weight_p_sum');
        $dataUpdate->weight_kg_sum = $request->get('weight_kg_sum');
        $dataUpdate->weight_p_package = $request->get('weight_p_package');
        $dataUpdate->weight_kg_package = $request->get('weight_kg_package');
        $dataUpdate->save();

        // return redirect('/materialstore')->with('success', 'materialstore updated.'); // -> resources/views/stocks/index.blade.php
        $lastTenRecords = Material::latest()->take(10)->get();

        return view('material.index', compact('lastTenRecords'));
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
        $data = Material::find($id);
        $data->delete();

        $lastTenRecords = Material::latest()->take(10)->get();

        return view('material.index', compact('lastTenRecords'));
    }
}
