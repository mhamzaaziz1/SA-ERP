<?php

namespace App\Http\Controllers;

use App\Utils\Util;
use App\Utils\ChequeManagementUtil;
use App\BusinessLocation;
use App\Contact;
use App\User;
use App\CustomerGroup;
use App\TransactionPayment;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class CheckManagementController extends Controller
{
      /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $chequeUtil;

    public function __construct(Util $commonUtil, ChequeManagementUtil $chequeUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->chequeUtil = $chequeUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('crud_all_bookings') && !auth()->user()->can('crud_own_bookings')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');

        $user_id = request()->has('user_id') ? request()->user_id : null;
        if (!auth()->user()->hasPermissionTo('crud_all_bookings') && !$this->chequeUtil->is_admin(auth()->user(), $business_id)) {
            $user_id = request()->session()->get('user.id');
        }
        if (request()->ajax()) {
            $filters = [
                'start_date' => request()->start,
                'end_date' => request()->end,
                'user_id' => $user_id,
                'location_id' => !empty(request()->location_id) ? request()->location_id : null,
                'business_id' => $business_id
            ];

            $events = $this->chequeUtil->getBookingsForCalendar($filters);

            return $events;
        }

        $business_locations = BusinessLocation::forDropdown($business_id);

        $customers =  Contact::customersDropdown($business_id, false);

        $correspondents = User::forDropdown($business_id, false);

        $types = Contact::getContactTypes();
        $customer_groups = CustomerGroup::forDropdown($business_id);

        return view('checkmanagement.index', compact('business_locations', 'customers', 'correspondents', 'types', 'customer_groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $booking = TransactionPayment::where('business_id', $business_id)
            ->where('id', $id)
            ->first();
            // $booking = Booking::where('business_id', $business_id)
            //                     ->where('id', $id)
            //                     ->with(['table', 'customer', 'correspondent', 'waiter', 'location'])
            //                     ->first();
            if (!empty($booking)) {
                // $booking_start = $this->commonUtil->format_date($booking->booking_start, true);
                // $booking_end = $this->commonUtil->format_date($booking->booking_end, true);

                $booking_statuses = [
                    'waiting' => __('lang_v1.waiting'),
                    'booked' => __('restaurant.booked'),
                    'completed' => __('restaurant.completed'),
                    'cancelled' => __('restaurant.cancelled'),
                ];
                $cheque_status = ['Pending', 'Paid'];
                return view('checkmanagement.show', compact('booking', 'cheque_status', 'booking_statuses'));
            }
        }
        // else {
        //     {
        //     $business_id = request()->session()->get('user.business_id');
        //     $booking = TransactionPayment::where('business_id', $business_id)
        //     ->where('id', $id)
        //     ->first();
        //     // $booking = Booking::where('business_id', $business_id)
        //     //                     ->where('id', $id)
        //     //                     ->with(['table', 'customer', 'correspondent', 'waiter', 'location'])
        //     //                     ->first();
        //     if (!empty($booking)) {
        //         // $booking_start = $this->commonUtil->format_date($booking->booking_start, true);
        //         // $booking_end = $this->commonUtil->format_date($booking->booking_end, true);

        //         $booking_statuses = [
        //             'waiting' => __('lang_v1.waiting'),
        //             'booked' => __('restaurant.booked'),
        //             'completed' => __('restaurant.completed'),
        //             'cancelled' => __('restaurant.cancelled'),
        //         ];
        //         $cheque_status = ['Pending', 'Paid'];
        //         return view('checkmanagement.show', compact('booking', 'cheque_status', 'booking_statuses'));
        //     }
        //     }
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // return $id;
        if (!auth()->user()->can('crud_all_bookings') && !auth()->user()->can('crud_own_bookings')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

             $booking = TransactionPayment::where('business_id', $business_id)
            ->where('id', $id)
            ->first();
            // $expense_category = ExpenseCategory::where('business_id', $business_id)->find($id);
            $cheque_status = ['Pending', 'Paid'];

            return view('checkmanagement.edit')
                    ->with(compact('booking', 'cheque_status'));
        }
        // else{
        //      $business_id = request()->session()->get('user.business_id');

        //      $booking = TransactionPayment::where('business_id', $business_id)
        //     ->where('id', $id)
        //     ->first();
        //     // $expense_category = ExpenseCategory::where('business_id', $business_id)->find($id);
        //     $cheque_status = ['Pending', 'Paid'];

        //     return view('checkmanagement.edit')
        //             ->with(compact('booking', 'cheque_status'));
        // }
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
        if (!auth()->user()->can('crud_all_bookings') && !auth()->user()->can('crud_own_bookings')) {
            abort(403, 'Unauthorized action.');
        }
        // return $id;
        // $input = $request->only(['cheque_number', 'cheque_status', 'bank_name', 'branch_code', 'cheque_due_date']);
        // return $input;

        if (request()->ajax()) {
             
            try {
                $input = $request->only(['cheque_number', 'bank_name', 'cheque_status', 'branch_code', 'cheque_due_date']);
                $business_id = $request->session()->get('user.business_id');
                   
                $transactionPayment = TransactionPayment::where('business_id', $business_id)->findOrFail($id);
                if(isset($input['cheque_number']))
                {
                $transactionPayment->cheque_number = $input['cheque_number'];
                }
                 if(isset($input['bank_name']))
                {
                $transactionPayment->bank_name = $input['bank_name'];
                }
                 if(isset($input['branch_code']))
                {
                $transactionPayment->branch_code = $input['branch_code'];
                }
                 if(isset($input['cheque_due_date']))
                {
                $transactionPayment->cheque_due_date = $input['cheque_due_date'];
                }
                 if(isset($input['cheque_status']))
                {
                $transactionPayment->cheque_status = $input['cheque_status'];
                }
                $transactionPayment->save();

                $output = ['success' => true,
                            'msg' => __("Cheque details updated!")
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getTodaysCheque()
    {
                if (!auth()->user()->can('crud_all_bookings') && !auth()->user()->can('crud_own_bookings')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $user_id = request()->session()->get('user.id');
            $today = \Carbon::now()->format('Y-m-d');
            // $query = Booking::where('business_id', $business_id)
            //             ->where('booking_status', 'booked')
            //             ->whereDate('booking_start', $today)
            //             ->with(['table', 'customer', 'correspondent', 'waiter', 'location']);
                        
            // if (!empty(request()->location_id)) {
            //     $query->where('location_id', request()->location_id);
            // }

            // if (!auth()->user()->hasPermissionTo('crud_all_bookings') && !$this->commonUtil->is_admin(auth()->user(), $business_id)) {


            //     $query->where(function ($query) use ($user_id){
            //         $query->where('created_by', $user_id)
            //             ->orWhere('correspondent_id', $user_id)
            //             ->orWhere('waiter_id', $user_id);
            //     });

             $query = TransactionPayment::where('business_id', $business_id)
                        ->where('cheque_due_date', $today);
        // $bookings = $query->get();
                //$query->where('created_by', $user_id);
            // }
                        //  $id = 0;
            return Datatables::of($query)
                // ->editColumn('table', function ($row) {
                //     return !empty($row->table->name) ? $row->table->name : '--';
                // })
                // ->editColumn('#', function ($row) {
                //     return !empty(++$id) ? $id : '--';
                // })
                ->editColumn('cheque_number', function ($row) {
                    return !empty($row->cheque_number) ? $row->cheque_number : '--';
                })
                ->editColumn('bank_name', function ($row) {
                    return !empty($row->bank_name) ? $row->bank_name : '--';
                })
                ->editColumn('branch_code', function ($row) {
                    return !empty($row->branch_code) ? $row->branch_code : '--';
                })
                ->editColumn('cheque_due_date', function ($row) {
                    return !empty($row->cheque_due_date) ? $row->cheque_due_date : '--';
                })
               ->removeColumn('id')
                ->make(true);
        }
    }
}
