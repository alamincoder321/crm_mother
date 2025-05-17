@extends('master')

@section('title', 'Purchase Entry')
@section('breadcrumb', 'Purchase Entry')
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

    .supplierCard {
        height: 210px;
    }

    .btnCart,
    .btnCart:hover,
    .btnCart:focus {
        background: #228dc1;
        color: #fff;
    }
</style>
@endpush
@section('content')
<div class="row" id="purchase">
    <div class="col-12 col-md-12">
        <div class="card mb-0">
            <div class="card-body p-2">
                <div class="row d-flex align-items-center">
                    <label class="form-label col-4 col-md-1 mb-md-0" for="name">Invoice:</label>
                    <div class="col-8 col-md-2 mb-1 mb-md-0">
                        <input type="text" readonly class="form-control" autocomplete="off" id="invoice" name="invoice" v-model="purchase.invoice" />
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
                        <input type="date" class="form-control" autocomplete="off" id="date" name="date" v-model="purchase.date" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-9 mt-2">
        <div class="row">
            <div class="col-12 col-md-6 pe-md-1 mb-1">
                <div class="card mb-0 supplierCard">
                    <div class="card-header py-2">
                        <h3 class="m-0 card-title p-0">Supplier Info</h3>
                    </div>
                    <div class="card-body p-3 py-2">
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Supplier:</label>
                            <div class="col-8 col-md-8">
                                <v-select :options="suppliers" v-model="selectedSupplier" label="display_name" @input="onChangeSupplier" @search="onSearchSupplier"></v-select>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Name:</label>
                            <div class="col-8 col-md-8">
                                <input type="text" :disabled="selectedSupplier.type == 'regular'" class="form-control" v-model="selectedSupplier.name" />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Phone:</label>
                            <div class="col-8 col-md-8">
                                <input type="text" :disabled="selectedSupplier.type == 'regular'" class="form-control" v-model="selectedSupplier.phone" />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Address:</label>
                            <div class="col-8 col-md-8">
                                <textarea type="text" :disabled="selectedSupplier.type == 'regular'" class="form-control" v-model="selectedSupplier.address"></textarea>
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
                                    <input type="number" min="0" step="any" class="form-control" v-model="selectedProduct.purchase_rate" @input="productTotal" />
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
                            <div class="form-group row">
                                <div class="col-12 col-md-12 text-end">
                                    <button type="submit" class="btn btn-sm btnCart w-50">AddToCart</button>
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
                            <td class="text-center" v-text="cart.purchase_rate"></td>
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
                                <textarea class="form-control" v-model="purchase.note" placeholder="Enter note here"></textarea>
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
                            <input type="number" v-model="purchase.subtotal" min="0" step="any" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Discount</label>
                        <div class="col-4 col-md-6">
                            <div class="input-group">
                                <input type="number" v-model="discountPercent" id="discountPercent" @input="calculateTotal" min="0" step="any" class="form-control" />
                                <button class="btn btn-sm btn-outline-secondary">%</button>
                            </div>
                        </div>
                        <div class="col-4 col-md-6">
                            <div class="input-group">
                                <input type="number" v-model="purchase.discount" id="discount" @input="calculateTotal" min="0" step="any" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Vat</label>
                        <div class="col-4 col-md-6">
                            <div class="input-group">
                                <input type="number" v-model="vatPercent" id="vatPercent" @input="calculateTotal" min="0" step="any" class="form-control" />
                                <button class="btn btn-sm btn-outline-secondary">%</button>
                            </div>
                        </div>
                        <div class="col-4 col-md-6">
                            <div class="input-group">
                                <input type="number" v-model="purchase.vat" id="vat" @input="calculateTotal" min="0" step="any" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="subtotal" class="col-4 col-md-12 form-label pe-0 mb-0">Transport Cost</label>
                        <div class="col-8 col-md-12">
                            <input type="number" v-model="purchase.transport_cost" @input="calculateTotal" min="0" step="any" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Total</label>
                        <div class="col-8 col-md-12">
                            <input type="number" v-model="purchase.total" min="0" step="any" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Paid</label>
                        <div class="col-8 col-md-12">
                            <input type="number" v-model="purchase.paid" id="paid" @input="calculateTotal" min="0" step="any" class="form-control" :disabled="selectedSupplier.type == 'general'" />
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Due</label>
                        <div class="col-4 col-md-6">
                            <input type="number" v-model="purchase.due" min="0" step="any" class="form-control" readonly />
                        </div>
                        <div class="col-4 col-md-6">
                            <input type="number" v-model="purchase.previous_due" min="0" step="any" class="form-control text-danger" readonly />
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <div class="form-group row mb-2">
                        <div class="col-6 col-md-6">
                            <button type="submit" :disabled="onProgress" class="btn btn-success w-100" v-text="purchase.id != '' ? 'Update' : 'Save'"></button>
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
        el: "#purchase",
        data() {
            return {
                purchase: {
                    id: "{{$id}}",
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
                    note: ''
                },
                discountPercent: 0,
                vatPercent: 0,
                suppliers: [],
                selectedSupplier: {
                    id: '',
                    display_name: 'Walk In Supplier',
                    name: 'Walk In Supplier',
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
                carts: [],
                onProgress: false,
            }
        },

        async created() {
            this.getEmployee();
            this.getSupplier();
            this.getProduct();
            if (this.purchase.id != '') {
                await this.getPurchase();
            }
        },

        methods: {
            getEmployee() {
                axios.get('/get-employee')
                    .then(res => {
                        this.employees = res.data;
                    })
            },
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
                        }, {
                            id: '',
                            display_name: 'New Supplier',
                            name: '',
                            phone: '',
                            address: '',
                            type: 'new'
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
                if (this.selectedSupplier == null) {
                    this.selectedSupplier = {
                        id: '',
                        display_name: 'Walk In Supplier',
                        name: 'Walk In Supplier',
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
                this.selectedProduct.total = parseFloat(this.selectedProduct.purchase_rate * this.selectedProduct.quantity).toFixed(2);
            },

            addToCart() {
                if (this.selectedProduct.id == '') {
                    toastr.error('Please select a product')
                    return;
                }
                let cart = this.carts.find(item => item.id == this.selectedProduct.id);
                if (cart) {
                    cart.quantity = parseFloat(cart.quantity) + parseFloat(this.selectedProduct.quantity);
                    cart.total = parseFloat(cart.purchase_rate * cart.quantity).toFixed(2);
                } else {
                    this.carts.push({
                        id: this.selectedProduct.id,
                        code: this.selectedProduct.code,
                        category_name: this.selectedProduct.category?.name,
                        name: this.selectedProduct.name,
                        unit_name: this.selectedProduct.unit?.name,
                        purchase_rate: this.selectedProduct.purchase_rate,
                        quantity: this.selectedProduct.quantity,
                        total: this.selectedProduct.total,
                        sale_rate: this.selectedProduct.sale_rate
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
                    item.total = parseFloat(item.purchase_rate * item.quantity).toFixed(2);
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
                this.purchase.subtotal = this.carts.reduce((pr, cu) => {
                    return pr + parseFloat(cu.total)
                }, 0).toFixed(2);
                if (event.target.id == 'discount') {
                    this.discountPercent = (this.purchase.discount * 100) / this.purchase.subtotal;
                }
                if (event.target.id == 'discountPercent') {
                    this.purchase.discount = parseFloat((this.discountPercent * this.purchase.subtotal) / 100).toFixed(2);
                }
                this.purchase.total = parseFloat(this.purchase.subtotal - this.purchase.discount).toFixed(2);
                if (event.target.id == 'vat') {
                    this.vatPercent = (this.purchase.vat * 100) / this.purchase.total;
                }
                if (event.target.id == 'vatPercent') {
                    this.purchase.vat = parseFloat((this.vatPercent * this.purchase.total) / 100).toFixed(2);
                }
                this.purchase.total = parseFloat(+this.purchase.total + +this.purchase.vat + +this.purchase.transport_cost).toFixed(2);
                if (event.target.id == 'paid') {
                    this.purchase.due = parseFloat(this.purchase.total - this.purchase.paid).toFixed(2);
                } else {
                    this.purchase.paid = this.purchase.total;
                    this.purchase.due = parseFloat(0).toFixed(2);
                }
            },

            saveData(event) {
                this.purchase.employee_id = this.selectedEmployee ? this.selectedEmployee.id : "";
                let formdata = {
                    purchase: this.purchase,
                    supplier: this.selectedSupplier,
                    carts: this.carts
                }
                let url = this.purchase.id != '' ? '/update-purchase' : '/purchase'
                this.onProgress = true;
                axios.post(url, formdata)
                    .then(async res => {
                        toastr.success(res.data.message);
                        this.clearData();
                        history.pushState(null, '', '/purchase');
                        this.purchase.invoice = res.data.invoice;
                        if (confirm('Do you want to go to the invoice page?')) {
                            window.open(`/purchase-invoice/${res.data.purchaseId}`, '_blank');
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
                this.purchase = {
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
                this.selectedSupplier = {
                    id: '',
                    display_name: 'Walk In Supplier',
                    name: 'Walk In Supplier',
                    phone: '',
                    address: '',
                    type: 'general'
                };
                this.selectedEmployee = null;
                this.carts = [];
                this.getSupplier();
            },

            async getPurchase() {
                await axios.post('/get-purchase', {
                    purchaseId: this.purchase.id
                }).then(res => {
                    let purchase = res.data[0];
                    let purchaseKeys = Object.keys(this.purchase);
                    purchaseKeys.forEach(key => {
                        this.purchase[key] = purchase[key];
                    })

                    purchase.details.map(item => {
                        let detail = {
                            id: item.product_id,
                            code: item.code,
                            category_name: item.category_name,
                            name: item.name,
                            unit_name: item.unit_name,
                            purchase_rate: item.purchase_rate,
                            quantity: item.quantity,
                            total: item.total,
                            sale_rate: item.sale_rate
                        };
                        this.carts.push(detail);
                    })

                    this.selectedSupplier = {
                        id: purchase.supplier_id ?? '',
                        name: purchase.supplier_name,
                        phone: purchase.supplier_phone,
                        address: purchase.supplier_address,
                        display_name: purchase.supplier_type == 'general' ? 'Walk In Supplier' : `${purchase.supplier_name} - ${purchase.supplier_phone} - ${purchase.supplier_address}`,
                        type: purchase.supplier_type
                    }

                    setTimeout(() => {
                        this.selectedEmployee = this.employees.find(item => item.id == purchase.employee_id);
                    }, 1000);
                })
            }
        },
    })
</script>
@endpush