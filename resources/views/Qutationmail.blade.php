<!DOCTYPE html>
<html>
<head>
    <title>Inventory Management</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto:100,300,400,900,700,500,300,100);
*{
  margin: 0;
  box-sizing: border-box;
  -webkit-print-color-adjust: exact;
}
body{
  background: #E0E0E0;
  font-family: 'Roboto', sans-serif;
}
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
.clearfix::after {
    content: "";
    clear: both;
    display: table;
}
.curreny-data {
    text-align: right;
    margin-bottom: 10px;
}
.curreny-data p {
    color: #666;
    padding: 5px 8px;
    border: 0;
    font-size: 0.75em;
    border-bottom: 1px solid #eeeeee;
    display: inline;
    font-weight: 500;
}
.curreny-data p span {
    font-weight: bold;
    padding: 0 5px;
}



.col-left {
    float: left;
}
.col-right {
    float: right;
}
h1{
  font-size: 1.5em;
  color: #444;
}
h2{font-size: .9em;}
h3{
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
p{
  font-size: .75em;
  color: #666;
  line-height: 1.2em;
}
a {
    text-decoration: none;
    color: #00a63f;
}

#invoiceholder{
  width:100%;
  height: 100%;
  padding: 50px 0;
}
#invoice{
  position: relative;
  margin: 0 auto;
  width: 700px;
  background: #FFF;
}

[id*='invoice-']{ /* Targets all id with 'col-' */
/*  border-bottom: 1px solid #EEE;*/
  padding: 20px;
}

#invoice-top{border-bottom: 2px solid #00a63f;}
#invoice-mid{min-height: 110px;}
#invoice-bot{ min-height: 240px;}

.logo{
    display: inline-block;
    vertical-align: middle;
    width: 110px;
    overflow: hidden;
}
.info{
    display: inline-block;
    vertical-align: middle;
    margin-left: 20px;
}
.logo img,
.clientlogo img {
    width: 100%;
}
.clientlogo{
    display: inline-block;
    vertical-align: middle;
    width: 50px;
}
.clientinfo {
    display: inline-block;
    vertical-align: middle;
    margin-left: 20px
}
.title{
  float: right;
}
.title p{text-align: right;}
#message{margin-bottom: 30px; display: block;}
h2 {
    margin-bottom: 5px;
    color: #444;
}
.col-right td {
    color: #666;
    padding: 5px 8px;
    border: 0;
    font-size: 0.75em;
    border-bottom: 1px solid #eeeeee;
}
.col-right td label {
    margin-left: 5px;
    font-weight: 600;
    color: #444;
}
.cta-group a {
    display: inline-block;
    padding: 7px;
    border-radius: 4px;
    background: rgb(196, 57, 10);
    margin-right: 10px;
    min-width: 100px;
    text-align: center;
    color: #fff;
    font-size: 0.75em;
}
.cta-group .btn-primary {
    background: #00a63f;
}
.cta-group.mobile-btn-group {
    display: none;
}
table{
  width: 100%!important;
  border-collapse: collapse;
}
td{
    padding: 10px;
    border-bottom: 1px solid #cccaca;
    font-size: 0.70em;
    text-align: left;
}

