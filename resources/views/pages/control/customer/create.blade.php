@extends('master')

@section('title', 'Customer Entry')
@section('breadcrumb', 'Customer Entry')
@section('content')
<div class="row" id="customer">
    <div class="col-12 col-md-12">
        <div class="card mb-0">
            <div class="card-body">
                <h5 class="card-title">Customer Entry Form</h5>
                <form @submit.prevent="saveData($event)">
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="name">Name:</label>
                                <div class="col-8 col-md-9">
                                    <input type="text" class="form-control" autocomplete="off" id="name" name="name" v-model="customer.name" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="owner">Owner:</label>
                                <div class="col-8 col-md-9">
                                    <input type="text" class="form-control" autocomplete="off" id="owner" name="owner" v-model="customer.owner" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="address">Address:</label>
                                <div class="col-8 col-md-9">
                                    <input type="text" class="form-control" autocomplete="off" id="address" name="address" v-model="customer.address" />
                                </div>
                            </div>

                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="area_id">Area:</label>
                                <div class="col-8 col-md-9">
                                    <v-select :options="areas" v-model="selectedArea" label="name"></v-select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="phone">Mobile:</label>
                                <div class="col-8 col-md-9">
                                    <input type="number" class="form-control" autocomplete="off" id="phone" name="phone" v-model="customer.phone" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="email">Email:</label>
                                <div class="col-8 col-md-9">
                                    <input type="email" class="form-control" autocomplete="off" id="email" name="email" v-model="customer.email" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="previous_due">Prev. Balance:</label>
                                <div class="col-8 col-md-3 pe-md-0">
                                    <input type="number" min="0" step="any" class="form-control" autocomplete="off" id="previous_due" name="previous_due" v-model="customer.previous_due" />
                                </div>
                                <label class="form-label col-4 col-md-3 mb-md-0 d-flex align-items-center" for="credit_limit">Credit Limit:</label>
                                <div class="col-8 col-md-3 ps-md-0">
                                    <input type="number" min="0" step="any" class="form-control" autocomplete="off" id="credit_limit" name="credit_limit" v-model="customer.credit_limit" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3 pe-md-0" for="customer_type">Customer Type:</label>
                                <div class="col-8 col-md-9">
                                    <label for="retail" class="form-label">
                                        <input type="radio" name="customer_type" value="retail" id="retail" v-model="customer.customer_type">
                                        Retail
                                    </label>
                                    <label for="wholesale" class="form-label ms-md-3">
                                        <input type="radio" name="customer_type" value="wholesale" id="wholesale" v-model="customer.customer_type">
                                        Wholesale
                                    </label>
                                </div>
                            </div>
                            <div class="mt-md-0 mt-1 row">
                                <label class="col-md-3 col-12"></label>
                                <div class="col-md-3 col-12">
                                    <label for="status" class="form-label">
                                        <input type="checkbox" name="status" id="status" :false-value="'p'" :true-value="'a'" v-model="customer.status" />
                                        IsActive
                                    </label>
                                </div>
                                <div class="col-md-6 col-12 text-end">
                                    <button class="btn btn-danger" type="button">Reset</button>
                                    <button class="btn btn-primary" type="submit" :disabled="onProgress">
                                        <span v-if="customer.id == ''">Save</span>
                                        <span v-if="customer.id != ''">Update</span>
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
        <vue-good-table :columns="columns" :rows="customers" :fixed-header="false" :pagination-options="{
                enabled: true,
                perPage: 100,
            }" :search-options="{ enabled: true }" :line-numbers="true" styleClass="vgt-table condensed" max-height="550px">
            <template #table-row="props">
                <span class="d-flex gap-2 justify-content-end" v-if="props.column.field == 'before'">
                    <a href="" title="edit" @click.prevent="editData(props.row)">
                        <i class="bi bi-pen text-info" style="font-size: 14px;"></i>
                    </a>
                    <a href="" title="delete" @click.prevent="deleteData(props.row.id)">
                        <i class="bi bi-trash text-danger" style="font-size: 14px;"></i>
                    </a>
                </span>
            </template>
        </vue-good-table>
    </div>
