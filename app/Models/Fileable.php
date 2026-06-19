<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fileable extends Model
{
    protected $table = 'fileables';

    protected $fillable = [
        'file_id',
        'fileable_id',
        'fileable_type',
        'fileable_key',
        'sort',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
