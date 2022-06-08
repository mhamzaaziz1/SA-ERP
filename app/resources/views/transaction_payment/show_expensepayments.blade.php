<style>
.box_head{
    border: 1px solid black;
    width:250px;
    margin-left: 20px;
    padding: 10px;
}
.box_head_right{
    border: 1px solid black;
    width:200px;
    margin-left: 20px;
    padding: 10px;
    float:right;
    margin-right: 50px;
}
.box_head_right_end{
    /* border: 1px solid black; */
    width:250px;
    margin-left: 20px;
    padding: 10px;
    /* float:right; */
    /* text-align: center; */
    margin-right: 50px;
}

.box_end{
    border: 1px solid black;
    width:250px;
    height: 60px;
    margin-left: 20px;
    /* padding: 10px; */
}
.requester_signature{
    padding-bottom: 50px;
    font-size: 14px;
    margin-top: 0px;
}
.finance_signature{
    text-align: center;
     padding-bottom: 50px;
    font-size: 14px;
    margin-top: 0px;
}

.box_end_right{
    border: 1px solid black;
    width:250px;
    height: 60px;
    margin-left: 20px;
    /* padding: 10px; */
    float:right;
    margin-right: 50px;
}
.footer{
    text-align: center;
    border: 1px solid black;
    /* width:200px; */
    height: 60px;
    margin-top: 20px;
}
.date{
height: 20px;
/* padding: 10px; */
/* background-color: black; */
/* margin: 20px; */
}
.right{
float:right;
margin-right: 130px;
}
.requester{
    /* border: 2px solid black; */
    /* margin-left: 20px; */
    /* margin-right: 20px; */
    padding: 10px;
    width:100%;
    margin-top: 10px;
}
.reson{
    border: 1px solid black;
    /* margin-left: 20px; */
    /* margin-right: 20px; */
    padding: 10px;
    width:100%;
    /* margin-top: 50px; */
}

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
.teb{
    margin-top: 20px;
}
.he{
    text-align: center;
}


</style>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title no-print he">
                <!-- @lang( 'purchase.view_payments' )  -->
                @lang( 'Expense sheet No' )
                (
                @if(in_array($transaction->type, ['purchase', 'expense', 'purchase_return', 'payroll']))    
                     {{ $transaction->ref_no }} 
                     <!-- @lang('purchase.ref_no'): -->
                @elseif(in_array($transaction->type, ['sell', 'sell_return']))
                    @lang('sale.invoice_no'): {{ $transaction->invoice_no }}
                @endif
                )   
            </h4>
            <h4 class="modal-title visible-print-block he">
                @if(in_array($transaction->type, ['purchase', 'expense', 'purchase_return', 'payroll'])) 
                    <!-- @lang('purchase.ref_no'): {{ $transaction->ref_no }} -->
                     @lang( 'Expense sheet No' ) ( {{ $transaction->ref_no }} )
                @elseif($transaction->type == 'sell')
                    @lang('sale.invoice_no'): {{ $transaction->invoice_no }}
                @endif
            </h4>
        </div>

        <div class="modal-body">
            @if(in_array($transaction->type, ['purchase', 'purchase_return']))
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        @include('transaction_payment.transaction_supplier_details')
                    </div>
                    <div class="col-md-4 invoice-col">
                        @include('transaction_payment.payment_business_details')
                    </div>

                    <div class="col-sm-4 invoice-col">
                        <b>@lang('purchase.ref_no'):</b> #{{ $transaction->ref_no }}<br/>
                        <b>@lang('messages.date'):</b> {{ @format_date($transaction->transaction_date) }}<br/>
                        <b>@lang('purchase.purchase_status'):</b> {{ __('lang_v1.' . $transaction->status) }}<br>
                        <b>@lang('purchase.payment_status'):</b> {{ __('lang_v1.' . $transaction->payment_status) }}<br>
                    </div>
                </div>
            @elseif(in_array($transaction->type, ['expense', 'expense_refund']))

                <div class="row invoice-info ">
                     @if(!empty($transection_subcategory))
                        
        <div class="col-md-6 invoice-col box_head">
            
                <b>  @lang('Supplier'):</b>
                            <address>
                                <strong>{{ $contacts->supplier_business_name }}</strong>
                                 <b>@lang('Name'):</b> {{ $contacts->name }}
                                 @if(!empty($contacts->address_line_1))                                
                                <br><b>@lang('Address'):</b> {{ $contacts->address_line_1 }}
                                @endif
                                @if(!empty($contacts->country))
                                    <br><b>@lang('Country'):</b> {{$contacts->country}}
                                @endif
                                @if(!empty($contacts->city))
                                    <br><b>@lang('City'):</b> {{$contacts->city}}
                                @endif
                                @if(!empty($transaction->contact->tax_number))
                                    <br><b>@lang('contact.tax_no'): </b>{{$transaction->contact->tax_number}}
                                @endif
                                @if(!empty($transaction->contact->mobile))
                                    <br><b>@lang('contact.mobile'): </b>{{$transaction->contact->mobile}}
                                @endif
                                @if(!empty($transaction->contact->email))
                                    <br><b>@lang('business.email'):</b> {{$transaction->contact->email}}
                                @endif


                            </address>
         </div> 
         <div class="col-md-2 invoice-col">
                </div>
    <div class="col-md-4 invoice-col box_head_right">
              <b> Business:</b>
              @if(!empty($transaction->location->landmark))
                    <br> <b>@lang('Name'):</b> <strong>{{ $transaction->business->name }}</strong>
               @endif
            
             @if(!empty($transaction->location->landmark))
           <br><b>@lang('Location'):</b> {{$transaction->location->landmark}}
             @endif
             @if(!empty($transaction->location->city) || !empty($transaction->location->state) || !empty($transaction->location->country))
            <br> <b>@lang('Country'):</b>  {{implode(',', array_filter([$transaction->location->city, $transaction->location->state, $transaction->location->country]))}}
             @endif
              @if(!empty($transaction->business->tax_number_1))
         <br>  <b> {{$transaction->business->tax_label_1}}:</b> {{$transaction->business->tax_number_1}}
       @endif
        @if(!empty($transaction->business->tax_number_2))
      <br><b>{{$transaction->business->tax_label_2}}:</b>  {{$transaction->business->tax_number_2}}
      @endif
      @if(!empty($transaction->location->mobile))
      <br><b>@lang('contact.mobile'):</b> {{$transaction->location->mobile}}
       @endif 
       @if(!empty($transaction->location->email))
       <br> <b>@lang('business.email'):</b> {{$transaction->location->email}}
    
         @endif 
         </div>          
    </div>
           <div class="date">
              <div class="right">
               <b>@lang('messages.date'):</b>         {{ @format_date($transaction->transaction_date) }}
              </div>
           </div>
          <div class=" requester">
              <b>@lang('Expense Requester'):</b> {{ $name }}
              <br> <b>@lang('Expense Beneficiary'):</b> {{ $expensebeneficiary->name }}
          </div>
           <div class="reson">
               <b>@lang('Expense reason'):</b> {{ $transaction->expense_reason }}
               </div>
