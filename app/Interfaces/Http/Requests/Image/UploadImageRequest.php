<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Accepts image files up to 50MB upfront so our service can compress them under 3MB
            'image' => 'required|image|max:51200',
        ];
    }
}
