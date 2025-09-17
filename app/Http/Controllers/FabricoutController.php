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
    try {
        // ดึงข้อมูลพร้อม groupBy และ aggregate functions
        $records = fabricout::groupBy([
                'vatType',
                'vatNo',
                'fabricStruct',
                'no',
                'refId',
                'customerName',
                'receiveName',
                'fabricPattern',
                'fabricW'
            ])
            ->selectRaw('
                refId,
                vatNo,
                vatType,
                fabricStruct,
                fabricPattern,
                fabricW,
                receiveName,
                no,
                customerName,
                COUNT(fold) as foldCount,
                SUM(sumYard) as sumYardSum,
                MAX(createDate) as lastDate
            ')
            ->orderBy('vatNo', 'DESC') // เรียง vatNo จากมากไปน้อย
            ->get();

        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if ($records->isEmpty()) {
            $sumfabricout = collect(); // ถ้าไม่มีข้อมูล ให้เป็น collection ว่าง
        } else {
            $sumfabricout = $records;
        }

        // ดึงหมายเลข no ที่ไม่ซ้ำกัน
        $nofind = fabricout::select('no')->groupBy('no')->get();

    } catch (\Exception $e) {
        // ถ้ามีข้อผิดพลาดขณะดึงข้อมูล
        return back()->with('error', 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage());
    }

    // ส่งข้อมูลไปยัง view
    return view('fabricout.index', compact('sumfabricout', 'nofind'));
}

    public function index5()
{
    try {
        // ลองดึงข้อมูลโดย group by และ select ตามที่ต้องการ
        $records = fabricout::groupBy([
                'vatType',
                'vatNo',
                'fabricStruct',
                'no',
                'refId',
                'customerName',
                'receiveName',
                'fabricPattern',
                'fabricW'
            ])
            ->selectRaw('refId,vatNo,vatType,fabricStruct,fabricPattern,fabricW,receiveName,no,customerName,COUNT(fold) as foldCount,SUM(sumYard) as sumYardSum,MAX(createDate) as lastDate')
            ->orderBy('lastDate', 'DESC')
            ->get();

        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if ($records->isEmpty()) {
            // กรณีไม่มีข้อมูล
            $sumfabricout = collect(); // collection ว่าง
        } else {
            $sumfabricout = $records;
        }

        // ดึงเลข no ที่ไม่ซ้ำ
        $nofind = fabricout::select('no')->groupBy('no')->get();

    } catch (\Exception $e) {
        // ดักจับข้อผิดพลาดจาก database/query แล้วส่งกลับ error หรือค่าดีฟอลต์
        return back()->with('error', 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage());
        // หรือจะ return view พร้อมข้อมูลว่าง
        // return view('fabricout.index', ['sumfabricout' => collect(), 'nofind' => collect(), 'errorMsg' => $e->getMessage()]);
    }

    return view('fabricout.index', compact('sumfabricout', 'nofind'));
}

    public function index2()
    {
        //
        // $records = fabricout::groupBy(['fabricStruct', 'no', 'refId', 'customerName', 'receiveName','fabricPattern','fabricW'])
        //     ->selectRaw('fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
        //     ->get();

        $records = fabricout::groupBy(['vatType', 'vatNo', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName', 'fabricPattern', 'fabricW'])
            ->selectRaw('refId,vatNo,vatType,fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
            ->orderBy('lastDate', 'DESC')
            ->get();

        // ->count();
        // var_dump($records );
        $sumfabricout = $records;
        // $customers = Customer::orderBy('name')->get();
        // $nofind = fabricout::orderBy('no')->groupBy('no')->get();
        $nofind = fabricout::select('no')
            ->groupBy('no')
            ->get();
        // ->selectRaw('fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
        // // ->orderBy('lastDate', 'DESC')
        // ->get();
        // print(count($nofind) );
        // print(count($sumfabricout) );
        return view('fabricout.index', compact('sumfabricout', 'nofind'));
    }

    public static function findNo()
    {
        $nofind = fabricout::select('no')
            ->groupBy('no')
            ->get();

        return         $nofind;
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

            session()->forget('customerReplace');
            session()->forget('fabricStructReplace');

            session()->forget('vatNo');
            session()->forget('vatType');
        }


        $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();

        $orders = AstPurchaseorder::select('id', 'customerName', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder')
            ->whereIn('id', $ecp)
            ->orderBy('customerName')
            ->get();


        $lastVat = Fabricout::groupBy('vatType')
            ->select('vatType', Fabricout::raw('MAX(vatNo) as max_no'))
            ->get();

        $vatA = '1001';
        $vatB = '1001';
        $vatC = '1001';
        foreach ($lastVat as $test) {
            // print($test->max_no );
            if ($test->vatType  == 'A') {
                if ($test->max_no == '') {
                    $vatA = '1001';
                } else {
                    $vatA = $test->max_no + 1;
                }
            } elseif ($test->vatType  == 'B') {
                if ($test->max_no == '') {
                    $vatB = '1001';
                } else {
                    $vatB = $test->max_no + 1;
                }
            } elseif ($test->vatType  == 'C') {
                if ($test->max_no == '') {
                    $vatC = '1001';
                } else {
                    $vatC = $test->max_no + 1;
                }
            }
        }

        $lastRecord = Fabricout::latest()->first(); // get the last record of the table
        // print_r($lastRecord );
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

        return view('fabricout.create', compact('customers', 'order_id', 'customer_name', 'fabric_struct', 'orders', 'stockFabricStruct', 'vatA', 'vatB', 'vatC'));
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
        // Find No
        if ($request->filled('submit') && $request->submit == 'searchImport') {
            $select_search = '';
            $searchInput = '';

            // if ($request->filled('findNo') && $request->filled('Notype')) {
            //     $importFabricout = Fabricout::where('vatNo', 'LIKE', '%' . $request->findNo . '%')
            //         ->where('vatType', 'LIKE', '%' . $request->Notype . '%')
            //         ->groupBy('vatType', 'vatNo', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName', 'fabricPattern', 'fabricW')
            //         ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
            //         ->orderBy('lastDate', 'DESC')
            //         ->get();

            //     $select_search = 'findNo';
            //     $searchInput = $request->no;
            // } elseif ($request->filled('findNo') && $request->Notype === 'non') {
            //     $importFabricout = Fabricout::where('vatNo', 'LIKE', '%' . $request->findNo . '%')
            //         ->groupBy('vatType', 'vatNo', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName', 'fabricPattern', 'fabricW')
            //         ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
            //         ->orderBy('lastDate', 'DESC')
            //         ->get();

            //     $select_search = 'findNo';
            //     $searchInput = $request->no;
            // }
            // empty($request->Notype)

            if (empty($request->findNo) && $request->filled('Notype')) {
                $importFabricout = Fabricout::where('vatType', 'LIKE', '%' . $request->Notype . '%')
                    ->groupBy('vatType', 'vatNo', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName', 'fabricPattern', 'fabricW')
                    ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                    ->orderBy('lastDate', 'DESC')
                    ->get();
                $select_search = 'findNo';
                $searchInput = $request->Notype;
            } elseif ($request->filled('findNo') && $request->Notype === 'non') {
                $importFabricout = Fabricout::where('vatNo', 'LIKE', '%' . $request->findNo . '%')
                    ->groupBy('vatType', 'vatNo', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName', 'fabricPattern', 'fabricW')
                    ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                    ->orderBy('lastDate', 'DESC')
                    ->get();

                $select_search = 'findNo';
                $searchInput = $request->no;
            } elseif ($request->filled('findNo') && $request->filled('Notype')) {
                $importFabricout = Fabricout::where('vatNo', 'LIKE', '%' . $request->findNo . '%')
                    ->where('vatType', 'LIKE', '%' . $request->Notype . '%')
                    ->groupBy('vatType', 'vatNo', 'fabricStruct', 'no', 'refId', 'customerName', 'receiveName', 'fabricPattern', 'fabricW')
                    ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                    ->orderBy('lastDate', 'DESC')
                    ->get();

                $select_search = 'findNo';
                $searchInput = $request->no;
            }




            // print_r($importFabricout);
            return view('fabricout.index', compact('importFabricout', 'select_search', 'searchInput'));
        }
        // Find No
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


            //define vatNo
            $lastVat = Fabricout::groupBy('vatType')
                ->select('vatType', Fabricout::raw('MAX(vatNo) as max_no'))
                ->get();
            $vatA = '1001';
            $vatB = '1001';
            $vatC = '1001';
            foreach ($lastVat as $test) {
                // print($test->max_no );
                if ($test->vatType  == 'A') {
                    if ($test->max_no == '') {
                        $vatA = '1001';
                    } else {
                        $vatA = $test->max_no + 1;
                    }
                } elseif ($test->vatType  == 'B') {
                    if ($test->max_no == '') {
                        $vatB = '1001';
                    } else {
                        $vatB = $test->max_no + 1;
                    }
                } elseif ($test->vatType  == 'C') {
                    if ($test->max_no == '') {
                        $vatC = '1001';
                    } else {
                        $vatC = $test->max_no + 1;
                    }
                }
            }

            return view('fabricout.create', compact('customers', 'order_id', 'customer_name', 'fabric_struct', 'orders', 'stockFabricStruct', 'vatA', 'vatB', 'vatC'));
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

            session()->forget('customerReplace');
            session()->forget('fabricStructReplace');


            session()->forget('vatNo');
            session()->forget('vatType');

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
            // $date = $request->input('dt') ? $request->input('dt') : date('Y-m-d');

            $fixedDate = str_replace('/', '-', $request->input('dt') ? $request->input('dt') : date('Y-m-d'));
            // $validatedData['createDate'] = date('Y-m-d', strtotime($fixedDate));
            // $createDate = $request->input('createDate');
            $date = date('Y-m-d', strtotime($fixedDate));

            session()->put('dt', $date);

            session()->put('customerName', $request->input('customerName'));
            session()->put('receiveName', $request->input('receiveName'));
            session()->put('comment', $request->input('comment'));
            session()->put('receiveType', $request->input('receiveType'));
            session()->put('orderId', $request->input('orderId'));

            session()->put('fabricStruct',  $request->input('fabricStruct'));
            session()->put('fabricPattern',  $request->input('fabricPattern'));
            session()->put('fabricW',  $request->input('fabricW'));

            session()->put('customerReplace',  $request->input('customerReplace'));
            session()->put('fabricStructReplace',  $request->input('fabricStructReplace'));

            session()->put('vatNo',  $request->input('vatNo'));
            session()->put('vatType',  $request->input('vatType'));

            //check input fabric 
            if (count($arr_data) > 0) {
                //check key of fabric lot input
                if (session()->has('refId')) {
                    //get key from session to variable key
                    $key = session()->get('refId');
                    // $key = urlencode($key);
                    //save data
                    $this->saveFabricData(
                        $arr_data,
                        $key,
                        auth()->user()->name,
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        session()->get('customerReplace'),
                        session()->get('fabricStructReplace'),
                        session()->get('vatNo'),
                        session()->get('vatType'),
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
                    // $key = base64_encode(random_bytes(32)); // generates a 32-byte (256-bit) key
                    // $key = urlencode($key);
                    $bytes = random_bytes(32);
                    $base64 = base64_encode($bytes);
                    $key = str_replace('/', '', $base64);
                    // echo $key;
                    session()->put(['refId' => $key]);
                    // $key = urlencode($key);
                    //save data
                    $this->saveFabricData(
                        $arr_data,
                        $key,
                        auth()->user()->name,
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        session()->get('customerReplace'),
                        session()->get('fabricStructReplace'),
                        session()->get('vatNo'),
                        session()->get('vatType'),
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
            // print_r(session()->get('vatNo'));
            // print_r(session()->get('vatType'));
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

            session()->forget('customerReplace');
            session()->forget('fabricStructReplace');

            session()->forget('vatNo');
            session()->forget('vatType');

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
                $oldEnd  = $oldEnd   + 1;
            }

            session()->put('endCount',  $endCount);
            // $date = $request->input('dt') ? $request->input('dt') : date('Y-m-d');

            $fixedDate = str_replace('/', '-', $request->input('dt') ? $request->input('dt') : date('Y-m-d'));
            // $validatedData['createDate'] = date('Y-m-d', strtotime($fixedDate));
            // $createDate = $request->input('createDate');
            $date = date('Y-m-d', strtotime($fixedDate));

            session()->put('dt', $date);

            session()->put('customerName', $request->input('customerName'));
            session()->put('receiveName', $request->input('receiveName'));
            session()->put('comment', $request->input('comment'));
            session()->put('receiveType', $request->input('receiveType'));
            session()->put('orderId', $request->input('orderId'));

            session()->put('fabricStruct',  $request->input('fabricStruct'));
            session()->put('fabricPattern',  $request->input('fabricPattern'));
            session()->put('fabricW',  $request->input('fabricW'));

            session()->put('customerReplace',  $request->input('customerReplace'));
            session()->put('fabricStructReplace',  $request->input('fabricStructReplace'));

            session()->put('vatNo',  $request->input('vatNo'));
            session()->put('vatType',  $request->input('vatType'));

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
                        session()->get('customerReplace'),
                        session()->get('fabricStructReplace'),
                        session()->get('vatNo'),
                        session()->get('vatType'),
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
                        session()->get('fabricStruct'),
                        session()->get('fabricPattern'),
                        session()->get('fabricW'),
                        session()->get('customerReplace'),
                        session()->get('fabricStructReplace'),
                        session()->get('vatNo'),
                        session()->get('vatType'),
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
            session()->forget('customerReplace');
            session()->forget('fabricStructReplace');

            session()->forget('no');
            session()->forget('orderId');
            session()->forget('sum');


            // return $this->create();
            return redirect('/fabricout');
        }
        if ($request->filled('submit') && $request->submit == 'submitfabricout') {
            $fabricout_no = $request->fabricout_no;
            $records = fabricout::groupBy(['vatType', 'vatNo', 'comment', 'fabricStruct', 'fabricPattern', 'fabricW', 'no', 'refId', 'customerName', 'receiveName', 'customerReplace', 'fabricStructReplace'])
                ->selectRaw('vatType,vatNo,comment,customerReplace,fabricStructReplace,fabricStruct, fabricPattern , fabricW , receiveName,no, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
                ->where('no', $fabricout_no)
                ->get();

            // $vatNotype = fabricout::groupBy(['vatType', 'vatNo', 'no', 'refId'])
            //     ->selectRaw('vatType,vatNo,no')
            //     ->where('no', $fabricout_no)
            //     // ->where('another_column', $another_value)
            //     ->get();


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
            $count = $records[0]->foldCount / 160;



            $pageadd = 0;
            $countHeadPage = 1;
            $now = Carbon::now(new \DateTimeZone('Asia/Bangkok'));
            $day = $now->day;
            $month = $now->month;
            $year = $now->year;
            //example create pdf with thai font
            $this->fpdf = new Fpdf;
            // Add Thai font   
            for ($countPage = 0; $countPage < $count; $countPage++) {

                $this->fpdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
                $this->fpdf->AddFont('THSarabunNew', 'B', 'THSarabunNew_b.php');
                $this->fpdf->AddPage();
                $this->fpdf->SetFont('THSarabunNew', '', 14);
                $this->fpdf->Cell(10, 0, '', 0, 0);
                $pageall = ceil($count);
                $this->fpdf->Cell(60, 0, iconv('UTF-8', 'cp874', 'แผ่นที่ ' . $countHeadPage . ' จาก ทั้งหมด ' . $pageall . ' แผ่น'), 0, 0);
                $countHeadPage += 1;
                $this->fpdf->SetFont('THSarabunNew', 'B', 20);
                $this->fpdf->Cell(80, 0, iconv('UTF-8', 'cp874', 'ใบส่งสินค้า / Delivery Note'), 0, 0);
                // foreach ($records as $userrr) {
                $this->fpdf->Cell(60, 0, iconv('UTF-8', 'cp874', 'เลขที่ ' . $records[0]->vatType . ' - ' . $records[0]->vatNo), 0, 0);
                // }

                $this->fpdf->Cell(20, 15, '', 5, 1); //end of line
                $this->fpdf->SetFont('THSarabunNew', 'B', 16);
                // foreach ($records as $userrr) {
                // $this->fpdf->SetXY($x, $y);
                $this->fpdf->Cell(10, 5, '', 5, 0); //end of line
                if (isset($records[0]->customerReplace)) {
                    $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'ผู้สั่ง Order by : ' . $records[0]->customerReplace . ' '), 0, 0);
                } else {
                    $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'ผู้สั่ง Order by : ' . $records[0]->customerName . ' '), 0, 0);
                    // $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'ผู้สั่ง : ' . $records[0]->customerReplace . ' '), 0, 0);
                }


                $this->fpdf->Cell(60, 10, '', 0, 0);
                $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', 'ผู้รับ Received by ' . $records[0]->receiveName . ' '), 0, 0);
                $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
                $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
                $this->fpdf->Cell(10, 5, '', 5, 0); //end of line
                // $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'รหัสผ้า :' . $records[0]->fabricStruct . ' '), 0, 0);
                if (isset($records[0]->fabricStructReplace)) {
                    $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'รหัสผ้า Code : ' . $records[0]->fabricStructReplace . ' '), 0, 0);
                } else {
                    // เตรียมค่า fabricPattern
$fabricPattern = $records[0]->fabricPattern;

// 1) ตัดวงเล็บและข้อความข้างในออก เช่น "OXFORD (ใส่ลูกเบี้ยว 1/1)" -> "OXFORD"
$cleanPattern = preg_replace('/\(.*?\)/', '', $fabricPattern);
$cleanPattern = trim($cleanPattern);

// 2) ถ้ามีตัวเลขรูปแบบ n/m อยู่ในข้อความ (ที่ไม่ใช่วงเล็บแล้ว) ให้ใช้
if (preg_match('/(\d+)\s*\/\s*(\d+)/', $cleanPattern, $m)) {
    $patternDisplay = $m[1] . '/' . $m[2];
} else {
    $patternDisplay = $cleanPattern;
}

// 3) ใช้ใน Cell
$this->fpdf->Cell(
    60,
    5,
    iconv('UTF-8', 'cp874',
        'รหัสผ้า Code : ' 
        . $records[0]->fabricStruct . ' ' 
        . $records[0]->fabricW . "'' " 
        . $patternDisplay
    ),
    0,
    0
);

                    // $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'รหัสผ้า Code : ' . $records[0]->fabricStruct . ' ' . ' ' . $records[0]->fabricW . ' \'\' ' . (preg_match('/(\d+)\s*\/\s*(\d+)/', $records[0]->fabricPattern, $m) ? $m[1] . '/' . $m[2] : '') ), 0, 0);
                    // $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'รหัสผ้า : ' . $records[0]->fabricStructReplace . ' '), 0, 0);
                }
                $this->fpdf->Cell(60, 10, '', 0, 0);
                $date = date('d/m/Y', strtotime($records[0]->lastDate));
                $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', 'วันที่ Date : ' . $date . ' '), 0, 0);
                $this->fpdf->Cell(20, 10, '', 5, 1); //end of line

                // }

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

                    // ///////////////////////
                    $coltable = 0;
                    for ($b = 1; $b < 9; $b++) {
                        foreach ($orders as $key => $order) {
                            if ($i + $pageadd + $coltable < $a) {
                                if ($key === $i + $pageadd + $coltable) {
                                    $this->fpdf->SetFont('THSarabunNew', '', 12);
                                    $this->fpdf->SetXY($x + ($col_width - 6) * (3 * ($b - 1)), $y);
                                    $this->fpdf->Cell($col_width - 5, 8, iconv('UTF-8', 'cp874', ''), 1);
                                    // $this->fpdf->SetXY($x, $y);
                                    $rightMargin = 185;
                                    $textWidth = $this->fpdf->GetStringWidth($column1[$i] + $pageadd + $coltable);
                                    $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                                    $this->fpdf->SetXY($xPos + ($col_width - 6) * (3 * ($b - 1)), $y);
                                    $this->fpdf->Cell($col_width - 5, 8, iconv('UTF-8', 'cp874', $column1[$i] + $pageadd + $coltable), 0, 0);
                                    $this->fpdf->SetXY($x + ($col_width - 6) * ((3 * ($b - 1)) + 1.15), $y);
                                    $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                                    $this->fpdf->Cell($col_width, 8, ' ' .  $order->sumYard, 1);
                                    // $sum1 += $order->sumYard;
                                    $variableName = "sum{$b}";
                                    ${$variableName} += $order->sumYard;
                                    // $sum1 += $order->sumYard;
                                }
                            } else {
                                $this->fpdf->SetFont('THSarabunNew', '', 12);
                                $this->fpdf->SetXY($x + ($col_width - 6) * (3 * ($b - 1)), $y);
                                // $this->fpdf->Cell($col_width - 5, 8, iconv('UTF-8', 'cp874', $column1[$i]), 1);
                                $this->fpdf->Cell($col_width - 5, 8, iconv('UTF-8', 'cp874', ''), 1);
                                $this->fpdf->SetXY($x + ($col_width - 6) * ((3 * ($b - 1)) + 1.15), $y);
                                $this->fpdf->Cell($col_width, 8,  '', 1);
                            }
                        }
                        $coltable += 20;
                    }
                    // ///////////////////////

                    $y += 8;
                    if ($y > $this->fpdf->GetPageHeight() - 40) {
                        $this->fpdf->AddPage();
                        $y = 40;
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
                $this->fpdf->SetFont('THSarabunNew', 'B', 14);


                // write the text on top
                // foreach ($records as $userrr) {
                // $this->fpdf->setXY(50, 210);
                $col_width = 30;
                $row_height = 6;
                $this->fpdf->setXY(65, 220);
                $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', 'รวม'), 0, 0);
                $this->fpdf->SetFont('THSarabunNew', '', 14);
                $this->fpdf->setXY(65, 225);
                $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', 'Total'), 0, 0);
                $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                $this->fpdf->setXY(80, 220);
                $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', $records[0]->foldCount), 0, 0);
                $this->fpdf->setXY(90, 220);
                $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', 'พับ'), 0, 0);
                $this->fpdf->SetFont('THSarabunNew', '', 14);
                $this->fpdf->setXY(90, 225);
                $this->fpdf->Cell(0, $row_height, iconv('UTF-8', 'cp874', 'Pieces'), 0, 0);
                // $this->fpdf->setXY(130, 180);
                $rightMargin = 75;
                $textWidth = $this->fpdf->GetStringWidth($records[0]->sumYardSum);
                $xPos = $this->fpdf->GetPageWidth() - $rightMargin - $textWidth;
                $this->fpdf->SetFont('THSarabunNew', 'B', 18);
                $this->fpdf->SetXY($xPos, 220);
                $this->fpdf->Cell($textWidth, $row_height, iconv('UTF-8', 'cp874', $records[0]->sumYardSum), 0, 1, 'R');

                // $this->fpdf->setXY(130, 180);
                // $this->fpdf->Cell($col_width, $row_height, iconv('UTF-8', 'cp874',  $userrr->sumYardSum), 0, 1);
                $this->fpdf->setXY(140, 220);
                $this->fpdf->Cell($col_width, $row_height, iconv('UTF-8', 'cp874',  'หลา'), 0, 1);
                $this->fpdf->SetFont('THSarabunNew', '', 14);
                $this->fpdf->setXY(140, 225);
                $this->fpdf->Cell($col_width, $row_height, iconv('UTF-8', 'cp874',  'Yards'), 0, 1);
                // }

                // $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'รวม ......' . $userrr->foldCount . ' พับ'), 0, 0);
                // $this->fpdf->Cell(30, 10, '', 0, 0);
                // $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', '   .....  ' . $userrr->sumYardSum . '  หลา'), 0, 0);

                // $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
                // $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
                $this->fpdf->SetFont('THSarabunNew', 'B', 14);
                $this->fpdf->setXY(20, 225);
                $this->fpdf->Cell(40, 40, iconv('UTF-8', 'cp874', '      ตัวอย่างผ้า'), 1, 0);
                $this->fpdf->setXY(20, 235);
                $this->fpdf->Cell(40, 40, iconv('UTF-8', 'cp874', '      Sample'), 0, 0);
                $this->fpdf->setXY(65, 235);
                $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'ลงชื่อประทับตรา'), 0, 0);
                $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
                $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
                $this->fpdf->SetFont('THSarabunNew', '', 14);
                $this->fpdf->setXY(65, 240);
                $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'Authorize Signature_____________________________________________'), 0, 0);
                $this->fpdf->SetFont('THSarabunNew', 'B', 14);
                $this->fpdf->setXY(65, 255);
                $this->fpdf->Cell(60, 5, iconv('UTF-8', 'cp874', 'หมายเหตุ'), 0, 0);
                $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
                // $this->fpdf->Cell(20, 10, '', 5, 1); //end of line
                // foreach ($records as $userrr) {
                $this->fpdf->setXY(65, 260);
                $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', 'ได้รับผ้าตามรายการข้างบนนี้ไว้ถูกต้องและเรียบร้อยแล้ว'), 0, 1);
                $this->fpdf->SetFont('THSarabunNew', 'B', 12);
                $this->fpdf->setXY(65, 265);
                $this->fpdf->Cell(80, 5, iconv('UTF-8', 'cp874', 'Received the above goods in good order and condition' . $pageadd), 0, 1);
                // }
                // foreach ($orders as $key => $order) {
                //     if ($key === 1 || $key === 2 || $key === 5 || $key === 6) {
                //         $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', $order->fold . ' ' . $order->sumYard), 0, 0);
                //         $this->fpdf->Cell(20, 5, '', 0, 1); //end of line
                //     }
                //     // $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', $order->fold . ' ' . $order->sumYard), 0, 0);
                //     // $this->fpdf->Cell(20, 5, '', 0, 1); //end of line
                // }
                $pageadd += 160;
            }
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
    public function edit($refId)
    {
        //
        // $refId = $request->refId;
        $FabricOutEdit = fabricout::where('refId', $refId)
            // ->groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
            // ->selectRaw('refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
            ->selectRaw('refId,fabricStruct, createDate, fabricPattern, fabricW, fold, sumYard')
            // ->orderByDesc('id')
            ->orderBy('id', 'asc')
            ->get();


        $FabricOutEdit2 = fabricout::where('refId', $refId)
            ->groupBy(['no', 'receiveName', 'refId', 'fabricStruct', 'fabricPattern', 'fabricW', 'customerName', 'vatType', 'customerReplace', 'fabricStructReplace', 'vatNo'])
            ->selectRaw('no,receiveName,customerName,refId,fabricStruct, MAX(createDate) as lastCreateDate,
             fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum,vatType,customerReplace,fabricStructReplace,vatNo')
            // ->selectRaw('refId,fabricStruct, createDate, fabricPattern, fabricW, fold, sumYard')
            // ->orderByDesc('id')
            ->orderBy('id', 'asc')
            ->get();
        // print_r($FabricOutEdit);
        // print_r('///////////////');
        // print_r($FabricOutEdit2);

        $stockFabricStruct = stockfabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW',])
            ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as 
                lastDate')
            ->get();
        $customers = Customer::orderBy('name')->get();
        print(count($FabricOutEdit ) );

        // return view('fabricout.edit', compact('FabricOutEdit', 'FabricOutEdit2', 'stockFabricStruct', 'customers'));
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
        fabricout::where('refId', $id)->delete();
        return $this->index();
    }

    private function saveFabricData($data, $refId, $emp, $fabricStruct, $fabricPattern, $fabricW, $customerReplace, $fabricStructReplace, $vatNo, $vatType, $start, $createDate, $no, $customerName, $receiveName, $comment, $receiveType, $orderId)
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
                'customerReplace' => $customerReplace,
                'fabricStructReplace' => $fabricStructReplace,
                'vatNo' => $vatNo,
                'vatType' => $vatType,
                'fold' => $c,
                'sumYard' => $datasave,
                'createDate' => $createDate
            ]);

            $c = $c + 1;
        }
    }
}