.tabletitle th {
  border-bottom: 2px solid #ddd;
  text-align: right;
}
.tabletitle th:nth-child(2) {
    text-align: left;
}
th {
    font-size: 0.7em;
    text-align: left;
    padding: 5px 10px;
}
.item{width: 50%;}
.list-item td {
    text-align: right;
}
.list-item td:nth-child(2) {
    text-align: left;
}
.total-row th,
.total-row td {
    text-align: right;
    font-weight: 700;
    font-size: .75em;
    border: 0 none;
}
.table-main {
    
}
footer {
    border-top: 1px solid #eeeeee;;
    padding: 15px 20px;
}
.effect2
{
  position: relative;
}
.effect2:before, .effect2:after
{
  z-index: -1;
  position: absolute;
  content: "";
  bottom: 15px;
  left: 10px;
  width: 50%;
  top: 80%;
  max-width:300px;
  background: #777;
  -webkit-box-shadow: 0 15px 10px #777;
  -moz-box-shadow: 0 15px 10px #777;
  box-shadow: 0 15px 10px #777;
  -webkit-transform: rotate(-3deg);
  -moz-transform: rotate(-3deg);
  -o-transform: rotate(-3deg);
  -ms-transform: rotate(-3deg);
  transform: rotate(-3deg);
}
.effect2:after
{
  -webkit-transform: rotate(3deg);
  -moz-transform: rotate(3deg);
  -o-transform: rotate(3deg);
  -ms-transform: rotate(3deg);
  transform: rotate(3deg);
  right: 10px;
  left: auto;
}
@media screen and (max-width: 767px) {
    h1 {
        font-size: .9em;
    }
    #invoice {
        width: 100%;
    }
    #message {
        margin-bottom: 20px;
    }
    [id*='invoice-'] {
        padding: 20px 10px;
    }
    .logo {
        width: 140px;
    }
    .title {
        float: none;
        display: inline-block;
        vertical-align: middle;
        margin-left: 40px;
    }
    .title p {
        text-align: left;
    }
    .col-left,
    .col-right {
        width: 100%;
    }
    .table {
        margin-top: 20px;
    }
    #table {
        white-space: nowrap;
        overflow: auto;
    }
    td {
        white-space: normal;
    }
    .cta-group {
        text-align: center;
    }
    .cta-group.mobile-btn-group {
        display: block;
        margin-bottom: 20px;
    }
     /*==================== Table ====================*/
    .table-main {
        border: 0 none;
    }  
      .table-main thead {
        border: none;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
      }
      .table-main tr {
        border-bottom: 2px solid #eee;
        display: block;
        margin-bottom: 20px;
      }
      .table-main td {
        font-weight: 700;
        display: block;
        padding-left: 40%;
        max-width: none;
        position: relative;
        border: 1px solid #cccaca;
        text-align: left;
      }
      .table-main td:before {
        /*
        * aria-label has no advantage, it won't be read inside a table
        content: attr(aria-label);
        */
        content: attr(data-label);
        position: absolute;
        left: 10px;
        font-weight: normal;
        text-transform: uppercase;
      }
    .total-row th {
        display: none;
    }
    .total-row td {
        text-align: left;
    }
    footer {text-align: center;}
}

    </style>
</head>
<body>

    <body>
  <div id="invoiceholder">
  <div id="invoice" class="effect2">
    
    <div id="invoice-top">
      <div class="logo"><svg width="58" height="61" viewBox="0 0 58 61" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.0081 28.3259C10.0081 17.6072 18.6973 8.91797 29.416 8.91797C40.1347 8.91797 48.8239 17.6072 48.8239 28.3259L48.8239 47.7338L29.416 47.7338C18.6973 47.7338 10.0081 39.0446 10.0081 28.3259Z" fill="white"></path>
<path d="M42.7028 14.2328C50.3801 21.9101 50.3801 34.3575 42.7028 42.0347C35.0255 49.712 22.5782 49.712 14.9009 42.0347L0.999964 28.1338L14.9009 14.2328C22.5782 6.55555 35.0255 6.55556 42.7028 14.2328Z" fill="white"></path>
<path d="M43.0861 41.9942C35.3552 49.7251 22.821 49.7251 15.0902 41.9942C7.35934 34.2634 7.35934 21.7292 15.0902 13.9983L29.0881 0.000383774L43.0861 13.9983C50.8169 21.7292 50.8169 34.2634 43.0861 41.9942Z" fill="white"></path>
<g filter="url(#filter0_d_135_13841)">
<rect x="49.262" y="24.2725" width="28.968" height="28.968" rx="14.484" transform="rotate(124.409 49.262 24.2725)" fill="#0F5132"></rect>
</g>
<path d="M35.1497 33.3281C35.1497 33.3281 32.3596 31.3911 29.7815 31.3911C27.2035 31.3911 24.4134 33.3281 24.4134 33.3281C24.4134 33.3281 25.9076 30.538 25.9076 27.96C25.9076 25.3819 24.4134 22.5918 24.4134 22.5918C24.4134 22.5918 27.2035 24.086 29.7815 24.086C32.3596 24.086 35.1497 22.5918 35.1497 22.5918C35.1497 22.5918 32.9914 25.3819 32.9914 27.96C32.9914 30.538 35.1497 33.3281 35.1497 33.3281Z" fill="white"></path>
<defs>
<filter id="filter0_d_135_13841" x="0.64209" y="3.55176" width="56.9707" height="56.9707" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
<feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
<feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
<feOffset dy="4"></feOffset>
<feGaussianBlur stdDeviation="7"></feGaussianBlur>
<feComposite in2="hardAlpha" operator="out"></feComposite>
<feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.2 0"></feColorMatrix>
<feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_135_13841"></feBlend>
<feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_135_13841" result="shape"></feBlend>
</filter>
</defs>
</svg></div>
      <div class="title">
        <h1>Quote Number #<span class="invoiceVal invoice_num">{{ $details['quote_id'] }}</span></h1>
        <!-- <h1>Quote</h1> -->
        <p>Quote Date: <span id="invoice_date">  <?php
