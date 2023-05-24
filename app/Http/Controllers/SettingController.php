<?php

namespace App\Http\Controllers;

use App\Models\UserEmailAutomation;
use App\Services\CompanyService;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = (new CompanyService())->getCompany(auth()->user()->uuid);

        $email_period_ids = UserEmailAutomation::where('user_id', $user->id)->pluck('email_periods')->toArray();

        $timezones = (new SettingService())->getTimezone();

        return view('profile.setting', ['user' => $user, 'email_period_ids' => $email_period_ids, 'timezones'=>$timezones]);

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
    public function store(Request $request, SettingService $settingService)
    {
        try {
            $settingService->addOrUpdateCompanyEmailPeriod($request, auth()->user()->id);
            return $this->success_response(200, trans('message.message.updated', ['module' => trans('message.label.settings')]));
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
}
