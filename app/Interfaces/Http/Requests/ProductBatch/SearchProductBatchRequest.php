<?php

namespace App\Interfaces\Http\Requests\ProductBatch;

use Illuminate\Foundation\Http\FormRequest;

class SearchProductBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'batch_code' => 'sometimes|string|max:255',
        ];
    }
}