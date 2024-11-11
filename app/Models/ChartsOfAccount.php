<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartsOfAccount extends Model
{
    use HasFactory;

    protected $table = 'charts_of_accounts';

    protected $parent_id = 'id';

    protected $fillable = ['name', 'type', 'description', 'parent_id'];

    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }
}
