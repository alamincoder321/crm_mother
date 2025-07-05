@extends('master')

@section('title', 'Purchase Return')
@section('breadcrumb', 'Purchase Return')
@push('style')
<style>
    .table>thead>tr>th {
        text-align: center !important;
        background-color: gray;
        color: #fff;
        vertical-align: middle !important;
    }

    .table>tbody>tr>td {
        vertical-align: middle !important;
    }

    .v-select {
        width: 300px !important;
    }
</style>
@endpush
@section('content')
<div id="purchasereturnList">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card m-0">
                <div class="card-body py-3 px-2">
                    <form @submit.prevent="showList" class="form-inline">
                        <div class="form-group">
                            <label for="supplier_id">Supplier</label>
                            <v-select :options="suppliers" v-model="selectedSupplier" label="display_name" @input="onChangeSupplier" @search="onSearchSupplier" placeholder="Search Supplier"></v-select>
                        </div>
                        <div class="form-group">
                            <label for="brand_id">Invoice</label>
                            <v-select :options="invoices" v-model="selectedInvoice" label="display_name" placeholder="Search Invoice"></v-select>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Show Invoice</button>
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
                    <div id="reportContent" style="overflow-x: auto;">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>ProductName</th>
                                    <th>Purchased Qty</th>
                                    <th>Purchased Total</th>
                                    <th>Already Return Qty</th>
                                    <th>Return Qty</th>
                                    <th>Return Rate</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in purchase.details">
                                    <td class="text-center" v-text="index + 1"></td>
                                    <td class="text-left" v-text="`${item.name} - ${item.code}`"></td>
                                    <td class="text-center" v-text="item.quantity"></td>
                                    <td class="text-center" v-text="item.total"></td>
                                    <td class="text-center" v-text="'already taken'"></td>
                                    <td class="text-center">
                                        <input type="number" class="text-center" min="0" step="any" v-model="item.return_quantity" @input="qtyRateChange(index)" />
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="text-center" min="0" step="any" v-model="item.purchase_rate" @input="qtyRateChange(index)" />
                                    </td>
                                    <td class="text-center" v-text="item.returnTotal"></td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <textarea name="note" id="note" placeholder="Enter note here..." class="form-control" v-model="purchaseReturn.note"></textarea>
                                    </td>
                                    <td class="text-center">
                                        @{{ purchase.details.reduce((acc, item) => {
                                            return acc + (item.return_quantity !== "" && !isNaN(item.return_quantity) ? parseFloat(item.return_quantity) : 0);
                                        }, 0) }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" :disabled="onProgress" @click="saveData" class="btn btn-success btn-sm d-block w-100">Save Return</button>
                                    </td>
                                    <td class="text-center">
                                        @{{ purchase.details.reduce((acc, item) => {
                                            return acc + (item.returnTotal !== "" && !isNaN(item.returnTotal) ? parseFloat(item.returnTotal) : 0);
                                        }, 0).toFixed(2) }}
                                    </td>
                                </tr>
                                <tr :class="purchase.details.length == 0 ? '' : 'd-none'" v-if="purchase.details.length == 0">
                                    <td :colspan="5" class="text-center">Not Found Data</td>
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
        el: '#purchasereturnList',
        data: {
            purchase: {
                details: []
            },
            purchaseReturn: {
                date: moment().format('YYYY-MM-DD'),
                supplier_id: '',
                purchase_id: '',
                note: '',
            },
            suppliers: [],
            selectedSupplier: null,
            invoices: [],
            selectedInvoice: null,
            isLoading: null,
            onProgress: false,
        },

        created() {
            this.getSupplier();
        },

        methods: {
            getSupplier() {
                axios.post('/get-supplier', {
                        forSearch: 'yes'
                    })
                    .then(res => {
                        this.suppliers = res.data;
                        this.suppliers.unshift({
                            id: '',
                            display_name: 'Walk In Supplier',
                            name: 'Walk In Supplier',
                            phone: '',
                            address: '',
                            type: 'general'
                        })
                    })
            },

            async onSearchSupplier(val, loading) {
                if (val.length > 2) {
                    loading(true);
                    await axios.post("/get-supplier", {
                            search: val,
                        })
                        .then(res => {
                            this.suppliers = res.data;
                            loading(false)
                        })
                } else {
                    loading(false)
                    await this.getSupplier();
                }
            },

            onChangeSupplier() {
                if (this.selectedSupplier != null) {
                    this.getPurchase();
                }
            },

            getPurchase() {
                axios.post('/get-purchase', {
                        forSearch: 'yes',
                        supplierId: this.selectedSupplier ? this.selectedSupplier.id : ""
                    })
                    .then(res => {
                        this.invoices = res.data;
                    })
            },

            async onSearchPurchase(val, loading) {
                if (val.length > 2) {
                    loading(true);
                    await axios.post("/get-purchase", {
                            search: val,
                        })
                        .then(res => {
                            this.purchase = res.data;
                            loading(false)
                        })
                } else {
                    loading(false)
                    await this.getPurchase();
                }
            },

            async qtyRateChange(ind) {
                let item = this.purchase.details[ind];
                if (item.return_quantity > item.quantity) {
                    item.return_quantity = item.quantity;
                }
                if (item.purchase_rate < 0) {
                    item.purchase_rate = 0;
                }
                item.returnTotal = (item.return_quantity * item.purchase_rate).toFixed(2);
            },

            showList() {
                let filter = {
                    supplierId: this.selectedSupplier ? this.selectedSupplier.id : '',
                    purchaseId: this.selectedInvoice ? this.selectedInvoice.id : '',
                }
                this.isLoading = false;
                axios.post('/get-purchase', filter)
                    .then(res => {
                        this.purchase = res.data[0]
                        this.purchaseReturn.supplier_id = this.selectedSupplier ? this.selectedSupplier.id : '';
                        this.purchaseReturn.purchase_id = this.selectedInvoice ? this.selectedInvoice.id : '';
                        this.isLoading = true;
                    })
            },

            saveData() {
                let details = this.purchase.details.filter(item => item.return_quantity > 0).map(item => {
                    return {
                        id: item.id,
                        product_id: item.product_id,
                        return_quantity: item.return_quantity,
                        purchase_rate: item.purchase_rate,
                        discount: item.discount,
                        returnTotal: item.returnTotal
                    }
                });

                this.purchaseReturn.total = details.reduce((pr, cu) => {return pr + parseFloat(cu.returnTotal)}, 0).toFixed(2);
                
                let filter = {
                    purchaseReturn: this.purchaseReturn,
                    carts: details
                }
                this.onProgress = true;
                axios.post('/purchase-return', filter)
                    .then(res => {
                        if (res.data.status == 'success') {
                            toastr.success(res.data.message);
                            this.showList();
                        } else {
                            toastr.error(res.data.message);
                        }
                        this.onProgress = false;
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
                            toastr.error(val)
                        }
                    })
            },
        },
    })
</script>
@endpush