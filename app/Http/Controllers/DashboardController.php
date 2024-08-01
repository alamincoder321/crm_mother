<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $branchId;
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->branchId = $request->session()->get('branch')->id;
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
}

