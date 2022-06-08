@extends('layouts.app')
@section('title', __('Expense Beneficiary'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Expense Beneficiary' )
        <small>@lang( 'Manage your expense Beneficiary' )</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All your expense Beneficiary' )])
        @slot('tool')
            <div class="box-tools">
                <button type="button" class="btn btn-block btn-primary btn-modal" 
                data-href="{{action('ExpenseBeneficiaryController@create')}}" 
                data-container=".expense_beneficiary_modal">
                <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
            </div>
        @endslot
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="expense_beneficiary_table">
                <thead>
                    <tr>
                        <th>@lang( 'Name' )</th>
                        <th>@lang( 'Email' )</th>
                        <th>@lang( 'Phone Number' )</th>
                        <th>@lang( 'messages.action' )</th>
                    </tr>
                </thead>
            </table>
        </div>
    @endcomponent

    <div class="modal fade expense_beneficiary_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection