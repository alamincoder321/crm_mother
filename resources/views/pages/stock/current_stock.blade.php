@extends('master')

@section('title', 'Stock List')
@section('breadcrumb', 'Stock List')
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
<div id="stockList">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card m-0">
                <div class="card-body py-3 px-2">
                    <form @submit.prevent="showList" class="form-inline">
                        <div class="form-group">
                            <label for="searchType">SearchType</label>
                            <select id="searchType" class="form-select" v-model="searchType" @change="onChangeSearchType">
                                <option value="current">Current Stock</option>
                                <option value="">Total Stock</option>
                                <option value="category">By Category</option>
                                <option value="brand">By Brand</option>
                                <option value="product">By Product</option>
                            </select>
                        </div>
                        <div class="form-group" :class="searchType == 'product' ? '' : 'd-none'" v-if="searchType == 'product'">
                            <label for="product_id">Product</label>
                            <v-select :options="products" v-model="selectedProduct" label="display_name"></v-select>
                        </div>
                        <div class="form-group" :class="searchType == 'category' ? '' : 'd-none'" v-if="searchType == 'category'">
                            <label for="category_id">Category</label>
                            <v-select :options="categories" v-model="selectedCategory" label="name"></v-select>
                        </div>
                        <div class="form-group" :class="searchType == 'brand' ? '' : 'd-none'" v-if="searchType == 'brand'">
                            <label for="brand_id">Brand</label>
                            <v-select :options="brands" v-model="selectedBrand" label="name"></v-select>
                        </div>
                        <div class="form-group" :class="searchType != 'current' ? '' : 'd-none'" v-if="searchType != 'current'">
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
                        <table v-if="searchType == 'current'" :class="searchType == 'current' ? '' : 'd-none'" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Stock</th>
                                    <th>Rate</th>
                                    <th>Stock Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in stocks">
                                    <td v-html="index + 1"></td>
                                    <td v-html="item.code"></td>
                                    <td v-html="item.name"></td>
                                    <td v-html="`${item.stock} ${item.unit_name}`" class="text-center"></td>
                                    <td v-html="item.purchase_rate" class="text-end"></td>
                                    <td v-html="parseFloat(item.stock_value).toFixed(2)" class="text-end"></td>
                                </tr>
                                <tr :class="stocks.length > 0 ? '' : 'd-none'" v-if="stocks.length > 0">
                                    <td class="text-center bg-light" colspan="3">Total</td>
                                    <td class="text-center bg-light" style="font-weight: 700;">@{{ stocks.reduce((pre, cur) => {return pre + parseFloat(cur.stock)}, 0) }}</td>
                                    <td class="bg-light"></td>
                                    <td class="text-end bg-light" style="font-weight: 700;">@{{ stocks.reduce((pre, cur) => {return pre + parseFloat(cur.stock_value)}, 0).toFixed(2) }}</td>
                                </tr>
                                <tr :class="stocks.length == 0 ? '' : 'd-none'" v-if="stocks.length == 0">
                                    <td colspan="6" class="text-center">Not Found Data</td>
                                </tr>
                            </tbody>
                        </table>
                        <table v-if="searchType != 'current'" :class="searchType != 'current' ? '' : 'd-none'" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Purchase Qty</th>
                                    <th>Purchase Return Qty</th>
                                    <th>Sale Qty</th>
                                    <th>Sale Return Qty</th>
                                    <th>Stock</th>
                                    <th>Rate</th>
                                    <th>Stock Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in stocks">
                                    <td v-html="index + 1"></td>
                                    <td v-html="item.code"></td>
                                    <td v-html="item.name"></td>
                                    <td v-html="item.purchase_quantity" class="text-center"></td>
                                    <td v-html="item.purchase_return_quantity" class="text-center"></td>
                                    <td v-html="item.sale_quantity" class="text-center"></td>
                                    <td v-html="item.sale_return_quantity" class="text-center"></td>
                                    <td v-html="`${item.stock} ${item.unit_name}`" class="text-center"></td>
                                    <td v-html="item.purchase_rate" class="text-end"></td>
                                    <td v-html="parseFloat(item.stock_value).toFixed(2)" class="text-end"></td>
                                </tr>
                                <tr>
                                    <td class="text-end bg-light" style="font-weight: 700;" colspan="3">Total</td>
                                    <td class="text-center bg-light" style="font-weight: 700;">@{{ stocks.reduce((pre, cur) => {return pre + parseFloat(cur.purchase_quantity)}, 0) }}</td>
                                    <td class="text-center bg-light" style="font-weight: 700;">@{{ stocks.reduce((pre, cur) => {return pre + parseFloat(cur.purchase_return_quantity)}, 0) }}</td>
                                    <td class="text-center bg-light" style="font-weight: 700;">@{{ stocks.reduce((pre, cur) => {return pre + parseFloat(cur.sale_quantity)}, 0) }}</td>
                                    <td class="text-center bg-light" style="font-weight: 700;">@{{ stocks.reduce((pre, cur) => {return pre + parseFloat(cur.sale_return_quantity)}, 0) }}</td>
                                    <td class="text-center bg-light" style="font-weight: 700;">@{{ stocks.reduce((pre, cur) => {return pre + parseFloat(cur.stock)}, 0) }}</td>
                                    <td class="bg-light"></td>
                                    <td class="text-end bg-light" style="font-weight: 700;">@{{ stocks.reduce((pre, cur) => {return pre + parseFloat(cur.stock_value)}, 0).toFixed(2) }}</td>
                                </tr>
                                <tr :class="stocks.length == 0 ? '' : 'd-none'" v-if="stocks.length == 0">
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
        el: '#stockList',
        data: {
            searchType: 'current',
            date: moment().format('YYYY-MM-DD'),
            stocks: [],
            products: [],
            selectedProduct: null,
            categories: [],
            selectedCategory: null,
            brands: [],
            selectedBrand: null,
            isLoading: null
        },

        methods: {
            getBrand() {
                axios.post('/get-brand')
                    .then(res => {
                        this.brands = res.data;
                    })
            },
            getCategory() {
                axios.post('/get-category')
                    .then(res => {
                        this.categories = res.data;
                    })
            },
            getProduct() {
                axios.post('/get-product')
                    .then(res => {
                        this.products = res.data;
                    })
            },

            onChangeSearchType() {
                this.stocks = [];
                this.selectedCategory = null;
                this.selectedBrand = null;
                this.selectedProduct = null;
                this.isLoading = null;
                this.date = moment().format('YYYY-MM-DD');
                if (this.searchType == 'category') {
                    this.getCategory();
                } else if (this.searchType == 'brand') {
                    this.getBrand();
                } else if (this.searchType == 'product') {
                    this.getProduct();
                }
            },

            showList() {
                let filter = {
                    categoryId: this.selectedCategory ? this.selectedCategory.id : '',
                    brandId: this.selectedBrand ? this.selectedBrand.id : '',
                    productId: this.selectedProduct ? this.selectedProduct.id : '',
                    date: this.searchType == 'current' ? '' : this.date
                }
                this.isLoading = false;
                axios.post('/get-currentStock', filter)
                    .then(res => {
                        this.stocks = res.data
                        this.isLoading = true;
                    })
            },

            async print() {
                const oldTitle = window.document.title;
                window.document.title = "Stock List"
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
                                <h5>Stock List</h5>
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