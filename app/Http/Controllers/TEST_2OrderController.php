<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AstPurchaseorder;
use App\Models\orderdeadline;
use App\Models\customer;
use App\Models\Supplier;
use App\Models\Material;

use App\Models\FabricAst;

use App\Models\FabricAststructure;

use PDF;
use Dompdf\Dompdf;
use Codedge\Fpdf\Fpdf\Fpdf;

// require_once 'path/to/dompdf/autoload.inc.php';

class OrderController extends Controller
{
    protected $fpdf;
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
        $orderlist = AstPurchaseorder::latest()->take(5)->get();

        //example create pdf with thai font 
        /*
        $this->fpdf = new Fpdf;
        // Add Thai font 
        $this->fpdf->AddFont('THSarabunNew','','THSarabunNew.php');
        $this->fpdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
        $this->fpdf->AddPage();
        $this->fpdf->SetFont('THSarabunNew','',16);
        $this->fpdf->Cell(40, 10, iconv('UTF-8', 'cp874', 'สวัสดี'));
        $this->fpdf->SetFont('THSarabunNew','B',16);
        $this->fpdf->Cell(40, 10, iconv('UTF-8', 'cp874', 'สวัสดี'));
        $this->fpdf->SetFont('Arial', 'B', 15);
        $this->fpdf->AddPage("L", ['100', '100']);
        $this->fpdf->Text(10, 10, "Hello World! ");       
        $this->fpdf->Output();
        exit; 
        */
        //end example
        
