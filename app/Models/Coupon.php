<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'used_times',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function getDiscountAttribute()
    {
        if ($this->type === 'fixed') {
            return $this->value;
        }
        return null;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->type === 'percent') {
            return $this->value;
        }
        return null;
    }

    public function getFormattedDiscountAttribute()
    {
        if ($this->type === 'fixed') {
            return number_format($this->value, 2) . ' €';
        }
        return $this->value . '%';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where(function ($query) {
                $query->whereNull('max_uses')
                    ->orWhere('used_times', '<', 'max_uses');
            });
    }

    public function scopeExpired($query)
    {
        return $query->where(function ($query) {
            $query->where('end_date', '<', now())
                ->orWhere(function ($query) {
                    $query->whereNotNull('max_uses')
                        ->whereColumn('used_times', '>=', 'max_uses');
                });
        });
    }

    public function isValid()
    {
        return $this->is_active
            && $this->start_date->isPast()
            && $this->end_date->isFuture()
            && ($this->max_uses === null || $this->used_times < $this->max_uses);
    }

    public function calculateDiscount($amount)
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->min_order_amount && $amount < $this->min_order_amount) {
            return 0;
        }

        if ($this->type === 'fixed') {
            return $this->value;
        }

        return ($amount * $this->value) / 100;
    }
}
