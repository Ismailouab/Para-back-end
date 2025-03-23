<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_price', 'status','priority'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsToMany(Product::class, 'product_order') // Specify pivot table
            ->withPivot('quantity', 'total_price')  // Include pivot data
            ->withTimestamps(); // Include timestamps from the pivot table
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class);
    }
}
