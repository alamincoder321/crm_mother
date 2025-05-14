@extends('master')

@section('title', 'Sale Entry')
@section('breadcrumb', 'Sale Entry')
@push('style')
<style scoped>
    .purTable thead tr th {
        background: #96a0a5;
        padding: 3px 4px !important;
        color: #fff;
        text-align: center !important;
    }

    table tr td,
    table tr th {
        vertical-align: middle !important;
    }

    .CustomerCard {
        height: 210px;
    }

    .btnCart,
    .btnCart:hover,
    .btnCart:focus {
        background: #228dc1;
        color: #fff;
    }

    .bankBtn:focus {
        background: #db9696 !important;
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
    <div class="col-12 col-md-9 mt-2">
        <div class="row">
            <div class="col-12 col-md-6 pe-md-1 mb-1">
                <div class="card mb-0 CustomerCard">
                    <div class="card-header py-2">
                        <h3 class="m-0 card-title p-0">Customer Info</h3>
                    </div>
                    <div class="card-body p-3 py-2">
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Customer:</label>
                            <div class="col-8 col-md-8">
                                <v-select :options="customers" v-model="selectedCustomer" label="display_name" @input="onChangeCustomer" @search="onSearchCustomer"></v-select>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Name:</label>
                            <div class="col-8 col-md-8">
                                <input type="text" :disabled="selectedCustomer.type == 'regular'" class="form-control" v-model="selectedCustomer.name" />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Phone:</label>
                            <div class="col-8 col-md-8">
                                <input type="text" :disabled="selectedCustomer.type == 'regular'" class="form-control" v-model="selectedCustomer.phone" />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Address:</label>
                            <div class="col-8 col-md-8">
                                <textarea type="text" :disabled="selectedCustomer.type == 'regular'" class="form-control" v-model="selectedCustomer.address"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 ps-md-1">
                <div class="card mb-0">
                    <div class="card-header py-2">
                        <h3 class="m-0 card-title p-0">Product Info</h3>
                    </div>
                    <div class="card-body p-3 py-2">
                        <form @submit.prevent="addToCart">
                            <div class="form-group row mb-1">
                                <label for="" class="col-4 col-md-3 form-label">Product:</label>
                                <div class="col-8 col-md-9">
                                    <v-select :options="products" id="product" v-model="selectedProduct" label="display_name" @input="onChangeProduct" @search="onSearchProduct"></v-select>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="" class="col-4 col-md-3 form-label">Rate:</label>
                                <div class="col-8 col-md-4">
                                    <input type="number" min="0" step="any" class="form-control" v-model="selectedProduct.sale_rate" @input="productTotal" />
                                </div>
                                <label for="" class="col-4 col-md-2 pe-md-0 form-label">Qty:</label>
                                <div class="col-8 col-md-3 ps-md-0">
                                    <input type="number" min="0" step="any" ref="quantity" class="form-control" v-model="selectedProduct.quantity" @input="productTotal" />
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="" class="col-4 col-md-3 form-label">Total:</label>
                                <div class="col-8 col-md-9">
                                    <input type="number" min="0" step="any" class="form-control" v-model="selectedProduct.total" readonly />
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="" class="col-4 col-md-3 form-label">Sale Rate:</label>
                                <div class="col-8 col-md-9">
                                    <input type="number" min="0" step="any" class="form-control" v-model="selectedProduct.sale_rate" />
                                </div>
                            </div>
                            <div class="form-group row" style="display: flex; align-items: center;">
                                <div class="col-12 col-md-7" style="font-size: 13px;">
                                    <span>Stock:</span> <span class="text-success" v-text="stock"></span>
                                    <span class="text-danger" v-if="stock <= 0"> (Out of Stock)</span>
                                </div>
                                <div class="col-12 col-md-5 text-end">
                                    <button type="submit" class="btn btn-sm btnCart w-100">AddToCart</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 mt-1" style="overflow-x: auto;">
                <table class="table table-hover purTable">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Rate</th>
                            <th>Total</th>
                            <th style="width: 3%;">Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="carts.length > 0" :class="carts.length > 0 ? '' : 'd-none'">
                        <tr v-for="(cart, index) in carts" :key="index">
                            <td class="text-center" v-text="index + 1"></td>
                            <td v-text="`${cart.name} - ${cart.code}`"></td>
                            <td class="text-center" v-text="cart.category_name"></td>
                            <td class="text-center">
                                <input type="number" min="0" step="any" style="width: 100px;padding:0; text-align: center; outline: none; border: 1px solid #c3c3c3; border-radius: 5px;" v-model="cart.quantity" @input="quantityRateTotal(cart)" />
                            </td>
                            <td class="text-center" v-text="cart.unit_name"></td>
                            <td class="text-center">
                                <input type="number" min="0" step="any" style="width: 100px;padding:0; text-align: center; outline: none; border: 1px solid #c3c3c3; border-radius: 5px;" v-model="cart.sale_rate" @input="quantityRateTotal(cart)" />
                            </td>
                            <td class="text-center" v-text="cart.total"></td>
                            <td class="text-center">
                                <i @click="removeCart(index)" class="bi bi-trash3 text-danger" style="cursor: pointer;"></i>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" style="padding: 5px !important;"></td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 6px !important;" colspan="2"><strong>Note:</strong></td>
                            <td style="padding: 5px 6px !important;" colspan="6">
                                <textarea class="form-control" v-model="sale.note" placeholder="Enter note here"></textarea>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="carts.length == 0" :class="carts.length == 0 ? '' : 'd-none'">
                        <td colspan="8" class="text-center">Cart is Empty</td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3 mt-2 ps-md-1">
        <form @submit.prevent="saveData($event)">
            <div class="card mb-0">
                <div class="card-header py-2">
                    <h3 class="m-0 card-title p-0">Payment Info</h3>
                </div>
                <div class="card-body p-3 pt-md-2 pb-md-1">
                    <div class="form-group row mb-1">
                        <label for="subtotal" class="col-4 col-md-12 form-label mb-0">SubTotal</label>
                        <div class="col-8 col-md-12">
                            <input type="number" v-model="sale.subtotal" min="0" step="any" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="discountPercent" class="col-4 col-md-12 form-label mb-0">Discount</label>
                        <div class="col-4 col-md-6">
                            <div class="input-group">
                                <input type="number" v-model="discountPercent" id="discountPercent" @input="calculateTotal" min="0" step="any" class="form-control" />
                                <button class="btn btn-sm btn-outline-secondary">%</button>
                            </div>
                        </div>
                        <div class="col-4 col-md-6">
                            <div class="input-group">
                                <input type="number" v-model="sale.discount" id="discount" @input="calculateTotal" min="0" step="any" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="vatPercent" class="col-4 col-md-12 form-label mb-0">Vat</label>
                        <div class="col-4 col-md-6">
                            <div class="input-group">
                                <input type="number" v-model="vatPercent" id="vatPercent" @input="calculateTotal" min="0" step="any" class="form-control" />
                                <button class="btn btn-sm btn-outline-secondary">%</button>
                            </div>
                        </div>
                        <div class="col-4 col-md-6">
                            <div class="input-group">
                                <input type="number" v-model="sale.vat" id="vat" @input="calculateTotal" min="0" step="any" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="transport_cost" class="col-4 col-md-12 form-label pe-0 mb-0">Transport Cost</label>
                        <div class="col-8 col-md-12">
                            <input type="number" v-model="sale.transport_cost" @input="calculateTotal" min="0" step="any" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="total" class="col-4 col-md-12 form-label mb-0">Total</label>
                        <div class="col-8 col-md-12">
                            <input type="number" v-model="sale.total" min="0" step="any" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="cashPaid" class="col-4 col-md-6 form-label mb-0">CashPaid</label>
                        <div class="col-8 col-md-6">
                            <input type="number" v-model="sale.cashPaid" id="cashPaid" @input="calculateTotal" min="0" step="any" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <div class="col-4 col-md-6">
                            <label class="form-label mb-0 btn btn-secondary w-100 px-0" @click="showModal">Multi-Payment</label>
                        </div>
                        <div class="col-8 col-md-6">
                            <input type="number" v-model="sale.bankPaid" id="paid" min="0" step="any" class="form-control" disabled />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="total" class="col-4 col-md-12 form-label mb-0">Change Amount</label>
                        <div class="col-8 col-md-12">
                            <input type="number" v-model="sale.returnAmount" min="0" step="any" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Due</label>
                        <div class="col-4 col-md-6">
                            <input type="number" v-model="sale.due" min="0" step="any" class="form-control" readonly />
                        </div>
                        <div class="col-4 col-md-6">
                            <input type="number" v-model="sale.previous_due" min="0" step="any" class="form-control text-danger" readonly />
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <div class="form-group row mb-2">
                        <div class="col-6 col-md-6">
                            <button type="submit" :disabled="onProgress" class="btn btn-success w-100">Save</button>
                        </div>
                        <div class="col-6 col-md-6">
                            <button type="button" class="btn btn-danger w-100">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                products: [],
                selectedProduct: {
                    id: '',
                    display_name: ''
                },
                employees: [],
                selectedEmployee: null,
                banks: [],
                selectedBank: {
                    id: '',
                    display_name: '',
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
            this.getEmployee();
            this.getBank();
            this.getCustomer();
            this.getProduct();
            if (this.sale.id != '') {
                await this.getSale();
            }
        },

        methods: {
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
                        forSearch: 'yes'
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

            getProduct() {
                axios.post('/get-product', {
                        forSearch: 'yes'
                    })
                    .then(res => {
                        this.products = res.data;
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
                            this.products = res.data;
                            loading(false)
                        })
                } else {
                    loading(false)
                    await this.getProduct();
                }
            },

            onChangeProduct() {
                if (this.selectedProduct == null) {
                    this.selectedProduct = {
                        id: '',
                        display_name: ''
                    }
                    return;
                }
                this.$refs.quantity.focus();
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
                if (cart) {
                    cart.quantity = parseFloat(cart.quantity) + parseFloat(this.selectedProduct.quantity);
                    cart.total = parseFloat(cart.sale_rate * cart.quantity).toFixed(2);
                } else {
                    this.carts.push({
                        id: this.selectedProduct.id,
                        code: this.selectedProduct.code,
                        category_name: this.selectedProduct.category?.name,
                        name: this.selectedProduct.name,
                        unit_name: this.selectedProduct.unit?.name,
                        purchase_rate: this.selectedProduct.purchase_rate,
                        sale_rate: this.selectedProduct.sale_rate,
                        quantity: this.selectedProduct.quantity,
                        total: this.selectedProduct.total,
                    })
                }
                this.clearProduct();
                this.calculateTotal();
            },

            async quantityRateTotal(cart) {
                this.carts = this.carts.map(item => {
                    if (item.quantity == '') {
                        item.quantity = 1;
                    }
                    item.total = parseFloat(item.sale_rate * item.quantity).toFixed(2);
                    return item;
                })
                await this.calculateTotal();
            },

            removeCart(sl) {
                this.carts.splice(sl, 1);
                this.calculateTotal();
            },

            clearProduct() {
                this.selectedProduct = {
                    id: '',
                    display_name: ''
                }
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
                        display_name: '',
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
                    display_name: '',
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
</script>
@endpush