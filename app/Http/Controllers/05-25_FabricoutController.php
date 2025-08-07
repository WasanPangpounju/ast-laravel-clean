<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customer;
use App\Models\fabricout;
use App\Models\AstPurchaseorder;
use App\Models\FabricAst;
use App\Models\FabricAststructure;
use App\Models\stockfabric;

use PDF;
use Dompdf\Dompdf;
use Codedge\Fpdf\Fpdf\Fpdf;

use Carbon\Carbon;

class FabricoutController extends Controller
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
        // $records = fabricout::groupBy(['fabricStruct', 'no', 'refId', 'customerName', 'receiveName','fabricPattern','fabricW'])
        //     ->selectRaw('fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
        //     ->get();

        $records = fabricout::groupBy(['fabricStruct', 'no', 'refId', 'customerName', 'receiveName', 'fabricPattern', 'fabricW'])
            ->selectRaw('fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
            ->orderBy('lastDate', 'DESC')
            ->get();

        // ->count();
        // var_dump($records );
        $sumfabricout = $records;
        // print_r($sumfabricout);
        // $orders = fabricout::selectRaw('no,fold , sumYard')
        // ->where('no', 1016)
        // ->get();
        // print_r($orders);
        return view('fabricout.index', compact('sumfabricout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (session()->get('endCount') <= 0) {

            //remove session
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('customerName');
            session()->forget('receiveName');
            session()->forget('comment');
            session()->forget('receiveType');
            session()->forget('orderId');

            session()->forget('fabricStruct');
            session()->forget('fabricPattern');
            session()->forget('fabricW');
        }


        $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();

        $orders = AstPurchaseorder::select('id', 'customerName', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder')
            ->whereIn('id', $ecp)
            ->orderBy('customerName')
            ->get();


        $lastRecord = Fabricout::latest()->first(); // get the last record of the table
        $no = $lastRecord->no; // get the value of the "no" field from the last record
        $no = $no + 1;

        if (!session()->has('no')) {
            session()->put('no', $no);
        }

        // session()->put('no', 1001);

        $customers = Customer::orderBy('name')->get();

        // return view('fabricout.create', compact('customers'));
        $order_id = '';
        $customer_name = '';
        $fabric_struct = '';

        $stockFabricStruct = stockfabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW',])
            ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as 
        lastDate')
            ->get();

        return view('fabricout.create', compact('customers', 'order_id', 'customer_name', 'fabric_struct', 'orders', 'stockFabricStruct'));
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

        //generate by order
        if ($request->filled('submit') && $request->submit == 'generateByOrder') {
            $customers = Customer::orderBy('name')->get();
            // session()->put('no', 1001);
            $lastRecord = Fabricout::latest()->first(); // get the last record of the table
            $no = $lastRecord->no; // get the value of the "no" field from the last record
            $no = $no + 1;

            if (!session()->has('no')) {
                session()->put('no', $no);
            }

            $order_id = $request->input('orderId');
            session()->put('orderId', $order_id);

            $customer_name = $request->input('customerName');
            $fabric_struct = $request->input('fabricStruct');
            $order_send = AstPurchaseorder::select('customerName', 'fabricId', 'fabricStructure', 'fabricPattern')
                ->where('id', $order_id)
                ->get();

            $order_sendW = FabricAst::select('fabric_w')
                ->where('purchaseOrder', $order_id)
                ->get();

            $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();

            $orders = AstPurchaseorder::select('id', 'customerName', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder')
                ->whereIn('id', $ecp)
                ->orderBy('customerName')
                ->get();
            // var_dump($order_sendW);
            session()->forget('fabricStruct');
            session()->forget('fabricPattern');
            session()->forget('fabricW');

            session()->put('fabricStruct',  $order_send[0]->fabricStructure);
            session()->put('fabricPattern', $order_send[0]->fabricPattern);
            session()->put('fabricW', $order_sendW[0]->fabric_w);

            $stockFabricStruct = stockfabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW',])
                ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as 
                lastDate')
                ->get();



            return view('fabricout.create', compact('customers', 'order_id', 'customer_name', 'fabric_struct', 'orders', 'stockFabricStruct'));
        }

        //check next data then save and set end count to session  and show create with end count
        if ($request->filled('submit') && $request->submit == 'nextData') {
            $oldEnd = session()->get('endCount');
            //remove session
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('customerName');
            session()->forget('receiveName');
            session()->forget('comment');
            session()->forget('receiveType');
            session()->forget('orderId');

            session()->forget('fabricStruct');
            session()->forget('fabricPattern');
            session()->forget('fabricW');


            $fabricData = $request->input('sumYard');

            $arr_data = array();

            //sum yard
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
                $oldEnd  = $oldEnd   + 1;
            }

            session()->put('endCount',  $endCount);
            $date = $request->input('dt') ? $request->input('dt') : date('Y-m-d');
            session()->put('dt', $date);

            session()->put('customerName', $request->input('customerName'));
            session()->put('receiveName', $request->input('receiveName'));
            session()->put('comment', $request->input('comment'));
            session()->put('receiveType', $request->input('receiveType'));
            session()->put('orderId', $request->input('orderId'));

            session()->put('fabricStruct',  $request->input('fabricStruct'));
            session()->put('fabricPattern',  $request->input('fabricPattern'));
            session()->put('fabricW',  $request->input('fabricW'));


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
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        $oldEnd,
                        $date,
                        session()->get('no'),
                        session()->get('customerName'),
                        session()->get('receiveName'),
                        session()->get('comment'),
                        session()->get('receiveType'),
                        $request->input('orderId')
                    );
                } else {
                    //generate key and set to session
                    $key = base64_encode(random_bytes(32)); // generates a 32-byte (256-bit) key
                    // echo $key;
                    session()->put(['refId' => $key]);

                    //save data
                    $this->saveFabricData(
                        $arr_data,
                        $key,
                        auth()->user()->name,
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        $oldEnd,
                        $date,
                        session()->get('no'),
                        session()->get('customerName'),
                        session()->get('receiveName'),
                        session()->get('comment'),
                        session()->get('receiveType'),
                        $request->input('orderId')
                    );
                }
            }
            return $this->create();
        }

        //save last record
        if ($request->filled('submit') && $request->submit == 'endData') {
            $oldEnd = session()->get('endCount');

            //remove session
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('customerName');
            session()->forget('receiveName');
            session()->forget('comment');
            session()->forget('receiveType');

            session()->forget('fabricStruct');
            session()->forget('fabricPattern');
            session()->forget('fabricW');


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
                $oldEnd  = 1;
            }
            session()->put('endCount',  $endCount);
            $date = $request->input('dt') ? $request->input('dt') : date('Y-m-d');
            session()->put('dt', $date);

            session()->put('customerName', $request->input('customerName'));
            session()->put('receiveName', $request->input('receiveName'));
            session()->put('comment', $request->input('comment'));
            session()->put('receiveType', $request->input('receiveType'));
            session()->put('orderId', $request->input('orderId'));

            session()->put('fabricStruct',  $request->input('fabricStruct'));
            session()->put('fabricPattern',  $request->input('fabricPattern'));
            session()->put('fabricW',  $request->input('fabricW'));


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
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        $oldEnd,
                        $date,
                        session()->get('no'),
                        session()->get('customerName'),
                        session()->get('receiveName'),
                        session()->get('comment'),
                        session()->get('receiveType'),
                        $request->input('orderId')
                    );
                } else {
                    //generate key and set to session
                    $key = base64_encode(random_bytes(32)); // generates a 32-byte (256-bit) key
                    // echo $key;
                    session()->put(['refId' => $key]);

                    //save data
                    $this->saveFabricData(
                        $arr_data,
                        $key,
                        auth()->user()->name,
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        $oldEnd,
                        $date,
                        session()->get('no'),
                        session()->get('customerName'),
                        session()->get('receiveName'),
                        session()->get('comment'),
                        session()->get('receiveType'),
                        $request->input('orderId')
                    );
                }
            }


            //remove session
            session()->forget('refId');
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('fabricStruct');
            session()->forget('fabricPattern');
            session()->forget('fabricW');
            session()->forget('no');
            session()->forget('orderId');
            session()->forget('sum');


            // return $this->create();
            return redirect('/fabricout');
        }
        if ($request->filled('submit') && $request->submit == 'submitfabricout') {
            $fabricout_no = $request->fabricout_no;
            $records = fabricout::groupBy(['comment', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName'])
                ->selectRaw('comment,fabricStruct,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                ->where('no', $fabricout_no)
                ->get();

            // $orders = fabricout::select('no, fold , sumYard')
            //     ->where('no','', $fabricout_no)
            //     ->get();
            //     print_r($orders);

            $orders = fabricout::selectRaw('no,fold , sumYard')
                ->where('no', $fabricout_no)
                ->get();

            // $recordCount = fabricout::groupBy(['no'])
            //     ->selectRaw('COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
            //     ->get();

            // $users = fabricout::where('no', $fabricout_no)
            //     ->selectRaw('customerName,receiveName,fabricStruct,comment,COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
            //     ->groupBy('no',)
            //     ->get();
            // print_r($orders);
            // foreach($orders as $order) {
            //     $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', $order->fold), 0, 0);
            //     $this->fpdf->Cell(20, 10, '', 0, 0);
            //     $this->fpdf->Cell(80, 10, 'No.', 0, 0);
            //     $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            // }



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
            // $this->fpdf->Cell(80, 10, 'No.', 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(150, 5, '', 0, 0);
            // $this->fpdf->Cell(40, 10, iconv('UTF-8', 'cp874', 'ว.ด.ป.' . $day . '/' . $month . '/' . $year), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
            foreach ($records as $userrr) {
                // $this->fpdf->SetXY($x, $y);
                $this->fpdf->Cell(10, 5, '', 5, 0); //end of line
                $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'ผู้สั่ง : ' . $userrr->customerName . ' '), 0, 0);

                $this->fpdf->Cell(60, 10, '', 0, 0);
                $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', 'ผู้รับ : ' . $userrr->receiveName . ' '), 0, 0);
                $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
                $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
                $this->fpdf->Cell(10, 5, '', 5, 0); //end of line
                $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'รหัสผ้า :' . $userrr->fabricStruct . ' '), 0, 0);
                $this->fpdf->Cell(60, 10, '', 0, 0);
                $date = date('d/m/Y', strtotime($userrr->lastDate));
                $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', 'วันที่ : ' . $date . ' '), 0, 0);
                $this->fpdf->Cell(20, 10, '', 5, 1); //end of line

            }

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

            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line

            $this->fpdf->SetFont('THSarabunNew', '', 12);
            foreach ($records as $userrr) {
                $a = $userrr->foldCount;
                // $a = 160;
            }

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
            $col_width = $this->fpdf->GetPageWidth() / 16;
            $x = $this->fpdf->GetX() + 10;
            $y = $this->fpdf->GetY();

            $sum1 = 0;
            $sum2 = 0;
            $sum3 = 0;
            $sum4 = 0;
            $sum5 = 0;
            $sum6 = 0;
            $sum7 = 0;
            $sum8 = 0;

            for ($i = 0; $i < 8; $i++) {
                $this->fpdf->SetXY($x + ($col_width - 6) * (3 * $i), $y - 5);
                $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', 'ลำดับ'), 1);
                $this->fpdf->SetXY($x + ($col_width - 6) * ((3 * $i) + 1.15), $y - 5);
                $this->fpdf->Cell($col_width, 5, iconv('UTF-8', 'cp874', '   หลา'), 1);
            }
            for ($i = 0; $i < count($column1); $i++) {
                foreach ($orders as $key => $order) {
                    if ($i < $a) {
                        if ($key === $i) {
                            $this->fpdf->SetFont('THSarabunNew', '', 12);
                            $this->fpdf->SetXY($x, $y);
                            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i]), 1);
                            $this->fpdf->SetXY($x + $col_width - 5, $y);
                            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                            $this->fpdf->Cell($col_width, 5,  $order->sumYard, 1);
                            $sum1 += $order->sumYard;
                        }
                    } else {
                        $this->fpdf->SetFont('THSarabunNew', '', 12);
                        $this->fpdf->SetXY($x, $y);
                        $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i]), 1);
                        $this->fpdf->SetXY($x + $col_width - 5, $y);
                        $this->fpdf->Cell($col_width, 5,  '', 1);
                    }
                }

                foreach ($orders as $key => $order) {
                    if ($i + 20 < $a) {
                        if ($key === $i + 20) {
                            $this->fpdf->SetFont('THSarabunNew', '', 12);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 3, $y);
                            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 20), 1);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 4.15, $y);
                            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                            $this->fpdf->Cell($col_width, 5, $order->sumYard, 1);
                            $sum2 += $order->sumYard;
                        }
                    } else {
                        $this->fpdf->SetFont('THSarabunNew', '', 12);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 3, $y);
                        $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 20), 1);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 4.15, $y);
                        $this->fpdf->Cell($col_width, 5, '', 1);
                    }
                }
                // // $this->fpdf->SetXY($x+ $col_width*2, $y);
                // // $this->fpdf->Cell($col_width, 5, $column1[$i] + 10, 1);
                // // $this->fpdf->SetXY($x + $col_width*3, $y);
                // // $this->fpdf->Cell($col_width, 5, $column2[$i], 1);
                foreach ($orders as $key => $order) {
                    if ($i + 40 < $a) {
                        if ($key === $i + 40) {
                            $this->fpdf->SetFont('THSarabunNew', '', 12);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 6, $y);
                            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 40), 1);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 7.15, $y);
                            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                            $this->fpdf->Cell($col_width, 5, $order->sumYard, 1);
                            $sum3 += $order->sumYard;
                        }
                    } else {
                        $this->fpdf->SetFont('THSarabunNew', '', 12);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 6, $y);
                        $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 40), 1);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 7.15, $y);
                        // $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                        $this->fpdf->Cell($col_width, 5, '', 1);
                    }
                }
                foreach ($orders as $key => $order) {
                    if ($i + 60 < $a) {
                        if ($key === $i + 60) {
                            $this->fpdf->SetFont('THSarabunNew', '', 12);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 9, $y);
                            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 60), 1);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 10.15, $y);
                            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                            $this->fpdf->Cell($col_width, 5, $order->sumYard, 1);
                            $sum4 += $order->sumYard;
                        }
                    } else {
                        $this->fpdf->SetFont('THSarabunNew', '', 12);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 9, $y);
                        $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 60), 1);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 10.15, $y);
                        $this->fpdf->Cell($col_width, 5, '', 1);
                    }
                }
                foreach ($orders as $key => $order) {
                    if ($i + 80 < $a) {
                        if ($key === $i + 80) {
                            $this->fpdf->SetFont('THSarabunNew', '', 12);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 12, $y);
                            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 80), 1);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 13.15, $y);
                            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                            $this->fpdf->Cell($col_width, 5, $order->sumYard, 1);
                            $sum5 += $order->sumYard;
                        }
                    } else {
                        $this->fpdf->SetFont('THSarabunNew', '', 12);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 12, $y);
                        $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 80), 1);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 13.15, $y);
                        $this->fpdf->Cell($col_width, 5, '', 1);
                    }
                }
                foreach ($orders as $key => $order) {
                    if ($i + 100 < $a) {
                        if ($key === $i + 100) {
                            $this->fpdf->SetFont('THSarabunNew', '', 12);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 15, $y);
                            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 100), 1);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 16.15, $y);
                            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                            $this->fpdf->Cell($col_width, 5, $order->sumYard, 1);
                            $sum6 += $order->sumYard;
                        }
                    } else {
                        $this->fpdf->SetFont('THSarabunNew', '', 12);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 15, $y);
                        $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 100), 1);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 16.15, $y);
                        $this->fpdf->Cell($col_width, 5, '', 1);
                    }
                }
                foreach ($orders as $key => $order) {
                    if ($i + 120 < $a) {
                        if ($key === $i + 120) {
                            $this->fpdf->SetFont('THSarabunNew', '', 12);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 18, $y);
                            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 120), 1);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 19.15, $y);
                            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                            $this->fpdf->Cell($col_width, 5, $order->sumYard, 1);
                            $sum7 += $order->sumYard;
                        }
                    } else {
                        $this->fpdf->SetFont('THSarabunNew', '', 12);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 18, $y);
                        $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 120), 1);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 19.15, $y);
                        $this->fpdf->Cell($col_width, 5, '', 1);
                    }
                }
                foreach ($orders as $key => $order) {
                    if ($i + 140 < $a) {
                        if ($key === $i + 140) {
                            $this->fpdf->SetFont('THSarabunNew', '', 12);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 21, $y);
                            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 140), 1);
                            $this->fpdf->SetXY($x + ($col_width - 6) * 22.15, $y);
                            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                            $this->fpdf->Cell($col_width, 5, $order->sumYard, 1);
                            $sum8 += $order->sumYard;
                        }
                    } else {
                        $this->fpdf->SetFont('THSarabunNew', '', 12);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 21, $y);
                        $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', $column1[$i] + 140), 1);
                        $this->fpdf->SetXY($x + ($col_width - 6) * 22.15, $y);
                        $this->fpdf->Cell($col_width, 5, '', 1);
                    }
                }
                // }
                // $this->fpdf->SetXY($x+ $col_width*4, $y);
                // $this->fpdf->Cell($col_width, 5, $column1[$i] +20, 1);
                // $this->fpdf->SetXY($x + $col_width*5, $y);
                // $this->fpdf->Cell($col_width, 5, $column2[$i], 1);

                $y += 5;
                if ($y > $this->fpdf->GetPageHeight() - 30) {
                    $this->fpdf->AddPage();
                    $y = 30;
                }
            }
            $this->fpdf->SetFont('THSarabunNew', '', 12);
            // $this->fpdf->Cell(20, 20, '', 5, 1); //end of line
            $this->fpdf->SetXY($x, $y);
            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', 'รวม'), 1);
            $this->fpdf->SetXY($x + ($col_width - 5), $y);
            $this->fpdf->Cell($col_width, 5, iconv('UTF-8', 'cp874', $sum1), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 3, $y);
            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', 'รวม'), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 4.15, $y);
            $this->fpdf->Cell($col_width, 5, iconv('UTF-8', 'cp874', $sum2), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 6, $y);
            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', 'รวม'), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 7.15, $y);
            $this->fpdf->Cell($col_width, 5, iconv('UTF-8', 'cp874', $sum3), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 9, $y);
            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', 'รวม'), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 10.15, $y);
            $this->fpdf->Cell($col_width, 5, iconv('UTF-8', 'cp874', $sum4), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 12, $y);
            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', 'รวม'), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 13.15, $y);
            $this->fpdf->Cell($col_width, 5, iconv('UTF-8', 'cp874', $sum5), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 15, $y);
            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', 'รวม'), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 16.15, $y);
            $this->fpdf->Cell($col_width, 5, iconv('UTF-8', 'cp874', $sum6), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 18, $y);
            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', 'รวม'), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 19.15, $y);
            $this->fpdf->Cell($col_width, 5, iconv('UTF-8', 'cp874', $sum7), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 21, $y);
            $this->fpdf->Cell($col_width - 5, 5, iconv('UTF-8', 'cp874', 'รวม'), 1);
            $this->fpdf->SetXY($x + ($col_width - 6) * 22.15, $y);
            $this->fpdf->Cell($col_width, 5, iconv('UTF-8', 'cp874', $sum8), 1);
            $this->fpdf->SetFont('THSarabunNew', 'B', 18);


            // write the text on top
            foreach ($records as $userrr) {
                // $this->fpdf->setXY(50, 210);
                $col_width = 30;
                $row_height = 6;
                $this->fpdf->setXY(90, 180);
                $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', 'รวม'), 0, 0);
                $this->fpdf->SetFont('THSarabunNew', '', 14);
                $this->fpdf->setXY(90, 185);
                $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', 'Total'), 0, 0);
                $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                $this->fpdf->setXY(100, 180);
                $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', $userrr->foldCount), 0, 0);
                $this->fpdf->setXY(110, 180);
                $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', 'พับ'), 0, 0);
                $this->fpdf->SetFont('THSarabunNew', '', 14);
                $this->fpdf->setXY(110, 185);
                $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', 'Pieces'), 0, 0);
                // $this->fpdf->setXY(130, 180);
                $rightMargin = 55;
                $textWidth = $this->fpdf->GetStringWidth($userrr->sumYardSum);
                $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                $this->fpdf->SetXY($xPos, 180);
                $this->fpdf->Cell($textWidth, $row_height, iconv('UTF-8', 'cp874', $userrr->sumYardSum), 0, 1, 'R');

                // $this->fpdf->setXY(130, 180);
                // $this->fpdf->Cell($col_width, $row_height, iconv('UTF-8', 'cp874',  $userrr->sumYardSum), 0, 1);
                $this->fpdf->setXY(160, 180);
                $this->fpdf->Cell($col_width, $row_height, iconv('UTF-8', 'cp874',  'หลา'), 0, 1);
                $this->fpdf->SetFont('THSarabunNew', '', 14);
                $this->fpdf->setXY(160, 185);
                $this->fpdf->Cell($col_width, $row_height, iconv('UTF-8', 'cp874',  'Yards'), 0, 1);
            }

            // $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'รวม ......' . $userrr->foldCount . ' พับ'), 0, 0);
            // $this->fpdf->Cell(30, 10, '', 0, 0);
            // $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', '   .....  ' . $userrr->sumYardSum . '  หลา'), 0, 0);

            // $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            // $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
            $this->fpdf->setXY(20, 180);
            $this->fpdf->Cell(60, 60, iconv('UTF-8', 'cp874', '      ตัวอย่างผ้า'), 1, 0);
            $this->fpdf->setXY(20, 190);
            $this->fpdf->Cell(60, 60, iconv('UTF-8', 'cp874', '      Sample'), 0, 0);
            $this->fpdf->setXY(90, 210);
            $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'ลงชื่อประทับตรา'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
            $this->fpdf->SetFont('THSarabunNew', '', 14);
            $this->fpdf->setXY(90, 215);
            $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'Authorize Signature_____________________________________________'), 0, 0);
            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
            $this->fpdf->setXY(90, 235);
            $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'หมายเหตุ'), 0, 0);
            $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
            // $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
            // foreach ($records as $userrr) {
            $this->fpdf->setXY(90, 250);
            $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', 'ได้รับผ้าตามรายการข้างบนนี้ไว้ถูกต้องและเรียบร้อยแล้ว'), 0, 1);
            $this->fpdf->setXY(90, 265);
            $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', 'Received the above goods in good order and condition'), 0, 1);
            // }
            // foreach ($orders as $key => $order) {
            //     if ($key === 1 || $key === 2 || $key === 5 || $key === 6) {
            //         $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', $order->fold . ' ' . $order->sumYard), 0, 0);
            //         $this->fpdf->Cell(20, 5, '', 0, 1); //end of line
            //     }
            //     // $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', $order->fold . ' ' . $order->sumYard), 0, 0);
            //     // $this->fpdf->Cell(20, 5, '', 0, 1); //end of line
            // }



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

    private function saveFabricData($data, $refId, $emp, $fabricStruct, $fabricPattern, $fabricW, $start, $createDate, $no, $customerName, $receiveName, $comment, $receiveType, $orderId)
    {
        // echo   $refId .' '. $emp.' '. $fabricStruct .' '. $fabricW .' '. $start .' '. $createDate;
        // echo $orderId;
        $c = $start;
        foreach ($data as $datasave) {
            $sf = fabricout::create([
                'refId' => $refId,
                'emp' => $emp,
                'orderId' => $orderId,
                'no' => $no,
                'customerName' => $customerName,
                'receiveName' => $receiveName,
                'receiveType' => $receiveType,
                'comment' => $comment,
                'fabricStruct' => $fabricStruct,
                'fabricPattern' => $fabricPattern,
                'fabricW' =>  $fabricW,
                'fold' => $c,
                'sumYard' => $datasave,
                'createDate' => $createDate
            ]);

            $c = $c + 1;
        }
    }
}
