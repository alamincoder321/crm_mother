@extends('master')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
       <img class="w-100" src="{{asset('backend')}}/img/header.jpg" alt="header logo" style="border-radius: 20px;border: 1px solid rgb(0, 126, 187);box-shadow: rgb(0, 126, 187) 0px 5px 0px 0px;">
    </div>

    <div class="col-md-12 my-3"></div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-6 col-md-3">
                <a href="">
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
                <a href="">
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
                <a href="">
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
                <a href="">
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
                <a href="">
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
                <a href="">
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
                <a href="">
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
                <a href="">
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
</div>
@endsection