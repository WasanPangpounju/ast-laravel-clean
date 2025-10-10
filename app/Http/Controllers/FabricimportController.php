// app/Http/Controllers/FabricImportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\FabricImport;
use App\Models\customer;
use App\Models\astpurchaseorder; // สำหรับ datalist เดิม
use App\Models\fabricast;        // เอา fabric_w ประกอบ (ถ้าใช้)

class FabricImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // หน้าเลือกข้อมูล + ช่องคีย์ 160 ช่อง
    public function create()
    {
        // ดึง orders + fabric_w แบบเดียวกับ inventory.create เพื่อใช้ datalist
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

    // เก็บรอบ/ปิดรอบ
    public function store(Request $request)
    {
        $action = $request->input('submit'); // nextData | endData

        // validate header/spec
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

        // parse date
        $dt = \DateTime::createFromFormat('d/m/Y', str_replace('-', '/', $request->dt));
        $dateYmd = $dt ? $dt->format('Y-m-d') : now()->toDateString();

        // เก็บลง session เพื่อใช้ต่อเนื่องตอนกด nextData
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

        // เตรียม refId (เหมือนเดิม: สุ่ม base64 ตัด '/')
        if (!session()->has('refId')) {
            $bytes  = random_bytes(32);
            $base64 = base64_encode($bytes);
            $key    = str_replace('/', '', $base64);
            session()->put('refId', $key);
        }
        $refId = session('refId');

        // อ่านค่าช่องคีย์
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

        // บันทึก batch
        if (!empty($toInsert)) {
            DB::table('fabricimports')->insert($toInsert);
        }

        if ($action === 'nextData') {
            return redirect()->route('fabricimport.create')->with('ok', 'บันทึกรายการเพิ่มแล้ว (ยังไม่ปิดเอกสาร)');
        }

        if ($action === 'endData') {
            // เคลียร์รอบ session และไปหน้า show รายการของ refId นี้
            $gotoRef = $refId;

            session()->forget([
                'refId','endCount','sum','dt','fabricId','fabricStruct','fabricPattern','fabricW','customer',
                'supplier_name','invoice_no','unit_price','dye_lot','location','SONumber'
            ]);

            return redirect()->route('fabricimport.show', $gotoRef);
        }

        return back();
    }

    // แสดงรายการทั้งหมดใน refId เดียวกัน (เหมือนใบซื้อหนึ่งใบ)
    public function show(string $refId)
    {
        $rows = FabricImport::where('refId', $refId)->orderBy('fold')->get();

        abort_if($rows->isEmpty(), 404);

        // header สรุปรวม
        $header = [
            'refId'         => $refId,
            'createDate'    => optional($rows->first())->createDate?->format('d/m/Y'),
            'supplier_name' => $rows->first()->supplier_name,
            'invoice_no'    => $rows->first()->invoice_no,
            'unit_price'    => $rows->first()->unit_price,
            'dye_lot'       => $rows->first()->dye_lot,
            'location'      => $rows->first()->location,
            'SONumber'      => $rows->first()->SONumber,
            'emp'           => $rows->first()->emp,
            'fabricId'      => $rows->first()->fabricId,
            'fabricStruct'  => $rows->first()->fabricStruct,
            'fabricPattern' => $rows->first()->fabricPattern,
            'fabricW'       => $rows->first()->fabricW,
            'customer'      => $rows->first()->customer,
            'total_folds'   => $rows->count(),
            'total_yards'   => $rows->sum('sumYard'),
            'total_cost'    => $rows->first()->unit_price ? $rows->sum('sumYard') * $rows->first()->unit_price : null,
        ];

        return view('fabricimport.show', compact('rows','header'));
    }

    public function index()
    {
        // list ตามใบ/เอกสาร (refId) ล่าสุด
        $list = FabricImport::select('refId',
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
}
