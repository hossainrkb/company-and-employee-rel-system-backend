<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpAttendance extends Model
{
    protected $fillable = ['emp_id', 'com_id', 'on_date', 'check_in', 'check_out'];
}