</div>
@endsection

@push("js")
<script>
    new Vue({
        el: "#customer",
        data() {
            return {
                columns: [{
                        label: "Image",
                        field: 'imgSrc',
                        html: true
                    },
                    {
                        label: "Code",
                        field: 'code'
                    },
                    {
                        label: "Name",
                        field: 'name'
                    },
                    {
                        label: "Owner",
                        field: 'owner'
                    },
                    {
                        label: "Customer_Type",
                        field: 'customer_type'
                    },
                    {
                        label: "Mobile",
                        field: 'phone'
                    },
                    {
                        label: "Area",
                        field: 'area.name'
                    },
                    {
                        label: "Prev_Balance",
                        field: 'previous_due'
                    },
                    {
                        label: "Credit_Limit",
                        field: 'credit_limit'
                    },
                    {
                        label: "Status",
                        field: 'statusTxt',
                        html: true,
                    },
                    {
                        label: "Added_By",
                        field: 'ad_user.username',
                        html: true,
                    },
                    {
                        label: "Updated_By",
                        field: 'up_user.username',
                        html: true,
                    },
                    {
                        label: "Action",
                        field: "before"
                    }
                ],
                customer: {
                    id: '',
                    name: '',
                    owner: '',
                    email: '',
                    phone: '',
                    customer_type: 'retail',
                    address: '',
                    area_id: '',
                    previous_due: 0,
                    credit_limit: 0,
                    status: 'a',
                    image: ''
                },
                customers: [],
                areas: [],
                selectedArea: null,

                imageSrc: "/noImage.jpg",
                onProgress: false,
            }
        },

        created() {
            this.getArea();
            this.getCustomer();
        },

        methods: {
            getArea() {
                axios.post('/get-area')
                    .then(res => {
                        this.areas = res.data;
                    })
            },
            getCustomer() {
                axios.post('/get-customer')
                    .then(res => {
                        this.customers = res.data.map((item, index) => {
                            item.statusTxt = item.status == 'a' ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-warning'>Deactive</span>";
                            item.imgSrc = `<a href="${item.image ? '/'+item.image : '/noImage.jpg'}"><img src="${item.image ? '/'+item.image : '/noImage.jpg'}" style="width:30px;height:30px;" class="rounded"/></a>`;
                            return item;
                        });
                    })
            },
            saveData(event) {
                let formdata = new FormData(event.target);
                formdata.append('id', this.customer.id);
                formdata.append('image', this.customer.image);
                formdata.append('status', this.customer.status);
                formdata.append('area_id', this.selectedArea ? this.selectedArea.id : '');
                let url = this.customer.id != '' ? '/update-customer' : '/customer'
                this.onProgress = true;
                axios.post(url, formdata)
                    .then(res => {
                        toastr.success(res.data.message);
                        this.clearData();
                        this.getCustomer();
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
                let keys = Object.keys(this.customer);
                keys.forEach(item => {
                    this.customer[item] = row[item];
                })
                this.selectedArea = {
                    id: row.area_id,
                    name: row.area?.name
                }
                this.imageSrc = row.image ? '/' + row.image : "/noImage.jpg";
            },

            deleteData(rowId) {
                if (!confirm('Are you sure ?')) {
                    return;
                }
                axios.post('/delete-customer', {
                        id: rowId
                    })
                    .then(res => {
                        if (res.data.status) {
                            toastr.success(res.data.message);
                            this.getCustomer();
                        }
                    })
            },

            clearData() {
                this.customer = {
                    id: '',
                    name: '',
                    owner: '',
                    email: '',
                    phone: '',
                    customer_type: 'retail',
                    address: '',
                    area_id: '',
                    previous_due: 0,
                    credit_limit: 0,
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
                            this.customer.image = new File([resizedImage], event.target.files[0].name, {
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