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

    tr td {
        padding: 6px !important;
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
    <div class="col-12 col-md-12 mt-2">
        <div class="card mb-0">
            <div class="card-body p-2">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" style="padding: 10px;" placeholder="Search Product" v-model="searchProductText"
                        @input="onSearchProduct($event)"
                        @keydown.down.prevent="moveHighlight(1)"
                        @keydown.up.prevent="moveHighlight(-1)"
                        @keydown.enter.prevent="addToCart(products[highlightedIndex])"
                        autocomplete="off"
                        ref="searchInput" />
                    <button class="btn btn-primary" type="button" style="font-size: 22px;"><i class="bi bi-upc"></i></button>
                </div>
                <div v-if="products.length > 0 && searchProductText" class="position-relative">
                    <ul class="list-group position-absolute w-100 shadow" style="z-index: 1000; max-height: 250px; overflow-y: auto;"
                        tabindex="0"
                        ref="productList">
                        <li
                            v-for="(product, idx) in products"
                            :key="product.id"
                            :class="['list-group-item', 'd-flex', 'justify-content-between', 'align-items-center', 'list-group-item-action', {active: idx === highlightedIndex}]"
                            style="cursor: pointer;"
                            @click="addToCart(product)"
                            @mouseenter="highlightedIndex = idx">
                            <div>
                                <strong v-text="product.name"></strong>
                                <span class="text-muted small" v-if="product.code"> - <span v-text="product.code"></span></span>
                                <span class="badge bg-secondary ms-2" v-if="product.stock == undefined">
                                    Stock: <span v-text="0"></span>
                                </span>
                            </div>
                            <span class="badge bg-primary" v-if="product.sale_rate">৳ <span v-text="product.sale_rate"></span></span>
                        </li>
                        <li v-if="products.length == '0'" class="list-group-item text-center text-muted">
                            No products found
                        </li>
                    </ul>
                </div>
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
                        <div class="input-group input-group-sm" style="width: 180px; margin: 0 auto;">
                            <button class="btn btn-outline-secondary" type="button" @click="cart.quantity = Math.max(1, +cart.quantity - 1); quantityRateTotal(cart)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" min="1" step="any"
                                class="form-control text-center"
                                style="width:80px;padding: 3px 6px; outline: none; border-radius: 0;border-color: #000;"
                                v-model="cart.quantity"
                                @input="quantityRateTotal(cart)" />
                            <button class="btn btn-outline-secondary" type="button" @click="cart.quantity = +cart.quantity + 1; quantityRateTotal(cart)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="text-center" v-text="cart.unit_name"></td>
                    <td class="text-center">
                        <div class="input-group input-group-sm" style="width: 210px; margin: 0 auto;">
                            <button class="btn btn-outline-secondary" type="button" @click="cart.sale_rate = Math.max(0, +cart.sale_rate - 1); quantityRateTotal(cart)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" min="0" step="any"
                                class="form-control text-center"
                                style="width:120px;padding: 3px 6px; outline: none; border-radius: 0;border-color: #000;"
                                v-model="cart.sale_rate"
                                @input="quantityRateTotal(cart)" />
                            <button class="btn btn-outline-secondary" type="button" @click="cart.sale_rate = +cart.sale_rate + 1; quantityRateTotal(cart)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="text-center" v-text="cart.total"></td>
                    <td class="text-center">
                        <i @click="removeCart(index)" class="bi bi-trash3 text-danger" style="cursor: pointer;"></i>
                    </td>
                </tr>
            </tbody>
            <tbody v-if="carts.length == 0" :class="carts.length == 0 ? '' : 'd-none'">
                <tr>
                    <td colspan="8" style="padding: 6px !important;" class="text-center">Cart is Empty</td>
                </tr>
            </tbody>
        </table>
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
                products: [],
                searchProductText: "",
                highlightedIndex: 0,
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
            this.getEmployee();
            this.getBank();
            this.getCustomer();
            if (this.sale.id != '') {
                await this.getSale();
            }
        },

        methods: {
            async moveHighlight(direction) {
                if (!this.products.length) return;
                this.highlightedIndex += direction;
                if (this.highlightedIndex < 0) {
                    this.highlightedIndex = this.products.length - 1;
                } else if (this.highlightedIndex >= this.products.length) {
                    this.highlightedIndex = 0;
                }
                this.$nextTick(() => {
                    const list = this.$refs.productList;
                    if (list && list.children[this.highlightedIndex]) {
                        list.children[this.highlightedIndex].scrollIntoView({
                            block: 'nearest'
                        });
                    }
                });
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

            // async getProduct() {
            //     await axios.post('/get-product', {
            //             forSearch: 'yes'
            //         })
            //         .then(res => {
            //             this.products = res.data.map(item => {
            //                 item.sale_rate = this.sale.sale_type == 'retail' ? item.sale_rate : item.wholesale_rate;
            //                 item.id = item.id;
            //                 item.code = item.code ? String(item.code) : '';
            //                 return item;
            //             });
            //         })
            // },

            async onSearchProduct(event) {
                if (event.target.value.length > 2) {
                    await axios.post("/get-product", {
                            search: event.target.value,
                            is_service: 'false'
                        })
                        .then(res => {
                            this.products = res.data.map(item => {
                                item.sale_rate = this.sale.sale_type == 'retail' ? item.sale_rate : item.wholesale_rate;
                                item.id = item.id;
                                item.code = item.code ? String(item.code) : '';
                                return item;
                            });
                        })
                } else {
                    this.products = [];
                }
            },

            async addToCart(product) {
                const exists = this.carts.find(p => p.id === product.id);
                if (!exists) {
                    let cart = {
                        id: product.id,
                        code: product.code ? String(product.code) : '',
                        category_name: product.category?.name,
                        name: product.name,
                        unit_name: product.unit?.name,
                        purchase_rate: product.purchase_rate,
                        sale_rate: product.sale_rate,
                        quantity: 1,
                        total: parseFloat(product.sale_rate).toFixed(2),
                    };
                    this.carts.push(cart);
                } else {
                    exists.quantity = Number(exists.quantity) + 1;
                    exists.total = (exists.quantity * exists.sale_rate).toFixed(2);
                }

                this.searchProductText = '';
                this.products = [];
                await this.calculateTotal();
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

            async removeCart(sl) {
                this.carts.splice(sl, 1);
                await this.calculateTotal();
            },

            async calculateTotal() {
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
</script>
@endpush