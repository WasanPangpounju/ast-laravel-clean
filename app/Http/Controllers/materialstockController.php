<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\MaterialOutside;
use App\Models\Materialstock;
use App\Models\Materialstore;
use App\Models\Supplier;

class materialstockController extends Controller
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
        /*
        $sum_import_package = Material::selectRaw('supplierName , SUM(spool) as sumspool, SUM(sack) as sumsack , SUM(box) as sumbox, SUM(pallet) as sumpallet')
        ->groupBy('supplierName')
        ->get();
    //print(count($sum_import_package ) .' ');
    $cc =0;
foreach($sum_import_package as $sumpackage){
$cc +=1 ;
    print($cc .'. '  .$sumpackage->supplierName );
    print(': <br>'. 'จำนวนพาเลท ' . $sumpackage->sumpallet  .' ไม้ =   / เหล็ก =   ');
print('<br>  จำนวนกล่อง '. $sumpackage->sumbox .' ');
print('<br> จำนวนกระสอบ ' . $sumpackage->sumsack .' พลาสติก =   / ปอ =   ');
print('<br> จำนวนกระดาษกั้น =   ');
print('<br> จำนวนหลอด '. $sumpackage->sumspool .' หลอดกรวย พลาสติก =   / หลอดกรวย กระดาษ =   <br> หลอดทรงกระบอก พลาสติก =   / หลอดทรงกระบอก กระดาษ =   ');
    print('<br> <br>');
}
*/

        //get all supplier 
        $supplier = Supplier::all();

        //get all Yarn type from Material
        $stockYarns = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
            return $data->yarnType;
        });

        $stockList = $this->getStockList1();

        return view('materialstock.index', compact('stockList', 'supplier', 'stockYarns'));
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
        //Search materialstock 
        if ($request->filled('submit') && $request->submit == 'search') {

            //search by yarnType
            if ($request->filled('yarnType')) {
                //print('yarntype');
                //print($request->yarnType);

                //Search by yarnType
                //$yarnType = 'T 300 (A)';
                $yarnType = $request->input('yarnType');
                $stockLatest = '';

                //get last record
                $importLatest = Material::where('yarnType', $yarnType)->latest('created_at')->first();
                if ($importLatest) {
                    //print($importLatest->created_at->format('d-m-Y') );
                    $stockLatest = $importLatest->created_at->format('d-m-Y');
                }

                $sum_import_yarnType = Material::selectRaw('supplierName , SUM(spool) as sumspool,SUM(weight_p_net) as sumweight_p_net , SUM(weight_kg_net) as sumweight_kg_net , AVG(average_p) as average_p , AVG(average_kg) as average_kg')
                    ->where('yarnType', $yarnType)
                    ->groupBy('supplierName')
                    ->get();
                //print(count($sum_import_yarnType) .' ');

                //get latest record
                $withdrawLatest = Materialstore::where('yarnType', $yarnType)->latest('created_at')->first();
                if ($withdrawLatest) {
                    //print($withdrawLatest->created_at->format('d-m-Y') );
                    if ($stockLatest != '') {
                        //check withdraw date > import latest
                        if (strtotime($withdrawLatest->created_at->format('d-m-Y')) > strtotime($stockLatest)) {
                            //update stockLatest
                            $stockLatest = $withdrawLatest->created_at->format('d-m-Y');
                        }
                    } else {
                        $stockLatest = $withdrawLatest->created_at->format('d-m-Y');
                    }
                }


                $sum_withdraw_yarnType = Materialstore::selectRaw('supplierName , SUM(spool) as sumspool,SUM(weight_p_net) as sumweight_p_net , SUM(weight_kg_net) as sumweight_kg_net , AVG(average_p) as average_p , AVG(average_kg) as average_kg')
                    ->where('yarnType', $yarnType)
                    ->groupBy('supplierName')
                    ->get();
                //print(count($sum_import_yarnType) .' ');


                $withdrawoutLatest = MaterialOutside::where('yarnType', $yarnType)->latest('created_at')->first();
                if ($withdrawoutLatest) {
                    //print($withdrawoutLatest->created_at->format('d-m-Y') );
                    if ($stockLatest != '') {
                        //check stock latest > withdraw outside 
                        if (strtotime($withdrawoutLatest->created_at->format('d-m-Y')) > strtotime($stockLatest)) {
                            //update stock latest
                            $stockLatest = $withdrawoutLatest->created_at->format('d-m-Y');
                        }
                    } else {
                        $stockLatest  = $withdrawoutLatest->created_at->format('d-m-Y');
                    }
                }

                $sum_without_yarnType = MaterialOutside::selectRaw('supplierName , SUM(spool) as sumspool,SUM(weight_p_net) as sumweight_p_net , SUM(weight_kg_net) as sumweight_kg_net , AVG(average_p) as average_p , AVG(average_kg) as average_kg')
                    ->where('yarnType', $yarnType)
                    ->groupBy('supplierName')
                    ->get();
                //print(count($sum_import_yarnType) .' ');

                //get all supplier 
                $supplier = Supplier::all();

                //get all Yarn type from Material
                $stockYarns = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
                    return $data->yarnType;
                });

                if ($stockLatest  == '') {
                    $stockLatest = 'ไม่มีรายการล่าสุด';
                }

                //print($stockLatest);
                $stockList = $this->getStockList1();

                return view('materialstock.index', compact('sum_import_yarnType', 'sum_withdraw_yarnType', 'sum_without_yarnType', 'supplier', 'stockYarns', 'stockList', 'yarnType', 'stockLatest'));
            } elseif ($request->filled('supplier')) {

                //print('supplier');
                $supplierName = 'บริษัท กังวาลเท็กซ์ไทล์ จำกัด';
                $supplierName = $request->input('supplier');
                //$request->filled('supplier');


                $stockLatest = '';

                //get last record
                $importLatest = Material::where('supplierName', $supplierName)->latest('created_at')->first();
                if ($importLatest) {
                    //print($importLatest->created_at->format('d-m-Y') );
                    $stockLatest = $importLatest->created_at->format('d-m-Y');
                }

                $sum_import_supplier = Material::selectRaw('yarnType, SUM(spool) as sumspool,SUM(weight_p_net) as sumweight_p_net , SUM(weight_kg_net) as sumweight_kg_net , AVG(average_p) as average_p , AVG(average_kg) as average_kg')
                    ->where('supplierName', $supplierName)
                    ->groupBy('yarnType')
                    ->get();
                //print(count($sum_import_supplier ) .' ');


                //get latest record
                $withdrawLatest = Materialstore::where('supplierName', $supplierName)->latest('created_at')->first();
                if ($withdrawLatest) {
                    //print($withdrawLatest->created_at->format('d-m-Y') );
                    if ($stockLatest != '') {
                        //check withdraw date > import latest
                        if (strtotime($withdrawLatest->created_at->format('d-m-Y')) > strtotime($stockLatest)) {
                            //update stockLatest
                            $stockLatest = $withdrawLatest->created_at->format('d-m-Y');
                        }
                    } else {
                        $stockLatest = $withdrawLatest->created_at->format('d-m-Y');
                    }
                }


                $sum_withdraw_supplier = Materialstore::selectRaw('yarnType, SUM(spool) as sumspool,SUM(weight_p_net) as sumweight_p_net , SUM(weight_kg_net) as sumweight_kg_net , AVG(average_p) as average_p , AVG(average_kg) as average_kg')
                    ->where('supplierName', $supplierName)
                    ->groupBy('yarnType')
                    ->get();
                //print(count($sum_withdraw_supplier ) .' ');


                $withdrawoutLatest = MaterialOutside::where('supplierName', $supplierName)->latest('created_at')->first();
                if ($withdrawoutLatest) {
                    //print($withdrawoutLatest->created_at->format('d-m-Y') );
                    if ($stockLatest != '') {
                        //check stock latest > withdraw outside 
                        if (strtotime($withdrawoutLatest->created_at->format('d-m-Y')) > strtotime($stockLatest)) {
                            //update stock latest
                            $stockLatest = $withdrawoutLatest->created_at->format('d-m-Y');
                        }
                    } else {
                        $stockLatest  = $withdrawoutLatest->created_at->format('d-m-Y');
                    }
                }

                $sum_without_supplier = MaterialOutside::selectRaw('yarnType, SUM(spool) as sumspool,SUM(weight_p_net) as sumweight_p_net , SUM(weight_kg_net) as sumweight_kg_net , AVG(average_p) as average_p , AVG(average_kg) as average_kg')
                    ->where('supplierName', $supplierName)
                    ->groupBy('yarnType')
                    ->get();
                //print(count($sum_withdraw_supplier ) .' ');

                //get all supplier 
                $supplier = Supplier::all();

                //get all Yarn type from Material
                $stockYarns = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
                    return $data->yarnType;
                });

                if ($stockLatest  == '') {
                    $stockLatest = 'ไม่มีรายการล่าสุด';
                }
                //print('*' . $stockLatest );
                $stockList = $this->getStockList1();

                return view('materialstock.index', compact('sum_import_supplier', 'sum_withdraw_supplier', 'sum_without_supplier', 'supplier', 'stockYarns', 'stockList', 'supplierName', 'stockLatest'));
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


    private function getStockList1()
    {
        $yarnSumList = [];
        $stockList = [];

        //Get all material import group by yarnType
        $stockYarns  = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
            return $data->yarnType;
        });

        $supplierList = Material::orderBy('supplierName')->get()->groupBy(function ($data) {
            return $data->supplierName;
        });


        //loop by yarnType
        foreach ($stockYarns as $simp) {

            $yarnSumList = [];

            //loop by Supplier name 
            foreach ($supplierList as $spl) {
                //query data by YarnType and supplier name
                $stockQuery = Material::where('yarnType', 'LIKE', $simp[0]->yarnType)
                    ->where('supplierName', "=", $spl[0]->supplierName)->orderBy('id', 'desc')->get();


                //Check query data 
                if (count($stockQuery) >= 1) {
                    $stockImport = new Materialstock();
                    //get last record
                    $importLatest = Material::where('yarnType', 'LIKE', $simp[0]->yarnType)
                        ->where('supplierName', "=", $spl[0]->supplierName)
                        ->latest('created_at')->first();
                    if ($importLatest) {
                        //print($importLatest->created_at->format('d-m-Y') );
                        $stockLatest = $importLatest->created_at->format('d-m-Y');
                    }

                    $spool = 0;
                    $weight_p_net = 0;
                    $weight_kg_net = 0;
                    $average_p  = 0;
                    $average_kg  = 0;
                    $lot = ' ';

                    $stockImport->yarnType = $spl[0]->yarnType;
                    //$stockImport->createDate = date("m/d/Y");
                    $stockImport->supplierName = $spl[0]->supplierName;

                    //loop data and weight summerry 
                    foreach ($stockQuery as $temp) {
                        //sum spool and weight
                        $spool += $temp->spool;
                        $weight_p_net += $temp->weight_p_net;
                        $weight_kg_net += $temp->weight_kg_net;

                        $stockImport->yarnType  = $temp->yarnType;
                    } //end loop data

                    //check import sum spool > 0
                    if ($spool  > 0) {
                        $average_p = $weight_p_net / $spool;
                        $average_kg = $weight_kg_net / $spool;
                    } else {
                        $average_p = $weight_p_net / 1;
                        $average_kg = $weight_kg_net / 1;
                    }

                    //Check stock withdraw by yarntype and supplierName
                    $stockWithdraws = Materialstore::where('yarnType', 'LIKE', $stockImport->yarnType)
                        ->where('supplierName', "=", $stockImport->supplierName)->orderBy('id', 'desc')->get();

                    //Check query data material withdraw
                    if (count($stockWithdraws) >= 1) {
                        //get latest record
                        $withdrawLatest = Materialstore::where('yarnType', 'LIKE', $stockImport->yarnType)
                            ->where('supplierName', "=", $stockImport->supplierName)
                            ->latest('created_at')->first();
                        if ($withdrawLatest) {
                            //print($withdrawLatest->created_at->format('d-m-Y') );
                            if ($stockLatest != '') {
                                //check withdraw date > import latest
                                if (strtotime($withdrawLatest->created_at->format('d-m-Y')) > strtotime($stockLatest)) {
                                    //update stockLatest
                                    $stockLatest = $withdrawLatest->created_at->format('d-m-Y');
                                }
                            } else {
                                $stockLatest = $withdrawLatest->created_at->format('d-m-Y');
                            }
                        }


                        $spoolWithdraw = 0;
                        $weight_p_net_withdraw = 0;
                        $weight_kg_net_withdraw = 0;

                        //Loop for summerry stock with draw 
                        foreach ($stockWithdraws as $stockWithdraw) {
                            //Summerry stock withdraw
                            $spoolWithdraw += $stockWithdraw->spool;
                            // $weight_p_net_withdraw += $stockWithdraw->weight_p_net;
                            $weight_p_net_withdraw += preg_replace('/[^0-9.]/', '', $stockWithdraw->weight_p_net);
                            // $weight_kg_net_withdraw += $stockWithdraw->weight_kg_net;
                            $weight_kg_net_withdraw += preg_replace('/[^0-9.]/', '', $stockWithdraw->weight_kg_net);

                        } //end loop data material withdraw
                        // print_r($weight_p_net_withdraw . '----  /');
                        // $test = count($weight_p_net_withdraw);
                        // print_r($test . '----  /');
                        //stock import - stock withdraw
                        $spool -= $spoolWithdraw;
                        $weight_p_net = $spool * $average_p;
                        $weight_kg_net = $spool * $average_kg;

                        //$weight_p_net -= $weight_p_net_withdraw;
                        //$weight_kg_net -= $weight_kg_net_withdraw;

                    } //endif check query data material with draw


                    //Check stock outside by yarntype and supplierName
                    $stockWithdrawsOutside = MaterialOutside::where('yarnType', 'LIKE', $stockImport->yarnType)
                        ->where('supplierName', "=", $stockImport->supplierName)->orderBy('id', 'desc')->get();

                    //Check query data material outside
                    if (count($stockWithdrawsOutside) >= 1) {
                        $withdrawoutLatest = MaterialOutside::where('yarnType', 'LIKE', $stockImport->yarnType)
                            ->where('supplierName', "=", $stockImport->supplierName)
                            ->latest('created_at')->first();
                        if ($withdrawoutLatest) {
                            //print($withdrawoutLatest->created_at->format('d-m-Y') );
                            if ($stockLatest != '') {
                                //check stock latest > withdraw outside 
                                if (strtotime($withdrawoutLatest->created_at->format('d-m-Y')) > strtotime($stockLatest)) {
                                    //update stock latest
                                    $stockLatest = $withdrawoutLatest->created_at->format('d-m-Y');
                                }
                            } else {
                                $stockLatest  = $withdrawoutLatest->created_at->format('d-m-Y');
                            }
                        }

                        $spoolOutside = 0;
                        $weight_p_net_outside = 0;
                        $weight_kg_net_outside = 0;

                        //Loop for summerry stock with draw 
                        foreach ($stockWithdrawsOutside as $swOutside) {
                            //Summerry stock withdraw
                            $spoolOutside += $swOutside->spool;
                            $weight_p_net_outside += $swOutside->weight_p_net;
                            $weight_kg_net_outside += $swOutside->weight_kg_net;
                        } //end loop data material withdraw

                        //stock import - stock outside
                        $spool -= $spoolOutside;
                        $weight_p_net = $spool * $average_p;
                        $weight_kg_net = $spool * $average_kg;

                        //$weight_p_net -= $weight_p_net_outside;
                        //$weight_kg_net -= $weight_kg_net_outside;

                    } //endif check query data material outside


                    //set material stock total
                    $stockImport->spool = $spool;
                    $stockImport->weight_p_net = $weight_p_net;
                    $stockImport->weight_kg_net = $weight_kg_net;
                    $stockImport->average_p = $average_p;
                    $stockImport->average_kg = $average_kg;

                    if ($stockLatest  == '') {
                        $stockLatest = 'ไม่มีรายการล่าสุด';
                    }
                    $stockImport->createDate = $stockLatest;


                    array_push($yarnSumList, $stockImport);
                } //end if check query


            } //end loop supplierName

            //$yarnSumList  = array_reverse($yarnSumList);
            array_push($stockList, $yarnSumList);
        } //end loop yarnType


        return $stockList;
    }
}
