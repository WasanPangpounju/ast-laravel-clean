<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
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
//                $employees = Employee::latest()->paginate(5);
//        return view('employee.index',compact('employees'))
//            ->with('i', (request()->input('page', 1) - 1) * 5);
        $employees = Employee::all();

        return view('employees.index' ,compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employees.create');
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

        $validatedData = $request->validate([
            'empID' => 'required',
            'name' => 'required|max:255',
            'ssn' => 'required',
            'tel' => 'required',
            'gender' => 'required',
            'berthdate' => 'required',
            'address' => 'required',
            'description' => 'required',
            'department' => 'required',
            'jobdescription' => 'required',
            'manager' => 'required',
            'createEmployeeBy' => 'required',
        ]);

        $show = Employee::create($validatedData);
   
        return redirect('/employee')->with('success', 'Employee is successfully saved');

//        Employee::create($request->all());

//        return view('employees.index');


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
}
