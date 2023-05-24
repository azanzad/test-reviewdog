<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function success_response($status_code = 200, $message, $data = '')
    {
        return response()->json([
            'status' => true,
            'status_code' => $status_code,
            'message' => $message,
            'data' => $data,
        ]);
    }
    public function error_response($status_code = 404, $message, $data = '')
    {
        return response()->json([
            'status' => false,
            'status_code' => $status_code,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function delete_file($path = '')
    {
        if (file_exists($path)) {
            unlink($path);
            return true;
        }
    }
}