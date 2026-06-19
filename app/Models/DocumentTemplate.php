<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    protected $fillable = ['name', 'description', 'file_path', 'file_name', 'is_active'];

    public function documents()
    {
        return $this->hasMany(Document::class, 'template_id');
    }
}
