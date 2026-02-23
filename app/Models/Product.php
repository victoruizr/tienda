<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
 
    protected $table = 'products';
    protected $fillable = ['name', 'price','stock', 'active', 'category_id'];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class,'product_id');
    }


}
