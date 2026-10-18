<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

class UploadedImage extends Model
{
    protected $table = 'uploaded_images';

    protected $fillable = [
        'url',
        'cloudflare_id',
        'filename',
        'size_bytes',
        'mime_type',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];
}
