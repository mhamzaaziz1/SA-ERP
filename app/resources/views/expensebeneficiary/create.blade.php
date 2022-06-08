<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('ExpenseBeneficiaryController@store'), 'method' => 'post', 'id' => 'expense_category_add_form' ]) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'Add expense Beneficiary' )</h4>
    </div>

    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('name', __( 'Name' ) . ':*') !!}
          {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'enter name' )]); !!}
      </div>

      <div class="form-group">
        {!! Form::label('code', __( 'Email' ) . ':') !!}
          {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => __( 'enter email' )]); !!}
      </div>
      <div class="form-group">
        {!! Form::label('code', __( 'Phone Number' ) . ':') !!}
          {!! Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => __( 'enter phone number' )]); !!}
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->