@extends('master')

@section('title', 'Customer Due')
@section('breadcrumb', 'Customer Due')
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
</style>
@endpush
@section('content')
<div id="customerDue">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card m-0">
                <div class="card-body py-3 px-2">
                    <form @submit.prevent="showList" class="form-inline">
                        <div class="form-group">
                            <label for="searchType">SearchType</label>
                            <select id="searchType" class="form-select" v-model="searchType" @change="onChangeSearchType">
                                <option value="">All</option>
                                <option value="customer">By Customer</option>
                            </select>
                        </div>
                        <div class="form-group" :class="searchType == 'customer' ? '' : 'd-none'" v-if="searchType == 'customer'">
                            <label for="customer_id">Customer</label>
                            <v-select :options="customers" v-model="selectedCustomer" label="display_name"></v-select>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" v-model="date" />
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
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <th>Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in dues">
                                    <td v-html="index + 1"></td>
                                    <td v-html="item.code"></td>
                                    <td v-html="item.name"></td>
                                    <td v-html="item.phone"></td>
                                    <td v-html="item.address"></td>
                                    <td v-html="parseFloat(item.due).toFixed(2)" class="text-end"></td>
                                </tr>
                                <tr :class="dues.length > 0 ? '' : 'd-none'" v-if="dues.length > 0">
                                    <td class="text-center bg-light" colspan="5">Total</td>
                                    <td class="text-end bg-light" style="font-weight: 700;">@{{ dues.reduce((pre, cur) => {return pre + parseFloat(cur.due)}, 0).toFixed(2) }}</td>
                                </tr>
                                <tr :class="dues.length == 0 ? '' : 'd-none'" v-if="dues.length == 0">
                                    <td colspan="6" class="text-center">Not Found Data</td>
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
        el: '#customerDue',
        data: {
            searchType: '',
            date: moment().format('YYYY-MM-DD'),
            dues: [],
            customers: [],
            selectedCustomer: null,
            isLoading: null
        },

        methods: {
            getCustomer() {
                axios.post('/get-customer')
                    .then(res => {
                        this.customers = res.data;
                    })
            },

            onChangeSearchType() {
                this.dues = [];
                this.selectedCustomer = null;
                this.isLoading = null;
                this.date = moment().format('YYYY-MM-DD');
                if (this.searchType == 'customer') {
                    this.getCustomer();
                }
            },

            showList() {
                let filter = {
                    customerId: this.selectedCustomer ? this.selectedCustomer.id : '',
                    date: this.date
                }
                this.isLoading = false;
                axios.post('/get-customerDue', filter)
                    .then(res => {
                        if (this.searchType == 'customer' && this.selectedCustomer) {
                            this.dues = res.data;
                        } else {
                            this.dues = res.data.filter(item => parseFloat(item.due) > 0);
                        }
                        this.isLoading = true;
                    })
            },

            async print() {
                const oldTitle = window.document.title;
                window.document.title = "Customer Due"
                let customerText = '';
                if (this.selectedCustomer != null) {
                    customerText = `
                        <strong>Customer ID: </strong>
                        <span>${this.selectedCustomer.code}</span><br>
                        <strong>Name: </strong>
                        <span>${this.selectedCustomer.name}</span><br>
                        <strong>Mobile: </strong>
                        <span>${this.selectedCustomer.phone}</span>
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
                                <h5 style="text-decoration:underline;">Customer Due</h5>
                            </div>
                            <div class="col-12">${customerText}</div>
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