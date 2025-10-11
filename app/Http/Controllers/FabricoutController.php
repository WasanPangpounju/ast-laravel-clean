<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
// เบา: ดึงตัวเลือก customer/struct/pattern/width ตรง ๆ จาก stockfabrics
private function stockPickerOptions()
{
    $tbl = (new \App\Models\stockfabric())->getTable();

    return DB::table($tbl)
        ->select('customer','fabricStruct','fabricPattern','fabricW')
        ->whereNotNull('customer')->where('customer','<>','')
        ->whereNotNull('fabricStruct')->where('fabricStruct','<>','')
        ->whereNotNull('fabricPattern')->where('fabricPattern','<>','')
        ->whereNotNull('fabricW')->where('fabricW','<>','')
        // ถ้าตารางใหญ่มาก ใส่ช่วงเวลาให้แคบลง เช่น 12 เดือนล่าสุด
        //->where('createDate', '>=', now()->subMonths(12))
        ->groupBy('customer','fabricStruct','fabricPattern','fabricW')
        ->orderBy('customer')
        ->limit(2000) // กันหน้าระเบิด ถ้าอยากมากกว่านี้ค่อยขยับ
        ->get();
}

public function create()
{
    if ((int)session()->get('endCount', 0) <= 0) {
        session()->forget([
            'endCount','dt','customerName','receiveName','comment','receiveType','orderId',
            'fabricStruct','fabricPattern','fabricW','customerReplace','fabricStructReplace',
            'vatNo','vatType'
        ]);
    }

    $ecp = FabricAststructure::select('purchaseOrder AS id')
        ->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();

    $orders = AstPurchaseorder::select('id','customerName','fabricId','fabricStructure','orderSumYard','purchaseOrder')
        ->whereIn('id', $ecp)->orderBy('customerName')->get();

    // ใช้ DB::raw (ห้าม Fabricout::raw)
    $lastVat = Fabricout::groupBy('vatType')
        ->select('vatType', DB::raw('MAX(vatNo) as max_no'))
        ->get();

    $vatA='1001'; $vatB='1001'; $vatC='1001';
    foreach ($lastVat as $v) {
        if ($v->vatType==='A') $vatA = $v->max_no ? $v->max_no+1 : '1001';
        if ($v->vatType==='B') $vatB = $v->max_no ? $v->max_no+1 : '1001';
        if ($v->vatType==='C') $vatC = $v->max_no ? $v->max_no+1 : '1001';
    }

    // หาเลข no ล่าสุดแบบชัวร์
    $lastRecord = Fabricout::orderBy('no','DESC')->first();
    $no = $lastRecord ? ($lastRecord->no + 1) : 1001;
    if (!session()->has('no')) session()->put('no', $no);

    $customers = Customer::orderBy('name')->get();

    $order_id = '';
    $customer_name = '';
    $fabric_struct = '';

    // 👉 เปลี่ยนมาใช้ตัวเลือกแบบเบาเครื่อง
    $stockOptions = $this->stockPickerOptions();

    // (ถ้าต้องโชว์ “สต็อกที่เลือกปัจจุบัน” ใต้ select)
    $fg = session('fabricout_group', []);
    $selStockCustomer = $fg['stockCustomer']      ?? null;
    $selStockStruct   = $fg['stockFabricStruct']  ?? null;
    $selStockPattern  = $fg['stockFabricPattern'] ?? null;
    $selStockW        = $fg['stockFabricW']       ?? null;

    // 👉 เปลี่ยนมาใช้ตัวเลือกแบบเบาเครื่อง
$stockLots = $this->stockPickerOptions();
return view('fabricout.create', compact(
    'customers','order_id','customer_name','fabric_struct',
    'orders','vatA','vatB','vatC',
    // ส่งตัวแปรที่ชื่อเดียวกับที่ Blade ใช้
    'stockLots',
    'selStockCustomer','selStockStruct','selStockPattern','selStockW'
));

    // return view('fabricout.create', compact(
    //     'customers','order_id','customer_name','fabric_struct',
    //     'orders','vatA','vatB','vatC',
    //     // ส่งชุดตัวเลือกไปแทน stockLots
    //     'stockOptions',
    //     // ส่งของที่เลือกอยู่ไปโชว์ใต้ select
    //     'selStockCustomer','selStockStruct','selStockPattern','selStockW'
    // ));
}

