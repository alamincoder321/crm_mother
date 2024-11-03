@extends('master')

@section('title', 'Product List')
@section('breadcrumb', 'Product List')
@push('style')
<style>
    .table>thead>tr>th {
        text-align: center !important;
        background-color: gray;
        color: #fff;
    }
</style>
@endpush
@section('content')
<div id="productList">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card m-0">
                <div class="card-body py-3 px-2">
                    <form @submit.prevent="showList" class="form-inline">
                        <div class="form-group">
                            <label for="searchType">SearchType</label>
                            <select id="searchType" class="form-select" v-model="searchType" @change="onChangeSearchType">
                                <option value="">All</option>
                                <option value="category">By Category</option>
                                <option value="brand">By Brand</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="priceType">Type</label>
                            <select id="priceType" class="form-select" v-model="priceType">
                                <option value="">All</option>
                                <option value="sale">Only Sale Price</option>
                                <option value="purchase">Only Purchase Price</option>
                            </select>
                        </div>
                        <div class="form-group" :class="searchType == 'category' ? '' : 'd-none'" v-if="searchType == 'category'">
                            <label for="category_id">Category</label>
                            <v-select :options="categories" v-model="selectedCategory" label="name"></v-select>
                        </div>
                        <div class="form-group" :class="searchType == 'brand' ? '' : 'd-none'" v-if="searchType == 'brand'">
                            <label for="brand_id">Brand</label>
                            <v-select :options="brands" v-model="selectedBrand" label="name"></v-select>
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
        el: '#productList',
        data: {
            searchType: '',
            priceType: '',
            products: [],
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

            onChangeSearchType() {
                this.products = [];
                this.categories = [];
                this.brands = [];
                this.selectedCategory = null;
                this.selectedBrand = null;
                this.priceType = "";
                this.isLoading = null;
                if (this.searchType == 'category') {
                    this.getCategory();
                } else if (this.searchType == 'brand') {
                    this.getBrand();
                }
            },

            showList() {
                let filter = {
                    categoryId: this.selectedCategory ? this.selectedCategory.id : '',
                    brandId: this.selectedBrand ? this.selectedBrand.id : '',
                }
                this.isLoading = false;
                axios.post('/get-product', filter)
                    .then(res => {
                        this.products = res.data
                        this.isLoading = true;
                    })
            },

            async print() {
                const oldTitle = window.document.title;
                window.document.title = "Product List"
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
                                <h5>Product List</h5>
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