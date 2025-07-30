Vue.component('invoice-preview', {
  props: ['visible', 'cart', 'customer', 'sale'],
  template: `
  <div v-if="visible" class="invoice-overlay">
      <div class="row ms-0 me-0 py-1 px-1" style="border-radius: 8px;">
        <div class="col-2 ps-0">
            <img src="/noImage.jpg" class="w-100 h-100" style="box-shadow:1px 1px 1px 1px #d9d9d9;border-radius:5px;">
        </div>
        <div class="col-10 pe-0">
            <h4 class="m-0">{{company.title}}</h4>
            <address class="m-0"><strong>Mobile: </strong>{{ company.phone }}</address>
            <address class="m-0" v-html="company.address"></address>
        </div>
      </div>
      <div class="invoice-box">
        <h3>{{company.title}}</h3>
        <p><strong>Customer:</strong> {{ customer.name || 'Walk-in' }}</p>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Product</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in cart" :key="index">
              <td>{{ item.name }}</td>
              <td>{{ item.quantity }}</td>
              <td>{{ item.sale_rate }}</td>
              <td>{{ item.total }}</td>
            </tr>
          </tbody>
        </table>

        <p><strong>Subtotal:</strong> {{ sale.subtotal }}</p>
        <p><strong>Discount:</strong> {{ sale.discount }}</p>
        <p><strong>VAT:</strong> {{ sale.vat }}</p>
        <p><strong>Total:</strong> {{ sale.total }}</p>
        <p><strong>Cash Paid:</strong> {{ sale.cashPaid }}</p>
        <p><strong>Return:</strong> {{ sale.returnAmount }}</p>
      </div>
    </div>
  `,
  watch: {
    visible(newVal) {
      if (newVal) {
        this.$nextTick(() => {
          this.autoPrint();
        });
      }
    }
  },
  data() {
    return {
      company: {},
    }
  },
  created() {
    this.getCompany();
  },
  methods: {
    getCompany() {
      axios.get('/get-companyProfile')
        .then(res => {
          this.company = res.data;
        })
    },
    autoPrint() {
      const selfThis = this;
      const oldTitle = window.document.title;
      window.document.title = "Sale Invoice"
      const printWindow = document.createElement('iframe');
      document.body.appendChild(printWindow);
      printWindow.srcdoc = `
                  <html>
                    <head>
                          <link href="/backend/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
                          <link href="/backend/css/custom.css" rel="stylesheet">
                          <style>
                              .table>:not(caption)>*>* {
                                  font-size: 11px !important;
                              }
                              address p{
                                  margin: 0 !important;
                              }                                        
                          </style>
                    </head>
                    <body>
                      <div class="container-fluid">
                          <div class="row">
                              <div class="col-12">
                                  ${document.querySelector('.invoice-overlay').innerHTML}
                              </div>
                          </div>
                      </div>
                    </body>
                  </html>
                `;
      printWindow.onload = async function () {
        printWindow.contentWindow.focus();
        await new Promise(resolve => setTimeout(resolve, 500));
        printWindow.contentWindow.print();
        document.body.removeChild(printWindow);
        window.document.title = oldTitle;

        selfThis.$emit('close');
      };
    }
  }
});
