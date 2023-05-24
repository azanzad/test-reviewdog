<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * updateProfile function
     * update user profile details
     * @param Request $request
     * @param string $uuid
     * @return object
     */
    public function updateProfile(Request $request, string $uuid)
    {
        $user = (new CompanyService())->getCompany($uuid);
        $profileImage = $user->profile_image;
        $path = '/user_images';
        if (!Storage::disk('public')->exists($path)) {
            Storage::makeDirectory($path, 0777, true); //creates directory
        }
        if (empty($request->userProfile)) {
            $profileImage = '';
        }
        if ($request->file('profile_image')) {
            $file = $request->file('profile_image');
            if ($user->profile_image != null) {
                $oldImagePath = $user->profile_image;
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
            }
            $profileImage = Storage::disk('public')->put($path, $file);
        }
        $timezone = (new SettingService())->getTimezoneByCountryCode($request->country_id);

        $user->update([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'timezone' => $timezone,
            'profile_image' => $profileImage,
            'contact_number' => $request->contact_number,
            'country_code' => $request->contact_number == null ? null : $request->country_code,
            'updated_by' => Auth()->user()->id,
        ]);
        if (auth()->user()->email != $request->email) {
            auth()->user()->newEmail($request->email);
        }
        return $user;
    }
    /**
     * changePassword function
     * chnage login user password
     * @param Request $request
     * @return object
     */
    public function changePassword(Request $request)
    {
        return User::where('id', auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

    }
}
