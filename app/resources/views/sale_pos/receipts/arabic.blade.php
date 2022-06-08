<style>
.bord {
    border: 3px solid black;
    height: 100%;
}
.head{
   border-bottom: 2px solid #d2d7da;
  width: 90%;
  margin-left: 5%;
}
.invoice-coll{
    text-align: right;
} 
.pull-rightt{
    text-align: left;
    width: 90%;
    margin-left: 5%;
}
.test_center{
    text-align: center;
}
.text_ret{
    text-align: right;
}
.table{
    border: 1px solid #d2d7da;
    border-left: none;
}
.footer_left{
  text-align: center;
}
.page_body{
  height: 100vh;
}
.tes{
	background-color: gray;
}
table, td, tr, th {
  border: 1px solid #d2d7da;
}

table {
  width: 100%;
  border-collapse: collapse;
}
.footer {
   position: fixed;
   left: 0;
   bottom: 0;
   width: 100%;
   /* background-color: red; */
   color: white;
   text-align: center;
   height: 50px;
}
#leftmr{
  margin-left: 10px;
}
</style>
<div class="bord border page_body">
<div class="modal-header head">
    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      <address>
        <strong>@lang('business.business'):</strong>
        {{ $receipt_details->display_name }}
		@if(!empty($receipt_details->address))
          <br><strong>Address:</strong> {{$receipt_details->address}}
        @endif
		
		 @if(!empty($receipt_details->tax_info1))
          <br><strong>{{$receipt_details->tax_label1}}:</strong> {{$receipt_details->tax_info1}}
        @endif
		 @if(!empty($receipt_details->tax_info2))
          <br><strong>{{$receipt_details->tax_label2}}:</strong> {{$receipt_details->tax_info2}}
        @endif
         @if(!empty($receipt_details->contact))
          <br> {!! $receipt_details->contact !!}
        @endif
        @if(!empty($purchase->location->email))
          <br><strong>@lang('business.email'):</strong> {{$receipt_details->email}}
        @endif
      </address>
    </div>

    <div class="col-sm-4 invoice-col">
       @php 
        $img = URL::to('') . '/uploads/business_logos/';
        @endphp


{!! QrCode::size(150)->generate($qrcode) !!}
         
        <!-- <img src="{{ $img }}" alt="Girl in a jacket" width="100" height="100"> -->
    </div>

    <div class="col-sm-4 invoice-col invoice-coll">
      <address>
        @if(!empty($receipt_details->display_name))
        {{$receipt_details->display_name}} <strong>:عمل</strong> 
        @endif
        @if(!empty($receipt_details->address))
          <br>{{$receipt_details->address}}<strong> :عنوان</strong> 
        @endif
        @if(!empty($receipt_details->tax_info1))
          <br>{{$receipt_details->tax_info1}} <strong>:{{$receipt_details->tax_label1}}</strong> 
        @endif

        @if(!empty($receipt_details->tax_info2))
          <br>{{$receipt_details->tax_info2}} <strong>:{{$receipt_details->tax_label2}}</strong> 
        @endif

        @if(!empty($receipt_details->contact))
          <br><strong>معلم معروف:</strong> {!! $receipt_details->contact !!}
        @endif
        @if(!empty($purchase->location->email))
          <br><strong>البريد الإلكتروني:</strong> {{$purchase->location->email}}
        @endif
      </address>
    </div>
  </div>
</div>
<div class="modal-body ">
  <div class="row">
    <div class="col-sm-12">
      <p class="pull-rightt"><b>تاريخ:</b> {{ @format_date($receipt_details->invoice_date) }}</p>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 col-xs-12">
