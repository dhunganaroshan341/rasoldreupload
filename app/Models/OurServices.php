<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// to show the log records:
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OurServices extends Model
{
    use HasFactory,LogsActivity;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'text']);
        // Chain fluent methods for configuration options
    }

    protected $table = 'our_services';

    protected $primary_key = 'id';

    protected $fillable = ['name', 'price',
        'duration', 'duration_type', 'description', 'category', 'status', 'parent',
    ];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_services', 'service_id', 'client_id');
    }

    public function clientServices()
    {
        return $this->hasMany(ClientService::class, 'service_id', 'id');
    }
}
