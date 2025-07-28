@extends('master')

@section('title', 'Sale Entry')
@section('breadcrumb', 'Sale Entry')
@push('style')
<style scoped>
    .purTable thead tr th {
        background: #64747ca3;
        padding: 7px 5px !important;
        color: #fff;
        text-align: center !important;
    }

    table tr td,
    table tr th {
        vertical-align: middle !important;
    }

    .mostly-customized-scrollbar {
        overflow-y: auto;
        overflow-x: hidden;
        height: 1em !important;
    }

    .mostly-customized-scrollbar::-webkit-scrollbar {
        width: 5px !important;
        height: 3px !important;
        background-color: #aaa !important;
    }

    .mostly-customized-scrollbar::-webkit-scrollbar-thumb {
        background: #4154f1 !important;
    }
</style>
@endpush
@section('content')
<div class="row" id="sale">
    <div class="col-12 col-md-12">
        <div class="card mb-0">
            <div class="card-body p-2">
                <div class="row d-flex align-items-center">
                    <label class="form-label col-4 col-md-1 mb-md-0" for="name">Invoice:</label>
                    <div class="col-8 col-md-2 mb-1 mb-md-0">
                        <input type="text" readonly class="form-control" autocomplete="off" id="invoice" name="invoice" v-model="sale.invoice" />
                    </div>
                    <label class="form-label col-4 col-md-1 mb-md-0" for="name">Employee:</label>
                    <div class="col-8 col-md-2 mb-1 mb-md-0">
                        <v-select :options="employees" v-model="selectedEmployee" label="display_name"></v-select>
                    </div>
                    <label class="form-label col-4 col-md-1 mb-md-0" for="name">AddBy:</label>
                    <div class="col-8 col-md-2 mb-1 mb-md-0">
                        <input type="text" readonly class="form-control" autocomplete="off" id="name" value="{{auth()->user()->name}}" />
                    </div>
                    <label class="form-label col-4 col-md-1 mb-md-0" for="name">Date:</label>
                    <div class="col-8 col-md-2 mb-1 mb-md-0">
                        <input type="date" class="form-control" autocomplete="off" id="date" name="date" v-model="sale.date" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2 col-md-6 col-12 pe-md-0">
        <div class="card mb-0 shadow-none" style="height:560px;border: 1px solid gray;border-top-right-radius: 0 !important;border-bottom-right-radius: 0 !important;">
            <div class="card-header" style="padding: 7px 15px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4" for="">Category</label>
                            <div class="col-md-8">
                                <v-select :options="categories" v-model="selectedCategory" label="name" @input="onChangeCategory"></v-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4" for="">Brand</label>
                            <div class="col-md-8">
                                <v-select :options="brands" v-model="selectedBrand" label="name" @input="onChangeBrand"></v-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="form-group row">
                            <label for="" class="col-md-2">Product</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" placeholder="Search Product Or Code"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body bg-light p-2 mostly-customized-scrollbar" style="overflow-y: auto;overflow-x: hidden;">
                <div class="row">
                    <div class="col-md-3 col-6" v-for="(product, index) in products" :key="index">
                        <div
                            class="card my-1 text-center"
                            :class="carts.some(c => c.id == product.id) ? 'border border-info' : ''"
                            style="height: 135px;cursor:pointer; border-radius: 10px; padding: 10px 5px;border:1px solid #e7e7e7;"
                            @click="selectedProduct = product; addToCart()">
                            <img :src="`/${product.image ? product.image : 'noImage.jpg'}`" alt="Product Image" style="width: 40px; height: 40px; object-fit: cover; margin: 0 auto; border-radius: 8px;">
                            <div class="mt-2">
                                <h6 style="font-size: 12px;" class="mb-1" :title="product.name">
                                    <span v-if="product.name && product.name.length > 30" :title="product.name">
                                        @{{ product.name.substring(0, 30) + '...' }}
                                    </span>
                                    <span v-else v-text="product.name"></span>
                                </h6>
                                <p style="font-size: 11px; line-height: 1;" class="m-0" v-text="product.code"></p>
                                <span class="badge bg-primary fs-7" v-if="product.sale_rate">
                                    ৳ @{{ product.sale_rate }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2 col-md-6 col-12 ps-md-0">
        <div class="card mb-0 shadow-none" style="height:560px;border: 1px solid gray;border-top-left-radius: 0 !important;border-bottom-left-radius: 0 !important;">
            <div class="card-header" style="padding: 7px;padding-bottom:2px;">
                <div class="row">
                    <div class="col-md-4">
                        <v-select :options="customers" v-model="selectedCustomer" label="display_name" @input="onChangeCustomer" @search="onSearchCustomer"></v-select>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" :disabled="selectedCustomer.type == 'retail'" class="form-control" v-model="selectedCustomer.name" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" :disabled="selectedCustomer.type == 'retail'" class="form-control" v-model="selectedCustomer.phone" />
                        </div>
                    </div>
                    <div class="col-12 col-md-12 mt-2">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Scan Barcode">
                            <button class="btn btn-primary" type="button" style="font-size: 15px;"><i class="bi bi-upc"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body bg-light p-2" style="overflow-x: auto;">
                <table class="table table-hover purTable">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th style="width: 30%;">Description</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                            <th>Total</th>
                            <th style="width: 3%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(cart, index) in carts" :key="index" v-if="carts.length > 0" :class="carts.length > 0 ? '' : 'd-none'">
                            <td class="text-center" v-text="index + 1"></td>
                            <td v-text="`${cart.name} - ${cart.code}`"></td>
                            <td class="text-center" v-text="cart.category_name"></td>
                            <td class="text-center">
                                <div class="input-group input-group-sm" style="width: 80px; margin: 0 auto;">
                                    <button style="padding: 0px 5px;" class="btn btn-outline-secondary" type="button" @click="cart.quantity = Math.max(1, +cart.quantity - 1); quantityRateTotal(cart)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="text"
                                        class="form-control text-center"
                                        style="width:30px;padding: 0px 5px; outline: none; border-radius: 0;border-color: #000;"
                                        v-model="cart.quantity"
                                        @input="quantityRateTotal(cart)" />
                                    <button style="padding: 0px 5px;" class="btn btn-outline-secondary" type="button" @click="cart.quantity = +cart.quantity + 1; quantityRateTotal(cart)">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="input-group input-group-sm" style="width: 120px; margin: 0 auto;">
                                    <button style="padding: 0px 5px;" class="btn btn-outline-secondary" type="button" @click="cart.sale_rate = Math.max(0, +cart.sale_rate - 1); quantityRateTotal(cart)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="text"
                                        class="form-control text-center"
                                        style="width:70px;padding: 0px 5px; outline: none; border-radius: 0;border-color: #000;"
                                        v-model="cart.sale_rate"
                                        @input="quantityRateTotal(cart)" />
                                    <button style="padding: 0px 5px;" class="btn btn-outline-secondary" type="button" @click="cart.sale_rate = +cart.sale_rate + 1; quantityRateTotal(cart)">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center" v-text="cart.total"></td>
                            <td class="text-center">
                                <i @click="removeCart(index)" class="bi bi-trash3 text-danger" style="cursor: pointer;"></i>
                            </td>
                        </tr>
                        <tr v-if="carts.length == 0" :class="carts.length == 0 ? '' : 'd-none'">
                            <td colspan="8" class="text-center">Not Found Data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-12 mt-1">
        <div class="card mb-0" style="border: 1px solid gray;">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group align-items-center">
                            <label for="" class="pe-2">Note</label>
                            <textarea rows="6" name="note" id="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3" style="border-left: 1px solid gray;">
                        <div class="form-group row">
                            <label for="" class="col-md-4">SubTotal</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" v-model="sale.subtotal" readonly>
                            </div>
                        </div>
                        <div class="form-group row mt-1">
                            <label for="" class="col-md-4">Discount</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="number" v-model="discountPercent" id="discountPercent" @input="calculateTotal" min="0" step="any" class="form-control">
                                    <span class="px-1">%</span>
                                    <input type="number" v-model="sale.discount" id="discount" @input="calculateTotal" min="0" step="any" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-1">
                            <label for="" class="col-md-4">Vat</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="number" v-model="vatPercent" id="vatPercent" @input="calculateTotal" min="0" step="any" class="form-control">
                                    <span class="px-1">%</span>
                                    <input type="number" v-model="sale.vat" id="vat" @input="calculateTotal" min="0" step="any" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" style="border-left: 1px solid gray;">
                        <div class="form-group row">
                            <label for="" class="col-md-4">Total</label>
                            <div class="col-md-8">
                                <input type="number" v-model="sale.total" min="0" step="any" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mt-1">
                            <label for="" class="col-md-4">CashPaid</label>
                            <div class="col-md-8">
                                <input type="number" v-model="sale.cashPaid" id="cashPaid" @input="calculateTotal" min="0" step="any" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mt-1">
                            <div class="col-md-5 ps-1 pe-0">
                                <label class="form-label mb-0 btn btn-secondary px-0 w-100" @click="showModal">Multi-Payment</label>
                            </div>
                            <div class="col-md-7">
                                <input type="number" v-model="sale.bankPaid" id="bankPaid" min="0" step="any" class="form-control" disabled />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" style="border-left: 1px solid gray;">
                        <div class="form-group row">
                            <label for="" class="col-md-4">Change</label>
                            <div class="col-md-8">
                                <input type="number" v-model="sale.returnAmount" min="0" step="any" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <button class="btn w-100 btn-success" style="height: 57px;">Save</button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn w-100 btn-danger" style="height: 57px;">Hold</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- bank account entry -->
    <div class="modal showModal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header justify-content-between text-white" style="background: #236974;">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Multi-Payment Add To Cart & List</h1>
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body pb-3">
                    <form @submit.prevent="addToBankCart">
                        <div class="input-group">
                            <v-select :options="banks" id="banks" v-model="selectedBank" label="display_name" @input="onChangeBank" style="width: 350px;"></v-select>
                            <input type="text" class="form-control" id="last_digit" v-model="selectedBank.last_digit" @input="goToAmount" placeholder="Last Digit" />
                            <input type="number" step="any" min="0" class="form-control" id="bankAmount" v-model="selectedBank.amount" placeholder="Amount" />
                            <input type="submit" class="bankBtn" value="Add" style="border: none; background: #a9a9a9; color: #fff;" />
                        </div>
                    </form>
                    <table class="table table-bordered table-hover text-center mt-3">
                        <thead>
                            <tr>
                                <th colspan="6" style="background: gainsboro;">Bank Cart List</th>
                            </tr>
                            <tr>
                                <th>Sl</th>
                                <th>Bank Name</th>
                                <th>Account Number</th>
                                <th>Last Digit</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody v-if="bankCart.length > 0" :class="bankCart.length > 0 ? '' : 'd-none'">
                            <tr v-for="(cart, index) in bankCart" :key="index">
                                <td v-text="index + 1"></td>
                                <td v-text="cart.bank_name"></td>
                                <td v-text="cart.number"></td>
                                <td v-text="cart.last_digit"></td>
                                <td v-text="cart.amount"></td>
                                <td>
                                    <span @click="removeBankCart(index)" style="cursor: pointer;">X</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer text-white" style="background: #f1e5ac;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push("js")
<script>
    new Vue({
        el: "#sale",
        data() {
            return {
                sale: {
                    id: "{{$id}}",
                    invoice: "{{$invoice}}",
                    date: moment().format('YYYY-MM-DD'),
                    sale_type: 'retail',
                    employee_id: "",
                    subtotal: 0,
                    discount: 0,
                    vat: 0,
                    transport_cost: 0,
                    total: 0,
                    cashPaid: 0,
                    bankPaid: 0,
                    paid: 0,
                    returnAmount: 0,
                    due: 0,
                    previous_due: 0,
                    note: ''
                },
                discountPercent: 0,
                vatPercent: 0,
                customers: [],
                selectedCustomer: {
                    id: '',
                    display_name: 'Walk In Customer',
                    name: 'Walk In Customer',
                    phone: '',
                    address: '',
                    type: 'general'
                },
                brands: [],
                selectedBrand: null,
                categories: [],
                selectedCategory: null,
                products: [],
                selectedProduct: {
                    id: '',
                    unit: {},
                    display_name: 'select product'
                },
                employees: [],
                selectedEmployee: null,
                banks: [],
                selectedBank: {
                    id: '',
                    display_name: 'select product',
                    last_digit: '',
                    amount: ''
                },
                carts: [],
                bankCart: [],
                stock: 0,
                onProgress: false,
            }
        },

        async created() {
            this.getBrand();
            this.getCategory();
            this.getEmployee();
            this.getBank();
            this.getCustomer();
            this.getProduct();
            if (this.sale.id != '') {
                await this.getSale();
            }
        },

        methods: {
            getBrand(){
                axios.get('/get-brand')
                    .then(res => {
                        this.brands = res.data;
                    })
            },
            getCategory(){
                axios.get('/get-category')
                    .then(res => {
                        this.categories = res.data;
                    })
            },
            getBank() {
                axios.get('/get-bank')
                    .then(res => {
                        this.banks = res.data.map(item => {
                            item.last_digit = "";
                            item.amount = "";
                            return item;
                        });
                    })
            },
            getEmployee() {
                axios.get('/get-employee')
                    .then(res => {
                        this.employees = res.data;
                    })
            },
            getCustomer() {
                axios.post('/get-customer', {
                        forSearch: 'yes',
                        customer_type: this.sale.sale_type
                    })
                    .then(res => {
                        this.customers = res.data;
                        this.customers.unshift({
                            id: '',
                            display_name: 'Walk In Customer',
                            name: 'Walk In Customer',
                            phone: '',
                            address: '',
                            type: 'general'
                        }, {
                            id: '',
                            display_name: 'New Customer',
                            name: '',
                            phone: '',
                            address: '',
                            type: 'new'
                        })
                    })
            },

            async onSearchCustomer(val, loading) {
                if (val.length > 2) {
                    loading(true);
                    await axios.post("/get-customer", {
                            search: val,
                            customer_type: this.sale.sale_type
                        })
                        .then(res => {
                            this.customers = res.data;
                            loading(false)
                        })
                } else {
                    loading(false)
                    await this.getCustomer();
                }
            },

            onChangeCustomer() {
                if (this.selectedCustomer == null) {
                    this.selectedCustomer = {
                        id: '',
                        display_name: 'Walk In Customer',
                        name: 'Walk In Customer',
                        phone: '',
                        address: '',
                        type: 'general'
                    }
                    return;
                }

            },

            onChangeSaleType() {
                this.selectedCustomer = {
                    id: '',
                    display_name: 'Walk In Customer',
                    name: 'Walk In Customer',
                    phone: '',
                    address: '',
                    type: 'general'
                }
                this.selectedProduct = {
                    id: '',
                    unit: {},
                    display_name: 'select product'
                }
                this.getCustomer();
                this.getProduct();
            },

            getProduct() {
                axios.post('/get-product', {
                        forSearch: 'yes'
                    })
                    .then(res => {
                        this.products = res.data.map(item => {
                            item.sale_rate = this.sale.sale_type == 'retail' ? item.sale_rate : item.wholesale_rate;
                            return item;
                        });
                    })
            },
            async onSearchProduct(val, loading) {
                if (val.length > 2) {
                    loading(true);
                    await axios.post("/get-product", {
                            search: val,
                            is_service: 'false'
                        })
                        .then(res => {
                            this.products = res.data.map(item => {
                                item.sale_rate = this.sale.sale_type == 'retail' ? item.sale_rate : item.wholesale_rate;
                                return item;
                            });
                            loading(false)
                        })
                } else {
                    loading(false)
                    await this.getProduct();
                }
            },

            async onChangeProduct() {
                if (this.selectedProduct == null) {
                    this.selectedProduct = {
                        id: '',
                        unit: {},
                        display_name: 'select product'
                    }
                    return;
                }
                if (this.selectedProduct.id != '') {
                    await axios.post('/get-currentStock', {
                        productId: this.selectedProduct.id
                    }).then(res => {
                        this.stock = res.data.length > 0 ? res.data[0].stock : 0;
                    })
                    this.$refs.quantity.focus();
                }
            },

            productTotal() {
                this.selectedProduct.total = parseFloat(this.selectedProduct.sale_rate * this.selectedProduct.quantity).toFixed(2);
            },

            addToCart() {
                if (this.selectedProduct.id == '') {
                    toastr.error('Please select a product')
                    return;
                }
                let cart = this.carts.find(item => item.id == this.selectedProduct.id);

                if (cart != undefined) {
                    let newQuantity = parseFloat(cart.quantity) + parseFloat(1)
                    // if (parseFloat(newQuantity) > parseFloat(this.stock)) {
                    //     toastr.error('Stock is unavailable');
                    //     return;
                    // }
                    cart.quantity = newQuantity;
                    cart.total = parseFloat(cart.sale_rate * cart.quantity).toFixed(2);
                } else {
                    // if (1 > parseFloat(this.stock)) {
                    //     toastr.error('Stock is unavailable');
                    //     return;
                    // }
                    this.carts.push({
                        id: this.selectedProduct.id,
                        code: this.selectedProduct.code,
                        category_name: this.selectedProduct.category?.name,
                        name: this.selectedProduct.name,
                        unit_name: this.selectedProduct.unit?.name,
                        purchase_rate: this.selectedProduct.purchase_rate,
                        sale_rate: this.selectedProduct.sale_rate,
                        quantity: 1,
                        total: parseFloat(this.selectedProduct.sale_rate * 1).toFixed(2)
                    })
                }
                this.clearProduct();
                this.calculateTotal();
            },

            async quantityRateTotal(cart) {
                let stock = await axios.post('/get-currentStock', {
                    productId: cart.id
                }).then(res => {
                    return res.data.length > 0 ? res.data[0].stock : 0;
                });

                this.carts = this.carts.map(item => {
                    if (item.id === cart.id) {
                        if (item.quantity == '') {
                            item.quantity = 1;
                        }
                        if (parseFloat(item.quantity) > parseFloat(stock)) {
                            toastr.error('Stock is unavailable');
                            item.quantity = stock;
                        }
                        item.total = parseFloat(item.sale_rate * item.quantity).toFixed(2);
                    }
                    return item;
                });
                await this.calculateTotal();
            },

            removeCart(sl) {
                this.carts.splice(sl, 1);
                this.calculateTotal();
            },

            clearProduct() {
                this.selectedProduct = {
                    id: '',
                    unit: {},
                    display_name: 'select product'
                }
                this.stock = 0;
            },

            calculateTotal() {
                this.sale.subtotal = this.carts.reduce((pr, cu) => {
                    return pr + parseFloat(cu.total)
                }, 0).toFixed(2);
                if (event.target.id == 'discount') {
                    this.discountPercent = (this.sale.discount * 100) / this.sale.subtotal;
                }
                if (event.target.id == 'discountPercent') {
                    this.sale.discount = parseFloat((this.discountPercent * this.sale.subtotal) / 100).toFixed(2);
                }
                this.sale.total = parseFloat(this.sale.subtotal - this.sale.discount).toFixed(2);
                if (event.target.id == 'vat') {
                    this.vatPercent = (this.sale.vat * 100) / this.sale.total;
                }
                if (event.target.id == 'vatPercent') {
                    this.sale.vat = parseFloat((this.vatPercent * this.sale.total) / 100).toFixed(2);
                }
                this.sale.total = parseFloat(+this.sale.total + +this.sale.vat + +this.sale.transport_cost).toFixed(2);
                if (event.target.id == 'cashPaid' || this.bankCart.length > 0) {
                    this.sale.paid = parseFloat(parseFloat(this.sale.cashPaid) + parseFloat(this.sale.bankPaid)).toFixed(2);
                    if (parseFloat(this.sale.paid) > parseFloat(this.sale.total)) {
                        this.sale.returnAmount = parseFloat(this.sale.paid - this.sale.total).toFixed(2);
                        this.sale.due = 0;

                    } else {
                        this.sale.returnAmount = 0;
                        this.sale.due = parseFloat(this.sale.total - this.sale.paid).toFixed(2);
                    }
                } else {
                    this.sale.cashPaid = this.sale.total;
                    this.sale.bankPaid = 0;
                    this.sale.paid = this.sale.total;
                    this.sale.due = 0;
                    this.sale.returnAmount = 0;
                }
            },

            showModal() {
                $('.showModal').modal('show');
            },

            onChangeBank() {
                if (this.selectedBank == null) {
                    this.selectedBank = {
                        id: '',
                        display_name: 'select product',
                        last_digit: '',
                        amount: ''
                    }
                    return;
                }
                if (this.selectedBank.id != '') {
                    $('#staticBackdrop').find('#last_digit').select();
                }
            },

            goToAmount() {
                if (this.selectedBank.last_digit.length > 3) {
                    $('#staticBackdrop').find('#bankAmount').select();
                }
            },

            async addToBankCart() {
                if (this.selectedBank == null) {
                    toastr.error('Please select a bank')
                    return;
                }
                if (this.selectedBank.id == '') {
                    toastr.error('Please select a bank')
                    $('#staticBackdrop').find("#banks [type='search']").focus();
                    return;
                }
                let cart = this.bankCart.find(item => item.id == this.selectedBank.id);
                if (cart) {
                    cart.amount = parseFloat(cart.amount) + parseFloat(this.selectedBank.amount);
                } else {
                    this.bankCart.push({
                        id: this.selectedBank.id,
                        bank_name: this.selectedBank.bank_name,
                        number: this.selectedBank.number,
                        last_digit: this.selectedBank.last_digit,
                        amount: this.selectedBank.amount,
                    })
                }
                this.sale.bankPaid = this.bankCart.reduce((pr, cu) => {
                    return pr + parseFloat(cu.amount)
                }, 0).toFixed(2);
                await this.calculateTotal();
                this.clearBankCart();
            },

            removeBankCart(sl) {
                this.bankCart.splice(sl, 1);
                this.sale.bankPaid = this.bankCart.reduce((pr, cu) => {
                    return pr + parseFloat(cu.amount)
                }, 0).toFixed(2);
                this.calculateTotal();
            },

            clearBankCart() {
                this.selectedBank = {
                    id: '',
                    display_name: 'select product',
                    last_digit: '',
                    amount: ''
                };
            },

            saveData(event) {
                this.sale.employee_id = this.selectedEmployee ? this.selectedEmployee.id : "";
                let formdata = {
                    sale: this.sale,
                    customer: this.selectedCustomer,
                    carts: this.carts,
                    bankCart: this.bankCart,
                }
                let url = this.sale.id != '' ? '/update-sale' : '/sale'
                this.onProgress = true;
                axios.post(url, formdata)
                    .then(async res => {
                        toastr.success(res.data.message);
                        this.clearData();
                        history.pushState(null, '', '/sale');
                        this.sale.invoice = res.data.invoice;
                        if (confirm('Do you want to go to the invoice page?')) {
                            window.open(`/sale-invoice/${res.data.saleId}`, '_blank');
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
                            toastr.error(val)
                        }
                    })

            },
            clearData() {
                this.sale = {
                    id: "",
                    invoice: "{{$invoice}}",
                    date: moment().format('YYYY-MM-DD'),
                    sale_type: 'retail',
                    employee_id: "",
                    subtotal: 0,
                    discount: 0,
                    vat: 0,
                    transport_cost: 0,
                    total: 0,
                    paid: 0,
                    due: 0,
                    previous_due: 0,
                };
                this.onProgress = false;
                this.discountPercent = 0;
                this.vatPercent = 0;
                this.selectedCustomer = {
                    id: '',
                    display_name: 'Walk In Customer',
                    name: 'Walk In Customer',
                    phone: '',
                    address: '',
                    type: 'general'
                };
                this.selectedEmployee = null;
                this.carts = [];
                this.getCustomer();
            },

            async getSale() {
                await axios.post('/get-sale', {
                    saleId: this.sale.id
                }).then(res => {
                    let sale = res.data[0];
                    let saleKeys = Object.keys(this.sale);
                    saleKeys.forEach(key => {
                        this.sale[key] = sale[key];
                    })

                    sale.details.map(item => {
                        let detail = {
                            id: item.product_id,
                            code: item.code,
                            category_name: item.category_name,
                            name: item.name,
                            unit_name: item.unit_name,
                            purchase_rate: item.purchase_rate,
                            sale_rate: item.sale_rate,
                            quantity: item.quantity,
                            total: item.total
                        };
                        this.carts.push(detail);
                    })
                    sale.bank_details.map(item => {
                        let detail = {
                            id: item.bank_id,
                            bank_name: item.bank_name,
                            number: item.number,
                            last_digit: item.last_digit,
                            amount: item.amount
                        };
                        this.bankCart.push(detail);
                    })

                    this.selectedCustomer = {
                        id: sale.customer_id ?? '',
                        name: sale.customer_name,
                        phone: sale.customer_phone,
                        address: sale.customer_address,
                        display_name: sale.customer_type == 'general' ? 'Walk In Customer' : `${sale.customer_name} - ${sale.customer_phone} - ${sale.customer_address}`,
                        type: sale.customer_type
                    }

                    setTimeout(() => {
                        this.selectedEmployee = this.employees.find(item => item.id == sale.employee_id);
                    }, 1000);
                })
            }
        },
    })
    $(document).ready(function() {
        $("body").addClass("toggle-sidebar");
    });
</script>
@endpush