<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'address_complement',
        'postal_code',
        'city',
        'country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getCountryNameAttribute()
    {
        $countries = [
            'FR' => 'France',
            'BE' => 'Belgique',
            'CH' => 'Suisse',
            'LU' => 'Luxembourg',
        ];

        return $countries[$this->country] ?? $this->country;
    }

    public function setAsDefault()
    {
        $this->user->addresses()->where('id', '!=', $this->id)->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address;
        
        if ($this->address_complement) {
            $address .= "\n" . $this->address_complement;
        }

        $address .= "\n" . $this->postal_code . ' ' . $this->city;
        $address .= "\n" . $this->country_name;

        return $address;
    }
}
