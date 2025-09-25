<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\AstPurchaseorder;
use App\Models\FabricAst;
use App\Models\FabricAststructure;
use App\Models\Inventory;
use App\Models\orderdeadline;
use App\Models\stockfabric;
use App\Models\fabricout;
use App\Models\customer;


use PhpParser\Node\Expr\Print_;

use PDF;
use Dompdf\Dompdf;
use Codedge\Fpdf\Fpdf\Fpdf;

use Carbon\Carbon;

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
        // $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();
        $ecp = AstPurchaseorder::select('id')->where('status', 'อนุมัติให้ผลิต')->get();

        $orders = AstPurchaseorder::select('id', 'purchaseOrder' , 'customerName', 'createDate', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder', 'fabricPattern')
            ->whereIn('id', $ecp)
            // ->orderBy('created_at', 'desc')
            ->orderBy('createDate', 'desc')
            ->get();

        $inventorydata = Inventory::select('refId', Inventory::raw('SUM(fold) as foldSum'), Inventory::raw('SUM(sumYard) as sumYardSum'))
            ->groupBy('refId')
            ->get();
        // print_r($inventorydata);
        // $fabricoutdata = fabricout::select('orderId', fabricout::raw('SUM(fold) as foldSum'), fabricout::raw('SUM(sumYard) as sumYardSum'))
        //     ->groupBy('orderId')
        //     ->get();
        // print_r($fabricoutdata);
        $fabricoutdata = fabricout::select('orderId', fabricout::raw('COUNT(fold) as foldCount'), fabricout::raw('SUM(sumYard) as sumYardSum'))
            ->whereNotNull('orderId')
            ->groupBy('orderId')
            ->get();

        $fabricoutdata2 = FabricAst::select('purchaseOrder', 'fabric_w')
            ->whereIn('purchaseOrder', $ecp)
            // ->groupBy('purchaseOrder','fabric_w')
            ->get();

        // Combine the data using purchaseOrder as the key
        $combinedData = $orders->map(function ($order) use ($fabricoutdata2) {
            $fabricData = $fabricoutdata2->where('purchaseOrder', $order->purchaseOrder)->first();
            $order->fabric_w = $fabricData ? $fabricData->fabric_w : null;
            return $order;
        });

        // print_r($orders->fabricId);

        return view('inventory.index', compact('orders', 'inventorydata', 'fabricoutdata', 'fabricoutdata2'));
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
        // $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();
        $ecp = FabricAststructure::select('purchaseOrder AS id')->get();

        // $orders = AstPurchaseorder::select('ast_purchaseorders.id', 'ast_purchaseorders.customerName', 'ast_purchaseorders.fabricId',
        //  'ast_purchaseorders.fabricStructure', 'ast_purchaseorders.fabricPattern',
        //   'ast_purchaseorders.orderSumYard', 'ast_purchaseorders.purchaseOrder')
        //     ->whereIn('id', $ecp)
        //     ->orderBy('customerName')
        //     ->get();

        // $ecp = FabricAststructure::select('purchaseOrder AS id')->where('purchaseOrder', 'อนุมัติให้ผลิต')->get();

        // $orderIds = $orders->pluck('id')->toArray();

        // $records = FabricAst::groupBy(['purchaseOrder'])
        //     ->select('purchaseOrder,fabric_w')
        //     ->whereIn('purchaseOrder', $orderIds)
        //     ->get();

        // Add the fabric_w data to each item in the $orders collection
        // $orders = $orders->map(function ($order) use ($records) {
        //     $order->fabric_w = $records->where('purchaseOrder', $order->id)->pluck('fabric_w')->toArray();
        //     return $order;
        // });

        $orders = AstPurchaseorder::select(
            'ast_purchaseorders.id',
            'ast_purchaseorders.customerName',
            'ast_purchaseorders.createDate',
            'ast_purchaseorders.fabricId',
            'ast_purchaseorders.fabricStructure',
            'ast_purchaseorders.orderSumYard',
            'ast_purchaseorders.purchaseOrder',
            'ast_purchaseorders.fabricPattern',
            'fabric_asts.fabric_w'
        )
            ->join('fabric_asts', 'fabric_asts.purchaseOrder', '=', 'ast_purchaseorders.id')
            ->whereIn('ast_purchaseorders.id', $ecp)
            ->orderBy('ast_purchaseorders.customerName')
            ->get();


        // print_r($records);

        // print_r('/////////');

        // print_r($orders);
        $customers = Customer::orderBy('name')->get();
        // print_r($customers);

        // $orders123 = AstPurchaseorder::select('id', 'customerName', 'createDate', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder', 'fabricPattern')
        // ->whereIn('id', $ecp)
        // // ->orderBy('created_at', 'desc')
        // ->orderBy('createDate', 'desc')
        // ->get();
        // return view('inventory.create', compact('orders','customers','orders123'));
        return view('inventory.create', compact('orders', 'customers'));
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
        //searchImport
var_dump($request)
        if ($request->filled('submit') && $request->submit == 'searchImport') {

    // --- normalize fabricStruct: * == x, ลบช่องว่าง, to lower ---
    $normFs = function (?string $s) {
        $s = (string) $s;
        $s = str_replace('undefined', '', $s);
        // ให้ * และ x เป็นตัวคั่นเดียวกัน และตัดช่องว่างรอบตัวคั่นออก
        $s = preg_replace('/\s*([*x])\s*/i', 'x', $s);
        // ลบช่องว่างที่เหลือทั้งหมด
        $s = preg_replace('/\s+/', '', $s);
        return strtolower(trim($s));
    };
    // ฝั่งคอลัมน์ใน DB (ast_purchaseorders)
    $fsExpr = "REPLACE(REPLACE(LOWER(fabricStructure),' ',''),'*','x')";

    // --- รับ input ---
    $customerName  = $request->input('customerName');
    $fabricStruct  = $request->input('fabricStruct');
    $fabricPattern = $request->input('fabricPattern');
    $fabricW       = $request->input('fabricW');   // ตาราง FabricAst
    $fabricId      = $request->input('fabricId');
    $orderId       = $request->input('orderId');
    $imDate        = $request->input('imDate');    // d/m/Y

    // ออเดอร์ที่อนุมัติให้ผลิต
    $ecp = AstPurchaseorder::where('status', 'อนุมัติให้ผลิต')->pluck('id');

    // ====== สร้างคิวรีแบบ "แม่นก่อน" ======
    $q = AstPurchaseorder::select(
            'id','customerName','createDate','fabricId',
            'fabricStructure','orderSumYard','purchaseOrder','fabricPattern'
        )->whereIn('id', $ecp);

    // customerName: เริ่มจาก like prefix ให้แคบก่อน
    if (filled($customerName)) {
        $q->where('customerName', 'LIKE', $customerName.'%');
    }

    // fabricStruct: เริ่มจาก == หลัง normalize (แม่น)
    if (filled($fabricStruct)) {
        $q->whereRaw("$fsExpr = ?", [$normFs($fabricStruct)]);
    }

    // fabricPattern: ถ้าต้องตรง ให้ใช้ '=' (ถ้าอยากยืดหยุ่นค่อย fallback)
    if (filled($fabricPattern)) {
        $q->where('fabricPattern', $fabricPattern);
    }

    // fabricW: อยู่ใน FabricAst — เริ่มจาก '=' ให้แม่นก่อน
    if (filled($fabricW)) {
        $q->whereExists(function ($sub) use ($fabricW) {
            $sub->select(DB::raw(1))
                ->from('fabric_asts') // แก้ให้ตรงชื่อตารางจริงของ FabricAst
                ->whereColumn('fabric_asts.purchaseOrder', 'ast_purchaseorders.purchaseOrder')
                ->where('fabric_asts.fabric_w', $fabricW);
        });
    }

    if (filled($fabricId)) {
        $q->where('fabricId', $fabricId);
    }

    if (filled($orderId)) {
        $q->where('id', $orderId);
    }

    if (filled($imDate)) {
        $dt = \DateTime::createFromFormat('d/m/Y', $imDate);
        if ($dt) {
            $q->whereDate('createDate', $dt->format('Y-m-d'));
        }
    }

    // ยิงรอบแรก (แม่น)
    $importorder = $q->orderBy('createDate','desc')->get();

    // ====== Fallback: ถ้าไม่เจอเลย ค่อย "ยืดหยุ่น" บางเงื่อนไข ======
    if ($importorder->isEmpty()) {
        $q2 = AstPurchaseorder::select(
                'id','customerName','createDate','fabricId',
                'fabricStructure','orderSumYard','purchaseOrder','fabricPattern'
            )->whereIn('id', $ecp);

        if (filled($customerName)) {
            // ขยายเป็น contains
            $q2->where('customerName', 'LIKE', '%'.$customerName.'%');
        }

        if (filled($fabricStruct)) {
            // ขยายจาก == เป็น LIKE ใกล้เคียง (normalized ทั้งสองฝั่ง)
            $needle = $normFs($fabricStruct);
            $q2->whereRaw("$fsExpr LIKE ?", ['%'.$needle.'%']);
        }

        if (filled($fabricPattern)) {
            // ขยายเป็น LIKE แทน =
            $q2->where('fabricPattern', 'LIKE', '%'.$fabricPattern.'%');
        }

        if (filled($fabricW)) {
            // ขยายเป็น LIKE แทน =
            $q2->whereExists(function ($sub) use ($fabricW) {
                $sub->select(DB::raw(1))
                    ->from('fabric_asts')
                    ->whereColumn('fabric_asts.purchaseOrder', 'ast_purchaseorders.purchaseOrder')
                    ->where('fabric_asts.fabric_w', 'LIKE', '%'.$fabricW.'%');
            });
        }

        if (filled($fabricId)) {
            $q2->where('fabricId', 'LIKE', '%'.$fabricId.'%');
        }

        if (filled($orderId)) {
            $q2->where('id', 'LIKE', '%'.$orderId.'%');
        }

        if (filled($imDate)) {
            $dt = \DateTime::createFromFormat('d/m/Y', $imDate);
            if ($dt) {
                $q2->whereDate('createDate', $dt->format('Y-m-d'));
            }
        }

        $importorder = $q2->orderBy('createDate','desc')->get();
    }

    // ====== คงตัวแปรประกอบที่ view ใช้อยู่เดิม ======
    $orders = AstPurchaseorder::select(
            'id','customerName','createDate','fabricId',
            'fabricStructure','orderSumYard','purchaseOrder','fabricPattern'
        )
        ->whereIn('id', $ecp)
        ->orderBy('createDate','desc')
        ->get();

    $inventorydata = Inventory::select(
            'refId',
            Inventory::raw('SUM(fold) as foldSum'),
            Inventory::raw('SUM(sumYard) as sumYardSum')
        )
        ->groupBy('refId')
        ->get();

    $fabricoutdata = fabricout::select(
            'orderId',
            fabricout::raw('COUNT(fold) as foldCount'),
            fabricout::raw('SUM(sumYard) as sumYardSum')
        )
        ->whereNotNull('orderId')
        ->groupBy('orderId')
        ->get();

    $f2 = FabricAst::select('purchaseOrder','fabric_w');
    if (filled($fabricW)) {
        $f2->where('fabric_w','LIKE','%'.$fabricW.'%');
    }
    $fabricoutdata2 = $f2->get();

    $select_search = '';
    $searchInput   = '';

    return view('inventory.index', compact(
        'importorder','select_search','inventorydata','fabricoutdata','fabricoutdata2','orders'
    ));
}

        //check next data then save and set end count to session  and show create with end count
        if ($request->filled('submit') && $request->submit == 'nextData') {
            $oldEnd = session()->get('endCount');
            //remove session
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('fabricId');
            session()->forget('fabricStruct');
            session()->forget('fabricPattern');
            session()->forget('fabricW');
            session()->forget('customer');

            //check data is null 
            // $foldData = $request->input('fold');
            // print($foldData[1]);

            $fabricData = $request->input('sumYard');

            $arr_data = array();

            // foreach ($fabricData as $data) {
            //     // print($data);

            //     if ($data  != '') {

            //         array_push($arr_data, $data);
            //     }
            // }
            $sum = 0;

            foreach ($fabricData as $data) {
                // print($data);

                if ($data  != '') {
                    $sum = $sum + $data;

                    array_push($arr_data, $data);
                }
            }

            //set sumyard  to session
            if (!session()->has('sum')) {
                session()->put('sum', $sum);
            } else {
                $sum = $sum + session()->get('sum');

                session()->forget('sum');
                session()->put('sum', $sum);
            }

            //set session from input 
            // $endCount = $request->input('endCount') ? $request->input('endCount') : 0;
            $endCount =  $oldEnd + count($arr_data);
            // print($endCount );
            if ($oldEnd == null) {
                $oldEnd  = 1;
            } else {
                $oldEnd = $oldEnd + 1;
            }
            session()->put('endCount',  $endCount);
            // $date = $request->input('dt') ? $request->input('dt') : date('Y-m-d');

            $fixedDate = str_replace('/', '-', $request->input('dt') ? $request->input('dt') : date('Y-m-d'));
            // $validatedData['createDate'] = date('Y-m-d', strtotime($fixedDate));
            // $createDate = $request->input('createDate');
            $date = date('Y-m-d', strtotime($fixedDate));

            session()->put('dt', $date);

            session()->put('fabricStruct',  $request->input('fabricStruct'));
            session()->put('fabricPattern',  $request->input('fabricPattern'));
            session()->put('fabricW', $request->input('fabricW'));
            session()->put('customer', $request->input('customer'));
            session()->put('fabricId', $request->input('fabricId'));


            //check input fabric 
            if (count($arr_data) > 0) {
                //check key of fabric lot input
                if (session()->has('refId')) {
                    //get key from session to variable key
                    $key = session()->get('refId');

                    //save data
                    $this->saveFabricData(
                        $arr_data,
                        $key,
                        auth()->user()->name,
                        session()->get('fabricId'),
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        $oldEnd,
                        $date,
                        session()->get('customer'),
                    );
                } else {
                    //generate key and set to session
                    // $key = base64_encode(random_bytes(32)); // generates a 32-byte (256-bit) key
                    // $key = urlencode($key);
                    $bytes = random_bytes(32);
                    $base64 = base64_encode($bytes);
                    $key = str_replace('/', '', $base64);
                    // echo $key;
                    session()->put(['refId' => $key]);

                    //save data
                    $this->saveFabricData(
                        $arr_data,
                        $key,
                        auth()->user()->name,
                        session()->get('fabricId'),
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        $oldEnd,
                        $date,
                        session()->get('customer'),
                    );
                }
            }
            // print_r($arr_data .'////'.
            // $key .'////'.
            // auth()->user()->name.'////'.
            // session()->get('fabricStruct').'////'.
            // session()->get('fabricPattern')
            // .'////'.
            // session()->get('fabricW')
            // .'////'.
            // session()->get('customer').'////'.
            // $oldEnd.'////'.
            // $date);
            print_r(session()->get('customer'));
            return $this->create();
        }

        //save last record
        if ($request->filled('submit') && $request->submit == 'endData') {
            $oldEnd = session()->get('endCount');
            //remove session
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('fabricStruct');
            session()->forget('fabricPattern');
            session()->forget('fabricW');
            session()->forget('customer');
            session()->forget('fabricId');

            //check data is null 
            // $foldData = $request->input('fold');
            // print($foldData[1]);

            $fabricData = $request->input('sumYard');

            $arr_data = array();

            foreach ($fabricData as $data) {
                // print($data);

                if ($data  != '') {

                    array_push($arr_data, $data);
                }
            }

            //set session from input 
            // $endCount = $request->input('endCount') ? $request->input('endCount') : 0;
            $endCount =  $oldEnd + count($arr_data);
            // print($endCount );
            if ($oldEnd == null) {
                $oldEnd  = 0;
            }

            if ($oldEnd >= 0) {
                $oldEnd = $oldEnd + 1;
            }

            session()->put('endCount',  $endCount);
            // $date = $request->input('dt') ? $request->input('dt') : date('Y-m-d');

            $fixedDate = str_replace('/', '-', $request->input('dt') ? $request->input('dt') : date('Y-m-d'));
            // $validatedData['createDate'] = date('Y-m-d', strtotime($fixedDate));
            // $createDate = $request->input('createDate');
            $date = date('Y-m-d', strtotime($fixedDate));

            session()->put('dt', $date);
            session()->put('fabricStruct',  $request->input('fabricStruct'));
            session()->put('fabricPattern',  $request->input('fabricPattern'));
            session()->put('fabricW', $request->input('fabricW'));
            session()->put('customer', $request->input('customer'));
            session()->put('fabricId', $request->input('fabricId'));


            //check input fabric 
            if (count($arr_data) > 0) {
                //check key of fabric lot input
                if (session()->has('refId')) {
                    //get key from session to variable key
                    $key = session()->get('refId');

                    //save data
                    $this->saveFabricData(
                        $arr_data,
                        $key,
                        auth()->user()->name,
                        session()->get('fabricId'),
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        $oldEnd,
                        $date,
                        session()->get('customer'),
                    );
                } else {
                    //generate key and set to session
                    // $key = base64_encode(random_bytes(32)); // generates a 32-byte (256-bit) key
                    // $key = urlencode($key);
                    $bytes = random_bytes(32);
                    $base64 = base64_encode($bytes);
                    $key = str_replace('/', '', $base64);
                    // echo $key;
                    session()->put(['refId' => $key]);

                    //save data
                    $this->saveFabricData(
                        $arr_data,
                        $key,
                        auth()->user()->name,
                        session()->get('fabricId'),
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        $oldEnd,
                        $date,
                        session()->get('customer'),
                    );
                }
            }


            //remove session
            session()->forget('refId');
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('fabricId');
            session()->forget('fabricStruct');
            session()->forget('fabricPattern');
            session()->forget('fabricW');
            session()->forget('customer');
            session()->forget('sum');


            // return $this->create();
            return redirect('/stockfabric');
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
        if ($request->filled('submit') && $request->submit == "genPDF") {

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
            $this->fpdf->SetFont('THSarabunNew', '', 16);
            $this->fpdf->Cell(70, 10, '', 0, 0);
            $this->fpdf->SetFont('THSarabunNew', 'B', 24);
            $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', 'AST ใบส่งคืนสินค้า'), 0, 0);
            $this->fpdf->Cell(20, 10, '', 0, 0);
            $this->fpdf->Cell(80, 10, 'No.', 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(150, 5, '', 0, 0);
            // $this->fpdf->Cell(40, 10, iconv('UTF-8', 'cp874', 'ว.ด.ป.' . $day . '/' . $month . '/' . $year), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
            $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'ผู้สั่ง .....'), 0, 0);
            $this->fpdf->Cell(60, 10, '', 0, 0);
            $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', 'ผู้รับ   .....    '), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
            $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'รหัสผ้า  .....    '), 0, 0);
            $this->fpdf->Cell(60, 10, '', 0, 0);
            $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', 'วันที่   .....    '), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line


            // $this->fpdf->SetFont('Arial', '', 12);
            // $this->fpdf->Ln();
            // $this->fpdf->Cell(60, 10, 'Header 1', 1);
            // $this->fpdf->Cell(60, 10, 'Header 2', 1);
            // $this->fpdf->Cell(60, 10, 'Header 3', 1);
            // $this->fpdf->Ln();
            // $this->fpdf->Cell(60, 10, 'Row 1, Column 1', 1);
            // $this->fpdf->Cell(60, 10, 'Row 1, Column 2', 1);
            // $this->fpdf->Cell(60, 10, 'Row 1, Column 3', 1);
            // $this->fpdf->Ln();
            // $this->fpdf->Cell(60, 10, 'Row 2, Column 1', 1);
            // $this->fpdf->Cell(60, 10, 'Row 2, Column 2', 1);
            // $this->fpdf->Cell(60, 10, 'Row 2, Column 3', 1);

            // $this->fpdf->AddPage();
            // $this->fpdf->SetFont('Arial', 'B', 16);
            // $this->fpdf->Cell(40, 10, 'Table 2');

            // $this->fpdf->SetFont('Arial', '', 12);
            // $this->fpdf->Ln();
            // $this->fpdf->Cell(60, 10, 'Header 1', 1);
            // $this->fpdf->Cell(60, 10, 'Header 2', 1);
            // $this->fpdf->Cell(60, 10, 'Header 3', 1);
            // $this->fpdf->Ln();
            // $this->fpdf->Cell(60, 10, 'Row 1, Column 1', 1);
            // $this->fpdf->Cell(60, 10, 'Row 1, Column 2', 1);
            // $this->fpdf->Cell(60, 10, 'Row 1, Column 3', 1);
            // $this->fpdf->Ln();
            // $this->fpdf->Cell(60, 10, 'Row 2, Column 1', 1);
            // $this->fpdf->Cell(60, 10, 'Row 2, Column 2', 1);
            // $this->fpdf->Cell(60, 10, 'Row 2, Column 3', 1);
            $this->fpdf->Cell(20, 10, '', 5, 1); //end of line

            $this->fpdf->SetFont('Arial', '', 12);
            $a = 46;
            // Generate the data for the first column
            $column1 = array();
            for ($i = 1; $i <= 20; $i++) {
                $column1[] = "$i";
            }

            // Generate the data for the second column based on the first column
            $column2 = array();
            for ($i = 0; $i < count($column1); $i++) {
                $column2[] = "new " . $column1[$i];
            }

            // Display the data in two columns
            $col_width = $this->fpdf->GetPageWidth() / 8;
            $x = $this->fpdf->GetX();
            $y = $this->fpdf->GetY();

            for ($i = 0; $i < count($column1); $i++) {
                $this->fpdf->SetXY($x, $y);
                $this->fpdf->Cell($col_width, 5, $column1[$i], 1);
                $this->fpdf->SetXY($x + $col_width, $y);
                $this->fpdf->Cell($col_width, 5, $column2[$i], 1);

                if ($i + 20 < $a) {
                    $this->fpdf->SetXY($x + $col_width * 2, $y);
                    $this->fpdf->Cell($col_width, 5, $column1[$i] + 20, 1);
                    $this->fpdf->SetXY($x + $col_width * 3, $y);
                    $this->fpdf->Cell($col_width, 5, $column2[$i], 1);
                } else {
                }
                // $this->fpdf->SetXY($x+ $col_width*2, $y);
                // $this->fpdf->Cell($col_width, 5, $column1[$i] + 10, 1);
                // $this->fpdf->SetXY($x + $col_width*3, $y);
                // $this->fpdf->Cell($col_width, 5, $column2[$i], 1);
                if ($i + 40 < $a) {
                    $this->fpdf->SetXY($x + $col_width * 4, $y);
                    $this->fpdf->Cell($col_width, 5, $column1[$i] + 40, 1);
                    $this->fpdf->SetXY($x + $col_width * 5, $y);
                    $this->fpdf->Cell($col_width, 5, $column2[$i], 1);
                } else {
                }
                // $this->fpdf->SetXY($x+ $col_width*4, $y);
                // $this->fpdf->Cell($col_width, 5, $column1[$i] +20, 1);
                // $this->fpdf->SetXY($x + $col_width*5, $y);
                // $this->fpdf->Cell($col_width, 5, $column2[$i], 1);

                $y += 5;
                if ($y > $this->fpdf->GetPageHeight() - 20) {
                    $this->fpdf->AddPage();
                    $y = 20;
                }
            }
            $this->fpdf->Cell(20, 20, '', 5, 1); //end of line
            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
            $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'รวม ..... พับ'), 0, 0);
            $this->fpdf->Cell(30, 10, '', 0, 0);
            $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', '   .....    หลา'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
            $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'ลงชื่อประทับตา  .....    '), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
            $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'หมายเหตุ'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
            $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', '..................................   .....    '), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line



            $this->fpdf->Output();
            exit;
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

    private function saveFabricData($data, $refId, $emp, $fabricId, $fabricStruct, $fabricPattern, $fabricW, $start, $createDate, $customer)
    {
        // echo   $refId .' '. $emp.' '. $fabricStruct .' '. $fabricW .' '. $start .' '. $createDate;
        $c = $start;
        foreach ($data as $datasave) {
            $sf = stockfabric::create([
                'refId' => $refId,
                'emp' => $emp,
                'fabricId' => $fabricId,
                'fabricStruct' => $fabricStruct,
                'fabricPattern' => $fabricPattern,
                'fabricW' => $fabricW,
                'fold' => $c,
                'sumYard' => $datasave,
                'createDate' => $createDate,
                'customer' => $customer
            ]);

            $c = $c + 1;
            // print_r($customer);
        }
    }
}
