@extends('layouts.app')
@section('title', __('expense.add_expense'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('expense.add_expense')</h1>
</section>

<!-- Main content -->
<section class="content">
	{!! Form::open(['url' => action('ExpenseController@store'), 'method' => 'post', 'id' => 'add_expense_form', 'files' => true ]) !!}
	<!-- {!! Form::open(['url' => 'saveexpenses', 'method' => 'post', 'files' => true ]) !!} -->
	<div class="box box-solid">
		<div class="box-body">
				<div class="row">
                   <div class="col-sm-3">
					<div class="form-group">
							{!! Form::label('quantity', __('Expense Reason') . ':*') !!}
						{!! Form::text('expense_reason', null, ['class' => 'form-control', 'placeholder' => __('Add Expense reason'), 'required']); !!}
					</div>
				</div>
                  </div>
				  <br>
			<div class="row">
				@if(count($business_locations) == 1)
					@php 
						$default_location = current(array_keys($business_locations->toArray())) 
					@endphp
				@else
					@php $default_location = null; @endphp
				@endif
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('location_id', __('purchase.business_location').':*') !!}
						{!! Form::select('location_id', $business_locations, $default_location, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required'], $bl_attributes); !!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('Expense Requester', __('Expense Requester').':') !!} @show_tooltip(__('tooltip.expense_for'))
						{!! Form::select('expense_for', $users, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
					</div>
				</div>
				
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('Expense Beneficiary', __('Expense Beneficiary').':') !!} @show_tooltip(__('tooltip.expense_for'))
						{!! Form::select('expensebeneficiary', $expensebeneficiary, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
						<!-- {!! Form::label('Expense Beneficiary', __('Expense Beneficiary') . ':*') !!} -->
						<!-- <div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							{!! Form::text('transaction_date', @format_datetime('now'), ['class' => 'form-control', 'readonly', 'required', 'id' => 'expense_transaction_date']); !!}
						</div> -->
					</div>
				</div>
				<div class="clearfix"></div>				
			</div>
			<br>
			<br>
			<div class="row">
				<div class="col-sm-5">
					<div class="form-group">
						{!! Form::label('expense_category_id', __('expense.expense_category').':') !!}
						{!! Form::select('expense_category_id[]', $expense_categories, null, ['class' => 'form-control select2', 'id' => '0', 'onchange' => 'get_subcategory(this.id)',  'placeholder' => __('messages.please_select'), "required"]); !!}
                    </div>
				</div>
				<div class="col-sm-1" style=" margin-top: 0px; ">
					<div class="form-group" id="category_button" title="Add Category" style=" margin-top: 25px; margin-left: 25px; ">
					<svg xmlns="http://www.w3.org/2000/svg"  width="30" height="30" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                       <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                      </svg>
				</div>
				</div>
				<div class="clearfix"></div>
              <div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('Expense Subcategory', __('Expense Subcategory').':') !!} @show_tooltip(__('tooltip.expense_for'))
						{!! Form::select('sub_category0[]', $sub_category, null, ['class' => 'form-control select2','id' => 'value_sub_category_drop0', 'placeholder' => __('messages.please_select'). "required"]); !!}
					</div>
				</div>		
						      <!--sub_categoryy  'onchange' => 'get_subcategory(this.id)', -->
				<div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('quantity', __('Quantity') . ':*') !!}
						{!! Form::text('quantity0[]', null, ['class' => 'form-control input_number', 'id' => 'quantity', 'placeholder' => __('Add Quantity'), 'required']); !!}
					</div>
				</div>
				
				  <div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('final_amount', __('Amount') . ':*') !!}
						{!! Form::text('amount0[]', null, ['class' => 'form-control input_number', 'id' => 'amount', 'onchange' => 'calculateExpenseTotalPayment(this.id)',  'placeholder' => __('amount'), 'required']); !!}
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('Supplier', __('Supplier').':') !!} 
						{!! Form::select('contact_id0[]', $contacts,  null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), "required"]); !!}
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
				<div class="col-md-12" id="sub_category_fields"></div>
		</div>
			<div  id="category_fields"></div>
			<br>
			<br>
			<div class="row">
				 <div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('additional_notes', __('expense.expense_note') . ':') !!}
								{!! Form::textarea('additional_notes', null, ['class' => 'form-control', 'rows' => 3]); !!}
					</div>
				</div>
				
					<div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('document', __('purchase.attach_document') . ':') !!}
                        {!! Form::file('document', ['id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
                        <small><p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                        @includeIf('components.document_help_text')</p></small>
                    </div>
                </div>
				<div class="col-md-4">
			    	<div class="form-group">
			            {!! Form::label('tax_id', __('product.applicable_tax') . ':' ) !!}
			            <div class="input-group">
			                <span class="input-group-addon">
			                    <i class="fa fa-info"></i>
			                </span>
			                {!! Form::select('tax_id', $taxes['tax_rates'], null, ['class' => 'form-control'], $taxes['attributes']); !!}

							<input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
							value="0">
			            </div>
			        </div>
			    </div>
			    </div>
				<div class="clearfix"></div>
					<br>
			      <br>
				<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('final_total', __('sale.total_amount') . ':*') !!}
						{!! Form::text('final_total', 0, ['class' => 'form-control input_number', 'placeholder' => __('sale.total_amount'), 'required']); !!}
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
		</div>
	</div> 
@include('expense.recur_expense_form_part')
@if(auth()->user()->can('add_expense_payment')) 
	
	@component('components.widget', ['class' => 'box-solid', 'id' => "payment_rows_div", 'title' => __('purchase.add_payment')])
	<div class="payment_row">
		@include('sale_pos.partials.payment_row_form', ['row_index' => 0, 'show_date' => true])
		<hr>
		<div class="row">
			<div class="col-sm-12">
				<div class="pull-right">
					<strong>@lang('purchase.payment_due'):</strong>
					<span id="payment_due">{{@num_format(0)}}</span>
				</div>
			</div>
		</div>
	</div>
	@endcomponent
	@endif
	<div class="col-sm-12">
		<button type="submit" class="btn btn-primary pull-right" >@lang('messages.save')</button>
	</div>
	<!--  -->
{!! Form::close() !!}
</section>
@endsection
@section('javascript')
<script type="text/javascript">
function get_subcategory(id)
{
	// console.log(id);
	var stateID = document.getElementById(id).value;
		var set_value = 'select[name="sub_category'+id+'[]"]';
		// console.log(set_value);
            if(stateID) {
                $.ajax({
                    url: '/get_subCategory?category_id='+stateID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {                    
                        $(set_value).empty();
                        $.each(data, function(key, value) {
							 $(set_value).append('<option value="'+ key +'">'+ value +'</option>');
                        });
					}
                });
            }else{
                $('select[name="sub_category"]').empty();
            }
}
$(document).ready(function() {
limit_k= 0;
max = 20;
category_count = 0;
	});

//===================Add first SUB Category =========================//
$(document).ready(function() {
    var max_fields      = 10;
    var add_sub_category_fields         = $("#sub_category_fields"); 
    var add_sub_category_button     = $("#sub_category_button"); 				
    var limit_x = 1;
    $(add_sub_category_button).click(function(e){
        e.preventDefault();
		document.getElementById('sub_category_fields').style.display = 'block';
        if(limit_x < max_fields){ 
            limit_x++;
            $("#rm_sub_category_fields").remove();
              $(add_sub_category_fields).append('<div id="remove_sub_category_content" class="row">' +
			  '<div  class="col-sm-3"><div class="form-group">{!! Form::label("Expense Subcategory", __("Expense Subcategory").":") !!}{!! Form::select("sub_category0[]", $sub_category, null, ["class" => "form-control select2", "id" => "value_sub_category_drop0", "placeholder" => __("messages.please_select"), "required"]); !!}</div></div>' //add input box
			    + '<div  class="col-sm-2"><div class="form-group">{!! Form::label("quantity", __("Quantity") . ":*") !!}{!! Form::text("quantity0[]", null, ["class" => "form-control input_number", "id" => "quantity", "placeholder" => __("Add quantity"), "required"]); !!}</div></div>'
				+ '<div  class="col-sm-3"><div class="form-group">{!! Form::label("final_amount", __("Amount") . ":*") !!}{!! Form::text("amount0[]", null, ["class" => "form-control input_number", "id" => "amount", "onchange" => "calculateExpenseTotalPayment(this.id)", "placeholder" => __("amount"), "required"]); !!}</div></div>'+
				'<div class="col-sm-3"><div class="form-group">{!! Form::label("Supplier", __("Supplier").':') !!}{!! Form::select("contact_id0[]", $contacts,  null, ["class" => "form-control select2", "placeholder" => __("messages.please_select"), "required"]); !!}</div></div>'+ 
			  '<div class="col-sm-1" ><div class="form-group" style=" margin-top: 25px; margin-left: 25px; " ><svg id="rm_sub_category_fields" class="remove_sub_category_fields" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-dash-circle-fill" viewBox="0 0 16 16">' + 
			  '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/></svg></div></div></div>'); //add input box "id" => "quantity",
        }
    });
    $(add_sub_category_fields).on("click",".remove_sub_category_fields", function(e){
        e.preventDefault(); $("#remove_sub_category_content").remove(); limit_x--;
			console.log(limit_x);
		if(limit_x == 1)
		{
			document.getElementById('rm_sub_category_fields').style.display = 'none';
		}
	})
});
//===================Add Category =========================//
$(document).ready(function() {
    var max_fieldss      = 10;
    var category_fields   = $("#category_fields"); 
    var add_category_button  = $("#category_button");  				
    var limit_y = 1;
	var main_test_id = 0;
	var categories = <?php echo json_encode($expense_categories); ?>;
	var sub_category = <?php echo json_encode($sub_category); ?>;
	var contacts = <?php echo json_encode($contacts); ?>;
	$.each(categories, function(key, value) {
	categories += '<option value="'+ key +'">'+ value +'</option>'
	});
	$.each(sub_category, function(key, value) {
	sub_category += '<option value="'+ key +'">'+ value +'</option>'
	});
	$.each(contacts, function(key, value) {
	contacts += '<option value="'+ key +'">'+ value +'</option>'
	});
    $(add_category_button).click(function(e){
        e.preventDefault();
		document.getElementById('category_fields').style.display = 'block';
        if(limit_y < max_fieldss){ 
            limit_y++;
			main_test_id++;
			category_count++;
            $("#rm_category").remove();
              $(category_fields).append('<div id="remove_category_content" class="row"><div class="col-sm-5"><div class="form-group"><label for="cars">Expense Category</label><select class="form-control" name="expense_category_id[]" id="'+main_test_id+'" onchange="get_subcategory(this.id)" required><option value="">Please Select</option>'+ categories
			   +'</select></div></div><div class="col-sm-1" ><div class="form-group" style=" margin-top: 25px; margin-left: 25px; " ><svg id="rm_category" class="remove_category" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-dash-circle-fill" viewBox="0 0 16 16">' + 
			  '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/></svg></div></div><div class="clearfix"></div>    <div class="col-sm-3"><div class="form-group"><label for="cars">Expense Subcategory</label><select class="form-control" name="sub_category'+main_test_id+'[]" required><option value="">Please Select</option>'+ sub_category
			   +'</select></div></div>  <div class="col-sm-3"><div class="form-group"><label for="cars">Quantity</label> <input class="form-control" name="quantity'+main_test_id+'[]" type="number" placeholder="Add Quantity" required></div></div>'+
			   '<div class="col-sm-3"><div class="form-group"><label for="cars">Amount</label> <input class="form-control" name="amount'+main_test_id+'[]" id="'+main_test_id+'" onchange="calculateExpenseTotalPayment(this.id)" type="number" placeholder="Add Amount" required></div></div>'+
			   '<div class="col-sm-3"><div class="form-group"><label for="cars">Supplier</label><select class="form-control" name="contact_id'+main_test_id+'[]" id="cars" required><option value="">Please Select</option>'+ contacts
			   +'</select></div></div>'+
			//    '<div class="col-sm-1" style=" margin-top: 0px;">' +
			// 	'<div class="form-group" id="' + main_test_id + '" onclick="add_subcategory_row(this.id)" style="margin-top: 25px; margin-left: 25px; "><svg xmlns="http://www.w3.org/2000/svg"  width="30" height="30" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/></svg></div></div>' +
			//     '<div class="col-md-12" id="add_sub_category_fields' + main_test_id + '"></div> '+
				' </div>');
		}
    }); 
    $(category_fields).on("click",".remove_category", function(e){ 
        e.preventDefault(); $("#remove_category_content").remove(); limit_y--;
		console.log(limit_y);
		if(limit_y == 1)
		{ 
			document.getElementById('rm_category').style.display = 'none';
		}
	}) 
});


//===================Add Second Sub Category =========================//
function add_subcategory_row(main_test_id)
{
	var sub_category = <?php echo json_encode($sub_category); ?>;
	var contacts = <?php echo json_encode($contacts); ?>;
	$.each(sub_category, function(key, value) {
	sub_category += '<option value="'+ key +'">'+ value +'</option>'
	});
	$.each(contacts, function(key, value) {
	contacts += '<option value="'+ key +'">'+ value +'</option>'
	});
	var sub_max = max * main_test_id;
	sub_max = sub_max - limit_k;
	 if(limit_k < sub_max){ 
            limit_k++;
			 $("#sub" + main_test_id + "").remove();
			 console.log('add_sub_category_fields' + main_test_id + '');
              $('#add_sub_category_fields' + main_test_id + '').append('<div id="divs_sub_category' + main_test_id + '" class="row">' +
				'<div class="col-sm-3"><div class="form-group"><label for="cars">Expense Subcategory</label><select class="form-control" name="sub_category'+ main_test_id +'[]" required><option value="">Please Select</option>'+ sub_category
			   +'</select></div></div><div class="col-sm-2"><div class="form-group"><label for="cars">Quantity</label> <input class="form-control" name="quantity'+main_test_id+'[]" type="number" placeholder="Add Quantity" required></div></div>'+
			   '<div class="col-sm-3"><div class="form-group"><label for="cars">Amount</label> <input class="form-control" name="amount'+main_test_id+'[]" id="amount" onchange="calculateExpenseTotalPayment(this.id)" type="number" placeholder="Add Amount" required></div></div>'+
			   '<div class="col-sm-3"><div class="form-group"><label for="cars">Expense Category</label><select class="form-control" name="contact_id'+main_test_id+'[]" id="contact_id'+main_test_id+'[]" required><option value="">Please Select</option>'+ contacts
			   +'</select></div></div>  <div class="col-sm-1" style=" margin-top: 0px;">' +
				'<div class="form-group" onclick="remove('+ main_test_id +')" style=" margin-top: 25px; margin-left: 25px; " ><svg id="sub' + main_test_id + '"  xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-dash-circle-fill" viewBox="0 0 16 16">' + 
			  '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/></svg></div></div>    </div>');
	     }
}
function remove(main_test_id)
{
// console.log(main_test_id);
$("#divs_sub_category" + main_test_id + "").remove();
limit_k--;
if(limit_k == 0)
	{ 
		document.getElementById('sub' + main_test_id + '').style.display = 'none';
	}
}
	$(document).ready( function(){
		$('.paid_on').datetimepicker({
            format: moment_date_format + ' ' + moment_time_format,
            ignoreReadonly: true,
        });
		$("input#final_total").prop("readonly", true);
	});
	__page_leave_confirmation('#add_expense_form');
function calculateExpenseTotalPayment(id)
{
	var sub_total = 0;
	for (let i = 0; i <= category_count; i++) {
	var quantity_first = 'input[name="quantity'+ i +'[]"]';
	var amount_first = 'input[name="amount'+ i +'[]"]';
	// "input[name='quantity0[]']"
 var quantity = $(quantity_first)
              .map(function(){return $(this).val();}).get();
 var amount = $(amount_first)
              .map(function(){return $(this).val();}).get();
			  console.log(quantity);
			  console.log(amount);
			  console.log(category_count);

 
	$.each(quantity, function(index, value) {
 var total = amount[index] * value;
 sub_total = sub_total + total;
 });
 }
 $("input#final_total").val(sub_total);
 $('#payment_due').text(__currency_trans_from_en(sub_total, true, false));
}

	$(document).on('change', 'input#final_total, input.payment-amount', function() {
		calculateExpensePaymentDue();
	});

	function calculateExpensePaymentDue() {
		var final_total = __read_number($('input#final_total'));
		var payment_amount = __read_number($('input.payment-amount'));
		var payment_due = final_total - payment_amount;
		$('#payment_due').text(__currency_trans_from_en(payment_due, true, false));
	}

	$(document).on('change', '#recur_interval_type', function() {
	    if ($(this).val() == 'months') {
	        $('.recur_repeat_on_div').removeClass('hide');
	    } else {
	        $('.recur_repeat_on_div').addClass('hide');
	    }
	});

	$('#is_refund').on('ifChecked', function(event){
		$('#recur_expense_div').addClass('hide');
	});
	$('#is_refund').on('ifUnchecked', function(event){
		$('#recur_expense_div').removeClass('hide');
	});

	$(document).on('change', '.payment_types_dropdown, #location_id', function(e) {
	    var default_accounts = $('select#location_id').length ? 
	                $('select#location_id')
	                .find(':selected')
	                .data('default_payment_accounts') : [];
	    var payment_types_dropdown = $('.payment_types_dropdown');
	    var payment_type = payment_types_dropdown.val();
	    if (payment_type) {
	        var default_account = default_accounts && default_accounts[payment_type]['account'] ? 
	            default_accounts[payment_type]['account'] : '';
	        var payment_row = payment_types_dropdown.closest('.payment_row');
	        var row_index = payment_row.find('.payment_row_index').val();

	        var account_dropdown = payment_row.find('select#account_' + row_index);
	        if (account_dropdown.length && default_accounts) {
	            account_dropdown.val(default_account);
	            account_dropdown.change();
	        }
	    }
	});

	// $('select[name="expense_category_id[]"]').change(function() {
        // getAjax('/get_subCategory', $(this).val());
		// 'url_of_the_ajax_controller', 'id='+$(this).val()

// var category = $("input[name='expense_category_id[]']")
// .map(function(){return $(this).val();}).get();
//  var subcategory = $("input[name='sub_category0[]']")
// .map(function(){return $(this).val();}).get();

//  var sub_total = 0;
// 	$.each(category, function(index, value) {
//  var total = amount[index] * value;
//  sub_total = sub_total + total;
//  });		
// });
</script>
@endsection


