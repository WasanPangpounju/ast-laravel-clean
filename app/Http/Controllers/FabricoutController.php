<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\fabricout;
use App\Models\AstPurchaseorder;
use App\Models\FabricAst;
use App\Models\FabricAststructure;
use App\Models\stockfabric;

use Codedge\Fpdf\Fpdf\Fpdf;

class FabricoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* ==========================
     * List หน้า index
     * ========================== */
    public function index()
    {
        try {
            $records = fabricout::groupBy([
                    'vatType','vatNo','fabricStruct','no','refId',
                    'customerName','receiveName','fabricPattern','fabricW'
                ])
                ->selectRaw('
                    refId, vatNo, vatType, fabricStruct, fabricPattern, fabricW,
                    receiveName, no, customerName,
                    COUNT(fold) as foldCount,
                    SUM(sumYard) as sumYardSum,
                    MAX(createDate) as lastDate
                ')
                ->orderBy('vatNo', 'DESC')
                ->get();

            $sumfabricout = $records->isEmpty() ? collect() : $records;
            $nofind       = fabricout::select('no')->groupBy('no')->get();
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาดในการดึงข้อมูล: '.$e->getMessage());
        }

        return view('fabricout.index', compact('sumfabricout','nofind'));
    }

    /* ใช้ใน Blade เดิมบางหน้า */
    public static function findNo()
    {
        return fabricout::select('no')->groupBy('no')->get();
    }

    /* ==========================
     * Create form
     * ========================== */
    private function stockPickerOptions()
    {
        $tbl = (new stockfabric())->getTable();

        return DB::table($tbl)
            ->select('customer','fabricStruct','fabricPattern','fabricW')
            ->whereNotNull('customer')->where('customer','<>','')
            ->whereNotNull('fabricStruct')->where('fabricStruct','<>','')
            ->whereNotNull('fabricPattern')->where('fabricPattern','<>','')
            ->whereNotNull('fabricW')->where('fabricW','<>','')
            ->groupBy('customer','fabricStruct','fabricPattern','fabricW')
            ->orderBy('customer')
            ->limit(2000)
            ->get();
    }

    public function create()
    {
        if ((int)session()->get('endCount', 0) <= 0) {
            session()->forget([
                'endCount','dt','customerName','receiveName','comment','receiveType','orderId',
                'fabricStruct','fabricPattern','fabricW','customerReplace','fabricStructReplace',
                'vatNo','vatType','purchaseOrder','fabricout_group'
            ]);
        }

        $ecp = FabricAststructure::select('purchaseOrder AS id')
            ->where('yarnWRatio2', 'อนุมัติให้ผลิต')->get();

        $orders = AstPurchaseorder::select('id','customerName','fabricId','fabricStructure','orderSumYard','purchaseOrder')
            ->whereIn('id', $ecp)->orderBy('customerName')->get();

        $lastVat = fabricout::groupBy('vatType')
            ->select('vatType', DB::raw('MAX(vatNo) as max_no'))
            ->get();

        $vatA='1001'; $vatB='1001'; $vatC='1001';
        foreach ($lastVat as $v) {
            if ($v->vatType==='A') $vatA = $v->max_no ? $v->max_no+1 : '1001';
            if ($v->vatType==='B') $vatB = $v->max_no ? $v->max_no+1 : '1001';
            if ($v->vatType==='C') $vatC = $v->max_no ? $v->max_no+1 : '1001';
        }

        $lastRecord = fabricout::orderBy('no','DESC')->first();
        $no = $lastRecord ? ($lastRecord->no + 1) : 1001;
        if (!session()->has('no')) session()->put('no', $no);

        $customers  = Customer::orderBy('name')->get();
        $order_id   = '';
        $customer_name = '';
        $fabric_struct = '';
        $stockLots  = $this->stockPickerOptions();

        $fg = session('fabricout_group', []);
        $selStockCustomer = $fg['stockCustomer']      ?? null;
        $selStockStruct   = $fg['stockFabricStruct']  ?? null;
        $selStockPattern  = $fg['stockFabricPattern'] ?? null;
        $selStockW        = $fg['stockFabricW']       ?? null;

        return view('fabricout.create', compact(
            'customers','order_id','customer_name','fabric_struct',
            'orders','vatA','vatB','vatC','stockLots',
            'selStockCustomer','selStockStruct','selStockPattern','selStockW'
        ));
    }

    /* ==========================
     * Store / Search / Print
     * ========================== */
    public function store(Request $request)
    {
        /* ---------- 0) สั่งใบส่ง (PDF) ---------- */
        if ($request->filled('submit') && $request->submit === 'submitfabricout') {
            // รับคีย์ให้ครบจากฟอร์ม (แนะนำให้ส่งมาใน Blade)
            $no      = $request->integer('fabricout_no');  // fallback ถ้าไม่ส่ง vatType/vatNo
            $vatType = $request->input('vat_type');        // A/B/C
            $vatNo   = $request->input('vat_no');          // running no
            $refId   = $request->input('ref_id');          // ถ้ามีจะล็อกชุดแม่นขึ้น

            if ((!$vatType || !$vatNo) && !$no) {
                return back()->with('error','ไม่พบคีย์สำหรับพิมพ์ใบส่ง');
            }
            return $this->printDeliveryPdfSmart($no, $vatType, $vatNo, $refId);
        }

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

            $lastRecord = Fabricout::orderBy('no','DESC')->first();
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
                ->select('vatType', DB::raw('MAX(vatNo) as max_no'))->get();
            $vatA='1001'; $vatB='1001'; $vatC='1001';
            foreach ($lastVat as $v) {
                if ($v->vatType==='A') $vatA = $v->max_no ? $v->max_no+1 : '1001';
                if ($v->vatType==='B') $vatB = $v->max_no ? $v->max_no+1 : '1001';
                if ($v->vatType==='C') $vatC = $v->max_no ? $v->max_no+1 : '1001';
            }

            $ecp    = FabricAststructure::select('purchaseOrder AS id')->where('yarnWRatio2','อนุมัติให้ผลิต')->get();
            $orders = AstPurchaseorder::select('id','customerName','fabricId','fabricStructure','orderSumYard','purchaseOrder')
                        ->whereIn('id',$ecp)->orderBy('customerName')->get();

            $customer_name = session('customerName','');
            $fabric_struct = '';
            $stockLots     = $this->stockPickerOptions();

            return view('fabricout.create', compact(
                'customers','order_id','customer_name','fabric_struct',
                'orders','vatA','vatB','vatC','stockLots'
            ));
        }

        /* ---------- 3) ตั้งค่าพื้นฐาน + snapshot group key ---------- */
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
            // คีย์ตัดสต็อก (fallback ถ้าไม่ได้เลือกแยก)
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

        // พับรอบนี้
        $arr_data = [];
        foreach ($request->input('sumYard', []) as $v) {
            if ($v !== '' && $v !== null) $arr_data[] = $v;
        }

        // บันทึก 1 ก้อน
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

            // กุญแจตัดสต็อก
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
                    // ตัดสต็อก
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

        return back()->with('error','คำสั่งไม่ถูกต้อง');
    }

    /* ==========================
     * พิมพ์ใบส่ง: เลือกชุดอย่างแม่น
     * ========================== */
    private function printDeliveryPdfSmart(?int $no, ?string $vatType, ?string $vatNo, ?string $refId)
    {
        // สร้างคิวรีพื้นฐาน
        $base = fabricout::query();
        if ($vatType && $vatNo) {
            $base->where('vatType', $vatType)->where('vatNo', $vatNo);
        }
        if ($refId) {
            $base->where('refId', $refId);
        }
        if ((!$vatType || !$vatNo) && $no) {
            $base->where('no', $no);
        }

        // หัวบิล
        $records = (clone $base)
            ->groupBy([
                'vatType','vatNo','fabricStruct','fabricPattern','fabricW',
                'no','refId','customerName','receiveName',
                'customerReplace','fabricStructReplace','comment'
            ])
            ->selectRaw("
                vatType, vatNo, fabricStruct, fabricPattern, fabricW,
                no, refId, customerName, receiveName,
                customerReplace, fabricStructReplace, comment,
                COUNT(fold) AS foldCount,
                SUM(sumYard) AS sumYardSum,
                MAX(createDate) AS lastDate
            ")
            ->get();

        if ($records->isEmpty()) {
            return back()->with('error', 'ไม่พบข้อมูลใบส่งของสำหรับคีย์ที่เลือก');
        }

        // รายการพับ
        $orders = (clone $base)
            ->selectRaw('no, fold, sumYard')
            ->orderBy('fold', 'asc')
            ->get();

        return $this->renderDeliveryPdf($records[0], $orders);
    }

    private function renderDeliveryPdf($head, $orders)
    {
        $pageCount = max(1, (int)ceil(($head->foldCount ?? 0) / 160));
        $vatType   = $head->vatType;
        $vatNo     = $head->vatNo;

        $this->fpdf = new Fpdf;
        $this->fpdf->AddFont('THSarabunNew','', 'THSarabunNew.php');
        $this->fpdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');

        // ===== ส่วนหัวเอกสาร =====
        $pageOffset = 0;
        for ($page = 1; $page <= $pageCount; $page++) {

            $this->fpdf->AddPage();

            // Header (ย่อจากโค้ดเดิมของคุณ)
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

            // เตรียม pattern: ตัดวงเล็บ + จับ n/m
            $fabricPattern = (string)$head->fabricPattern;
            $cleanPattern  = preg_replace('/\(.*?\)/', '', $fabricPattern);
            $cleanPattern  = trim($cleanPattern);
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

            // ===== ตาราง 8 คอลัมน์ x 20 แถว =====
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

            $y   = $yStart;
            $sum = array_fill(1, 8, 0.0);

            for ($row = 0; $row < 20; $row++) {
                for ($col = 1; $col <= 8; $col++) {
                    $idx = $pageOffset + ($col - 1) * 20 + $row;

                    // กล่องลำดับ
                    $this->fpdf->SetXY($xStart + ($colWidth - 6) * (3 * ($col - 1)), $y);
                    $this->fpdf->Cell($colWidth - 5, 8, iconv('UTF-8','cp874',''), 1);

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

            // รวมคอลัมน์
            $this->fpdf->SetFont('THSarabunNew','',12);
            for ($col = 1; $col <= 8; $col++) {
                $xCol = $xStart + ($colWidth - 6) * (3 * ($col - 1));
                $this->fpdf->SetXY($xCol, $y);
                $this->fpdf->Cell($colWidth - 5, 5, iconv('UTF-8','cp874','รวม'), 1);
                $this->fpdf->SetXY($xCol + ($colWidth - 5), $y);
                $this->fpdf->Cell($colWidth, 5, iconv('UTF-8','cp874', (string)$sum[$col]), 1);
            }

            // สรุปรวมด้านล่าง
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

            $pageOffset += 160;
        }

        $pdfBinary = $this->fpdf->Output('S');
        $filename  = "Delivery_{$vatType}-{$vatNo}.pdf";

        return response($pdfBinary, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'Cache-Control'       => 'private, max-age=0, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }

    /* ==========================
     * Create records helper
     * ========================== */
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
        ?string $stockCustomer = null,
        ?string $stockFabricStruct = null,
        ?string $stockFabricPattern = null,
        ?string $stockFabricW = null
    ) {
        $c = $start;
        foreach ($data as $yd) {
            fabricout::create([
                'refId'         => $refId,
                'emp'           => $emp,

                'orderId'       => $orderId,
                'purchaseOrder' => $purchaseOrder,

                // กุญแจตัดสต็อก (fallback)
                'stockCustomer'      => $stockCustomer      ?: ($customerName ?: 'AST'),
                'stockFabricStruct'  => $stockFabricStruct  ?: $fabricStruct,
                'stockFabricPattern' => $stockFabricPattern ?: $fabricPattern,
                'stockFabricW'       => $stockFabricW       ?: $fabricW,

                // แสดงผล
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

    /* ==========================
     * ลบทั้งชุดตาม refId
     * ========================== */
    public function destroy($id)
    {
        fabricout::where('refId', $id)->delete();
        return $this->index();
    }
}
