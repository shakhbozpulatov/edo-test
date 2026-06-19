<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'parent_id', 'region_id', 'district_id', 'name', 'type', 'is_category', 'sort_order',
    ];

    protected $casts = ['is_category' => 'boolean'];

    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_recipients');
    }
}