<div class="teb">
<table>
  <tr>
    <th>Sub Category</th>
    <th>Quantity</th>
    <th>Unit</th>
    <th>Unit Price</th>
    <th>Total Price</th>
  </tr>
  <!-- {{ $transaction->id }} -->
   @if(!empty($transection_subcategory))
  @foreach($transection_subcategory as $transection_subcategor)  
  <tr>
    <td>{{ $transection_subcategor->sub_category_name->name }}</td>
    <td>{{ $transection_subcategor->quantity }}</td>
    <td>{{ $expense_categories->unit }}</td>
    <td>{{ $transection_subcategor->amount }}</td>
    <td>{{ $transection_subcategor->total_amount }}</td>
  </tr>
  @endforeach
  <tr>
    <td><b> Total </b></td>
    <td><b>{{ $transection_subcategor->total_quantity }}</b></td>
    <td></td>
    <td></td>
    <td><b>{{ $transection_subcategor->sub_total_amount }}</b></td>
  </tr>
  @endif
</table>
</div>
                <div class="row invoice-info ">
                   <div class="col-md-6 invoice-col box_head_right_end">
                      <!-- <b>  @lang('Supplier'):</b> -->
                            <address>
                                @if(!empty($transection_subcategor->sub_total_amount))
                                 <b>@lang('Total Amount'):</b> {{ $transection_subcategor->sub_total_amount }}
                                @endif
                                 @if(!empty($payments->method))
                                                               
                                <br><b>@lang('purchase.payment_method'):</b> {{ $payments->method }}
                                @endif
                               <br><b> @lang('purchase.ref_no'):</b> {{ $transaction->ref_no }}
                               @if(!empty($payments->paid_on))
                               <br><b> @lang('messages.date'):</b>  {{ @format_datetime($payments->paid_on) }}
                              
                               @endif                                
                            </address>
                    </div>
                </div>
                <!-- 'transactions.is_recurring',
                            'transactions.recur_interval',
                            'transactions.recur_interval_type',
                            'transactions.recur_repetitions', -->

                 <div class="row invoice-info ">
                   <div class="col-md-6 invoice-col box_head_right_end">
                      <!-- <b>  @lang('Supplier'):</b> -->
                      <!-- {{ $transaction->is_recurring }}
                      {{ $transaction->recur_interval }}
                      {{ $transaction->recur_repetitions }} -->
                            <address>
                                @if(!empty($transaction->is_recurring))                                
                                 <b>@lang('Is Recurring'):</b>  Yes
                                 @else
                                  <b>@lang('Is Recurring'):</b>  No
                                 @endif
                                 @if(!empty($transaction->recur_interval))
                                            
                                <br><b>@lang('Recurring interval'): </b> {{ $transaction->recur_interval }} Days
                                @endif
                                @if(!empty($transaction->recur_repetitions))
                               <br><b> @lang('No. of Repetitions'): </b> {{ $transaction->recur_repetitions }}
                                     @endif                        
                            </address>
                    </div>
                </div>
        <div class="row invoice-info ">
              <div class="col-md-4 invoice-col box_end">
                <b class="requester_signature">Requester signature</b>
              </div> 
              <div class="col-md-2 invoice-col">
                 
                </div>
           
              <div class="col-md-6 invoice-col box_end_right">
             <b class="finance_signature">Finance department signature</b>
              </div>          
        </div>

        <div class="footer">
         <b>Notes</b>
        </div>   
                     @endif
            @elseif($transaction->type == 'payroll')
                <div class="row invoice-info">
                    
                    <div class="col-md-4 invoice-col">
                        @lang('essentials::lang.payroll_for'):
                        <address>
                            <strong>{{ $transaction->transaction_for->user_full_name }}</strong>
                            @if(!empty($transaction->transaction_for->address))
                                <br>{{$transaction->transaction_for->address}}
                            @endif
                            @if(!empty($transaction->transaction_for->contact_number))
                                <br>@lang('contact.mobile'): {{$transaction->transaction_for->contact_number}}
                            @endif
                            @if(!empty($transaction->transaction_for->email))
                                <br>@lang('business.email'): {{$transaction->transaction_for->email}}
                            @endif
                        </address>
                    </div>
                    <div class="col-md-4 invoice-col">
                        @include('transaction_payment.payment_business_details')
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <b>@lang('purchase.ref_no'):</b> #{{ $transaction->ref_no }}<br/>
                        @php
                            $transaction_date = \Carbon::parse($transaction->transaction_date);
                        @endphp
                        <b>@lang( 'essentials::lang.month_year' ):</b> {{ $transaction_date->format('F') }} {{ $transaction_date->format('Y') }}<br/>
                        <b>@lang('purchase.payment_status'):</b> {{ __('lang_v1.' . $transaction->payment_status) }}<br>
                    </div>
                </div>
            @else
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        @lang('contact.customer'):
                        <address>
                            <strong>{{ $transaction->contact->name }}</strong>

                            {!! $transaction->contact->contact_address !!}
                            @if(!empty($transaction->contact->tax_number))
                                <br>@lang('contact.tax_no'): {{$transaction->contact->tax_number}}
                            @endif
                            @if(!empty($transaction->contact->mobile))
                                <br>@lang('contact.mobile'): {{$transaction->contact->mobile}}
                            @endif
                            @if(!empty($transaction->contact->email))
                                <br>@lang('business.email'): {{$transaction->contact->email}}
                            @endif
                        </address>
                    </div>
                    <div class="col-md-4 invoice-col">
                        @include('transaction_payment.payment_business_details')
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <b>@lang('sale.invoice_no'):</b> #{{ $transaction->invoice_no }}<br/>
                        <b>@lang('messages.date'):</b> {{ @format_date($transaction->transaction_date) }}<br/>
                        <b>@lang('purchase.payment_status'):</b> {{ __('lang_v1.' . $transaction->payment_status) }}<br>
                    </div>
                </div>
            @endif

            @can('send_notification')
                @if($transaction->type == 'purchase')
                    <div class="row no-print">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-info btn-modal btn-xs" 
                            data-href="{{action('NotificationController@getTemplate', ['transaction_id' => $transaction->id,'template_for' => 'payment_paid'])}}" data-container=".view_modal"><i class="fa fa-envelope"></i> @lang('lang_v1.payment_paid_notification')</button>
                        </div>
                    </div>
                    <br>
                @endif
                @if($transaction->type == 'sell')
                    <div class="row no-print">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-info btn-modal btn-xs" 
                            data-href="{{action('NotificationController@getTemplate', ['transaction_id' => $transaction->id,'template_for' => 'payment_received'])}}" data-container=".view_modal"><i class="fa fa-envelope"></i> @lang('lang_v1.payment_received_notification')</button>
                          
                            @if($transaction->payment_status != 'paid')
                                &nbsp;
                                <button type="button" class="btn btn-warning btn-modal btn-xs" data-href="{{action('NotificationController@getTemplate', ['transaction_id' => $transaction->id,'template_for' => 'payment_reminder'])}}" data-container=".view_modal"><i class="fa fa-envelope"></i> @lang('lang_v1.send_payment_reminder')</button>
                            @endif
                        </div>
                    </div>
                    <br>
                @endif
            @endcan           
        <div class="modal-footer">
            <button type="button" class="btn btn-primary no-print" 
              aria-label="Print" 
                onclick="$(this).closest('div.modal').printThis();">
                <i class="fa fa-print"></i> @lang( 'messages.print' )
            </button>
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang( 'messages.close' )</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->