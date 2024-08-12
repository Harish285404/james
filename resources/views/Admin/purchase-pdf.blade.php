<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Purchase</title>
    <style>
      
    </style>
</head>
  <body style="font-family:Arial Unicode MS, Helvetica , Sans-Serif;">
       <table style="table-layout: fixed; width: 100%;">
            <tbody>
                <tr>
                    <td class="">
                        <div>
                            <img src="https://cdn.greeneryimports.com.au/newgreen/wp-content/uploads/2015/07/gi-logo.jpg" alt="Company Logo" style="max-width: 100%;">
                        </div>
                    </td>
                    <td width="15%">
                        
                    </td>
            
                </tr>
            </tbody>
        </table>
        
        <div style="padding-top: 1cm; padding-bottom: 1cm;">
            <table style="table-layout: fixed; width: 100%;">
                <tbody>
                    <tr>
                        <td>
                            <div style="padding-bottom: 10px;">
                                <strong style="text-transform: uppercase;">Purchase From</strong>
                            </div>
                            <div>
                                     {{ $details['purchase_from'] }}
                            </div>
                        </td>
                        <td width="15%">
                            
                        </td>
                        <td>
                        <div class="main" style="padding-bottom: 17px;">
                            <div class="bill" style="">
                                <strong style="text-transform: uppercase;">Invoice Id</strong>
                            </div>
                      
                                 <div>
                              {{ $details['invoice_id'] }}
                            </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
       
        <div>
            <table style="table-layout: fixed; width: 100%;">
                <thead>
       
                    <tr>
                        <th  width="40%" align="left" style="border-top: 1px solid #eee; padding: 5px;">
                            Item
                        </th>
                          <th align="right" style="border-top: 1px solid #eee; padding: 5px;padding-left:80px;">
                         Sku
                        </th>
                        <th align="center" style="border-top: 1px solid #eee; padding: 5px;padding-left:60px;">
                            Qty
                        </th>
                        <th align="center" style="border-top: 1px solid #eee; padding: 5px;padding-left:40px;">
                            Cost Price
                        </th>
                      
                    </tr>

                </thead>
                <tbody>
                                 <?php 
          $k = 1;
          for($i = 0 ;$i<count($details['product_name']);$i++) { ?>
                    <tr>
                        <td style="border-top: 1px solid #eee; padding: 5px;">
                            {{ $details['product_name'][$i] }}
                        </td>
                           <td align="right" style="border-top: 1px solid #eee; padding: 5px;">
                        {{ $details['product_sku'][$i] }}
                        </td>
                        <td align="center" style="border-top: 1px solid #eee; padding: 5px;">
                     {{$details['product_quantity'][$i] }}
                        </td>
                        <td align="center" style="border-top: 1px solid #eee; padding: 5px;">
                      ${{ $details['product_price'][$i] }}
                        </td>
                     
                    </tr>
                     <?php $k++; } ?>
                </tbody>
            </table>
        </div>
</body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>