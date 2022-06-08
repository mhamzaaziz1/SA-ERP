<style>
.bord {
    border: 3px solid black;
    height: 100%;
}
.head{
    /* border-bottom: 5px solid black; */
    /* border-bottom-left: 50px 20px; */
    /* padding-left: 50px;
    padding-right: 50px;
    border-bottom-width: 15px; */
  /* display: inline-block; */
  /* position: relative; */
   border-bottom: 2px solid #d2d7da;
  width: 90%;
  margin-left: 5%;
}
/* .head:after {
  position: absolute;
  content: '';
  border-bottom: 1px solid #d2d7da;
  width: 70%;
  transform: translateX(-50%);
  bottom: -15px;
  left: 50%;
}*/
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
  height: 40vh;
}
</style>
<div class="bord border">
<div class="modal-header head">
    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    @php
      $title = $purchase->type == 'purchase_order' ? __('lang_v1.purchase_order_details') : __('purchase.purchase_details');
    @endphp
    <!-- <h4 class="modal-title" id="modalTitle"> {{$title}} (<b>@lang('purchase.ref_no'):</b> #{{ $purchase->ref_no }})
    </h4> -->
   <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      <address>
        <strong>@lang('business.business'):</strong>
        {{ $purchase->location->name }}
        @if(!empty($purchase->location->landmark))
          <br><strong>Landmark:</strong> {{$purchase->location->landmark}}
        @endif
        <!-- @if(!empty($purchase->location->city) || !empty($purchase->location->state) || !empty($purchase->location->country))
          <br>{{implode(',', array_filter([$purchase->location->city, $purchase->location->state, $purchase->location->country]))}}
        @endif -->
        @if(!empty($purchase->business->tax_number_1))
          <br><strong>{{$purchase->business->tax_label_1}}:</strong> {{$purchase->business->tax_number_1}}
        @endif

        @if(!empty($purchase->business->tax_number_2))
          <br><strong>{{$purchase->business->tax_label_2}}:</strong> {{$purchase->business->tax_number_2}}
        @endif

        @if(!empty($purchase->location->mobile))
          <br><strong>@lang('contact.mobile'):</strong> {{$purchase->location->mobile}}
        @endif
        @if(!empty($purchase->location->email))
          <br><strong>@lang('business.email'):</strong> {{$purchase->location->email}}
        @endif
      </address>
    </div>

    <div class="col-sm-2 invoice-col">
        @php 
        $img = URL::to('') . '/uploads/business_logos/' . $purchase->business->logo;
        @endphp

        <!-- {!! QrCode::size(150)->generate($qrcode) !!} -->
        <img src="{{ $img }}" alt="Girl in a jacket" width="100" height="100">
    </div>
   
         

    <div class="col-sm-4 invoice-col invoice-coll">
        <address>
        @if(!empty($purchase->location->name))
        {{$purchase->location->name}} <strong>:عمل</strong> 
        @endif
        @if(!empty($purchase->location->landmark))
          <br><strong>متحرك:</strong> {{$purchase->location->landmark}}
        @endif
        <!-- @if(!empty($purchase->location->city) || !empty($purchase->location->state) || !empty($purchase->location->country))
          <br>{{implode(',', array_filter([$purchase->location->city, $purchase->location->state, $purchase->location->country]))}}
        @endif -->
        @if(!empty($purchase->business->tax_number_1))
          <br>{{$purchase->business->tax_number_1}} <strong>:{{$purchase->business->tax_label_1}}</strong> 
        @endif

        @if(!empty($purchase->business->tax_number_2))
          <br>{{$purchase->business->tax_number_2}} <strong>:{{$purchase->business->tax_label_2}}</strong> 
        @endif

        @if(!empty($purchase->location->mobile))
          <br><strong>معلم معروف:</strong> {{$purchase->location->mobile}}
        @endif
        @if(!empty($purchase->location->email))
          <br><strong>البريد الإلكتروني:</strong> {{$purchase->location->email}}
        @endif
      </address>
    </div>
  </div>

</div>
<div class="modal-body">
  <div class="row">
    <div class="col-sm-12">
      <p class="pull-rightt"><b>تاريخ:</b> {{ @format_date($purchase->transaction_date) }}</p>
    </div>
  </div>
  
  <div class="row">
    <div class="col-sm-12 col-xs-12">
<h3 class="test_center"> كشف استلام بضاعة </h3>
<h3 class="text_ret"> / السادة <h3>
</div>
</div>

  <br>
  <div class="row page_body">
    <div class="col-sm-12 col-xs-12">
      <div class="table-responsive">
        <table class="table bg-gray">
          <thead>
            <tr class="bg-green">
              <th class="test_center">ملحوظات</th>
               <th >@if($purchase->type == 'purchase_order') كمية الشراء @else كمية الشراء @endif</th>
              <th>اسم المنتج</th>
              <th>م</th>
            </tr>
          </thead>
          @php 
            $total_before_tax = 0.00;
          @endphp
          @foreach($purchase->purchase_lines as $purchase_line)
            <tr>
              <td class="test_center"><span>{{ $purchase_det->additional_notes }}</span></td>
              <!-- <td class="test_center"><span>{{ $purchase_det->expense_reason }}</span></td> -->
               <td><span class="display_currency" data-is_quantity="true" data-currency_symbol="false">{{ $purchase_line->quantity }}</span> @if(!empty($purchase_line->sub_unit)) {{$purchase_line->sub_unit->short_name}} @else {{$purchase_line->product->unit->short_name}} @endif</td>
              <td>
                {{ $purchase_line->product->name }}
                 @if( $purchase_line->product->type == 'variable')
                  - {{ $purchase_line->variations->product_variation->name}}
                  - {{ $purchase_line->variations->name}}
                 @endif
              </td>   
               <td>{{ $loop->iteration }}</td>
            @php 
              $total_before_tax += ($purchase_line->quantity * $purchase_line->purchase_price);
            @endphp
          @endforeach
        </table>
      </div>
    </div>
  </div>
   <!-- <div class="col-sm-2 invoice-col"> -->
     

      <!-- </div> -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col footer_left">
          <p></p>
       <br> <span>الختم   </span>
</div>
<div class="col-sm-4 invoice-col">
</div>
     <div class="col-sm-4 invoice-col invoice-coll">
        <p>( تم استلام البضاعة الموضحة اعلاه )</p>
        <br>
        <span>:المستلم   </span><br>
         <span>  / الاسم </span><br>
          <span>/ التوقيع   </span>

</div>
</div>
  <br>
  <!-- <div class="row">
    @if(!empty($purchase->type == 'purchase'))
    <div class="col-sm-12 col-xs-12">
      <h4>{{ __('sale.payment_info') }}:</h4>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
      <div class="table-responsive">
        <table class="table">
          <tr class="bg-green">
            <th>#</th>
            <th>{{ __('messages.date') }}</th>
            <th>{{ __('purchase.ref_no') }}</th>
            <th>{{ __('sale.amount') }}</th>
            <th>{{ __('sale.payment_mode') }}</th>
            <th>{{ __('sale.payment_note') }}</th>
          </tr>
          @php
            $total_paid = 0;
          @endphp
          @forelse($purchase->payment_lines as $payment_line)
            @php
              $total_paid += $payment_line->amount;
            @endphp
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ @format_date($payment_line->paid_on) }}</td>
              <td>{{ $payment_line->payment_ref_no }}</td>
              <td><span class="display_currency" data-currency_symbol="true">{{ $payment_line->amount }}</span></td>
              <td>{{ $payment_methods[$payment_line->method] ?? '' }}</td>
              <td>@if($payment_line->note) 
                {{ ucfirst($payment_line->note) }}
                @else
                --
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center">
                @lang('purchase.no_payments')
              </td>
            </tr>
          @endforelse
        </table>
      </div>
    </div>
    @endif
    <div class="col-md-6 col-sm-12 col-xs-12 @if($purchase->type == 'purchase_order') col-md-offset-6 @endif">
      <div class="table-responsive">
        <table class="table">
          
          <tr>
            <th>@lang('purchase.net_total_amount'): </th>
            <td></td>
            <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $total_before_tax }}</span></td>
          </tr>
          <tr>
            <th>@lang('purchase.discount'):</th>
            <td>
              <b>(-)</b>
              @if($purchase->discount_type == 'percentage')
                ({{$purchase->discount_amount}} %)
              @endif
            </td>
            <td>
              <span class="display_currency pull-right" data-currency_symbol="true">
                @if($purchase->discount_type == 'percentage')
                  {{$purchase->discount_amount * $total_before_tax / 100}}
                @else
                  {{$purchase->discount_amount}}
                @endif                  
              </span>
            </td>
          </tr>
          <tr>
            <th>@lang('purchase.purchase_tax'):</th>
            <td><b>(+)</b></td>
            <td class="text-right">
                @if(!empty($purchase_taxes))
                  @foreach($purchase_taxes as $k => $v)
                    <strong><small>{{$k}}</small></strong> - <span class="display_currency pull-right" data-currency_symbol="true">{{ $v }}</span><br>
                  @endforeach
                @else
                0.00
                @endif
              </td>
          </tr>
          @if( !empty( $purchase->shipping_charges ) )
            <tr>
              <th>@lang('purchase.additional_shipping_charges'):</th>
              <td><b>(+)</b></td>
              <td><span class="display_currency pull-right" >{{ $purchase->shipping_charges }}</span></td>
            </tr>
          @endif
          <tr>
            <th>@lang('purchase.purchase_total'):</th>
            <td></td>
            <td><span class="display_currency pull-right" data-currency_symbol="true" >{{ $purchase->final_total }}</span></td>
          </tr>
        </table>
      </div>
    </div>
  </div> -->
  <!-- <div class="row">
    <div class="col-sm-6">
      <strong>@lang('purchase.shipping_details'):</strong><br>
      <p class="well well-sm no-shadow bg-gray">
        @if($purchase->shipping_details)
          {{ $purchase->shipping_details }}
        @else
          --
        @endif
      </p>
    </div>
    <div class="col-sm-6">
      <strong>@lang('purchase.additional_notes'):</strong><br>
      <p class="well well-sm no-shadow bg-gray">
        @if($purchase->additional_notes)
          {{ $purchase->additional_notes }}
        @else
          --
        @endif
      </p>
    </div>
  </div> -->
  <!-- @if(!empty($activities))
  <div class="row">
    <div class="col-md-12">
          <strong>{{ __('lang_v1.activities') }}:</strong><br>
          @includeIf('activity_log.activities', ['activity_type' => 'purchase'])
      </div>
  </div>
  @endif -->

  {{-- Barcode --}}
  <div class="row print_section">
    <div class="col-xs-12">
      <img class="center-block" src="data:image/png;base64,{{DNS1D::getBarcodePNG($purchase->ref_no, 'C128', 2,30,array(39, 48, 54), true)}}">
    </div>
  </div>
</div>
</div>