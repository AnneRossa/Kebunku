<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use function PHPSTORM_META\map;
use Spatie\Sluggable\SlugOptions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasSlug;
    use HasFactory;
    use SoftDeletes;

    // Add this line to your fillable properties if necessary
    protected $dates = ['deleted_by'];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'published',
        'inStock',
        'price',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeFiltered(Builder $quary) {
        $quary
        ->when(request('brands'), function(Builder $q) {
            $q->whereIn('location_id', request('locations'));
        })
        ->when(request('categories'), function(Builder $q){
            $q->whereIn('category_id', request('categories'));
        })
        ->when(request('prices'), function(Builder $q){
            $q->whereBetween('price', [
                request('prices.from', 0),
                request('prices.to', 10000),
            ]);
        });

    }

}
