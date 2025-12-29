<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public static function res($msg, $success = true, $data = [], $status = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $msg,
            'data' => $data
        ], $status);
    }
}
