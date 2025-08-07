<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Supplier;
use App\Models\Coordinator;
use App\Models\Material;
use App\Models\Htrpackage;
use App\Models\Packageast;
use App\Models\Stuff;


use App\Models\FabricAststructure;
use App\Models\Inventory;


use PDF;
use Dompdf\Dompdf;
use Codedge\Fpdf\Fpdf\Fpdf;

use Carbon\Carbon;

class PackageController extends Controller
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
        //         // Get all records where emp is equal to "test"
        // $records = Htrpackage::where('emp', '=', 'วสันต์ แปงปวนจู')->get();

        // // Delete all records returned by the query
        // $records->each(function ($record) {
        //     $record->delete();
        // });

        $package = Package::all();
        // $supplier = Supplier::all();
        $supplier = Material::select('supplierName AS name')
            ->groupBy('supplierName')
            ->orderBy('supplierName')
            ->get();

        // print(count($supplier) );
        $duplicate_data = Package::select('supplier_name', Package::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
            ->groupBy('supplier_name')
            ->get();
        $duplicate_datahtr = Htrpackage::select('supplier_name', Htrpackage::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
            ->groupBy('supplier_name')
            ->get();

        // $duplicate_data = Package::table('packages')
        //         ->join('packages','packages.supplier_name','=','Htrpacke.supplier_name')
        //         ->groupBy('packages.supplier_name')
        //         ->selectRaw('packages.supplier_name, sum(packages.box) - sum(Htrpacke.box) as boxsum,
        //         sum(packages.spool) - sum(Htrpacke.spool) as spoolsum,
        //         sum(packages.sack) - sum(Htrpacke.sack) as sacksum,
        //         sum(packages.pallet) - sum(Htrpacke.pallet) as palletsum,')
        //          ->get();
        //         $packageList = Package::orderBy('id')->get()->groupBy(function($data) {
        //             return $data->supplier_name;
        //         });
        // print(count($packageList) );
        $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();
        // $inventorydata = Inventory::whereIn('refId', $ecp)
        //     ->select(Inventory::raw('id', 'refId' , 'SUM(fold) AS sumFold' , 'SUM(sumYard) AS  sumYard'))
        //     ->get();
        // $inventorydata = Inventory::whereIn('refId', $ecp)
        //     ->select('id', 'refId' , 'SUM(fold) AS sumFold' , 'SUM(sumYard) AS  sumYard')
        //     ->get();
        // $inventorydata = Inventory::select('id', 'refId' , 'SUM(fold) AS sumFold' , 'SUM(sumYard) AS  sumYard')
        // $inventorydata = Inventory::select('id', 'refId', 'SUM(fold) as foldSum', 'SUM(sumYard) as sumYardSum')
        //     ->groupBy('refId')
        //     ->get();
        // $inventorydata = Inventory::select('id', 'refId', Inventory::raw('SUM(fold) as foldSum'), Inventory::raw('SUM(sumYard) as sumYardSum'))
        //     // ->groupBy('refId')
        //     ->get();
        // $inventorydata = Inventory::select('refId', Inventory::raw('SUM(fold) as foldSum'), Inventory::raw('SUM(sumYard) as sumYardSum'))
        //     ->groupBy('refId')
        //     ->get();
        // print_r($inventorydata);
        // $allpackage = Htrpackage::select('supplier_name',Htrpackage::raw('SUM(box) as boxsum, SUM(spool) as spoolsum, SUM(sack) as sacksum, SUM(pallet) as palletsum, SUM(`partition`) as partitionsum, SUM(spool_paper) as spool_paper, SUM(spool_plastic) as spool_plastic, SUM(spoolC_plastic) as spoolC_plastic, SUM(spoolC_paper) as spoolC_paper, SUM(pallet_wood) as pallet_wood, SUM(pallet_steel) as pallet_steel'))
        //     ->groupBy('supplier_name')
        //     ->get();   
        $allpackage = Htrpackage::all();
        // print_r($allpackage);
        return view('package.index', compact('package', 'duplicate_data', 'duplicate_datahtr', 'supplier', 'allpackage'));
        // return view('package.index' ,compact('package',));


        return view('package.editdetail', compact('package', 'supplier'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $package = Package::all();
        $supplier = Supplier::all();
        $stuff = Stuff::all();
        $duplicate_data = Package::select('supplier_name', Package::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
            ->groupBy('supplier_name')
            ->get();
        $duplicate_datahtr = Htrpackage::select('supplier_name', Htrpackage::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
            ->groupBy('supplier_name')
            ->get();

        //get Supplier name from get parameter
        $selectSupplier = request()->input('supplier');

        //get all input package from material
        $sumPackage = Material::select(Material::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
            ->where('supplierName', $selectSupplier)
            ->get();
        // print($sumPackage );

        //get all package for return 
        $returnPackage = $this->getData($selectSupplier);

        //get package type 

        $palletSteelImp = Packageast::select(Packageast::raw('SUM(pallet) as palletsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('pallet_type', 'steel')
            ->where('package_status', 'packageImport')
            ->get();
        // print($palletSteelImp );
        $palletWoodImp = Packageast::select(Packageast::raw('SUM(pallet) as palletsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('pallet_type', 'wood')
            ->where('package_status', 'packageImport')
            ->get();
        // print($palletWoodImp );

        $palletSteelRet = Packageast::select(Packageast::raw('SUM(pallet) as palletsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('pallet_type', 'steel')
            ->where('package_status', 'packageReturn')
            ->get();
        // print($palletSteelImp );
        $palletWoodRet = Packageast::select(Packageast::raw('SUM(pallet) as palletsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('pallet_type', 'wood')
            ->where('package_status', 'packageReturn')
            ->get();
        // print($palletWoodImp );

        $spool_plasticImp = Packageast::select(Packageast::raw('SUM(spool) as spoolsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('spool_type', 'spool_plastic')
            ->where('package_status', 'packageImport')
            ->get();
        $spool_paperImp = Packageast::select(Packageast::raw('SUM(spool) as spoolsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('spool_type', 'spool_paper')
            ->where('package_status', 'packageImport')
            ->get();
        $spoolC_plasticImp = Packageast::select(Packageast::raw('SUM(spool) as spoolsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('spool_type', 'spoolC_plastic')
            ->where('package_status', 'packageImport')
            ->get();
        $spoolC_paperImp = Packageast::select(Packageast::raw('SUM(spool) as spoolsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('spool_type', 'spoolC_paper')
            ->where('package_status', 'packageImport')
            ->get();

        $spool_plasticRet = Packageast::select(Packageast::raw('SUM(spool) as spoolsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('spool_type', 'spool_plastic')
            ->where('package_status', 'packageReturn')
            ->get();
        $spool_paperRet = Packageast::select(Packageast::raw('SUM(spool) as spoolsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('spool_type', 'spool_paper')
            ->where('package_status', 'packageReturn')
            ->get();
        $spoolC_plasticRet = Packageast::select(Packageast::raw('SUM(spool) as spoolsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('spool_type', 'spoolC_plastic')
            ->where('package_status', 'packageImport')
            ->get();
        $spoolC_paperRet = Packageast::select(Packageast::raw('SUM(spool) as spoolsum'))
            ->where('supplier_name', $selectSupplier)
            ->where('spool_type', 'spoolC_paper')
            ->where('package_status', 'packageReturn')
            ->get();

        $partition =
            // Packageast::select(Packageast::raw('SUM(partition) as partitionsum'))
            Packageast::where('supplier_name', $selectSupplier)
            ->where('package_status', 'packageReturn')
            ->get();


        $partitionImp = Packageast::where('supplier_name', $selectSupplier)
            ->where('package_status', 'packageImport')
            ->sum('partition');

        // var_dump($partitionImp );

        $partitionRet = Packageast::where('supplier_name', $selectSupplier)
            ->where('package_status', 'packageReturn')
            ->sum('partition');
        // print($partitionRet);


        //get all package return success
        //         $sumReturnPackageSuccess = Htrpackage::select(Htrpackage::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum , SUM(pallet) as palletsum ,
        //         SUM(partition) as partition, 
        //         SUM(spool_paper) as spool_paper ,
        //         SUM(spool_plastic) as spool_plastic ,
        //         SUM(spoolC_plastic) as spoolC_plastic,
        //         SUM(spoolC_paper) as spoolC_paper,
        //         SUM(pallet_wood) as pallet_wood,
        //         SUM(pallet_steel) as pallet_steel
        // '))
        //             ->where('supplier_name', $selectSupplier)
        //             ->get();
        //         // print($sumPackage );
        $sumReturnPackageSuccess = Htrpackage::select(Htrpackage::raw('SUM(box) as boxsum, SUM(spool) as spoolsum, SUM(sack) as sacksum, SUM(pallet) as palletsum, SUM(`partition`) as partitionsum, SUM(spool_paper) as spool_paper, SUM(spool_plastic) as spool_plastic, SUM(spoolC_plastic) as spoolC_plastic, SUM(spoolC_paper) as spoolC_paper, SUM(pallet_wood) as pallet_wood, SUM(pallet_steel) as pallet_steel'))
            ->where('supplier_name', $selectSupplier)
            ->get();


        return view('package.create', compact(
            'package',
            'supplier',
            'stuff',
            'duplicate_data',
            'duplicate_datahtr',
            'selectSupplier',
            'sumPackage',
            'returnPackage',
            'palletSteelImp',
            'palletWoodImp',
            'palletSteelRet',
            'palletWoodRet',
            'spool_plasticImp',
            'spool_paperImp',
            'spoolC_plasticImp',
            'spoolC_paperImp',
            'spool_plasticRet',
            'spool_paperRet',
            'spoolC_plasticRet',
            'spoolC_paperRet',
            'partitionImp',
            'partitionRet',
            'sumReturnPackageSuccess'
        ));
    }

    public function editdetail()
    {
        $package = Package::all();
        $supplier = Supplier::all();
        $duplicate_data = Package::select('supplier_name', Package::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
            ->groupBy('supplier_name')
            ->get();
        $duplicate_datahtr = Htrpackage::select('supplier_name', Htrpackage::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
            ->groupBy('supplier_name')
            ->get();
        return view('package.editdetail', compact('package', 'supplier'));
    }


    public static function getData($supplier_searchData)
    {
        $duplicate_data = Package::select(Package::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
            ->where('supplier_name', $supplier_searchData)
            ->get();

        $package_import = $duplicate_data;
        return         $package_import;
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
        if ($request->filled('submit') && $request->submit == 'searchImport') {
            $select_search = '';
            $searchInput = '';

            if ($request->filled('importId') && $request->filled('supId') && $request->filled('yarnType') && $request->filled('imDate')) {
                //print('1 2 3 4');
            } elseif ($request->filled('importId') && $request->filled('supId') && $request->filled('yarnType')) {
                //print('1 2 3');
            } elseif ($request->filled('supId') && $request->filled('yarnType') && $request->filled('imDate')) {
                //print('2 3 4');
            } elseif ($request->filled('importId') && $request->filled('supId')) {
                //print('1 2');
            } elseif ($request->filled('yarnType') && $request->filled('imDate')) {
                //print('3 4');
            } elseif ($request->filled('supId') && $request->filled('yarnType')) {
                //print('2 3');
            } elseif ($request->filled('importId') && $request->filled('imDate')) {
                //print('1 4');
            } elseif ($request->filled('importId') && $request->filled('yarnType')) {
                //print('1 3');
            } elseif ($request->filled('supId') && $request->filled('imDate')) {
                //print('2 4');
            } elseif ($request->filled('importId')) {
                $importmaterial = Material::where('importStatus', 'LIKE', '%' . $request->importId . '%')->get();
                //var_dump($importmaterial );
                //print($request->importId);
                $select_search = 'importId';
                $searchInput = $request->importId;
            } elseif ($request->filled('supId')) {
                // $importmaterial = Material::where('supplierName', 'LIKE', '%' . $request->supId . '%')->get();
                $importPackage = Package::where('supplier_name', 'like', '%' . $request->supId . '%')
                    ->select('supplier_name', Package::raw('SUM(box) as boxsum'), Package::raw('SUM(spool) as spoolsum'), Package::raw('SUM(sack) as sacksum'), Package::raw('SUM(pallet) as palletsum'))
                    ->groupBy('supplier_name')
                    ->get();
                $importHtrpackage = Htrpackage::where('supplier_name', 'like', '%' . $request->supId . '%')
                    ->select('supplier_name', Htrpackage::raw('SUM(box) as boxsum'), Htrpackage::raw('SUM(spool) as spoolsum'), Htrpackage::raw('SUM(sack) as sacksum'), Htrpackage::raw('SUM(pallet) as palletsum'))
                    ->groupBy('supplier_name')
                    ->get();
                //print($request->supId);
                $select_search = 'supId';
                $searchInput = $request->supId;
            } elseif ($request->filled('yarnType')) {
                $importmaterial = Material::where('yarnType', 'LIKE', '%' . $request->yarnType . '%')->get();
                //print($request->yarnType );
                $select_search = 'yarnType';
                $searchInput = $request->yarnType;
            } elseif ($request->filled('imDate')) {
                $importmaterial = Material::where('createDate', 'LIKE', '%' . $request->imDate . '%')->get();
                //print($request->imDate);
                $select_search = 'imDate';
                $searchInput = $request->imDate;
            } else {
                $importmaterial = Material::where('importStatus', 'LIKE', '%' . $request->importId . '%')->get();
                $select_search = 'non data';
            }
            $package = Package::all();
            $supplier = Supplier::all();
            $stuff = Stuff::all();

            return view('package.index', compact('select_search', 'searchInput', 'importPackage', 'importHtrpackage'));
        }
        if ($request->filled('submit') && $request->submit == "htrpackage") {
            //print('htrpackage');

            // $validatedData = $request->validate([
            //     'emp' => 'required',
            //     'supplier_name' => 'required',
            //     'spool' => 'required',
            //     'sack' => 'required',
            //     'box' => 'required',
            //     'pallet' => 'required'
            // ]);

            $emp = $request->input('emp');
            $supplier_name = $request->input('supplier_name');
            $spool = $request->input('spool', 0);
            $spool = is_null($spool) ? 0 : $spool;
            $sack = $request->input('sack', 0);
            $sack = is_null($sack) ? 0 : $sack;
            $box = $request->input('box', 0);
            $box = is_null($box) ? 0 : $box;
            $pallet = $request->input('pallet', 0);
            $pallet = is_null($pallet) ? 0 : $pallet;

            // $spool = $request->has('spool') ? $request->input('spool') : 0;
            // $sack = $request->has('sack') ? $request->input('sack') : 0;
            // $box = $request->has('box') ? $request->input('box') : 0;
            // $pallet = $request->has('pallet') ? $request->input('pallet') : 0;

            $validatedData = [
                'emp' => $emp,
                'supplier_name' => $supplier_name,
                'spool' => $spool,
                'sack' => $sack,
                'box' => $box,
                'pallet' => $pallet
            ];


            $show = Htrpackage::create($validatedData);
            if ($show) {
                $lastCreatedData = $validatedData;
                return view('package.printpdf', compact('lastCreatedData'));
                // $pdf = PDF::loadView('package.printpdf');
                // return $pdf->download('pdf.pdf');
            }
            //$lastCreatedData = Htrpackage::latest()->first();

            //return redirect('/package.printpdf',compact('lastCreatedData'))->with('success', 'package is successfully saved');


        }
        if ($request->filled('submit') && $request->submit == 'packagecheck') {
            //print($request->submit);
            if (!$request->filled('createDate')) {
                $request->request->add(['createDate' => date("m/d/Y")]);
            }
            if (!$request->filled('lot')) {
                $request->request->add(['lot' => date("Y") . '1']);
            }
            if (!$request->filled('pallet_wood')) {
                $request->request->add(['pallet_wood' => '0']);
            }
            if (!$request->filled('pallet_steel')) {
                $request->request->add(['pallet_steel' => '0']);
            }
            if (!$request->filled('box')) {
                $request->request->add(['box' => '0']);
            }
            if (!$request->filled('partition')) {
                $request->request->add(['partition' => '0']);
            }
            if (!$request->filled('sack')) {
                $request->request->add(['sack' => '0']);
            }
            if (!$request->filled('spool_paper')) {
                $request->request->add(['spool_paper' => '0']);
            }
            if (!$request->filled('spool_plastic')) {
                $request->request->add(['spool_plastic' => '0']);
            }
            if (!$request->filled('spoolC_paper')) {
                $request->request->add(['spoolC_paper' => '0']);
            }
            if (!$request->filled('spoolC_plastic')) {
                $request->request->add(['spoolC_plastic' => '0']);
            }
            if (!$request->filled('importStatus')) {
                $request->request->add(['importStatus' => 'no import number']);
            }

            $packdata = $request;
            //print($imdata->supplierName );

            return view('package.packagecheck', compact('packdata'));
        }
        if ($request->filled('submit') && $request->submit == "Htrpackagecreate") {
            //print('htrpackage');

            // $validatedData = $request->validate([
            //     'emp' => 'required',
            //     'supplier_name' => 'required',
            //     'spool' => 'required',
            //     'sack' => 'required',
            //     'box' => 'required',
            //     'pallet' => 'required'
            // ]);

            $emp = $request->input('emp');
            $createDate = $request->input('createDate');
            $supplier_name = $request->input('supplier_name');
            $stuff = $request->input('stuff');
            $spool = $request->input('spool', 0);
            $spool = is_null($spool) ? 0 : $spool;
            $pallet = $request->input('pallet', 0);
            $pallet = is_null($pallet) ? 0 : $pallet;
            $spool_paper = $request->input('spool_paper', 0);
            $spool_paper = is_null($spool_paper) ? 0 : $spool_paper;
            $spool_plastic = $request->input('spool_plastic', 0);
            $spool_plastic = is_null($spool_plastic) ? 0 : $spool_plastic;
            $spoolC_paper = $request->input('spoolC_paper', 0);
            $spoolC_paper = is_null($spoolC_paper) ? 0 : $spoolC_paper;
            $spoolC_plastic = $request->input('spoolC_plastic', 0);
            $spoolC_plastic = is_null($spoolC_plastic) ? 0 : $spoolC_plastic;
            $sack = $request->input('sack', 0);
            $sack = is_null($sack) ? 0 : $sack;
            $box = $request->input('box', 0);
            $box = is_null($box) ? 0 : $box;
            $pallet_wood = $request->input('pallet_wood', 0);
            $pallet_wood = is_null($pallet_wood) ? 0 : $pallet_wood;
            $pallet_steel = $request->input('pallet_steel', 0);
            $pallet_steel = is_null($pallet_steel) ? 0 : $pallet_steel;
            $partition = $request->input('partition', 0);
            $partition = is_null($partition) ? 0 : $partition;

            // $spool = $request->has('spool') ? $request->input('spool') : 0;
            // $sack = $request->has('sack') ? $request->input('sack') : 0;
            // $box = $request->has('box') ? $request->input('box') : 0;
            // $pallet = $request->has('pallet') ? $request->input('pallet') : 0;

            $validatedData = [
                'emp' => $emp,
                'createDate' => $createDate,
                'supplier_name' => $supplier_name,
                'stuff' => $stuff,
                'spool' => $spool,
                'pallet' => $pallet,
                'spool_paper' => $spool_paper,
                'spool_plastic' => $spool_plastic,
                'spoolC_paper' => $spoolC_paper,
                'spoolC_plastic' => $spoolC_plastic,
                'sack' => $sack,
                'box' => $box,
                'pallet_wood' => $pallet_wood,
                'pallet_steel' => $pallet_steel,
                'partition' => $partition,
            ];

            print_r($validatedData);
            $show = Htrpackage::create($validatedData);
            if ($show) {
                $lastCreatedData = $validatedData;
                return view('package.printpdf', compact('lastCreatedData'));
                // $pdf = PDF::loadView('package.printpdf');
                // return $pdf->download('pdf.pdf');
            }
            //$lastCreatedData = Htrpackage::latest()->first();

            //return redirect('/package.printpdf',compact('lastCreatedData'))->with('success', 'package is successfully saved');


        } elseif ($request->filled('submit') && $request->submit == "genPDF") {
            // $orderlist = AstPurchaseorder::latest()->take(5)->get();

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
            $this->fpdf->Cell(80, 10, '', 0, 0);
            $this->fpdf->SetFont('THSarabunNew', 'B', 24);
            $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', 'AST ใบส่งคืนสินค้า'), 0, 0);
            $this->fpdf->Cell(20, 10, '', 0, 0);
            $this->fpdf->Cell(80, 10, 'No.', 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(150, 5, '', 0, 0);
            $this->fpdf->Cell(40, 10, iconv('UTF-8', 'cp874', 'ว.ด.ป.' . $day . '/' . $month . '/' . $year), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
            $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', 'บริษัท.....'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', 'หลอด  .....    หลวด'), 0, 0);
            $this->fpdf->Cell(60, 10, '', 0, 0);
            $this->fpdf->Cell(80, 10, iconv('UTF-8', 'cp874', 'กระสอบ   .....    ใบ'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', 'กล่อง  .....   หลอด'), 0, 0);
            $this->fpdf->Cell(60, 10, '', 0, 0);
            $this->fpdf->Cell(80, 10, iconv('UTF-8', 'cp874', 'พาเลท.....'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', 'อื่นๆระบุ................................................................................................................................................................'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
            $this->fpdf->Cell(130, 10, '', 0, 0);
            $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', 'ตรวจสอบแล้วถูกต้อง'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(80, 10, iconv('UTF-8', 'cp874', 'ผู้ส่ง.............................................................'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->SetFont('THSarabunNew', 'B', 18);
            $this->fpdf->Cell(110, 10, '', 0, 0);
            $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', 'ลงชื่อ.........................................ผู้รับสินค้า'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of line
            $this->fpdf->Cell(110, 10, '', 0, 0);
            $this->fpdf->Cell(60, 10, iconv('UTF-8', 'cp874', 'ทะเบียนรถ...................................'), 0, 0);
            $this->fpdf->Cell(20, 5, '', 5, 1); //end of linex`
            $this->fpdf->Cell(80, 10, iconv('UTF-8', 'cp874', 'ผู้ตรวจสอบ..................................................'), 0, 0);
            // $this->fpdf->SetFont('Arial', 'B', 15);
            // $this->fpdf->AddPage("L", ['100', '100']);
            // $this->fpdf->Text(10, 10, "Hello World! ");
            $this->fpdf->Output();
            exit;

            return view('package.index', compact('orderlist'));
        }
        $validatedData = $request->validate([
            'emp' => 'required',
            'supplier_id' => 'required',
            'supplier_name' => 'required',
            'spool' => 'required',
            'sack' => 'required',
            'box' => 'required',
            'pallet' => 'required',
            'package_status' => 'required'
        ]);

        $show = package::index($validatedData);

        return redirect('/package')->with('success', 'package is successfully saved');
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
    // public function edit($supplier_name)
    public function edit($id)
    {
        // $dataEdit = Package::where('supplier_name', $supplier_name)
        //     ->groupBy('supplier_name')
        //     ->selectRaw('supplier_name as supplierName, SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum')
        //     ->get();
        // $dataEdithtr = Htrpackage::where('supplier_name', $id)
        //     ->groupBy('supplier_name')
        //     ->selectRaw('supplier_name as supplierName, SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum')
        //     ->get();
        $dataEdit = Htrpackage::find($id);

        return view('package.edit', compact('dataEdit'));
        // return view('materialstore.edit', compact('supplier', 'stockYarns', 'stuff', 'dataEdit'));


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
        $request->validate([
            'supplier_name' => 'required',
            'createDate' => 'required',
            'pallet_wood' => 'required',
            'pallet_steel' => 'required',
            'box' => 'required',
            'sack' => 'required',
            'partition' => 'required',
            'spool_paper' => 'required',
            'spool_plastic' => 'required',
            'spoolC_paper' => 'required',
            'spoolC_plastic' => 'required',
        ]);
        $dataUpdate = Htrpackage::find($id);
        // $lastTenRecords = Material::latest()->take(10)->get();
        // Getting values from the blade template form
        $dataUpdate->supplier_name = $request->get('supplier_name');
        $dataUpdate->createDate = $request->get('createDate');
        $dataUpdate->pallet_wood = $request->get('pallet_wood');
        $dataUpdate->pallet_steel = $request->get('pallet_steel');
        $dataUpdate->box = $request->get('box');
        $dataUpdate->sack = $request->get('sack');
        $dataUpdate->partition = $request->get('partition');
        $dataUpdate->spool_paper = $request->get('spool_paper');
        $dataUpdate->spool_plastic = $request->get('spool_plastic');
        $dataUpdate->spoolC_paper = $request->get('spoolC_paper');
        $dataUpdate->spoolC_plastic = $request->get('spoolC_plastic');
        $dataUpdate->save();

        // return redirect('/materialstore')->with('success', 'materialstore updated.'); // -> resources/views/stocks/index.blade.php
        // $lastTenRecords = Material::latest()->take(10)->get();
        // print_r($request);
        $package = Package::all();
        // $supplier = Supplier::all();
        $supplier = Material::select('supplierName AS name')
            ->groupBy('supplierName')
            ->orderBy('supplierName')
            ->get();

        // print(count($supplier) );
        $duplicate_data = Package::select('supplier_name', Package::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
            ->groupBy('supplier_name')
            ->get();
        $duplicate_datahtr = Htrpackage::select('supplier_name', Htrpackage::raw('SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum'))
            ->groupBy('supplier_name')
            ->get();
        $ecp = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();
        $allpackage = Htrpackage::all();
        // print_r($allpackage);
        return view('package.index', compact('package', 'duplicate_data', 'duplicate_datahtr', 'supplier', 'allpackage'));
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
        $data = Htrpackage::find($id);
        $data->delete();

        // $lastTenRecords = Htrpackage::latest()->take(10)->get();

        // return $this->index();
        return $this->index();
    }
}
