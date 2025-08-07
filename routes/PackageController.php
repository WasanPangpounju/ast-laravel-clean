<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Supplier;
use App\Models\Coordinator;
use App\Models\Material;
use App\Models\Htrpackage;

class PackageController extends Controller
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
        $package = Package::all();
        $supplier = Supplier::all();
        $duplicate_data = Package::select('supplier_name', Package::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
                ->groupBy('supplier_name')
                ->get();

                $packageList = Package::orderBy('id')->get()->groupBy(function($data) {
                    return $data->supplier_name;
                });
    // print(count($packageList) );
                return view('package.index' ,compact('package','duplicate_data'));
        // return view('package.index' ,compact('package',));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('package.create');
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
       if($request->filled('submit') && $request->submit == 'htrpackage') {
print('htrpackage');

       }
        /*
       if($request->filled('submit') && $request->submit == 'htrpackage') {
/*
        $validatedData = $request->validate([
            'emp' => 'required',
            'supplier_name' => 'required',
            'spool' => 'required',
            'sack' => 'required',
            'box' => 'required',
            'pallet' => 'required',
        ]);

        //$show = package::index($validatedData);
    //$show = Htrpackage::create($validatedData);  
   print('hello');
        //return redirect('/package')->with('success', 'package is successfully saved');
        }else{
            if(! $request->filled('spool')) {
            $request->request->add(['spool' => '0']); 
        }
            if(! $request->filled('box')) {
        $request->request->add(['box' => '0']); 
        }
        if(! $request->filled('pallet')) {
            $request->request->add(['pallet' => '0']); 
            }
                if(! $request->filled('sack')) {
            $request->request->add(['sack' => '0']); 
            }
      
                $validatedData = $request->validate([
                    'supplier_name' => 'required',
                    'spool' => 'required',
                    'box' => 'required',
                    'pallet' => 'required',
                    'sack' => 'required',
                ]);
        
                $show = Package::create($validatedData);  
  */         
                //return redirect('/package')->with('success', 'customer is successfully saved');
        //}
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
    public function edit($supplier_name)
    {
        //
        // $package = Package::all();
        //print_r($supplier_name);
        /*
        $dataEdit = Package::where('supplier_name', $supplier_name)
            ->selectRaw('supplier_name , SUM(box) as boxsum , SUM(spool) as spoolsum , SUM(sack) as sacksum , SUM(pallet) as palletsum')
             ->groupBy($supplier_name) 
            ->get();
            print_r($dataEdit);
            $packageList = Package::orderBy('id')->get()->groupBy(function($data) {
                                return $data->supplier_name;
            });

                */

                $dataEdit = Package::where('supplier_name' , $supplier_name)
                ->groupBy('supplier_name')
                    ->selectRaw('supplier_name as supplierName, SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum')
                    ->get();
        
    
            return view('package.edit' ,compact('dataEdit'));
        // return view('materialstore.edit', compact('supplier', 'stockYarns', 'stuff', 'dataEdit'));

    
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
