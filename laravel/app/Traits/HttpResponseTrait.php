<?php

namespace App\Traits;

trait HttpResponseTrait
{
    public function success(mixed $data, string $status = 'success', int $code = 200)
    {
        $status = !$status ? 'success':$status;
        return response()->json(['data' => $data, 'status' => $status])->setStatusCode($code);
    }

    public function error(string $message, string $status = 'error', int $code = 500)
    {
        $status = !$status ? 'error':$status;
        return response()->json(['message' => $message, 'status' => $status])->setStatusCode($code);
    }

    public function delete(string $message,string $status = 'success', int $code = 200)
    {
        return response()->json(['message' => $message, 'status' => $status])->setStatusCode($code);
    }

    public function notFound(string $message, string $status = 'info', int $code = 404)
    {
        return response()->json(['message' => $message, 'status' => $status])->setStatusCode($code);
    }
}
