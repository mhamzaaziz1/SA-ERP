<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('SubCategoryController@update', [$expense_subcategory->id]), 'method' => 'PUT', 'id' => 'expense_subcategory_add_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'Edit expense sub category' )</h4>
    </div>

    <div class="modal-body">
     <div class="form-group">
        {!! Form::label('name', __( 'Sub category name' ) . ':*') !!}
          {!! Form::text('name', $expense_subcategory->name, ['class' => 'form-control', 'required', 'placeholder' => __( 'Sub category name' )]); !!}
      </div>

      <!-- <div class="form-group">
        {!! Form::label('code', __( 'expense.category_code' ) . ':') !!}
          {!! Form::text('code', $expense_subcategory->code, ['class' => 'form-control', 'placeholder' => __( 'expense.category_code' )]); !!}
      </div> -->
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->