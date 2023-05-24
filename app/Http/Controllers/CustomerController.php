<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\StoreConfig;
use App\Models\UserEmailAutomation;
use App\Services\CompanyService;
use App\Services\CustomerService;
use App\Services\SubscriptionPlanService;
use DataTables;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct(CustomerService $customerService, CompanyService $companyService)
    {
        $this->middleware('auth');
        //$this->middleware('admin')->only(['store', 'create', 'edit']);
        $this->customerService = $customerService;
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
            $data = $this->customerService->getData($request);
            return $this->initDataTable($data);
        } else {
            $create_route = route('customer.create');
            if (auth()->user()->role == config('params.company_role')) {
                $create_route = route('customer.create') . '?id=' . base64_encode(auth()->user()->id);

            }

            return view('customers.index', ['create_route' => $create_route]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $companyid = !empty($_GET['id']) ? base64_decode($_GET['id']) : '';
        $plans = (new SubscriptionPlanService())->getData($request)->where('status', config('params.active'))->orderBy('id', 'desc')->get();
        $companies = (new CompanyService())->getData($request)->whereNull('customer_type')->where('status', config('params.active'))->get();

        $selectedCountryCodes = array('us');

        return view('customers.create', ['plans' => $plans, 'companies' => $companies, 'companyid' => $companyid, 'selectedcountrycodes' => $selectedCountryCodes]);

    }
    /**
     * appendContact function
     *
     * @return Response
     */
    public function appendContact()
    {
        return view('customers.newcontact');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        try {
            $this->companyService->storeCompany($request);
            return $this->success_response(200, trans('message.message.added', ['module' => trans('message.label.customer')]));
        } catch (\Illuminate\Database\QueryException$ex) {
            $msg = $ex->getMessage();
            if (isset($ex->errorInfo[2])):
                $msg = $ex->errorInfo[2];
            endif;
            return $this->error_response(400, $msg);
        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }

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
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid, Request $request)
    {
        $plans = (new SubscriptionPlanService())->getData($request)->where('status', config('params.active'))->get();
        $companies = $this->companyService->getData($request)->whereNull('customer_type')->where('status', config('params.active'))->orderBy('id', 'desc')->get();
        $data = $this->companyService->getCompany($uuid);
        $selectedCountryCodes = array_column($data->contacts->toArray(), 'country_code');
        $email_period_ids = UserEmailAutomation::where('user_id', $data->id)->pluck('email_periods')->toArray();

        return view('customers.edit', ['plans' => $plans, 'companies' => $companies, 'data' => $data, 'selectedcountrycodes' => $selectedCountryCodes, 'email_period_ids' => $email_period_ids]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerUpdateRequest $request, $uuid)
    {
        try {
            $this->companyService->updateCompany($request, $uuid);
            return $this->success_response(200, trans('message.message.updated', ['module' => trans('message.label.customer')]));

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $this->companyService->deleteCompany($uuid);
        return $this->success_response(200, trans('message.message.deleted', ['module' => trans('message.label.customer')]));

    }
    /**
     * changeStatus function
     *
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        $this->companyService->updateStatus($request);
        return $this->success_response(200, trans('message.message.status_changed'));
    }
    /**
     * exportCustomer function
     *
     * @param Request $request
     * @return Response
     */

    public function exportCustomers(Request $request)
    {
        /*used for get all company role users that has been under to main company admin*/
        try {

            $data = $this->customerService->getData($request)->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'user_id');
            $sheet->getColumnDimension('A')->setAutoSize(true);

            $sheet->setCellValue('B1', 'customername');
            $sheet->getColumnDimension('B')->setAutoSize(true);

            $sheet->setCellValue('C1', 'store_name');
            $sheet->getColumnDimension('C')->setAutoSize(true);

            $sheet->setCellValue('D1', 'seller_id');
            $sheet->getColumnDimension('D')->setAutoSize(true);

            $sheet->setCellValue('E1', 'amazon_marketplace');
            $sheet->getColumnDimension('E')->setAutoSize(true);

            $sheet->setCellValue('F1', 'refresh_token');
            $sheet->getColumnDimension('F')->setAutoSize(true);

            $sheet->setCellValue('G1', 'access_token');
            $sheet->getColumnDimension('G')->setAutoSize(true);

            $sheet->setCellValue('H1', 'aws_access_key_id');
            $sheet->getColumnDimension('H')->setAutoSize(true);

            $sheet->setCellValue('I1', 'aws_secret_key');
            $sheet->getColumnDimension('I')->setAutoSize(true);

            $sheet->freezePaneByColumnAndRow(3, 2);

            $store_types = StoreConfig::where(['store_marketplace' => 'Amazon'])->pluck('store_type');

            $amazon_marketplace = explode(",", $store_types);
            $store_types = str_replace("[", "", $amazon_marketplace);
            $store_types = str_replace("]", "", $store_types);
            $store_types = str_replace('"', "", $store_types);

            if (!empty($data)) {
                $i = 2;
                foreach ($data as $d) {

                    //Choose Storage Area

                    $objValidation2 = $sheet->getCell("E$i")->getDataValidation();
                    $objValidation2->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation2->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation2->setAllowBlank(false);
                    $objValidation2->setShowInputMessage(true);
                    $objValidation2->setShowDropDown(true);
                    $objValidation2->setPromptTitle('Pick Amazon Marketplace');
                    $objValidation2->setPrompt('Please pick a value from the drop-down list.');
                    $objValidation2->setErrorTitle('Amazon Marketplace error');
                    $objValidation2->setError('Value is not in list');
                    $objValidation2->setFormula1('"' . implode(',', $store_types) . '"');
                    unset($objValidation2);

                    $sheet->setCellValue('A' . $i, $d->id);
                    $sheet->setCellValue('B' . $i, $d->name);
                    $sheet->setCellValue('C' . $i, '');
                    $sheet->setCellValue('D' . $i, '');
                    $sheet->setCellValue('F' . $i, '');
                    $sheet->setCellValue('G' . $i, '');
                    $sheet->setCellValue('H' . $i, '');
                    $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
                    $spreadsheet->getDefaultStyle()->getProtection()->setLocked(false);
                    $sheet->getStyle('A' . $i)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
                    $sheet->getStyle('B' . $i)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);

                    $i++;
                }
            }

            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="customers.xlsx"');
            $writer->save("php://output");
        } catch (\Exception$e) {

            return $this->error_response(400, $e->getMessage());
        }

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
            ->editColumn('check', function ($value) {
                return '<input  class="form-check-input dt-checkboxes user_checkbox" type="checkbox" id="checkbox' . $value->id . '" name="row_id" value="' . $value->id . '">';

            })
            ->editColumn('name', function ($value) {
                return '<span data-toggle="tooltip" title="' . $value->name . '">' . mb_strimwidth($value->name, 0, 25, "...") . '</span>';

            })
            ->editColumn('getCompany.name', function ($value) {
                return $value->getCompany->name ? '<span data-toggle="tooltip" title="' . $value->getCompany->name . '">' . mb_strimwidth($value->getCompany->name, 0, 25, "...") . '</span>' : '';
            })
            ->editColumn('customer_type', function ($value) {
                return config('params.customer_types.' . $value->customer_type);
            })
            ->editColumn('status', function ($value) {
                return view('customers.getstatus', ['value' => $value]);
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d/m/Y h:i:s A');
            })
            ->addColumn('action', function ($value) {
                return view('customers.action-button', ['value' => $value]);

            })
            ->editColumn('stores', function ($data) {
                return count($data->stores);
            })
            ->editColumn('requests', function ($data) {
                return \App\Models\AmazonOrder::where('user_id', '=', $data->id)->count();
            })
            ->editColumn('emails', function ($data) {
                return '';
            })
            ->editColumn('email_cadence', function ($data) {
                return '';
            })
            ->escapeColumns([])
            ->make(true);
    }
    /**
     * sendStoreLink function
     *
     * @param Request $request
     * @return Response
     */
    public function sendStoreLink(Request $request, CustomerService $customerService)
    {
        try {
            $customerService->sendStoreLink($request);
            return $this->success_response(200, trans('message.message.store_link_sent'));
        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }

    }
    /**
     * bulkStoreLinkSend function
     *
     * @param Request $request
     * @return Response
     */
    public function sendBulkStoreLink(Request $request, CustomerService $customerService)
    {
        try {
            $customerService->sendBulkStoreLink($request);
            return $this->success_response(200, trans('message.message.store_link_sent'));
        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }

    }

}