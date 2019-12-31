<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'category_id', 'description', 'price', 'image_path',
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'price' => 'double',
    ];

    public function scopeByName($query, $name)
    {
        return $query->where('name', 'LIKE', '%' . $name . '%');
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }
}
