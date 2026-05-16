@extends('master')
@section('title', 'Daybook Report')
@section('breadcrumb', 'Daybook Report')
@push('style')
<style scoped>
    .table>thead>tr>th {
        text-align: center !important;
        background-color: gray;
        color: #fff;
    }

    .v-select .dropdown-toggle {
        width: 250px !important;
    }

    .v-select .dropdown-menu {
        width: 350px !important;
        overflow-y: hidden !important;
    }

    tr th,
    tr td {
        vertical-align: top !important;
    }
</style>
@endpush
@section('content')
<div id="daybook">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card m-0">
                <div class="card-body py-3 px-2">
                    <form @submit.prevent="showReport" class="form-inline">
                        <div class="form-group">
                            <label for="dateFrom">From</label>
                            <input type="date" class="form-control" id="dateFrom" v-model="filter.dateFrom" />
                        </div>
                        <div class="form-group">
                            <label for="dateTo">To</label>
                            <input type="date" class="form-control" id="dateTo" v-model="filter.dateTo" />
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Show</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2" :class="isLoading == false ? '' : 'd-none'" v-if="isLoading == false">
        <div class="col-12 text-center">
            Loading...
        </div>
    </div>
    <div class="row mt-2" :class="isLoading ? '' : 'd-none'" v-if="isLoading">
        <div class="col-12 col-md-12">
            <div class="card m-0">
                <div class="card-body pt-1 pb-3 px-2">
                    <div class="text-end">
                        <a href="" @click.prevent="print" title="Print"><i class="bi bi-printer"></i></a>
                    </div>
                    <div id="reportContent" style="overflow-x: auto;">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th class="text-center" style="padding: 8px 5px !important;" colspan="2">Receive</th>
                                <th></th>
                                <th class="text-center" style="padding: 8px 5px !important;" colspan="2">Payment</th>
                            </tr>
                            <tr>
                                <td><strong>Sales Receipt</strong></td>
                                <td rowspan="2" class="text-center"><strong>@{{totalSale | formatCurrency }}</strong></td>

                                <td></td>

                                <td><strong>Purchase Paid</strong></td>
                                <td rowspan="2" class="text-center"><strong>@{{totalPurchase | formatCurrency }}</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="table table-bordered" :class="sales.length > 0 ? '' : 'd-none'" v-if="sales.length > 0">
                                        <tr>
                                            <td class="text-center">Invoice</td>
                                            <td class="text-center">Customer</td>
                                            <td class="text-center">Received</td>
                                        </tr>
                                        <tr v-for="sale in sales" :key="sale.id">
                                            <td class="text-center">@{{ sale.invoice }}</td>
                                            <td class="text-center">@{{ sale.customer_name }}</td>
                                            <td class="text-end">@{{ sale.cashPaid }}</td>
                                        </tr>
                                    </table>
                                </td>

                                <td></td>

                                <td>
                                    <table class="table table-bordered" :class="purchases.length > 0 ? '' : 'd-none'" v-if="purchases.length > 0">
                                        <tr>
                                            <td class="text-center">Invoice</td>
                                            <td class="text-center">Supplier</td>
                                            <td class="text-center">Paid</td>
                                        </tr>
                                        <tr v-for="purchase in purchases" :key="purchase.id">
                                            <td class="text-center">@{{ purchase.invoice }}</td>
                                            <td class="text-center">@{{ purchase.supplier_name }}</td>
                                            <td class="text-end">@{{ purchase.paid }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center"><strong>Total Receive</strong></td>
                                <td class="text-center"><strong>@{{totalSale | formatCurrency }}</strong></td>

                                <td></td>

                                <td class="text-center"><strong>Total Payment</strong></td>
                                <td class="text-center"><strong>@{{totalPurchase | formatCurrency }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-center" style="padding: 6px 5px !important;">
                                    <strong>Closing Balance: @{{ (totalSale - totalPurchase) | formatCurrency }}</strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script>
    new Vue({
        el: '#daybook',
        data: {
            filter: {
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD')
            },
            sales: [],
            purchases: [],
            isLoading: true
        },

        computed: {
            totalSale(){
                return this.sales.reduce((pr, cu) => pr + parseFloat(cu.cashPaid), 0);
            },
            totalPurchase(){
                return this.purchases.reduce((pr, cu) => pr + parseFloat(cu.paid), 0);
            }
        },

        filters: {
            formatCurrency(value) {
                if (typeof value !== "number") {
                    value = Number(value);
                }
                if (isNaN(value)) return "৳ 0.00";
                return "৳ " + value.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        },

        methods: {
            async showReport() {
                this.isLoading = true;
                await this.getSale();
                await this.getPurchase();
            },

            async getPurchase() {
                const purchases = await axios.post("/get-purchase", this.filter);
                this.purchases = purchases.data;
            },

            async getSale() {
                const sales = await axios.post("/get-sale", this.filter);
                this.sales = sales.data;
            },

            async print() {
                const oldTitle = window.document.title;
                window.document.title = "Daybook Report";
                let dateText = '';
                if (this.filter.dateFrom && this.filter.dateTo) {
                    dateText = `
                        <strong>Statement From: </strong>
                        <span>${moment(this.filter.dateFrom).format('DD-MM-YYYY')} to ${moment(this.filter.dateTo).format('DD-MM-YYYY')}</span>
                    `;
                }

                const printWindow = document.createElement('iframe');
                document.body.appendChild(printWindow);
                printWindow.srcdoc = `
                    <style>
                        .table>:not(caption)>*>* {
                            font-size: 11px !important;
                        }
                        address p{
                            margin: 0 !important;
                        }                                        
                    </style>

                    @include('layouts.headerInfo')
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h5 style="text-decoration:underline;">Daybook Report</h5>
                            </div>
                            <div class="col-6"></div>
                            <div class="col-6">${dateText}</div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                ${document.getElementById('reportContent').innerHTML}
                            </div>
                        </div>
                    </div>
                `;
                printWindow.onload = async function() {
                    printWindow.contentWindow.focus();
                    await new Promise(resolve => setTimeout(resolve, 500));
                    printWindow.contentWindow.print();
                    document.body.removeChild(printWindow);
                    window.document.title = oldTitle;
                };
            }
        },
    })
</script>
@endpush