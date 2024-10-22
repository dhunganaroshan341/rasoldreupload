<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $primary_key = 'id';

    protected $fillable = ['user_id', 'action', 'model', 'model_id', 'changes'];
}
