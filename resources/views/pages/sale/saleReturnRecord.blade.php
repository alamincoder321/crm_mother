@extends('master')

@section('title', 'Sale Return Record')
@section('breadcrumb', 'Sale Return Record')
@push('style')
<style>
    .table>thead>tr>th {
        text-align: center !important;
        background-color: gray;
        color: #fff;
    }

    tr td,
    tr th {
        vertical-align: middle !important;
    }
</style>
@endpush
@section('content')
<div id="saleReturnList">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card m-0">
                <div class="card-body py-3 px-2">
                    <form @submit.prevent="showReport" class="form-inline">
                        <div class="form-group">
                            <label for="searchType">SearchType</label>
                            <select id="searchType" class="form-select" v-model="searchType" @change="onChangeSearchType">
                                <option value="">All</option>
                                <option value="customer">By Customer</option>
                                <option value="user">By User</option>
                            </select>
                        </div>

                        <div class="form-group" :class="searchType == 'customer' ? '' : 'd-none'" v-if="searchType == 'customer'">
                            <label for="customer">Customer</label>
                            <v-select :options="customers" v-model="selectedCustomer" label="display_name"></v-select>
                        </div>
                        <div class="form-group" :class="searchType == 'user' ? '' : 'd-none'" v-if="searchType == 'user'">
                            <label for="user">User</label>
                            <v-select :options="users" v-model="selectedUser" label="name"></v-select>
                        </div>
                        <div class="form-group">
                            <label for="recordType">Type</label>
                            <select id="recordType" class="form-select" v-model="recordType" @change="salereturns = []">
                                <option value="without">Without Detail</option>
                                <option value="with">With Detail</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dateFrom">From</label>
                            <input type="date" class="form-control" v-model="dateFrom">
                        </div>
                        <div class="form-group">
                            <label for="dateFrom">To</label>
                            <input type="date" class="form-control" v-model="dateTo">
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
                        <table class="table table-bordered table-hover record-table" :class="recordType == 'without' ? '' : 'd-none'" v-if="recordType == 'without'">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in salereturns" :class="salereturns.length > 0 ? '' : 'd-none'" v-if="salereturns.length > 0">
                                    <td v-html="index + 1" class="text-center"></td>
                                    <td v-html="item.invoice" class="text-center"></td>
                                    <td v-html="item.date" class="text-center"></td>
                                    <td v-html="item.customer_name" class="text-center"></td>
                                    <td v-html="item.total" class="text-end"></td>
                                    <td v-html="item.note" class="text-center"></td>
                                    <td class="text-center">
                                        <i class="bi bi-file-earmark-medical-fill text-secondary" style="cursor: pointer;font-size:14px"></i>
                                        <!-- <i @click="editSale(item.id)" class="bi bi-pencil-square text-info" style="cursor: pointer;"></i> -->
                                        <i @click="deleteData(item.id)" class="bi bi-trash3 text-danger" style="cursor: pointer;"></i>
                                    </td>
                                </tr>
                                <tr :class="salereturns.length > 0 ? '' : 'd-none'" v-show="salereturns.length > 0">
                                    <th v-html="`Total`" colspan="4" class="text-end"></th>
                                    <th v-html="salereturns.reduce((pr, cu) => {return pr + parseFloat(cu.total)}, 0).toFixed(2)" class="text-end"></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr :class="salereturns.length == 0 ? '' : 'd-none'" v-if="salereturns.length == 0">
                                    <td colspan="7" class="text-center">Not Found Data</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered table-hover record-table" :class="recordType == 'with' ? '' : 'd-none'" v-if="recordType == 'with'">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(item, index) in salereturns">
                                    <tr :class="salereturns.length > 0 ? '' : 'd-none'" v-show="salereturns.length > 0">
                                        <td v-html="index + 1"></td>
                                        <td v-html="item.invoice" class="text-center"></td>
                                        <td v-html="item.date" class="text-center"></td>
                                        <td v-html="item.customer_name" class="text-center"></td>
                                        <td v-html="item.details[0].name" class="text-center"></td>
                                        <td v-html="item.details[0].quantity" class="text-center"></td>
                                        <td v-html="item.details[0].sale_rate" class="text-end"></td>
                                        <td v-html="item.details[0].total" class="text-end"></td>
                                        <td class="text-center">
                                            <i class="bi bi-file-earmark-medical-fill text-secondary" style="cursor: pointer;font-size:14px"></i>
                                            <!-- <i @click="editSale(item.id)" class="bi bi-pencil-square text-info" style="cursor: pointer;"></i> -->
                                            <i @click="deleteData(item.id)" class="bi bi-trash3 text-danger" style="cursor: pointer;"></i>
                                        </td>
                                    </tr>
                                    <tr v-for="(product, ind) in item.details.slice(1)" v-show="item.details.length > 1">
                                        <td colspan="4" :rowspan="item.details.length - 1" v-if="ind == 0"></td>
                                        <td v-html="product.name" class="text-center"></td>
                                        <td v-html="product.quantity" class="text-center"></td>
                                        <td v-html="product.sale_rate" class="text-end"></td>
                                        <td v-html="product.total" class="text-end"></td>
                                        <td :rowspan="item.details.length - 1" v-if="ind == 0"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">
                                            <strong>Note: </strong><span v-text="item.note"></span>
                                        </td>
                                        <td colspan="3" class="text-end">
                                            <strong>Total: <span v-text="item.total"></span></strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </template>
                                <tr :class="salereturns.length == 0 ? '' : 'd-none'" v-if="salereturns.length == 0">
                                    <td colspan="10" class="text-center">Not Found Data</td>
                                </tr>
                            </tbody>
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
        el: '#saleReturnList',
        data: {
            searchType: '',
            recordType: 'without',
            dateFrom: moment().format('YYYY-MM-DD'),
            dateTo: moment().format('YYYY-MM-DD'),
            salereturns: [],
            customers: [],
            selectedCustomer: null,
            users: [],
            selectedUser: null,
            isLoading: null
        },

        methods: {
            editSale(id) {
                if (typeof window !== 'undefined') {
                    window.open('/sale/' + id, '_blank');
                }
            },
            getUser() {
                axios.post('/get-user')
                    .then(res => {
                        this.users = res.data;
                    })
            },
            getCustomer() {
                axios.post('/get-customer')
                    .then(res => {
                        this.customers = res.data;
                    })
            },

            onChangeSearchType() {
                this.salereturns = [];
                this.customers = [];
                this.selectedCustomer = null;
                this.users = [];
                this.selectedUser = null;
                this.recordType = "without";
                this.isLoading = null;
                if (this.searchType == 'customer') {
                    this.getCustomer();
                } else if (this.searchType == 'user') {
                    this.getUser();
                }
            },

            showReport() {
                let filter = {
                    userId: this.selectedUser ? this.selectedUser.id : '',
                    customerId: this.selectedCustomer ? this.selectedCustomer.id : '',
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo
                }
                this.isLoading = false;
                axios.post('/get-sale-return', filter)
                    .then(res => {
                        this.salereturns = res.data
                        this.isLoading = true;
                    })
            },

            deleteData(rowId) {
                if (!confirm('Are you sure ?')) {
                    return;
                }
                axios.post('/delete-sale-return', {
                        id: rowId
                    })
                    .then(res => {
                        if (res.data.status) {
                            toastr.success(res.data.message);
                            this.showReport();
                        }
                    })
                    .catch(err => {
                        this.onProgress = false
                        var r = JSON.parse(err.request.response);
                        console.log(r);

                        if (err.request.status == '422' && r.errors != undefined && typeof r.errors == 'object') {
                            $.each(r.errors, (index, value) => {
                                $.each(value, (ind, val) => {
                                    toastr.error(val)
                                })
                            })
                        } else {
                            if (r.errors != undefined) {
                                console.log(r.errors);
                            }
                            toastr.error(r.message)
                        }
                    })
            },

            async print() {
                const oldTitle = window.document.title;
                window.document.title = "Sale Return Record"
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
                                <h5>Sale Return Record</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                ${document.getElementById('reportContent').innerHTML}
                            </div>
                        </div>
                    </div>
                `;
                printWindow.onload = async function() {
                    const rows = printWindow.contentDocument.querySelectorAll('.record-table tr');
                    rows.forEach(row => {
                        const lastCell = row.lastElementChild;
                        if (lastCell) {
                            lastCell.remove();
                        }
                    });

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