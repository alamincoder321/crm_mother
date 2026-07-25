<div class="row">
    <div class="col-md-10 offset-1" style="margin-bottom:10px;">
        <div class="sale-panel-header">
            <div class="header-line"></div>
            <h2 style="border-bottom: 4px solid #932af3;">Sale Panel</h2>
            <div class="header-line"></div>
        </div>
    </div>

    <div class="col-md-4 col-6">
        <div class="card cardInfo">
            <div class="card-body">
                <div class="cardIcon">
                    <i class="ri-shopping-cart-line"></i>
                </div>

                <div class="cardContent">
                    <span class="cardTitle">Today Sale</span>
                    <h3 class="cardValue">৳ 125,450</h3>
                    <small>
                        <i class="ri-arrow-up-line"></i>
                        higher than yesterday
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="card cardInfo">
            <div class="card-body">
                <div class="cardIcon">
                    <i class="ri-calendar-2-line"></i>
                </div>

                <div class="cardContent">
                    <span class="cardTitle">Monthly Sale</span>
                    <h3 class="cardValue">৳ 125,450</h3>
                    <small>
                        <i class="ri-arrow-up-line"></i>
                        Today's Total Sale
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="card cardInfo">
            <div class="card-body">
                <div class="cardIcon">
                    <i class="ri-bar-chart-2-fill"></i>
                </div>

                <div class="cardContent">
                    <span class="cardTitle">Yearly Sale</span>
                    <h3 class="cardValue">৳ 125,450</h3>
                    <small>
                        <i class="ri-arrow-up-line"></i>
                        Today's Total Sale
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="card cardInfo">
            <div class="card-body">
                <div class="cardIcon">
                    <i class="ri-file-list-3-line"></i>
                </div>

                <div class="cardContent">
                    <span class="cardTitle">Yearly Sale</span>
                    <h3 class="cardValue">৳ 125,450</h3>
                    <small>
                        <i class="ri-arrow-up-line"></i>
                        Today's Total Sale
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="card cardInfo">
            <div class="card-body">
                <div class="cardIcon">
                    <i class="ri-file-list-3-line"></i>
                </div>

                <div class="cardContent">
                    <span class="cardTitle">Yearly Sale</span>
                    <h3 class="cardValue">৳ 125,450</h3>
                    <small>
                        <i class="ri-arrow-up-line"></i>
                        Today's Total Sale
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="card cardInfo">
            <div class="card-body">
                <div class="cardIcon">
                    <i class="ri-file-list-3-line"></i>
                </div>

                <div class="cardContent">
                    <span class="cardTitle">Yearly Sale</span>
                    <h3 class="cardValue">৳ 125,450</h3>
                    <small>
                        <i class="ri-arrow-up-line"></i>
                        Today's Total Sale
                    </small>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-10 offset-1" style="margin-bottom:10px;">
        <div class="sale-panel-header">
            <div class="header-line"></div>
            <h2>Sales Shortcuts</h2>
            <div class="header-line"></div>
        </div>
    </div>

    <div class="col-md-2 col-6 mb-3">
        @if(checkAccess('sale'))
        <a href="/sale">
            <div class="card mb-0 displayFlex">
                <div class="card-body p-2">
                    <i class="bi bi-cart-dash"></i>
                    <span>Sale Entry</span>
                </div>
            </div>
        </a>
        @endif
    </div>
    <div class="col-md-2 col-6 mb-3">
        @if(checkAccess('saleRecord'))
        <a href="/sale-record">
            <div class="card mb-0 displayFlex">
                <div class="card-body p-2">
                    <i class="bi bi-file-text"></i>
                    <span>Sale Record</span>
                </div>
            </div>
        </a>
        @endif
    </div>
    <div class="col-md-2 col-6 mb-3">
        @if(checkAccess('saleReturn'))
        <a href="/sale-return">
            <div class="card mb-0 displayFlex">
                <div class="card-body p-2">
                    <i class="bi bi-arrow-return-left"></i>
                    <span>SaleReturn Entry</span>
                </div>
            </div>
        </a>
        @endif
    </div>
    <div class="col-md-2 col-6 mb-3">
        @if(checkAccess('saleReturnRecord'))
        <a href="/sale-return-record">
            <div class="card mb-0 displayFlex">
                <div class="card-body p-2">
                    <i class="bi bi-file-text"></i>
                    <span>SaleReturn Record</span>
                </div>
            </div>
        </a>
        @endif
    </div>
    <div class="col-md-2 col-6 mb-3">
        @if(checkAccess('quotation'))
        <a href="/quotation">
            <div class="card mb-0 displayFlex">
                <div class="card-body p-2">
                    <i class="bi bi-file-plus"></i>
                    <span>Quotation Entry</span>
                </div>
            </div>
        </a>
        @endif
    </div>
    <div class="col-md-2 col-6 mb-3">
        @if(checkAccess('quotationRecord'))
        <a href="/quotation-record">
            <div class="card mb-0 displayFlex">
                <div class="card-body p-2">
                    <i class="bi bi-file-text"></i>
                    <span>Quotation Record</span>
                </div>
            </div>
        </a>
        @endif
    </div>
    <div class="col-md-2 col-6 mb-3">
        @if(checkAccess('stock'))
        <a href="/stock">
            <div class="card mb-0 displayFlex">
                <div class="card-body p-2">
                    <i class="bi bi-list"></i>
                    <span>Stock Report</span>
                </div>
            </div>
        </a>
        @endif
    </div>

    <div class="col-md-2 col-6 mb-3">
        @if(checkAccess('dailyReport'))
        <a href="/dailyReport">
            <div class="card mb-0 displayFlex">
                <div class="card-body p-2">
                    <i class="bi bi-book"></i>
                    <span>Daily Report</span>
                </div>
            </div>
        </a>
        @endif
    </div>


</div>