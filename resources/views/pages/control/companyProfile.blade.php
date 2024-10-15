@extends('master')

@section('title')
Company Profile
@endsection
@section('breadcrumb')
Update Company Profile
@endsection
@section('content')
<div class="row" id="companyProfile">
    <div class="col-md-4">
        <div class="card mb-1 mb-md-0">
            <div class="card-body">
                <div class="form-group">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-0">
            <div class="card-body">
                <h5 class="card-title">Company Information</h5>
                <form @submit.prevent="updateCompanyProfile($event)">
                    <div class="row mb-2">
                        <label for="name" class="col-3 col-form-label">Company Name</label>
                        <div class="col-9">
                            <input type="text" name="name" id="name" v-model="company.name" class="form-control" autocomplete="off" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="title" class="col-3 col-form-label">Company Title</label>
                        <div class="col-9">
                            <input type="text" name="title" id="title" v-model="company.title" class="form-control" autocomplete="off" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="phone" class="col-3 col-form-label">Mobile</label>
                        <div class="col-9">
                            <input type="text" name="phone" id="phone" v-model="company.phone" class="form-control" autocomplete="off" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="email" class="col-3 col-form-label">Email</label>
                        <div class="col-9">
                            <input type="email" name="email" id="email" v-model="company.email" class="form-control" autocomplete="off" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="address" class="col-3 col-form-label">Address</label>
                        <div class="col-9">
                            <textarea name="address" id="address" v-model="company.address" class="form-control" autocomplete="off"></textarea>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">Submit Form</button>
                        </div>
                    </div>

                </form><!-- End General Form Elements -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    new Vue({
        el: '#companyProfile',
        data: {
            company: @json(company())
        },

        methods: {
            updateCompanyProfile(event) {
                let formdata = new FormData(event.target);
                axios.post('/update-companyProfile', formdata)
                    .then(res => {
                        if (res.data.status) {
                            toastr.success(res.data.message);
                        }
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            let errMsg = error.response.data.errors;
                            $.each(errMsg, (index, item) => {
                                $.each(item, (ind, val) => {
                                    toastr.error(val);
                                })
                            })

                        }

                    })
            }
        },
    })
</script>
@endpush