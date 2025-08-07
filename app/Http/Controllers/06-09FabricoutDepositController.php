<?php

namespace App\Http\Controllers;

use App\Models\AstPurchaseorder;
use App\Models\customer;
use App\Models\FabricAst;
use App\Models\FabricAststructure;
use App\Models\fabricout;
use App\Models\stockfabric;

use Illuminate\Http\Request;

class FabricoutDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            if ($test->vatType == 'A') {
                $vatA = $test->max_no + 1;
            }
            if ($test->vatType == 'B') {
                $vatB = $test->max_no + 1;
            }
            if ($test->vatType == 'C') {
                $vatC = $test->max_no + 1;
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

        $stockFabricStruct = stockfabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW'])
            ->selectRaw('fabricStruct, fabricPattern, fabricW, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as
        lastDate')
            ->get();

        return view('fabricoutdeposit.create', compact('customers', 'order_id', 'customer_name', 'fabric_struct', 'orders', 'stockFabricStruct', 'vatA', 'vatB', 'vatC'));
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

            $fabricStruct = $request->fabricStruct;
            $fabricPattern = $request->fabricPattern;
            $fabricW = $request->fabricW;

            // session()->put('fabricStruct',  $order_send[0]->fabricStructure);
            // session()->put('fabricPattern', $order_send[0]->fabricPattern);
            // session()->put('fabricW', $order_sendW[0]->fabric_w);

            session()->put('fabricStruct', $fabricStruct);
            session()->put('fabricPattern', $fabricPattern);
            session()->put('fabricW', $fabricW);

            $stockFabricStruct = stockfabric::groupBy(['fabricStruct', 'fabricPattern', 'fabricW'])
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
                if ($test->vatType == 'A') {
                    $vatA = $test->max_no + 1;
                }
                if ($test->vatType == 'B') {
                    $vatB = $test->max_no + 1;
                }
                if ($test->vatType == 'C') {
                    $vatC = $test->max_no + 1;
                }
            }

            // print_r($fabricStruct . '///');
            // print_r($fabricPattern . '///');
            // print_r($fabricW);
            return view('fabricoutdeposit.create', compact('customers', 'order_id', 'customer_name', 'fabric_struct', 'orders', 'stockFabricStruct', 'vatA', 'vatB', 'vatC'));
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

                if ($data != '') {
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
            $endCount = $oldEnd + count($arr_data);
            // print($endCount );
            if ($oldEnd == null) {
                $oldEnd = 1;
            } else {
                $oldEnd = $oldEnd + 1;
            }

            session()->put('endCount', $endCount);
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

            session()->put('fabricStruct', $request->input('fabricStruct'));
            session()->put('fabricPattern', $request->input('fabricPattern'));
            session()->put('fabricW', $request->input('fabricW'));

            session()->put('customerReplace', $request->input('customerReplace'));
            session()->put('fabricStructReplace', $request->input('fabricStructReplace'));

            session()->put('vatNo', $request->input('vatNo'));
            session()->put('vatType', $request->input('vatType'));

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

                if ($data != '') {

                    array_push($arr_data, $data);
                }
            }

            //set session from input
            // $endCount = $request->input('endCount') ? $request->input('endCount') : 0;
            $endCount = $oldEnd + count($arr_data);
            // print($endCount );
            if ($oldEnd == null) {
                $oldEnd = 1;
            }
            if ($oldEnd >= 0) {
                $oldEnd = $oldEnd + 1;
            }

            session()->put('endCount', $endCount);
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

            session()->put('fabricStruct', $request->input('fabricStruct'));
            session()->put('fabricPattern', $request->input('fabricPattern'));
            session()->put('fabricW', $request->input('fabricW'));

            session()->put('customerReplace', $request->input('customerReplace'));
            session()->put('fabricStructReplace', $request->input('fabricStructReplace'));

            session()->put('vatNo', $request->input('vatNo'));
            session()->put('vatType', $request->input('vatType'));

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
            return redirect('/fabricoutdeposit');
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
}
