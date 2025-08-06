@extends('master')

@section('title', 'Purchase Invoice')
@section('breadcrumb', 'Purchase Invoice')
@push('style')
<style>
    tr td,
    tr th {
        vertical-align: middle !important;
    }
</style>
@endpush
@section('content')
<div id="purchaseInvoice">
    <div class="card py-5">
        <div class="col-md-10 offset-md-1">
            <div class="d-flex justify-content-between align-items-center">
                <a class="btn btn-danger btn-sm" onclick="window.close();"><i class="bi bi-backspace"></i> Back</a>
                <a class="btn btn-success btn-sm" href="" @click.prevent="printInvoice = true" title="Print"><i class="bi bi-printer"></i> Print</a>
            </div>
        </div>
        <div class="col-md-10 offset-md-1">
            <invoice-preview
                :visible="printInvoice"
                :showable="showInvoice"
                :cart="carts"
                :supplier="selectedSupplier"
                :purchase="purchase"
                :username="username"
                @close="printInvoice = false"></invoice-preview>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{asset('component')}}/PurchaseInvoicePreview.js"></script>
<script>
    new Vue({
        el: '#purchaseInvoice',
        data: {
            purchaseId: "{{$id}}",
            purchase: {},
            selectedSupplier: {},
            carts: [],
            username: "",
            showInvoice: false,
            printInvoice: 1,
            isLoading: null
        },

        created() {
            this.showReport();
        },

        methods: {
            showReport() {
                axios.post('/get-purchase', {
                        purchaseId: this.purchaseId
                    })
                    .then(res => {
                        this.purchase = res.data[0];
                        this.username = this.purchase?.ad_user?.username;
                        this.carts = this.purchase.details;
                        this.selectedSupplier = {
                            id: this.purchase.customer_id ?? '',
                            code: this.purchase.supplier_code,
                            name: this.purchase.customer_name,
                            phone: this.purchase.customer_phone,
                            address: this.purchase.customer_address,
                            type: this.purchase.customer_type
                        }
                        this.showInvoice = true;
                    })
            }
        }
    })
</script>
@endpush