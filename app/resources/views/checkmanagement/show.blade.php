<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">@lang( 'Cheque details' )</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
                        <strong>@lang('Cheque Number'):</strong> {{ $booking->cheque_number }}<br>
						<strong>@lang('Bank Name'):</strong> {{ $booking->bank_name }}<br>
                        <strong>@lang('Branch Code'):</strong> {{ $booking->branch_code ?? '--' }}<br>
						
						@if(!empty($booking->booking_note))
						<strong>@lang('restaurant.customer_note'):</strong> {{ $booking->method }}
						@endif
					</div>
					<div class="col-sm-6">
						<strong>@lang('Amount'):</strong> {{ $booking->amount }}<br>
						<strong>@lang('Cheque Due Date'):</strong> {{ $booking->cheque_due_date ?? '--' }}<br>
					
					<strong>@lang('Cheque Status'):</strong><span> @if($booking->cheque_status == 0)<label class="label label-default">Pending</label>@elseif($booking->cheque_status == 1)<label class="label label-success">Paid</label>@elseif($booking->cheque_status == "Rejected")<label class="label label-danger"> {{ $booking->cheque_status }}</label> @elseif($booking->cheque_status == "Sent to Revoke")<label class="label label-warning"> {{ $booking->cheque_status }}</label> @endif</span>
					</div>
				</div>
                <br>
                <div class="row">
					<!-- {!! Form::open(['url' => action('CheckManagementController@update', [$booking->id]), 'method' => 'PUT', 'id' => 'edit_cheque_add_form' ]) !!} -->
					{!! Form::open(['url' => action('CheckManagementController@update', [$booking->id]), 'method' => 'PUT', 'id' => 'cheque_status_update' ]) !!}
					<div class="col-sm-6">						 
					<div class="form-group">
						{!! Form::label('Supplier', 'Cheque Status:') !!} 
						{!! Form::select('cheque_status', $cheque_status,  null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), "required"]); !!}
					</div>
					
					</div>
					
					<div class="col-sm-3 text-center">
						<label>     </label>
						<div class="form-group mt-5">
							 <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
                       <!-- id="edit_chequedetails" <button type="button" class="btn btn-danger" id="delete_booking" >@lang('restaurant.delete_booking')</button> -->
					</div>
					</div>
					{!! Form::close() !!}
				</div>
                 
				<br>
			<div class="modal-footer">
				 <button class="btn  btn-primary btn-modall" data-container=".edit_cheque_modal"  data-href="{{action('CheckManagementController@edit', [$booking->id])}}"><i class="glyphicon glyphicon-edit"></i> &#160;@lang('Edit')</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
			</div>
		

	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->