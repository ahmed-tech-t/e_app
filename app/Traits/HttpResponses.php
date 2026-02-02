<?php

namespace App\Traits;

use function PHPUnit\Framework\isArray;

trait HttpResponses
{
    public function success($data, $message = "Success", $meta = null, $code = 200)
    {
        if ($meta)
            return response()->json([
                'code' => $code,
                'status' => 'success',
                'message' => $message,
                'data' => [
                    'items' => $data,
                    'meta' => $meta
                ]

            ], $code);

        return response()->json([
            'code' => $code,
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);


    }

    public function error($data, $message = null, $code = 404)
    {
        return response()->json([
            'code' => $code,
            'status' => 'Error has occurred...',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}