<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Fabricimport;   // ใช้ F ใหญ่ ตรงกับชื่อคลาส
use App\Models\customer;       // ใช้ C ใหญ่ ตรงกับชื่อคลาส
use App\Models\AstPurchaseorder;
use App\Models\FabricAst;

class FabricimportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ---------- หน้าเลือกข้อมูล + ช่องคีย์ 160 ช่อง ----------
    public function create()
    {
        $orders = AstPurchaseorder::select(
            'ast_purchaseorders.id',
            'ast_purchaseorders.customerName',
            'ast_purchaseorders.createDate',
            'ast_purchaseorders.fabricId',
            'ast_purchaseorders.fabricStructure',
            'ast_purchaseorders.orderSumYard',
            'ast_purchaseorders.purchaseOrder',
            'ast_purchaseorders.fabricPattern',
            'fabric_asts.fabric_w'
        )
        ->join('fabric_asts', 'fabric_asts.purchaseOrder', '=', 'ast_purchaseorders.id')
        ->orderBy('ast_purchaseorders.customerName')
        ->get();

        $customers = Customer::orderBy('name')->get();

        return view('fabricimport.create', compact('orders','customers'));
    }

    // ---------- เก็บรอบ/ปิดรอบ ----------
    public function store(Request $request)
    {
        $action = $request->input('submit'); // nextData | endData

        $request->validate([
            'dt'             => ['required','string'], // dd/mm/yyyy
            'fabricId'       => ['required','string'],
            'fabricStruct'   => ['required','string'],
            'fabricPattern'  => ['nullable','string'],
            'fabricW'        => ['required','string'],
            'customer'       => ['nullable','string'],

            'supplier_name'  => ['required','string'],
            'invoice_no'     => ['required','string'],
            'unit_price'     => ['nullable','numeric'],
            'dye_lot'        => ['nullable','string'],
            'location'       => ['nullable','string'],
            'SONumber'       => ['nullable','string'],
        ]);

        // parse date (รับ d/m/Y หรือ d-m-Y)
        $dt = \DateTime::createFromFormat('d/m/Y', str_replace('-', '/', $request->dt));
        $dateYmd = $dt ? $dt->format('Y-m-d') : now()->toDateString();

        // เก็บ header ลง session (ใช้ตอน nextData)
        session()->put([
            'dt'            => $dateYmd,
            'fabricId'      => $request->fabricId,
            'fabricStruct'  => $request->fabricStruct,
            'fabricPattern' => $request->fabricPattern,
            'fabricW'       => $request->fabricW,
            'customer'      => $request->customer,
            'supplier_name' => $request->supplier_name,
            'invoice_no'    => $request->invoice_no,
            'unit_price'    => $request->unit_price,
            'dye_lot'       => $request->dye_lot,
            'location'      => $request->location,
            'SONumber'      => $request->SONumber,
        ]);

        // เตรียม refId
        if (!session()->has('refId')) {
            $bytes  = random_bytes(32);
            $base64 = base64_encode($bytes);
            $key    = str_replace('/', '', $base64);
            session()->put('refId', $key);
        }
        $refId = session('refId');

        // เตรียมข้อมูล insert ทีละพับ
        $arr = $request->input('sumYard', []); // [foldNo => yards]
        $toInsert = [];
        $countNew = 0;
        $sumNew   = 0;

        foreach ($arr as $foldNo => $yards) {
            if ($yards === '' || $yards === null) continue;
            if (!is_numeric($yards)) continue;
            $countNew++;
            $sumNew += (float)$yards;

            $toInsert[] = [
                'refId'         => $refId,
                'emp'           => auth()->user()->name,
                'fabricStruct'  => session('fabricStruct'),
                'fabricW'       => session('fabricW'),
                'fold'          => (int)$foldNo,
                'sumYard'       => (float)$yards,
                'createDate'    => session('dt'),
                'fabricPattern' => session('fabricPattern'),
                'customer'      => session('customer'),
                'fabricId'      => session('fabricId'),
                'supplier_name' => session('supplier_name'),
                'invoice_no'    => session('invoice_no'),
                'unit_price'    => session('unit_price'),
                'dye_lot'       => session('dye_lot'),
                'location'      => session('location'),
                'SONumber'      => session('SONumber'),
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // อัปเดตตัวนับบน session (แสดงผลในหน้า)
        $oldEnd = (int) session('endCount', 0);
        $oldSum = (float) session('sum', 0);
        session()->put('endCount', $oldEnd + $countNew);
        session()->put('sum',     $oldSum + $sumNew);

        // บันทึก batch → ชื่อตารางจริงคือ 'fabricimport' (ไม่มี s)
        if (!empty($toInsert)) {
            DB::table('fabricimport')->insert($toInsert);
        }

        if ($action === 'nextData') {
            return redirect()->route('fabricimport.create')
                ->with('ok', 'บันทึกรายการเพิ่มแล้ว (ยังไม่ปิดเอกสาร)');
        }

        if ($action === 'endData') {
            $gotoRef = $refId;

            session()->forget([
                'refId','endCount','sum','dt','fabricId','fabricStruct','fabricPattern','fabricW','customer',
                'supplier_name','invoice_no','unit_price','dye_lot','location','SONumber'
            ]);

            return redirect()->route('fabricimport.show', $gotoRef);
        }

        return back();
    }

    // ---------- แสดงรายการทั้งหมดใน refId เดียวกัน ----------
    public function show(string $refId)
    {
        $rows = Fabricimport::where('refId', $refId)
            ->orderBy('fold')
            ->get();

        abort_if($rows->isEmpty(), 404);

        $first = $rows->first();
        $header = [
            'refId'         => $refId,
            'createDate'    => $first->createDate, // แปลงรูปแบบใน blade ตามต้องการ
            'supplier_name' => $first->supplier_name,
            'invoice_no'    => $first->invoice_no,
            'unit_price'    => $first->unit_price,
            'dye_lot'       => $first->dye_lot,
            'location'      => $first->location,
            'SONumber'      => $first->SONumber,
            'emp'           => $first->emp,
            'fabricId'      => $first->fabricId,
            'fabricStruct'  => $first->fabricStruct,
            'fabricPattern' => $first->fabricPattern,
            'fabricW'       => $first->fabricW,
            'customer'      => $first->customer,
            'total_folds'   => $rows->count(),
            'total_yards'   => $rows->sum('sumYard'),
            'total_cost'    => $first->unit_price ? $rows->sum('sumYard') * $first->unit_price : null,
        ];

        return view('fabricimport.show', compact('rows','header'));
    }

    // ---------- list ตามใบ/เอกสาร (refId) ล่าสุด ----------
    public function index()
    {
        $list = Fabricimport::select(
                'refId',
                DB::raw('MIN(createDate) as date'),
                DB::raw('COUNT(*) as folds'),
                DB::raw('SUM(sumYard) as yards'),
                DB::raw('MAX(supplier_name) as supplier'),
                DB::raw('MAX(invoice_no) as invoice_no')
            )
            ->groupBy('refId')
            ->orderByDesc('date')
            ->paginate(20);

        return view('fabricimport.index', compact('list'));
    }

    // ================== ตรวจสอบ “คีย์ผ้าซื้อเข้า” (สรุปเป็นชุด ๆ) ==================
    public function checkIndex(Request $request)
    {
        $perPage = 500;
        $page    = max(1, (int)$request->query('page', 1));

        // นับจำนวนกลุ่ม (refId ที่ไม่ซ้ำ) → ตารางจริง 'fabricimport'
        $totalGroups = DB::table('fabricimport')->distinct('refId')->count('refId');

        // 1) หา refId ของหน้านี้ (ใหม่→เก่า โดยอิง MAX(id))
        $refChunk = DB::table('fabricimport')
            ->select('refId', DB::raw('MAX(id) AS last_id'))
            ->groupBy('refId')
            ->orderByDesc(DB::raw('MAX(id)'))
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        if ($refChunk->isEmpty()) {
            $paginator = new LengthAwarePaginator(
                collect(),
                $totalGroups,
                $perPage,
                $page,
                ['path' => route('fabricimport.check.index')]
            );
            return view('fabricimport.check.index', ['rows' => $paginator]);
        }

        $refIds    = $refChunk->pluck('refId')->all();
        $lastIdMap = $refChunk->pluck('last_id', 'refId'); // [refId => last_id]

        // 2) สรุปเฉพาะกลุ่มในหน้านี้
        $rawSummary = DB::table('fabricimport as f')
            ->select([
                'f.refId',
                DB::raw('COUNT(*)             AS folds'),
                DB::raw('SUM(f.sumYard)       AS yards'),
                DB::raw('MAX(f.createDate)    AS key_date'),
                DB::raw('MAX(f.emp)           AS emp'),
                DB::raw('MAX(f.customer)      AS customer'),
                DB::raw('MAX(f.fabricId)      AS fabricId'),
                DB::raw('MAX(f.fabricStruct)  AS fabricStruct'),
                DB::raw('MAX(f.fabricPattern) AS fabricPattern'),
                DB::raw('MAX(f.fabricW)       AS fabricW'),
                DB::raw('MAX(f.supplier_name) AS supplier_name'),
                DB::raw('MAX(f.invoice_no)    AS invoice_no'),
                DB::raw('MAX(f.unit_price)    AS unit_price'),
                DB::raw('MAX(f.SONumber)      AS SONumber'),
            ])
            ->whereIn('f.refId', $refIds)
            ->groupBy('f.refId')
            ->get();

        $rows = $rawSummary->map(function ($row) use ($lastIdMap) {
                $row->last_id = $lastIdMap[$row->refId] ?? null;
                return $row;
            })
            ->sortByDesc('last_id')
            ->values();

        $paginator = new LengthAwarePaginator(
            $rows,
            $totalGroups,
            $perPage,
            $page,
            ['path' => route('fabricimport.check.index')]
        );

        return view('fabricimport.check.index', ['rows' => $paginator]);
    }

    public function checkShow($refId)
    {
        $items = Fabricimport::where('refId', $refId)
            ->orderByRaw('CAST(fold AS UNSIGNED) ASC')
            ->get();

        if ($items->isEmpty()) {
            abort(404, 'ไม่พบข้อมูลชุดนี้');
        }

        $first = $items->first();
        $totalYards = $items->sum('sumYard');
        $unitPrice  = $first->unit_price;

        $header = (object)[
            'refId'         => $refId,
            'emp'           => $first->emp,
            'customer'      => $first->customer,
            'fabricId'      => $first->fabricId,
            'fabricStruct'  => $first->fabricStruct,
            'fabricPattern' => $first->fabricPattern,
            'fabricW'       => $first->fabricW,
            'supplier_name' => $first->supplier_name,
            'invoice_no'    => $first->invoice_no,
            'unit_price'    => $unitPrice,
            'dye_lot'       => $first->dye_lot,
            'location'      => $first->location,
            'SONumber'      => $first->SONumber,
            'folds'         => $items->count(),
            'yards'         => $totalYards,
            'total_cost'    => $unitPrice ? ($totalYards * $unitPrice) : null,
            'key_date'      => $first->createDate ?? $first->created_at,
        ];

        return view('fabricimport.check.show', compact('header', 'items', 'refId'));
    }

    public function checkDestroyItem(Request $request, $refId, $id)
    {
        $row = Fabricimport::where('refId', $refId)->where('id', $id)->firstOrFail();
        $row->delete();

        return redirect()
            ->route('fabricimport.check.show', $refId)
            ->with('status', "ลบพับที่ {$row->fold} แล้ว");
    }

    public function checkDestroy($refId)
    {
        $count = Fabricimport::where('refId', $refId)->count();
        Fabricimport::where('refId', $refId)->delete();

        return redirect()
            ->route('fabricimport.check.index')
            ->with('status', "ลบข้อมูลทั้งชุด จำนวน {$count} แถวเรียบร้อย");
    }
}
