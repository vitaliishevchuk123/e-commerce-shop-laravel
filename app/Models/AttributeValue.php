<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    public $fillable = [
        'attribute_id',
        'value',
    ];

    protected $casts  = [
        'attribute_id' =>  'integer',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
