<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
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
        $employees = Employee::with('adUser', 'upUser', 'area', 'department', 'designation')
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
        $employees = $employees->latest()->get();
        return response()->json($employees);
    }

    public function create()
    {
        return view('pages.hr.employee.create');
    }

    public function supplierList()
    {
        return view('pages.hr.employee.index');
    }


    public function store(Request $request)
    {
        $branchId = $this->branchId;
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('suppliers')
                    ->where(function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->whereNull('deleted_at'),
            ],
            'department_id' => 'required',
            'designation_id' => 'required',
            'salary' => 'required',
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        try {
            $check = Employee::where('name', $request->name)->withTrashed()->first();
            if (!empty($check) && $check->deleted_at != NULL) {
                $check->status = 'a';
                $check->deleted_at = NULL;
                $check->update();
            } else {
                $data = new Employee();
                $data->code = generateCode('Supplier', 'S', $this->branchId);
                $dataKey = $request->except('id', 'image');
                foreach ($dataKey as $key => $value) {
                    $data[$key] = $value;
                }
                if ($request->hasFile('image')) {
                    $data->image = imageUpload($request, 'image', 'uploads/employee', $data->code . '_' . $this->branchId);
                }
                $data->created_by = $this->userId;
                $data->ipAddress = request()->ip();
                $data->branch_id = $this->branchId;
                $data->save();
            }

            return response()->json(['status' => true, 'message' => "Employee has created successfully"]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(Request $request)
    {
        $branchId = $this->branchId;
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('suppliers')
                    ->ignore($request->id)
                    ->where(function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->whereNull('deleted_at'),
            ],
            'department_id' => 'required',
            'designation_id' => 'required',
            'salary' => 'required',
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        try {
            $data = Employee::find($request->id);
            $dataKey = $request->except('id', 'image');
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image = imageUpload($request, 'image', 'uploads/employee', $data->code . '_' . $this->branchId);
            }
            $data->updated_by = $this->userId;
            $data->updated_at = Carbon::now();
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            $data->update();

            return response()->json(['status' => true, 'message' => "Employee has updated successfully"]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Employee::find($request->id);
            if (File::exists($data->image)) {
                File::delete($data->image);
            }
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            $data->delete();
            return response()->json(['status' => true, 'message' => "Employee has deleted successfully"]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }
}
