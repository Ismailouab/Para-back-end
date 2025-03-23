<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['name', 'description', 'price', 'image', 'color', 'taille', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function order()
    {
        return $this->belongsToMany(Order::class, 'product_order') // Specify pivot table
            ->withPivot('quantity', 'total_price')  // Include pivot data
            ->withTimestamps(); // Include timestamps from the pivot table
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
