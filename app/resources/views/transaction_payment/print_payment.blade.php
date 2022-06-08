<style>
.bord_print {
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
  height: 55vh;
}
.last{
    margin-top: 60px;
/* background-color: black; */
 /* border: 1px solid #d2d7da; */
}
</style>
<div class="bord_print border">
<div class="modal-header head">
    <!-- <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
 <!-- {{ $payment }} -->
   
   <div class="row invoice-info">
    <div class="col-sm-3 invoice-col">
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
         
        <!-- {!! QrCode::size(100)->generate($qrcode) !!} -->
    </div>
    

    <div class="col-sm-3 invoice-col invoice-coll">
        <address>
        @if(!empty($business_details->name))
        {{$business_details->name}} <strong>:عمل</strong> 
        @endif
        @if(!empty($business_location_details->landmark))
          <br><strong>متحرك:</strong> {{$business_location_details->landmark}}
        @endif
      
        @if(!empty($business_details->tax_number_1))
          <br>{{$business_details->tax_number_1}} <strong>:{{$business_details->tax_label_1}}</strong> 
        @endif

        @if(!empty($business_details->tax_number_2))
          <br>{{$business_details->tax_number_2}} <strong>:{{$business_details->tax_label_2}}</strong> 
        @endif

        @if(!empty($business_details->mobile))
          <br><strong>معلم معروف:</strong> {{$business_details->mobile}}
        @endif
        @if(!empty($business_details->email))
          <br><strong>البريد الإلكتروني:</strong> {{$business_details->email}}
        @endif
      </address>
    </div>
  </div>

</div>
<div class="modal-body">
  <div class="row">
    <div class="col-sm-12">
      <p class="pull-rightt"><b>تاريخ:</b> {{ @format_date($payment->paid_on) }}</p>
    </div>
  </div>
  
  <div class="row">
    <div class="col-sm-12 col-xs-12">
<h3 class="test_center"> خطـاب مطـالـبـة » رفت ٠٠٠١٤٤  »</h3>
<h3 class="text_ret">--------------------------------------------------------------------------- / سعـادة   &nbsp; &nbsp; &nbsp;  -------------------------------------------------------------------------------------- <h3>
</div>
</div>

<div class="row">
    <div class="col-sm-12 col-xs-12">
<h3 class="test_center"> السلام عليكم ورحمة الله وبركاته </h3>
</div>
</div>

  <br>
  <div class="row page_body">
    <div class="col-sm-12 col-xs-12">
      <h3 class="text_ret">بناء على تعميـدكـم رقـم : &nbsp; &nbsp; &nbsp;&nbsp; <u>{{ $payment->approval_number }} </u>&nbsp; &nbsp; &nbsp;&nbsp;   وتاريخ    &nbsp; &nbsp; &nbsp;&nbsp;<u> {{ @format_date($payment->approval_date) }}</u></h3>
      <h3 class="text_ret">وتاريخ   &nbsp; &nbsp; &nbsp;&nbsp;<u> {{ @format_date($payment->paid_on) }}</u>&nbsp; &nbsp; &nbsp;&nbsp;<u>{{ $payment->payment_ref_no }} </u>&nbsp; &nbsp; &nbsp;&nbsp; : تم التسليم بالفاتورة رقم   </h3>
      <h3 class="text_ret">---------(  &nbsp; {{ $payment->amount }} &nbsp;) ,إلى المستودعات لذا نأمل صرف مستحقات المؤسسة وقدرها </h3>
      
      <h3> &nbsp; &nbsp; ---------------------------------------------------------------------------------- </h3>
   
    <div class="row last">
    <div class="col-md-12 mt-0">
<h3 class="test_center"> ، ، ، شاكرين لكم حسن تعاونكم معنا .</h3>
</div>
</div>
   
<div class="row invoice-info last">
      <div class="col-sm-4 invoice-col footer_left">
          <p></p>
       <br> <h3>مكتبة هضاب تبوك</h3>
</div>

</div>
    </div>
  </div>

 

    