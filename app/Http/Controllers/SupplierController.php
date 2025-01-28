<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
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
        $suppliers = Supplier::with('adUser', 'upUser', 'area')->where('branch_id', $this->branchId);
        if (!empty($request->supplierId)) {
            $suppliers = $suppliers->where('id', $request->supplierId);
        }
        if (!empty($request->areaId)) {
            $suppliers = $suppliers->where('area_id', $request->areaId);
        }
        $suppliers = $suppliers->latest()->get()->map(function ($item) {
            $item->display_name = $item->name . ' - ' . $item->phone . ' - ' . $item->code;
            return $item;
        });
        return response()->json($suppliers);
    }

    public function create()
    {
        return view('pages.control.supplier.create');
    }

    public function supplierList()
    {
        return view('pages.control.supplier.index');
    }


    public function store(Request $request)
    {
        $branchId = $this->branchId;
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'phone' => [
                'required',
                Rule::unique('suppliers')
                    ->where(function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->whereNull('deleted_at'),
            ],
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        try {
            $check = Supplier::where('phone', $request->phone)->withTrashed()->first();
            if (!empty($check) && $check->deleted_at != NULL) {
                $check->status = 'a';
                $check->deleted_at = NULL;
                $check->update();
            } else {
                $data = new Supplier();
                $data->code = generateCode('Supplier', 'S', $this->branchId);
                $dataKey = $request->except('id', 'image');
                foreach ($dataKey as $key => $value) {
                    $data[$key] = $value;
                }
                if ($request->hasFile('image')) {
                    $data->image = imageUpload($request, 'image', 'uploads/supplier', $data->code . '_' . $this->branchId);
                }
                $data->created_by = $this->userId;
                $data->ipAddress = request()->ip();
                $data->branch_id = $this->branchId;
                $data->save();
            }

            return response()->json(['status' => true, 'message' => "Supplier has created successfully"]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(Request $request)
    {
        $branchId = $this->branchId;
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'phone' => [
                'required',
                Rule::unique('suppliers')
                    ->ignore($request->id)
                    ->where(function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->whereNull('deleted_at'),
            ],
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        try {
            $data = Supplier::find($request->id);
            $dataKey = $request->except('id', 'image');
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image = imageUpload($request, 'image', 'uploads/supplier', $data->code . '_' . $this->branchId);
            }
            $data->updated_by = $this->userId;
            $data->updated_at = Carbon::now();
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            $data->update();

            return response()->json(['status' => true, 'message' => "Supplier has updated successfully"]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Supplier::find($request->id);
            if (File::exists($data->image)) {
                File::delete($data->image);
            }
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            $data->delete();
            return response()->json(['status' => true, 'message' => "Supplier has deleted successfully"]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }
}
