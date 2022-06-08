@extends('layouts.app')
@section('title', __('expense subcategories'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Expense SubCategories' )
        <small>@lang( 'Manage your expense subcategories' )</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All your expense subcategories' )])
        @slot('tool')
            <div class="box-tools">
                <button type="button" class="btn btn-block btn-primary btn-modal" 
                data-href="{{action('SubCategoryController@create')}}" 
                data-container=".subexpense_category_modal">
                <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
            </div>
        @endslot
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="expense_subcategory_table">
                <thead>
                    <tr>
                        <th>@lang( 'ID' )</th>
                        <th>@lang( 'expense.category_name' )</th>                        
                        <th>@lang( 'messages.action' )</th>
                    </tr>
                </thead>
            </table>
        </div>
    @endcomponent

    <div class="modal fade subexpense_category_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection