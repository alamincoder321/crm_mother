<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use App\Models\Bank;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
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

    public function index()
    {
        Session::forget('panel');
        Session::put('panel', 'dashboard');
        return view('pages.dashboard');
    }

    public function panel($panel)
    {
        Session::forget('panel');
        Session::put('panel', $panel);
        return view('pages.dashboard');
    }

    public function businessInfo()
    {
        $data['cashBalance'] = AccountHead::getCashBalance((object)[], date('Y-m-d'));
        $bankAccounts = Bank::getBankBalance((object)[], date('Y-m-d'));
        $data['bankBalance'] = collect($bankAccounts)->reduce(function ($pre, $cur) {
            return $pre + $cur->currentbalance;
        }, 0);
        return view('pages.businessInfo', $data);
    }

    public function getBusinessInfo(Request $request)
    {
        $data['todaySale'] = Sale::where('branch_id', $this->branchId)
            ->where('date', date('Y-m-d'))
            ->where('status', 'a')
            ->sum('total');
        $data['monthlySale'] = Sale::where('branch_id', $this->branchId)
            ->where(DB::raw("DATE_FORMAT(date, '%Y-%m')"), date('Y-m'))
            ->where('status', 'a')
            ->sum('total');
        $data['yearlySale'] = Sale::where('branch_id', $this->branchId)
            ->where(DB::raw("DATE_FORMAT(date, '%Y')"), date('Y'))
            ->where('status', 'a')
            ->sum('total');
        $data['totalSale'] = Sale::where('branch_id', $this->branchId)
            ->where('status', 'a')
            ->sum('total');

        //collection
        $today = date("Y-m-d");
        $data['collection'] = DB::select("select
                                (select ifnull(sum(sm.paid), 0) from sales sm
                                where sm.status = 'a'
                                and sm.branch_id = '$this->branchId'
                                and DATE_FORMAT(sm.date, '%Y-%m-%d') = '$today') as salePaid,

                                (select ifnull(sum(cr.amount), 0) from receives cr
                                where cr.status = 'a'
                                and cr.type = 'customer'
                                and cr.branch_id = '$this->branchId'
                                and DATE_FORMAT(cr.date, '%Y-%m-%d') = '$today') as customerReceive,

                                (select ifnull(sum(tr.amount), 0) from transactions tr
                                where tr.status = 'a'
                                and tr.type = 'income'
                                and tr.branch_id = '$this->branchId'
                                and DATE_FORMAT(tr.date, '%Y-%m-%d') = '$today') as income,
                                (select salePaid + customerReceive + income) as collection")[0]->collection;

        $data['customerDue'] = collect(Customer::customerDue([]))->sum('due');
        $data['supplierDue'] = collect(Supplier::supplierDue([]))->sum('due');
        $data['stockBalance'] = collect(Product::stock([]))->sum('stock_value');
        $data['expense'] = Transaction::where('branch_id', $this->branchId)
            ->where('type', 'expense')
            ->where('status', 'a')
            ->sum('amount');
        $data['income'] = Transaction::where('branch_id', $this->branchId)
            ->where('type', 'income')
            ->where('status', 'a')
            ->sum('amount');

        return response()->json($data);
    }

    // admin logout
    public function Logout()
    {
        try {
            // UserActivity::create([
            //     'user_id' => Auth::user()->id,
            //     'page_name' => 'Dashboard',
            //     'ip_address' => request()->ip(),
            //     'login_time' => Carbon::now(),
            //     'logout_time' => Carbon::now(),
            //     'branch_id' => $this->branchId,
            // ]);
            Auth::guard('web')->logout();
            Session::forget(['branch', 'panel']);
            Session::flash('success', 'Logout successfully');
            return redirect('/');
        } catch (\Throwable $e) {
            return send_error('Something went wrong', $e->getMessage());
        }
    }

    // change branch
    // public function branch($id)
    // {
    //     Session::forget('branch');
    //     $this->branchset($id);
    //     return back();
    // }

    // branch set on session
    protected function branchset($id)
    {
        $branch = Branch::find($id);
        Session::put('branch', $branch);
        return true;
    }

    public function companyProfile()
    {
        return view('pages.control.companyProfile');
    }

    public function getcompanyProfile()
    {
        return response()->json(CompanyProfile::first());
    }

    public function updatecompanyProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'title' => 'required',
            'phone' => 'required'
        ]);

        try {
            $data = CompanyProfile::first();
            if ($request->logo == 'null') {
                if (File::exists($data->logo)) {
                    File::delete($data->logo);
                }
                $data->logo = NULL;
            }
            if ($request->favicon == 'null') {
                if (File::exists($data->favicon)) {
                    File::delete($data->favicon);
                }
                $data->favicon = NULL;
            }
            $dataKeys = $request->except('id', 'logo', 'favicon');
            foreach ($dataKeys as $key => $value) {
                $data[$key] = $value;
            }

            if ($request->hasFile('logo')) {
                if (File::exists($data->logo)) {
                    File::delete($data->logo);
                }
                $data->logo = imageUpload($request, 'logo', 'uploads/logo', 'logo');
            }
            if ($request->favicon == NULL) {
                if (File::exists($data->favicon)) {
                    File::delete($data->favicon);
                }
                $data->favicon = NULL;
            }
            if ($request->hasFile('favicon')) {
                if (File::exists($data->favicon)) {
                    File::delete($data->favicon);
                }
                $data->favicon = imageUpload($request, 'favicon', 'uploads/favicon', 'favicon');
            }
            $data->updated_by = $this->userId;
            $data->updated_at = Carbon::now();
            $data->ipAddress = request()->ip();
            $data->update();

            return response()->json(['status' => true, 'message' => 'Company profile update successfully']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong! ' . $th->getMessage()]);
        }
    }

    public function getHeaderInfo()
    {
        return view('layouts.headerInfo');
    }
}
