<?php

namespace App\Http\Controllers;

use App\Account;
use App\transection_subcategory;
use App\transaction_maincategory;

use App\AccountTransaction;
use App\BusinessLocation;
use App\ExpenseCategory;
use App\TaxRate;
use App\Transaction;
use App\User;
use App\Utils\ModuleUtil;
use App\Utils\TransactionUtil;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Contact;
use App\sub_category;
use App\expensebeneficiary;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
    * Constructor
    *
    * @param TransactionUtil $transactionUtil
    * @return void
    */
    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '',
        'is_return' => 0, 'transaction_no' => ''];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('all_expense.access') && !auth()->user()->can('view_own_expense')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $expenses = Transaction::leftJoin('expense_categories AS ec', 'transactions.expense_category_id', '=', 'ec.id')
                        ->join(
                            'business_locations AS bl',
                            'transactions.location_id',
                            '=',
                            'bl.id'
                        )
                        ->leftJoin('tax_rates as tr', 'transactions.tax_id', '=', 'tr.id')
                        ->leftJoin('users AS U', 'transactions.expense_for', '=', 'U.id')
                        ->leftJoin('users AS usr', 'transactions.created_by', '=', 'usr.id')
                        ->leftJoin('contacts AS c', 'transactions.contact_id', '=', 'c.id')
                        ->leftJoin(
                            'transaction_payments AS TP',
                            'transactions.id',
                            '=',
                            'TP.transaction_id'
                        )
                        ->where('transactions.business_id', $business_id)
                        ->whereIn('transactions.type', ['expense', 'expense_refund'])
                        ->select(
                            'transactions.id',
                            'transactions.document',
                            'transaction_date',
                            'ref_no',
                            'expensebeneficiary',
                            'sub_category_id',
                            'ec.name as category',
                            'payment_status',
                            'additional_notes',
                            'final_total',
                            'transactions.is_recurring',
                            'transactions.recur_interval',
                            'transactions.recur_interval_type',
                            'transactions.recur_repetitions',
                            'transactions.subscription_repeat_on',
                            'bl.name as location_name',
                            DB::raw("CONCAT(COALESCE(U.surname, ''),' ',COALESCE(U.first_name, ''),' ',COALESCE(U.last_name,'')) as expense_for"),
                            DB::raw("CONCAT(tr.name ,' (', tr.amount ,' )') as tax"),
                            DB::raw('SUM(TP.amount) as amount_paid'),
                            DB::raw("CONCAT(COALESCE(usr.surname, ''),' ',COALESCE(usr.first_name, ''),' ',COALESCE(usr.last_name,'')) as added_by"),
                            'transactions.recur_parent_id',
                            'c.name as contact_name',
                            'transactions.type'
                        )
                        ->with(['recurring_parent'])
                        ->groupBy('transactions.id');

            //Add condition for expense for,used in sales representative expense report & list of expense
            if (request()->has('expense_for')) {
                $expense_for = request()->get('expense_for');
                if (!empty($expense_for)) {
                    $expenses->where('transactions.expense_for', $expense_for);
                   
                }          

            }

            if (request()->has('contact_id')) {
                $contact_id = request()->get('contact_id');
                if (!empty($contact_id)) {
                    $expenses->where('transactions.contact_id', $contact_id);
                }
            }

            //Add condition for location,used in sales representative expense report & list of expense
            if (request()->has('location_id')) {
                $location_id = request()->get('location_id');
                if (!empty($location_id)) {
                    $expenses->where('transactions.location_id', $location_id);
                }
            }

            //Add condition for expense category, used in list of expense,
            if (request()->has('expense_category_id')) {
                $expense_category_id = request()->get('expense_category_id');
                if (!empty($expense_category_id)) {
                    $expenses->where('transactions.expense_category_id', $expense_category_id);
                }
            }
            //  if (request()->has('sub_category_id')) {
            //     $sub_category_id = request()->get('sub_category_id');
            //     if (!empty($sub_category_id)) {
            //         $expenses->where('transactions.sub_category_id', $sub_category_id);
            //     }
            // }
            if (request()->has('expensebeneficiary_id')) {
                $expensebeneficiary_id = request()->get('expensebeneficiary_id');
                if (!empty($expensebeneficiary_id)) {
                    $expenses->where('transactions.expensebeneficiary', $expensebeneficiary_id);
                }
            }

            
            

            //Add condition for start and end date filter, uses in sales representative expense report & list of expense
            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $expenses->whereDate('transaction_date', '>=', $start)
                        ->whereDate('transaction_date', '<=', $end);
            }

            //Add condition for expense category, used in list of expense,
            if (request()->has('expense_category_id')) {
                $expense_category_id = request()->get('expense_category_id');
                if (!empty($expense_category_id)) {
                    $expenses->where('transactions.expense_category_id', $expense_category_id);
                }
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $expenses->whereIn('transactions.location_id', $permitted_locations);
            }

            //Add condition for payment status for the list of expense
            if (request()->has('payment_status')) {
                $payment_status = request()->get('payment_status');
                if (!empty($payment_status)) {
                    $expenses->where('transactions.payment_status', $payment_status);
                }
            }

            $is_admin = $this->moduleUtil->is_admin(auth()->user(), $business_id);
            if (!$is_admin && !auth()->user()->can('all_expense.access')) {
                $user_id = auth()->user()->id;
                $expenses->where(function ($query) use ($user_id) {
                        $query->where('transactions.created_by', $user_id)
                        ->orWhere('transactions.expense_for', $user_id);
                    });
            }
            
            return Datatables::of($expenses)
                ->addColumn(
                    'action',
                    '<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                            data-toggle="dropdown" aria-expanded="false"> @lang("messages.actions")<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                </span>
                        </button>
                    <ul class="dropdown-menu dropdown-menu-left" role="menu">
                    @if(auth()->user()->can("expense.edit"))
                        <li><a href="{{action(\'ExpenseController@edit\', [$id])}}"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a></li>
                    @endif
                    @if($document)
                        <li><a href="{{ url(\'uploads/documents/\' . $document)}}" 
                        download=""><i class="fa fa-download" aria-hidden="true"></i> @lang("purchase.download_document")</a></li>
                        @if(isFileImage($document))
                            <li><a href="#" data-href="{{ url(\'uploads/documents/\' . $document)}}" class="view_uploaded_document"><i class="fas fa-file-image" aria-hidden="true"></i>@lang("lang_v1.view_document")</a></li>
                        @endif
                    @endif
                    @if(auth()->user()->can("expense.delete"))
                        <li>
                        <a href="#" data-href="{{action(\'ExpenseController@destroy\', [$id])}}" class="delete_expense"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</a></li>
                    @endif
                    <li class="divider"></li> 
                    @if($payment_status != "paid")
                        <li><a href="{{action("TransactionPaymentController@addPayment", [$id])}}" class="add_payment_modal"><i class="fas fa-money-bill-alt" aria-hidden="true"></i> @lang("purchase.add_payment")</a></li>
                    @endif
                    <li><a href="{{action("TransactionPaymentController@expensePayment", [$id])}}" class="view_payment_modal"><i class="fas fa-money-bill-alt" aria-hidden="true" ></i> @lang("purchase.view_payments")</a></li>
                    </ul></div>'
                )
                ->removeColumn('id')
                ->editColumn(
                    'final_total',
                    '<span class="display_currency final-total" data-currency_symbol="true" data-orig-value="@if($type=="expense_refund"){{-1 * $final_total}}@else{{$final_total}}@endif">@if($type=="expense_refund") - @endif{{$final_total}}</span>'
                )
                ->editColumn('transaction_date', '{{@format_datetime($transaction_date)}}')
                ->editColumn(
                    'payment_status',
                    '<a href="{{ action("TransactionPaymentController@show", [$id])}}" class="view_payment_modal payment-status no-print" data-orig-value="{{$payment_status}}" data-status-name="{{__(\'lang_v1.\' . $payment_status)}}"><span class="label @payment_status($payment_status)">{{__(\'lang_v1.\' . $payment_status)}}
                        </span></a><span class="print_section">{{__(\'lang_v1.\' . $payment_status)}}</span>'
                )
                ->addColumn('payment_due', function ($row) {
                    $due = $row->final_total - $row->amount_paid;

                    if ($row->type == 'expense_refund') {
                        $due = -1 * $due;
                    }
                    return '<span class="display_currency payment_due" data-currency_symbol="true" data-orig-value="' . $due . '">' . $due . '</span>';
                })
                ->addColumn('recur_details', function($row){
                    $details = '<small>';
                    if ($row->is_recurring == 1) {
                        $type = $row->recur_interval == 1 ? Str::singular(__('lang_v1.' . $row->recur_interval_type)) : __('lang_v1.' . $row->recur_interval_type);
                        $recur_interval = $row->recur_interval . $type;
                        
                        $details .= __('lang_v1.recur_interval') . ': ' . $recur_interval; 
                        if (!empty($row->recur_repetitions)) {
                            $details .= ', ' .__('lang_v1.no_of_repetitions') . ': ' . $row->recur_repetitions; 
                        }
                        if ($row->recur_interval_type == 'months' && !empty($row->subscription_repeat_on)) {
                            $details .= '<br><small class="text-muted">' . 
                            __('lang_v1.repeat_on') . ': ' . str_ordinal($row->subscription_repeat_on) ;
                        }
                    } elseif (!empty($row->recur_parent_id)) {
                        $details .= __('lang_v1.recurred_from') . ': ' . $row->recurring_parent->ref_no;
                    }
                    $details .= '</small>';
                    return $details;
                })
                // ->editColumn('sub_category_id', function($row){  
                //     //  return $row->sub_category_id;  
                //     $sub_category_id = null;                 
                //      $data = sub_category::where('id', $row->sub_category_id)->first();
                //       if(isset($data ))
                //      {
                //     $sub_category_id = $data->name;
                //      }
                    // if (!empty($row->is_recurring)) {
                    //     $sub_category_id .= ' &nbsp;<small class="label bg-red label-round no-print" title="' . __('lang_v1.recurring_expense') .'"><i class="fas fa-recycle"></i></small>';
                    // }

                    // if (!empty($row->recur_parent_id)) {
                    //     $sub_category_id .= ' &nbsp;<small class="label bg-info label-round no-print" title="' . __('lang_v1.generated_recurring_expense') .'"><i class="fas fa-recycle"></i></small>';
                    // }

                    // if ($row->type == 'expense_refund') {
                    //     $sub_category_id .= ' &nbsp;<small class="label bg-gray">' . __('lang_v1.refund') . '</small>';
                    // }

                //     return $sub_category_id;
                // })
                 ->editColumn('expensebeneficiary', function($row){
            //   ->addColumn('expense_beneficiary', function($row){
                    //  return 'jdnfjisndi';
                    // return  $expensebeneficiary = $row->expensebeneficiary;
                    $beneficiary = expensebeneficiary::where('id', $row->expensebeneficiary)->first();
                    
                      if(isset($beneficiary))
                     {
                         return $beneficiary->name;
                     }else {
                         return null;
                     }
                       })
                ->rawColumns(['final_total', 'action', 'payment_status', 'payment_due', 'ref_no', 'expensebeneficiary', 'recur_details'])
                ->make(true);
        }
        // , 'sub_category_id'

        $business_id = request()->session()->get('user.business_id');

        $categories = ExpenseCategory::where('business_id', $business_id)
                            ->pluck('name', 'id');
     $sub_category = sub_category::select('name','id', 'category_id')->where('business_id', $business_id)
    ->pluck('name','id', 'category_id');
    $expensebeneficiary = expensebeneficiary::select('name','id')->where('business_id', $business_id)
    ->pluck('name','id');
                           

        $users = User::forDropdown($business_id, false, true, true);

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        $contacts = Contact::contactDropdown($business_id, false, false);

        return view('expense.index')
            ->with(compact('categories', 'business_locations', 'users', 'contacts', 'sub_category', 'expensebeneficiary' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('expense.add')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        
        //Check if subscribed or not
        if (!$this->moduleUtil->isSubscribed($business_id)) {
            return $this->moduleUtil->expiredResponse(action('ExpenseController@index'));
        }

        $business_locations = BusinessLocation::forDropdown($business_id, false, true);

        $bl_attributes = $business_locations['attributes'];
        $business_locations = $business_locations['locations'];

        $expense_categories = ExpenseCategory::where('business_id', $business_id)
                                ->pluck('name', 'id');
        $expense_test = ExpenseCategory::where('business_id', $business_id)->select('id')->first();           

   $sub_category = sub_category::select('name','id', 'category_id')->where('business_id', $business_id)->where('category_id', $expense_test->id)
    ->pluck('name','id', 'category_id');
    $expensebeneficiary = expensebeneficiary::select('name','id')->where('business_id', $business_id)
    ->pluck('name','id');
                            // ->get();
                            // pluck('name', 'id') 'name', 'id', 'category_id'
        $users = User::forDropdown($business_id, true, true);

        $taxes = TaxRate::forBusinessDropdown($business_id, true, true);
        
        $payment_line = $this->dummyPaymentLine;

        $payment_types = $this->transactionUtil->payment_types(null, false, $business_id);

         $contacts = Contact::contactcreateDropdown($business_id, false, false);
        //  $contacts = Contact::pluck('name', 'id', 'contact_id', 'type');
// die();
        //Accounts
        $accounts = [];
        if ($this->moduleUtil->isModuleEnabled('account')) {
            $accounts = Account::forDropdown($business_id, true, false, true);
        }

        return view('expense.create')
            ->with(compact('expense_categories', 'business_locations', 'users', 'taxes', 'payment_line', 'payment_types', 'accounts', 'bl_attributes', 'contacts', 'sub_category', 'expensebeneficiary'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('expense.add')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');

            //Check if subscribed or not
            if (!$this->moduleUtil->isSubscribed($business_id)) {
                return $this->moduleUtil->expiredResponse(action('ExpenseController@index'));
            }

            //Validate document size
            
            $request->validate([
                'document' => 'file|max:'. (config('constants.document_size_limit') / 1000)
            ]);
            $user_id = $request->session()->get('user.id');
            DB::beginTransaction();
            $expense = $this->transactionUtil->createExpense($request, $business_id, $user_id);
            $this->transactionUtil->activityLog($expense, 'added');
            DB::commit();
            $total_categories = count($request->expense_category_id);
            $inputt['transaction_id'] = $expense->id;
            // return $input['subcategory_id'] = $request->input('sub_category2');
            for($i = 0; $i<$total_categories; $i++)
            {
             $inputt['category_id'] = $request->expense_category_id[$i];
            //  $transaction_maincategory = transaction_maincategory::create($inputt);
             $total_sub_category = count($request->input('amount'.$i));
            for($j = 0; $j<$total_sub_category; $j++)
            {
                $input['subcategory_id'] = $request->input('sub_category'.$i)[$j];
                $input['quantity'] = $request->input('quantity'.$i)[$j];
                $input['amount'] = $request->input('amount'.$i)[$j];
                $input['main_category_id'] = $transaction_maincategory->id;
                $input['contact_id'] = $request->input('contact_id'.$i)[$j];
                $transection_subcategory[] = transection_subcategory::create($input);
            }
            }
            $output = ['success' => 1,
                            'msg' => __('expense.expense_add_success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __('messages.something_went_wrong')
                        ];
        }
        // return $output;

        return redirect('expenses')->with('status', $output);
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
    public function edit($id)
    {
        if (!auth()->user()->can('expense.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        //Check if subscribed or not
        if (!$this->moduleUtil->isSubscribed($business_id)) {
            return $this->moduleUtil->expiredResponse(action('ExpenseController@index'));
        }

        $business_locations = BusinessLocation::forDropdown($business_id);

        $expense_categories = ExpenseCategory::where('business_id', $business_id)
                                ->pluck('name', 'id');
       $expense = Transaction::where('business_id', $business_id)
                                ->where('id', $id)
                                ->first();

        // echo  $teansection_id =  ;
    

    // echo $expense->id;
    $transaction_maincategory = transaction_maincategory::where('transaction_id', $expense->id)->get();
    // var_dump($transaction_maincategory);

     foreach($transaction_maincategory as $transaction_maincategor)
     {
        //   var_dump($transaction_maincategor);
    //    echo  $transaction_maincategor->id;
      
       $transection_subcategorytest['category'] = $transaction_maincategor;
    //  $transection_subcategorytest['category']->id;
       
        $transection_subcategorytest['subcategory'] = transection_subcategory::where('main_category_id', $transaction_maincategor->id)->first();
        $transection_subcategorytest['contact'] = Contact::where('id', $transection_subcategorytest['subcategory']->contact_id)->first();
    //   echo $transection_subcategorytest['contact']->id;
  
      $transection_subcategory[] = $transection_subcategorytest;

    //    break;
     }
// return $transection_subcategory;
    //  return json_encode($transection_subcategory);

    $sub_total = 0;
    // return $transection_subcategory;
    // die();

    foreach($transection_subcategory as $transection_subcategor)
    {
        $transection_subcategor['subcategory'];
    //     break;
        $total = $transection_subcategor['subcategory']->quantity * $transection_subcategor['subcategory']->amount;
        $sub_total = $sub_total + $total;
    }
    // echo $sub_total;
    //  die();
        $users = User::forDropdown($business_id, true, true);

        $taxes = TaxRate::forBusinessDropdown($business_id, true, true);

        $contacts = Contact::contactcreateDropdown($business_id, false, false);
        $sub_category = sub_category::select('name','id', 'category_id')->where('business_id', $business_id)
    ->pluck('name','id', 'category_id');
    $expensebeneficiary = expensebeneficiary::select('name','id')->where('business_id', $business_id)
    ->pluck('name','id');
    $expensebeneficiary_edit = expensebeneficiary::select('name','id')->where('business_id', $business_id)
    ->first();


    //  $sub_category = sub_category::where('business_id', $business_id)->where('id', $expense->ref_no)->first();
    // // ->pluck('name','id', 'category_id');
    // $expensebeneficiary = expensebeneficiary::where('business_id', $business_id)->where('id', $expense->expensebeneficiary)->first();
        // return view('expense.create')
        //     ->with(compact('expense_categories', 'business_locations', 'users', 'taxes', 'payment_line', 'payment_types', 'accounts', 'bl_attributes', 'contacts'));

        return view('expense.edit')
            ->with(compact('expense', 'expense_categories', 'transaction_maincategory', 'transection_subcategory', 'sub_total', 'business_locations', 'users', 'taxes', 'contacts', 'sub_category', 'expensebeneficiary', 'expensebeneficiary_edit'));
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
        if (!auth()->user()->can('expense.edit')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            //Validate document size
            $request->validate([
                'document' => 'file|max:'. (config('constants.document_size_limit') / 1000)
            ]);
            
            $business_id = $request->session()->get('user.business_id');
        //   return  $final_total = $request->final_total;
        // return  $request->transection_subcategory_id;
        // return  $request->maincategory_id;
        // return  $request->expense_category_id;
        // return  $request->sub_category0;
        // return  $request->quantity0;
        //   return  $request->amount0;
        //  return  $request->contact_id0;
            $trn_maincategory_id = $request->maincategory_id;
            $trn_expensecategory_id = $request->expense_category_id;
            $trn_subcategory_id = $request->transection_subcategory_id;
            $sub_categorys = $request->sub_category0;
            $quantities = $request->quantity0;
            $amount = $request->amount0;
            $contact = $request->contact_id0;
            $arrLength = count($trn_maincategory_id);
            
            for($i = 0; $i<$arrLength; $i++)
            {
                // if($subcategory_id[$i] == 0)
                // {
                // $input['transaction_id'] = $id;
                // $input['subcategory_id'] = $sub_categorys[$i];
                // $input['quantity'] = $quantities[$i];
                // $input['amount'] = $amount[$i];
                // // $data[] = $info;
                // $transection_subcatego = transection_subcategory::create($input);
                // unset($input);
                // }else{
                    $transaction_maincategory = transaction_maincategory::find($trn_maincategory_id[$i]);
                    $transaction_maincategory->category_id = $trn_expensecategory_id[$i];
                    $transaction_maincategory->transaction_id = $id;
                    $transaction_maincategory->save();
                    $transection_subcategory = transection_subcategory::find($trn_subcategory_id[$i]);
                    $transection_subcategory->subcategory_id = $sub_categorys[$i];
                    $transection_subcategory->quantity = $quantities[$i];
                    $transection_subcategory->amount = $amount[$i];
                    $transection_subcategory->main_category_id = $trn_maincategory_id[$i];
                    $transection_subcategory->contact_id = $contact[$i];
                    $transection_subcategory->save();
                // }
            }
            // die();
            //Check if subscribed or not
            if (!$this->moduleUtil->isSubscribed($business_id)) {
                return $this->moduleUtil->expiredResponse(action('ExpenseController@index'));
            }
// return $request->expense_reason;
         $expense = $this->transactionUtil->updateExpense($request, $id, $business_id);

            $this->transactionUtil->activityLog($expense, 'edited');

            $output = ['success' => 1,
                            'msg' => __('expense.expense_update_success')
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __('messages.something_went_wrong')
                        ];
        }

        return redirect('expenses')->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('expense.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $expense = Transaction::where('business_id', $business_id)
                                        ->where(function($q) {
                                            $q->where('type', 'expense')
                                                ->orWhere('type', 'expense_refund');
                                        })
                                        ->where('id', $id)
                                        ->first();
                $expense->delete();

                //Delete account transactions
                AccountTransaction::where('transaction_id', $expense->id)->delete();

                $output = ['success' => true,
                            'msg' => __("expense.expense_delete_success")
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
    public function get_subCategory(Request $request)
    {
       $id = $request->category_id;
       $business_id = request()->session()->get('user.business_id');
     $sub_category = sub_category::select('name','id', 'category_id')->where('business_id', $business_id)->where('category_id', $id)
    ->pluck('name','id', 'category_id');
    return json_encode($sub_category);

    }
}
