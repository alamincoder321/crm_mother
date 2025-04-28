@extends('master')

@section('title', 'Purchase Return')
@section('breadcrumb', 'Purchase Return')
@push('style')
<style>
    .table>thead>tr>th {
        text-align: center !important;
        background-color: gray;
        color: #fff;
    }
    .v-select{
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
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th :class="priceType == '' || priceType == 'purchase' ? '' : 'd-none'">Purchase_Rate</th>
                                    <th :class="priceType == '' || priceType == 'sale' ? '' : 'd-none'">Sale_Rate</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in products">
                                    <td v-html="index + 1"></td>
                                    <td v-html="item.code"></td>
                                    <td v-html="item.name"></td>
                                    <td v-html="item.brand?.name"></td>
                                    <td v-html="item.category?.name"></td>
                                    <td v-html="item.purchase_rate" :class="priceType == '' || priceType == 'purchase' ? '' : 'd-none'" class="text-end"></td>
                                    <td v-html="item.sale_rate" :class="priceType == '' || priceType == 'sale' ? '' : 'd-none'" class="text-end"></td>
                                    <td v-html="item.unit?.name" class="text-center"></td>
                                </tr>
                                <tr :class="products.length == 0 ? '' : 'd-none'" v-if="products.length == 0">
                                    <td :colspan="priceType == '' ? 8 : 7" class="text-center">Not Found Data</td>
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
            purchases: {
                details: []
            },
            suppliers: [],
            selectedSupplier: null,
            invoices: [],
            selectedInvoice: null,
            isLoading: null
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

            getPurchase() {
                axios.post('/get-purchase', {
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

            async onSearchPurchase(val, loading) {
                if (val.length > 2) {
                    loading(true);
                    await axios.post("/get-purchase", {
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

            showList() {
                let filter = {
                    supplierId: this.selectedSupplier ? this.selectedSupplier.id : '',
                    purchaseId: this.selectedInvoice ? this.selectedInvoice.id : '',
                }
                this.isLoading = false;
                axios.post('/get-purchase', filter)
                    .then(res => {
                        this.purchases = res.data
                        this.isLoading = true;
                    })
            }
        },
    })
</script>
@endpush