<?php
namespace App\Services;

use App\Helpers\MarketPlaceHelper;
use App\Models\Store;
use App\Models\StoreConfig;
use App\Models\StoreCredential;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class StoreService
{
    /**
     * getData function
     * get individual and sub customer from users table
     * @param Request $request
     * @return void
     */
    public function getData(Request $request)
    {
        return Store::with('getCustomer', 'getCustomer.getCompany')->select('stores.*')
            ->when((auth()->user()->role == config('params.company_role') && auth()->user()->customer_type == null), function ($query) {
                $query->where('user_id', auth()->user()->id)->orWhereIn('user_id', $this->getSubCustomerIds(auth()->user()->id));
            })
            ->when(auth()->user()->role == config('params.company_role') && auth()->user()->customer_type == config('params.individual_brand'), function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->when(request('companyid'), function ($query) {
                $query->where('user_id', request('companyid'));
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when($request['store_name'], function ($query, $request) {
                $terms = explode(',', $request);
                $query->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('store_name', 'like', "%" . trim($term) . "%");
                    };
                });
            })
            ->when($request['customer_name'], function ($query, $request) {
                $query->whereHas('getCustomer', function ($query) use ($request) {
                    $terms = explode(',', $request);
                    $query->where(function ($query) use ($terms) {
                        foreach ($terms as $term) {
                            $query->orWhere('name', 'like', "%" . trim($term) . "%");
                        };
                    });
                });
            })
            ->when($request['parent_company'], function ($query, $request) {
                $query->whereHas('getCustomer.getCompany', function ($query) use ($request) {
                    $terms = explode(',', $request);
                    $query->where(function ($query) use ($terms) {
                        foreach ($terms as $term) {
                            $query->orWhere('name', 'like', "%" . trim($term) . "%");
                        };
                    });
                });
            })
            ->when($request['store_type'], function ($query, $request) {
                $terms = explode(',', $request);
                $query->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('store_type', 'like', "%" . trim($term) . "%");
                    };
                });
            })
            ->when($request['status'], function ($query) use ($request) {
                $query->where('status', $request['status']);
            })
            ->when($request['store_created_date'], function ($query, $request) {
                $dates = explode('to', $request);
                $query->when(count($dates) == 1, function ($query) use ($dates) {
                    $query->whereDate('created_at', '=', $dates[0]);
                })
                    ->when(count($dates) == 2, function ($query) use ($dates) {
                        $query->whereBetween('created_at', [$dates[0], $dates[1]]);
                    });
            });
    }
    /**
     * update store status
     * @param Request $request
     * @return boolean
     */
    public function updateStatus(Request $request)
    {
        $store = $this->getStore($request->uuid);
        return $store->update(['status' => $request->status, 'updated_by' => Auth()->user()->id]);
    }
    /** get refresh token from amazon seller */
    public function getRefreshToken($request)
    {
        $redirectUri = urlencode(route('store.create'));
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.amazon.com/auth/o2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=' . config('amazon.MWS_CLIENT_ID') . '&client_secret=' . config('amazon.MWS_CLIENT_SECRET') . '&grant_type=authorization_code&code=' . $request->spapi_oauth_code . '&redirect_uri=' . $redirectUri,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
    /**
     * addStore function
     *
     * @param Request $request
     * @return object
     */
    public function addStore(Request $request)
    {

        $configuration = MarketPlaceHelper::configuration($request->store_type);
        $storeConfig = StoreConfig::select('id', 'amazon_aws_region')->where('store_type', $request->store_type)->first();
        $user_id = $request->id ? $request->id : auth()->user()->id;
        // check if any store already exist
        $count = Store::where([
            ['user_id', $user_id],
            ['store_type', $request->store_type],
        ])->whereHas('storeCredentials', function ($query) use ($request) {
            $query->where([
                ['merchant_id', $request->merchant_id],
            ]);
        })->count();
        if ($count > 0) {
            return array('status' => false, 'msg' => 'Account with this detail already exists');
        }
        $store = [
            'uuid' => Str::uuid(),
            'store_marketplace' => 'Amazon',
            'store_name' => trim($request->store_name),
            'store_type' => trim($request->store_type),
            'store_config_id' => $storeConfig->id,
            'user_id' => $user_id,
            'created_by' => $user_id,
            'updated_by' => $user_id,
            'created_at' => Carbon::now(),
        ];

        $store = Store::create($store);
        if (isset($store->id)) {
            $storeCredential = [
                'uuid' => Str::uuid(),
                'store_id' => $store->id,
                'merchant_id' => $request->merchant_id,
                'refresh_token' => $request->refresh_token,
                'access_token' => $request->access_token,
                'mws_access_key_id' => $configuration['mws_access_key_id'],
                'mws_secret_key' => $configuration['mws_secret_key'],
                'aws_access_key_id' => $configuration['aws_access_key_id'],
                'aws_secret_key' => $configuration['aws_secret_key'],
                'order_fetching_start_date' => config('amazon.ORDER_FETCHING_START_DATE'),
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => Carbon::now(),
            ];
            StoreCredential::create($storeCredential);
        }
        return array('status' => true, 'msg' => 'store added');

    }
    /**
     * getStore function
     *
     * @param string $uuid
     * @return object
     */
    public function getStore(string $uuid)
    {
        return Store::with('getCustomer')->where('uuid', $uuid)->first();
    }
    /**
     * deleteStore function
     *
     * @param string $uuid
     * @return boolean
     */
    public function deleteStore(string $uuid)
    {
        $store = $this->getStore($uuid);
        //delete StoreCredential
        StoreCredential::where('store_id', $store->id)->delete();
        $store->update([
            'status' => 3,
            'deleted_by' => Auth()->user()->id,
        ]);
        return $store->delete();
    }
    /**
     * importCustomerStore function
     *
     * @param Request $request
     * @return void
     */
    public function importCustomerStore(Request $request)
    {
        $extension = '';
        if (!empty($request->file)) {
            $extension = $request->file->getClientOriginalExtension();
        }
        if ($extension == 'xlsx') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        // file path
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        // array Count
        $arrayCount = count($allDataInSheet);
        $flag = 0;

        $createArray = array('user_id', 'customername', 'store_name', 'seller_id', 'amazon_marketplace', 'refresh_token', 'access_token', 'aws_access_key_id', 'aws_secret_key');

        $makeArray = array(
            'user_id' => 'user_id',
            'store_name' => 'store_name',
            'seller_id' => 'seller_id',
            'amazon_marketplace' => 'amazon_marketplace',
            'refresh_token' => 'refresh_token',
            'access_token' => 'access_token',
            'aws_access_key_id' => 'aws_access_key_id',
            'aws_secret_key' => 'aws_secret_key',
        );
        $SheetDataKey = array();
        $store_array = array();
        $customer_stores = array();
        foreach ($allDataInSheet as $row_index => $dataInSheet) {
            foreach ($dataInSheet as $key => $value) {
                if (in_array(trim($value), $createArray)) {
                    $value = preg_replace('/\s+/', '', $value);
                    $SheetDataKey[trim($value)] = $key;
                }
            }
        }
        $dataDiff = array_diff_key($makeArray, $SheetDataKey);
        if (empty($dataDiff)) {
            $flag = 1;
        }
        // match excel sheet column
        if ($flag == 1) {
            foreach ($allDataInSheet as $row_index => $dataInSheet) {

                if ($row_index > 1) {
                    $col_headers = array_keys($dataInSheet);
                    array_push($store_array, $dataInSheet[$col_headers[3]]);
                }
            }

            for ($i = 2; $i <= $arrayCount; $i++) {
                $user_id = $allDataInSheet[$i][$SheetDataKey['user_id']];

                $store_name = $allDataInSheet[$i][$SheetDataKey['store_name']];
                $store_name = $allDataInSheet[$i][$SheetDataKey['store_name']];
                $store_type = $allDataInSheet[$i][$SheetDataKey['amazon_marketplace']];

                //check store exist or not
                $isValid = true;
                $store = Store::where([['user_id', $user_id], ['store_name', $store_name]])->first();

                if (!empty($store)) {
                    array_push($customer_stores, array("store_name" => $store->store_name, 'user_id' => $user_id));
                    $isValid = false;
                    continue;
                }
                //check user is active or not
                $customer = User::find($user_id);
                if (empty($customer)) {
                    $isValid = false;
                    continue;
                }
                try {
                    $validator = Validator::make($allDataInSheet[$i], $this->rules(), $this->validationMessages());

                    if ($validator->fails()) {
                        $isValid = false;
                        return response()->json(['status' => false, 'status_code' => 400, 'message' => $validator->errors()->first() . ' Please check in imported file.', 'data' => '']);
                    }

                    if ($isValid) {
                        $storeConfig = StoreConfig::select('id', 'amazon_aws_region')->where('store_type', $store_type)->first();
                        $customer_store = new Store();
                        $customer_store->uuid = Str::uuid();
                        $customer_store->user_id = $user_id;
                        $customer_store->store_name = $store_name;
                        $customer_store->store_marketplace = 'Amazon';
                        $customer_store->store_type = $store_type;
                        $customer_store->store_config_id = $storeConfig->id ?? '';
                        $customer_store->created_by = auth()->user()->id;
                        $customer_store->updated_by = auth()->user()->id;
                        $customer_store->created_at = Carbon::now();
                        $customer_store->save();

                        $customer_store->storeCredentials()->updateOrCreate([
                            'merchant_id' => $allDataInSheet[$i][$SheetDataKey['seller_id']] ?? "",
                            'refresh_token' => $allDataInSheet[$i][$SheetDataKey['refresh_token']] ?? "",
                            'access_token' => $allDataInSheet[$i][$SheetDataKey['access_token']] ?? "",
                            'aws_access_key_id' => $allDataInSheet[$i][$SheetDataKey['aws_access_key_id']] ?? "",
                            'aws_secret_key' => $allDataInSheet[$i][$SheetDataKey['aws_secret_key']] ?? "",
                            'order_fetching_start_date' => config('amazon.ORDER_FETCHING_START_DATE'),
                            'created_by' => auth()->user()->id,
                            'updated_by' => auth()->user()->id,
                            'uuid' => Str::uuid(),
                            'created_at' => Carbon::now(),
                        ]);
                    }

                } catch (\Exception$e) {
                    throw new \Exception($e->getMessage() . ' Please check in imported file.');
                }
            }
            return response()->json(['status' => true, 'status_code' => 200, 'message' => trans('message.message.imported', ['module' => trans('message.label.store')])]);

        } else {
            return response()->json(['status' => false, 'status_code' => 400, 'message' => "Please import correct file, did not match excel sheet column.", 'data' => '']);
        }

    }
    private $errors = []; // array to accumulate errors
    // this function returns all validation errors after import:
    public function getErrors()
    {
        return $this->errors;
    }
    /**define rules for excel file submit time */
    public function rules(): array
    {
        return [
            'A' => 'required|numeric|exists:users,id',
            'B' => 'required',
            'C' => 'required',
            'D' => 'required',
            'E' => 'required|exists:store_configs,store_type',
            'F' => 'required',
            'G' => 'required',
            'H' => 'required',
            'I' => 'required',
        ];
    }
    public function validationMessages()
    {
        return [
            'A.required' => "The user_id is required.",
            'B.required' => "The customer name is required.",
            'C.required' => "The store name is required.",
            'D.required' => "The merchant_id is required.",
            'E.required' => "The amazon marketplace is required.",
            'F.required' => "The refresh_token is required.",
            'G.required' => "The access_token is required.",
            'H.required' => "The aws_access_key_id is required.",
            'I.required' => "The aws_secret_key is required.",
            'A.numeric' => "The user_id must be numeric.",
            'A.exists' => "Invalid user_id.",
            'E.exists' => "Invalid amazon marketplace. Please enter valid value i.e. Amazon US,Amazon UK,etc.",
        ];
    }
    /**
     * getSubCustomerIds function
     *
     * @param integer $companyid
     * @return object
     */
    public function getSubCustomerIds(int $companyid)
    {
        return User::where('companyid', $companyid)->pluck('id');
    }
}