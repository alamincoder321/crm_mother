@php
$panel = session('panel');
@endphp

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        @if ($panel == 'dashboard' || $panel == '')
        <li class="nav-item">
            <a class="nav-link" href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/panel/SalePanel">
                <i class="bi bi-cart-dash"></i>
                <span>Sales Panel</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/panel/PurchasePanel">
                <i class="bi bi-cart-plus"></i>
                <span>Purchase Panel</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/panel/AccountPanel">
                <i class="bi bi-cash"></i>
                <span>Accounts Panel</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/panel/HRPanel">
                <i class="bi bi-people"></i>
                <span>HR Panel</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/panel/ReportPanel">
                <i class="bi bi-calendar-check"></i>
                <span>Reports Panel</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/panel/ControlPanel">
                <i class="bi bi-bank2"></i>
                <span>Control Panel</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/panel/BusinessPanel">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Business Panel</span>
            </a>
        </li>

        @elseif($panel == 'SalePanel')
        <li class="nav-item">
            <a class="nav-link" href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link panel-link" href="/panel/SalePanel">
                <span>Sale Panel</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/sale">
                <i class="bi bi-cart-dash"></i>
                <span>Sale Entry</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/sale-record">
                <i class="bi bi-file-text"></i>
                <span>Sale Record</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/sale-return">
                <i class="bi bi-arrow-return-left"></i>
                <span>SaleReturn Entry</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/sale-return-record">
                <i class="bi bi-file-text"></i>
                <span>SaleReturn Record</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/quotation">
                <i class="bi bi-file-plus"></i>
                <span>Quotation Entry</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/quotation-record">
                <i class="bi bi-file-text"></i>
                <span>Quotation Record</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/stock">
                <i class="bi bi-list"></i>
                <span>Stock Report</span>
            </a>
        </li>

        @elseif($panel == 'PurchasePanel')
        <li class="nav-item">
            <a class="nav-link" href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link panel-link" href="/panel/PurchasePanel">
                <span>Purchase Panel</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/purchase">
                <i class="bi bi-cart-plus"></i>
                <span>Purchase Entry</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/purchase-record">
                <i class="bi bi-file-text"></i>
                <span>Purchase Record</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/purchase-return">
                <i class="bi bi-arrow-return-left"></i>
                <span>Return Entry</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/purchase-return-record">
                <i class="bi bi-file-text"></i>
                <span>Return Record</span>
            </a>
        </li>

        @elseif($panel == 'AccountPanel')
        <li class="nav-item">
            <a class="nav-link" href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link panel-link" href="/panel/AccountPanel">
                <span>Account Panel</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/expense">
                <i class="bi bi-clipboard-minus"></i>
                <span>Expense Entry</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/income">
                <i class="bi bi-duffle"></i>
                <span>Income Entry</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/receive">
                <i class="bi bi-cash-stack"></i>
                <span>Receive</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/payment">
                <i class="bi bi-person-workspace"></i>
                <span>Payment</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/bankTransaction">
                <i class="bi bi-bank"></i>
                <span>Bank Transaction</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-list-ul"></i>
                <span>Bank Transaction Record</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/accounthead">
                <i class="bi bi-plus-circle"></i>
                <span>AccountHead Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/bank">
                <i class="bi bi-plus-circle"></i>
                <span>Bank Entry</span>
            </a>
        </li>

        @elseif($panel == 'ReportPanel')
        <li class="nav-item">
            <a class="nav-link" href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link panel-link" href="/panel/ReportPanel">
                <span>Report Panel</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/profitLoss">
                <i class="bi bi-journal-text"></i>
                <span>Profit/Loss</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="/cashLedger">
                <i class="bi bi-list"></i>
                <span>Cash Ledger</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/bankLedger">
                <i class="bi bi-list"></i>
                <span>Bank Ledger</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/supplierDue">
                <i class="bi bi-cash"></i>
                <span>Supplier Due</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/supplierLedger">
                <i class="bi bi-list"></i>
                <span>Supplier Ledger</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="/customerDue">
                <i class="bi bi-cash"></i>
                <span>Customer Due</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/customerLedger">
                <i class="bi bi-list"></i>
                <span>Customer Ledger</span>
            </a>
        </li>

        @elseif($panel == 'HRPanel')
        <li class="nav-item">
            <a class="nav-link" href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link panel-link" href="/panel/HRPanel">
                <span>HR Panel</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/salary">
                <i class="bi bi-receipt"></i>
                <span>Salary Generate</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/salaryList">
                <i class="bi bi-file-text"></i>
                <span>Salary Record</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/employee">
                <i class="bi bi-people"></i>
                <span>Employee Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/employeeList">
                <i class="bi bi-list-ul"></i>
                <span>Employee List</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/department">
                <i class="bi bi-plus-circle"></i>
                <span>Department Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/designation">
                <i class="bi bi-plus-circle"></i>
                <span>Designation Entry</span>
            </a>
        </li>

        @elseif($panel == 'ControlPanel')
        <li class="nav-item">
            <a class="nav-link" href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link panel-link" href="/panel/ControlPanel">
                <span>Control Panel</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/product">
                <i class="bi bi-plus-circle"></i>
                <span>Product Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/productList">
                <i class="bi bi-list-ul"></i>
                <span>Product List</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/supplier">
                <i class="bi bi-person"></i>
                <span>Supplier Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/supplierList">
                <i class="bi bi-list-ul"></i>
                <span>Supplier List</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/customer">
                <i class="bi bi-person"></i>
                <span>Customer Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/customerList">
                <i class="bi bi-list-ul"></i>
                <span>Customer List</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/area">
                <i class="bi bi-globe"></i>
                <span>Area Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/unit">
                <i class="bi bi-box"></i>
                <span>Unit Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/category">
                <i class="bi bi-tags"></i>
                <span>Category Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/brand">
                <i class="bi bi-plus-circle"></i>
                <span>Brand Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/user">
                <i class="bi bi-person-fill-add"></i>
                <span>User Entry</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/companyProfile">
                <i class="bi bi-house-fill"></i>
                <span>Company Profile</span>
            </a>
        </li>
        @endif
    </ul>

</aside>