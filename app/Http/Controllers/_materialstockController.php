<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
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
        $yarnSumList = [];
$stockList = [];

//Get all material import group by yarnType
$stockYarns  = Material::orderBy('id')->get()->groupBy(function($data) {
            return $data->yarnType;
        });

$supplierList = Material::orderBy('id')->get()->groupBy(function($data) {
            return $data->supplierName;
        });

//print(count($stockYarns ) .' ');
//print(count($supplierList ));

//loop by yarnType
foreach($stockYarns as $simp) {
//print($simp[0]->yarnType .' ');
$yarnSumList = [];

$sumYarntype = '';
$sumspool = 0;
$sumweight_p_net = 0;
$sumweight_kg_net = 0;
$sumaverage_p = 0;
$sumaverage_kg = 0;
$sumlot = ' ';
$sumsupplierName = 'รวม';

//loop by Supplier name 
foreach($supplierList as $spl){
//print(count($spl) .' ');

//query data by YarnType and supplier name
$stockQuery = Material::where('yarnType' , 'LIKE' , $simp[0]->yarnType )
->where('supplierName' , "=" , $spl[0]->supplierName )->orderBy('id' , 'desc')->get();
//print(count($stockQuery ));

//Check query data 
if(count($stockQuery) >= 1 ){
    $stockImport = new Materialstock();

$spool = 0;
$weight_p_net = 0;
$weight_kg_net = 0;
$lot = ' ';

$stockImport->yarnType = $spl[0]->yarnType;
$stockImport->createDate = date("m/d/Y");
$stockImport->supplierName = $spl[0]->supplierName;
//$supplierName = $spl[0]->supplierName;

//loop data and weight summerry 
foreach($stockQuery as $temp){

    $spool += $temp->spool;
$weight_p_net += $temp->weight_p_net;
$weight_kg_net += $temp->weight_kg_net;

//print($temp->weight_p_net .' ');
//print($temp->yarnType  .' ' . $temp->supplierName .$c .' ');

$stockImport->yarnType  = $temp->yarnType;
}

//Check stock withdraw by yarntype and supplierName
$stockWithdraws = Materialstore::where('yarnType' , 'LIKE' , '%'. $stockImport->yarnType . '%' )
->where('supplierName' , "=" , $stockImport->supplierName )->orderBy('id' , 'desc')->get();

//Check query data 
if(count($stockWithdraws) >= 1 ){
    
    $spoolWithdraw =0;
$weight_p_net_withdraw =0;
$weight_kg_net_withdraw = 0;

//Loop for summerry stock with draw 
foreach($stockWithdraws as $stockWithdraw){

//Summerry stock withdraw
$spoolWithdraw += $stockWithdraw->spool;
$weight_p_net_withdraw += $stockWithdraw->weight_p_net;
$weight_kg_net_withdraw += $stockWithdraw->weight_kg_net;
}

    //stock import - stock withdraw
    $spool -= $spoolWithdraw;
    $weight_p_net -= $weight_p_net_withdraw;
    $weight_kg_net -= $weight_kg_net_withdraw;
}

//print($stockImport->spool . ' ');
//if($stockImport->spool == 0){
//$stockImport->average_p = $stockImport->weight_p_net / 1;
//$stockImport->average_kg = $stockImport->weight_kg_net / 1;
//print('x');
//} else {
    //$stockImport->average_p = $stockImport->weight_p_net / $stockImport->spool;
    //$stockImport->average_kg = $stockImport->weight_kg_net / $stockImport->spool;
    //print('y');
//}


$stockImport->spool = $spool;
$stockImport->weight_p_net = $weight_p_net;
$stockImport->weight_kg_net = $weight_kg_net;
//$stockImport->average_p = $stockImport->weight_p_net / $stockImport->spool;
//$stockImport->average_kg = $stockImport->weight_kg_net / $stockImport->spool;
//print($spool .' ');
if($spool == 0){
//print(' x ');
$stockImport->average_p = $weight_p_net /1;
$stockImport->average_kg = $weight_kg_net / 1;

}else {
$stockImport->average_p = $weight_p_net / $spool;
$stockImport->average_kg = $weight_kg_net / $spool;
}

//print($stockImport->yarnType  .' '.$stockImport->supplierName  .' จำนวน ' . $stockImport->spool .' ');

array_push($yarnSumList , $stockImport);

//summerry all supplier
$sumYarntype = $stockImport->yarnType;
$sumspool += $spool ;
$sumweight_p_net += $weight_p_net ;
$sumweight_kg_net += $weight_kg_net;

//print($stockImport->yarnType .' ' . $spool .'<br>');
//print($spl[0]->supplierName .' sum weight_p_net = ' . $weight_p_net .'<br>');

}//end if

} //end loop supplier
//print('รวม ' . $sumspool );
//print(' x ');

$sumstockImport = new Materialstock();
$sumstockImport->yarnType = $sumYarntype;
$sumstockImport->createDate = date("m/d/Y");
$sumstockImport->supplierName = $sumsupplierName;
$sumstockImport->lot = $sumlot;
$sumstockImport->spool = $sumspool;
$sumstockImport->weight_p_net = $sumweight_p_net;
$sumstockImport->weight_kg_net = $sumweight_kg_net;
$sumstockImport->average_p = $sumstockImport->weight_p_net / $sumstockImport->spool;
$sumstockImport->average_kg = $sumstockImport->weight_kg_net / $sumstockImport->spool;

//print($sumstockImport->yarnType .' ' . $sumstockImport->supplierName .' '. $sumstockImport->spool .' ลูก');

//array_push($yarnSumList , $sumstockImport);
$yarnSumList  = array_reverse($yarnSumList );
array_push($stockList , $yarnSumList );
} //end loop stock with yarnType


