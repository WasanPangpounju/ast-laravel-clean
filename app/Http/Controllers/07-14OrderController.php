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
use App\Models\production;

use PDF;
use Dompdf\Dompdf;
use Codedge\Fpdf\Fpdf\Fpdf;

use Carbon\Carbon;


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
        // $orderlist = AstPurchaseorder::latest()->take(10)->get();

        //$orderlist = AstPurchaseorder::orderByDesc('createDate')->get();
        $orderlist = AstPurchaseorder::orderBy('createDate', 'desc')->get();

        // $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $orderEdit->id)->get();
        for ($i = 0; $i < count($orderlist); $i++) {
            $st = $this->getStatus($orderlist[$i]->id);
            if ($st == 'no data') {
                $orderlist[$i]->status = 'สร้างใบสั่งซื้อ';
            } else {
                $orderlist[$i]->status = $st;
            }
        }

        // print($orderlist );

        //guide data display for search 
        $customers = customer::all('id', 'name');
        //Get all material import group by yarnType
        $yarnType = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
            return $data->yarnType;
        });


        return view('orders.index', compact('orderlist', 'customers', 'yarnType'));
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
        $yarnType = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
            return $data->yarnType;
        });

        //Get all purchase order import group by purchaseOrder
        $purchaseList = AstPurchaseorder::orderBy('purchaseOrder')->get()->groupBy(function ($data) {
            return $data->purchaseOrder;
        });


        //get month
        $monthNumber = date('m');
        $monthNumber  = str_pad($monthNumber, 2, '0', STR_PAD_LEFT);  // Output: 06 (for June)
        //echo $monthNumber;

        $so = AstPurchaseorder::where('vat', 'LIKE', 'SO')
            ->orderBy('id', 'DESC')->first();
        if (!$so) {
            $so = 'SO' . (Date('Y') - 1957) . $monthNumber   . '/1';
        } else {
            $so = $so->purchaseOrder;
        }
        $sox = AstPurchaseorder::where('vat', 'LIKE', 'SOX')
            ->orderBy('id', 'DESC')->first();
        if (!$sox) {
            $sox = 'SO' . (Date('Y') - 1957) .  $monthNumber . '/1';
        } else {
            $sox = $sox->purchaseOrder;
        }
        $sob = AstPurchaseorder::where('vat', 'LIKE', 'SOB')
            ->orderBy('id', 'DESC')->first();
        if (!$sob) {
            $sob = 'SOB' . (Date('Y') - 1957) .  $monthNumber  . '/1';
        } else {
            $sob = $sob->purchaseOrder;
        }

        $soMonth = substr($so, 4, 2);


        //check next month    
        if (($soMonth !== null) &&  ($soMonth !== $monthNumber)) {
            //new month and start count purchase order
            $so = 'SO' . (Date('Y') - 1957) . $monthNumber   . '/1';
        } else {
            //next purchase order            
            $tso = explode('/', $so);
            $so = $tso[0] . '/' . ($tso[1] + 1);
        }

        $soxMonth = substr($sox, 4, 2);

        //check next month    
        if (($soxMonth  !== null) &&  ($soxMonth !== $monthNumber)) {
            //new month and start count purchase order
            $sox = 'SO' . (Date('Y') - 1957) .  $monthNumber  . '/1';
        } else {
            //next purchase order            
            $tsox = explode('/', $sox);
            $sox = $tsox[0] . '/' . ($tsox[1] + 1);
        }

        $sobMonth = substr($sob, 4, 2);

        //check next month    
        if (($sobMonth !== null) &&  ($sobMonth !== $monthNumber)) {
            //new month and start count purchase order
            $sob = 'SOB' . (Date('Y') - 1957) .  $monthNumber  . '/1';
        } else {
            //next purchase order            
            $tsob = explode('/', $sob);
            $sob = $tsob[0] . '/' . ($tsob[1] + 1);
        }



        $last = count(AstPurchaseorder::all());
        if (!$last) {
            $last  = 0;
        } else {
            //$last = $last->id;
        }


        return view('orders.create', compact('customers', 'supplier', 'yarnType', 'last', 'so', 'sox', 'sob'));

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
        //searchImport
        if ($request->filled('submit') && $request->submit == 'searchImport') {
            $select_search = '';
            $searchInput = '';

            if ($request->filled('importId') && $request->filled('customerName') && $request->filled('yarnType') && $request->filled('imDate')) {
                //print('1 2 3 4');
            } elseif ($request->filled('importId') && $request->filled('customerName') && $request->filled('yarnType')) {
                //print('1 2 3');
            } elseif ($request->filled('customerName') && $request->filled('yarnType') && $request->filled('imDate')) {
                //print('2 3 4');
            } elseif ($request->filled('importId') && $request->filled('customerName')) {
                //print('1 2');
            } elseif ($request->filled('yarnType') && $request->filled('imDate')) {
                //print('3 4');
            } elseif ($request->filled('customerName') && $request->filled('yarnType')) {
                //print('2 3');
            } elseif ($request->filled('importId') && $request->filled('imDate')) {
                //print('1 4');
            } elseif ($request->filled('importId') && $request->filled('yarnType')) {
                //print('1 3');
            } elseif ($request->filled('customerName') && $request->filled('imDate')) {
                //print('2 4');
            } elseif ($request->filled('importId')) {
                $importorder = AstPurchaseorder::where('importStatus', 'LIKE', '%' . $request->importId . '%')->get();
                //var_dump($importorder );
                //print($request->importId);
                $select_search = 'importId';
                $searchInput = $request->importId;
            } elseif ($request->filled('customerName')) {
                $importorder = AstPurchaseorder::where('customerName', 'LIKE', '%' . $request->customerName . '%')->get();
                //print($request->customerName);
                $select_search = 'customerName';
                $searchInput = $request->customerName;
            } elseif ($request->filled('yarnType')) {
                $importorder = AstPurchaseorder::where('yarnType', 'LIKE', '%' . $request->yarnType . '%')->get();
                //print($request->yarnType );
                $select_search = 'yarnType';
                $searchInput = $request->yarnType;
            } elseif ($request->filled('imDate')) {

                // Create a DateTime object from the original date format
                $dateObj = date_create_from_format('d/m/Y', $request->imDate);

                // Convert the DateTime object to the desired format
                $fixedValue = date_format($dateObj, 'Y-m-d');
                $importorder = AstPurchaseorder::where('createDate', 'LIKE', '%' . $fixedValue . '%')->get();
                //print($request->imDate);
                $select_search = 'imDate';
                $searchInput = $request->imDate;
            } else {
                // $importorder = AstPurchaseorder::where('importStatus', 'LIKE', '%' . $request->importId . '%')->get();
                // $select_search = 'non data';
            }

            $orderlist = AstPurchaseorder::orderByDesc('createDate')->get();

            // $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $orderEdit->id)->get();
            for ($i = 0; $i < count($orderlist); $i++) {
                $st = $this->getStatus($orderlist[$i]->id);
                if ($st == 'no data') {
                    $orderlist[$i]->status = 'สร้างใบสั่งซื้อ';
                } else {
                    $orderlist[$i]->status = $st;
                }
            }
            // print_r($request->imDate);
            // print_r($searchInput);

            //guide data display for search 
            $customers = customer::all('id', 'name');
            //Get all material import group by yarnType
            $yarnType = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
                return $data->yarnType;
            });

            return view('orders.index', compact('importorder', 'select_search', 'searchInput', 'orderlist', 'customers', 'yarnType'));
        }

        //edit production
        if ($request->filled('submit') && $request->submit == 'editproduction') {
            $id = $request->id;

            //send order detail to manufacture and create order production
            $customers = customer::all('id', 'name');
            $supplier = Supplier::all('id', 'name');

            //Get all material import group by yarnType
            $yarnType = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
                return $data->yarnType;
            });

            //get production
            $productionEdit = production::where('refId', $id)->get();

            $orderEdit = AstPurchaseorder::find($id);
            if ($orderEdit) {

                //get fabric structure by order id , 1 order = 1 fabric structure
                $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $orderEdit->id)->get();

                //get payment discription , 1 order = 1 fabric payment 
                $fabricpaymentEdit = FabricAst::where('purchaseOrder', $orderEdit->id)->get();

                //get order deadline , one order to many deadline
                $deadlineEdit = orderdeadline::where('purchaseOrder', $orderEdit->id)->get();

                if ($fabricStructureEdit  && $fabricpaymentEdit && $deadlineEdit) {
                    //get month
                    $monthNumber = date('m');
                    $monthNumber  = str_pad($monthNumber, 2, '0', STR_PAD_LEFT);  // Output: 06 (for June)
                    //echo $monthNumber;

                    $so = AstPurchaseorder::where('vat', 'LIKE', 'SO')
                        ->orderBy('id', 'DESC')->first();
                    if (!$so) {
                        $so = 'SO' . (Date('Y') - 1957) . $monthNumber   . '/1';
                    } else {
                        $so = $so->purchaseOrder;
                    }
                    $sox = AstPurchaseorder::where('vat', 'LIKE', 'SOX')
                        ->orderBy('id', 'DESC')->first();
                    if (!$sox) {
                        $sox = 'SO' . (Date('Y') - 1957) .  $monthNumber . '/1';
                    } else {
                        $sox = $sox->purchaseOrder;
                    }
                    $sob = AstPurchaseorder::where('vat', 'LIKE', 'SOB')
                        ->orderBy('id', 'DESC')->first();
                    if (!$sob) {
                        $sob = 'SOB' . (Date('Y') - 1957) .  $monthNumber  . '/1';
                    } else {
                        $sob = $sob->purchaseOrder;
                    }

                    $soMonth = substr($so, 4, 2);


                    //check next month    
                    if (($soMonth !== null) &&  ($soMonth !== $monthNumber)) {
                        //new month and start count purchase order
                        $so = 'SO' . (Date('Y') - 1957) . $monthNumber   . '/1';
                    } else {
                        //next purchase order            
                        $tso = explode('/', $so);
                        $so = $tso[0] . '/' . ($tso[1] + 1);
                    }

                    $soxMonth = substr($sox, 4, 2);

                    //check next month    
                    if (($soxMonth  !== null) &&  ($soxMonth !== $monthNumber)) {
                        //new month and start count purchase order
                        $sox = 'SO' . (Date('Y') - 1957) .  $monthNumber  . '/1';
                    } else {
                        //next purchase order            
                        $tsox = explode('/', $sox);
                        $sox = $tsox[0] . '/' . ($tsox[1] + 1);
                    }

                    $sobMonth = substr($sob, 4, 2);

                    //check next month    
                    if (($sobMonth !== null) &&  ($sobMonth !== $monthNumber)) {
                        //new month and start count purchase order
                        $sob = 'SOB' . (Date('Y') - 1957) .  $monthNumber  . '/1';
                    } else {
                        //next purchase order            
                        $tsob = explode('/', $sob);
                        $sob = $tsob[0] . '/' . ($tsob[1] + 1);
                    }

                    // print_r($productionEdit);
                    return view('orders.productionEdit', compact('customers', 'supplier', 'yarnType', 'orderEdit', 'fabricStructureEdit', 'fabricpaymentEdit', 'deadlineEdit', 'so', 'sox', 'sob', 'productionEdit'));
                }
            }
        }

        //generate production order
        if ($request->filled('submit') && $request->submit == 'generateproduction') {
            //check create date and add date 
            if (!$request->filled('createDate')) {
                $request->request->add(['createDate' => date("Y-m-d")]);
            }

            $validatedData = $request->validate([
                'refId' => 'required',
                'emp' => 'required',
                // 'createDate' => 'required',
                'createDate' => 'required|date_format:d/m/Y',
                'customerName' => 'required',
                'coname' => 'nullable',
                'fabricId' => 'required',
                'fabricPattern' => 'nullable',
                'fabricStructure' => 'required',
                'yarn_h_count' => 'required',
                'fabric_w' => 'required',

                'orderSumYard' => 'required',
                'orderSumM' => 'required',
                'typrtag' => 'required',
                'fabricSPY' => 'nullable',
                'fabricSpP' => 'nullable',
                'typemachine' => 'nullable',
                'machinenumber' => 'nullable',
                'phewNumber' => 'nullable',
                'phewW' => 'nullable',

                'purchaseOrder' => 'required',
                'no' => 'required',
                'po'  => 'nullable',
                'comment' => 'nullable',
                'comment2' => 'nullable',
                'payment'  => 'nullable',

                'yarnHType1' => 'required',
                'subNameH1' => 'required',
                'yarnHCount1' => 'nullable',
                'yarnHRatio1' => 'nullable',

                'yarnHType2' => 'nullable',
                'subNameH2' => 'nullable',
                'yarnHCount2' => 'nullable',

                'yarnWType1' => 'required',
                'subNameW1' => 'required',
                'yarnWCount1' => 'required',
                'yarnWType2' => 'nullable',
                'subNameW2' => 'nullable',
                'yarnWCount2' => 'nullable',
                'yarnWType3' => 'nullable',
                'subNameW3' => 'nullable',
                'yarnWCount3'  => 'nullable',
                'yarnWType4' => 'nullable',
                'subNameW4' => 'nullable',
                'yarnWCount4' => 'nullable',

            ]);
            // print_r($validatedData);
            $fixedDate = str_replace('/', '-', $validatedData['createDate']);
            $validatedData['createDate'] = date('Y-m-d', strtotime($fixedDate));

            $show = production::updateOrCreate($validatedData);
            if ($show) {
                $findid = production::latest()->first();
                // $fabricStructureData = [];
                //         print_r($showproduction);

                // return view('orders.showproduction', compact('showproduction'));
                $showproduction = '';
                $fabricStructureData = [];

                //check isset Id
                // if ($request->filled('id')) {
                // $orderEdit = AstPurchaseorder::find($request->id);
                $orderEdit = production::where('refId', $findid->refId)->first();

                if ($orderEdit) {
                    $showproduction = $orderEdit;

                    //get payment discription , 1 order = 1 fabric payment 
                    $fabricpaymentEdit = FabricAst::where('purchaseOrder', $findid->refId)->get();
                    if ($fabricpaymentEdit) {
                        $showproduction->payment = $fabricpaymentEdit[0]->payment;
                        $showproduction->vat = $fabricpaymentEdit[0]->vat;
                    }

                    //get fabric structure by order id , 1 order = 1 fabric structure
                    $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $findid->refId)->get();
                    if ($fabricStructureEdit) {
                        $fabricStructureData = $fabricStructureEdit[0]->toArray();
                        if ($fabricStructureData['yarnWRatio3'] == 'm') {
                            $showproduction->typrtag = 'm';
                        } else {
                            $showproduction->typrtag = 'y';
                        }

                        // print($fabricStructureData['yarnWRatio3'] );
                        $s = explode(' /', $showproduction->fabricStructure);
                        // print_r($s);
                        $showproduction->s1 = $s[0];
                        $showproduction->s2 = $s[1];
                    }
                }
                $orderdeadline = orderdeadline::where('purchaseOrder', $findid->refId)->get();
                // print_r($orderEdit);
                // print_r("---------------------------------------------------------------------");
                // print_r($fabricStructureData);
                return view('orders.showproduction', compact('showproduction', 'fabricStructureData', 'orderdeadline'));
                // }
            }
        }

        //set order status
        if ($request->filled('submit') && $request->submit == 'updateStatus') {
            if ($request->filled('id') && $request->filled('status')) {
                $ans = $this->setStatus($request->id, $request->status);
                if ($ans) {
                    return $this->index();
                }
            }
        }

        //show purchaseorder detail
        if ($request->filled('submit') && $request->submit == 'purchaseorderdetail') {
            $showpurchaseorder = '';
            $fabricStructureData = [];
            //check isset Id
            if ($request->filled('id')) {
                $orderEdit = AstPurchaseorder::find($request->id);
                if ($orderEdit) {
                    $showpurchaseorder = $orderEdit;

                    //get payment discription , 1 order = 1 fabric payment 
                    $fabricpaymentEdit = FabricAst::where('purchaseOrder', $request->id)->get();
                    if ($fabricpaymentEdit) {
                        $showpurchaseorder->payment = $fabricpaymentEdit[0]->payment;
                        $showpurchaseorder->fabric_w = $fabricpaymentEdit[0]->fabric_w;
                        // $showpurchaseorder->yarnHRatio1 = $fabricpaymentEdit[0]->yarnHRatio1;
                    }

                    //get fabric structure by order id , 1 order = 1 fabric structure
                    $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $request->id)->get();
                    if ($fabricStructureEdit) {
                        $fabricStructureData = $fabricStructureEdit[0]->toArray();
                        // if ($fabricStructureData['yarnWRatio3'] == 'm') {
                        //     $showpurchaseorder->typrtag = 'm';
                        // } else {
                        //     $showpurchaseorder->typrtag = 'y';
                        // }
                        if ($fabricStructureData['yarnWRatio1'] == 'm') {
                            $showpurchaseorder->typrtag = 'm';
                        } else {
                            $showpurchaseorder->typrtag = 'y';
                        }

                        // print($fabricStructureData['yarnWRatio3'] );
                        $s = explode(' /', $showpurchaseorder->fabricStructure);
                        // print_r($s);
                        $showpurchaseorder->s1 = $s[0];
                        $showpurchaseorder->s2 = $s[1];

                        // print_r($showpurchaseorder);
                        // print_r("/////////////////////////////");
                        return view('orders.purchaseorder', compact('showpurchaseorder', 'fabricStructureData'));
                    }
                }
            }

            // return view('orders.purchaseorder', compact('showpurchaseorder', 'fabricStructureData'));
        }

        if (($request->filled('submit') && $request->submit == 'productionderdetail')  || ($request->filled('submit') && $request->submit == 'productionderdetailPdf')) {
            $showproduction = '';
            $fabricStructureData = [];

            //check isset Id
            if ($request->filled('id')) {
                // $orderEdit = AstPurchaseorder::find($request->id);
                $orderEdit = production::where('refId', $request->id)->first();

                if ($orderEdit) {
                    $showproduction = $orderEdit;

                    //get payment discription , 1 order = 1 fabric payment 
                    $fabricpaymentEdit = FabricAst::where('purchaseOrder', $request->id)->get();
                    if ($fabricpaymentEdit) {
                        $showproduction->payment = $fabricpaymentEdit[0]->payment;
                        $showproduction->vat = $fabricpaymentEdit[0]->vat;
                    }

                    //get fabric structure by order id , 1 order = 1 fabric structure
                    $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $request->id)->get();
                    if ($fabricStructureEdit) {
                        $fabricStructureData = $fabricStructureEdit[0]->toArray();
                        if ($fabricStructureData['yarnWRatio3'] == 'm') {
                            $showproduction->typrtag = 'm';
                        } else {
                            $showproduction->typrtag = 'y';
                        }

                        // print($fabricStructureData['yarnWRatio3'] );
                        $s = explode(' /', $showproduction->fabricStructure);
                        // print_r($s);
                        $showproduction->s1 = $s[0];
                        $showproduction->s2 = $s[1];

                        // print_r($showproduction);
                        // print_r("---------------------------------------------------------------------");
                        // print_r($fabricStructureData);
                        $orderdeadline = orderdeadline::where('purchaseOrder', $request->id)->get();
                        if ($request->submit == 'productionderdetailPdf') {
                            return view('orders.showproductionpdf', compact('showproduction', 'fabricStructureData', 'orderdeadline'));
                        } else {
                            return view('orders.showproduction', compact('showproduction', 'fabricStructureData', 'orderdeadline'));
                        }
                    }
                } else {

                    $id = $request->id;

                    //send order detail to manufacture and create order production
                    $customers = customer::all('id', 'name');
                    $supplier = Supplier::all('id', 'name');

                    //Get all material import group by yarnType
                    $yarnType = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
                        return $data->yarnType;
                    });


                    $orderEdit = AstPurchaseorder::find($id);
                    if ($orderEdit) {
                        //print($orderEdit->id);

                        //get fabric structure by order id , 1 order = 1 fabric structure
                        $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $orderEdit->id)->get();

                        //get payment discription , 1 order = 1 fabric payment 
                        $fabricpaymentEdit = FabricAst::where('purchaseOrder', $orderEdit->id)->get();

                        //get order deadline , one order to many deadline
                        $deadlineEdit = orderdeadline::where('purchaseOrder', $orderEdit->id)->get();

                        if ($fabricStructureEdit  && $fabricpaymentEdit && $deadlineEdit) {

                            $so = AstPurchaseorder::where('vat', 'LIKE', 'SO')
                                ->orderBy('id', 'DESC')->first();
                            if (!$so) {
                                $so = 'SO' . (Date('Y') - 1957) . Date('m') . '/0';
                            } else {
                                $so = $so->purchaseOrder;
                            }
                            $sox = AstPurchaseorder::where('vat', 'LIKE', 'SOX')
                                ->orderBy('id', 'DESC')->first();
                            if (!$sox) {
                                $sox = 'SO' . (Date('Y') - 1957) .  Date('m') . '/0';
                            } else {
                                $sox = $sox->purchaseOrder;
                            }
                            $sob = AstPurchaseorder::where('vat', 'LIKE', 'SOB')
                                ->orderBy('id', 'DESC')->first();
                            if (!$sob) {
                                $sob = 'SOB' . (Date('Y') - 1957) .  Date('m') . '/0';
                            } else {
                                $sob = $sob->purchaseOrder;
                            }

                            $tso = explode('/', $so);
                            $so = $tso[0] . '/' . ($tso[1] + 1);
                            $tsox = explode('/', $sox);
                            $sox = $tsox[0] . '/' . ($tsox[1] + 1);
                            $tsob = explode('/', $sob);
                            $sob = $tsob[0] . '/' . ($tsob[1] + 1);


                            return view('orders.production', compact('customers', 'supplier', 'yarnType', 'orderEdit', 'fabricStructureEdit', 'fabricpaymentEdit', 'deadlineEdit', 'so', 'sox', 'sob'));
                        }
                    }
                }
            }

            // return view('orders.purchaseorder', compact('showpurchaseorder', 'fabricStructureData'));
        }

        if (!$request->filled('createDate')) {
            $request->request->add(['createDate' => date("Y-m-d")]);
        }
        if (!$request->filled('fabricSPY')) {
            $request->request->add(['fabricSPY' => '0']);
        }
        if (!$request->filled('fabricSpP')) {
            $request->request->add(['fabricSpP' => '0']);
        }
        if (!$request->filled('priceYard')) {
            $request->request->add(['priceYard' => '0']);
        }
        if (!$request->filled('priceM')) {
            $request->request->add(['priceM' => '0']);
        }
        if (!$request->filled('discountP')) {
            $request->request->add(['discountP' => '0']);
        }
        if (!$request->filled('discountYard')) {
            $request->request->add(['discountYard' => '0']);
        }
        if (!$request->filled('commission')) {
            $request->request->add(['commission' => '0']);
        }
        if (!$request->filled('po')) {
            //$request->request->add(['po' => $request->purchaseOrder]);
            $request->request->add(['po' => 'no data']);
        }
        if (!$request->filled('comment')) {
            $request->request->add(['comment' => '-']);
        }
        if (!$request->filled('deadline')) {
            if (!$request->filled('coname')) {
                $request->request->add(['deadline' => $request->customerName]);
                $request->request->add(['coname' => $request->customerName]);
            } else {
                $request->request->add(['deadline' => $request->coname]);
            }
        }
        if (!$request->filled('payment')) {
            $request->request->add(['payment' => 'ชำระเงินภายใน 15 วัน หลังได้รับสินค้า']);
        }


        //submit create order or production
        if (($request->filled('submit') && $request->submit == 'purchaseorder') || ($request->filled('submit') && $request->submit == 'production')) {
            $validatedData = $request->validate([
                'emp' => 'required',
                // 'createDate' => 'required',
                'createDate' => 'required|date_format:d/m/Y',
                'customerName' => 'required',
                'fabricId' => 'required',
                'fabricPattern'  => 'required',
                'fabricStructure' => 'required',
                'orderSumYard' => 'required',
                'orderSumM' => 'required',
                'fabricSPY' => 'required',
                'fabricSpP' => 'required',
                'priceYard' => 'required',
                'priceM' => 'required',
                'discountP' => 'required',
                'discountYard' => 'required',
                'commission' => 'required',
                'vat' => 'required',
                'purchaseOrder' => 'required',
                'po' => 'required',
                'deadline' => 'required',
                'comment' => 'required',
                'comment2' => 'nullable|string',
            ]);

            $fixedDate = str_replace('/', '-', $validatedData['createDate']);
            $validatedData['createDate'] = date('Y-m-d', strtotime($fixedDate));

            // $fixedDate = date('Y-m-d', strtotime($validatedData['createDate']));
            // $validatedData['createDate'] = $fixedDate;

            $show = AstPurchaseorder::updateOrCreate($validatedData);

            if ($show) {
                $fabricStructureData = [];
                $fabricStructureData = ['vat' => $request->vat] + $fabricStructureData;
                //$fabricStructureData = ['purchaseOrder' => $request->purchaseOrder ] + $fabricStructureData;
                $fabricStructureData = ['purchaseOrder' => $show->id] + $fabricStructureData;

                $fabricStructureData = ['yarnHType1' => $request->yarnHType1] + $fabricStructureData;
                $fabricStructureData = ['subNameH1' => $request->subNameH1] + $fabricStructureData;
                $fabricStructureData = ['yarnHCount1' => $request->yarnHCount1] + $fabricStructureData;
                //$fabricStructureData = ['yarnHRatio1' => $request->yarnHRatio1] + $fabricStructureData;
                if (!$request->filled('yarnHRatio1')) {
                    $fabricStructureData = ['yarnHRatio1' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnHRatio1' => $request->yarnHRatio1] + $fabricStructureData;
                }

                if (!$request->filled('yarnHType2')) {
                    $fabricStructureData = ['yarnHType2' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnHType2' => $request->yarnHType2] + $fabricStructureData;
                }
                if (!$request->filled('subNameH2')) {
                    $fabricStructureData = ['subNameH2' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['subNameH2' => $request->subNameH2] + $fabricStructureData;
                }
                if (!$request->filled('yarnHCount2')) {
                    $fabricStructureData = ['yarnHCount2' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnHCount2' => $request->yarnHCount2] + $fabricStructureData;
                }
                if (!$request->filled('yarnHRatio2')) {
                    $fabricStructureData = ['yarnHRatio2' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnHRatio2' => $request->yarnHRatio2] + $fabricStructureData;
                }

                $fabricStructureData = ['yarnWType1' => $request->yarnWType1] + $fabricStructureData;
                $fabricStructureData = ['subNameW1' => $request->subNameW1] + $fabricStructureData;
                $fabricStructureData = ['yarnWCount1' => $request->yarnWCount1] + $fabricStructureData;
                if (!$request->filled('yarnWRatio1')) {
                    $fabricStructureData = ['yarnWRatio1' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWRatio1' => $request->yarnWRatio1] + $fabricStructureData;
                }

                if (!$request->filled('yarnWType2')) {
                    $fabricStructureData = ['yarnWType2' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWType2' => $request->yarnWType2] + $fabricStructureData;
                }
                if (!$request->filled('subNameW2')) {
                    $fabricStructureData = ['subNameW2' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['subNameW2' => $request->subNameW2] + $fabricStructureData;
                }
                if (!$request->filled('yarnWCount2')) {
                    $fabricStructureData = ['yarnWCount2' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWCount2' => $request->yarnWCount2] + $fabricStructureData;
                }
                if (!$request->filled('yarnWRatio2')) {
                    $fabricStructureData = ['yarnWRatio2' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWRatio2' => $request->yarnWRatio2] + $fabricStructureData;
                }

                if (!$request->filled('yarnWType3')) {
                    $fabricStructureData = ['yarnWType3' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWType3' => $request->yarnWType3] + $fabricStructureData;
                }
                if (!$request->filled('subNameW3')) {
                    $fabricStructureData = ['subNameW3' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['subNameW3' => $request->subNameW3] + $fabricStructureData;
                }
                if (!$request->filled('yarnWCount3')) {
                    $fabricStructureData = ['yarnWCount3' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWCount3' => $request->yarnWCount3] + $fabricStructureData;
                }
                if (!$request->filled('yarnWRatio3')) {
                    $fabricStructureData = ['yarnWRatio3' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWRatio3' => $request->yarnWRatio3] + $fabricStructureData;
                }

                if (!$request->filled('yarnWType4')) {
                    $fabricStructureData = ['yarnWType4' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWType4' => $request->yarnWType4] + $fabricStructureData;
                }
                if (!$request->filled('subNameW4')) {
                    $fabricStructureData = ['subNameW4' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['subNameW4' => $request->subNameW4] + $fabricStructureData;
                }
                if (!$request->filled('yarnWCount4')) {
                    $fabricStructureData = ['yarnWCount4' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWCount4' => $request->yarnWCount4] + $fabricStructureData;
                }
                if (!$request->filled('yarnWRatio4')) {
                    $fabricStructureData = ['yarnWRatio4' => 'no data'] + $fabricStructureData;
                } else {
                    $fabricStructureData = ['yarnWRatio4' => $request->yarnWRatio4] + $fabricStructureData;
                }

                //used yarnWRatio4 to Surcharge payment
                $fabricStructureData = ['yarnWRatio4' => $request->filled('surcharge') ? $request->surcharge : null] + $fabricStructureData;
                //used yarnWRatio3 to y or m generate purchase order 
                $fabricStructureData = ['yarnWRatio3' => $request->filled('typrtag') ? $request->typrtag : null] + $fabricStructureData;


                $insert_fabric_structure = FabricAststructure::updateOrCreate($fabricStructureData);

                $fabricData = [];

                //using vat to send_cloth 
                $fabricData = ['vat' => $request->send_cloth] + $fabricData;
                //print($request->send_cloth );
                //$fabricData = ['purchaseOrder' => $request->purchaseOrder ] + $fabricData;
                $fabricData = ['purchaseOrder' => $show->id] + $fabricData;

                $fabricData = ['yarn_h_count' => $request->yarn_h_count] + $fabricData;
                $fabricData = ['fabric_w'  => $request->fabric_w] + $fabricData;
                $fabricData = ['phewNumber' => $request->phewNumber] + $fabricData;
                $fabricData = ['phewW' => $request->phewW] + $fabricData;

                if (!$request->filled('payment')) {
                    $fabricData = ['payment' => 'ชำระเงินภายใน 15 วัน หลังได้รับสินค้า'] + $fabricData;
                } else {
                    $fabricData = ['payment' => $request->payment] + $fabricData;
                }

                $insert_fabric = FabricAst::updateOrCreate($fabricData);
            }


            if ($show) {
                //Copy deadline to temp data
                $temp = $request->dl;


                $c = 1;
                foreach ($temp as $key => $value) {
                    //Add Purchase ID  field to value
                    //$value = ["purchaseOrder" => $request['purchaseOrder'] ] + $value;
                    $value = ["purchaseOrder" => $show->id] + $value;

                    //set round data 
                    $value = ["round" => $c] + $value;
                    $c += 1;
                    if ($value['dt'] == null) {
                        continue;

                        //Set createDate to value
                        $value = ["dt" =>  $request['createDate']] + $value;
                    }

                    if ($value['ordery'] == null) {
                        //Set orderSumYard to value
                        $value = ["ordery" =>  $request['orderSumYard']] + $value;
                    }
                    if ($value['orderp'] == null) {
                        //Set "100%" to value
                        $value = ["orderp" => '100'] + $value;
                    }

                    //Insert deadline
                    $dl = orderdeadline::updateOrCreate($value);
                }


                $showpurchaseorder = $request;
                // $validatedData['createDate']
                $showpurchaseorder['createDate'] = date('Y-m-d', strtotime($fixedDate));

                if (isset($request->typemachine) || isset($request->machinenumber)) {
                    $data = $request->except('priceYard');
                    $validatedData = [];

                    $validatedData['refId'] = $show->id;
                    $validatedData['emp'] = $request->emp;
                    $validatedData['createDate'] = $request->createDate;
                    $validatedData['customerName'] = $request->customerName;
                    $validatedData['coname'] = $request->coname;
                    $validatedData['fabricId'] = $request->fabricId;
                    $validatedData['fabricPattern'] = $request->fabricPattern;
                    $validatedData['fabricStructure'] = $request->fabricStructure;
                    $validatedData['yarn_h_count'] = $request->yarn_h_count;
                    $validatedData['fabric_w'] = $request->fabric_w;

                    $validatedData['orderSumYard'] = $request->orderSumYard;
                    $validatedData['orderSumM'] = $request->orderSumM;
                    $validatedData['typrtag'] = $request->typrtag;
                    $validatedData['fabricSPY'] = $request->fabricSPY;
                    $validatedData['fabricSpP'] = $request->fabricSpP;
                    $validatedData['typemachine'] = $request->typemachine;
                    $validatedData['machinenumber'] = $request->machinenumber;
                    $validatedData['phewNumber'] = $request->phewNumber;
                    $validatedData['phewW'] = $request->phewW;


                    $validatedData['purchaseOrder'] = $request->purchaseOrder;
                    $validatedData['po'] = $request->po;
                    $validatedData['comment'] = $request->comment;
                    $validatedData['comment2'] = $request->comment2;
                    $validatedData['payment'] = $request->payment;
                    $validatedData['yarnHType1'] = $request->yarnHType1;

                    $validatedData['subNameH1'] = $request->subNameH1;
                    $validatedData['yarnHCount1'] = $request->yarnHCount1;
                    $validatedData['yarnHRatio1'] = $request->yarnHRatio1;

                    $validatedData['yarnHType2'] = $request->yarnHType2;
                    $validatedData['subNameH2'] = $request->subNameH2;
                    $validatedData['yarnHCount2'] = $request->yarnHCount2;
                    $validatedData['yarnWType1'] = $request->yarnWType1;
                    // $validatedData['refId'] = $show->id;
                    $validatedData['subNameW1'] = $request->subNameW1;
                    $validatedData['yarnWCount1'] = $request->yarnWCount1;
                    $validatedData['yarnWType2'] = $request->yarnWType2;
                    $validatedData['subNameW2'] = $request->subNameW2;
                    $validatedData['yarnWCount2'] = $request->yarnWCount2;
                    $validatedData['yarnWType3'] = $request->yarnWType3;
                    $validatedData['subNameW3'] = $request->subNameW3;
                    $validatedData['yarnWCount3'] = $request->yarnWCount3;
                    $validatedData['yarnWType4'] = $request->yarnWType4;
                    $validatedData['subNameW4'] = $request->subNameW4;
                    $validatedData['yarnWCount4'] = $request->yarnWCount4;

                    // $validatedData['emp'] = $show->emp;
                    //    print_r($validatedData);
                    $show = production::updateOrCreate($validatedData);
                }



                if ($request->submit == 'purchaseorder') {
                    return view('orders.purchaseorder', compact('showpurchaseorder', 'fabricStructureData'));
                } elseif ($request->submit == 'purchaseorder') {
                    return view('orders.purchaseorder', compact('showpurchaseorder', 'fabricStructureData'));
                } else {
                    $id = $show->id;

                    //send order detail to manufacture and create order production
                    $customers = customer::all('id', 'name');
                    $supplier = Supplier::all('id', 'name');

                    //Get all material import group by yarnType
                    $yarnType = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
                        return $data->yarnType;
                    });


                    $orderEdit = AstPurchaseorder::find($id);
                    if ($orderEdit) {
                        //print($orderEdit->id);

                        //get fabric structure by order id , 1 order = 1 fabric structure
                        $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $orderEdit->id)->get();

                        //get payment discription , 1 order = 1 fabric payment 
                        $fabricpaymentEdit = FabricAst::where('purchaseOrder', $orderEdit->id)->get();

                        //get order deadline , one order to many deadline
                        $deadlineEdit = orderdeadline::where('purchaseOrder', $orderEdit->id)->get();

                        if ($fabricStructureEdit  && $fabricpaymentEdit && $deadlineEdit) {

                            //get month
                            $monthNumber = date('m');
                            $monthNumber  = str_pad($monthNumber, 2, '0', STR_PAD_LEFT);  // Output: 06 (for June)
                            //echo $monthNumber;

                            $so = AstPurchaseorder::where('vat', 'LIKE', 'SO')
                                ->orderBy('id', 'DESC')->first();
                            if (!$so) {
                                $so = 'SO' . (Date('Y') - 1957) . $monthNumber   . '/1';
                            } else {
                                $so = $so->purchaseOrder;
                            }
                            $sox = AstPurchaseorder::where('vat', 'LIKE', 'SOX')
                                ->orderBy('id', 'DESC')->first();
                            if (!$sox) {
                                $sox = 'SO' . (Date('Y') - 1957) .  $monthNumber . '/1';
                            } else {
                                $sox = $sox->purchaseOrder;
                            }
                            $sob = AstPurchaseorder::where('vat', 'LIKE', 'SOB')
                                ->orderBy('id', 'DESC')->first();
                            if (!$sob) {
                                $sob = 'SOB' . (Date('Y') - 1957) .  $monthNumber  . '/1';
                            } else {
                                $sob = $sob->purchaseOrder;
                            }

                            $soMonth = substr($so, 4, 2);


                            //check next month    
                            if (($soMonth !== null) &&  ($soMonth !== $monthNumber)) {
                                //new month and start count purchase order
                                $so = 'SO' . (Date('Y') - 1957) . $monthNumber   . '/1';
                            } else {
                                //next purchase order            
                                $tso = explode('/', $so);
                                $so = $tso[0] . '/' . ($tso[1] + 1);
                            }

                            $soxMonth = substr($sox, 4, 2);

                            //check next month    
                            if (($soxMonth  !== null) &&  ($soxMonth !== $monthNumber)) {
                                //new month and start count purchase order
                                $sox = 'SO' . (Date('Y') - 1957) .  $monthNumber  . '/1';
                            } else {
                                //next purchase order            
                                $tsox = explode('/', $sox);
                                $sox = $tsox[0] . '/' . ($tsox[1] + 1);
                            }

                            $sobMonth = substr($sob, 4, 2);

                            //check next month    
                            if (($sobMonth !== null) &&  ($sobMonth !== $monthNumber)) {
                                //new month and start count purchase order
                                $sob = 'SOB' . (Date('Y') - 1957) .  $monthNumber  . '/1';
                            } else {
                                //next purchase order            
                                $tsob = explode('/', $sob);
                                $sob = $tsob[0] . '/' . ($tsob[1] + 1);
                            }


                            return view('orders.production', compact('customers', 'supplier', 'yarnType', 'orderEdit', 'fabricStructureEdit', 'fabricpaymentEdit', 'deadlineEdit', 'so', 'sox', 'sob'));
                        }
                    }
                }

                //return redirect('/order')->with('success', 'order is successfully saved');

            }
        } elseif ($request->filled('submit') && $request->submit == "genPDF") {
            // $orderlist = AstPurchaseorder::latest()->take(5)->get();
            // $firstOperand = $request->first_operand;
            ////ชื่อลูกค้า////
            $customerName = $request->customerName;
            $address = $request->address;
            $tax = $request->tax;
            $coname = $request->coname;
            $purchaseOrder = $request->purchaseOrder;
            $createDate = $request->createDate;
            $payment = $request->payment;
            $s1 = $request->s1;
            $s2 = $request->s2;
            $fabricPattern = $request->fabricPattern;
            $fabric_w = $request->fabric_w;
            $fabricId = $request->fabricId;
            $orderSumYard = $request->orderSumYard;
            $orderSumM = $request->orderSumM;
            $priceYard = $request->priceYard;
            $priceM = $request->priceM;
            $comment = $request->comment;
            // $firstOperand = $request->price;
            $vat = $request->vat;
            $po = $request->po;
            $discountP = $request->discountP;
            $emp = $request->emp;
            $yarnHRatio1 = $request->yarnHRatio1;
            $typrtag = $request->typrtag;
            $surcharge = $request->surcharge;
            $po = $request->po;

            $now = Carbon::now(new \DateTimeZone('Asia/Bangkok'));
            $day = $now->day;
            $month = $now->month;
            $year = $now->year;

            //example create pdf with thai font
            $this->fpdf = new Fpdf;
            // Add Thai font 
            $this->fpdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
            $this->fpdf->AddFont('THSarabunNew', 'B', 'THSarabunNew_b.php');
            $this->fpdf->AddPage();

            /* Page header */
            function Header()
            {
                /* Logo */
                $this->fpdf->Image('assets/images/logo-blue.png', 10, 6, 30);
            }

            $rest = substr($purchaseOrder, 0, 3);
            $this->fpdf->SetFont('Arial', 'B', 14);

            //Cell(width , height , text , border , end line , [align] )
            if ($rest == "SOB") {
                $this->fpdf->SetFont('THSarabunNew', 'B', 48);
                $this->fpdf->Cell(15, 5, '', 0, 0);
                $this->fpdf->Cell(110, 5, iconv('UTF-8', 'cp874', 'AST'), 0, 0);
                $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            } else {
                $this->fpdf->Image('assets/images/logo-blue.png', 11, 11, -100);
            }


            $this->fpdf->Cell(100, 5, '', 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line

            // $this->fpdf->Cell(59, 5, 'INVOICE', 0, 1); //end of line


            // $this->fpdf->SetFont('THSarabunNew', 'B', 30);
            // $this->fpdf->Cell(35, 5, '', 0, 0);
            // $this->fpdf->Cell(110, 5, iconv('UTF-8', 'cp874', 'บริษัท เอเชียเท็กซ์ไทล์ จำกัด'), 0, 0);
            // $this->fpdf->Cell(10, 5, $rest.'321654987', 0, 1); //end of line
            if ($rest == "SOB") {
                // $this->fpdf->SetFont('THSarabunNew', 'B', 30);
                // $this->fpdf->Cell(35, 5, '', 0, 0);
                // $this->fpdf->Cell(110, 5, iconv('UTF-8', 'cp874', 'บริษัท เอเชียเท็กซ์ไทล์ จำกัด'), 0, 0);
                // $this->fpdf->Cell(10, 5, $rest.'321654987', 0, 1); //end of line
                $this->fpdf->Cell(10, 10, '', 0, 1); //end of line
            } else {
                $this->fpdf->SetFont('THSarabunNew', 'B', 30);
                $this->fpdf->Cell(35, 5, '', 0, 0);
                $this->fpdf->Cell(110, 5, iconv('UTF-8', 'cp874', 'บริษัท เอเซียเท็กซ์ไทล์ จำกัด'), 0, 0);
                $this->fpdf->Cell(10, 5, $purchaseOrder, 0, 1); //end of line
                $this->fpdf->Cell(10, 10, '', 0, 1); //end of line

                //set font to arial, regular, 12pt
                // $this->fpdf->SetFont('Arial', '', 12);
                $this->fpdf->SetFont('THSarabunNew', 'B', 20);
                $this->fpdf->Cell(35, 5, '', 0, 0);
                $this->fpdf->Cell(130, 5, iconv('UTF-8', 'cp874', 'ASIA TEXTILE CO., LTD.'), 0, 0);
                $this->fpdf->Cell(10, 5, '', 0, 1); //end of line

                $this->fpdf->SetFont('THSarabunNew', 'B', 13);
                $this->fpdf->Cell(35, 5, '', 0, 0);
                $this->fpdf->Cell(130, 10, iconv('UTF-8', 'cp874', '798 หมู่ที่ 2 ซอยบางเมฆขาว ถนนสุขุมวิท ตำบลท้ายบ้าน อำเภอเมืองสมุทปราการ จังหวัดสมุทรปราการ 10280'), 0, 0);

                $this->fpdf->Cell(100, 5, '', 0, 0);
                $this->fpdf->Cell(20, 5, '', 5, 1); //end of line

                $this->fpdf->Cell(35, 5, '', 0, 0);
                $this->fpdf->Cell(130, 10, iconv('UTF-8', 'cp874', 'เบอร์โทรศัพท์ : 0899988899 Email : asiatestile@gmail.com'), 0, 1);

                $this->fpdf->Cell(35, 5, '', 0, 0);
                $this->fpdf->Cell(130, 1, iconv('UTF-8', 'cp874', 'เลขประจำตัวผู้เสียภาษี : 1234567890'), 0, 1);
            }

            $countLenthai = grapheme_strlen($customerName);
            //make a dummy empty cell as a vertical spacer
            $this->fpdf->Cell(189, 10, '', 0, 1); //end of line
            $this->fpdf->SetFont('THSarabunNew', 'B', 24);
            $this->fpdf->Cell(85, 5, '', 0, 0);
            $this->fpdf->Cell(130, 5, iconv('UTF-8', 'cp874', 'ใบสั่งขาย'), 0, 0);
            $this->fpdf->Cell(10, 10, '', 0, 1); //end of line
            $this->fpdf->Cell(189, 0, iconv('UTF-8', 'cp874', ''), 1, 0);

            // $this->fpdf->Cell(100, 5, iconv('UTF-8', 'cp874', 'ชื่อลูกค้า'), 0, 0,'C',true);
            // $this->fpdf->Cell(10, 5, iconv('UTF-8', 'cp874', 'เลขที่ใบสั่งซื้อ'), 0, 1,'C',true);
            $this->fpdf->SetFont('THSarabunNew', 'B', 16);
            $this->fpdf->Cell(189, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(120, 5, iconv('UTF-8', 'cp874', 'ชื่อลูกค้า'), 0, 0);
            $this->fpdf->Cell(10, 5, iconv('UTF-8', 'cp874', 'เลขที่ใบสั่งขาย'), 0, 1);

            if ($countLenthai > 40) {
                $this->fpdf->Cell(10, 7, '', 0, 1); //end of line
            } else {
                $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
            }
            // $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(120, 5, iconv('UTF-8', 'cp874', 'เลขประจำตัวผู้เสียภาษี'), 0, 0);
            $this->fpdf->Cell(10, 5, iconv('UTF-8', 'cp874', 'วันที่'), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            // $this->fpdf->Cell(100, 5, iconv('UTF-8', 'cp874', 'ผู้ประสานงาน'), 0, 0);
            $this->fpdf->Cell(120, 5, iconv('UTF-8', 'cp874', 'ที่อยู่'), 0, 0);
            $this->fpdf->Cell(10, 5, iconv('UTF-8', 'cp874', 'เงื่อนไขการขำระเงิน'), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(100, 5, iconv('UTF-8', 'cp874', ''), 0, 0);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line

            ///////////////////

            $this->fpdf->SetFont('THSarabunNew', '', 16);
            $this->fpdf->Cell(40, 5, '', 0, 0);
            if ($countLenthai > 40) {
                $this->fpdf->Cell(120, -51, iconv('UTF-8', 'cp874', ''), 0, 0);
                $this->fpdf->Cell(10, -51, iconv('UTF-8', 'cp874', ''), 0, 1);
            } else {
                $this->fpdf->Cell(120, -51, iconv('UTF-8', 'cp874', $customerName), 0, 0);
                $this->fpdf->Cell(10, -51, iconv('UTF-8', 'cp874', $purchaseOrder), 0, 1);
            }
            // $this->fpdf->Cell(120, -61, iconv('UTF-8', 'cp874', $customerName), 0, 0);
            // $this->fpdf->setXY(30, 69);
            // $this->fpdf->MultiCell(70, 5, iconv('UTF-8', 'cp874', $customerName), 0, 0);
            // $this->fpdf->MultiCell(120, -51, iconv('UTF-8', 'cp874', $customerName), 0, 0);
            // $this->fpdf->Cell(10, -51, iconv('UTF-8', 'cp874', $purchaseOrder), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            // $this->fpdf->Cell(40, 5, '', 0, 0);
            // if ($countLenthai > 40) {
            //     $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
            // } else {
            //     $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            // }

            $this->fpdf->Cell(40, 5, '', 0, 0);
            $this->fpdf->Cell(120, 61, iconv('UTF-8', 'cp874', $tax), 0, 0);
            // $this->fpdf->Cell(40, 10, iconv('UTF-8', 'cp874','ว.ด.ป.'.$day.'/'.$month.'/'.$year), 0, 0);
            $date = date('d/m/Y', strtotime($createDate));
            $this->fpdf->Cell(10, 61, iconv('UTF-8', 'cp874',  $date), 0, 1);
            // $this->fpdf->Cell(10, 61, iconv('UTF-8', 'cp874', $purchaseOrder), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(40, 5, '', 0, 0);
            // $this->fpdf->Cell(100, -51, iconv('UTF-8', 'cp874', $coname), 0, 0);
            // $this->fpdf->Cell(120, -51, iconv('UTF-8', 'cp874', $address), 0, 0);
            $this->fpdf->Cell(120, -51, iconv('UTF-8', 'cp874', ''), 0, 0);
            // $date = date('d/m/Y', strtotime($createDate));
            // $this->fpdf->Cell(10, -51, iconv('UTF-8', 'cp874',  $payment), 0, 1);
            $this->fpdf->Cell(10, -51, iconv('UTF-8', 'cp874',  ''), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(20, 5, '', 0, 0);
            $this->fpdf->Cell(100, 61, iconv('UTF-8', 'cp874', ''), 0, 0);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line


            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line

            //invoice contents
            // $this->fpdf->SetFont('Arial', 'B', 12);

            $this->fpdf->Cell(189, 0, iconv('UTF-8', 'cp874', ''), 1, 0);

            $this->fpdf->Cell(10, 0.5, '', 0, 1); //end of line

            $this->fpdf->Cell(189, 0, '', 0, 1); //end of line
            $this->fpdf->SetFont('THSarabunNew', 'B', 16);
            $this->fpdf->SetFillColor(193, 229, 252);

            $this->fpdf->Cell(1, 10, iconv('UTF-8', 'cp874', ''), 0, 0, 'C', true);
            $this->fpdf->Cell(15, 10, iconv('UTF-8', 'cp874', 'ลำดับ'), 0, 0, 'C', true);
            $this->fpdf->Cell(85, 10, iconv('UTF-8', 'cp874', 'รายการสินค้า'), 0, 0, 'C', true);
            if ($typrtag == 'm') {
                $this->fpdf->Cell(30, 10, iconv('UTF-8', 'cp874', 'จำนวนเมตร'), 0, 0, 'C', true);
            } else {
                $this->fpdf->Cell(30, 10, iconv('UTF-8', 'cp874', 'จำนวนหลา'), 0, 0, 'C', true);
            }

            $this->fpdf->Cell(20, 10, iconv('UTF-8', 'cp874', 'หน่วยละ'), 0, 0, 'C', true);
            $this->fpdf->Cell(38, 10, iconv('UTF-8', 'cp874', 'จำนวนเงิน'), 0, 1, 'C', true); //end of line
            // $this->fpdf->Cell(0, 10, iconv('UTF-8', 'cp874', ''), 0, 1,'C',true); //end of line
            $this->fpdf->Cell(189, 0, iconv('UTF-8', 'cp874', ''), 1, 0, 'C', true);

            $this->fpdf->Cell(189, 0, '', 0, 1); //end of line
            $this->fpdf->Cell(189, 0, iconv('UTF-8', 'cp874', ''), 1, 0);

            $this->fpdf->SetFont('THSarabunNew', '', 14);
            $this->fpdf->SetFillColor(255, 255, 255);
            $this->fpdf->Cell(189, 0.5, '', 0, 1); //end of line
            $this->fpdf->Cell(1, 10, iconv('UTF-8', 'cp874', ''), 0, 0, 'C', true);
            $this->fpdf->Cell(15, 10, iconv('UTF-8', 'cp874', '1'), 0, 0, 'C', true);

            // $stest = strstr($s1, '*', true);
            // $stest2 = ltrim(stristr($s1, '*'), '*');

            // $this->fpdf->Cell(20, 5, '', 0, 0);
            // $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
            // // $price_b5 = number_format($price - $price * ($discountP / 100), 2, '.', ',');
            // $rightMargin = 200;
            // $textWidth = $this->fpdf->GetStringWidth($stest);
            // $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
            // $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
            // $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $stest), 0, 0, 'R');
            // $this->fpdf->Cell(10, 2, '', 0, 0); //end of line


            $this->fpdf->Cell(85, 10, iconv('UTF-8', 'cp874',  ' * '), 0, 0, 'C', true);
            // $this->fpdf->Cell(35, 10, iconv('UTF-8', 'cp874', '3/1 เฉียงซ้าย 63 "'), 0, 0, 'C', true);
            if ($typrtag == 'm') {
                $orderSumM_b = number_format($orderSumM, 2, '.', ',');
                $this->fpdf->Cell(30, 10, iconv('UTF-8', 'cp874', $orderSumM_b), 0, 0, 'C', true);
            } else {
                // $orderSumYard_b = number_format($orderSumYard, 2, '.', ',');
                $this->fpdf->Cell(30, 10, iconv('UTF-8', 'cp874', $orderSumYard), 0, 0, 'C', true);
            }
            // $orderSumYard_b = number_format($orderSumYard, 2, '.', ',');
            // $this->fpdf->Cell(30, 10, iconv('UTF-8', 'cp874', $orderSumYard_b), 0, 0, 'C', true);
            if ($typrtag == 'm') {
                $priceM_b = number_format($priceM, 3, '.', ',');
                $this->fpdf->Cell(20, 10, iconv('UTF-8', 'cp874', $priceM_b), 0, 0, 'C', true);
            } else {
                $priceYard_b = number_format($priceYard, 3, '.', ',');
                $this->fpdf->Cell(20, 10, iconv('UTF-8', 'cp874', $priceYard_b), 0, 0, 'C', true);
            }
            // $priceYard_b = number_format($priceYard, 3, '.', ',');
            // $this->fpdf->Cell(20, 10, iconv('UTF-8', 'cp874', $priceYard_b), 0, 0, 'C', true);
            $price = $orderSumYard * $priceYard;
            $price_b = number_format($price, 2, '.', ',');
            $this->fpdf->Cell(38, 10, iconv('UTF-8', 'cp874', $price_b), 0, 1, 'C', true); //end of line
            // $this->fpdf->Cell(0, 10, iconv('UTF-8', 'cp874', ''), 0, 1,'C',true); //end of line

            $this->fpdf->Cell(189, 0, '', 0, 1); //end of line
            $this->fpdf->Cell(17, 0, '', 0, 0); //end of line
            $this->fpdf->Cell(80, 0, iconv('UTF-8', 'cp874', ''), 1, 0);

            $this->fpdf->SetFont('THSarabunNew', '', 16);
            $this->fpdf->SetFillColor(255, 255, 255);
            $this->fpdf->Cell(189, 0.5, '', 0, 1); //end of line
            $this->fpdf->Cell(43.5, 10, iconv('UTF-8', 'cp874', ''), 0, 0, 'C', true);
            $s2test = strstr($s2, '*', true);
            $s2test2 = ltrim(stristr($s2, '*'), '*');
            $this->fpdf->Cell(30, 10, iconv('UTF-8', 'cp874',  ' * '), 0, 0, 'C', true);

            $this->fpdf->SetFont('THSarabunNew', '', 16);
            $this->fpdf->SetFillColor(255, 255, 255);
            $this->fpdf->Cell(189, 10, '', 0, 1); //end of line
            $this->fpdf->Cell(45, 10, iconv('UTF-8', 'cp874', ''), 0, 0, 'C', true);
            $fabricPatternCut = preg_replace("/\([^)]+\)/", "", $fabricPattern);
            if ($yarnHRatio1 == 'no data') {
                $this->fpdf->Cell(30, 10, iconv('UTF-8', 'cp874', $fabricPatternCut . ' ' . $fabric_w . " ''"), 0, 0, 'C', true);
            } else {
                $this->fpdf->Cell(30, 10, iconv('UTF-8', 'cp874', $yarnHRatio1 . ' ' . $fabricPatternCut . ' ' . $fabric_w . " ''"), 0, 0, 'C', true);
            }

            $this->fpdf->Cell(189, 10, '', 0, 1); //end of line
            // $this->fpdf->Cell(40, 10, iconv('UTF-8', 'cp874', ''), 0, 0, 'C', true)
            $this->fpdf->Cell(20, 10, '', 0, 0, 'C', true);
            $this->fpdf->Cell(40, 10, iconv('UTF-8', 'cp874', 'รหัสผ้า  '), 0, 0, 'C', true);
            $this->fpdf->Cell(10, 10, iconv('UTF-8', 'cp874', $fabricId), 0, 0, 'C', true);
            // ----------------------------------------------------------------------------------------------------/////
            $this->fpdf->Cell(189, 10, '', 0, 1); //end of line

            // $this->fpdf->Cell(40, 10, iconv('UTF-8', 'cp874', ''), 0, 0, 'C', true)

            if (isset($surcharge)) {
                $this->fpdf->SetFont('THSarabunNew', '', 14);
                $this->fpdf->Cell(189, 0, '', 0, 1); //end of line
                $this->fpdf->Cell(189, 0, iconv('UTF-8', 'cp874', ''), 1, 0);

                $this->fpdf->Cell(189, 0.5, '', 0, 1); //end of line
                $this->fpdf->Cell(1, 10, iconv('UTF-8', 'cp874', ''), 0, 0, 'C', true);
                $this->fpdf->Cell(15, 10, iconv('UTF-8', 'cp874', '2'), 0, 0, 'C', true);
                $this->fpdf->Cell(85, 10, iconv('UTF-8', 'cp874', 'SURCHARGE'), 0, 0, 'C', true);
                $surcharge_b = number_format($surcharge, 2, '.', ',');
                $this->fpdf->Cell(140, 10, iconv('UTF-8', 'cp874', $surcharge_b), 0, 0, 'C', true);
            }


            $this->fpdf->Cell(189, 0, '', 0, 1); //end of line
            // $this->fpdf->Cell(189, 0, iconv('UTF-8', 'cp874', ''), 1, 0);

            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(10, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(189, 0, iconv('UTF-8', 'cp874', ''), 1, 0);

            $this->fpdf->SetFont('THSarabunNew', 'B', 16);
            $this->fpdf->Cell(189, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(100, 0, iconv('UTF-8', 'cp874', 'หมายเหตุ'), 0, 0);
            $this->fpdf->Cell(80, 0, iconv('UTF-8', 'cp874', 'รวมเป็นเงิน'), 0, 0);
            $this->fpdf->Cell(10, 0, iconv('UTF-8', 'cp874', 'บาท'), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(189, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(100, 0, iconv('UTF-8', 'cp874', ''), 0, 0);
            $this->fpdf->Cell(80, 0, iconv('UTF-8', 'cp874', 'หักส่วนลด ' . $discountP . ' %'), 0, 0);
            $this->fpdf->Cell(10, 0, iconv('UTF-8', 'cp874', 'บาท'), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(189, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(100, 0, iconv('UTF-8', 'cp874', ''), 0, 0);
            $this->fpdf->Cell(80, 0, iconv('UTF-8', 'cp874', 'จำนวนเงินหลังหักส่วนลด'), 0, 0);
            $this->fpdf->Cell(10, 0, iconv('UTF-8', 'cp874', 'บาท'), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(189, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(100, 0, iconv('UTF-8', 'cp874', ''), 0, 0);
            $this->fpdf->Cell(80, 0, iconv('UTF-8', 'cp874', 'จำนวนภาษีมูลค่าเพิ่ม'), 0, 0);
            $this->fpdf->Cell(10, 0, iconv('UTF-8', 'cp874', 'บาท'), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(189, 5, '', 0, 1); //end of line
            $this->fpdf->Cell(100, 0, iconv('UTF-8', 'cp874', ''), 0, 0);
            $this->fpdf->Cell(80, 0, iconv('UTF-8', 'cp874', 'จำนวนเงินรวมทั้งสิ้น'), 0, 0);
            $this->fpdf->Cell(10, 0, iconv('UTF-8', 'cp874', 'บาท'), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->SetFont('THSarabunNew', '', 16);
            $this->fpdf->Cell(20, 5, '', 0, 0);
            // if ($comment != 'no data'){
            //     $comment;
            // }
            // print($surcharge);
            if (isset($surcharge)) {
                if ($vat == 'SOX') {
                    // $vat; 
                    $this->fpdf->Cell(3, -61, iconv('UTF-8', 'cp874', $comment . ' ราคานี้รวม VAT แล้ว'), 0, 0);
                    if ($po != 'no data') {
                        $this->fpdf->Cell(130, -51, iconv('UTF-8', 'cp874', $po), 0, 0);
                    }

                    // $orderSumYard_b = number_format($orderSumYard, 2, '.', ',');
                    $rightMargin = 20;
                    // $text = "Hello, World!";
                    // $textWidth = $this->fpdf->GetStringWidth($text);
                    // $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    // $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    // $this->fpdf->Cell($textWidth, -60, $text, 0, 1, 'R');
                    $priceSum = $price + $surcharge;
                    $priceSum_b2 = number_format($priceSum, 2, '.', ',');
                    $textWidth = $this->fpdf->GetStringWidth($priceSum_b2);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, -61, iconv('UTF-8', 'cp874', $priceSum_b2), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
                } else {
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', $comment . ' ' . $po), 0, 0);
                    $priceSum = $price + $surcharge;
                    $priceSum_b2 = number_format($priceSum, 2, '.', ',');
                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($priceSum_b2);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, -61, iconv('UTF-8', 'cp874', $priceSum_b2), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
                }
                $this->fpdf->Cell(20, 5, '', 0, 0);
                $this->fpdf->Cell(130, 6, iconv('UTF-8', 'cp874', ''), 0, 0);
                $price_b2 = number_format($priceSum * ($discountP / 100), 2, '.', ',');
                $rightMargin = 20;
                $textWidth = $this->fpdf->GetStringWidth($price_b2);
                $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                $this->fpdf->Cell($textWidth, 71, iconv('UTF-8', 'cp874', $price_b2), 0, 1, 'R');
                $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

                $this->fpdf->Cell(20, 5, '', 0, 0);
                $this->fpdf->Cell(130, -62, iconv('UTF-8', 'cp874', ''), 0, 0);
                $priceSumF = $priceSum - $priceSum * ($discountP / 100);
                $price_b3 = number_format($priceSumF, 2, '.', ',');
                $rightMargin = 20;
                $textWidth = $this->fpdf->GetStringWidth($price_b3);
                $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $price_b3), 0, 1, 'R');
                $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

                // $test = "0.00";
                // $rightMargin = 100;
                // $textWidth = $this->fpdf->GetStringWidth($test);
                // $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                // $this->fpdf->SetXY($xPos, $this->fpdf->GetY());

                // $pdf->Cell($textWidth, 10, $text, 1, 0, 'C');
                if ($vat == 'SO') {
                    if ($po != 'no data') {
                        $this->fpdf->Cell(130, -51, iconv('UTF-8', 'cp874', $po), 0, 0);
                    }
                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    // $price_bb = ($price - $price * ($discountP / 100)) * 0.07;
                    $price_b4 = number_format(($priceSum - $priceSum * ($discountP / 100)) * 0.07, 2, '.', ',');
                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($price_b4);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, 70, iconv('UTF-8', 'cp874', $price_b4), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    // $price = $price + ($price - $price * ($discountP / 100)) * 0.07;
                    // print_r($price);
                    $price_b5 = number_format($priceSumF + ($priceSum - $priceSum * ($discountP / 100)) * 0.07, 2, '.', ',');
                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($price_b4);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $price_b5), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
                } elseif ($vat == 'SOX') {
                    if ($po != 'no data') {
                        $this->fpdf->Cell(23, 2, '', 0,); //end of line
                        $this->fpdf->Cell(130, 37, iconv('UTF-8', 'cp874', $po), 0, 0);
                    }
                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    $rightMargin = 20;
                    $zero = '0.00';
                    $textWidth = $this->fpdf->GetStringWidth($zero);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, 71, iconv('UTF-8', 'cp874', $zero), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    $price_b5 = number_format($priceSum - $priceSum * ($discountP / 100), 2, '.', ',');
                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($price_b5);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $price_b5), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
                } else {
                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    $rightMargin = 20;
                    $zero = '0.00';
                    $textWidth = $this->fpdf->GetStringWidth($zero);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, 70, iconv('UTF-8', 'cp874', $zero), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    $price_b5 = number_format($priceSum - $priceSum * ($discountP / 100), 2, '.', ',');
                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($price_b5);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $price_b5), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
                }
            } else {
                if ($vat == 'SOX') {
                    // $vat; 
                    $this->fpdf->Cell(3, -61, iconv('UTF-8', 'cp874', $comment . ' ราคานี้รวม VAT แล้ว'), 0, 0);
                    if ($po != 'no data') {
                        $this->fpdf->Cell(0, -46, iconv('UTF-8', 'cp874', $po), 0, 0);
                    }
                    // $orderSumYard_b = number_format($orderSumYard, 2, '.', ',');
                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($price_b);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, -61, iconv('UTF-8', 'cp874', $price_b), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
                } else {
                    if ($comment != 'no data' && $po != 'no data') {
                        // $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', $po), 0, 0);
                        $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', $comment . ' ' . $po), 0, 0);
                    } elseif ($po == 'no data') {
                        // $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', $comment . ' ' . $po), 0, 0);
                        $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    }

                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($price_b);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, -61, iconv('UTF-8', 'cp874', $price_b), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
                }
                $this->fpdf->Cell(20, 5, '', 0, 0);
                $this->fpdf->Cell(130, 6, iconv('UTF-8', 'cp874', ''), 0, 0);
                $price_b2 = number_format($price * ($discountP / 100), 2, '.', ',');
                $rightMargin = 20;
                $textWidth = $this->fpdf->GetStringWidth($price_b2);
                $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                $this->fpdf->Cell($textWidth, 70, iconv('UTF-8', 'cp874', $price_b2), 0, 1, 'R');
                $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

                $this->fpdf->Cell(20, 5, '', 0, 0);
                $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                $priceF = $price - $price * ($discountP / 100);
                $price_b3 = number_format($priceF, 2, '.', ',');
                $rightMargin = 20;
                $textWidth = $this->fpdf->GetStringWidth($price_b3);
                $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                $this->fpdf->Cell($textWidth, -59, iconv('UTF-8', 'cp874', $price_b3), 0, 1, 'R');
                $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

                // $test = "0.00";
                // $rightMargin = 100;
                // $textWidth = $this->fpdf->GetStringWidth($test);
                // $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                // $this->fpdf->SetXY($xPos, $this->fpdf->GetY());

                // $pdf->Cell($textWidth, 10, $text, 1, 0, 'C');
                if ($vat == 'SO') {
                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    // $price_bb = ($price - $price * ($discountP / 100)) * 0.07;
                    $price_b4 = number_format(($price - $price * ($discountP / 100)) * 0.07, 2, '.', ',');
                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($price_b4);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, 70, iconv('UTF-8', 'cp874', $price_b4), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    // $price = $price + ($price - $price * ($discountP / 100)) * 0.07;
                    // print_r($price);
                    $price_b5 = number_format($priceF + ($price - $price * ($discountP / 100)) * 0.07, 2, '.', ',');
                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($price_b5);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $price_b5), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
                } elseif ($vat == 'SOX') {
                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    $rightMargin = 20;
                    $zero = '0.00';
                    $textWidth = $this->fpdf->GetStringWidth($zero);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, 71, iconv('UTF-8', 'cp874',  $zero), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    $price_b5 = number_format($price - $price * ($discountP / 100), 2, '.', ',');
                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($price_b5);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $price_b5), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
                } else {
                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    $rightMargin = 20;
                    $zero = '0.00';
                    $textWidth = $this->fpdf->GetStringWidth($zero);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, 70, iconv('UTF-8', 'cp874',  $zero), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

                    $this->fpdf->Cell(20, 5, '', 0, 0);
                    $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
                    $price_b5 = number_format($price - $price * ($discountP / 100), 2, '.', ',');
                    $rightMargin = 20;
                    $textWidth = $this->fpdf->GetStringWidth($price_b5);
                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                    $this->fpdf->SetXY($xPos, $this->fpdf->GetY());
                    $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $price_b5), 0, 1, 'R');
                    $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
                }
            }
            // if ($vat == 'SO') {
            //     $this->fpdf->Cell(20, 5, '', 0, 0);
            //     $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
            //     // $price_bb = ($price - $price * ($discountP / 100)) * 0.07;
            //     $price_b4 = number_format(($price - $price * ($discountP / 100)) * 0.07, 2, '.', ',');
            //     $this->fpdf->Cell($textWidth, 70, iconv('UTF-8', 'cp874', $price_b4), 0, 1);
            //     $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            //     $this->fpdf->Cell(20, 5, '', 0, 0);
            //     $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
            //     $price_b5 = number_format($price + ($price - $price * ($discountP / 100)) * 0.07, 2, '.', ',');
            //     $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $price_b5), 0, 1);
            //     $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
            // } elseif ($vat == 'SOX') {
            //     $this->fpdf->Cell(20, 5, '', 0, 0);
            //     $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
            //     $this->fpdf->Cell($textWidth, 71, iconv('UTF-8', 'cp874', '0.00'), 0, 1);
            //     $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            //     $this->fpdf->Cell(20, 5, '', 0, 0);
            //     $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
            //     $price_b5 = number_format($price - $price * ($discountP / 100), 2, '.', ',');
            //     $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $price_b5), 0, 1);
            //     $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
            // } else {
            //     $this->fpdf->Cell(20, 5, '', 0, 0);
            //     $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
            //     $this->fpdf->Cell($textWidth, 70, iconv('UTF-8', 'cp874', '0.00'), 0, 1);
            //     $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            //     $this->fpdf->Cell(20, 5, '', 0, 0);
            //     $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
            //     $price_b5 = number_format($price - $price * ($discountP / 100), 2, '.', ',');
            //     $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $price - $price * ($discountP / 100)), 0, 1);
            //     $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
            // }

            // $this->fpdf->Cell(20, 5, '', 0, 0);
            // $this->fpdf->Cell(140, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
            // $this->fpdf->Cell(10, -62, iconv('UTF-8', 'cp874', 'xxxxxxxxxx5'), 0, 1);
            // $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(10, 40, '', 0, 1); //end of line
            $this->fpdf->Cell(189, 0, iconv('UTF-8', 'cp874', ''), 1, 0);

            $this->fpdf->SetFont('THSarabunNew', '', 16);
            $this->fpdf->Cell(189, 10, '', 0, 1); //end of line
            $this->fpdf->Cell(35, 10, '', 0, 0); //end of line
            // $this->fpdf->Cell(100, 0, iconv('UTF-8', 'cp874', $coname), 0, 0);
            // $this->fpdf->Cell(10, 0, iconv('UTF-8', 'cp874', $emp), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line

            $this->fpdf->Cell(189, 6, '', 0, 1); //end of line
            $this->fpdf->Cell(189, 6, '', 0, 1); //end of line
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line
            $this->fpdf->Cell(25, 1, '', 0, 0); //end of line
            $this->fpdf->Cell(40, 0, iconv('UTF-8', 'cp874', ''), 1, 0);
            $this->fpdf->Cell(60, 1, '', 0, 0); //end of line
            $this->fpdf->Cell(40, 0, iconv('UTF-8', 'cp874', ''), 1, 0);

            $this->fpdf->Cell(5, 0, '', 0, 1); //end of line

            $this->fpdf->SetFont('THSarabunNew', 'B', 16);
            $this->fpdf->Cell(189, 6, '', 0, 1); //end of line
            $this->fpdf->Cell(38, 6, '', 0, 0); //end of line
            $this->fpdf->Cell(100, 0, iconv('UTF-8', 'cp874', 'ผู้สั่งสินค้า'), 0, 0);
            $this->fpdf->Cell(10, 0, iconv('UTF-8', 'cp874', 'ผู้ขายสินค้า'), 0, 1);
            $this->fpdf->Cell(10, 2, '', 0, 1); //end of line



            // $text = "This is a very long text string that needs to be split across multiple lines in the PDF document.";
            // $length = strlen($text);
            // $middle1 = ceil($length / 3);
            // $middle2 = $middle1 * 2;
            // $text1 = substr($text, 0, $middle1);
            // $text2 = substr($text, $middle1, $middle2 - $middle1);
            // $text3 = substr($text, $middle2);
            // $this->fpdf->Cell(0, 10, $text1);
            // $this->fpdf->Ln(); // move cursor to new line
            // $this->fpdf->Cell(0, 10, $text2);
            // $this->fpdf->Ln(); // move cursor to new line
            // $this->fpdf->Cell(0, 10, $text3);

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // set the position for the text on top
            $this->fpdf->SetFont('THSarabunNew', '', 16);
            $this->fpdf->setXY(10, 10);
            $col_width = 30;
            $row_height = 6;
            // write the text on top
            $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', ''), 0, 1);

            // set the position for the first column
            $this->fpdf->setXY(10, 20);

            // set the width and height of each column


            // write the text for the first column
            $this->fpdf->Cell($col_width, $row_height, iconv('UTF-8', 'cp874', ''), 0, 1);

            // set the position for the second column
            // $this->fpdf->setXY(50, 90);
            // if ($rest == "SOB") {
            //     $this->fpdf->setXY(50, 69);
            // } else {
            //     $this->fpdf->setXY(50, 90);
            // }

            if ($countLenthai > 40) {
                if ($rest == "SOB") {
                    $this->fpdf->setXY(50, 74);
                } else {
                    $this->fpdf->setXY(50, 95);
                }
            } else {
                if ($rest == "SOB") {
                    $this->fpdf->setXY(50, 69);
                } else {
                    $this->fpdf->setXY(50, 90);
                }
            }

            // write the text for the second column
            $address = str_replace(' ', '', $address);
            $this->fpdf->MultiCell(70, $row_height, iconv('UTF-8', 'cp874', $address), 0, 1);

            // set the position for the third column

            // โครงสร้างผ้า
            $stest = strstr($s1, '*', true);
            $stest2 = ltrim(stristr($s1, '*'), '*');

            // $this->fpdf->Cell(20, 5, '', 0, 0);
            // $this->fpdf->Cell(130, -61, iconv('UTF-8', 'cp874', ''), 0, 0);
            // // $price_b5 = number_format($price - $price * ($discountP / 100), 2, '.', ',');

            // $this->fpdf->Cell($textWidth, -60, iconv('UTF-8', 'cp874', $stest), 0, 0, 'R');
            // $this->fpdf->Cell(10, 2, '', 0, 0); //end of line

            // $this->fpdf->setXY(50, 150);
            $rightMargin = 145;
            $textWidth = $this->fpdf->GetStringWidth($stest);
            $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
            // $this->fpdf->SetXY($xPos, 124);

            if ($countLenthai > 40) {
                if ($rest == "SOB") {
                    $this->fpdf->SetXY($xPos, 108);
                } else {
                    $this->fpdf->SetXY($xPos, 129);
                }
            } else {
                if ($rest == "SOB") {
                    $this->fpdf->SetXY($xPos, 103);
                } else {
                    $this->fpdf->SetXY($xPos, 124);
                }
            }

            // write the text for the second column
            // $address = str_replace(' ', '', $address);
            $this->fpdf->MultiCell(70, $row_height, iconv('UTF-8', 'cp874', $stest), 0, 1);

            // $this->fpdf->setXY(70, 124);
            if ($countLenthai > 40) {
                if ($rest == "SOB") {
                    $this->fpdf->SetXY(70, 108);
                } else {
                    $this->fpdf->SetXY(70, 129);
                }
            } else {
                if ($rest == "SOB") {
                    $this->fpdf->SetXY(70, 103);
                } else {
                    $this->fpdf->SetXY(70, 124);
                }
            }

            // write the text for the second column
            // $address = str_replace(' ', '', $address);
            $this->fpdf->MultiCell(70, $row_height, iconv('UTF-8', 'cp874', $stest2), 0, 1);

            // เศษส่วนโครงสร้าง
            $s2test = strstr($s2, '*', true);
            $s2test2 = ltrim(stristr($s2, '*'), '*');

            $rightMargin = 145;
            $textWidth = $this->fpdf->GetStringWidth($s2test);
            $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;

            if ($countLenthai > 40) {
                if ($rest == "SOB") {
                    $this->fpdf->SetXY($xPos, 119);
                } else {
                    $this->fpdf->SetXY($xPos, 139);
                }
            } else {
                if ($rest == "SOB") {
                    $this->fpdf->SetXY($xPos, 114);
                } else {
                    $this->fpdf->SetXY($xPos, 134);
                }
            }


            // write the text for the second column
            // $address = str_replace(' ', '', $address);
            $this->fpdf->MultiCell(70, $row_height, iconv('UTF-8', 'cp874', $s2test), 0, 1);

            if ($countLenthai > 40) {
                if ($rest == "SOB") {
                    $this->fpdf->SetXY(70, 119);
                } else {
                    $this->fpdf->SetXY(70, 139);
                }
            } else {
                if ($rest == "SOB") {
                    $this->fpdf->SetXY(70, 114);
                } else {
                    $this->fpdf->SetXY(70, 134);
                }
            }


            // write the text for the second column
            // $address = str_replace(' ', '', $address);
            $this->fpdf->MultiCell(70, $row_height, iconv('UTF-8', 'cp874', $s2test2), 0, 1);



            // increase the row height to 12
            // $row_height = 6;
            // $this->fpdf->setXY(90, $this->fpdf->getY() + $row_height + 2); // add some padding between the text and the next line
            // write the text for the third column using MultiCell()

            // $this->fpdf->setXY(170, 90);
            if ($countLenthai > 40) {
                if ($rest == "SOB") {
                    $this->fpdf->setXY(170, 74);
                } else {
                    $this->fpdf->setXY(170, 95);
                }
            } else {
                if ($rest == "SOB") {
                    $this->fpdf->setXY(170, 69);
                } else {
                    $this->fpdf->setXY(170, 90);
                }
            }

            $payment = str_replace(' ', '', $payment);
            $this->fpdf->MultiCell($col_width, $row_height, iconv('UTF-8', 'cp874', $payment), 0, 1);
            // $this->fpdf->MultiCell($col_width, $row_height, iconv('UTF-8', 'cp874', 'add some padding between the text and the next lineadd some padding between the text and the next lineadd some padding between the text and the next line'), 1);

            // set the Y position to move down to the next line after the third column

            if ($countLenthai > 40) {
                $this->fpdf->setXY(50, 75);
                // strlen(string $customerName): int
                // $customerName = str_replace(' ', '', $customerName);
                $this->fpdf->MultiCell(70, $row_height, iconv('UTF-8', 'cp874', $customerName), 0, 1);
                $this->fpdf->setXY(170, 75);
                // strlen(string $customerName): int
                // $customerName = str_replace(' ', '', $customerName);
                $this->fpdf->MultiCell(70, $row_height, iconv('UTF-8', 'cp874', $purchaseOrder), 0, 1);
            } else {
                // $this->fpdf->setXY(40, 130);
                // strlen(string $customerName): int
                // $customerName = str_replace(' ', '', $customerName);
                // $this->fpdf->MultiCell(70, $row_height, iconv('UTF-8', 'cp874', $customerName), 0, 1);
            }

            // $this->fpdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
            // $this->fpdf->AddFont('THSarabunNew', 'B', 'THSarabunNew_b.php');
            // $this->fpdf->AddPage();

            $this->fpdf->Output();
            exit;

            return view('package.index', compact('orderlist'));
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
        $orderdata = AstPurchaseorder::find($id);

        if ($orderdata) {
            //print($orderdata);
            $structuredata = FabricAststructure::where('purchaseOrder', $id)->get();

            $orderdeadline = orderdeadline::where('purchaseOrder', $id)->get();

            $fabricdata = FabricAst::where('purchaseOrder', $id)->get();

            return view('orders.detail', compact('orderdata', 'structuredata', 'orderdeadline', 'fabricdata'));
        }
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
        $customers = customer::all('id', 'name');
        $supplier = Supplier::all('id', 'name');

        //Get all material import group by yarnType
        $yarnType = Material::orderBy('yarnType')->get()->groupBy(function ($data) {
            return $data->yarnType;
        });


        $orderEdit = AstPurchaseorder::find($id);
        if ($orderEdit) {
            //print($orderEdit->id);

            //get fabric structure by order id , 1 order = 1 fabric structure
            $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $orderEdit->id)->get();

            //get payment discription , 1 order = 1 fabric payment 
            $fabricpaymentEdit = FabricAst::where('purchaseOrder', $orderEdit->id)->get();

            //get order deadline , one order to many deadline
            $deadlineEdit = orderdeadline::where('purchaseOrder', $orderEdit->id)->get();

            if ($fabricStructureEdit  && $fabricpaymentEdit && $deadlineEdit) {

                $so = AstPurchaseorder::where('vat', 'LIKE', 'SO')
                    ->orderBy('id', 'DESC')->first();
                if (!$so) {
                    $so = 'SO' . (Date('Y') - 1957) . Date('m') . '/0';
                } else {
                    $so = $so->purchaseOrder;
                }
                $sox = AstPurchaseorder::where('vat', 'LIKE', 'SOX')
                    ->orderBy('id', 'DESC')->first();
                if (!$sox) {
                    $sox = 'SO' . (Date('Y') - 1957) .  Date('m') . '/0';
                } else {
                    $sox = $sox->purchaseOrder;
                }
                $sob = AstPurchaseorder::where('vat', 'LIKE', 'SOB')
                    ->orderBy('id', 'DESC')->first();
                if (!$sob) {
                    $sob = 'SOB' . (Date('Y') - 1957) .  Date('m') . '/0';
                } else {
                    $sob = $sob->purchaseOrder;
                }

                $tso = explode('/', $so);
                $so = $tso[0] . '/' . ($tso[1] + 1);
                $tsox = explode('/', $sox);
                $sox = $tsox[0] . '/' . ($tsox[1] + 1);
                $tsob = explode('/', $sob);
                $sob = $tsob[0] . '/' . ($tsob[1] + 1);


                return view('orders.edit', compact('customers', 'supplier', 'yarnType', 'orderEdit', 'fabricStructureEdit', 'fabricpaymentEdit', 'deadlineEdit', 'so', 'sox', 'sob'));
            }
        }

        //return view('orders.create', compact('customers', 'supplier', 'yarnType', 'last', 'so', 'sox', 'sob'));

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
        if ($request->filled('submit') && $request->submit == 'updateproduction') {
            $request->validate([
                'refId' => 'required',
                'emp' => 'required',
                // 'createDate' => 'required',
                'createDate' => 'required|date_format:d/m/Y',
                'customerName' => 'required',
                'coname' => 'nullable',
                'fabricId' => 'required',
                'fabricPattern' => 'nullable',
                'fabricStructure' => 'required',
                'yarn_h_count' => 'required',
                'fabric_w' => 'required',

                'orderSumYard' => 'required',
                'orderSumM' => 'required',
                'typrtag' => 'required',
                'fabricSPY' => 'nullable',
                'fabricSpP' => 'nullable',
                'typemachine' => 'nullable',
                'machinenumber' => 'nullable',
                'phewNumber' => 'nullable',
                'phewW' => 'nullable',

                'purchaseOrder' => 'required',
                'no' => 'required',
                'po'  => 'nullable',
                'comment' => 'nullable',
                'comment2' => 'nullable',
                'payment'  => 'nullable',

                'yarnHType1' => 'required',
                'subNameH1' => 'required',
                'yarnHCount1' => 'nullable',
                'yarnHRatio1' => 'nullable',

                'yarnHType2' => 'nullable',
                'subNameH2' => 'nullable',
                'yarnHCount2' => 'nullable',

                'yarnWType1' => 'required',
                'subNameW1' => 'required',
                'yarnWCount1' => 'required',
                'yarnWType2' => 'nullable',
                'subNameW2' => 'nullable',
                'yarnWCount2' => 'nullable',
                'yarnWType3' => 'nullable',
                'subNameW3' => 'nullable',
                'yarnWCount3'  => 'nullable',
                'yarnWType4' => 'nullable',
                'subNameW4' => 'nullable',
                'yarnWCount4' => 'nullable',

                'fabriccomment' => 'required',

            ]);



            $dataUpdate = production::find($id);
            // Getting values from the blade template form
            $dataUpdate->refId = $request->get('refId');
            $dataUpdate->emp = $request->get('emp');

            $fixedDate = str_replace('/', '-', $request->get('createDate'));
            // $validatedData['createDate'] = date('Y-m-d', strtotime($fixedDate));

            // $dataUpdate->createDate = $request->get('createDate');
            $dataUpdate->createDate = date('Y-m-d', strtotime($fixedDate));

            $dataUpdate->customerName = $request->get('customerName');
            $dataUpdate->coname = $request->get('coname');
            $dataUpdate->fabricId = $request->get('fabricId');
            $dataUpdate->fabricPattern = $request->get('fabricPattern');
            $dataUpdate->fabricStructure = $request->get('fabricStructure');
            $dataUpdate->yarn_h_count = $request->get('yarn_h_count');
            $dataUpdate->fabric_w = $request->get('fabric_w');

            $dataUpdate->orderSumYard = $request->get('orderSumYard');
            $dataUpdate->orderSumM = $request->get('orderSumM');
            $dataUpdate->typrtag = $request->get('typrtag');
            $dataUpdate->fabricSPY = $request->get('fabricSPY');
            $dataUpdate->fabricSpP = $request->get('fabricSpP');
            $dataUpdate->typemachine = $request->get('typemachine');
            $dataUpdate->machinenumber = $request->get('machinenumber');
            $dataUpdate->phewNumber = $request->get('phewNumber');
            $dataUpdate->phewW = $request->get('phewW');

            $dataUpdate->purchaseOrder = $request->get('purchaseOrder');
            $dataUpdate->po = $request->get('po');
            $dataUpdate->no = $request->get('no');
            $dataUpdate->comment = $request->get('comment');
            $dataUpdate->comment2 = $request->get('comment2');
            $dataUpdate->payment = $request->get('payment');

            $dataUpdate->yarnHType1 = $request->get('yarnHType1');
            $dataUpdate->subNameH1 = $request->get('subNameH1');
            $dataUpdate->yarnHCount1 = $request->get('yarnHCount1');
            $dataUpdate->yarnHRatio1 = $request->get('yarnHRatio1');

            $dataUpdate->yarnHType2 = $request->get('yarnHType2');
            $dataUpdate->subNameH2 = $request->get('subNameH2');
            $dataUpdate->yarnHCount2 = $request->get('yarnHCount2');

            $dataUpdate->yarnWType1 = $request->get('yarnWType1');
            $dataUpdate->subNameW1 = $request->get('subNameW1');
            $dataUpdate->yarnWCount1 = $request->get('yarnWCount1');
            $dataUpdate->yarnWType2 = $request->get('yarnWType2');
            $dataUpdate->subNameW2 = $request->get('subNameW2');
            $dataUpdate->yarnWCount2 = $request->get('yarnWCount2');
            $dataUpdate->yarnWType3 = $request->get('yarnWType3');
            $dataUpdate->subNameW3 = $request->get('subNameW3');
            $dataUpdate->yarnWCount3 = $request->get('yarnWCount3');
            $dataUpdate->yarnWType4 = $request->get('yarnWType4');
            $dataUpdate->subNameW4 = $request->get('subNameW4');
            $dataUpdate->yarnWCount4 = $request->get('yarnWCount4');
            $dataUpdate->save();

            // $dataUpdate2 = FabricAst::find($id);
            $dataUpdate2 = FabricAst::where('purchaseOrder', $request->refId)->first();
            $dataUpdate2->vat = $request->get('fabriccomment');
            $dataUpdate2->save();


            // return redirect('/materialstore')->with('success', 'materialstore updated.'); // -> resources/views/stocks/index.blade.php
            // $lastTenRecords = Material::latest()->take(10)->get();
            $showproduction = '';
            $fabricStructureData = [];

            //check isset Id
            // if ($request->filled('id')) {
            // $orderEdit = AstPurchaseorder::find($request->id);
            $orderEdit = production::where('refId', $dataUpdate->refId)->first();

            if ($orderEdit) {
                $showproduction = $orderEdit;

                //get payment discription , 1 order = 1 fabric payment 
                $fabricpaymentEdit = FabricAst::where('purchaseOrder', $dataUpdate->refId)->get();
                if ($fabricpaymentEdit) {
                    $showproduction->payment = $fabricpaymentEdit[0]->payment;
                    $showproduction->vat = $fabricpaymentEdit[0]->vat;
                }

                //get fabric structure by order id , 1 order = 1 fabric structure
                $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $dataUpdate->refId)->get();
                if ($fabricStructureEdit) {
                    $fabricStructureData = $fabricStructureEdit[0]->toArray();
                    if ($fabricStructureData['yarnWRatio3'] == 'm') {
                        $showproduction->typrtag = 'm';
                    } else {
                        $showproduction->typrtag = 'y';
                    }

                    // print($fabricStructureData['yarnWRatio3'] );
                    $s = explode(' /', $showproduction->fabricStructure);
                    // print_r($s);
                    $showproduction->s1 = $s[0];
                    $showproduction->s2 = $s[1];
                }
            }
            $orderdeadline = orderdeadline::where('purchaseOrder', $dataUpdate->refId)->get();
            // print_r($orderEdit);
            // print_r("---------------------------------------------------------------------");
            // print_r($fabricStructureData);
            return view('orders.showproduction', compact('showproduction', 'fabricStructureData', 'orderdeadline'));


            // return $this->index();
        }
        $orderEdit = AstPurchaseorder::find($id);
        if ($orderEdit) {
            //print($orderEdit->id);
            if (!$request->filled('createDate')) {
                $request->request->add(['createDate' => date("Y-m-d")]);
            }
            if (!$request->filled('fabricSPY')) {
                $request->request->add(['fabricSPY' => '0']);
            }
            if (!$request->filled('fabricSpP')) {
                $request->request->add(['fabricSpP' => '0']);
            }
            if (!$request->filled('priceYard')) {
                $request->request->add(['priceYard' => '0']);
            }
            if (!$request->filled('priceM')) {
                $request->request->add(['priceM' => '0']);
            }
            if (!$request->filled('discountP')) {
                $request->request->add(['discountP' => '0']);
            }
            if (!$request->filled('discountYard')) {
                $request->request->add(['discountYard' => '0']);
            }
            if (!$request->filled('commission')) {
                $request->request->add(['commission' => '0']);
            }
            if (!$request->filled('po')) {
                //$request->request->add(['po' => $request->purchaseOrder]);
                $request->request->add(['po' => 'no data']);
            }
            if (!$request->filled('comment')) {
                $request->request->add(['comment' => '-']);
            }
            if (!$request->filled('deadline')) {
                if (!$request->filled('coname')) {
                    $request->request->add(['deadline' => $request->customerName]);
                    $request->request->add(['coname' => $request->customerName]);
                } else {
                    $request->request->add(['deadline' => $request->coname]);
                }
            }
            if (!$request->filled('payment')) {
                $request->request->add(['payment' => 'ชำระเงินภายใน 15 วัน หลังได้รับสินค้า']);
            }

            $orderEdit->emp = $request->input('emp');


            $fixedDate = str_replace('/', '-', $request->input('createDate'));
            // $validatedData['createDate'] = date('Y-m-d', strtotime($fixedDate));
            // $createDate = $request->input('createDate');
            $fixedDate = date('Y-m-d', strtotime($fixedDate));

            // $orderEdit->createDate = $request->input('createDate');
            $orderEdit->createDate = $fixedDate;

            $orderEdit->customerName = $request->input('customerName');
            $orderEdit->fabricId = $request->input('fabricId');
            $orderEdit->fabricPattern = $request->input('fabricPattern');
            $orderEdit->fabricStructure = $request->input('fabricStructure');
            $orderEdit->orderSumYard = $request->input('orderSumYard');
            $orderEdit->orderSumM = $request->input('orderSumM');
            $orderEdit->fabricSPY = $request->input('fabricSPY');
            $orderEdit->fabricSpP = $request->input('fabricSpP');
            $orderEdit->priceYard = $request->input('priceYard');
            $orderEdit->priceM = $request->input('priceM');
            $orderEdit->discountP = $request->input('discountP');
            $orderEdit->discountYard = $request->input('discountYard');
            $orderEdit->commission = $request->input('commission');
            $orderEdit->vat = $request->input('vat');
            $orderEdit->purchaseOrder = $request->input('purchaseOrder');
            $orderEdit->po = $request->input('po');
            $orderEdit->deadline = $request->input('deadline');
            $orderEdit->comment = $request->input('comment');

            $orderEdit->save();


            //get fabric structure by order id , 1 order = 1 fabric structure
            $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $orderEdit->id)->get();
            if ($fabricStructureEdit) {
                $fabricStructureEdit[0]->vat = $request->input('vat');
                $fabricStructureEdit[0]->purchaseOrder = $orderEdit->id;

                $fabricStructureEdit[0]->yarnHType1 = $request->input('yarnHType1');
                $fabricStructureEdit[0]->subNameH1 = $request->input('subNameH1');
                $fabricStructureEdit[0]->yarnHCount1 = $request->input('yarnHCount1');
                $fabricStructureEdit[0]->yarnHRatio1 = $request->filled('yarnHRatio1') ? $request->input('yarnHRatio1') : 'no data';

                $fabricStructureEdit[0]->yarnHType2 = $request->filled('yarnHType2') ? $request->input('yarnHType2') : 'no data';
                $fabricStructureEdit[0]->subNameH2 = $request->filled('subNameH2') ? $request->input('subNameH2') : 'no data';
                $fabricStructureEdit[0]->yarnHCount2 = $request->filled('yarnHCount2') ? $request->input('yarnHCount2') : 'no data';
                $fabricStructureEdit[0]->yarnHRatio2 = $request->filled('yarnHRatio2') ? $request->input('yarnHRatio2') : 'no data';

                $fabricStructureEdit[0]->yarnWType1 =  $request->input('yarnWType1');
                $fabricStructureEdit[0]->subNameW1 = $request->input('subNameW1');
                $fabricStructureEdit[0]->yarnWCount1 = $request->input('yarnWCount1');
                $fabricStructureEdit[0]->yarnWRatio1 = $request->filled('yarnWRatio1') ? $request->input('yarnWRatio1') : 'no data';

                $fabricStructureEdit[0]->yarnWType2 = $request->filled('yarnWType2')  ?  $request->input('yarnWType2') : 'no data';
                $fabricStructureEdit[0]->subNameW2 = $request->filled('subNameW2') ? $request->input('subNameW2') : 'no data';
                $fabricStructureEdit[0]->yarnWCount2 = $request->filled('yarnWCount2') ? $request->input('yarnWCount2') : 'no data';
                $fabricStructureEdit[0]->yarnWRatio2 = $request->filled('yarnWRatio2') ? $request->input('yarnWRatio2') : 'no data';

                $fabricStructureEdit[0]->yarnWType3 = $request->filled('yarnWType3') ?  $request->input('yarnWType3') : 'no data';
                $fabricStructureEdit[0]->subNameW3 = $request->filled('subNameW3') ? $request->input('subNameW3') : 'no data';
                $fabricStructureEdit[0]->yarnWCount3 = $request->filled('yarnWCount3') ? $request->input('yarnWCount3') : 'no data';
                //  $fabricStructureEdit[0]->yarnWRatio3 = $request->input('yarnWRatio3');

                $fabricStructureEdit[0]->yarnWType4 = $request->filled('yarnWType4') ? $request->input('yarnWType4') : 'no data';
                $fabricStructureEdit[0]->subNameW4 = $request->filled('subNameW4') ? $request->input('subNameW4') : 'no data';
                $fabricStructureEdit[0]->yarnWCount4 = $request->filled('yarnWCount4') ? $request->input('yarnWCount4') : 'no data';
                //  $fabricStructureEdit[0]->yarnWRatio4 = $request->input('yarnWRatio4');


                //used yarnWRatio4 to Surcharge payment
                $fabricStructureEdit[0]->yarnWRatio4 = $request->filled('surcharge') ? $request->input('surcharge') : null;
                //used yarnWRatio3 to y or m generate purchase order 
                $fabricStructureEdit[0]->yarnWRatio3 = $request->filled('typrtag') ? $request->input('typrtag') : null;

                $fabricStructureEdit[0]->save();
            }
            //get payment discription , 1 order = 1 fabric payment 
            $fabricpaymentEdit = FabricAst::where('purchaseOrder', $orderEdit->id)->get();

            if ($fabricpaymentEdit) {

                //using vat to send_cloth 
                $fabricpaymentEdit[0]->vat = $request->input('send_cloth');
                $fabricpaymentEdit[0]->purchaseOrder = $orderEdit->id;

                $fabricpaymentEdit[0]->yarn_h_count = $request->input('yarn_h_count');
                $fabricpaymentEdit[0]->fabric_w = $request->input('fabric_w');
                $fabricpaymentEdit[0]->phewNumber = $request->input('phewNumber');
                $fabricpaymentEdit[0]->phewW = $request->input('phewW');

                if (!$request->filled('payment')) {
                    $fabricpaymentEdit[0]->payment = 'ชำระเงินภายใน 15 วัน หลังได้รับสินค้า';
                } else {
                    $fabricpaymentEdit[0]->payment = $request->input('payment');
                }

                $fabricpaymentEdit[0]->save();
            }
            //get order deadline , one order to many deadline
            $deadlineEdit = orderdeadline::where('purchaseOrder', $orderEdit->id)->get();

            if ($deadlineEdit) {
                for ($i = 0; $i < count($deadlineEdit); $i++) {
                    $deadlineEdit[$i]->delete();
                }
            }

            //Copy deadline to temp data
            $temp = $request->dl;
            $c = 1;
            foreach ($temp as $key => $value) {
                $value = ["purchaseOrder" => $orderEdit->id] + $value;
                //set round data 
                $value = ["round" => $c] + $value;
                $c += 1;

                if ($value['dt'] == null) {
                    //Set createDate to value
                    $value = ["dt" =>  $request['createDate']] + $value;
                }

                if ($value['ordery'] == null) {
                    //Set orderSumYard to value
                    $value = ["ordery" =>  $request['orderSumYard']] + $value;
                }
                if ($value['orderp'] == null) {
                    //Set "100%" to value
                    $value = ["orderp" => '100'] + $value;
                }

                // Insert deadline
                $dl = orderdeadline::updateOrCreate($value);
            }
        }


        $orderlist = AstPurchaseorder::latest()->take(10)->get();

        // return view('orders.index', compact('orderlist'));
        return redirect('/order/' . $id);
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
        $data = AstPurchaseorder::find($id);
        if ($data) {
            if ($data->delete()) {
                $structuredata = FabricAststructure::where('purchaseOrder', $id)->get();
                if (!$structuredata->isEmpty()) {
                    foreach ($structuredata as $tem) {
                        $tem->delete();
                    }
                }

                $orderdeadline = orderdeadline::where('purchaseOrder', $id)->get();
                if (!$orderdeadline->isEmpty()) {
                    foreach ($orderdeadline as $tem) {
                        $tem->delete();
                    }
                }

                $fabricdata = FabricAst::where('purchaseOrder', $id)->get();
                if (!$fabricdata->isEmpty()) {
                    foreach ($fabricdata as $tem) {
                        $tem->delete();
                    }
                }
            }
        }
        //$data->delete();
        $orderlist = AstPurchaseorder::latest()->take(5)->get();

        return view('orders.index', compact('orderlist'));
    }

    private    function getStatus($id)
    {
        //get fabric structure by order id , 1 order = 1 fabric structure
        $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $id)->get();
        if ($fabricStructureEdit) {
            return $fabricStructureEdit[0]->yarnWRatio2;
        } else {
            return 0;
        }
    }

    private    function setStatus($id, $status)
    {
        //get fabric structure by order id , 1 order = 1 fabric structure
        $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $id)->get();
        if ($fabricStructureEdit) {
            $fabricStructureEdit[0]->yarnWRatio2 = $status;
            $fabricStructureEdit[0]->save();

            return $fabricStructureEdit[0]->yarnWRatio2;
        } else {
            return 0;
        }
    }

    private function printDot()
    {
        // Set the page size
        $pageWidth = 9; // inches
        $pageHeight = 5.5; // inches


        $this->fpdf = new Fpdf('P', 'in', array($pageWidth, $pageHeight));
        // Add Thai font 
        $this->fpdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
        $this->fpdf->AddFont('THSarabunNew', 'B', 'THSarabunNew_b.php');
        $this->fpdf->AddPage();


        // Get the HTML content of the webpage
        $html = file_get_contents('https://www.google.com');

        // Convert the HTML to PDF and output it to the browser
        // $this->fpdf->WriteHTML($html);

        // Print the PDF to the default printer
        // $this->fpdf->Output();

    }
}
