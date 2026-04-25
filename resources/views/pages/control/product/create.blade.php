@extends('master')

@section('title', 'Product Entry')
@section('breadcrumb', 'Product Entry')
@push('style')
<style>
    .table>thead>tr>th {
        text-align: center !important;
        background-color: gray;
        color: #fff;
    }

    tr td,
    tr th {
        vertical-align: middle !important;
        text-align: center !important;
    }
</style>
@endpush
@section('content')
<div class="row" id="product">
    <div class="col-12 col-md-12">
        <div class="card mb-0">
            <div class="card-body">
                <h5 class="card-title">Product Entry Form</h5>
                <form @submit.prevent="saveData($event)">
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="code">Product Code:</label>
                                <div class="col-8 col-md-9">
                                    <input type="text" class="form-control" autocomplete="off" id="code" name="code" v-model="product.code" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="brand_id">Brand:</label>
                                <div class="col-8 col-md-9">
                                    <v-select :options="brands" v-model="selectedBrand" label="name"></v-select>
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="category_id">Category:</label>
                                <div class="col-8 col-md-9">
                                    <v-select :options="categories" v-model="selectedCategory" label="name"></v-select>
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="name">Name:</label>
                                <div class="col-8 col-md-9">
                                    <input type="text" class="form-control" autocomplete="off" id="name" name="name" v-model="product.name" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="category_id">Unit:</label>
                                <div class="col-8 col-md-9">
                                    <v-select :options="units" v-model="selectedUnit" label="name"></v-select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="per_unit">Per Unit:</label>
                                <div class="col-8 col-md-3">
                                    <input type="number" min="0" step="any" class="form-control" autocomplete="off" id="per_unit" name="per_unit" v-model="product.per_unit" />
                                </div>
                                <label class="form-label col-4 col-md-3" for="convertion_name">Conv. Name:</label>
                                <div class="col-8 col-md-3">
                                    <input type="text" class="form-control" autocomplete="off" id="convertion_name" name="convertion_name" v-model="product.convertion_name" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="vat">Vat:</label>
                                <div class="col-8 col-md-3">
                                    <input type="number" min="0" step="any" class="form-control" autocomplete="off" id="vat" name="vat" v-model="product.vat" />
                                </div>
                                <label class="form-label col-4 col-md-3" for="reorder">Reorder:</label>
                                <div class="col-8 col-md-3">
                                    <input type="number" min="0" step="any" class="form-control" autocomplete="off" id="reorder" name="reorder" v-model="product.reorder" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="purchase_rate">PurchaseRate:</label>
                                <div class="col-8 col-md-9">
                                    <input type="number" min="0" step="any" class="form-control" autocomplete="off" id="purchase_rate" name="purchase_rate" v-model="product.purchase_rate" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="sale_rate">SaleRate:</label>
                                <div class="col-8 col-md-9">
                                    <input type="number" min="0" step="any" class="form-control" autocomplete="off" id="sale_rate" name="sale_rate" v-model="product.sale_rate" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="wholesale_rate">WholeSale:</label>
                                <div class="col-8 col-md-9">
                                    <input type="number" min="0" step="any" class="form-control" autocomplete="off" id="wholesale_rate" name="wholesale_rate" v-model="product.wholesale_rate" />
                                </div>
                            </div>
                            <div class="mt-md-0 mt-1 row">
                                <div class="col-md-3 col-12">
                                    <label for="is_service" class="form-label">
                                        <input type="checkbox" id="is_service" :false-value="0" :true-value="1" v-model="product.is_service" />
                                        IsService
                                    </label>
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="status" class="form-label">
                                        <input type="checkbox" id="status" :false-value="'p'" :true-value="'a'" v-model="product.status" />
                                        IsActive
                                    </label>
                                </div>
                                <div class="col-md-6 col-12 text-end">
                                    <button class="btn btn-danger" type="button">Reset</button>
                                    <button class="btn btn-primary" type="submit" :disabled="onProgress">
                                        <span v-if="product.id == ''">Save</span>
                                        <span v-if="product.id != ''">Update</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 mt-2 mt-md-0">
                            <div class="form-group ImageBackground">
                                <span class="text-danger">(150 X 150)PX</span>
                                <img :src="imageSrc" class="imageShow" />
                                <label for="image">Upload Image</label>
                                <input type="file" id="image" class="form-control shadow-none" @change="imageUrl" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-12 mt-1">
        <!-- FILTER -->
        <input type="text" class="form-control w-25 rounded-0 mb-1" v-model="filter" placeholder="Search..." @input="getProduct">
        <div style="overflow-x: auto;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Purchase Rate</th>
                        <th>Sale Rate</th>
                        <th>Unit</th>
                        <th>IsProduct</th>
                        <th>Status</th>
                        <th>Added_By</th>
                        <th>Updated_By</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(row, index) in products" :key="row.id">
                        <td>@{{ row.sl }}</td>
                        <td>@{{ row.code }}</td>
                        <td>@{{ row.name }}</td>
                        <td>@{{ row.brand?.name }}</td>
                        <td>@{{ row.category?.name }}</td>
                        <td>@{{ row.purchase_rate }}</td>
                        <td>@{{ row.sale_rate }}</td>
                        <td>@{{ row.unit?.name }}</td>
                        <td v-html="row.isService"></td>
                        <td v-html="row.statusTxt"></td>
                        <td>@{{ row.ad_user?.name }}</td>
                        <td>@{{ row.up_user?.name }}</td>
                        <td>
                            <a :href="`/barcode/${row.id}`" title="barcode">
                                <i class="bi bi-upc-scan text-warning" style="font-size: 14px;margin-right: 5px;"></i>
                            </a>
                            <a href="" title="edit" @click.prevent="editData(row)">
                                <i class="bi bi-pen text-info" style="font-size: 14px;"></i>
                            </a>
                            <a href="" title="delete" @click.prevent="deleteData(row.id)">
                                <i class="bi bi-trash text-danger" style="font-size: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="text-left mt-2">
            <button type="button" class="filterBtn" @click="changePage(page - 1)" :disabled="page == 1"> Prev </button>

            <button
                type="button"
                v-for="p in pageNumbers"
                :key="p" @click="changePage(p)" :class="['filterBtn', p === page ? 'bg-primary text-white' : '']"> @{{ p }}
            </button>

            <button type="button" class="filterBtn" @click="changePage(page + 1)" :disabled="page == total_page"> Next </button>
        </div>
    </div>
