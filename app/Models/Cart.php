<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\CartItem;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalAttribute()
    {
        return $this->items->sum(function($item) {
            return $item->quantity * $item->product->current_price;
        });
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2) . ' €';
    }

    public function getItemsCountAttribute()
    {
        return $this->items->sum('quantity');
    }
}
