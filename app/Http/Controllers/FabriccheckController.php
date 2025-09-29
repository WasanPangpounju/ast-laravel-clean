<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\fabricout;
use App\Models\stockfabric;
use App\Models\customer;

class FabriccheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $records = StockFabric::groupBy(['refId','fabricStruct', 'fabricPattern', 'fabricW','createDate'])
        //     ->selectRaw('refId,fabricStruct,createDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum ')
        //     ->orderBy('createDate')
        //     ->get();
        //     // ->count();
        // // var_dump($records );
        // $allfabricout = $records;

        $records = StockFabric::groupBy(['fabricId','refId', 'fabricStruct', 'fabricPattern', 'fabricW','customer'])
            ->selectRaw('fabricId,customer,refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
            ->orderByDesc('lastCreateDate')
            ->get();

        // $records = StockFabric::groupBy(['refId'])
        // ->select('refId')
        // // ->orderByDesc('lastCreateDate')
        // ->get();

        $allfabricout = $records;
        //     $records = stockfabric::select('id', 'refId', 'emp', 'fabricStruct', 'fabricW', 'sumYard','createDate','fabricPattern')
        //         ->orderBy('createDate')
        //         ->get();
        // // ->count();
        // // var_dump($records );
        // $allfabricout = $records;
        // print_r($sumfabricout);
        // $orders = fabricout::selectRaw('no,fold , sumYard')
        // ->where('no', 1016)
        // ->get();
        // print_r($allfabricout);
        $stockFabricStruct = stockfabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW',])
            ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as 
        lastDate')
            ->get();

        return view('fabricoutcheck.index', compact('allfabricout', 'stockFabricStruct'));
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
    // กัน timeout ชั่วคราว (แต่ควรแก้ที่คิวรี/ดัชนีเป็นหลัก)
    @set_time_limit(0);
    @ini_set('max_execution_time', '0');

    if (!($request->filled('submit') && $request->submit === 'searchImport')) {
        return redirect()->route('fabriccheck.index');
    }

    // รับค่าจากปุ่ม "ตรวจสอบ" (ส่งมาจากหน้าสต็อก)
    $customer      = (string) $request->input('customer', '');
    $fabricId      = (string) $request->input('fabricId', '');      // ← แนะนำให้ส่งมาด้วย
    $fabricStruct  = (string) $request->input('fabricStruct', '');
    $fabricPattern = (string) $request->input('fabricPattern', '');
    $fabricW       = (string) $request->input('fabricW', '');

    // หากไม่มีเงื่อนไขเลย ไม่ให้สแกนทั้งตาราง
    if ($customer === '' && $fabricId === '' && $fabricStruct === '' && $fabricPattern === '' && $fabricW === '') {
        return redirect()
            ->route('fabriccheck.index')
            ->with('warn', 'กรุณาเลือกเงื่อนไขอย่างน้อย 1 อย่าง');
    }

    /**
     * ฟังก์ชันช่วย: สร้างคิวรีแบบ "ตรงคอลัมน์" (ใช้ index ได้)
     * แล้ว wrap เป็นซับคิวรีเพื่อ group/aggregate โดยอิง alias (เลี่ยง ONLY_FULL_GROUP_BY)
     */
    $buildExactAgg = function() use ($customer,$fabricId,$fabricStruct,$fabricPattern,$fabricW) {
        $pre = DB::table('stockfabrics as s');

        // กรณีลูกค้าว่าง = ใช้ AST: ตรงคอลัมน์เพื่อให้ index ทำงาน
        if ($customer !== '') {
            if (strtoupper($customer) === 'AST') {
                $pre->where(function ($q) {
                    $q->whereNull('s.customer')
                      ->orWhere('s.customer', '')
                      ->orWhere('s.customer', 'AST');
                });
            } else {
                $pre->where('s.customer', $customer);
            }
        }
        if ($fabricId      !== '') $pre->where('s.fabricId', $fabricId);
        if ($fabricStruct  !== '') $pre->where('s.fabricStruct', $fabricStruct);
        if ($fabricPattern !== '') $pre->where('s.fabricPattern', $fabricPattern);
        if ($fabricW       !== '') $pre->where('s.fabricW', $fabricW);

        // เลือกคอลัมน์พร้อม normalize เบา ๆ ในชั้นซับคิวรีเท่านั้น
        $base = $pre->selectRaw("
            COALESCE(NULLIF(TRIM(s.customer), ''), 'AST') AS customer,
            s.fabricId                                    AS fabricId,
            TRIM(s.fabricStruct)                          AS fabricStruct,
            TRIM(s.fabricPattern)                         AS fabricPattern,
            TRIM(s.fabricW)                               AS fabricW,
            s.createDate                                  AS createDate,
            s.sumYard                                     AS sumYard,
            s.refId                                       AS refId
        ");

        return DB::query()->fromSub($base, 't')
            ->selectRaw("
                customer, fabricId, fabricStruct, fabricPattern, fabricW,
                MAX(createDate) AS lastCreateDate,
                COUNT(*)        AS foldCount,
                SUM(sumYard)    AS sumYardSum,
                MIN(refId)      AS refId
            ")
            ->groupBy('customer','fabricId','fabricStruct','fabricPattern','fabricW')
            ->orderByDesc('lastCreateDate')
            ->limit(500); // กันผลลัพธ์ล้น
    };

    /**
     * คิวรีรอบแรก: ตรงคอลัมน์ (เร็วสุด)
     */
    $importorder = $buildExactAgg()->get();

    /**
     * ถ้าไม่เจอผลลัพธ์เลย → fallback แบบ normalize เพื่อกันเคส space/ตัวคูณ x/× ฯลฯ
     * (ทำเฉพาะเมื่อจำเป็น เพื่อลดโอกาสช้า/timeout)
     */
    if ($importorder->isEmpty()) {

        $norm = function ($col) {
            // normalize: trim, lower, ตัดช่องว่าง, แทน '×' เป็น 'x'
            // หมายเหตุ: ใช้เฉพาะใน fallback เท่านั้น
            return DB::raw("REPLACE(REPLACE(LOWER(TRIM($col)), ' ', ''), '×', 'x')");
        };

        $pre = DB::table('stockfabrics as s');

        if ($customer !== '') {
            if (strtoupper($customer) === 'AST') {
                $pre->where(function ($q) {
                    $q->whereNull('s.customer')
                      ->orWhere('s.customer', '')
                      ->orWhere('s.customer', 'AST');
                });
            } else {
                // ตรงคอลัมน์ (customer ไม่ค่อยมีปัญหาเรื่อง normalize)
                $pre->where('s.customer', $customer);
            }
        }

        if ($fabricId !== '') {
            $pre->where('s.fabricId', $fabricId);
        }

        if ($fabricStruct !== '') {
            // normalize เฉพาะฝั่งที่รับเข้ามา
            $needle = mb_strtolower(trim(str_replace([' ', '×'], ['', 'x'], $fabricStruct)));
            $pre->where($norm('s.fabricStruct'), '=', $needle);
        }
        if ($fabricPattern !== '') {
            $needle = mb_strtolower(trim(str_replace([' ', '×'], ['', 'x'], $fabricPattern)));
            $pre->where($norm('s.fabricPattern'), '=', $needle);
        }
        if ($fabricW !== '') {
            $needle = mb_strtolower(trim(str_replace(' ', '', $fabricW)));
            $pre->where($norm('s.fabricW'), '=', $needle);
        }

        $base = $pre->selectRaw("
            COALESCE(NULLIF(TRIM(s.customer), ''), 'AST') AS customer,
            s.fabricId                                    AS fabricId,
            TRIM(s.fabricStruct)                          AS fabricStruct,
            TRIM(s.fabricPattern)                         AS fabricPattern,
            TRIM(s.fabricW)                               AS fabricW,
            s.createDate                                  AS createDate,
            s.sumYard                                     AS sumYard,
            s.refId                                       AS refId
        ");

        $importorder = DB::query()->fromSub($base, 't')
            ->selectRaw("
                customer, fabricId, fabricStruct, fabricPattern, fabricW,
                MAX(createDate) AS lastCreateDate,
                COUNT(*)        AS foldCount,
                SUM(sumYard)    AS sumYardSum,
                MIN(refId)      AS refId
            ")
            ->groupBy('customer','fabricId','fabricStruct','fabricPattern','fabricW')
            ->orderByDesc('lastCreateDate')
            ->limit(500)
            ->get();
    }

    // ไม่ต้องดึงรายการใหญ่ ๆ อื่นเพื่อเซฟเวลา
    $allfabricout      = collect();
    $stockFabricStruct = collect();

    return view('fabricoutcheck.index', compact('importorder', 'stockFabricStruct', 'allfabricout'));
}
    
    public function store_back(Request $request)
    {
        //
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
            } elseif ($request->filled('fabricStruct')) {
                $importorder = StockFabric::where('fabricStruct', 'LIKE', '%' . $request->fabricStruct . '%')
                    ->groupBy(['fabricId','refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
                    ->selectRaw('fabricId,refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                    ->orderByDesc('lastCreateDate')
                    ->get();
                $select_search = 'importId';
                $searchInput = $request->importId;
            } elseif ($request->filled('imDate')) {

                // Create a DateTime object from the original date format
                $dateObj = date_create_from_format('d/m/Y', $request->imDate);

                // Convert the DateTime object to the desired format
                $fixedValue = date_format($dateObj, 'Y-m-d');
                $importorder = StockFabric::where('createDate', 'LIKE', '%' . $fixedValue . '%')
                    ->groupBy(['fabricId','refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
                    ->selectRaw('fabricId,refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                    ->orderByDesc('lastCreateDate')
                    ->get();
                // $records = StockFabric::groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
                // ->selectRaw('refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                // ->orderByDesc('lastCreateDate')
                // ->get();
                // ->get();
                //print($request->imDate);
                $select_search = 'imDate';
                $searchInput = $request->imDate;
            } else {
                // $importorder = AstPurchaseorder::where('importStatus', 'LIKE', '%' . $request->importId . '%')->get();
                // $select_search = 'non data';
            }

            $orderlist = StockFabric::orderByDesc('createDate')->get();

            // $fabricStructureEdit = FabricAststructure::where('purchaseOrder', $orderEdit->id)->get();
            // for ($i = 0; $i < count($orderlist); $i++) {
            //     $st = $this->getStatus($orderlist[$i]->id);
            //     if ($st == 'no data') {
            //         $orderlist[$i]->status = 'สร้างใบสั่งซื้อ';
            //     } else {
            //         $orderlist[$i]->status = $st;
            //     }
            // }
            // print_r($request->imDate);
            // print_r($searchInput);

            $records = StockFabric::groupBy(['fabricId','refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
                ->selectRaw('fabricId,refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
                ->orderByDesc('lastCreateDate')
                ->get();

            // $records = StockFabric::groupBy(['refId'])
            // ->select('refId')
            // // ->orderByDesc('lastCreateDate')
            // ->get();

            $allfabricout = $records;

            $stockFabricStruct = stockfabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW',])
                ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as 
        lastDate')
                ->get();

            return view('fabricoutcheck.index', compact('importorder', 'select_search', 'searchInput', 'orderlist', 'stockFabricStruct', 'allfabricout'));
        }


        // ////////////////////////////////

        if ($request->filled('submit') && $request->submit == 'nextData') {
            $sudorefId = $request->sudorefId;
            $oldEnd = session()->get('endCount');
            //remove session
            session()->forget('endCount');
            session()->forget('dt');
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
                        $oldEnd,
                        $date,
                        session()->get('customer'),
                    );
                } else {
                    //generate key and set to session
                    // $key = base64_encode(random_bytes(32)); // generates a 32-byte (256-bit) key
                    // // echo $key;
                    // $key = urlencode($key);
                    $bytes = random_bytes(32);
                    $base64 = base64_encode($bytes);
                    $key = str_replace('/', '', $base64);
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
                        session()->get('customer'),
                    );
                }
            }
            // $encodedKey = urlencode($key);
            $refId = $sudorefId;
            // $refId = $key;
            // print_r(
            //     session()->get('fabricStruct') . '/////' .
            //         session()->get('fabricPattern') . '/////' .
            //         session()->get('fabricW')
            // );
            return $this->edit($refId);
        }

        //save last record
        if ($request->filled('submit') && $request->submit == 'endData') {
            $sudorefId = $request->sudorefId;
            $oldEnd = session()->get('endCount');
            //remove session
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('fabricStruct');
            session()->forget('fabricPattern');
            session()->forget('fabricW');
            session()->forget('customer');

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
                $oldEnd  = 1;
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
                        $oldEnd,
                        $date,
                        session()->get('customer'),
                    );
                } else {
                    //generate key and set to session
                    // $key = base64_encode(random_bytes(32)); // generates a 32-byte (256-bit) key
                    // // echo $key;
                    // $key = urlencode($key);
                    $bytes = random_bytes(32);
                    $base64 = base64_encode($bytes);
                    $key = str_replace('/', '', $base64);
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
                        session()->get('customer'),
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
            session()->forget('sum');
            session()->forget('customer');


            // return $this->create();
            StockFabric::where('refId', $sudorefId)->delete();

            return redirect('/fabriccheck');
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
        $StockFabricEdit = StockFabric::where('refId', $refId)
            // ->groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW'])
            // ->selectRaw('refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
            ->selectRaw('refId,fabricStruct, createDate, fabricPattern, fabricW, fold, sumYard')
            // ->orderByDesc('id')
            ->orderBy('id', 'asc')
            ->get();


        $StockFabricEdit2 = StockFabric::where('refId', $refId)
            ->groupBy(['refId', 'fabricStruct', 'fabricPattern', 'fabricW', 'customer'])
            ->selectRaw('customer,refId,fabricStruct, MAX(createDate) as lastCreateDate, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum')
            // ->selectRaw('refId,fabricStruct, createDate, fabricPattern, fabricW, fold, sumYard')
            // ->orderByDesc('id')
            ->orderBy('id', 'asc')
            ->get();

        // print_r($StockFabricEdit2);
        // print_r($refId);
        $customers = Customer::orderBy('name')->get();
        return view('fabricoutcheck.edit', compact('StockFabricEdit', 'StockFabricEdit2','customers'));
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
    public function destroy($refId)
    {
        //
        StockFabric::where('refId', $refId)->delete();

        return $this->index();
    }

    private function saveFabricData($data, $refId, $emp, $fabricStruct, $fabricPattern, $fabricW, $start, $createDate, $customer)
    {
        // echo   $refId .' '. $emp.' '. $fabricStruct .' '. $fabricW .' '. $start .' '. $createDate;
        $c = $start;
        foreach ($data as $datasave) {
            $sf = stockfabric::create([
                'refId' => $refId,
                'emp' => $emp,
                'fabricStruct' => $fabricStruct,
                'fabricPattern' => $fabricPattern,
                'fabricW' => $fabricW,
                'fold' => $c,
                'sumYard' => $datasave,
                'createDate' => $createDate,
                'customer' => $customer
            ]);

            $c = $c + 1;
        }
    }
}
