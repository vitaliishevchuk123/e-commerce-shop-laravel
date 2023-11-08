<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AttributeValue extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['value'];

    public $fillable = [
        'attribute_id',
        'value',
    ];

    protected $casts = [
        'attribute_id' => 'integer',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