<h3 class="test_center"> فاتورة </h3>
<h4 class="text_ret">MS/-------------------------------------------------------------------------------------------/ المطلوب من المكرم  <h4>
</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<br/>
		@php
			$p_width = 40;
		@endphp
		@if(!empty($receipt_details->item_discount_label))
			@php
				$p_width -= 15;
			@endphp
		@endif

    <!-- table-slim  table table-responsive -->
		<table class="  ">
			<thead>
				<tr>
					<th width="{{$p_width}}%" class="text-center" rowspan="2">DESCRIPTION &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    وصف </th>
					<th class="text-center " width="7%" rowspan="2"> العدد <br> QTY</th>
					<th class="text-center" width="10%" colspan="2"> السعر الافرادي <br> UNITE PRICE</th>
					<!-- @if(!empty($receipt_details->item_discount_label))
						<th class="text-right" width="15%">{{$receipt_details->item_discount_label}}</th>
					@endif -->
					<th class="text-center" width="10%"  colspan="2">السعر الاجمالي <br> TOTAL PRICE</th>
         
				</tr>
        <tr>  
          <th class="text-center" width="7%" > S.R  ریال</th>
          <th class="text-center" width="7%" > H</th> 

           <th class="text-center" width="7%" >S.R  ریال</th>
          <th class="text-center" width="7%" > H </th> 

         </tr>
			</thead>
			<tbody>
				@forelse($receipt_details->lines as $line)
					<tr height="35px">
						<td class="text-center" >

            
                            {{$line['name']}} {{$line['product_variation']}} {{$line['variation']}} 
                            @if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif @if(!empty($line['cat_code'])), {{$line['cat_code']}}@endif
                            @if(!empty($line['product_custom_fields'])), {{$line['product_custom_fields']}} @endif
                            @if(!empty($line['sell_line_note']))
                            <br>
                            <small>
                            	{{$line['sell_line_note']}}
                            </small>
                            @endif 
                            @if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}:  {{$line['lot_number']}} @endif 
                            @if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}:  {{$line['product_expiry']}} @endif

                            @if(!empty($line['warranty_name'])) <br><small>{{$line['warranty_name']}} </small>@endif @if(!empty($line['warranty_exp_date'])) <small>- {{@format_date($line['warranty_exp_date'])}} </small>@endif
                            @if(!empty($line['warranty_description'])) <small> {{$line['warranty_description'] ?? ''}}</small>@endif
                        </td>
						<td class="text-center">{{$line['quantity']}} {{$line['units']}} </td>
						
						<!-- <td class="tes"> {{$line['line_total']}} </td> -->
						<td class="text-center">{{$line['line_total']}}<td>
              <td class="text-center">{{$line['line_total']}}<td>
               
                  

						@if(!empty($line['modifiers']))
						@foreach($line['modifiers'] as $modifier)
							<tr>
								<td>
		                            {{$modifier['name']}} {{$modifier['variation']}} 
		                            @if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif @if(!empty($modifier['cat_code'])), {{$modifier['cat_code']}}@endif
		                            @if(!empty($modifier['sell_line_note']))({{$modifier['sell_line_note']}}) @endif 
		                        </td>
								<td class="text-center">{{$modifier['quantity']}} {{$modifier['units']}} </td>
								<td class="text-center">{{$modifier['unit_price_inc_tax']}}</td>
								@if(!empty($receipt_details->item_discount_label))
									<td class="text-center">0.00</td>
								@endif
								<td class="text-center">{{$modifier['line_total']}}</td>
							</tr>
						@endforeach
					@endif
					</tr>
				
				@empty
				@endforelse
        	<tr height="35px">
        <td colspan="4">  &nbsp;&nbsp;&nbsp;-----------------------------------------------------------------------------------------------------/ المجموع</td>
        <td> </td>
        <td> </td>
        	</tr>

          	<tr height="35px">
        <td colspan="4">  &nbsp;&nbsp;&nbsp;--------------------------------------------------------------------------------/ ضريبة القيمة المضافة  % 15 </td>
        <td> </td>
        <td> </td>
        	</tr>

          	<tr height="35px">
        <td colspan="4"> &nbsp;&nbsp;&nbsp;---------------------------------------------------------------------------------------/ الاجمالي شامل الضريبة </td>
        <td> </td>
        <td> </td>
        	</tr>
			</tbody>
		</table>
	</div>
</div>
  <br>
 @if($receipt_details->show_barcode)
	<div class="row footer">
		<div class="col-xs-6">
	-------------------------------------/	المسئول
		</div>
    	<div class="col-xs-6">
	--------------------------------------/	المستلم 
		</div>
	</div>
@endif
  <div class="row print_section">
    <div class="col-xs-12">

    </div>
  </div>
</div>
</div>