        return view('orders.index' ,compact('orderlist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = customer::all('id', 'name');
        $supplier = Supplier::all('id', 'name');

        //Get all material import group by yarnType
        $yarnType = Material::orderBy('yarnType')->get()->groupBy(function($data) {
            return $data->yarnType;
        });

        //Get all purchase order import group by purchaseOrder
$purchaseList = AstPurchaseorder::orderBy('purchaseOrder')->get()->groupBy(function($data) {
    return $data->purchaseOrder;
});


$so = AstPurchaseorder::where('vat' , 'LIKE' , 'SO' )
->orderBy('id', 'DESC')->first();
if(!$so){
    $so = 'SO' . (Date('Y') - 1957) . Date('m') .'/0';
}else {
    $so = $so->purchaseOrder;
}
$sox = AstPurchaseorder::where('vat' , 'LIKE' , 'SOX' )
->orderBy('id', 'DESC')->first();
if(!$sox){
    $sox = 'SO'. (Date('Y') - 1957) .  Date('m') .'/0';
}else {
    $sox = $sox->purchaseOrder;
}
$sob = AstPurchaseorder::where('vat' , 'LIKE' , 'SOB' )
->orderBy('id', 'DESC')->first();
if(!$sob){
    $sob = 'SOB'. (Date('Y') - 1957) .  Date('m') .'/0';
}else {
    $sob = $sob->purchaseOrder;
}

$tso = explode('/', $so);
$so = $tso[0] . '/'. ($tso[1] +1);
$tsox = explode('/', $sox);
$sox = $tsox[0] . '/'. ($tsox[1] +1);
$tsob = explode('/', $sob);
$sob = $tsob[0] . '/'. ($tsob[1] +1);


//print(Date('Y') - 1957 .  Date('m') );
$last = count(AstPurchaseorder::all());
if(!$last ){
    $last  = 0;
}else {
    //$last = $last->id;
}

        return view('orders.create' ,compact('customers' , 'supplier' , 'yarnType' , 'last' , 'so','sox','sob'));

        //
            }

            public static function customerData($customerName)
            {
        $customerData = customer::where('name', '=', $customerName)->get();
                return $customerData;
            }
        
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(! $request->filled('createDate')){
            $request->request->add(['createDate' => date("m/d/Y") ]); 
            }
            if(! $request->filled('fabricSPY')){
                $request->request->add(['fabricSPY' => '0' ]); 
                }
                if(! $request->filled('fabricSpP')){
                    $request->request->add(['fabricSpP' => '0' ]); 
                    }
                    if(! $request->filled('priceYard')){
                        $request->request->add(['priceYard' => '0' ]); 
                        }
                        if(! $request->filled('priceM')){
                            $request->request->add(['priceM' => '0' ]); 
                            }
                            if(! $request->filled('discountP')){
                                $request->request->add(['discountP' => '0' ]); 
                                }
                                if(! $request->filled('discountYard')){
                                    $request->request->add(['discountYard' => '0' ]); 
                                    }
                                    if(! $request->filled('commission')){
                                        $request->request->add(['commission' => '0' ]); 
                                        }
                                        if(! $request->filled('po')){
                                            $request->request->add(['po' => $request->purchaseOrder ]); 
                                            }
                                            if(! $request->filled('comment')){
                                                $request->request->add(['comment' => 'no data' ]); 
                                                }
                                                if(! $request->filled('deadline')){
                                                    $request->request->add(['deadline' => $request->coname ]); 
                                                    }
                                                    if(! $request->filled('payment')){
                                                        $request->request->add(['payment' => 'ชำระเงินภายใน 15 วัน หลังได้รับสินค้า']); 
                                                        }
                                                                
                                    
        if($request->filled('submit') && $request->submit == 'purchaseorder') {
            $validatedData = $request->validate([
                'emp' => 'required',
                'createDate' => 'required',
    'customerName' => 'required',
    'fabricId' => 'required',
    'fabricPattern'  => 'required',
    'fabricStructure' => 'required',
    'orderSumYard' => 'required',
    'orderSumM' => 'required', 
    'fabricSPY' => 'required', 
    'fabricSpP'=> 'required' ,
        'priceYard' => 'required',
    'priceM' => 'required',
    'discountP' => 'required',
    'discountYard' => 'required',
    'commission' => 'required',
    'vat' => 'required',
    'purchaseOrder' => 'required',
    'po' => 'required',
    'deadline'=> 'required',
    'comment'=> 'required',
            ]);

            $show = AstPurchaseorder::updateOrCreate($validatedData);
            
            if($show ){
                $fabricStructureData = [];
$fabricStructureData = ['vat' => $request->vat ] + $fabricStructureData;
//$fabricStructureData = ['purchaseOrder' => $request->purchaseOrder ] + $fabricStructureData;
$fabricStructureData = ['purchaseOrder' => $show->id ] + $fabricStructureData;

$fabricStructureData = ['yarnHType1' => $request->yarnHType1 ] + $fabricStructureData;
$fabricStructureData = ['subNameH1' => $request->subNameH1 ] + $fabricStructureData;
$fabricStructureData = ['yarnHCount1' => $request->yarnHCount1 ] + $fabricStructureData;
$fabricStructureData = ['yarnHRatio1' => $request->yarnHRatio1 ] + $fabricStructureData;

if(! $request->filled('yarnHType2')){
$fabricStructureData = ['yarnHType2' => 'no data'] + $fabricStructureData;
} else{
    $fabricStructureData = ['yarnHType2' => $request->yarnHType2] + $fabricStructureData;
}
if(! $request->filled('subNameH2')){
    $fabricStructureData = ['subNameH2' => 'no data'] + $fabricStructureData;
    } else{
        $fabricStructureData = ['subNameH2' => $request->subNameH2 ] + $fabricStructureData;
    }
    if(! $request->filled('yarnHCount2')){
        $fabricStructureData = ['yarnHCount2' => 'no data'] + $fabricStructureData;
        } else {
            $fabricStructureData = ['yarnHCount2' => $request->yarnHCount2 ] + $fabricStructureData;
        }
        if(! $request->filled('yarnHRatio2')){
            $fabricStructureData = ['yarnHRatio2' => 'no data'] + $fabricStructureData;
            } else {
                $fabricStructureData = ['yarnHRatio2' => $request->yarnHRatio2 ] + $fabricStructureData;
            }
            
            $fabricStructureData = ['yarnWType1' => $request->yarnWType1 ] + $fabricStructureData;
            $fabricStructureData = ['subNameW1' => $request->subNameW1] + $fabricStructureData;
            $fabricStructureData = ['yarnWCount1' => $request->yarnWCount1 ] + $fabricStructureData;
                    if(! $request->filled('yarnWRatio1')){
            $fabricStructureData = ['yarnWRatio1' => 'no data'] + $fabricStructureData;
            } else {
                $fabricStructureData = ['yarnWRatio1' => $request->yarnWRatio1 ] + $fabricStructureData;
            }

            if(! $request->filled('yarnWType2')){
                $fabricStructureData = ['yarnWType2' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWType2' => $request->yarnWType2 ] + $fabricStructureData;
                }
                if(! $request->filled('subNameW2')){
                    $fabricStructureData = ['subNameW2' => 'no data'] + $fabricStructureData;
                    } else {
                        $fabricStructureData = ['subNameW2' => $request->subNameW2 ] + $fabricStructureData;
                    }
                    if(! $request->filled('yarnWCount2')){
                        $fabricStructureData = ['yarnWCount2' => 'no data'] + $fabricStructureData;
                        } else {
                            $fabricStructureData = ['yarnWCount2' => $request->yarnWCount2] + $fabricStructureData;
                        }
                        if(! $request->filled('yarnWRatio2')){
                            $fabricStructureData = ['yarnWRatio2' => 'no data'] + $fabricStructureData;
                            } else {
                                $fabricStructureData = ['yarnWRatio2' => $request->yarnWRatio2] + $fabricStructureData;
                            }

                            if(! $request->filled('yarnWType3')){
                                $fabricStructureData = ['yarnWType3' => 'no data'] + $fabricStructureData;
                                } else {
                                    $fabricStructureData = ['yarnWType3' => $request->yarnWType3 ] + $fabricStructureData;
                                }
                                if(! $request->filled('subNameW3')){
                                    $fabricStructureData = ['subNameW3' => 'no data'] + $fabricStructureData;
                                    } else {
                                        $fabricStructureData = ['subNameW3' => $request->subNameW3 ] + $fabricStructureData;
                                    }
                                    if(! $request->filled('yarnWCount3')){
                                        $fabricStructureData = ['yarnWCount3' => 'no data'] + $fabricStructureData;
                                        } else {
                                            $fabricStructureData = ['yarnWCount3' => $request->yarnWCount3] + $fabricStructureData;
                                        }
                                        if(! $request->filled('yarnWRatio3')){
                                            $fabricStructureData = ['yarnWRatio3' => 'no data'] + $fabricStructureData;
                                            } else {
                                                $fabricStructureData = ['yarnWRatio3' => $request->yarnWRatio3] + $fabricStructureData;
                                            }
                
                                            if(! $request->filled('yarnWType4')){
                                                $fabricStructureData = ['yarnWType4' => 'no data'] + $fabricStructureData;
                                                } else {
                                                    $fabricStructureData = ['yarnWType4' => $request->yarnWType4 ] + $fabricStructureData;
                                                }
                                                if(! $request->filled('subNameW4')){
                                                    $fabricStructureData = ['subNameW4' => 'no data'] + $fabricStructureData;
                                                    } else {
                                                        $fabricStructureData = ['subNameW4' => $request->subNameW4 ] + $fabricStructureData;
                                                    }
                                                    if(! $request->filled('yarnWCount4')){
                                                        $fabricStructureData = ['yarnWCount4' => 'no data'] + $fabricStructureData;
                                                        } else {
                                                            $fabricStructureData = ['yarnWCount4' => $request->yarnWCount4 ] + $fabricStructureData;
                                                        }
                                                        if(! $request->filled('yarnWRatio4')){
                                                            $fabricStructureData = ['yarnWRatio4' => 'no data'] + $fabricStructureData;
                                                            } else {
                                                                $fabricStructureData = ['yarnWRatio4' => $request->yarnWRatio4 ] + $fabricStructureData;
                                                            }
                                
                                
                                $insert_fabric_structure = FabricAststructure::updateOrCreate($fabricStructureData);

                                $fabricData = [];
                                $fabricData = ['vat' => $request->vat ] + $fabricData;
                                //$fabricData = ['purchaseOrder' => $request->purchaseOrder ] + $fabricData;
                                $fabricData = ['purchaseOrder' => $show->id ] + $fabricData;

                                $fabricData = ['yarn_h_count' => $request->yarn_h_count ] + $fabricData;
                                $fabricData = ['fabric_w'  => $request->fabric_w ] + $fabricData;
                                $fabricData = ['phewNumber' => $request->phewNumber] + $fabricData;
                                $fabricData = ['phewW' => $request->phewW ] + $fabricData;
                                
                                if(! $request->filled('payment')){
                                    $fabricData = ['payment' => 'ชำระเงินภายใน 15 วัน หลังได้รับสินค้า'] + $fabricData;
                                    } else {
                                        $fabricData = ['payment' => $request->payment ] + $fabricData;
                                    }

                                $insert_fabric = FabricAst::updateOrCreate($fabricData );
                                
                }

                
    if($show ){
    //Copy deadline to temp data
$temp = $request->dl;

$c = 1;
foreach($temp as $key => $value) {
//Add Purchase ID  field to value
//$value = ["purchaseOrder" => $request['purchaseOrder'] ] + $value;
$value = ["purchaseOrder" => $show->id ] + $value;

//set round data 
$value = ["round" => $c] + $value;
$c += 1;

if($value['ordery'] == null) {
    //Set orderSumYard to value
    $value = ["ordery" =>  $request['orderSumYard'] ] + $value;
    }
    if($value['orderp'] == null) {
        //Set "100%" to value
        $value = ["orderp" => '100'] + $value;
    }

//Insert deadline
$dl = orderdeadline::updateOrCreate($value);

}    
$showpurchaseorder = $request;        

return view('orders.purchaseorder' ,compact('showpurchaseorder'));

//return redirect('/order')->with('success', 'order is successfully saved');

    }
    
    
        }
   
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
