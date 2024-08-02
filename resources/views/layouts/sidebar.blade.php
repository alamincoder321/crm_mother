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
                <span>Sales Panel</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-cart-plus"></i>
                <span>Sale Entry</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="components-alerts.html">
                        <i class="bi bi-circle"></i><span>Alerts</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

    </ul>

</aside>