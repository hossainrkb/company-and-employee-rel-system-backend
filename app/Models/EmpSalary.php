<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpSalary extends Model
{
    protected $fillable = [
        'emp_id',
        'com_id',
        'trx_key',
        'month',
        'year',
        'salary_amount',
        'salary_type',
        'salary_status',
        'salary_currency'
    ];
    public static $basic = 'BASIC';
    public static $misc = 'MISC';
}
