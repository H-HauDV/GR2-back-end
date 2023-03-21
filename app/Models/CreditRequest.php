<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditRequest extends Model
{
    protected $table = 'credit_requests';
    use HasFactory;
    protected $fillable = [
        'user_id', 'ammount', 'message',
        'metamask_account',
        'status' => 'pending'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
