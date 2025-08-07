<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Coordinator;
use App\Models\Material;
use App\Models\Empmaterial;
use App\Models\Materialstore;
use App\Models\Stuff;

class materialstoreController extends Controller
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
        $lastTenRecords = Materialstore::latest()->take(10)->get();

        return view('materialstore.index', compact('lastTenRecords'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $stuff = Stuff::all();
        $supplier = Supplier::all();

        $stockYarns = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
            //print($data->yarnType);
            return $data->yarnType;
        });

        return view('materialstore.create', compact('supplier', 'stockYarns', 'stuff'));
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
            //print($request->submit );
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
            } elseif ($request->filled('importId')) {
                //$importmaterial = Materialstore::where('importStatus' , 'LIKE' , '%'. $request->importId .'%')->get();
                //var_dump($importmaterial );
                //print($request->importId);
                //$select_search = 'importId';
            } elseif ($request->filled('supId')) {
                $withdrawmaterial = Materialstore::where('supplierName', 'LIKE', '%' . $request->supId . '%')
                    ->orderBy('createDate', 'DESC')
                    ->get();

                //print($request->supId);
                $select_search = 'supId';
                //print(count($withdrawmaterial));
            } elseif ($request->filled('yarnType')) {
                $withdrawmaterial = Materialstore::where('yarnType', 'LIKE', '%' . $request->yarnType . '%')
                    ->orderBy('createDate', 'DESC')
                    ->get();
                //print($request->yarnType );
                $select_search = 'yarnType';
            } elseif ($request->filled('imDate')) {
                $withdrawmaterial = Materialstore::where('createDate', 'LIKE', '%' . $request->imDate . '%')
                    ->orderBy('createDate', 'DESC')
                    ->get();
                //print($request->imDate);
                $select_search = 'imDate';
            } else {
                //$withdrawmaterial = Materialstore::where('importStatus' , 'LIKE' , '%'. $request->importId .'%')->get();
                $select_search = 'non data';
            }

            return view('materialstore.index', compact('withdrawmaterial', 'select_search'));
        }


        if ($request->filled('submit') && $request->submit == 'checkdata') {

            $yarnlist = array();
            for ($i = 0; $i < count($request->yarnType); $i++) {
                // print($request->yarnType[$i]);
                // print($request->spool[$i]);

                $stockYarns = Material::where('yarnType', 'LIKE', '%' . $request->yarnType[$i] . '%')->orderBy('id', 'desc')->get()
                    ->groupBy('lot');
                //print(array_keys($stockYarns) );
                //print_r($stockYarns );
                //print(count($stockYarns ));
                $yarnlist[$request->yarnType[$i]] = $this->lotData($stockYarns, $request->spool[$i]);
                // print_r($this->lotData($stockYarns , $request->spool[$i] ) );
                //print_r($yarnlist);
            }


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
            return view('materialstore.checkwithdraw', compact('data', 'yarnlist'));
        }

        if ($request->filled('submit') && $request->submit == 'save') {
            print(count($request->lot));

            for ($i = 0; $i < count($request->lot); $i++) {



                $data['withdrawId'] = $request->withdrawId[$i];
                $data['department'] = $request->department[$i];
                $data['emp'] = $request->emp[$i];
                $data['supplierName'] = $request->supplierName[$i];
                $data['yarnType'] = $request->yarnType[$i];
                $data['lot'] = $request->lot[$i];
                $data['spool'] = $request->spool[$i];
                $data['weight_p_net'] = $request->weight_p_net[$i];
                $data['weight_kg_net'] = $request->weight_kg_net[$i];
                $data['average_p'] = $request->average_p[$i];
                $data['average_kg'] = $request->average_kg[$i];
                $data['createDate'] = $request->createDate[$i];

                $show = Materialstore::create($data);
            }
            if ($show) {
                return redirect('/materialstore')->with('success', $data);
            }


            //return redirect('/materialstore')->with('success', 'Material withdraw is successfully saved');

        }

        if ($request->filled('submit') && $request->submit == 'back') {
            //print('back');
            return back();
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
        // $data = Materialstore::find($id);
        // $lastTenRecords = Materialstore::latest()->take(10)->get();
        // return view('materialstore.edit', compact('lastTenRecords')); 
        $dataEdit = Materialstore::find($id);
        $stuff = Stuff::all();
        $supplier = Supplier::all();
        $stockYarns = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
            //print($data->yarnType);
            return $data->yarnType;
        });
        return view('materialstore.edit', compact('supplier', 'stockYarns', 'stuff', 'dataEdit'));
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
            'department' => 'required',
            'emp' => 'required',
            'supplierName' => 'required',
            'yarnType' => 'required',
            'spool' => 'required',
            'createDate' => 'required',
            'lot' => 'required',
            'weight_p_net' => 'required',
            'weight_kg_net' => 'required',
            'average_p' => 'required',
            'average_kg' => 'required',
        ]);
        $dataUpdate = Materialstore::find($id);
        $stuff = Stuff::all();
        $supplier = Supplier::all();
        $lastTenRecords = Materialstore::latest()->take(10)->get();
        // Getting values from the blade template form
        $dataUpdate->department = $request->get('department');
        $dataUpdate->emp = $request->get('emp');
        $dataUpdate->supplierName = $request->get('supplierName');
        $dataUpdate->yarnType = $request->get('yarnType');
        $dataUpdate->spool = $request->get('spool');
        $dataUpdate->createDate = $request->get('createDate');
        $dataUpdate->lot = $request->get('lot');
        $dataUpdate->weight_p_net = $request->get('weight_p_net');
        $dataUpdate->weight_kg_net = $request->get('weight_kg_net');
        $dataUpdate->average_p = $request->get('average_p');
        $dataUpdate->average_kg = $request->get('average_kg');
        $dataUpdate->save();

        // return redirect('/materialstore')->with('success', 'materialstore updated.'); // -> resources/views/stocks/index.blade.php
        $lastTenRecords = Materialstore::latest()->take(10)->get();

        return view('materialstore.index', compact('lastTenRecords'));
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
        $data = Materialstore::find($id);
        $data->delete();

        $lastTenRecords = Materialstore::latest()->take(10)->get();

        return view('materialstore.index', compact('lastTenRecords'));
    }

    public static function empData()
    {
        $empMaterial = Empmaterial::all();
        return $empMaterial;
    }

    public function lotData($stockYarns, $spool)
    {
        $lotlist = array();
        //summerry stock 

        foreach ($stockYarns as $key => $stockYarn) {
            //print(' '. $key .' ');

            $temlot = array();
            array_push($temlot, $key);

            if (count($stockYarn) > 1) {
                $c = 0;
                $p = 0;
                $kg = 0;
                foreach ($stockYarn as $sy) {
                    $c += 1;
                    $p += $sy->average_p;
                    $kg += $sy->average_kg;
                }
                $p = $p / $c;
                $kg = $kg / $c;
                array_push($temlot, $spool * $p);
                array_push($temlot, $spool * $kg);
                array_push($temlot, $p);
                array_push($temlot, $kg);
            } else {
                array_push($temlot, $spool * $stockYarn[0]->average_p);
                array_push($temlot, $spool * $stockYarn[0]->average_kg);
                array_push($temlot, $stockYarn[0]->average_p);
                array_push($temlot, $stockYarn[0]->average_kg);
            }
            array_push($lotlist, $temlot);
        }
        return $lotlist;
    }
}
