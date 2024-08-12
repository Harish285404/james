<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Invoice</title>
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
                    <td>    
                        <table class="tbl-padded">
                            <caption style="text-transform: uppercase; text-align: left; font-size: 30pt;">
                                <strong>
                                    Invoice
                                </strong>
                            </caption> 
                            <tbody>
                                <tr>
                                    <td style="padding:5px;">
                                        <strong >Invoice No.</strong>
                                    </td>
                                    <td style="padding:5px;">
                                        <div>
                                           {{ $details['invoice_id'] }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:5px;">
                                        <strong >Date Issued:</strong>                            
                                    </td>
                                    <td style="padding:5px;">
              {{ $details['invoice_date'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:5px;">
                                        <strong >Currency:</strong>                                
                                    </td>
                                    <td style="padding:5px;">
                                     AUD
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                                <strong style="text-transform: uppercase;">From</strong>
                            </div>
                            <div>
                               Inventory Management<br>
                                11/2187 Castlereagh Rd Penrith<br>
                                Australia - NSW 2750,<br>
                                (02) 4721 4918
                            </div>
                        </td>
                        <td width="15%">
                            
                        </td>
                        <td>
                        <div class="main" style="padding-bottom: 57px;">
                            <div class="bill" style="">
                                <strong style="text-transform: uppercase;">Bill To</strong>
                            </div>
                            <div>
                              {{ $details['bussiness_name'] }}
                            </div>
                                 <div>
                              {{ $details['address'] }}
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
                        <th align="center" style="border-top: 1px solid #eee; padding: 5px;padding-left:60px;">
                            Qty
                        </th>
                        <th align="center" style="border-top: 1px solid #eee; padding: 5px;padding-left:40px;">
                            Unit Price
                        </th>
                        <th align="right" style="border-top: 1px solid #eee; padding: 5px;padding-left:80px;">
                            Amount
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
                        <td align="center" style="border-top: 1px solid #eee; padding: 5px;">
                     {{ $details['product_quantity'][$i] }}
                        </td>
                        <td align="center" style="border-top: 1px solid #eee; padding: 5px;">
                      ${{ $details['product_price'][$i] }}
                        </td>
                        <td align="right" style="border-top: 1px solid #eee; padding: 5px;">
                        ${{ $details['product_total'][$i] }}
                        </td>
                    </tr>
                     <?php $k++; } ?>
                    <tr>
                        @if(isset($details['misc_item']) && $details['misc_item'] != null)
    <td style="border-top: 1px solid #eee; padding: 5px;">
                       {{ $details['misc_item']}}(miscellaneous item)
                        </td>
                         <td align="center" style="border-top: 1px solid #eee; padding: 5px;">

                        </td>

  @if(isset($details['misc_value']) && $details['misc_value'] != null)
    <td align="center" style="border-top: 1px solid #eee; padding: 5px;">
                     
                        </td>
                        <td align="right" style="border-top: 1px solid #eee; padding: 5px;">
                         {{ $details['misc_value']}}
                        </td>
@endif
@endif
                    </tr>
                 <!--    <tr>
                        <td style="border-top: 1px solid #eee; padding: 5px;">
                            Lorem ipsum dolor sit amet
                        </td>
                        <td align="center" style="border-top: 1px solid #eee; padding: 5px;">
                            10
                        </td>
                        <td align="center" style="border-top: 1px solid #eee; padding: 5px;">
                            50
                        </td>
                        <td align="right" style="border-top: 1px solid #eee; padding: 5px;">
                            USD 500.00
                        </td>
                    </tr> -->
                </tbody>
            </table>
        </div>

        <div style="border-top: 1px solid #000;">
            <table style="table-layout: fixed; width: 100%; border-collapse: collapse;">
                   <tbody>
                    <tr>
                    <td align="left" style="padding: 5px;">
                           ABN:68142005561
                        </td>
                        <td align="left" style="padding: 5px;font-size: 11px;">
                           Account: GREENERY IMPORTS   
                        </td>
                        <td align="right" style="padding: 5px;">
                            Subtotal
                        </td>
                        <td align="right" width="20%" style="padding: 5px;">
                       ${{ $details['grand_total'] }}  
                        </td>
                    </tr>
                    <tr>
                     <td align="left" style="padding: 5px;">
                        
                        </td>
                        <td align="left" style="padding: 5px;font-size: 13px;">
                               BSB: 062445
                        </td>
                       <td align="right" style="padding: 5px;">
                            Freight   
                        </td>
                        <td align="right" width="20%" style="padding: 5px;">

                            <?php

                            if($details['freight']){
                             echo '$'.$details['freight'];   
                            }
                             else{
                                echo"";
                             }

                              ?>
                        </td>
                    </tr>
                    <tr>
                     <td align="left" style="padding: 5px;">
                         
                        </td>
                        <td align="left" style="padding: 5px;font-size: 13px;">
                            Account No : 11090168 
                        </td>
                         <td align="right" style="padding: 5px;">
                            Discount({{$details['discount_per']}}%)  
                        </td>
                        <td align="right" width="20%" style="padding: 5px;">
                              ${{ $details['discount_amount'] }}  
                        </td>
                    </tr>
                       <tr>
                     <td align="left" style="padding: 5px;">
                         
                        </td>
                        <td align="left" style="padding: 5px;font-size: 13px;">
                     
                        </td>
                         <td align="right" style="padding: 5px;">
                             Gst    
                        </td>
                        <td align="right" width="20%" style="padding: 5px;">
                           ${{ $details['gst'] }}          
                        </td>
                    </tr>
                    <tr>
                     <td align="left" style="padding: 5px;">
                           
                        </td>
                        <td align="left" style="padding: 5px;">
                     
                        </td>
                        <td align="right" style="border-top: 2px solid #eee; padding: 8px;">
                            <span style="font-size: 16pt;">
                                Total        
                            </span>
                        </td>
                        <td align="right" width="20%" style="border-top: 2px solid #eee; padding: 8px;">
                            <strong style="font-size: 13pt;">
                                 @if(isset($details['grand_totall']))
                            ${{ $details['grand_totall'] }}  
                            @endif
                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <div style="padding-top:1cm; padding-bottom: 1cm;">
                <div>
                    <strong>Note:</strong>
                </div>
                <p style="font-size: 10pt; line-height: 14pt;">
                        {{ $details['notes'] }} 
                </p>
            </div>
        </div>
</body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
