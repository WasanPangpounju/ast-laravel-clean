<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\Materialstore;
use App\Models\MaterialOutside;
use App\Models\Packageoutside;
use App\Models\Astpackageoutside;

class MaterialOutsideController extends Controller
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
        $lastTenRecords = MaterialOutside::latest()->take(10)->get();

        // return view('materialoutside.index', compact('lastTenRecords'));
        return view('materialoutside.index', compact('lastTenRecords'));
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
        // $Materialoutside = Materialoutside::all();
        $supplier = Supplier::all();
        //Get all material import group by yarnType
        $stockYarns = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
            return $data->yarnType;
        });

        return view('materialoutside.create', compact('supplier', 'stockYarns'));
    }
    public static function supData()
    {
        $supplier = Supplier::all();

        return $supplier;
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
        if ($request->filled('submit') && $request->submit == 'searchwithdraw') {
            // print($request->submit);
            $select_search = '';

            if ($request->filled('importId') && $request->filled('supId') && $request->filled('yarnType') && $request->filled('imDate')) {
                //print('1 2 3 4');
            } elseif ($request->filled('importId') && $request->filled('supId') && $request->filled('yarnType')) {
                //print('1 2 3');
            } elseif ($request->filled('supId') && $request->filled('yarnType') && $request->filled('imDate')) {
                //print('2 3 4');
            } elseif ($request->filled('importId') && $request->filled('supId')) {
                //print('1 2');
            } elseif ($request->filled('yarnType') && $request->filled('imDate')) {
                //print('3 4');
            } elseif ($request->filled('supId') && $request->filled('yarnType')) {
                //print('2 3');
            } elseif ($request->filled('importId') && $request->filled('imDate')) {
                //print('1 4');
            } elseif ($request->filled('importId') && $request->filled('yarnType')) {
                //print('1 3');
            } elseif ($request->filled('supId') && $request->filled('imDate')) {
                //print('2 4');
            } elseif ($request->filled('supId')) {
                $withdrawmaterial = Materialoutside::where('supplierName', 'LIKE', '%' . $request->supId . '%')->get();
                // print($request->supId);
                $select_search = 'supId';
                $searchInput = $request->supId;
            } elseif ($request->filled('yarnType')) {
                $withdrawmaterial = Materialoutside::where('yarnType', 'LIKE', '%' . $request->yarnType . '%')->get();
                // print($request->yarnType);
                $select_search = 'yarnType';
                $searchInput = $request->yarnType;
            } elseif ($request->filled('imDate')) {
                $withdrawmaterial = Materialoutside::where('createDate', 'LIKE', '%' . $request->imDate . '%')->get();
                // print($request->imDate);
                $select_search = 'imDate';
                $searchInput = $request->imDate;
            } else {
                $withdrawmaterial = Materialoutside::where('importStatus', 'LIKE', '%' . $request->importId . '%')->get();
                $select_search = 'non data';
            }

            return view('materialoutside.index', compact('withdrawmaterial', 'select_search', 'searchInput'));
        }

        if ($request->filled('submit') && $request->submit == 'cleanForm') {
            $supplier = Supplier::all();

            //Get all material import group by yarnType
            $stockYarns = Materialoutside::orderBy('yarnType')->get()->groupBy(function ($data) {
                return $data->yarnType;
            });

            return view('materialoutside.create', compact('supplier', 'stockYarns'));
        }

        if ($request->filled('submit') && $request->submit == 'back') {
            $supplier = Supplier::all();

            //Get all material import group by yarnType
            $stockYarns = Materialoutside::orderBy('yarnType')->get()->groupBy(function ($data) {
                return $data->yarnType;
            });

            //send import data to create page
            $backdata = $request;

            return view('materialoutside.create', compact('supplier', 'stockYarns', 'backdata'));
        }

        if ($request->filled('submit') && $request->submit == 'checkdata') {
            //print($request->submit);
            // if (!$request->filled('createDate')) {
            //     $request->request->add(['createDate' => date("m/d/Y")]);
            // }

            // if (!$request->filled('lot')) {
            //     $request->request->add(['lot' => date("Y") . '1']);
            // }
            // if (!$request->filled('pallet')) {
            //     $request->request->add(['pallet' => '0']);
            // }
            // if (!$request->filled('box')) {
            //     $request->request->add(['box' => '0']);
            // }
            // if (!$request->filled('sack')) {
            //     $request->request->add(['sack' => '0']);
            // }

            // if (!$request->filled('comment')) {
            //     $request->request->add(['comment' => '   ']);
            // }

            // $imdata = $request;



            //print($imdata->supplierName );
            // print_r($data);
            // return view('materialoutside.checkwithdraw', compact('imdata'));
/*
            if (!$request->filled('createDate')) {
                $request->request->add(['createDate' => date("m/d/Y")]);
            }
            if (!$request->filled('lot')) {
                $request->request->add(['lot' => date("Y") . '1']);
            }
            if (!$request->filled('withdrawId')) {
                $request->request->add(['withdrawId' => 'no withdrawId number']);
            }
            $data = $request;
            //return view('materialoutside.checkwithdraw', compact('data', 'yarnlist'));
            return view('materialoutside.checkwithdraw', compact('data'));
*/
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

            if (!$request->filled('comment')) {
                $request->request->add(['comment' => '   ']);
            }

            $imdata = $request;
            //print($imdata->supplierName );
            // print_r($data);
            return view('materialoutside.checkwithdraw', compact('imdata'));

        }

        if ($request->filled('submit') && $request->submit == 'save') {
            $validatedData = $request->validate([
                'emp' => 'required',
                'supplierName' => 'required',
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
                'recipient' => 'required',
                'comment' => 'nullable',
                'paymentComment' => 'nullable'
            ]);
            //print_r($validatedData);
            $show = MaterialOutside::updateOrCreate($validatedData);

            if ($show) {
                //Copy submit data to packaging data
                $packagingData = $request;
                $packaging_return = [];
                //print($packagingData->packaging1);
                //print($packagingData->packaging2);
                //print($packagingData->packaging3);
                //print($packagingData->packaging4);

                //variable package outside and package return to ast
$outpackaging = [];
$retpackaging = [];

//set data package import
$outpackaging = ['receiver' => $packagingData->recipient ] + $outpackaging;
$outpackaging = ['emp' => $packagingData->emp ] + $outpackaging;
$outpackaging = ['ref_id' => $show->id ] + $outpackaging;
$outpackaging = ['supplier_name' => $packagingData->supplierName ] + $outpackaging;
$outpackaging = ['spool' => $packagingData->spool ] + $outpackaging;
$outpackaging = ['sack' => $packagingData->sack ] + $outpackaging;
$outpackaging = ['box' => $packagingData->box ] + $outpackaging;
$outpackaging = ['pallet' => $packagingData->pallet ] + $outpackaging;
$outpackaging = ['package_status' => 'packageOutside'] + $outpackaging;

$outpackaging = ['spool_type' => $request->filled('spool_type') ? $request->spool_type : null ] + $outpackaging;
$outpackaging = ['sack_type' => $request->filled('sack_type') ? $request->sack_type : null ] + $outpackaging;
$outpackaging = ['box_type' => $request->filled('box_type') ? $request->box_type : null] + $outpackaging;
$outpackaging = ['pallet_type' => $request->filled('pallet_type') ? $request->pallet_type : null ] + $outpackaging;
$outpackaging = ['partition' => $request->filled('partition') ? $request->partition: null ] + $outpackaging;
$outpackaging = ['partition_type' => $request->filled('partition_type') ? $request->partition_type: null ] + $outpackaging;

//set data for return package to ast
$retpackaging = ['receiver' => $packagingData->recipient ] + $retpackaging;
$retpackaging = ['emp' => $packagingData->emp ] + $retpackaging;
$retpackaging = ['ref_id' => $show->id] + $retpackaging;
$retpackaging = ['supplier_name' => $packagingData->supplierName ] + $retpackaging;
if($packagingData->packaging4  == 'spool'){
    $retpackaging = ['spool' => $packagingData->spool ] + $retpackaging;
    $retpackaging = ['spool_type' => $request->filled('spool_type') ? $request->spool_type : null ] + $retpackaging;
    } else {
        $retpackaging = ['spool' => 0 ] + $retpackaging;
        $retpackaging = ['spool_type' => $request->filled('spool_type') ? $request->spool_type: null ] + $retpackaging;
    }
    if($packagingData->packaging3  == 'sack'){
        $retpackaging = ['sack' => $packagingData->sack ] + $retpackaging;
        $retpackaging = ['sack_type' => $request->filled('sack_type') ? $request->sack_type : null ] + $retpackaging;
        } else {
            $retpackaging = ['sack' => 0 ] + $retpackaging;
            $retpackaging = ['sack_type' => $request->filled('sack_type') ? $request->sack_type : null ] + $retpackaging;
        }
        if($packagingData->packaging2  == 'box'){
            $retpackaging = ['box' => $packagingData->box ] + $retpackaging;
            $retpackaging = ['box_type' => $request->filled('box_type') ? $request->box_type : null] + $retpackaging;
            } else {
                $retpackaging = ['box' => 0 ] + $retpackaging;
                $retpackaging = ['box_type' => $request->filled('box_type') ? $request->box_type : null] + $retpackaging;
            }
            if($packagingData->packaging1  == 'pallet'){
                $retpackaging = ['pallet' => $packagingData->pallet ] + $retpackaging;
                $retpackaging = ['pallet_type' => $request->filled('pallet_type') ? $request->pallet_type : null ] + $retpackaging;
                } else {
                    $retpackaging = ['pallet' => 0 ] + $retpackaging;
                    $retpackaging = ['pallet_type' => $request->filled('pallet_type') ? $request->pallet_type : null ] + $retpackaging;
                }
                $retpackaging = ['package_status' => 'packageCallback'] + $retpackaging;
                $retpackaging = ['partition' => $request->filled('partition') ? $request->partition : null ] + $retpackaging;
                $retpackaging = ['partition_type' => $request->filled('partition_type') ? $request->partition_type : null ] + $retpackaging;
                

                $packaging_return = ['emp' => $packagingData->emp] + $packaging_return;
                $packaging_return = ['supplier_id' => Date('m/d/Y')] + $packaging_return;
                $packaging_return = ['supplier_name' => $packagingData->supplierName] + $packaging_return;
                $packageKey = $show->id;

                if ($packagingData->packaging4 == 'spool') {
                    $packaging_return = ['spool' => $packagingData->spool] + $packaging_return;
                } else {
                    $packaging_return = ['spool' => 0] + $packaging_return;
                }
                if ($packagingData->packaging3 == 'sack') {
                    $packaging_return = ['sack' => $packagingData->sack] + $packaging_return;
                } else {
                    $packaging_return = ['sack' => 0] + $packaging_return;
                }
                if ($packagingData->packaging2 == 'box') {
                    $packaging_return = ['box' => $packagingData->box] + $packaging_return;
                } else {
                    $packaging_return = ['box' => 0] + $packaging_return;
                }
                if ($packagingData->packaging1 == 'pallet') {
                    $packaging_return = ['pallet' => $packagingData->pallet] + $packaging_return;
                } else {
                    $packaging_return = ['pallet' => 0] + $packaging_return;
                }
                //$packaging_return = ['package_status' => $packagingData->importStatus] + $packaging_return;
                $packaging_return = ['package_status' =>  $packageKey] + $packaging_return;

                //print_r($packaging_return );

                $insertPackagingOutside = Astpackageoutside::updateOrCreate($outpackaging);
$insertPackagingcallback = Astpackageoutside::updateOrCreate($retpackaging );

                $importPackaging = Packageoutside::updateOrCreate($packaging_return);
                if ($importPackaging) {
                    return redirect('/materialoutside')->with('success', 'Material is successfully saved');
                }
            }
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
        $dataEdit = MaterialOutside::find($id);
        $supplier = Supplier::all();
        $stockYarns = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
            //print($data->yarnType);
            return $data->yarnType;
        });
        //print($dataEdit->comment );
        return view('materialoutside.edit', compact('supplier', 'stockYarns', 'dataEdit'));
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
        /*
        $request->validate([
            'supplierName' => 'required', 
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
            'recipient' => 'required', 
                            'comment' => 'nullable',
                'paymentComment' => 'nullable'
        ]);
*/
        $dataUpdate = MaterialOutside::find($id);


        $lastTenRecords = MaterialOutside::latest()->take(10)->get();

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

        $dataUpdate->pallet = $request->get('pallet');
        $dataUpdate->box = $request->get('box');
        $dataUpdate->sack = $request->get('sack');
        $dataUpdate->weight_p_sum = $request->get('weight_p_sum');
        $dataUpdate->weight_kg_sum = $request->get('weight_kg_sum');
        $dataUpdate->weight_p_package = $request->get('weight_p_package');
        $dataUpdate->weight_kg_package = $request->get('weight_kg_package');
        $dataUpdate->recipient = $request->get('recipient');
        $dataUpdate->comment = $request->get('comment');
        $dataUpdate->paymentComment = $request->get('paymentComment');


        $dataUpdate->save();

        $lastTenRecords = MaterialOutside::latest()->take(10)->get();

        return view('materialoutside.index', compact('lastTenRecords'));
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
        //
        $data = MaterialOutside::find($id);
        $data->delete();

        $record = Packageoutside::where('package_status', $id)->first();

        // Delete the record
        if ($record) {
            $record->delete();
        }

        $lastTenRecords = MaterialOutside::latest()->take(10)->get();

        return view('materialoutside.index', compact('lastTenRecords'));
    }
}
