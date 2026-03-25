<?php

namespace App\Traits;


trait HttpResponses
{
    public function success($data = [], $message = "Success", $meta = null, $code = 200)
    {

        $response = [
            'code' => $code,
            'status' => 'success',
            'message' => $message,
        ];

        if (!empty($data)) {
            if ($meta) {
                $response['data'] = [
                    'items' => $data,
                    'meta' => $meta
                ];
            } else {
                $response['data'] = $data;
            }

        }

        return response()->json($response, $code);

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