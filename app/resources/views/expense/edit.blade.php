@extends('layouts.app')
@section('title', __('expense.edit_expense'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('expense.edit_expense')</h1>
</section>

<!-- Main content -->
<section class="content">
  {!! Form::open(['url' => action('ExpenseController@update', [$expense->id]), 'method' => 'PUT', 'id' => 'add_expense_form', 'files' => true ]) !!}
  <div class="box box-solid">
    <div class="box-body">
      <div class="row">
         <div class="col-sm-3">
					<div class="form-group">
							{!! Form::label('quantity', __('Expense Reason') . ':*') !!}
						{!! Form::text('expense_reason', $expense->expense_reason, ['class' => 'form-control', 'placeholder' => __('Add Expense reason'), 'required']); !!}
					</div>
				</div>
         <div class="clearfix"></div>
        <div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('location_id', __('purchase.business_location').':*') !!}
            {!! Form::select('location_id', $business_locations, $expense->location_id, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required']); !!}
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('expense_for', __('Expense Requester').':') !!} @show_tooltip(__('tooltip.expense_for'))
            {!! Form::select('expense_for', $users, $expense->expense_for, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <!-- {!! Form::label('transaction_date', __('Expense Beneficiary') . ':*') !!} -->
            	{!! Form::label('Expense Beneficiary', __('Expense Beneficiary').':') !!} @show_tooltip(__('tooltip.expense_for'))
						{!! Form::select('expensebeneficiary', $expensebeneficiary, $expensebeneficiary_edit->id, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
           <!-- $expensebeneficiary_edit->name -->
          </div>
        </div>
         <div class="clearfix"></div>
<!-- <div class="row"> -->
 
  @foreach($transection_subcategory as $transection_subcategor)

 
				<div class="col-sm-5">
					<div class="form-group">
            {!! Form::hidden('maincategory_id[]', $transection_subcategor["category"]->id) !!}
						{!! Form::label('expense_category_id', __('expense.expense_category').':') !!}
						{!! Form::select('expense_category_id[]', $expense_categories,  $transection_subcategor["category"]->category_id, ['class' => 'form-control select2', 'id' => '0', 'onchange' => 'get_subcategory(this.id)',  'placeholder' => __('messages.please_select'), "required"]); !!}
                    </div>
				</div>
				<!-- <div class="col-sm-1" style=" margin-top: 0px; ">
					<div class="form-group" id="category_button" title="Add Category" style=" margin-top: 25px; margin-left: 25px; ">
					<svg xmlns="http://www.w3.org/2000/svg"  width="30" height="30" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                       <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                      </svg>
				</div>
				</div> -->

          <div class="clearfix"></div>
              <div class="col-sm-3">
					<div class="form-group">
            {!! Form::hidden('transection_subcategory_id[]', $transection_subcategor['subcategory']->id) !!}
						{!! Form::label('Expense Subcategory', __('Expense Subcategory').':') !!} @show_tooltip(__('tooltip.expense_for'))
						{!! Form::select('sub_category0[]', $sub_category, $transection_subcategor['subcategory']->subcategory_id, ['class' => 'form-control select2','id' => 'value_sub_category_drop0', 'placeholder' => __('messages.please_select'). "required"]); !!}
					</div>
				</div>		
						      <!--sub_categoryy  'onchange' => 'get_subcategory(this.id)', -->
				<div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('quantity', __('Quantity') . ':*') !!}
						{!! Form::text('quantity0[]', $transection_subcategor['subcategory']->quantity, ['class' => 'form-control input_number', 'id' => 'quantity', 'placeholder' => __('Add Quantity'), 'required']); !!}
					</div>
				</div>
				
				  <div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('final_amount', __('Amount') . ':*') !!}
						{!! Form::text('amount0[]', $transection_subcategor['subcategory']->amount, ['class' => 'form-control input_number', 'id' => 'amount', 'onchange' => 'calculateExpenseTotalPayment(this.id)',  'placeholder' => __('amount'), 'required']); !!}
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('Supplier', __('Supplier').':') !!} 
						{!! Form::select('contact_id0[]', $contacts,  $transection_subcategor["contact"]->id, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), "required"]); !!}
					</div>
				</div>
				<!-- float: right; -->
				<!-- <div class="col-sm-1" style=" margin-top: 0px;">
					<div class="form-group" id="sub_category_button" title="Add Sub Category" style=" margin-top: 25px; margin-left: 25px; ">
					<svg xmlns="http://www.w3.org/2000/svg"  width="30" height="30" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                       <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                      </svg>
					 
				</div>
				</div> -->
        @endforeach
           <!-- <div class="row">
      <div class="col-sm-4">
</div>
  <div class="col-sm-4">
</div>
<div class="col-sm-3">
</div>
<div class="col-sm-1" >
					<div class="form-group" id="sub_category_button" style=" margin-top: 25px;">
					<svg xmlns="http://www.w3.org/2000/svg"  width="30" height="30" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                       <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                      </svg>
				</div>
				</div>
        	</div> -->
		
      <div  id="sub_category_fields">

			</div>
				<!-- <div class="col-md-12" id="sub_category_fields"></div>
		
			<div  id="category_fields"></div> -->
			<br>
			<br>

        <div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('additional_notes', __('expense.expense_note') . ':') !!}
                {!! Form::textarea('additional_notes', $expense->additional_notes, ['class' => 'form-control', 'rows' => 3]); !!}
          </div>
        </div>
      
       
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('document', __('purchase.attach_document') . ':') !!}
                {!! Form::file('document', ['id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
                <p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                @includeIf('components.document_help_text')</p>
            </div>
        </div>
         
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('tax_id', __('product.applicable_tax') . ':' ) !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    {!! Form::select('tax_id', $taxes['tax_rates'], $expense->tax_id, ['class' => 'form-control'], $taxes['attributes']); !!}

            <input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
            value="0">
                </div>
            </div>
        </div>      
        <div class="clearfix"></div>
        
      </div>
     
        
      <!-- @foreach($transection_subcategory as $transection_subcategor)
      <div class="row">
          <div class="col-sm-4">
					<div class="form-group">
            {!! Form::hidden('subcategory_id[]', $transection_subcategor['subcategory']->id) !!}
						{!! Form::label('Expense Subcategory', __('Expense Subcategory').':') !!} @show_tooltip(__('tooltip.expense_for'))
						{!! Form::select('sub_category[]', $sub_category, $transection_subcategor['subcategory']->subcategory_id, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
		
					</div>
				</div>
				      
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('quantity', __('Quantity') . ':*') !!}
						{!! Form::text('quantity[]', $transection_subcategor['subcategory']->quantity, ['class' => 'form-control input_number', 'id' => 'quantity', 'placeholder' => __('Add Quantity'), 'required']); !!}
					</div>
				</div>
				
				  <div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('final_amount', __('Amount') . ':*') !!}
						{!! Form::text('amount[]', $transection_subcategor['subcategory']->amount, ['class' => 'form-control input_number', 'id' => 'amount', 'onchange' => 'calculateExpenseTotalPayment(this.id)',  'placeholder' => __('amount'), 'required']); !!}
					</div>
				</div>
			</div>
      @endforeach -->
   
			<div class="row">
				<div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('final_total', __('sale.total_amount') . ':*') !!}
            {!! Form::text('final_total', @num_format($sub_total), ['class' => 'form-control input_number', 'placeholder' => __('sale.total_amount'), 'required']); !!}
          </div>
        </div>
        <div class="col-sm-4 bg-dark">
					<div class="form-group" style=" margin-top: 25px;">
				<label>
		              {!! Form::checkbox('is_refund', 1, false, ['class' => 'input-icheck', 'id' => 'is_refund']); !!} @lang('lang_v1.is_refund')?
		            </label>@show_tooltip(__('lang_v1.is_refund_help'))
				</div>
				</div>
    </div>
  </div> <!--box end-->
  @include('expense.recur_expense_form_part')
  <div class="col-sm-12">
    <button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
  </div>

{!! Form::close() !!}
</section>
@stop
@section('javascript')
<script type="text/javascript">
  __page_leave_confirmation('#add_expense_form');
  function calculateExpenseTotalPayment(id)
{
var quantity = $("input[name='quantity[]']")
              .map(function(){return $(this).val();}).get();
var amount = $("input[name='amount[]']")
              .map(function(){return $(this).val();}).get();

var sub_total = 0;
	$.each(quantity, function(index, value) {
var total = amount[index] * value;
sub_total = sub_total + total;
		//  console.log('Test=>  ');
});
// console.log(sub_total);
$("input#final_total").val(__currency_trans_from_en(sub_total, true, false));
$('#payment_due').text(__currency_trans_from_en(sub_total, true, false));
}

  $(document).ready(function() {
    $("input#final_total").prop("readonly", true);
	//===================Add Sub category fields =========================//
    var max_fields      = 10;
    var wrapper         = $("#sub_category_fields"); 
    var add_button      = $("#sub_category_button"); 				
    var x = 1;
    $(add_button).click(function(e){
        e.preventDefault();
		document.getElementById('sub_category_fields').style.display = 'block';
        if(x < max_fields){ 
            x++;
			// console.log(x); 
            $("#rm").remove();
			// document.getElementById('rm').style.display = 'none';
               $(wrapper).append('<div id="divs" class="row"><div  class="col-sm-4"><div class="form-group">{!! Form::hidden("subcategory_id[]", 0) !!} 
               
               +'{!! Form::label("Expense Subcategory", __("Expense Subcategory").":") !!}{!! Form::select("sub_category[]", $sub_category, null, ["class" => "form-control select2", "placeholder" => __("messages.please_select")]); !!}</div></div>' //add input box
			    + '<div  class="col-sm-4"><div class="form-group">{!! Form::label("quantity", __("Quantity") . ":*") !!}{!! Form::text("quantity[]", null, ["class" => "form-control input_number", "id" => "quantity", "placeholder" => __("Add quantity"), "required"]); !!}</div></div>'
				+ '<div  class="col-sm-3"><div class="form-group">{!! Form::label("final_amount", __("Amount") . ":*") !!}{!! Form::text("amount[]", null, ["class" => "form-control input_number", "id" => "amount", "onchange" => "calculateExpenseTotalPayment(this.id)", "placeholder" => __("amount"), "required"]); !!}</div></div>'+ 
			  '<div class="col-sm-1" ><div class="form-group" style=" margin-top: 25px;" ><svg id="rm" class="remove_field" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-dash-circle-fill" viewBox="0 0 16 16">' + 
			  '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/></svg></div></div></div>'); //add input box "id" => "quantity",
      				
					  
			// 		   $(wrapper).append('<div id="divs" class="col-sm-4"><div class="form-group">{!! Form::label("quantity", __("Quantity") . ":*") !!}{!! Form::text("quantity[]", null, ["class" => "form-control input_number", "id" => "quantity' + x + '", "onchange" => "calculateExpenseTotalPayment(this.id)", "placeholder" => __("Add quantity"), "required"]); !!}</div></div>');
			// 	$(wrapper).append('<div id="divs" class="col-sm-3"><div class="form-group">{!! Form::label("final_amount", __("Amount") . ":*") !!}{!! Form::text("amount[]", null, ["class" => "form-control input_number", "id" => "amount' + x + '", "onchange" => "calculateExpenseTotalPayment(this.id)", "placeholder" => __("amount"), "required"]); !!}</div></div>'+ 
			//   '<div class="col-sm-1" ><div class="form-group" style=" margin-top: 25px;" ><svg id="rm" class="remove_field" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-dash-circle-fill" viewBox="0 0 16 16">' + 
			//   '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/></svg></div></div>'); //add input box "id" => "quantity",
					  // $(wrapper).before('<br>')
        }
    });
	//===================Remove Sub category fields =========================//
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $("#divs").remove(); x--;
		//  $("#divs").remove();
    //     $("#divs").remove();
		console.log(x);
		if(x == 1)
	{
		document.getElementById('sub_category_fields').style.display = 'none';
		document.getElementById('rm').style.display = 'none';
	}
	})
});
</script>
@endsection