//get all supplier 
$supplier = Supplier::all();

//get all Yarn type from Material
$stockYarns = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
    return $data->yarnType;
});

        return view('materialstock.index' ,compact('stockList' , 'supplier' , 'stockYarns'));

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
        if($request->filled('submit') && $request->submit== 'search') {

            //search by yarnType
            if($request->filled('yarnType') ){
//print('yarntype');
//print($request->yarnType);

//Search by yarnType
//$yarnType = 'T 300 (A)';
$yarnType = $request->input('yarnType');

$sum_import_yarnType = Material::selectRaw('supplierName , SUM(spool) as sumspool,SUM(weight_p_net) as sumweight_p_net , SUM(weight_kg_net) as sumweight_kg_net , AVG(average_p) as average_p , AVG(average_kg) as average_kg')
->where('yarnType' , $yarnType )
     ->groupBy('supplierName') 
    ->get();
    //print(count($sum_import_yarnType) .' ');
    
    $sum_withdraw_yarnType = Materialstore::selectRaw('supplierName , SUM(spool) as sumspool,SUM(weight_p_net) as sumweight_p_net , SUM(weight_kg_net) as sumweight_kg_net , AVG(average_p) as average_p , AVG(average_kg) as average_kg')
    ->where('yarnType' , $yarnType )
         ->groupBy('supplierName') 
        ->get();
        //print(count($sum_import_yarnType) .' ');

                        //get all supplier 
                        $supplier = Supplier::all();
                
                        //get all Yarn type from Material
                        $stockYarns = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
                            return $data->yarnType;
                        });
                        
                        $stockList = $this->getStockList();
                                return view('materialstock.index' ,compact('sum_import_yarnType' , 'sum_withdraw_yarnType' , 'supplier' , 'stockYarns' ,'stockList' , 'yarnType' ));
        
            } elseif($request->filled('supplier') ){

                //print('supplier');
                $supplierName = 'บริษัท กังวาลเท็กซ์ไทล์ จำกัด';
                $supplierName = $request->input('supplier');
                //$request->filled('supplier');
                
                $sum_import_supplier = Material::selectRaw('yarnType, SUM(spool) as sumspool,SUM(weight_p_net) as sumweight_p_net , SUM(weight_kg_net) as sumweight_kg_net , SUM(average_p) as sumaverage_p , SUM(average_kg) as sumaverage_kg')
                ->where('supplierName', $supplierName)
                     ->groupBy('yarnType') 
                    ->get();
                    //print(count($sum_import_supplier ) .' ');
                    $sum_withdraw_supplier = Materialstore::selectRaw('yarnType, SUM(spool) as sumspool,SUM(weight_p_net) as sumweight_p_net , SUM(weight_kg_net) as sumweight_kg_net , SUM(average_p) as sumaverage_p , SUM(average_kg) as sumaverage_kg')
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

                $stockList            = $this->getStockList();
                        return view('materialstock.index' ,compact('sum_import_supplier' , 'sum_withdraw_supplier' , 'supplier' , 'stockYarns' , 'stockList' , 'supplierName' ));
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


private function getStockList(){
    $yarnSumList = [];
    $stockList = [];
    
    //Get all material import group by yarnType
    $stockYarns  = Material::orderBy('id')->get()->groupBy(function($data) {
                return $data->yarnType;
            });
    
    $supplierList = Material::orderBy('id')->get()->groupBy(function($data) {
                return $data->supplierName;
            });
    
    //print(count($stockYarns ) .' ');
    //print(count($supplierList ));
    
    //loop by yarnType
    foreach($stockYarns as $simp) {
    //print($simp[0]->yarnType .' ');
    $yarnSumList = [];
    
    $sumYarntype = '';
    $sumspool = 0;
    $sumweight_p_net = 0;
    $sumweight_kg_net = 0;
    $sumaverage_p = 0;
    $sumaverage_kg = 0;
    $sumlot = ' ';
    $sumsupplierName = 'รวม';
    
    //loop by Supplier name 
    foreach($supplierList as $spl){
    //print(count($spl) .' ');
    
    //query data by YarnType and supplier name
    $stockQuery = Material::where('yarnType' , 'LIKE' , $simp[0]->yarnType )
    ->where('supplierName' , "=" , $spl[0]->supplierName )->orderBy('id' , 'desc')->get();
    //print(count($stockQuery ));
    
    //Check query data 
    if(count($stockQuery) >= 1 ){
        $stockImport = new Materialstock();
    
    $spool = 0;
    $weight_p_net = 0;
    $weight_kg_net = 0;
    $lot = ' ';
    
    $stockImport->yarnType = $spl[0]->yarnType;
    $stockImport->createDate = date("m/d/Y");
    $stockImport->supplierName = $spl[0]->supplierName;
    //$supplierName = $spl[0]->supplierName;
    
    //loop data and weight summerry 
    foreach($stockQuery as $temp){
    
        $spool += $temp->spool;
    $weight_p_net += $temp->weight_p_net;
    $weight_kg_net += $temp->weight_kg_net;
    
    //print($temp->weight_p_net .' ');
    //print($temp->yarnType  .' ' . $temp->supplierName .$c .' ');
    
    $stockImport->yarnType  = $temp->yarnType;
    }
    
    //Check stock withdraw by yarntype and supplierName
    $stockWithdraws = Materialstore::where('yarnType' , 'LIKE' , '%'. $stockImport->yarnType . '%' )
    ->where('supplierName' , "=" , $stockImport->supplierName )->orderBy('id' , 'desc')->get();
    
    //Check query data 
    if(count($stockWithdraws) >= 1 ){
        
        $spoolWithdraw =0;
    $weight_p_net_withdraw =0;
    $weight_kg_net_withdraw = 0;
    
    //Loop for summerry stock with draw 
    foreach($stockWithdraws as $stockWithdraw){
    
    //Summerry stock withdraw
    $spoolWithdraw += $stockWithdraw->spool;
    $weight_p_net_withdraw += $stockWithdraw->weight_p_net;
    $weight_kg_net_withdraw += $stockWithdraw->weight_kg_net;
    }
    
        //stock import - stock withdraw
        $spool -= $spoolWithdraw;
        $weight_p_net -= $weight_p_net_withdraw;
        $weight_kg_net -= $weight_kg_net_withdraw;
    }
    
    //print($stockImport->spool . ' ');
    //if($stockImport->spool == 0){
    //$stockImport->average_p = $stockImport->weight_p_net / 1;
    //$stockImport->average_kg = $stockImport->weight_kg_net / 1;
    //print('x');
    //} else {
        //$stockImport->average_p = $stockImport->weight_p_net / $stockImport->spool;
        //$stockImport->average_kg = $stockImport->weight_kg_net / $stockImport->spool;
        //print('y');
    //}
    
    
    $stockImport->spool = $spool;
    $stockImport->weight_p_net = $weight_p_net;
    $stockImport->weight_kg_net = $weight_kg_net;
    //$stockImport->average_p = $stockImport->weight_p_net / $stockImport->spool;
    //$stockImport->average_kg = $stockImport->weight_kg_net / $stockImport->spool;
    //print($spool .' ');
    if($spool == 0){
    //print(' x ');
    $stockImport->average_p = $weight_p_net /1;
    $stockImport->average_kg = $weight_kg_net / 1;
    
    }else {
    $stockImport->average_p = $weight_p_net / $spool;
    $stockImport->average_kg = $weight_kg_net / $spool;
    }
    
    //print($stockImport->yarnType  .' '.$stockImport->supplierName  .' จำนวน ' . $stockImport->spool .' ');
    
    array_push($yarnSumList , $stockImport);
    
    //summerry all supplier
    $sumYarntype = $stockImport->yarnType;
    $sumspool += $spool ;
    $sumweight_p_net += $weight_p_net ;
    $sumweight_kg_net += $weight_kg_net;
    
    //print($stockImport->yarnType .' ' . $spool .'<br>');
    //print($spl[0]->supplierName .' sum weight_p_net = ' . $weight_p_net .'<br>');
    
    }//end if
    
    } //end loop supplier
    //print('รวม ' . $sumspool );
    //print(' x ');
    
    $sumstockImport = new Materialstock();
    $sumstockImport->yarnType = $sumYarntype;
    $sumstockImport->createDate = date("m/d/Y");
    $sumstockImport->supplierName = $sumsupplierName;
    $sumstockImport->lot = $sumlot;
    $sumstockImport->spool = $sumspool;
    $sumstockImport->weight_p_net = $sumweight_p_net;
    $sumstockImport->weight_kg_net = $sumweight_kg_net;
    $sumstockImport->average_p = $sumstockImport->weight_p_net / $sumstockImport->spool;
    $sumstockImport->average_kg = $sumstockImport->weight_kg_net / $sumstockImport->spool;
    
    //print($sumstockImport->yarnType .' ' . $sumstockImport->supplierName .' '. $sumstockImport->spool .' ลูก');
    
    //array_push($yarnSumList , $sumstockImport);
    $yarnSumList  = array_reverse($yarnSumList );
    array_push($stockList , $yarnSumList );
    } //end loop stock with yarnType
    
    
    
return $stockList;    
    
}    
}
