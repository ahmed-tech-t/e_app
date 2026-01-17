<?php

namespace App\Traits;

use function PHPUnit\Framework\isArray;

trait HttpResponses
{
    public function success($data, $message = "Success", $code = 200, $title = 'items')
    {
        if (is_array($data) && isset($data['meta']))
            return response()->json([
                'code' => $code,
                'status' => 'success',
                'message' => $message,
                'data' => [
                    $title => $data['data'],
                    'meta' => [
                        'current_page' => $data['meta']['current_page'],
                        'last_page' => $data['meta']['last_page'],
                        'total_items' => $data['meta']['total'],
                    ]
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