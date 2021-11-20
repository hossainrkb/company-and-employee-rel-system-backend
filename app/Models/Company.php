<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name','email','username'];

    /*
    RELATIONSHIP
    */
    public function employees(){
        return $this->hasMany(Employee::class,'company_id','id');
    }
}
