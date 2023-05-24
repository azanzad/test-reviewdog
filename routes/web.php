<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\CompanyCardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CustomerStoreController;
use App\Http\Controllers\CreatePasswordController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\PaymentTransactionController;
use App\Http\Controllers\Auth\VerificationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::view('/', 'auth.login');

//verify new change email
Route::get('pendingEmail/verifyuser/{token}', [ProfileController::class, 'verify'])->name('pendingEmail.verifyuser');
//verify new change email
Route::get('create_password', [CreatePasswordController::class, 'CreatePassword'])->name('create_password');
Route::post('update_new_password', [CreatePasswordController::class, 'UpdateNewPassword'])->name('update_new_password');

Route::get('daily-request', function () {
    Artisan::call('dailyrequest:user');
    echo 'Request details mail sent.';
});

Route::get('weekly-request', function () {
    Artisan::call('weeklyrequest:user');
    echo 'Request details mail sent.';
});

Route::get('monthly-request', function () {
    Artisan::call('monthlyrequest:user');
    echo 'Request details mail sent.';
});



Auth::routes(['verify' => true]);

Route::get('signup/{uuid}', [SignupController::class,'index'])->name('signup');
Route::post('signup/store', [SignupController::class,'store'])->name('signup.store');

Route::middleware(['auth','verified'])->group(function () {

    Route::get('card/add', [CompanyCardController::class,'addUserCard'])->name('add-card');
    Route::post('card/store-usercard', [CompanyCardController::class,'storeUserCard'])->name('store-usercard');
    Route::post('apply-promotion-code', [CompanyCardController::class,'applyPromotionCode'])->name('apply-promotion-code');

    Route::middleware('check-card')->group(function () {

        /**dashboard */
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        /***Profile & current subscription*/
        Route::controller(ProfileController::class)->group(function () {
            Route::get('profile', 'index')->name('profile.index');
            Route::put('profile/{uuid}', 'update')->name('profile.update');
            Route::group(['prefix' => 'profile'], function () {
                Route::get('current_plan', 'currentPlan')->name('profile.current_plan');
                Route::get('change_password', 'changePassword')->name('change_password');
            });
        });
        /**change password */
        Route::match(['put', 'post'], 'update_password', [ProfileController::class, 'updatePassword'])->name('update_password');

        /***Start : only admin can access this module****/
        Route::group(['middleware' => 'check-permission:' . config('params.admin_role')], function () {
            /***plan ****/
            Route::controller(SubscriptionPlanController::class)->group(function () {
                Route::get('plans', 'index')->name('plans.index');
                Route::get('plans/create', 'create')->name('plans.create');
                Route::post('plans', 'store')->name('plans.store');
                Route::get('plans/{uuid}', 'show')->name('plans.show');
                Route::delete('plans/{uuid}', 'destroy')->name('plans.destroy');
                Route::group(['prefix' => 'plans'], function () {
                    Route::post('changestatus', 'changeStatus')->name('plans.changestatus');
                    Route::get('getAllCompany/{uuid?}', 'getAllCompany')->name('plans.getAllCompany');
                });
            });

            /***company ****/
            Route::controller(CompanyController::class)->group(function () {
                Route::get('company', 'index')->name('company.index');
                Route::get('company/create', 'create')->name('company.create');
                Route::post('company', 'store')->name('company.store');
                Route::get('company/{uuid}', 'show')->name('company.show');
                Route::put('company/{uuid}', 'update')->name('company.update');
                Route::delete('company/{uuid}', 'destroy')->name('company.destroy');
                Route::get('company/{uuid}/edit', 'edit')->name('company.edit');
                Route::group(['prefix' => 'company'], function () {
                    Route::post('appendcontact', 'appendContact')->name('company.appendcontact');
                    Route::post('changestatus', 'changeStatus')->name('company.changestatus');
                    Route::get('getAllContacts/{uuid?}', 'getAllContacts')->name('company.getAllContacts');
                });
            });
        });
        /***END : only admin can access this module****/

        /***Payment Transaction */
        Route::resource('payment_transaction', PaymentTransactionController::class);

        /***active subcription */
        Route::resource('subscription', SubscriptionController::class);
        Route::post('cancel-subscriptionbyadmin', [SubscriptionController::class,'cancelSubscriptionByAdmin'])->name('cancel.subscriptionbyadmin');

        /*** Start: only company can access this module**/
        Route::group(['middleware' => 'check-permission:' . config('params.company_role')], function () {
            /***cards ****/
            Route::controller(CompanyCardController::class)->group(function () {
                Route::get('cards', 'index')->name('cards.index');
                Route::get('cards/create', 'create')->name('cards.create');
                Route::post('cards', 'store')->name('cards.store');
                Route::delete('cards/{uuid}', 'destroy')->name('cards.destroy');
                //makePayment
                Route::get('cards_payment', 'createPayment')->name('cards_payment');
                Route::post('makepayment', 'makePayment')->name('cards_makepayment');
                Route::group(['prefix' => 'cards'], function () {
                    Route::post('changeDefaultCard', 'changeDefaultCard')->name('cards.changeDefaultCard');
                });
            });

            //setting
            Route::controller(SettingController::class)->group(function () {
                Route::get('settings', 'index')->name('settings.index');
                Route::post('settings', 'store')->name('settings.store');
            });

        });
        /*** END : only company can access this module**/

        /***customer ****/
        Route::controller(CustomerController::class)->group(function () {
            Route::get('customer', 'index')->name('customer.index');
            Route::get('customer/create', 'create')->name('customer.create');
            Route::post('customer', 'store')->name('customer.store');
            Route::get('customer/{uuid}', 'show')->name('customer.show');
            Route::get('customer/{uuid}/edit', 'edit')->name('customer.edit');
            Route::put('customer/{uuid}', 'update')->name('customer.update');
            Route::delete('customer/{uuid}', 'destroy')->name('customer.destroy');

            Route::group(['prefix' => 'customer'], function () {
                Route::post('appendcontact', 'appendContact')->name('customer.appendcontact');
                Route::post('changestatus', 'changeStatus')->name('customer.changestatus');
                Route::get('getAllContacts/{uuid?}', 'getAllContacts')->name('customer.getAllContacts');
                Route::post('customer_export', 'exportCustomers')->name('customer.export');
                Route::post('send-store-link', 'sendStoreLink')->name('customer.send-store-link');
                Route::post('send-bulk-store-link', 'sendBulkStoreLink')->name('send-bulk-store-link');
            });
        });

        /***stores */
        Route::controller(StoreController::class)->group(function () {
            Route::get('store', 'index')->name('store.index');
            // Route::get('store/create', 'create')->name('store.create');
            Route::post('store', 'store')->name('store.store');
            Route::delete('store/{uuid}', 'destroy')->name('store.destroy');
            Route::group(['prefix' => 'store'], function () {
                Route::post('changestatus', 'changeStatus')->name('store.changestatus');
                Route::get('bulk_store', 'importBulkStore')->name('store.bulk_store');
                Route::post('submitexcel', 'submitStoreExcel')->name('store.submitexcel');
            });
        });

        /***request */
        Route::controller(RequestsController::class)->group(function () {
            Route::get('request', 'index')->name('request.index');

            Route::group(['prefix' => 'request'], function () {
                Route::get('select-order-status', 'selectOrderStatus')->name('request.select-order-status');
            });
        });
        Route::get('company.fetchAllCompanies', [CompanyController::class, 'fetchAllCompanies'])->name('company.fetchAllCompanies');
        Route::post('fetchCustomerOfCompany', [CompanyController::class, 'fetchCustomerOfCompany'])->name('company.fetchCustomerOfCompany');
    });
});

//stripe webhook
Route::post('webhooks/stripe', [WebhookController::class, 'handleWebhook'])->name('webhooks.stripe');

/***customer stores */
Route::controller(CustomerStoreController::class)->group(function () {
    Route::get('store/create/{uuid}', 'create')->name('store.create');
    Route::post('store', 'store')->name('store.store');
});
Route::get('store/create', [StoreController::class, 'create'])->name('store.create')->middleware(['session.has.user']);

