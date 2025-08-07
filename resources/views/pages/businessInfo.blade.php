@extends('master')

@section('title', 'Business Info')
@section('breadcrumb', 'Business Info')

@push('style')
<style scoped>
    .card {
        border: 1px solid #959595;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 my-3">
        <div style="display: flex; align-items: center; text-align: center; margin: 0;">
            <div style="flex: 1; border-bottom: 1px solid #000;"></div>
            <div style="padding: 0 15px; font-size: 18px; font-weight: 700;">Welcome To Business Info</div>
            <div style="flex: 1; border-bottom: 1px solid #000;"></div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 50px;background: #dbd6d6;border-radius: 50%;">
                            <i class="bi bi-cash"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Cash Balance</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 50px;background: #dbd6d6;border-radius: 50%;">
                            <i class="bi bi-bank2"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Bank Balance</strong>
                            <p class="m-0">{{number_format($bankBalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 50px;background: #dbd6d6;border-radius: 50%;">
                            <img src="/taka.png" style="width: 50px;margin-top: -15px;">
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Total Balance</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance + $bankBalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Today Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Monthly Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Yearly Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Total Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Today Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Today Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Today Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Today Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Today Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Today Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Today Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 mb-3">
                    <div class="card-body p-0 d-flex gap-3 align-items-center">
                        <div class="w-25 text-center" style="font-size: 30px;background: #ffeec7;border-radius: 50%;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="w-75">
                            <strong class="m-0">Today Sale</strong>
                            <p class="m-0">{{number_format($cashBalance->cashbalance, 2)}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-12 col-md-8">
                <div id="chart_div"></div>
            </div>
            <div class="col-12 col-md-4">
                <div id="piechart"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', {
        packages: ['corechart', 'line']
    });
    google.charts.setOnLoadCallback(drawCurveTypes);

    function drawCurveTypes() {
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'X');
        data.addColumn('number', 'Sales');

        data.addRows([
            [0, 0],
            [1, 10],
            [2, 23],
            [3, 17],
            [4, 18],
            [5, 9],
            [6, 12],
            [7, 28],
            [8, 33],
            [9, 40],
            [10, 32],
            [11, 35],
            [12, 30],
            [13, 40],
            [14, 42],
            [15, 47],
            [16, 44],
            [17, 48],
            [18, 52],
            [19, 54],
            [20, 44],
            [21, 55],
            [22, 58],
            [23, 57],
            [24, 60],
            [25, 50],
            [26, 52],
            [27, 51],
            [28, 49],
            [29, 53],
            [30, 55]
        ]);

        var options = {
            hAxis: {
                title: 'Time'
            },
            vAxis: {
                title: 'Popularity'
            },
            series: {
                1: {
                    curveType: 'function'
                }
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Work', 11],
            ['Eat', 2],
            ['Commute', 2],
            ['Watch TV', 2],
            ['Sleep', 7]
        ]);

        var options = {
            title: 'My Daily Activities'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }
</script>
@endpush