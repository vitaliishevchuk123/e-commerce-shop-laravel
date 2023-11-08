<?php

namespace App\Models;

use App\Casts\Name;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Brand extends Model implements Sortable
{
    use HasFactory, HasSlug, SortableTrait;

    public $fillable = [
        'name',
        'slug',
        'logo',
        'order',
    ];

    protected $casts = [
        'name' => Name::class,
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
