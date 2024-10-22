<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'changes',
        'url',
    ];

    // Optionally, if you have a User model and want to define a relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
