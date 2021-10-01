<?php

namespace App\Http\Traits;

use Exception;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait {

    protected function sendSuccess(array $data){
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $data['message'],
            'data'      => $data['data'] ?? null,
        ], Response::HTTP_OK);
    }

    protected function sendError(Exception $e, $code = Response::HTTP_INTERNAL_SERVER_ERROR){
        return response()->json([
            "status"    => $code,
            "message"   => $e->getMessage(),
        ], $code);
    }
}