public function create_backup1()
{
    if (session()->get('endCount') <= 0) {
        session()->forget([
            'endCount','dt','customerName','receiveName','comment','receiveType','orderId',
            'fabricStruct','fabricPattern','fabricW','customerReplace','fabricStructReplace',
            'vatNo','vatType'
        ]);
    }

    $ecp = FabricAststructure::select('purchaseOrder AS id')
        ->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();

    $orders = AstPurchaseorder::select('id','customerName','fabricId','fabricStructure','orderSumYard','purchaseOrder')
        ->whereIn('id', $ecp)->orderBy('customerName')->get();

    // เลขบิลแยกตามประเภท
    $lastVat = Fabricout::groupBy('vatType')
        ->select('vatType', Fabricout::raw('MAX(vatNo) as max_no'))->get();
    $vatA='1001'; $vatB='1001'; $vatC='1001';
    foreach ($lastVat as $v) {
        if ($v->vatType==='A') $vatA = $v->max_no ? $v->max_no+1 : '1001';
        if ($v->vatType==='B') $vatB = $v->max_no ? $v->max_no+1 : '1001';
        if ($v->vatType==='C') $vatC = $v->max_no ? $v->max_no+1 : '1001';
    }

    $lastRecord = Fabricout::latest()->first();
    $no = $lastRecord ? ($lastRecord->no + 1) : 1001;
    if (!session()->has('no')) session()->put('no', $no);

    $customers = Customer::orderBy('name')->get();

    $order_id = '';
    $customer_name = '';
    $fabric_struct = '';

    // ---------- สำคัญ: ดึง “สต็อกคงเหลือ” ต่อกุญแจ (customer+struct+pattern+width) ----------
    $ins = DB::table('stockfabrics')
        ->selectRaw("
            COALESCE(NULLIF(TRIM(customer), ''), 'AST') AS customer,
            fabricStruct, fabricPattern, fabricW,
            COUNT(fold) AS folds_in,
            SUM(sumYard) AS yards_in,
            MAX(createDate) AS lastDate
        ")
        ->groupBy('customer','fabricStruct','fabricPattern','fabricW')
        ->get();

    $outs = DB::table('fabricouts')
        ->selectRaw("
            COALESCE(NULLIF(TRIM(stockCustomer), ''), 'AST') AS customer,
            stockFabricStruct AS fabricStruct,
            stockFabricPattern AS fabricPattern,
            stockFabricW AS fabricW,
            COUNT(fold) AS folds_out,
            SUM(sumYard) AS yards_out
        ")
        ->whereNotNull('stockFabricStruct') // เฉพาะอันที่เลือกสต็อกจริง ๆ
        ->groupBy('customer','fabricStruct','fabricPattern','fabricW')
        ->get();

    // รวมยอดคงเหลือ
    $stockLots = $ins->map(function($in) use ($outs) {
        $out = $outs->first(function($o) use ($in){
            return $o->customer      === $in->customer
                && $o->fabricStruct  === $in->fabricStruct
                && $o->fabricPattern === $in->fabricPattern
                && $o->fabricW       === $in->fabricW;
        });

        $foldsOut = $out->folds_out ?? 0;
        $yardsOut = $out->yards_out ?? 0;

        return (object)[
            'customer'        => $in->customer,
            'fabricStruct'    => $in->fabricStruct,
            'fabricPattern'   => $in->fabricPattern,
            'fabricW'         => $in->fabricW,
            'foldsIn'         => (int)$in->folds_in,
            'yardsIn'         => (float)$in->yards_in,
            'foldsOut'        => (int)$foldsOut,
            'yardsOut'        => (float)$yardsOut,
            'foldsRemaining'  => max(0, (int)$in->folds_in - (int)$foldsOut),
            'yardsRemaining'  => max(0, (float)$in->yards_in - (float)$yardsOut),
            'lastDate'        => $in->lastDate,
        ];
    })
    // เอาเฉพาะรายการที่ยังมีคงเหลือ
    ->filter(fn($r) => ($r->foldsRemaining > 0) || ($r->yardsRemaining > 0))
    // จัดเรียงให้เลือกง่าย: มากไปน้อยตาม yardsRemaining
    ->sortByDesc('yardsRemaining')
    ->values();

    return view('fabricout.create', compact(
        'customers','order_id','customer_name','fabric_struct',
        'orders','vatA','vatB','vatC','stockLots'
    ));
}

    
    public function create_back()
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

        /* ---------- 0) สั่งใบส่ง (PDF) ---------- */
// 0) สั่งใบส่ง (PDF)
if ($request->filled('submit') && $request->submit === 'submitfabricout') {
    $refId = $request->input('ref_id');
    if ($refId) {
        return $this->printDeliveryPdfByRef($refId);
    }
    // fallback เผื่อเคสเก่า
    $fabricout_no = (int) $request->input('fabricout_no');
    if ($fabricout_no) {
        return $this->printDeliveryPdf($fabricout_no);
    }
    return back()->with('error', 'ไม่พบ refId หรือ No สำหรับพิมพ์ใบส่ง');
}

/* ---------- 1) ค้นหา ---------- */
if ($request->filled('submit') && $request->submit === 'searchImport') {
    $select_search = 'findNo';
    $searchInput   = $request->findNo ?? $request->Notype ?? '';

    $base = Fabricout::query();

    if (empty($request->findNo) && $request->filled('Notype')) {
        $base->where('vatType','LIKE','%'.$request->Notype.'%');
    } elseif ($request->filled('findNo') && $request->Notype === 'non') {
        $base->where('vatNo','LIKE','%'.$request->findNo.'%');
    } else {
        $base->where('vatNo','LIKE','%'.$request->findNo.'%')
             ->where('vatType','LIKE','%'.$request->Notype.'%');
    }

    $importFabricout = $base
        ->select([
            'refId', // << สำคัญ: ต้องมี
            DB::raw('MIN(no) as no'),
            DB::raw('MIN(vatType) as vatType'),
            DB::raw('MIN(vatNo) as vatNo'),
            DB::raw('MIN(customerName) as customerName'),
            DB::raw('MIN(receiveName) as receiveName'),
            DB::raw('MIN(fabricStruct) as fabricStruct'),
            DB::raw('MIN(fabricPattern) as fabricPattern'),
            DB::raw('MIN(fabricW) as fabricW'),
            DB::raw('COUNT(fold) as foldCount'),
            DB::raw('SUM(COALESCE(sumYard,0)) as sumYardSum'),
            DB::raw('MAX(createDate) as lastDate'),
        ])
        ->groupBy('refId')          // << จบที่ refId เพื่อให้ 1 แถว = 1 ใบ (ชุดเดียวกัน)
        ->orderBy('lastDate','DESC')
        ->get();

    return view('fabricout.index', compact('importFabricout','select_search','searchInput'));
}


    // if ($request->filled('submit') && $request->submit === 'submitfabricout') {
    //     $fabricout_no = (int) $request->input('fabricout_no');
    //     if (!$fabricout_no) {
    //         return back()->with('error', 'ไม่พบเลขบิล');
    //     }
    //     return $this->printDeliveryPdf($fabricout_no); // <<<<< สำคัญ: return ตรงนี้เลย
    // }


    /* ---------- 1) ค้นหา ---------- */
    // if ($request->filled('submit') && $request->submit === 'searchImport') {
    //     $select_search = 'findNo';
    //     $searchInput   = $request->findNo ?? $request->Notype ?? '';

    //     if (empty($request->findNo) && $request->filled('Notype')) {
    //         $importFabricout = Fabricout::where('vatType','LIKE','%'.$request->Notype.'%')
    //             ->groupBy('vatType','vatNo','fabricStruct','no','refId','customerName','receiveName','fabricPattern','fabricW')
    //             ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no,customerName,COUNT(fold) as foldCount,SUM(sumYard) as sumYardSum,MAX(createDate) as lastDate')
    //             ->orderBy('lastDate','DESC')->get();
    //     } elseif ($request->filled('findNo') && $request->Notype === 'non') {
    //         $importFabricout = Fabricout::where('vatNo','LIKE','%'.$request->findNo.'%')
    //             ->groupBy('vatType','vatNo','fabricStruct','no','refId','customerName','receiveName','fabricPattern','fabricW')
    //             ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no,customerName,COUNT(fold) as foldCount,SUM(sumYard) as sumYardSum,MAX(createDate) as lastDate')
    //             ->orderBy('lastDate','DESC')->get();
    //     } else {
    //         $importFabricout = Fabricout::where('vatNo','LIKE','%'.$request->findNo.'%')
    //             ->where('vatType','LIKE','%'.$request->Notype.'%')
    //             ->groupBy('vatType','vatNo','fabricStruct','no','refId','customerName','receiveName','fabricPattern','fabricW')
    //             ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no,customerName,COUNT(fold) as foldCount,SUM(sumYard) as sumYardSum,MAX(createDate) as lastDate')
    //             ->orderBy('lastDate','DESC')->get();
    //     }
    //     return view('fabricout.index', compact('importFabricout','select_search','searchInput'));
    // }

    /* ---------- 2) generateByOrder ---------- */
    if ($request->filled('submit') && $request->submit === 'generateByOrder') {
        $customers  = Customer::orderBy('name')->get();

        $lastRecord = Fabricout::latest()->first();
        $no         = $lastRecord ? ($lastRecord->no + 1) : 1001;
        if (!session()->has('no')) session()->put('no', $no);

        $order_id = $request->input('orderId');
        session()->put('orderId', $order_id);

        $order_send  = AstPurchaseorder::select('customerName','fabricId','fabricStructure','fabricPattern')
                        ->where('id', $order_id)->get();
        $order_sendW = FabricAst::select('fabric_w')->where('purchaseOrder', $order_id)->get();

        session()->put('customerName',  $order_send[0]->customerName   ?? '');
        session()->put('fabricStruct',  $order_send[0]->fabricStructure ?? '');
        session()->put('fabricPattern', $order_send[0]->fabricPattern   ?? '');
        session()->put('fabricW',       $order_sendW[0]->fabric_w       ?? '');

        $po = AstPurchaseorder::where('id', $order_id)->value('purchaseOrder');
        session()->put('purchaseOrder', $po ?? '');

        session()->forget('fabricout_group');

        $lastVat = Fabricout::groupBy('vatType')
            ->select('vatType', Fabricout::raw('MAX(vatNo) as max_no'))->get();
        $vatA = '1001'; $vatB = '1001'; $vatC = '1001';
        foreach ($lastVat as $v) {
            if ($v->vatType === 'A') $vatA = $v->max_no ? $v->max_no + 1 : '1001';
            if ($v->vatType === 'B') $vatB = $v->max_no ? $v->max_no + 1 : '1001';
            if ($v->vatType === 'C') $vatC = $v->max_no ? $v->max_no + 1 : '1001';
        }

        $ecp    = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2','อนุมัติให้ผลิต')->get();
        $orders = AstPurchaseorder::select('id','customerName','fabricId','fabricStructure','orderSumYard','purchaseOrder')
                    ->whereIn('id',$ecp)->orderBy('customerName')->get();

        $customer_name     = session('customerName','');
        $fabric_struct     = '';
        $stockFabricStruct = stockfabric::groupBy(['fabricStruct','fabricPattern','fabricW'])
            ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
            ->get();
// xx
    // 👉 เปลี่ยนมาใช้ตัวเลือกแบบเบาเครื่อง
$stockLots = $this->stockPickerOptions();

        return view('fabricout.create', compact(
            'customers','order_id','customer_name','fabric_struct',
            'orders','stockFabricStruct','vatA','vatB','vatC',
            'stockLots'
        ));
    }

    /* ---------- 3) เตรียมค่า & Snapshot group key ---------- */
    $fixedDate = str_replace('/', '-', $request->input('dt') ?: date('Y-m-d'));
    $date      = date('Y-m-d', strtotime($fixedDate));

    $val = fn(string $key) => $request->filled($key) ? trim((string)$request->input($key)) : session($key);

    if (!session()->has('fabricout_group')) {
        $snapshot = [
            'vatNo'         => $val('vatNo'),
            'vatType'       => $val('vatType'),
            'fabricStruct'  => $val('fabricStruct'),
            'fabricPattern' => $val('fabricPattern'),
            'fabricW'       => $val('fabricW'),
            'customerName'  => $val('customerName'),
            'receiveName'   => $val('receiveName'),
            'no'            => session('no'),
            'orderId'       => session('orderId'),
            'purchaseOrder' => session('purchaseOrder') ?? $val('purchaseOrder'),
        ];
        // ✅ เพิ่มกุญแจตัดสต็อก โดย "รวม" เข้ากับ snapshot เดิม (ไม่ทับค่าเดิม)
        $snapshot = array_merge($snapshot, [
            'stockCustomer'      => $val('stockCustomer')      ?: ($val('customerName') ?: 'AST'),
            'stockFabricStruct'  => $val('stockFabricStruct')  ?: $val('fabricStruct'),
            'stockFabricPattern' => $val('stockFabricPattern') ?: $val('fabricPattern'),
            'stockFabricW'       => $val('stockFabricW')       ?: $val('fabricW'),
        ]);

        session()->put('fabricout_group', $snapshot);
    }
    $G = session('fabricout_group');

    $comment             = $val('comment');
    $receiveType         = $val('receiveType');
    $orderId             = $G['orderId']       ?? $val('orderId');
    $purchaseOrder       = $G['purchaseOrder'] ?? $val('purchaseOrder');
    $customerReplace     = $val('customerReplace');
    $fabricStructReplace = $val('fabricStructReplace');

    // ข้อมูลพับรอบนี้
    $arr_data = [];
    foreach ($request->input('sumYard', []) as $v) {
        if ($v !== '' && $v !== null) $arr_data[] = $v;
    }

    // ฟังก์ชันบันทึก 1 ก้อน
    $saveChunk = function(array $arr) use ($G, $date, $comment, $receiveType, $orderId, $purchaseOrder, $customerReplace, $fabricStructReplace) {
        if (empty($arr)) return;

        $oldEnd    = session('endCount') ?? 0;
        $startFold = ($oldEnd > 0) ? ($oldEnd + 1) : 1;

        // คง/สร้าง refId
        if (session()->has('refId')) {
            $refId = session('refId');
        } else {
            $refId = str_replace('/', '', base64_encode(random_bytes(32)));
            session()->put('refId', $refId);
        }

        // ✅ คีย์ตัดสต็อกจาก snapshot (มีค่า fallback)
        $stockCustomer      = $G['stockCustomer']      ?? 'AST';
        $stockFabricStruct  = $G['stockFabricStruct']  ?? $G['fabricStruct'];
        $stockFabricPattern = $G['stockFabricPattern'] ?? $G['fabricPattern'];
        $stockFabricW       = $G['stockFabricW']       ?? $G['fabricW'];

        DB::transaction(function() use ($arr, $refId, $G, $date, $comment, $receiveType, $orderId, $purchaseOrder, $customerReplace, $fabricStructReplace, $startFold, $stockCustomer, $stockFabricStruct, $stockFabricPattern, $stockFabricW) {
            $this->saveFabricData(
                $arr,
                $refId,
                auth()->user()->name,
                $G['fabricStruct'],
                $G['fabricPattern'],
                $G['fabricW'],
                $customerReplace,
                $fabricStructReplace,
                $G['vatNo'],
                $G['vatType'],
                $startFold,
                $date,
                $G['no'],
                $G['customerName'],
                $G['receiveName'],
                $comment,
                $receiveType,
                $orderId,
                $purchaseOrder,
                // 🔐 ส่งกุญแจตัดสต็อก 4 ตัว
                $stockCustomer, $stockFabricStruct, $stockFabricPattern, $stockFabricW
            );
        });

        session()->put('endCount', $oldEnd + count($arr));
    };

    /* ---------- 4) nextData ---------- */
    if ($request->filled('submit') && $request->submit === 'nextData') {
        if (!empty($arr_data)) {
            $total = (float)(session('sum') ?? 0) + array_sum(array_map('floatval', $arr_data));
            session()->put('sum', $total);
        }

        $saveChunk($arr_data);

        session()->put([
            'dt'                  => $date,
            'comment'             => $comment,
            'receiveType'         => $receiveType,
            'customerReplace'     => $customerReplace,
            'fabricStructReplace' => $fabricStructReplace,
        ]);

        return $this->create();
    }

    /* ---------- 5) endData ---------- */
    if ($request->filled('submit') && $request->submit === 'endData') {
        $saveChunk($arr_data);

        session()->forget([
            'refId','endCount','dt','fabricStruct','fabricPattern','fabricW',
            'customerReplace','fabricStructReplace','no','orderId','sum',
            'customerName','receiveName','comment','receiveType','vatNo','vatType',
            'purchaseOrder','fabricout_group'
        ]);

        return redirect('/fabricout');
    }

    /* ---------- 6) submitfabricout ---------- */
    if ($request->filled('submit') && $request->submit === 'submitfabricout') {
        // … (โค้ดพิมพ์ PDF เดิมของคุณ)
    }

    return back()->with('error','คำสั่งไม่ถูกต้อง');

    
}


private function printDeliveryPdf(int $fabricout_no)
{
    // 1) ดึงหัวบิล + ยอดรวม
    $records = fabricout::where('no', $fabricout_no)
        ->groupBy([
            'vatType','vatNo','fabricStruct','fabricPattern','fabricW',
            'no','refId','customerName','receiveName','customerReplace',
            'fabricStructReplace','comment'
        ])
        ->selectRaw("
            vatType, vatNo, fabricStruct, fabricPattern, fabricW,
            no, refId, customerName, receiveName, customerReplace,
            fabricStructReplace, comment,
            COUNT(fold) AS foldCount,
            SUM(sumYard) AS sumYardSum,
            MAX(createDate) AS lastDate
        ")
        ->get();

    // 2) ดึงไลน์พับ
    $orders = fabricout::selectRaw('no, fold, sumYard')
        ->where('no', $fabricout_no)
        ->orderBy('fold','asc')
        ->get();

    if ($records->isEmpty()) {
        return back()->with('error', 'ไม่พบข้อมูลใบส่งของ');
    }

    $head     = $records[0];
    $vatType  = $head->vatType;
    $vatNo    = $head->vatNo;
    $pageCount = max(1, (int)ceil(($head->foldCount ?? 0) / 160));

    // 3) สร้าง PDF
    $this->fpdf = new Fpdf;
    $this->fpdf->AddFont('THSarabunNew','', 'THSarabunNew.php');
    $this->fpdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');

    $pageOffset = 0; // ชดเชย index รายการต่อหน้า (หน้าๆ ละ 160 บรรทัด: 20 แถว x 8 คอลัมน์)

    for ($page = 1; $page <= $pageCount; $page++) {

        $this->fpdf->AddPage();

        // หัวกระดาษ
        $this->fpdf->SetFont('THSarabunNew','',14);
        $this->fpdf->Cell(10, 0, '', 0, 0);
        $this->fpdf->Cell(60, 0, iconv('UTF-8','cp874', 'แผ่นที่ '.$page.' จาก ทั้งหมด '.$pageCount.' แผ่น'), 0, 0);

        $this->fpdf->SetFont('THSarabunNew','B',20);
        $this->fpdf->Cell(80, 0, iconv('UTF-8','cp874','ใบส่งสินค้า / Delivery Note'), 0, 0);

        $this->fpdf->Cell(60, 0, iconv('UTF-8','cp874','เลขที่ '.$vatType.' - '.$vatNo), 0, 0);
        $this->fpdf->Ln(15);

        $this->fpdf->SetFont('THSarabunNew','B',16);
        $this->fpdf->Cell(10, 5, '', 0, 0);

        // ผู้สั่ง (ใช้ customerReplace ถ้ามี ไม่งั้นใช้ customerName)
        $orderBy = $head->customerReplace ?: $head->customerName;
        $this->fpdf->Cell(60, 5, iconv('UTF-8','cp874','ผู้สั่ง Order by : '.$orderBy), 0, 0);

        $this->fpdf->Cell(60, 5, '', 0, 0);
        $this->fpdf->Cell(80, 5, iconv('UTF-8','cp874','ผู้รับ Received by '.$head->receiveName), 0, 1);

        $this->fpdf->Ln(5);
        $this->fpdf->Cell(10, 5, '', 0, 0);

        // เตรียม pattern สำหรับหัวบิล (ตัดวงเล็บออก และถ้ามีรูปแบบ n/m ให้ดึงออกมา)
        $fabricPattern   = (string)$head->fabricPattern;
        $cleanPattern    = preg_replace('/\(.*?\)/', '', $fabricPattern);
        $cleanPattern    = trim($cleanPattern);
        if (preg_match('/(\d+)\s*\/\s*(\d+)/', $cleanPattern, $m)) {
            $patternDisplay = $m[1].'/'.$m[2];
        } else {
            $patternDisplay = $cleanPattern;
        }

        $codeLine = 'รหัสผ้า Code : '.$head->fabricStruct.' '.$head->fabricW."'' ".$patternDisplay;
        if (!empty($head->fabricStructReplace)) {
            $codeLine = 'รหัสผ้า Code : '.$head->fabricStructReplace;
        }
        $this->fpdf->Cell(60, 5, iconv('UTF-8','cp874', $codeLine), 0, 0);

        $this->fpdf->Cell(60, 5, '', 0, 0);
        $this->fpdf->Cell(80, 5, iconv('UTF-8','cp874','วันที่ Date : '.date('d/m/Y', strtotime($head->lastDate))), 0, 1);

        $this->fpdf->Ln(2);

        // ตาราง 8 คอลัมน์ (ลำดับ / หลา) x 20 แถว
        $this->fpdf->SetFont('THSarabunNew','',12);

        $colWidth  = $this->fpdf->GetPageWidth() / 16;
        $xStart    = $this->fpdf->GetX() + 10;
        $yStart    = $this->fpdf->GetY();

        // หัวคอลัมน์
        for ($i = 0; $i < 8; $i++) {
            $this->fpdf->SetXY($xStart + ($colWidth - 6) * (3 * $i), $yStart - 5);
            $this->fpdf->Cell($colWidth - 5, 5, iconv('UTF-8','cp874','ลำดับ'), 1);
            $this->fpdf->SetXY($xStart + ($colWidth - 6) * ((3 * $i) + 1.15), $yStart - 5);
            $this->fpdf->Cell($colWidth, 5, iconv('UTF-8','cp874','   หลา'), 1);
        }

        $y = $yStart;

        // รวมแต่ละคอลัมน์
        $sum = array_fill(1, 8, 0.0);

        // วาด 20 แถว
        for ($row = 0; $row < 20; $row++) {
            for ($col = 1; $col <= 8; $col++) {
                // index รายการจาก $orders ที่จะวางในช่องนี้
                $idx = $pageOffset + ($col - 1) * 20 + $row;

                // กล่องลำดับ
                $this->fpdf->SetXY($xStart + ($colWidth - 6) * (3 * ($col - 1)), $y);
                $this->fpdf->Cell($colWidth - 5, 8, iconv('UTF-8','cp874',''), 1);

                // ลำดับ (ขวาชิด)
                $rightMargin = 185;
                $seq         = $idx + 1;
                $tw          = $this->fpdf->GetStringWidth((string)$seq);
                $xPos        = $this->fpdf->GetPageWidth() - $rightMargin - $tw;
                $this->fpdf->SetXY($xPos + ($colWidth - 6) * (3 * ($col - 1)), $y);
                $this->fpdf->Cell($colWidth - 5, 8, iconv('UTF-8','cp874',(string)$seq), 0, 0);

                // กล่องหลา
                $this->fpdf->SetXY($xStart + ($colWidth - 6) * ((3 * ($col - 1)) + 1.15), $y);

                if (isset($orders[$idx])) {
                    $this->fpdf->SetFont('THSarabunNew','B',18);
                    $this->fpdf->Cell($colWidth, 8, ' '.$orders[$idx]->sumYard, 1);
                    $sum[$col] += (float)$orders[$idx]->sumYard;
                    $this->fpdf->SetFont('THSarabunNew','',12);
                } else {
                    $this->fpdf->Cell($colWidth, 8, '', 1);
                }
            }

            $y += 8;
            if ($y > $this->fpdf->GetPageHeight() - 40) {
                // เผื่อไว้ (กรณี overflow)
                $this->fpdf->AddPage();
                $y = 40;
            }
        }

        // แถว "รวม" ของแต่ละคอลัมน์
        $this->fpdf->SetFont('THSarabunNew','',12);
        for ($col = 1; $col <= 8; $col++) {
            $xCol = $xStart + ($colWidth - 6) * (3 * ($col - 1));
            $this->fpdf->SetXY($xCol, $y);
            $this->fpdf->Cell($colWidth - 5, 5, iconv('UTF-8','cp874','รวม'), 1);
            $this->fpdf->SetXY($xCol + ($colWidth - 5), $y);
            $this->fpdf->Cell($colWidth, 5, iconv('UTF-8','cp874', (string)$sum[$col]), 1);
        }

        // กล่องสรุปรวมด้านล่าง
        $this->fpdf->SetFont('THSarabunNew','B',14);
        $rowH = 6;

        // รวมพับ
        $this->fpdf->SetXY(65, 220);
        $this->fpdf->Cell(0, $rowH, iconv('UTF-8','cp874','รวม'), 0, 0);
        $this->fpdf->SetFont('THSarabunNew','',14);
        $this->fpdf->SetXY(65, 225);
        $this->fpdf->Cell(0, $rowH, iconv('UTF-8','cp874','Total'), 0, 0);
        $this->fpdf->SetFont('THSarabunNew','B',18);
        $this->fpdf->SetXY(80, 220);
        $this->fpdf->Cell(0, $rowH, iconv('UTF-8','cp874',(string)$head->foldCount), 0, 0);
        $this->fpdf->SetXY(90, 220);
        $this->fpdf->Cell(0, $rowH, iconv('UTF-8','cp874','พับ'), 0, 0);
        $this->fpdf->SetFont('THSarabunNew','',14);
        $this->fpdf->SetXY(90, 225);
        $this->fpdf->Cell(0, $rowH, iconv('UTF-8','cp874','Pieces'), 0, 0);

        // รวมหลา (ขวาชิด)
        $rightMargin = 75;
        $twTotal     = $this->fpdf->GetStringWidth((string)$head->sumYardSum);
        $xPos        = $this->fpdf->GetPageWidth() - $rightMargin - $twTotal;
        $this->fpdf->SetFont('THSarabunNew','B',18);
        $this->fpdf->SetXY($xPos, 220);
        $this->fpdf->Cell($twTotal, $rowH, iconv('UTF-8','cp874',(string)$head->sumYardSum), 0, 1, 'R');

        $this->fpdf->SetXY(140, 220);
        $this->fpdf->Cell(30, $rowH, iconv('UTF-8','cp874','หลา'), 0, 1);
        $this->fpdf->SetFont('THSarabunNew','',14);
        $this->fpdf->SetXY(140, 225);
        $this->fpdf->Cell(30, $rowH, iconv('UTF-8','cp874','Yards'), 0, 1);

        // ช่องตัวอย่าง/ลายเซ็น/หมายเหตุ
        $this->fpdf->SetFont('THSarabunNew','B',14);
        $this->fpdf->SetXY(20, 225);
        $this->fpdf->Cell(40, 40, iconv('UTF-8','cp874','      ตัวอย่างผ้า'), 1, 0);
        $this->fpdf->SetXY(20, 235);
        $this->fpdf->Cell(40, 40, iconv('UTF-8','cp874','      Sample'), 0, 0);

        $this->fpdf->SetXY(65, 235);
        $this->fpdf->Cell(60, 5, iconv('UTF-8','cp874','ลงชื่อประทับตรา'), 0, 0);
        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('THSarabunNew','',14);
        $this->fpdf->SetXY(65, 240);
        $this->fpdf->Cell(60, 5, iconv('UTF-8','cp874','Authorize Signature_____________________________________________'), 0, 0);

        $this->fpdf->SetFont('THSarabunNew','B',14);
        $this->fpdf->SetXY(65, 255);
        $this->fpdf->Cell(60, 5, iconv('UTF-8','cp874','หมายเหตุ'), 0, 0);

        $this->fpdf->SetFont('THSarabunNew','',12);
        $this->fpdf->SetXY(65, 260);
        $note = $head->comment ?: 'ได้รับผ้าตามรายการข้างบนนี้ไว้ถูกต้องและเรียบร้อยแล้ว';
        $this->fpdf->Cell(80, 5, iconv('UTF-8','cp874',$note), 0, 1);
        $this->fpdf->SetXY(65, 265);
        $this->fpdf->Cell(80, 5, iconv('UTF-8','cp874','Received the above goods in good order and condition'), 0, 1);

        // ชดเชย offset สำหรับหน้าไปต่อไป (20 แถว x 8 คอลัมน์)
        $pageOffset += 160;
    }

    // 4) ส่งออกเป็นสตรีม
    $pdfBinary = $this->fpdf->Output('S'); // S = เป็นสตริง
    $filename  = "Delivery_{$vatType}-{$vatNo}.pdf";

    return response($pdfBinary, 200, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$filename.'"',
        'Cache-Control'       => 'private, max-age=0, must-revalidate',
        'Pragma'              => 'public',
    ]);
}


private function saveFabricDataBack2(
    array $data,
    string $refId,
    string $emp,
    ?string $fabricStruct,
    ?string $fabricPattern,
    ?string $fabricW,
    ?string $customerReplace,
    ?string $fabricStructReplace,
    ?string $vatNo,
    ?string $vatType,
    int $start,
    string $createDate,
    $no,
    ?string $customerName,
    ?string $receiveName,
    ?string $comment,
    ?string $receiveType,
    $orderId = null,
    $purchaseOrder = null,
    // ✅ พารามิเตอร์ใหม่ (optional เพื่อให้โค้ดเก่าทำงานต่อได้)
    ?string $stockCustomer = null,
    ?string $stockFabricStruct = null,
    ?string $stockFabricPattern = null,
    ?string $stockFabricW = null
) {
    $c = $start;
    foreach ($data as $yd) {
        Fabricout::create([
            'refId'         => $refId,
            'emp'           => $emp,

            // อ้างอิงออร์เดอร์
            'orderId'       => $orderId,
            'purchaseOrder' => $purchaseOrder,

            // 🔐 กุญแจตัดสต็อก (ใช้ fallback ถ้าไม่ได้ส่งมา)
            'stockCustomer'      => $stockCustomer      ?: ($customerName ?: 'AST'),
            'stockFabricStruct'  => $stockFabricStruct  ?: $fabricStruct,
            'stockFabricPattern' => $stockFabricPattern ?: $fabricPattern,
            'stockFabricW'       => $stockFabricW       ?: $fabricW,

            // ข้อมูลใช้งาน/แสดงผล
            'no'                  => $no,
            'customerName'        => $customerName,
            'receiveName'         => $receiveName,
            'receiveType'         => $receiveType,
            'comment'             => $comment,
            'fabricStruct'        => $fabricStruct,
            'fabricPattern'       => $fabricPattern,
            'fabricW'             => $fabricW,
            'customerReplace'     => $customerReplace,
            'fabricStructReplace' => $fabricStructReplace,
            'vatNo'               => $vatNo,
            'vatType'             => $vatType,

            'fold'       => $c,
            'sumYard'    => $yd,
            'createDate' => $createDate,
        ]);
        $c++;
    }
}

private function saveFabricData(
    array $data,
    string $refId,
    string $emp,
    ?string $fabricStruct,
    ?string $fabricPattern,
    ?string $fabricW,
    ?string $customerReplace,
    ?string $fabricStructReplace,
    ?string $vatNo,
    ?string $vatType,
    int $start,
    string $createDate,
    $no,
    ?string $customerName,
    ?string $receiveName,
    ?string $comment,
    ?string $receiveType,
    $orderId = null,
    $purchaseOrder = null,
    // ✅ พารามิเตอร์ใหม่ (optional เพื่อให้โค้ดเก่าทำงานต่อได้)
    ?string $stockCustomer = null,
    ?string $stockFabricStruct = null,
    ?string $stockFabricPattern = null,
    ?string $stockFabricW = null
) {
    $c = $start;
    foreach ($data as $yd) {
        Fabricout::create([
            'refId'         => $refId,
            'emp'           => $emp,

            // อ้างอิงออร์เดอร์
            'orderId'       => $orderId,
            'purchaseOrder' => $purchaseOrder,

            // 🔐 กุญแจตัดสต็อก (ใช้ fallback ถ้าไม่ได้ส่งมา)
            'stockCustomer'      => $stockCustomer      ?: ($customerName ?: 'AST'),
            'stockFabricStruct'  => $stockFabricStruct  ?: $fabricStruct,
            'stockFabricPattern' => $stockFabricPattern ?: $fabricPattern,
            'stockFabricW'       => $stockFabricW       ?: $fabricW,

            // ข้อมูลใช้งาน/แสดงผล
            'no'                  => $no,
            'customerName'        => $customerName,
            'receiveName'         => $receiveName,
            'receiveType'         => $receiveType,
            'comment'             => $comment,
            'fabricStruct'        => $fabricStruct,
            'fabricPattern'       => $fabricPattern,
            'fabricW'             => $fabricW,
            'customerReplace'     => $customerReplace,
            'fabricStructReplace' => $fabricStructReplace,
            'vatNo'               => $vatNo,
            'vatType'             => $vatType,

            'fold'       => $c,
            'sumYard'    => $yd,
            'createDate' => $createDate,
        ]);
        $c++;
    }
}

// ==========

    public function storeback1(Request $request)
{
    /* ---------- 1) ค้นหา ---------- */
    if ($request->filled('submit') && $request->submit === 'searchImport') {
        $select_search = 'findNo';
        $searchInput   = $request->findNo ?? $request->Notype ?? '';

        if (empty($request->findNo) && $request->filled('Notype')) {
            $importFabricout = Fabricout::where('vatType','LIKE','%'.$request->Notype.'%')
                ->groupBy('vatType','vatNo','fabricStruct','no','refId','customerName','receiveName','fabricPattern','fabricW')
                ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no,customerName,COUNT(fold) as foldCount,SUM(sumYard) as sumYardSum,MAX(createDate) as lastDate')
                ->orderBy('lastDate','DESC')->get();
        } elseif ($request->filled('findNo') && $request->Notype === 'non') {
            $importFabricout = Fabricout::where('vatNo','LIKE','%'.$request->findNo.'%')
                ->groupBy('vatType','vatNo','fabricStruct','no','refId','customerName','receiveName','fabricPattern','fabricW')
                ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no,customerName,COUNT(fold) as foldCount,SUM(sumYard) as sumYardSum,MAX(createDate) as lastDate')
                ->orderBy('lastDate','DESC')->get();
        } else {
            $importFabricout = Fabricout::where('vatNo','LIKE','%'.$request->findNo.'%')
                ->where('vatType','LIKE','%'.$request->Notype.'%')
                ->groupBy('vatType','vatNo','fabricStruct','no','refId','customerName','receiveName','fabricPattern','fabricW')
                ->selectRaw('vatType,vatNo,fabricStruct,fabricPattern,fabricW,receiveName,no,customerName,COUNT(fold) as foldCount,SUM(sumYard) as sumYardSum,MAX(createDate) as lastDate')
                ->orderBy('lastDate','DESC')->get();
        }
        return view('fabricout.index', compact('importFabricout','select_search','searchInput'));
    }

    /* ---------- 2) generateByOrder ---------- */
    if ($request->filled('submit') && $request->submit === 'generateByOrder') {
        $customers  = Customer::orderBy('name')->get();

        $lastRecord = Fabricout::latest()->first();
        $no         = $lastRecord ? ($lastRecord->no + 1) : 1001;
        if (!session()->has('no')) session()->put('no', $no);

        $order_id = $request->input('orderId');
        session()->put('orderId', $order_id);

        $order_send  = AstPurchaseorder::select('customerName','fabricId','fabricStructure','fabricPattern')
                        ->where('id', $order_id)->get();
        $order_sendW = FabricAst::select('fabric_w')->where('purchaseOrder', $order_id)->get();

        // เก็บค่าพื้นฐานลง session เพื่อโชว์ในฟอร์ม
        session()->put('customerName',  $order_send[0]->customerName  ?? '');
        session()->put('fabricStruct',  $order_send[0]->fabricStructure ?? '');
        session()->put('fabricPattern', $order_send[0]->fabricPattern ?? '');
        session()->put('fabricW',       $order_sendW[0]->fabric_w     ?? '');

        // ดึง purchaseOrder (SO) จากตารางใบสั่งซื้อ แล้วเก็บลง session
        $po = AstPurchaseorder::where('id', $order_id)->value('purchaseOrder');
        session()->put('purchaseOrder', $po ?? '');

        // เริ่มใบใหม่ เคลียร์ snapshot เดิม
        session()->forget('fabricout_group');

        // เตรียมเลขบิลต่อประเภท
        $lastVat = Fabricout::groupBy('vatType')
            ->select('vatType', Fabricout::raw('MAX(vatNo) as max_no'))->get();
        $vatA = '1001'; $vatB = '1001'; $vatC = '1001';
        foreach ($lastVat as $v) {
            if ($v->vatType === 'A') $vatA = $v->max_no ? $v->max_no + 1 : '1001';
            if ($v->vatType === 'B') $vatB = $v->max_no ? $v->max_no + 1 : '1001';
            if ($v->vatType === 'C') $vatC = $v->max_no ? $v->max_no + 1 : '1001';
        }

        $ecp    = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2','อนุมัติให้ผลิต')->get();
        $orders = AstPurchaseorder::select('id','customerName','fabricId','fabricStructure','orderSumYard','purchaseOrder')
                    ->whereIn('id',$ecp)->orderBy('customerName')->get();

        $customer_name     = session('customerName','');
        $fabric_struct     = '';
        $stockFabricStruct = stockfabric::groupBy(['fabricStruct','fabricPattern','fabricW'])
            ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
            ->get();

        // return view('fabricout.create', compact(
        //     'customers','order_id','customer_name','fabric_struct',
        //     'orders','stockFabricStruct','vatA','vatB','vatC'
        // ));
    }

    /* ---------- 3) เตรียมค่า & Snapshot group key ---------- */
    $fixedDate = str_replace('/', '-', $request->input('dt') ?: date('Y-m-d'));
    $date      = date('Y-m-d', strtotime($fixedDate));

    // helper: ถ้ามีค่าและไม่ว่างใน request ใช้ค่านั้น ไม่งั้น fallback session
    $val = fn(string $key) => $request->filled($key) ? trim((string)$request->input($key)) : session($key);

    // สร้าง snapshot ของคีย์กรุ๊ปครั้งแรก แล้วคงไว้จนจบการบันทึก (กันแตกบิล)
    if (!session()->has('fabricout_group')) {
        $snapshot = [
            'vatNo'         => $val('vatNo'),
            'vatType'       => $val('vatType'),
            'fabricStruct'  => $val('fabricStruct'),
            'fabricPattern' => $val('fabricPattern'),
            'fabricW'       => $val('fabricW'),
            'customerName'  => $val('customerName'),
            'receiveName'   => $val('receiveName'),
            'no'            => session('no'),
            'orderId'       => session('orderId'),
            'purchaseOrder' => session('purchaseOrder') ?? $val('purchaseOrder'),
        ];

        $snapshot = [
  // ...คีย์เดิมของคุณ
  'stockCustomer'      => $val('stockCustomer')      ?: ($val('customerName') ?: 'AST'),
  'stockFabricStruct'  => $val('stockFabricStruct')  ?: $val('fabricStruct'),
  'stockFabricPattern' => $val('stockFabricPattern') ?: $val('fabricPattern'),
  'stockFabricW'       => $val('stockFabricW')       ?: $val('fabricW'),
];
// session()->put('fabricout_group', $snapshot);
        session()->put('fabricout_group', $snapshot);
    }
    $G = session('fabricout_group');

    // ฟิลด์อื่น ๆ
    $comment             = $val('comment');
    $receiveType         = $val('receiveType');
    $orderId             = $G['orderId']       ?? $val('orderId');
    $purchaseOrder       = $G['purchaseOrder'] ?? $val('purchaseOrder');
    $customerReplace     = $val('customerReplace');
    $fabricStructReplace = $val('fabricStructReplace');

    // ข้อมูลพับรอบนี้
    $arr_data = [];
    foreach ($request->input('sumYard', []) as $v) {
        if ($v !== '' && $v !== null) $arr_data[] = $v;
    }

    // ฟังก์ชันบันทึก 1 ก้อน: ต่อ fold จาก endCount และคง refId เดิม
    $saveChunk = function(array $arr) use ($G, $date, $comment, $receiveType, $orderId, $purchaseOrder, $customerReplace, $fabricStructReplace) {
        if (empty($arr)) return;

        $oldEnd    = session('endCount') ?? 0;
        $startFold = ($oldEnd > 0) ? ($oldEnd + 1) : 1;

        // คง/สร้าง refId
        if (session()->has('refId')) {
            $refId = session('refId');
        } else {
            $refId = str_replace('/', '', base64_encode(random_bytes(32)));
            session()->put('refId', $refId);
        }

        DB::transaction(function() use ($arr, $refId, $G, $date, $comment, $receiveType, $orderId, $purchaseOrder, $customerReplace, $fabricStructReplace, $startFold) {
            $this->saveFabricData(
                $arr,
                $refId,
                auth()->user()->name,
                $G['fabricStruct'],
                $G['fabricPattern'],
                $G['fabricW'],
                $customerReplace,
                $fabricStructReplace,
                $G['vatNo'],
                $G['vatType'],
                $startFold,
                $date,
                $G['no'],
                $G['customerName'],
                $G['receiveName'],
                $comment,
                $receiveType,
                $orderId,
                $purchaseOrder
            );
        });

        session()->put('endCount', $oldEnd + count($arr));
    };

    /* ---------- 4) nextData: บันทึกก้อนนี้แล้วกลับไปกรอกต่อ ---------- */
    if ($request->filled('submit') && $request->submit === 'nextData') {
        if (!empty($arr_data)) {
            $total = (float)(session('sum') ?? 0) + array_sum(array_map('floatval', $arr_data));
            session()->put('sum', $total);
        }

        $saveChunk($arr_data);

        // เก็บ context ที่ไม่ใช่ group key ให้ฟอร์มรอบถัดไป
        session()->put([
            'dt'                  => $date,
            'comment'             => $comment,
            'receiveType'         => $receiveType,
            'customerReplace'     => $customerReplace,
            'fabricStructReplace' => $fabricStructReplace,
            // orderId/purchaseOrder อยู่ใน snapshot แล้ว
        ]);

        return $this->create();
    }

    /* ---------- 5) endData: บันทึกก้อนสุดท้าย แล้วล้าง context ---------- */
    if ($request->filled('submit') && $request->submit === 'endData') {
        $saveChunk($arr_data);

        session()->forget([
            'refId','endCount','dt','fabricStruct','fabricPattern','fabricW',
            'customerReplace','fabricStructReplace','no','orderId','sum',
            'customerName','receiveName','comment','receiveType','vatNo','vatType',
            'purchaseOrder','fabricout_group'
        ]);

        return redirect('/fabricout');
    }

    /* ---------- 6) submitfabricout (ถ้ามี) ---------- */
    if ($request->filled('submit') && $request->submit === 'submitfabricout') {
        // … คงโค้ดเดิมสำหรับพิมพ์ PDF …
    }

    return back()->with('error','คำสั่งไม่ถูกต้อง');
}

private function saveFabricDataBack1(
    array $data,
    string $refId,
    string $emp,
    ?string $fabricStruct,
    ?string $fabricPattern,
    ?string $fabricW,
    ?string $customerReplace,
    ?string $fabricStructReplace,
    ?string $vatNo,
    ?string $vatType,
    int $start,
    string $createDate,
    $no,
    ?string $customerName,
    ?string $receiveName,
    ?string $comment,
    ?string $receiveType,
    $orderId = null,
    $purchaseOrder = null   // << เพิ่มตัวนี้
) {
    $c = $start;
    foreach ($data as $datasave) {
        Fabricout::create([
            'refId'               => $refId,
            'emp'                 => $emp,
            'orderId'             => $orderId,
            'purchaseOrder'       => $purchaseOrder, // << บันทึก SO ทุกแถว
            'no'                  => $no,
            'customerName'        => $customerName,
            'receiveName'         => $receiveName,
            'receiveType'         => $receiveType,
            'comment'             => $comment,
            'fabricStruct'        => $fabricStruct,
            'fabricPattern'       => $fabricPattern,
            'fabricW'             => $fabricW,
            'customerReplace'     => $customerReplace,
            'fabricStructReplace' => $fabricStructReplace,
            'vatNo'               => $vatNo,
            'vatType'             => $vatType,
            'fold'                => $c,
            'sumYard'             => $datasave,
            'createDate'          => $createDate,
        ]);
        $c++;
    }
}

    //โค้ดเก่า
    public function storeback(Request $request)
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

        //ดึงข้อมูล Order จากเลข SO 
//         if ($request->filled('submit') && $request->filled('purchaseOrder')) {
//             $customers = Customer::orderBy('name')->get();
//             // session()->put('no', 1001);
//             $lastRecord = Fabricout::latest()->first(); // get the last record of the table
//             $no = $lastRecord->no; // get the value of the "no" field from the last record
//             $no = $no + 1;

//             if (!session()->has('no')) {
//                 session()->put('no', $no);
//             }

//             $order_id = $request->input('orderId');
//             session()->put('orderId', $order_id);

//             $customer_name = $request->input('customerName');
//             $fabric_struct = $request->input('fabricStruct');
//             $order_send = AstPurchaseorder::select('customerName', 'fabricId', 'fabricStructure', 'fabricPattern')
//                 ->where('id', $order_id)
//                 ->get();

//             $order_sendW = FabricAst::select('fabric_w')
//                 ->where('purchaseOrder', $order_id)
//                 ->get();

//             $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();

//             $orders = AstPurchaseorder::select('id', 'customerName', 'fabricId', 'fabricStructure', 'orderSumYard', 'purchaseOrder')
//                 ->whereIn('id', $ecp)
//                 ->orderBy('customerName')
//                 ->get();
//             // var_dump($order_sendW);
//             session()->forget('fabricStruct');
//             session()->forget('fabricPattern');
//             session()->forget('fabricW');
//             session()->forget('purchaseOrder');

//             session()->put('fabricStruct',  $order_send[0]->fabricStructure);
//             session()->put('fabricPattern', $order_send[0]->fabricPattern);
//             session()->put('fabricW', $order_sendW[0]->fabric_w);
//             session()->put('purchaseOrder', $request->purchaseOrder);

//             $stockFabricStruct = stockfabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW',])
//                 ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as 
//                 lastDate')
//                 ->get();


//             //define vatNo
//             $lastVat = Fabricout::groupBy('vatType')
//                 ->select('vatType', Fabricout::raw('MAX(vatNo) as max_no'))
//                 ->get();
//             $vatA = '1001';
//             $vatB = '1001';
//             $vatC = '1001';
//             foreach ($lastVat as $test) {
//                 // print($test->max_no );
//                 if ($test->vatType  == 'A') {
//                     if ($test->max_no == '') {
//                         $vatA = '1001';
//                     } else {
//                         $vatA = $test->max_no + 1;
//                     }
//                 } elseif ($test->vatType  == 'B') {
//                     if ($test->max_no == '') {
//                         $vatB = '1001';
//                     } else {
//                         $vatB = $test->max_no + 1;
//                     }
//                 } elseif ($test->vatType  == 'C') {
//                     if ($test->max_no == '') {
//                         $vatC = '1001';
//                     } else {
//                         $vatC = $test->max_no + 1;
//                     }
//                 }
//             }
// // print_r($request->purchaseOrder . ' ');
// // print(count($orders) );
//             return view('fabricout.create', compact('customers', 'order_id', 'customer_name', 'fabric_struct', 'orders', 'stockFabricStruct', 'vatA', 'vatB', 'vatC'));
//         }

        
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
// print_r($request->purchaseOrder);
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

    private function saveFabricData_back($data, $refId, $emp, $fabricStruct, $fabricPattern, $fabricW, $customerReplace, $fabricStructReplace, $vatNo, $vatType, $start, $createDate, $no, $customerName, $receiveName, $comment, $receiveType, $orderId)
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

private function printDeliveryPdfByRef(string $refId)
{
    // 1) Head ใบส่ง (1 แถวต่อ refId)
    $head = Fabricout::where('refId', $refId)
        ->selectRaw("
            refId,
            MIN(vatType) as vatType,
            MIN(vatNo) as vatNo,
            MIN(fabricStruct) as fabricStruct,
            MIN(fabricPattern) as fabricPattern,
            MIN(fabricW) as fabricW,
            MIN(no) as no,
            MIN(customerName) as customerName,
            MIN(receiveName) as receiveName,
            MIN(customerReplace) as customerReplace,
            MIN(fabricStructReplace) as fabricStructReplace,
            MIN(comment) as comment,
            COUNT(*) as foldCount,
            SUM(sumYard) as sumYardSum,
            MAX(createDate) as lastDate
        ")
        ->groupBy('refId')
        ->first();

    if (!$head) {
        return back()->with('error', 'ไม่พบข้อมูลใบส่งของสำหรับ refId ที่ระบุ');
    }

    // 2) รายการพับ (เรียงตามลำดับ)
    $orders = Fabricout::select('no','fold','sumYard')
        ->where('refId', $refId)
        ->orderBy('fold','asc')
        ->get();

    // 3) จำนวนหน้า (20 แถว x 8 คอลัมน์ = 160 รายการ/หน้า)
    $pageCount = max(1, (int)ceil(($head->foldCount ?? 0) / 160));

    // 4) พิมพ์ PDF (reuse โค้ดจาก printDeliveryPdf เดิมได้เลย)
    $this->fpdf = new Fpdf;
    $this->fpdf->AddFont('THSarabunNew','', 'THSarabunNew.php');
    $this->fpdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');

    $pageOffset = 0;

    for ($page = 1; $page <= $pageCount; $page++) {
        $this->fpdf->AddPage();

        // ==== หัวกระดาษ ====
        $this->fpdf->SetFont('THSarabunNew','',14);
        $this->fpdf->Cell(10, 0, '', 0, 0);
        $this->fpdf->Cell(60, 0, iconv('UTF-8','cp874', 'แผ่นที่ '.$page.' จาก ทั้งหมด '.$pageCount.' แผ่น'), 0, 0);

        $this->fpdf->SetFont('THSarabunNew','B',20);
        $this->fpdf->Cell(80, 0, iconv('UTF-8','cp874','ใบส่งสินค้า / Delivery Note'), 0, 0);
        $this->fpdf->Cell(60, 0, iconv('UTF-8','cp874','เลขที่ '.$head->vatType.' - '.$head->vatNo), 0, 0);
        $this->fpdf->Ln(15);

        $this->fpdf->SetFont('THSarabunNew','B',16);
        $this->fpdf->Cell(10, 5, '', 0, 0);
        $orderBy = $head->customerReplace ?: $head->customerName;
        $this->fpdf->Cell(60, 5, iconv('UTF-8','cp874','ผู้สั่ง Order by : '.$orderBy), 0, 0);
        $this->fpdf->Cell(60, 5, '', 0, 0);
        $this->fpdf->Cell(80, 5, iconv('UTF-8','cp874','ผู้รับ Received by '.$head->receiveName), 0, 1);

        $this->fpdf->Ln(5);
        $this->fpdf->Cell(10, 5, '', 0, 0);

        // เตรียม pattern ที่สะอาด
        $fabricPattern = (string)$head->fabricPattern;
        $cleanPattern  = trim(preg_replace('/\(.*?\)/', '', $fabricPattern));
        if (preg_match('/(\d+)\s*\/\s*(\d+)/', $cleanPattern, $m)) {
            $patternDisplay = $m[1].'/'.$m[2];
        } else {
            $patternDisplay = $cleanPattern;
        }
        $codeLine = 'รหัสผ้า Code : '.$head->fabricStruct.' '.$head->fabricW."'' ".$patternDisplay;
        if (!empty($head->fabricStructReplace)) {
            $codeLine = 'รหัสผ้า Code : '.$head->fabricStructReplace;
        }
        $this->fpdf->Cell(60, 5, iconv('UTF-8','cp874', $codeLine), 0, 0);
        $this->fpdf->Cell(60, 5, '', 0, 0);
        $this->fpdf->Cell(80, 5, iconv('UTF-8','cp874','วันที่ Date : '.date('d/m/Y', strtotime($head->lastDate))), 0, 1);

        // ==== ตาราง 8 คอลัมน์ x 20 แถว ====
        $this->fpdf->SetFont('THSarabunNew','',12);
        $colWidth  = $this->fpdf->GetPageWidth() / 16;
        $xStart    = $this->fpdf->GetX() + 10;
        $yStart    = $this->fpdf->GetY();

        // หัวคอลัมน์
        for ($i = 0; $i < 8; $i++) {
            $this->fpdf->SetXY($xStart + ($colWidth - 6) * (3 * $i), $yStart - 5);
            $this->fpdf->Cell($colWidth - 5, 5, iconv('UTF-8','cp874','ลำดับ'), 1);
            $this->fpdf->SetXY($xStart + ($colWidth - 6) * ((3 * $i) + 1.15), $yStart - 5);
            $this->fpdf->Cell($colWidth, 5, iconv('UTF-8','cp874','   หลา'), 1);
        }

        $y = $yStart;
        $sum = array_fill(1, 8, 0.0);

        // วาด 20 แถว x 8 คอลัมน์
        for ($row = 0; $row < 20; $row++) {
            for ($col = 1; $col <= 8; $col++) {
                $idx = $pageOffset + ($col - 1) * 20 + $row;

                // กล่องลำดับ
                $this->fpdf->SetXY($xStart + ($colWidth - 6) * (3 * ($col - 1)), $y);
                $this->fpdf->Cell($colWidth - 5, 8, '', 1);

                // ลำดับ (ขวาชิด)
                $rightMargin = 185;
                $seq         = $idx + 1;
                $tw          = $this->fpdf->GetStringWidth((string)$seq);
                $xPos        = $this->fpdf->GetPageWidth() - $rightMargin - $tw;
                $this->fpdf->SetXY($xPos + ($colWidth - 6) * (3 * ($col - 1)), $y);
                $this->fpdf->Cell($colWidth - 5, 8, iconv('UTF-8','cp874',(string)$seq), 0, 0);

                // กล่องหลา
                $this->fpdf->SetXY($xStart + ($colWidth - 6) * ((3 * ($col - 1)) + 1.15), $y);

                if (isset($orders[$idx])) {
                    $this->fpdf->SetFont('THSarabunNew','B',18);
                    $this->fpdf->Cell($colWidth, 8, ' '.$orders[$idx]->sumYard, 1);
                    $sum[$col] += (float)$orders[$idx]->sumYard;
                    $this->fpdf->SetFont('THSarabunNew','',12);
                } else {
                    $this->fpdf->Cell($colWidth, 8, '', 1);
                }
            }

            $y += 8;
            if ($y > $this->fpdf->GetPageHeight() - 40) {
                $this->fpdf->AddPage();
                $y = 40;
            }
        }

        // แถวรวม
        for ($col = 1; $col <= 8; $col++) {
            $xCol = $xStart + ($colWidth - 6) * (3 * ($col - 1));
            $this->fpdf->SetXY($xCol, $y);
            $this->fpdf->Cell($colWidth - 5, 5, iconv('UTF-8','cp874','รวม'), 1);
            $this->fpdf->SetXY($xCol + ($colWidth - 5), $y);
            $this->fpdf->Cell($colWidth, 5, iconv('UTF-8','cp874', (string)$sum[$col]), 1);
        }

        // กล่องสรุปด้านล่าง
        $this->fpdf->SetFont('THSarabunNew','B',14);
        $rowH = 6;
        $this->fpdf->SetXY(65, 220);
        $this->fpdf->Cell(0, $rowH, iconv('UTF-8','cp874','รวม'), 0, 0);
        $this->fpdf->SetFont('THSarabunNew','',14);
        $this->fpdf->SetXY(65, 225);
        $this->fpdf->Cell(0, $rowH, iconv('UTF-8','cp874','Total'), 0, 0);
        $this->fpdf->SetFont('THSarabunNew','B',18);
        $this->fpdf->SetXY(80, 220);
        $this->fpdf->Cell(0, $rowH, iconv('UTF-8','cp874',(string)$head->foldCount), 0, 0);
        $this->fpdf->SetXY(90, 220);
        $this->fpdf->Cell(0, $rowH, iconv('UTF-8','cp874','พับ'), 0, 0);
        $this->fpdf->SetFont('THSarabunNew','',14);
        $this->fpdf->SetXY(90, 225);
        $this->fpdf->Cell(0, $rowH, iconv('UTF-8','cp874','Pieces'), 0, 0);

        $rightMargin = 75;
        $twTotal     = $this->fpdf->GetStringWidth((string)$head->sumYardSum);
        $xPos        = $this->fpdf->GetPageWidth() - $rightMargin - $twTotal;
        $this->fpdf->SetFont('THSarabunNew','B',18);
        $this->fpdf->SetXY($xPos, 220);
        $this->fpdf->Cell($twTotal, $rowH, iconv('UTF-8','cp874',(string)$head->sumYardSum), 0, 1, 'R');

        $this->fpdf->SetXY(140, 220);
        $this->fpdf->Cell(30, $rowH, iconv('UTF-8','cp874','หลา'), 0, 1);
        $this->fpdf->SetFont('THSarabunNew','',14);
        $this->fpdf->SetXY(140, 225);
        $this->fpdf->Cell(30, $rowH, iconv('UTF-8','cp874','Yards'), 0, 1);

        $this->fpdf->SetFont('THSarabunNew','B',14);
        $this->fpdf->SetXY(20, 225);
        $this->fpdf->Cell(40, 40, iconv('UTF-8','cp874','      ตัวอย่างผ้า'), 1, 0);
        $this->fpdf->SetXY(20, 235);
        $this->fpdf->Cell(40, 40, iconv('UTF-8','cp874','      Sample'), 0, 0);

        $this->fpdf->SetXY(65, 235);
        $this->fpdf->Cell(60, 5, iconv('UTF-8','cp874','ลงชื่อประทับตรา'), 0, 0);
        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('THSarabunNew','',14);
        $this->fpdf->SetXY(65, 240);
        $this->fpdf->Cell(60, 5, iconv('UTF-8','cp874','Authorize Signature_____________________________________________'), 0, 0);

        $this->fpdf->SetFont('THSarabunNew','B',14);
        $this->fpdf->SetXY(65, 255);
        $this->fpdf->Cell(60, 5, iconv('UTF-8','cp874','หมายเหตุ'), 0, 0);
        $this->fpdf->SetFont('THSarabunNew','',12);
        $this->fpdf->SetXY(65, 260);
        $note = $head->comment ?: 'ได้รับผ้าตามรายการข้างบนนี้ไว้ถูกต้องและเรียบร้อยแล้ว';
        $this->fpdf->Cell(80, 5, iconv('UTF-8','cp874',$note), 0, 1);
        $this->fpdf->SetXY(65, 265);
        $this->fpdf->Cell(80, 5, iconv('UTF-8','cp874','Received the above goods in good order and condition'), 0, 1);

        $pageOffset += 160;
    }

    $pdfBinary = $this->fpdf->Output('S');
    $filename  = "Delivery_{$head->vatType}-{$head->vatNo}.pdf";
    return response($pdfBinary, 200, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$filename.'"',
        'Cache-Control'       => 'private, max-age=0, must-revalidate',
        'Pragma'              => 'public',
    ]);
}


}
