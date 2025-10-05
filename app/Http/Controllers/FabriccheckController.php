<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\stockfabric; // <- ใช้งานตารางคีย์ผ้าเข้า

class FabriccheckController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // กรองพื้นฐาน (ถ้าภายหลังอยากเพิ่มค้นหา ค่อยต่อยอดได้)
        $query = stockfabric::query()->orderByDesc('id');

        // ดึง 500 รายการล่าสุดด้วย cursor pagination
        // ชื่อพารามิเตอร์จะเป็น ?cursor=xxxx โดยอัตโนมัติ
        $rows = $query->cursorPaginate(500);

        return view('fabriccheck.index', [
            'rows' => $rows,
        ]);
    }
}
