<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadedImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'cloudflare_id' => $this->cloudflare_id,
            'filename' => $this->filename,
            'size_bytes' => $this->size_bytes,
            'mime_type' => $this->mime_type,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}

