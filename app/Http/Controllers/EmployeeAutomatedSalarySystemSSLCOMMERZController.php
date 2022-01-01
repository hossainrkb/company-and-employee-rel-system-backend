<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Traits\SslCommerzTrait;
use Illuminate\Support\Facades\Log;
use App\Interfaces\SslCommerzInterface;

class EmployeeAutomatedSalarySystemSSLCOMMERZController extends CentralController implements SslCommerzInterface
{
    use SslCommerzTrait;
    public function createSession(Request $request, $companyId){
        $company   = Company::find($companyId);
       return $this->sslcommerzcreateSession($request,$companyId);
    }
    public function successMethod(Request $request){
        dd($request->all());
        return $request->all();
    }
    public function ipnMethod(){
        Log::error("INNNN");
        return "Heelo ipn";
    }
}
