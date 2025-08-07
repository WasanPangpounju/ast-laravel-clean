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
    public function index(Request $request)
    {
        $package = Package::all();
        $supplier = Supplier::all();
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


        return view('package.index', compact('package', 'duplicate_data', 'duplicate_datahtr'));
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
        return view('package.create', compact('package', 'supplier', 'stuff', 'duplicate_data', 'duplicate_datahtr'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //
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
            $sack = $request->input('sack', 0);
            $sack = is_null($sack) ? 0 : $sack;
            $box = $request->input('box', 0);
            $box = is_null($box) ? 0 : $box;
            $pallet = $request->input('pallet', 0);
            $pallet = is_null($pallet) ? 0 : $pallet;
            $paper_bar = $request->input('paper_bar', 0);
            $paper_bar = is_null($paper_bar) ? 0 : $paper_bar;
            $typetag_spool = $request->input('typetag_spool');
            $typetag_sack = $request->input('typetag_sack');
            $typetag_pallet = $request->input('typetag_pallet');
            // $spool = $request->has('spool') ? $request->input('spool') : 0;
            // $sack = $request->has('sack') ? $request->input('sack') : 0;
            // $box = $request->has('box') ? $request->input('box') : 0;
            // $pallet = $request->has('pallet') ? $request->input('pallet') : 0;

            $validatedData = [
                'emp' => $emp,
                // 'createDate' => $createDate,
                'supplier_name' => $supplier_name,
                'stuff' => $stuff,
                'spool' => $spool,
                'sack' => $sack,
                'box' => $box,
                'pallet' => $pallet,
                // 'paper_bar' => $paper_bar,
                // 'typetag_spool' => $typetag_spool,
                // 'typetag_sack' => $typetag_sack,
                // 'typetag_pallet' => $typetag_pallet
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
    public function edit($supplier_name)
    {
        //
        // $package = Package::all();
        //print_r($supplier_name);
        /*
        $dataEdit = Package::where('supplier_name', $supplier_name)
        ->selectRaw('supplier_name , SUM(box) as boxsum , SUM(spool) as spoolsum , SUM(sack) as sacksum , SUM(pallet) as palletsum')
        ->groupBy($supplier_name) 
        ->get();
        print_r($dataEdit);
        $packageList = Package::orderBy('id')->get()->groupBy(function($data) {
        return $data->supplier_name;
        });
        */

        $dataEdit = Package::where('supplier_name', $supplier_name)
            ->groupBy('supplier_name')
            ->selectRaw('supplier_name as supplierName, SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum')
            ->get();
        $dataEdithtr = Htrpackage::where('supplier_name', $supplier_name)
            ->groupBy('supplier_name')
            ->selectRaw('supplier_name as supplierName, SUM(box) as boxsum,SUM(spool) as spoolsum,SUM(sack) as sacksum,SUM(pallet) as palletsum')
            ->get();

        return view('package.edit', compact('dataEdit', 'dataEdithtr'));
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
