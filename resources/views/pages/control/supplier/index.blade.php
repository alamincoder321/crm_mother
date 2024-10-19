@extends('master')

@section('title', 'Supplier List')
@section('breadcrumb', 'Supplier List')
@push('style')
<style>
    .table>thead>tr>th {
        text-align: center !important;
        background-color: gray;
        color: #fff;
    }
</style>
@endpush
@section('content')
<div id="supplierList">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card m-0">
                <div class="card-body py-3 px-2">
                    <form @submit.prevent="showList" class="form-inline">
                        <div class="form-group">
                            <label for="searchType">SearchType</label>
                            <select id="searchType" class="form-select" v-model="searchType" @change="onChangeSearchType">
                                <option value="">All</option>
                                <option value="area">By Area</option>
                            </select>
                        </div>
                        <div class="form-group" :class="searchType == 'area' ? '' : 'd-none'" v-if="searchType == 'area'">
                            <label for="searchType">Area</label>
                            <v-select :options="areas" v-model="selectedArea" label="name"></v-select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">Show</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2" :class="isLoading == false ? '' : 'd-none'" v-if="isLoading == false">
        <div class="col-12 text-center">
            Loading...
        </div>
    </div>
    <div class="row mt-2" :class="isLoading ? '' : 'd-none'" v-if="isLoading">
        <div class="col-12 col-md-12">
            <div class="card m-0">
                <div class="card-body pt-1 pb-3 px-2">
                    <div class="text-end">
                        <a href="" @click.prevent="print"><i class="bi bi-printer"></i></a>
                    </div>
                    <div id="reportContent">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Owner</th>
                                    <th>Phone</th>
                                    <th>Area</th>
                                    <th>Address</th>
                                    <th>Prev_Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in suppliers">
                                    <td v-html="index + 1"></td>
                                    <td v-html="item.code"></td>
                                    <td v-html="item.name"></td>
                                    <td v-html="item.owner"></td>
                                    <td v-html="item.phone"></td>
                                    <td v-html="item.area?.name"></td>
                                    <td v-html="item.address"></td>
                                    <td v-html="item.previous_due"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script>
    new Vue({
        el: '#supplierList',
        data: {
            searchType: '',
            suppliers: [],
            areas: [],
            selectedArea: null,
            isLoading: null
        },

        created() {
            this.getArea();
        },

        methods: {
            getArea() {
                axios.post('/get-area')
                    .then(res => {
                        this.areas = res.data;
                    })
            },

            onChangeSearchType() {
                this.suppliers = [];
                this.selectedArea = null;
            },

            showList() {
                let filter = {
                    areaId: this.selectedArea ? this.selectedArea.id : ''
                }
                this.isLoading = false;
                axios.post('/get-supplier', filter)
                    .then(res => {
                        this.suppliers = res.data
                        this.isLoading = true;
                    })
            },

            async print() {
                let reportContent = `
					<div class="container-fluid">
						<div class="row">
							<div class="col-12 text-center">
								<h5>Supplier List</h5>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								${document.querySelector('#reportContent').innerHTML}
							</div>
						</div>
					</div>
				`;

                var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
                reportWindow.document.write(`
					@include("layouts.headerInfo")
				`);
                reportWindow.document.body.innerHTML += reportContent;
                reportWindow.document.head.innerHTML += `
					<link href="{{asset('backend')}}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
                    <link href="{{asset('backend')}}/css/custom.css" rel="stylesheet">
                    <style>
                        .table>:not(caption)>*>* {
                            font-size: 14px !important;
                        }
                    </style>
				`;

                reportWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                reportWindow.print();
                reportWindow.close();
            }
        },
    })
</script>
@endpush