</div>
@endsection

@push("js")
<script>
    new Vue({
        el: "#product",
        data() {
            return {
                page: 1,
                per_page: 10,
                total_page: 0,
                filter: '',

                product: {
                    id: '',
                    code: "{{generateCode('Product', 'PI')}}",
                    brand_id: '',
                    category_id: '',
                    unit_id: '',
                    name: '',
                    vat: 0,
                    reorder: 0,
                    per_unit: 0,
                    convertion_name: '',
                    purchase_rate: 0,
                    sale_rate: 0,
                    wholesale_rate: 0,
                    is_service: 0,
                    status: 'a',
                    image: ''
                },
                products: [],
                categories: [],
                selectedCategory: null,
                brands: [],
                selectedBrand: null,
                units: [],
                selectedUnit: null,

                imageSrc: "/noImage.jpg",
                onProgress: false,
            }
        },

        computed: {
            pageNumbers() {
                let pages = [];

                let total = this.total_page;
                let current = this.page;

                if (total <= 7) {
                    for (let i = 1; i <= total; i++) {
                        pages.push(i);
                    }
                } else {
                    if (current <= 4) {
                        pages = [1, 2, 3, 4, 5, '...', total];
                    } else if (current >= total - 3) {
                        pages = [1, '...', total - 4, total - 3, total - 2, total - 1, total];
                    } else {
                        pages = [1, '...', current - 1, current, current + 1, '...', total];
                    }
                }

                return pages;
            }
        },

        created() {
            this.getBrand();
            this.getCategory();
            this.getUnit();
            this.getProduct();
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
            getUnit() {
                axios.post('/get-unit')
                    .then(res => {
                        this.units = res.data;
                    })
            },

            getProduct() {
                axios.post(`/get-product?page=${this.page}&per_page=${this.per_page}&search=${this.filter}`)
                    .then(res => {
                        this.total_page = res.data.last_page;
                        this.products = res.data.data.map((item, index) => {
                            item.sl = ((res.data.current_page - 1) * this.per_page) + index + 1;
                            item.statusTxt = item.status == 'a' ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-warning'>Deactive</span>";
                            item.imgSrc = `<a href="${item.image ? '/'+item.image : '/noImage.jpg'}"><img src="${item.image ? '/'+item.image : '/noImage.jpg'}" style="width:30px;height:30px;" class="rounded"/></a>`;
                            item.isService = item.is_service == 0 ? "<span class='badge bg-success'>Yes</span>" : "<span class='badge bg-warning'>No</span>";
                            return item;
                        });
                    });
            },

            changePage(p) {
                if (p === '...') return;
                if (p < 1 || p > this.total_page) return;

                this.page = p;
                this.getProduct();
            },

            saveData(event) {
                let formdata = new FormData(event.target);
                formdata.append('id', this.product.id);
                formdata.append('image', this.product.image);
                formdata.append('status', this.product.status);
                formdata.append('is_service', this.product.is_service);
                formdata.append('brand_id', this.selectedBrand ? this.selectedBrand.id : '');
                formdata.append('category_id', this.selectedCategory ? this.selectedCategory.id : '');
                formdata.append('unit_id', this.selectedUnit ? this.selectedUnit.id : '');
                let url = this.product.id != '' ? '/update-product' : '/product'
                this.onProgress = true;
                axios.post(url, formdata)
                    .then(res => {
                        toastr.success(res.data.message);
                        this.getProduct();
                        this.clearData();
                        this.product.code = res.data.code;
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
                            toastr.error(r.message)
                        }
                    })

            },

            editData(row) {
                let keys = Object.keys(this.product);
                keys.forEach(item => {
                    this.product[item] = row[item];
                })

                if (row.brand_id != null) {
                    this.selectedBrand = this.brands.find(item => item.id == row.brand_id);
                }
                this.selectedCategory = this.categories.find(item => item.id == row.category_id);
                this.selectedUnit = this.units.find(item => item.id == row.unit_id);
                this.imageSrc = row.image ? '/' + row.image : "/noImage.jpg";
            },

            deleteData(rowId) {
                if (!confirm('Are you sure ?')) {
                    return;
                }
                axios.post('/delete-product', {
                        id: rowId
                    })
                    .then(res => {
                        if (res.data.status) {
                            toastr.success(res.data.message);
                            this.getProduct();
                        }
                    })
            },

            clearData() {
                this.product = {
                    id: '',
                    code: "{{generateCode('Product', 'PI')}}",
                    brand_id: '',
                    category_id: '',
                    unit_id: '',
                    name: '',
                    vat: 0,
                    reorder: 0,
                    per_unit: 0,
                    convertion_name: '',
                    purchase_rate: 0,
                    sale_rate: 0,
                    wholesale_rate: 0,
                    is_service: 0,
                    status: 'a',
                    image: ''
                }
                this.imageSrc = "/noImage.jpg";
                this.onProgress = false;
            },

            imageUrl(event) {
                const WIDTH = 150;
                const HEIGHT = 150;
                if (event.target.files[0]) {
                    let reader = new FileReader();
                    reader.readAsDataURL(event.target.files[0]);
                    reader.onload = (ev) => {
                        let img = new Image();
                        img.src = ev.target.result;
                        img.onload = async e => {
                            let canvas = document.createElement('canvas');
                            canvas.width = WIDTH;
                            canvas.height = HEIGHT;
                            const context = canvas.getContext("2d");
                            context.drawImage(img, 0, 0, canvas.width, canvas.height);
                            let new_img_url = context.canvas.toDataURL(event.target.files[0].type);
                            this.imageSrc = new_img_url;
                            const resizedImage = await new Promise(rs => canvas.toBlob(rs, 'image/jpeg', 1))
                            this.product.image = new File([resizedImage], event.target.files[0].name, {
                                type: resizedImage.type
                            });
                        }
                    }
                } else {
                    event.target.value = '';
                }
            }
        },
    })
</script>
@endpush