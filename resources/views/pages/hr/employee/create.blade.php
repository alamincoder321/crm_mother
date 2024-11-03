@extends('master')

@section('title', 'Employee Entry')
@section('breadcrumb', 'Employee Entry')
@section('content')
<div class="row" id="employee">
    <div class="col-12 col-md-12">
        <div class="card mb-0">
            <div class="card-body">
                <h5 class="card-title">Employee Entry Form</h5>
                <form @submit.prevent="saveData($event)">
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="department_id">Department:</label>
                                <div class="col-8 col-md-9">
                                    <v-select :options="departments" v-model="selectedDepartment" label="name"></v-select>
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="designation_id">Designation:</label>
                                <div class="col-8 col-md-9">
                                    <v-select :options="designations" v-model="selectedDesignation" label="name"></v-select>
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="name">Name:</label>
                                <div class="col-8 col-md-9">
                                    <input type="text" class="form-control" autocomplete="off" id="name" name="name" v-model="employee.name" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="address">Address:</label>
                                <div class="col-8 col-md-9">
                                    <input type="text" class="form-control" autocomplete="off" id="address" name="address" v-model="employee.address" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="phone">Mobile:</label>
                                <div class="col-8 col-md-9">
                                    <input type="number" class="form-control" autocomplete="off" id="phone" name="phone" v-model="employee.phone" />
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label class="form-label col-4 col-md-3" for="email">Email:</label>
                                <div class="col-8 col-md-9">
                                    <input type="email" class="form-control" autocomplete="off" id="email" name="email" v-model="employee.email" />
                                </div>
                            </div>
                            <div class="mt-1 row">
                                <label class="col-md-3 col-12"></label>
                                <div class="col-md-3 col-12">
                                    <label for="status">
                                        <input type="checkbox" name="status" id="status" :false-value="'p'" :true-value="'a'" v-model="employee.status" />
                                        IsActive
                                    </label>
                                </div>
                                <div class="col-md-6 col-12 text-end">
                                    <button class="btn btn-danger" type="button">Reset</button>
                                    <button class="btn btn-primary" type="submit" :disabled="onProgress">
                                        <span v-if="employee.id == ''">Save</span>
                                        <span v-if="employee.id != ''">Update</span>
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
        <vue-good-table :columns="columns" :rows="employees" :fixed-header="false" :pagination-options="{
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
        el: "#employee",
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
                        label: "Mobile",
                        field: 'phone'
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
                employee: {
                    id: '',
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    status: 'a',
                    image: ''
                },
                employees: [],
                departments: [],
                selectedDepartment: null,
                designations: [],
                selectedDesignation: null,

                imageSrc: "/noImage.jpg",
                onProgress: false,
            }
        },

        created() {
            this.getDepartment();
            this.getDesignation();
            this.getEmployee();
        },

        methods: {
            getDepartment() {
                axios.post('/get-department')
                    .then(res => {
                        this.departments = res.data;
                    })
            },
            getDesignation() {
                axios.post('/get-designation')
                    .then(res => {
                        this.designations = res.data;
                    })
            },
            getEmployee() {
                axios.post('/get-employee')
                    .then(res => {
                        this.employees = res.data.map((item, index) => {
                            item.statusTxt = item.status == 'a' ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-warning'>Deactive</span>";
                            item.imgSrc = `<a href="${item.image ? '/'+item.image : '/noImage.jpg'}"><img src="${item.image ? '/'+item.image : '/noImage.jpg'}" style="width:30px;height:30px;" class="rounded"/></a>`;
                            return item;
                        });
                    })
            },
            saveData(event) {
                let formdata = new FormData(event.target);
                formdata.append('id', this.employee.id);
                formdata.append('image', this.employee.image);
                formdata.append('status', this.employee.status);
                formdata.append('department_id', this.selectedDepartment ? this.selectedDepartment.id : '');
                formdata.append('designation_id', this.selectedDesignation ? this.selectedDesignation.id : '');
                let url = this.employee.id != '' ? '/update-employee' : '/employee'
                this.onProgress = true;
                axios.post(url, formdata)
                    .then(res => {
                        toastr.success(res.data.message);
                        this.clearData();
                        this.getEmployee();
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
                let keys = Object.keys(this.employee);
                keys.forEach(item => {
                    this.employee[item] = row[item];
                })
                this.selectedDepartment = {
                    id: row.department_id,
                    name: row.department?.name
                }
                this.imageSrc = row.image ? '/' + row.image : "/noImage.jpg";
            },

            deleteData(rowId) {
                if (!confirm('Are you sure ?')) {
                    return;
                }
                axios.post('/delete-employee', {
                        id: rowId
                    })
                    .then(res => {
                        if (res.data.status) {
                            toastr.success(res.data.message);
                            this.getEmployee();
                        }
                    })
            },

            clearData() {
                this.employee = {
                    id: '',
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    department_id: '',
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
                            this.employee.image = new File([resizedImage], event.target.files[0].name, {
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