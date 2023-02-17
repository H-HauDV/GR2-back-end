<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'percent', 'max_credit','amount_of_coupon', 'description', 
    'transaction_id',
    'color' => '#27ae60',
    'sub_color' => '#e74c3c',
    'logo'];
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
