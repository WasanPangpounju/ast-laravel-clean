<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FabriccheckController extends Controller
{
    public function index()
    {
        // ส่งค่ากลวง ๆ ให้ view เพื่อไม่ให้ error
        return view('fabricoutcheck.index', [
            'importorder'       => collect(),
            'stockFabricStruct' => collect(),
            'allfabricout'      => collect(),
        ]);
    }

    public function store(Request $request)
    {
        // ชั่วคราว: แค่ย้อนกลับไปหน้า index
        return redirect()->route('fabriccheck.index');
    }
}
