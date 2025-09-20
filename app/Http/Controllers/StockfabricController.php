<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\stockfabric;
use App\Models\fabricout;

class StockfabricController extends Controller
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
        // Get combined data from both tables for initial view
        $combinedData = $this->getCombinedData();
        
        return view('stockfabric.index', compact('combinedData'));
    }

    /**
     * Store a newly created resource in storage (used for search).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // searchImport
        if ($request->filled('submit') && $request->submit == 'searchImport') {
            
            // Get combined data filtered by search criteria
            $combinedData = $this->getCombinedData(
                $request->get('customer'),
                $request->get('fabricStruct'),
                $request->get('fabricId'),
                $request->get('fabricPattern'),
                $request->get('fabricW')
            );

            return view('stockfabric.index', compact('combinedData'));
        }
    }

    /**
     * Helper method to get and combine data.
     *
     * @param string|null $customer
     * @param string|null $fabricStruct
     * @param string|null $fabricId
     * @param string|null $fabricPattern
     * @param string|null $fabricW
     * @return \Illuminate\Support\Collection
     */
    private function getCombinedData($customer = null, $fabricStruct = null, $fabricId = null, $fabricPattern = null, $fabricW = null)
    {
        // Base query for stock-in data
        $stockInsQuery = \DB::table('stockfabrics')
            ->selectRaw("
                COALESCE(NULLIF(TRIM(customer), ''), 'AST') AS customer,
                fabricId,
                fabricStruct,
                fabricPattern,
                fabricW,
                COUNT(fold) as foldCountIn,
                SUM(sumYard) as sumYardIn
            ")
            ->groupBy('customer', 'fabricId', 'fabricStruct', 'fabricPattern', 'fabricW');

        // Apply filters if search criteria are provided
        if ($customer) {
            $stockInsQuery->where('customer', 'like', '%' . $customer . '%');
        }
        if ($fabricStruct) {
            $stockInsQuery->where('fabricStruct', 'like', '%' . $fabricStruct . '%');
        }
        if ($fabricId) {
            $stockInsQuery->where('fabricId', 'like', '%' . $fabricId . '%');
        }
        if ($fabricPattern) {
            $stockInsQuery->where('fabricPattern', 'like', '%' . $fabricPattern . '%');
        }
        if ($fabricW) {
            $stockInsQuery->where('fabricW', 'like', '%' . $fabricW . '%');
        }

        $stockIns = $stockInsQuery->get();

        // Base query for stock-out data
        $stockOutsQuery = \DB::table('fabricouts')
            ->selectRaw("
                COALESCE(NULLIF(TRIM(customerName), ''), 'AST') AS customer,
                fabricStruct,
                fabricPattern,
                fabricW,
                COUNT(fold) as foldCountOut,
                SUM(sumYard) as sumYardOut
            ")
            ->groupBy('customer', 'fabricStruct', 'fabricPattern', 'fabricW');
        
        // Apply filters to stock-out data as well
        if ($customer) {
             $stockOutsQuery->where('customerName', 'like', '%' . $customer . '%');
        }
        if ($fabricStruct) {
            $stockOutsQuery->where('fabricStruct', 'like', '%' . $fabricStruct . '%');
        }
        if ($fabricPattern) {
            $stockOutsQuery->where('fabricPattern', 'like', '%' . $fabricPattern . '%');
        }
        if ($fabricW) {
            $stockOutsQuery->where('fabricW', 'like', '%' . $fabricW . '%');
        }

        $stockOuts = $stockOutsQuery->get();

        // Combine and calculate remaining stock
        $combinedData = $stockIns->map(function ($in) use ($stockOuts) {
            $out = $stockOuts->first(function ($o) use ($in) {
                return $o->customer == $in->customer &&
                       $o->fabricStruct == $in->fabricStruct &&
                       $o->fabricPattern == $in->fabricPattern &&
                       $o->fabricW == $in->fabricW;
            });

            return (object)[
                'customer' => $in->customer,
                'fabricId' => $in->fabricId,
                'fabricStruct' => $in->fabricStruct,
                'fabricPattern' => $in->fabricPattern,
                'fabricW' => $in->fabricW,
                'foldCountIn' => $in->foldCountIn,
                'sumYardIn' => $in->sumYardIn,
                'foldCountOut' => $out ? $out->foldCountOut : 0,
                'sumYardOut' => $out ? $out->sumYardOut : 0,
                'foldCountRemaining' => $in->foldCountIn - ($out ? $out->foldCountOut : 0),
                'sumYardRemaining' => $in->sumYardIn - ($out ? $out->sumYardOut : 0),
            ];
        });

        return $combinedData;
    }
}
