<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OurServices;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'name',
        'description',
        'start_date_english',
        'start_date_nepali',
        'end_date_english',
        'end_date_nepali',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(OurServices::class);
    }
}
