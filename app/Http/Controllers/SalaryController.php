<?php

namespace App\Http\Controllers;

use App\Models\SalaryMaster;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    protected $userId;
    protected $branchId;
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->branchId = $request->session()->get('branch')->id;
            $this->userId = auth()->user()->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $employees = SalaryMaster::with('adUser', 'upUser', 'department', 'designation')
            ->where('role', 'employee')
            ->where('branch_id', $this->branchId);
        if (!empty($request->supplierId)) {
            $employees = $employees->where('id', $request->supplierId);
        }
        if (!empty($request->departmentId)) {
            $employees = $employees->where('department_id', $request->departmentId);
        }
        if (!empty($request->designationId)) {
            $employees = $employees->where('designation_id', $request->designationId);
        }
        if (!empty($request->status)) {
            $employees = $employees->where('status', $request->status);
        }
        $employees = $employees->latest()->get();
        return response()->json($employees);
    }

    public function create()
    {
        return view('pages.hr.salary.create');
    }

    public function employeeList()
    {
        return view('pages.hr.salary.index');
    }
}
