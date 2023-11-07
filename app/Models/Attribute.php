<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    public $fillable = [
        'code',
        'name',
        'frontend_type',
        'is_required',
        'is_filterable',
    ];

    protected $casts  = [
        'is_required'   =>  'boolean',
        'is_filterable' =>  'boolean',
    ];

    /**
     * @return HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

}
