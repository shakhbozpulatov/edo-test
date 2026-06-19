<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAttachment extends Model
{
    protected $fillable = [
        'document_id', 'original_name', 'file_path', 'file_name', 'mime_type', 'file_size', 'type',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
