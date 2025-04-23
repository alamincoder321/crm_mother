@extends('master')

@section('title', 'Purchase Entry')
@section('breadcrumb', 'Purchase Entry')
@push('style')
<style scoped>
    .purTable thead tr th {
        background: #c0ccd1;
        padding: 3px 4px !important;
        color: #fff;
    }
    .supplierCard{
        height: 215px;
    }
</style>
@endpush
@section('content')
<div class="row" id="department">
    <div class="col-12 col-md-12">
        <div class="card mb-0">
            <div class="card-body p-3">
                <div class="row d-flex align-items-center">
                    <label class="form-label col-4 col-md-1 mb-md-0" for="name">Invoice:</label>
                    <div class="col-8 col-md-2">
                        <input type="text" readonly class="form-control" autocomplete="off" id="name" name="name" v-model="department.name" />
                    </div>
                    <label class="form-label col-4 col-md-1 mb-md-0" for="name">Employee:</label>
                    <div class="col-8 col-md-2">
                        <input type="text" class="form-control" autocomplete="off" id="name" name="name" v-model="department.name" />
                    </div>
                    <label class="form-label col-4 col-md-1 mb-md-0" for="name">AddBy:</label>
                    <div class="col-8 col-md-2">
                        <input type="text" readonly class="form-control" autocomplete="off" id="name" name="name" v-model="department.name" />
                    </div>
                    <label class="form-label col-4 col-md-1 mb-md-0" for="name">Date:</label>
                    <div class="col-8 col-md-2">
                        <input type="date" class="form-control" autocomplete="off" id="name" name="name" v-model="department.name" />
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
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Name:</label>
                            <div class="col-8 col-md-8">
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Phone:</label>
                            <div class="col-8 col-md-8">
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-4 form-label">Address:</label>
                            <div class="col-8 col-md-8">
                                <textarea type="text" class="form-control"></textarea>
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
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-3 form-label">Product:</label>
                            <div class="col-8 col-md-9">
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-3 form-label">Rate:</label>
                            <div class="col-8 col-md-4">
                                <input type="number" min="0" step="any" class="form-control" />
                            </div>
                            <label for="" class="col-4 col-md-2 pe-md-0 form-label">Qty:</label>
                            <div class="col-8 col-md-3 ps-md-0">
                                <input type="number" min="0" step="any" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-3 form-label">Total:</label>
                            <div class="col-8 col-md-9">
                                <input type="number" min="0" step="any" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="" class="col-4 col-md-3 form-label">Sale Rate:</label>
                            <div class="col-8 col-md-9">
                                <input type="number" min="0" step="any" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-12 text-end">
                                <button type="button" class="btn btn-secondary w-50">AddToCart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 mt-1">
                <table class="table purTable">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Rate</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th style="width: 3%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3 mt-2 ps-md-1">
        <div class="card mb-0">
            <div class="card-header py-2">
                <h3 class="m-0 card-title p-0">Payment Info</h3>
            </div>
            <div class="card-body p-3 pt-md-2 pb-md-1">
                <div class="form-group row mb-2">
                    <label for="subtotal" class="col-4 col-md-12 form-label mb-0">SubTotal</label>
                    <div class="col-8 col-md-12">
                        <input type="number" min="0" step="any" class="form-control" readonly />
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Discount</label>
                    <div class="col-4 col-md-6">
                        <div class="input-group">
                            <input type="number" min="0" step="any" class="form-control" />
                            <button class="btn btn-sm btn-outline-secondary">%</button>
                        </div>
                    </div>
                    <div class="col-4 col-md-6">
                        <div class="input-group">
                            <input type="number" min="0" step="any" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Vat</label>
                    <div class="col-4 col-md-6">
                        <div class="input-group">
                            <input type="number" min="0" step="any" class="form-control" />
                            <button class="btn btn-sm btn-outline-secondary">%</button>
                        </div>
                    </div>
                    <div class="col-4 col-md-6">
                        <div class="input-group">
                            <input type="number" min="0" step="any" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="subtotal" class="col-4 col-md-12 form-label pe-0 mb-0">Transport Cost</label>
                    <div class="col-8 col-md-12">
                        <input type="number" min="0" step="any" class="form-control" />
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Total</label>
                    <div class="col-8 col-md-12">
                        <input type="number" min="0" step="any" class="form-control" readonly />
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Paid</label>
                    <div class="col-8 col-md-12">
                        <input type="number" min="0" step="any" class="form-control" />
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="subtotal" class="col-4 col-md-12 form-label mb-0">Due</label>
                    <div class="col-4 col-md-6">
                        <input type="number" min="0" step="any" class="form-control" readonly />
                    </div>
                    <div class="col-4 col-md-6">
                        <input type="number" min="0" step="any" class="form-control text-danger" readonly />
                    </div>
                </div>
            </div>
            <div class="card-footer py-2">
                <div class="form-group row mb-2">
                    <div class="col-6 col-md-6">
                        <button type="button" class="btn btn-danger w-100">Reset</button>
                    </div>
                    <div class="col-6 col-md-6">
                        <button type="button" class="btn btn-success w-100">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push("js")
<script>
    new Vue({
        el: "#department",
        data() {
            return {
                department: {
                    id: '',
                    name: '',
                },
                departments: [],
                onProgress: false,
            }
        },

        created() {
            this.getDepartment();
        },

        methods: {
            getDepartment() {
                axios.post('/get-department')
                    .then(res => {
                        this.departments = res.data;
                    })
            },
            saveData(event) {
                let formdata = new FormData(event.target);
                formdata.append('id', this.department.id);
                let url = this.department.id != '' ? '/update-department' : '/department'
                this.onProgress = true;
                axios.post(url, formdata)
                    .then(res => {
                        toastr.success(res.data.message);
                        this.clearData();
                        this.getDepartment();
                    })
                    .catch(err => {
                        this.onProgress = false
                        var r = JSON.parse(err.request.response);
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

            editData(row) {
                let keys = Object.keys(this.department);
                keys.forEach(item => {
                    this.department[item] = row[item];
                })
            },

            deleteData(rowId) {
                if (!confirm('Are you sure ?')) {
                    return;
                }
                axios.post('/delete-department', {
                        id: rowId
                    })
                    .then(res => {
                        if (res.data.status) {
                            toastr.success(res.data.message);
                            this.getDepartment();
                        }
                    })
            },

            clearData() {
                this.department = {
                    id: '',
                    name: '',
                }
                this.onProgress = false;
            },

        },
    })
</script>
@endpush