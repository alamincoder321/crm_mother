<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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

    public function updatecompanyProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'title' => 'required',
            'phone' => 'required'
        ]);

        try {
            $data = CompanyProfile::first();
            if($request->logo == 'null'){
                if (File::exists($data->logo)) {
                    File::delete($data->logo);
                }
                $data->logo = NULL;
            }
            if($request->favicon == 'null'){
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
            if($request->favicon == NULL){
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
