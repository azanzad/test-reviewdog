<?php

namespace App\Http\Controllers;

use App\Services\CompanyService;
use App\Services\RequestsService;
use DataTables;
use Illuminate\Http\Request;

class RequestsController extends Controller
{
    public function __construct(RequestsService $requestsService, CompanyService $companyService)
    {
        $this->requestsService = $requestsService;
        $this->companyService = $companyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->requestsService->getData($request);
            return $this->initDataTable($data);
        } else {
            $companies = $this->companyService->fetchAllCompanies();
            return view('requests.index', ['company' => $companies]);
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function initDataTable($data)
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('getCustomer.name', function ($value) {
                return $value->name ? $value->name : '';
            })
            ->editColumn('amazon_order_id', function ($value) {
                return $value->amazon_order_id;
            })
            ->editColumn('order_date', function ($value) {
                return $value->order_date != null ? date('d M Y h:i a', strtotime(($value->order_date))) : '';
            })
            ->editColumn('order_status', function ($value) {
                return $value->order_status;
            })
            ->addColumn('request_details', function ($value) {
                return view('requests.getstatus', ['value' => $value]);
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function selectOrderStatus()
    {
        return response()->json($this->requestsService->getOrderStatus());
    }
}
