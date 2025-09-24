<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use Illuminate\Http\Request;

class ReportController extends Controller
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

    public function profitLoss()
    {
        if (!checkAccess('profitLost')) {
            return view('error.403');
        }
        return view('pages.report.profitloss');
    }

    public function getOtherExpenseIncome(Request $request)
    {
        $reports = AccountHead::getOtherExpenseIncome($request);
        return response()->json($reports);
    }
}
