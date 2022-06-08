<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\expensebeneficiary;
use App\sub_category;
use Yajra\DataTables\Facades\DataTables;
// expensebeneficiary

class ExpenseBeneficiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          if (!(auth()->user()->can('expense.add') || auth()->user()->can('expense.edit'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

     $expense_beneficiary = expensebeneficiary::select('name', 'email', 'phone_number', 'id')->where('business_id', $business_id)->get();

            // $expense_category = ExpenseCategory::where('business_id', $business_id)
            //             ->select(['name', 'code', 'id']);
            return Datatables::of($expense_beneficiary)
                ->addColumn(
                    'action',
                    '<button data-href="{{action(\'ExpenseBeneficiaryController@edit\', [$id])}}" class="btn btn-xs btn-primary btn-modal" data-container=".expense_beneficiary_modal"><i class="glyphicon glyphicon-edit"></i>  @lang("messages.edit")</button>
                        &nbsp;
                        <button data-href="{{action(\'ExpenseBeneficiaryController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_expense_subcategory"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>'
                )
                ->removeColumn('id')
                ->rawColumns([3])
                ->make(false);

            //   $business_id = request()->session()->get('user.business_id');

            // $expense_category = ExpenseCategory::where('business_id', $business_id)
            //             ->select(['name', 'code', 'id']);

            // return Datatables::of($expense_category)
            //     ->addColumn(
            //         'action',
            //         '<button data-href="{{action(\'ExpenseCategoryController@edit\', [$id])}}" class="btn btn-xs btn-primary btn-modal" data-container=".expense_category_modal"><i class="glyphicon glyphicon-edit"></i>  @lang("messages.edit")</button>
            //             &nbsp;
            //             <button data-href="{{action(\'ExpenseCategoryController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_expense_category"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>'
            //     )
            //     ->removeColumn('id')
            //     ->rawColumns([2])
            //     ->make(false);
        }
    //    echo 'jfbuydf uurhfjui f iueui';
    //    die();
       return view('expensebeneficiary.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         if (!(auth()->user()->can('expense.add') || auth()->user()->can('expense.edit'))) {
            abort(403, 'Unauthorized action.');
        }
        //  $business_id = $request->session()->get('user.business_id');
         
        // $business_id = request()->session()->get('user.business_id');
        // $expense_category = ExpenseCategory::where('business_id', $business_id)
                            // ->pluck('name', 'id');

        // $expense_category = ExpenseCategory::get();

        return view('expensebeneficiary.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         if (!(auth()->user()->can('expense.add') || auth()->user()->can('expense.edit'))) {
            abort(403, 'Unauthorized action.');
        }

        try {
         $input = $request->only(['name', 'email', 'phone_number']);

        //   return $output = ['success' => true,
        //                     'msg' => __($input['name'])
        //                 ];
        // $input['category_id'] = $input['expense_category_id'];
            $input['business_id'] = $request->session()->get('user.business_id');

            expensebeneficiary::create($input);
            $output = ['success' => true,
                            'msg' => __("Expense beneficiary added")
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!(auth()->user()->can('expense.add') || auth()->user()->can('expense.edit'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $expensebeneficiary = expensebeneficiary::where('business_id', $business_id)->find($id);

            return view('expensebeneficiary.edit')
                    ->with(compact('expensebeneficiary'));

                    // return view('expense_category.edit')
                    // ->with(compact('expense_category'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         if (!(auth()->user()->can('expense.add') || auth()->user()->can('expense.edit'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
              $input = $request->only(['name', 'email', 'phone_number']);
                $business_id = $request->session()->get('user.business_id');

                $expense_category = expensebeneficiary::where('business_id', $business_id)->findOrFail($id);
                $expense_category->name = $input['name'];
                $expense_category->email = $input['email'];
                $expense_category->phone_number = $input['phone_number'];
                $expense_category->save();

                $output = ['success' => true,
                            'msg' => __("expence beneficiary updated")
                            ];
                            // "expence beneficiary updated"
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         if (!(auth()->user()->can('expense.add') || auth()->user()->can('expense.edit'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $expense_category = expensebeneficiary::where('business_id', $business_id)->findOrFail($id);
                $expense_category->delete();

                $output = ['success' => true,
                            'msg' => __("Expense beneficiary deleted")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }
}
