<?php

namespace App\Traits;

trait HttpResponseTrait
{
    public function success(mixed $data, string $message = 'ok', int $code = 200)
    {
        $result = [
            'status_code' => $code,
            'message' => $message,
            'data' => $data,
        ];
        return response()->json($result)->setStatusCode($code);
    }

    public function error(mixed $data,  string $message = 'error', int $code = 500)
    {
        $result = [
            'status_code' => $code,
            'message' => $message,
            'data' => $data,
        ];
        return response()->json($result)->setStatusCode($code);
    }
}
