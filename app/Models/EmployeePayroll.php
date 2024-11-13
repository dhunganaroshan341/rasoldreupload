<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayroll extends Model
{
    use HasFactory;

    protected $table = 'employee_payrolls';

    protected $primary_key = 'id';

    protected $fillable = ['employee_id',    'payroll_status', 'month_id', 'remaining_amount', 'default_salary_amount', 'amount'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }
}
