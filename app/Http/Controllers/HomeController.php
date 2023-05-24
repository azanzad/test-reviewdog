<?php

namespace App\Http\Controllers;

use App\Services\CompanyCardService;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        Storage::deleteDirectory('user_images');

        $data = $this->dashboardService->getRecordsCount($request);
        $customer_default_card = (new CompanyCardService())->getCustomerDefaultCard(auth()->user()->id);

        $payment_route = route('cards_payment');
        if (empty($customer_default_card)) {
            $payment_route = route('cards.create') . '?type=' . base64_encode('payment');

        }
        return view('home', ['data' => $data, 'customer_default_card' => $customer_default_card, 'payment_route' => $payment_route]);
    }
}