<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('CheckManagementController@update', [$booking->id]), 'method' => 'PUT', 'id' => 'edit_cheque_add_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Edit Cheque Details</h4>
    </div>

    <div class="modal-body">
     <div class="form-group">
        {!! Form::label('cheque_number', __( 'Cheque Number' ) . ':') !!}
          {!! Form::text('cheque_number', $booking->cheque_number, ['class' => 'form-control', 'required', 'placeholder' => __( 'cheque_number' )]); !!}
      </div>

      <div class="form-group">
        {!! Form::label('bank_name', __( 'Bank Name' ) . ':') !!}
          {!! Form::text('bank_name', $booking->bank_name, ['class' => 'form-control', 'placeholder' => __( 'bank_name' )]); !!}
      </div>
       <div class="form-group">
        {!! Form::label('branch_code', 'Branch Code'  . ':') !!}
          {!! Form::text('branch_code', $booking->branch_code, ['class' => 'form-control', 'placeholder' =>  'branch_code' ]); !!}
      </div>
       <div class="form-group">
        {!! Form::label('cheque_due_date', 'Cheque due date'  . ':') !!}
          {!! Form::date('cheque_due_date', $booking->cheque_due_date, ['class' => 'form-control', 'placeholder' =>  'cheque_due_date' ]); !!}
      </div>
      <div class="form-group">
      {!! Form::label('Supplier', 'Cheque Status:') !!} 
						{!! Form::select('cheque_status', $cheque_status,  $booking->cheque_status, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), "required"]); !!}
      </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->