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

    public function index()
    {
        // Get stock-in data
        $stockIns = \DB::table('stockfabrics')
            ->selectRaw("
                COALESCE(NULLIF(TRIM(customer), ''), 'AST') AS customer,
                fabricStruct,
                fabricPattern,
                fabricW,
                COUNT(fold) as foldCountIn,
                SUM(sumYard) as sumYardIn
            ")
            ->groupBy('customer', 'fabricStruct', 'fabricPattern', 'fabricW')
            ->get();

        // Get stock-out data
        $stockOuts = \DB::table('fabricouts')
            ->selectRaw("
                COALESCE(NULLIF(TRIM(customerName), ''), 'AST') AS customer,
                fabricStruct,
                fabricPattern,
                fabricW,
                COUNT(fold) as foldCountOut,
                SUM(sumYard) as sumYardOut
            ")
            ->groupBy('customer', 'fabricStruct', 'fabricPattern', 'fabricW')
            ->get();

        // Combine both data sets and calculate remaining stock
        $combinedData = $stockIns->map(function ($in) use ($stockOuts) {
            $out = $stockOuts->first(function ($o) use ($in) {
                return $o->customer == $in->customer &&
                       $o->fabricStruct == $in->fabricStruct &&
                       $o->fabricPattern == $in->fabricPattern &&
                       $o->fabricW == $in->fabricW;
            });

            return (object)[
                'customer' => $in->customer,
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

        return view('stockfabric.index', compact('combinedData'));
    }

    public function store(Request $request)
    {
        // searchImport
        if ($request->filled('submit') && $request->submit == 'searchImport') {

            // Get all data from stockfabrics and fabricouts
            $stockIns = \DB::table('stockfabrics')
                ->selectRaw("
                    COALESCE(NULLIF(TRIM(customer), ''), 'AST') AS customer,
                    fabricId,
                    fabricStruct,
                    fabricPattern,
                    fabricW,
                    COUNT(fold) as foldCountIn,
                    SUM(sumYard) as sumYardIn
                ")
                ->groupBy('customer', 'fabricId', 'fabricStruct', 'fabricPattern', 'fabricW')
                ->get();

            $stockOuts = \DB::table('fabricouts')
                ->selectRaw("
                    COALESCE(NULLIF(TRIM(customerName), ''), 'AST') AS customer,
                    fabricStruct,
                    fabricPattern,
                    fabricW,
                    COUNT(fold) as foldCountOut,
                    SUM(sumYard) as sumYardOut
                ")
                ->groupBy('customer', 'fabricStruct', 'fabricPattern', 'fabricW')
                ->get();

            // Filter data based on search criteria from the form
            $filteredIns = $stockIns->filter(function ($in) use ($request) {
                $fs_norm = function($s) { return preg_replace('/\s+/', ' ', trim((string) $s)); };
                
                // Perform a precise search based on all four fields
                $isMatch = true;
                if ($request->filled('customer') && $in->customer !== $request->customer) {
                    $isMatch = false;
                }
                if ($request->filled('fabricStruct') && $fs_norm($in->fabricStruct) !== $fs_norm($request->fabricStruct)) {
                    $isMatch = false;
                }
                if ($request->filled('fabricPattern') && $in->fabricPattern !== $request->fabricPattern) {
                    $isMatch = false;
                }
                if ($request->filled('fabricW') && $in->fabricW !== $request->fabricW) {
                    $isMatch = false;
                }
                if ($request->filled('fabricId') && $in->fabricId !== $request->fabricId) {
                    $isMatch = false;
                }
                
                return $isMatch;
            });

            // Combine filtered data sets and calculate remaining stock
            $combinedData = $filteredIns->map(function ($in) use ($stockOuts) {
                $out = $stockOuts->first(function ($o) use ($in) {
                    return $o->customer == $in->customer &&
                           $o->fabricStruct == $in->fabricStruct &&
                           $o->fabricPattern == $in->fabricPattern &&
                           $o->fabricW == $in->fabricW;
                });

                return (object)[
                    'customer' => $in->customer,
                    'fabricStruct' => $in->fabricStruct,
                    'fabricId' => $in->fabricId,
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

             return view('stockfabric.index', compact('combinedData'));
        }
    }
}
