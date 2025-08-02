@extends('master')

@section('title', 'Quotation Entry')
@section('breadcrumb', 'Quotation Entry')
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

    .sale-type-box.active {
        background: #228dc1 !important;
        color: #fff !important;
        border-color: #228dc1 !important;
    }

    .sale_type_label {
        cursor: pointer;
        font-size: 13px;
        width: 80px;
        text-align: center;
        border-radius: 6px;
        border: 1px solid #228dc1;
        background: #fff;
        color: #228dc1;
        font-weight: 500;
        transition: all .2s;
    }
</style>
@endpush
@section('content')
<div class="row" id="quotation">
    <div class="col-12 col-md-12">
        <div class="card mb-0">
            <div class="card-body p-2">
                <div class="row d-flex align-items-center">
                    <label class="form-label col-4 col-md-1 mb-md-0" for="name">Invoice:</label>
                    <div class="col-8 col-md-2 mb-1 mb-md-0">
                        <input type="text" readonly class="form-control" autocomplete="off" id="invoice" name="invoice" v-model="quotation.invoice" />
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
                        <input type="date" class="form-control" autocomplete="off" id="date" name="date" v-model="quotation.date" />
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
                    <div class="card-body p-3 pb-2 pt-1">                        
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
                                <input type="text" :disabled="selectedCustomer.type == 'regular'" class="form-control" v-model="selectedCustomer.address" >
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
                                <div class="col-8 col-md-9">
                                    <input type="number" min="0" step="any" class="form-control" v-model="selectedProduct.sale_rate" @input="productTotal" />
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="" class="col-4 col-md-3 form-label">Qty:</label>
                                <div class="col-8 col-md-9">
                                    <input type="number" min="0" step="any" ref="quantity" class="form-control" v-model="selectedProduct.quantity" @input="productTotal" />
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="" class="col-4 col-md-3 form-label">Total:</label>
                                <div class="col-8 col-md-9">
                                    <input type="number" min="0" step="any" class="form-control" v-model="selectedProduct.total" readonly />
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
                                <textarea class="form-control" v-model="quotation.note" placeholder="Enter note here"></textarea>
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
                    <h3 class="m-0 card-title p-0">Amount Info</h3>
                </div>
                <div class="card-body p-3 pt-md-2 pb-md-1">
                    <div class="form-group row mb-1">
                        <label for="subtotal" class="col-4 col-md-12 form-label mb-0">SubTotal</label>
                        <div class="col-8 col-md-12">
                            <input type="number" v-model="quotation.subtotal" min="0" step="any" class="form-control" readonly />
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
                                <input type="number" v-model="quotation.discount" id="discount" @input="calculateTotal" min="0" step="any" class="form-control" />
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
                                <input type="number" v-model="quotation.vat" id="vat" @input="calculateTotal" min="0" step="any" class="form-control" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row mb-1">
                        <label for="total" class="col-4 col-md-12 form-label mb-0">Total</label>
                        <div class="col-8 col-md-12">
                            <input type="number" v-model="quotation.total" min="0" step="any" class="form-control" readonly />
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <div class="form-group row mb-2">
                        <div class="col-6 col-md-6">
                            <button type="submit" :disabled="onProgress" class="btn btn-success w-100" v-text="quotation.id != '' ? 'Update' : 'Save'"></button>
                        </div>
                        <div class="col-6 col-md-6">
                            <button type="button" class="btn btn-danger w-100">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push("js")
<script>
    new Vue({
        el: "#quotation",
        data() {
            return {
                quotation: {
                    id: "{{$id}}",
                    invoice: "{{$invoice}}",
                    date: moment().format('YYYY-MM-DD'),
                    employee_id: "",
                    subtotal: 0,
                    discount: 0,
                    vat: 0,                    
                    total: 0,
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
                    display_name: 'select product'
                },
                employees: [],
                selectedEmployee: null,
                carts: [],
                stock: 0,
                onProgress: false,
            }
        },

        async created() {
            this.getEmployee();
            this.getCustomer();
            this.getProduct();
            if (this.quotation.id != '') {
                await this.getQuotation();
            }
        },

        methods: {
            getEmployee() {
                axios.get('/get-employee')
                    .then(res => {
                        this.employees = res.data;
                    })
            },
            getCustomer() {
                axios.post('/get-customer', {
                        forSearch: 'yes',
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
                        });
                    })
            },

            async onSearchCustomer(val, loading) {
                if (val.length > 2) {
                    loading(true);
                    await axios.post("/get-customer", {
                            search: val
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
                        display_name: 'select product'
                    }
                    return;
                }
                if(this.selectedProduct.id != ''){
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
                    display_name: 'select product'
                }
            },

            calculateTotal() {
                this.quotation.subtotal = this.carts.reduce((pr, cu) => {
                    return pr + parseFloat(cu.total)
                }, 0).toFixed(2);
                if (event.target.id == 'discount') {
                    this.discountPercent = (this.quotation.discount * 100) / this.quotation.subtotal;
                }
                if (event.target.id == 'discountPercent') {
                    this.quotation.discount = parseFloat((this.discountPercent * this.quotation.subtotal) / 100).toFixed(2);
                }
                this.quotation.total = parseFloat(this.quotation.subtotal - this.quotation.discount).toFixed(2);
                if (event.target.id == 'vat') {
                    this.vatPercent = (this.quotation.vat * 100) / this.quotation.total;
                }
                if (event.target.id == 'vatPercent') {
                    this.quotation.vat = parseFloat((this.vatPercent * this.quotation.total) / 100).toFixed(2);
                }
                this.quotation.total = parseFloat(+this.quotation.total + +this.quotation.vat).toFixed(2);
            },

            saveData(event) {
                this.quotation.employee_id = this.selectedEmployee ? this.selectedEmployee.id : "";
                let formdata = {
                    quotation: this.quotation,
                    customer: this.selectedCustomer,
                    carts: this.carts
                }
                let url = this.quotation.id != '' ? '/update-quotation' : '/quotation'
                this.onProgress = true;
                axios.post(url, formdata)
                    .then(async res => {
                        toastr.success(res.data.message);
                        this.clearData();
                        history.pushState(null, '', '/quotation');
                        this.quotation.invoice = res.data.invoice;
                        if (confirm('Do you want to go to the invoice page?')) {
                            window.open(`/quotation-invoice/${res.data.quotationId}`, '_blank');
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
            clearData() {
                this.quotation = {
                    id: "",
                    invoice: "{{$invoice}}",
                    date: moment().format('YYYY-MM-DD'),
                    employee_id: "",
                    subtotal: 0,
                    discount: 0,
                    vat: 0,
                    total: 0,
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

            async getQuotation() {
                await axios.post('/get-quotation', {
                    quotationId: this.quotation.id
                }).then(res => {
                    let quotation = res.data[0];
                    let quotationKeys = Object.keys(this.quotation);
                    quotationKeys.forEach(key => {
                        this.quotation[key] = quotation[key];
                    })

                    quotation.details.map(item => {
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

                    this.selectedCustomer = {
                        id: quotation.customer_id ?? '',
                        name: quotation.customer_name,
                        phone: quotation.customer_phone,
                        address: quotation.customer_address,
                        display_name: quotation.customer_type == 'general' ? 'Walk In Customer' : `${quotation.customer_name} - ${quotation.customer_phone} - ${quotation.customer_address}`,
                        type: quotation.customer_type
                    }

                    setTimeout(() => {
                        this.selectedEmployee = this.employees.find(item => item.id == quotation.employee_id);
                    }, 1000);
                })
            }
        },
    })
</script>
@endpush