$dateString = $details['date'];
$newDateString = date("d-m-Y", strtotime($dateString));
echo $newDateString;

         ?></span><br>
           <!-- GL Date: <span id="gl_date">23 Feb 2018</span> -->
        </p>
      </div><!--End Title-->
    </div><!--End InvoiceTop-->


    
    <div id="invoice-mid">   
      <div id="message">
        <h2>Hello {{ $details['name'] }}</h2>

      </div>
      <div class="curreny-data">
    <p>Currency<span>AUD</span></p>
</div>
      <!--  <div class="cta-group mobile-btn-group">
            <a href="https://inventory-management.chainpulse.tech/user-quotataion" class="btn-primary">Approve</a>
            <a href="https://inventory-management.chainpulse.tech/user-R-quotataion" class="btn-default">Reject</a>
        </div>  -->
        <div class="clearfix">
            <div class="col-left">
                <!-- <div class="clientlogo"><img src="https://cdn3.iconfinder.com/data/icons/daily-sales/512/Sale-card-address-512.png" alt="Sup" /></div> -->
                <div class="clientinfo">
                    <h2 id="supplier">Inventory Management</h2>
                    <p><span id="address">11/2187 Castlereagh Rd</span><br><span id="city">Penrith</span><br><span id="country">Australia</span> - <span id="zip">NSW 2750,</span><br><span id="tax_num">(02) 4721 4918</span><br></p>
                </div>
            </div>

             <div class="col-right">
                <!-- <div class="clientlogo"><img src="https://cdn3.iconfinder.com/data/icons/daily-sales/512/Sale-card-address-512.png" alt="Sup" /></div> -->
                <div class="clientinfo">
                    <h2 id="supplier">{{ $details['bussiness_name'] }}</h2>
                    <p>{{ $details['address'] }}</p>
                </div>
            </div>
           
        </div>       
    </div><!--End Invoice Mid-->
    
    <div id="invoice-bot">
      
      <div id="table">
        <table class="table-main"  style="margin-top: 30px; float:left;">
          <thead>    
              <tr class="tabletitle">
                 <th>Sr.No.</th>
                <th>Item</th>
                <!-- <th>Description</th> -->
                <th>Quantity</th>
                <th>Unit Price</th>
                <!-- <th>Taxable Amount</th> -->
                <!-- <th>Tax Code</th> -->
                <!-- <th>%</th> -->
                <!-- <th>Tax Amount</th> -->
                <!-- <th>AWT</th> -->
                <th>Total</th>
              </tr>
          </thead>
          <?php 
          $k = 1;
          for($i = 0 ;$i<count($details['product_name']);$i++) { ?>
          <tr class="list-item">
            <td data-label="Type" class="tableitem">{{ $k; }}</td>
            <td data-label="Description" class="tableitem">{{ $details['product_name'][$i] }}</td>
            <td data-label="Quantity" class="tableitem">{{ $details['product_quantity'][$i] }}</td>
            <!-- <td data-label="Unit Price" class="tableitem">{{ $details['product_price'][$i] }}</td> -->
            <td data-label="Taxable Amount" class="tableitem">${{ $details['product_price'][$i] }}</td>
            <!-- <td data-label="Tax Code" class="tableitem">DP20</td> -->
            <!-- <td data-label="%" class="tableitem">20</td> -->
            <!-- <td data-label="Tax Amount" class="tableitem">9.32</td> -->
            <!-- <td data-label="AWT" class="tableitem">None</td> -->
           <td data-label="Taxable Amount" class="tableitem">${{ $details['product_total'][$i] }}</td>
          </tr>
          <?php $k++; } ?>
            <tr class="list-item total-row">
                <th colspan="4" class="tableitem">Grand Total</th>
                <td data-label="Grand Total" class="tableitem">$ {{ $details['total'] }}</td>
            </tr>
        </table>
      </div><!--End Table-->                                     
     
      
    </div><!--End InvoiceBot-->
    <footer>
     <!--  <div id="legalcopy" class="clearfix">
        <p class="col-right">Our mailing address is:
            <span class="email"><a href="mailto:supplier.portal@almonature.com">supplier.portal@almonature.com</a></span>
        </p>
      </div> -->
    </footer>
  </div><!--End Invoice-->
</div><!-- End Invoice Holder-->
  
  


</body>
<script>
$(document).on('click', 'a.status', function(e) {
    var statusId = $(this).data('status-id');
    if (statusId === 0) {
        // Allow the click when statusId is 0
        alert("hi");
    } else {
        // Prevent the click when statusId is not 0
        e.preventDefault();
    }
});
</script>
</html>
