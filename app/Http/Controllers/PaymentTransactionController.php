<?php

namespace App\Http\Controllers;

use App\Services\PaymentTransactionService;
use DataTables;
use Illuminate\Http\Request;

class PaymentTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, PaymentTransactionService $paymentTransactionService)
    {
        if ($request->ajax()) {
            $data = $paymentTransactionService->getData($request);
            return $this->initDataTable($data);
        } else {
            return view('payment_transaction.index');
        }

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
        //
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
        //
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
    /**
     * initDataTable function
     *
     * @param [type] $data
     * @return object
     */
    public function initDataTable($data)
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('getPlan.name', function ($value) {
                return !empty($value->getPlan) ? '<span  data-toggle="tooltip" title="' . $value->getPlan->name . '">' . mb_strimwidth($value->getPlan->name, 0, 25, "...") . '</span>' : '';
            })
            ->editColumn('getCustomer.name', function ($value) {
                return !empty($value->getCustomer) ? '<span  data-toggle="tooltip"  title="' . $value->getCustomer->name . '">' . mb_strimwidth($value->getCustomer->name, 0, 25, "...") . '</span>' : '';

            })
            ->editColumn('amount', function ($value) {
                return '$' . $value->amount;
            })
            ->editColumn('transaction_status', function ($value) {
                return view('payment_transaction.getstatus', ['value' => $value]);
            })
            ->editColumn('transaction_date', function ($value) {
                return $value->transaction_date != null ? date('d M Y h:i a', strtotime(ConvertTimezone($value->transaction_date))) : ''  ;
            })
            ->escapeColumns([])
            ->make(true);
    }
}
