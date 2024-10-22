<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;

    protected $table = 'months';

    protected $fillable = [
        'month_name',
        'status'];

    public function employeePayroll()
    {
        return $this->hasMany(EmployeePayroll::class);
    }

    public function payrolls()
    {
        return $this->hasMany(EmployeePayroll::class);
    }
}
