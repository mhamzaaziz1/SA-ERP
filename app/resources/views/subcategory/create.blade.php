<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('SubCategoryController@store'), 'method' => 'post', 'id' => 'expense_subcategory_add_form' ]) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'Add expense subcategory' )</h4>
    </div>

    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('name', __( 'Expense subcategory name' ) . ':*') !!}
          {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'Expense subcategory name' )]); !!}
      </div>

      <!-- <div class="col-sm-4"> -->
					<div class="form-group">
							{!! Form::label('Expense Category', __('Expense Category').':') !!} @show_tooltip(__('tooltip.expense_for'))
						{!! Form::select('expense_category_id', $expense_category, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
						<!-- {!! Form::label('Expense Beneficiary', __('Expense Beneficiary') . ':*') !!} -->
						<!-- <div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							{!! Form::text('transaction_date', @format_datetime('now'), ['class' => 'form-control', 'readonly', 'required', 'id' => 'expense_transaction_date']); !!}
						</div> -->
					</div>
				<!-- </div> -->

      <!-- <div class="form-group">
        {!! Form::label('code', __( 'expense.category_code' ) . ':') !!}
          {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => __( 'expense.category_code' )]); !!}
      </div> -->
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->