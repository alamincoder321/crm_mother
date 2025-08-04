@extends('master')

@php
$panel = session('panel');
@endphp

@section('title')
{{ucfirst($panel)}}
@endsection
@section('breadcrumb')
{{ucfirst($panel)}}
@endsection

@push('style')
<style>
    .displayFlex {
        transition: 1ms ease-in-out;
        height: 115px;
    }

    .displayFlex:hover {
        background: linear-gradient(45deg, #39bcf1, #bbabab70);
        color: #fff;
    }

    .displayFlex:hover .card-body i {
        border-color: #fff !important;
    }

    .displayFlex .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .displayFlex .card-body i {
        font-size: 25px;
        border: 1px solid gray;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 3px;
    }
</style>
@endpush

@section('content')
<div class="row">
    @if ($panel == 'dashboard' || $panel == '')
    <div class="col-12 col-md-8 offset-md-2 text-center">
        <img class="w-75" src="{{asset('backend')}}/img/header.png" alt="header logo" style="border-radius: 20px;border: 1px solid rgb(0, 126, 187);box-shadow: rgb(0, 126, 187) 0px 5px 0px 0px;">
    </div>

    <div class="col-md-12 my-3"></div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body p-3 text-center">
                        <h2 class="m-0">Cash Balance</h2>
                        <h3 class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body p-3 text-center">
                        <h2 class="m-0">Bank Balance</h2>
                        <h3 class="m-0">{{number_format($bankBalance, 2)}}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body p-3 text-center">
                        <h2 class="m-0">Total Balance</h2>
                        <h3 class="m-0">{{number_format($cashBalance->cashbalance + $bankBalance, 2)}}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-3">
                <a href="/panel/SalePanel">
                    <div class="card mb-3 panel" style="background: #a7ecfb;" onmouseover="this.style.background = '#7de6ff'" onmouseout="this.style.background = '#a7ecfb'">
                        <div class="card-body p-2 text-center">
                            <div class="icon">
                                <i class="bi bi-cart-dash"></i>
                            </div>
                            <p class="m-0">Sale Panel</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/panel/PurchasePanel">
                    <div class="card mb-3 panel" style="background: #a7ecfb;" onmouseover="this.style.background = '#7de6ff'" onmouseout="this.style.background = '#a7ecfb'">
                        <div class="card-body p-2 text-center">
                            <div class="icon">
                                <i class="bi bi-cart-plus"></i>
                            </div>
                            <p class="m-0">Purchase Panel</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/panel/AccountPanel">
                    <div class="card mb-3 panel" style="background: #a7ecfb;" onmouseover="this.style.background = '#7de6ff'" onmouseout="this.style.background = '#a7ecfb'">
                        <div class="card-body p-2 text-center">
                            <div class="icon">
                                <i class="bi bi-cash"></i>
                            </div>
                            <p class="m-0">Account Panel</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/panel/HRPanel">
                    <div class="card mb-3 panel" style="background: #a7ecfb;" onmouseover="this.style.background = '#7de6ff'" onmouseout="this.style.background = '#a7ecfb'">
                        <div class="card-body p-2 text-center">
                            <div class="icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <p class="m-0">HR Panel</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/panel/ReportPanel">
                    <div class="card mb-3 panel" style="background: #a7ecfb;" onmouseover="this.style.background = '#7de6ff'" onmouseout="this.style.background = '#a7ecfb'">
                        <div class="card-body p-2 text-center">
                            <div class="icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <p class="m-0">Report Panel</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/panel/ControlPanel">
                    <div class="card mb-3 panel" style="background: #a7ecfb;" onmouseover="this.style.background = '#7de6ff'" onmouseout="this.style.background = '#a7ecfb'">
                        <div class="card-body p-2 text-center">
                            <div class="icon">
                                <i class="bi bi-bank2"></i>
                            </div>
                            <p class="m-0">Control Panel</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/business-view">
                    <div class="card mb-3 panel" style="background: #a7ecfb;" onmouseover="this.style.background = '#7de6ff'" onmouseout="this.style.background = '#a7ecfb'">
                        <div class="card-body p-2 text-center">
                            <div class="icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <p class="m-0">Business Panel</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/logout">
                    <div class="card mb-3 panel" style="background: #a7ecfb;" onmouseover="this.style.background = '#7de6ff'" onmouseout="this.style.background = '#a7ecfb'">
                        <div class="card-body p-2 text-center">
                            <div class="icon">
                                <i class="bi bi-box-arrow-right"></i>
                            </div>
                            <p class="m-0">Logout</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @elseif($panel == 'SalePanel')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10 col-12 offset-md-1 mb-5">
                <div class="card mb-0" style="box-shadow: 0px 5px 1px 2px #058ed152;">
                    <div class="card-body p-3 text-center">
                        <h2 class="m-0">Sale Panel</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-cart-dash"></i>
                            <span>Sale Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-file-text"></i>
                            <span>Sale Record</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/sale-return">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-arrow-return-left"></i>
                            <span>SaleReturn Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/sale-return-record">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-file-text"></i>
                            <span>SaleReturn Record</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-file-plus"></i>
                            <span>Quotation Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-file-text"></i>
                            <span>Quotation Record</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/stock">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-list"></i>
                            <span>Stock Report</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @elseif($panel == 'PurchasePanel')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10 col-12 offset-md-1 mb-5">
                <div class="card mb-0" style="box-shadow: 0px 5px 1px 2px #058ed152;">
                    <div class="card-body p-3 text-center">
                        <h2 class="m-0">Purchase Panel</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-cart-plus"></i>
                            <span>Purchase Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-file-text"></i>
                            <span>Purchase Record</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/purchase-return">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-arrow-return-left"></i>
                            <span>Return Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/purchase-return-record">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-file-text"></i>
                            <span>Return Record</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/damage">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-virus"></i>
                            <span>Damage Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/damage-record">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-file-text"></i>
                            <span>Damage Record</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @elseif($panel == 'AccountPanel')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10 col-12 offset-md-1 mb-5">
                <div class="card mb-0" style="box-shadow: 0px 5px 1px 2px #058ed152;">
                    <div class="card-body p-3 text-center">
                        <h2 class="m-0">Account Panel</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/expense">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-clipboard-minus"></i>
                            <span>Expense Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/income">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-duffle"></i>
                            <span>Income Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/receive">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-cash-stack"></i>
                            <span>Receive</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/payment">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-person-workspace"></i>
                            <span>Payment</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/bankTransaction">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-bank"></i>
                            <span>Bank Transaction</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-list-ul"></i>
                            <span>Bank Transaction Record</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/accounthead">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-plus-circle"></i>
                            <span>AccountHead Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/bank">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-plus-circle"></i>
                            <span>Bank Entry</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @elseif($panel == 'ReportPanel')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10 col-12 offset-md-1 mb-5">
                <div class="card mb-0" style="box-shadow: 0px 5px 1px 2px #058ed152;">
                    <div class="card-body p-3 text-center">
                        <h2 class="m-0">Report Panel</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/profitLoss">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-journal-text"></i>
                            <span>Profit/Loss</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/cashLedger">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-list"></i>
                            <span>Cash Ledger</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/bankLedger">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-list"></i>
                            <span>Bank Ledger</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/supplierDue">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-cash"></i>
                            <span>Supplier Due</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/supplierLedger">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-list"></i>
                            <span>Supplier Ledger</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/customerDue">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-cash"></i>
                            <span>Customer Due</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/customerLedger">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-list"></i>
                            <span>Customer Ledger</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @elseif($panel == 'HRPanel')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10 col-12 offset-md-1 mb-5">
                <div class="card mb-0" style="box-shadow: 0px 5px 1px 2px #058ed152;">
                    <div class="card-body p-3 text-center">
                        <h2 class="m-0">HR Panel</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/salary">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-receipt"></i>
                            <span>Salary Generate</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/salaryList">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-file-text"></i>
                            <span>Salary Record</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/employee">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-people"></i>
                            <span>Employee Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/employeeList">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-list-ul"></i>
                            <span>Employee List</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/department">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-plus-circle"></i>
                            <span>Department Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/designation">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-plus-circle"></i>
                            <span>Designation Entry</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @elseif($panel == 'ControlPanel')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10 col-12 offset-md-1 mb-5">
                <div class="card mb-0" style="box-shadow: 0px 5px 1px 2px #058ed152;">
                    <div class="card-body p-3 text-center">
                        <h2 class="m-0">Control Panel</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/product">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-plus-circle"></i>
                            <span>Product Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/productList">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-list-ul"></i>
                            <span>Product List</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/supplier">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-person"></i>
                            <span>Supplier Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/supplierList">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-list-ul"></i>
                            <span>Supplier List</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/customer">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-person"></i>
                            <span>Customer Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/customerList">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-list-ul"></i>
                            <span>Customer List</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/area">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-globe"></i>
                            <span>Area Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/unit">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-box"></i>
                            <span>Unit Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/category">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-tags"></i>
                            <span>Category Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/brand">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-plus-circle"></i>
                            <span>Brand Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/user">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-person-fill-add"></i>
                            <span>User Entry</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="/companyProfile">
                    <div class="card mb-0 displayFlex">
                        <div class="card-body p-3">
                            <i class="bi bi-house-fill"></i>
                            <span>Company Profile</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection