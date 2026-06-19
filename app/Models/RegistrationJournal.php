<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationJournal extends Model
{
    protected $fillable = ['name', 'prefix', 'description', 'is_active'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
