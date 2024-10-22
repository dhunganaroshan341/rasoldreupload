<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = ['name', 'position', 'email', 'address', 'phone', 'salary'];

    public function employeePayroll()
    {
        return $this->hasMany(EmployeePayroll::class);
    }

    public function payrolls()
    {
        return $this->hasMany(EmployeePayroll::class);
    }
}
