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
        'salary_currency',
        'misc_details'
    ];
    public static $basic = 'BASIC';
    public static $misc = 'MISC';
    public function company(){
        return $this->belongsTo(Company::class,'com_id','id');
    }
    public function employee(){
        return $this->belongsTo(Employee::class,'emp_id','id');
    }
}
