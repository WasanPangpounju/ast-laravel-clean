<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customer;
use App\Models\fabricout;
use App\Models\AstPurchaseorder;
use App\Models\FabricAststructure;

class FabricoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $records = fabricout::groupBy(['fabricStruct', 'refId','customerName','receiveName'])
            ->selectRaw('fabricStruct,receiveName, customerName, COUNT(fold) as foldCount, SUM(sumYard) as sumYardSum, MAX(createDate) as lastDate')
            ->get();
        // ->count();
        // var_dump($records );
        $sumfabricout = $records;
        // print_r($sumfabricout);
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
        $customers = Customer::orderBy('name')->get();
        session()->put('no', 1001);

        return view('fabricout.create', compact('customers'));
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

            session()->forget('fabricStruct');


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

            session()->put('fabricStruct',  $request->input('fabricStruct'));


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
                        $oldEnd,
                        $date,
                        session()->get('no'),
                        session()->get('customerName'),
                        session()->get('receiveName'),
                        session()->get('comment'),
                        session()->get('receiveType'),
                        session()->get('orderId')
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
                        $oldEnd,
                        $date,
                        session()->get('no'),
                        session()->get('customerName'),
                        session()->get('receiveName'),
                        session()->get('comment'),
                        session()->get('receiveType'),
                        session()->get('orderId')
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

            session()->put('fabricStruct',  $request->input('fabricStruct'));


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
                        $oldEnd,
                        $date,
                        session()->get('no'),
                        session()->get('customerName'),
                        session()->get('receiveName'),
                        session()->get('comment'),
                        session()->get('receiveType'),
                        session()->get('orderId')
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
                        $oldEnd,
                        $date,
                        session()->get('no'),
                        session()->get('customerName'),
                        session()->get('receiveName'),
                        session()->get('comment'),
                        session()->get('receiveType'),
                        session()->get('orderId')
                    );
                }
            }


            //remove session
            session()->forget('refId');
            session()->forget('endCount');
            session()->forget('dt');
            session()->forget('fabricStruct');
            session()->forget('no');


            // return $this->create();
            return redirect('/stockfabric');
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

    private function saveFabricData($data, $refId, $emp, $fabricStruct, $start, $createDate, $no, $customerName, $receiveName, $comment, $receiveType, $orderId)
    {
        // echo   $refId .' '. $emp.' '. $fabricStruct .' '. $fabricW .' '. $start .' '. $createDate;
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
                'fold' => $c,
                'sumYard' => $datasave,
                'createDate' => $createDate
            ]);

            $c = $c + 1;
        }
    }
}
