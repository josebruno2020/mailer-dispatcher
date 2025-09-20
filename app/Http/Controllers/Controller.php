<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class Controller
{
    protected function sendJson($data, $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }
}
