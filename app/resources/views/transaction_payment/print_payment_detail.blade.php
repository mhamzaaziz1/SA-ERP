@extends('layouts.app')
@section('content')
<section class="content no-print">
<style>
.bord {
    border: 3px solid black;
    height: 100%;
    margin-left: 150px;
    margin-right: 150px;
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
  height: 40vh;
}
</style>
<div class="bord border">
<div class="modal-header head">
    <!-- <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
 <!-- {{ $payment }} -->
   
   <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      <address>
        <strong>@lang('business.business'):</strong>
        {{ $business_details->name }}
        @if(!empty($business_location_details->landmark))
          <br><strong>Landmark:</strong> {{$business_location_details->landmark}}
        @endif
    
        @if(!empty($business_details->tax_number_1))
          <br><strong>{{$business_details->tax_label_1}}:</strong> {{$business_details->tax_number_1}}
        @endif

        @if(!empty($business_details->tax_number_2))
          <br><strong>{{$business_details->tax_label_2}}:</strong> {{$business_details->tax_number_2}}
        @endif

        @if(!empty($business_details->mobile))
          <br><strong>@lang('contact.mobile'):</strong> {{$business_details->mobile}}
        @endif
        @if(!empty($business_details->email))
          <br><strong>@lang('business.email'):</strong> {{$business_details->email}}
        @endif
      </address>
    </div>

    <div class="col-sm-2 invoice-col">
        @php 
        $img = URL::to('') . '/uploads/business_logos/' . $business_details->logo;
        @endphp
        <img src="{{ $img }}" alt="Girl in a jacket" width="100" height="100">
    </div>
 <div class="col-sm-2 invoice-col">
     {!! QrCode::size(100)->generate($qrcode) !!}

      </div>

    <div class="col-sm-4 invoice-col invoice-coll">
       
        <address>
        @if(!empty($business_details->name))
        {{$business_details->name}} <strong>:??????</strong> 
        @endif
        @if(!empty($business_location_details->landmark))
          <br><strong>??????????:</strong> {{$business_location_details->landmark}}
        @endif
      
        @if(!empty($business_details->tax_number_1))
          <br>{{$business_details->tax_number_1}} <strong>:{{$business_details->tax_label_1}}</strong> 
        @endif

        @if(!empty($business_details->tax_number_2))
          <br>{{$business_details->tax_number_2}} <strong>:{{$business_details->tax_label_2}}</strong> 
        @endif

        @if(!empty($business_details->mobile))
          <br><strong>???????? ??????????:</strong> {{$business_details->mobile}}
        @endif
        @if(!empty($business_details->email))
          <br><strong>???????????? ????????????????????:</strong> {{$business_details->email}}
        @endif
      </address>
    </div>
  </div>

</div>
<div class="modal-body">
  <div class="row">
    <div class="col-sm-12">
      <p class="pull-rightt"><b>??????????:</b> {{ @format_date($payment->paid_on) }}</p>
     
    </div>
  </div>
  
  <div class="row">
    <div class="col-sm-12 col-xs-12">
<h3 class="test_center"> ?????????? ?????????????????? ?? ?????? ????????????  ??</h3>
<h3 class="text_ret">------------------------------------------------------------------------------------ / ????????????   &nbsp; &nbsp; &nbsp;  ------------------------------------------------------------------------------------------- <h3>
</div>
</div>

<div class="row">
    <div class="col-sm-12 col-xs-12">
<h3 class="test_center"> ???????????? ?????????? ?????????? ???????? ?????????????? </h3>
</div>
</div>

  <br>
  <div class="row page_body">
    <div class="col-sm-12 col-xs-12">
      <h3 class="text_ret">???????? ?????? ?????????????????? ???????? : &nbsp; &nbsp; &nbsp;&nbsp; <u>{{ $payment->approval_number }} </u>&nbsp; &nbsp; &nbsp;&nbsp;   ????????????    &nbsp; &nbsp; &nbsp;&nbsp;<u> {{ @format_date($payment->approval_date) }}</u></h3>
      <h3 class="text_ret">????????????   &nbsp; &nbsp; &nbsp;&nbsp;<u> {{ @format_date($payment->paid_on) }}</u>&nbsp; &nbsp; &nbsp;&nbsp;<u>{{ $payment->payment_ref_no }} </u>&nbsp; &nbsp; &nbsp;&nbsp; : ???? ?????????????? ?????????????????? ??????   </h3>
      <h3 class="text_ret">--------------------(  &nbsp; &nbsp; {{ $payment->amount }} &nbsp;&nbsp;) ,?????? ???????????????????? ?????? ???????? ?????? ?????????????? ?????????????? ???????????? </h3>
      <h3> &nbsp; &nbsp; -------------------------------------------------------------------------------------------- </h3>
   
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12 col-xs-12">
<h3 class="test_center"> ?? ?? ?? ???????????? ?????? ?????? ?????????????? ???????? .</h3>
</div>
</div>

<div class="row invoice-info">
      <div class="col-sm-4 invoice-col footer_left">
          <p></p>
       <br> <h3>?????????? ???????? ????????</h3>
</div>
<div class="col-sm-4 invoice-col">
</div>
     <div class="col-sm-4 invoice-col invoice-coll">
       
</div>

</div>
 <div class="text_ret">
     <a href="#" id="printpagebutton" class="btn btn-info print-payment-invoice" data-href="{{ action('TransactionPaymentController@printPayContactDue', [$payment->id]) }}"><i class="fas fa-print" aria-hidden="true"></i>  {{ __("messages.print") }}</a>
</div >
     <br>

  <div class="row print_section">
    <div class="col-xs-12">
    cgvhbjkvbn
    </div>
  </div>
</div>
</div>

<!-- <input type="hidden" value="{{$payment}}"  id="payment_value" /> -->

</section>

<section class="invoice print_section" id="payment_receipt_section">
</section> 
@endsection

