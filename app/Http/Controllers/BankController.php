<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
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
        $bank = Bank::with('adUser', 'upUser', 'deUser')
            ->where('branch_id', $this->branchId)
            ->latest()->get()->map(function ($item) {
                $item->display_name = $item->name . ' - ' . $item->number . ' - ' . $item->bank_name;
                return $item;
            });

        return response()->json($bank);
    }

    public function create()
    {
        return view('pages.account.bank');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'number' => 'required',
            'type' => 'required',
            'balance' => 'required'
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        $check = Bank::where('bank_name', $request->bank_name)->where('number', $request->number)->where('branch_id', $this->branchId)->first();
        if (!empty($check)) {
            return send_error("Bank already exists", null, 422);
        }
        try {
            $check = Bank::where('name', $request->name)->withTrashed()->first();
            if (!empty($check) && $check->deleted_at != NULL) {
                $check->status = 'a';
                $check->deleted_by = NULL;
                $check->deleted_at = NULL;
                $check->update();
            } else {
                $data = new Bank();
                $dataKey = $request->except('id');
                foreach ($dataKey as $key => $value) {
                    $data[$key] = $value;
                }
                $data->created_by = $this->userId;
                $data->branch_id  = $this->branchId;
                $data->ipAddress  = request()->ip();
                $data->save();
            }

            return response()->json(['status' => true, 'message' => "Bank has created successfully"]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'number' => 'required',
            'type' => 'required',
            'balance' => 'required'
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        $check = Bank::where('id', '!=', $request->id)->where('bank_name', $request->bank_name)->where('number', $request->number)->where('branch_id', $this->branchId)->first();
        if (!empty($check)) {
            return send_error("Bank already exists", null, 422);
        }
        try {
            $data = Bank::find($request->id);
            $dataKey = $request->except('id');
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            $data->updated_at = Carbon::now();
            $data->updated_by = $this->userId;
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            $data->update();

            return response()->json(['status' => true, 'message' => "Bank has updated successfully"]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Bank::find($request->id);
            $data->deleted_by = $this->userId;
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            $data->delete();
            return response()->json(['status' => true, 'message' => "Bank has deleted successfully"]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }
}
