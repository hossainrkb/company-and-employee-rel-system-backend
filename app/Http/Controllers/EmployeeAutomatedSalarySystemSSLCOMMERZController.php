<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Traits\SslCommerzTrait;
use Illuminate\Support\Facades\Log;
use App\Interfaces\SslCommerzInterface;
use App\Models\Employee;
use App\Models\EmpSalary;

class EmployeeAutomatedSalarySystemSSLCOMMERZController extends CentralController implements SslCommerzInterface
{
    use SslCommerzTrait;
    public function createSession(Request $request, $companyId, $employeeId)
    {
        $company = Company::find($companyId);
        $emp     = Employee::find($employeeId);
        $trx     = "SSLCZ_TEST_" . generateRandomString();
        $data = $request->all();
        $data['trx']   = $trx;
        if ($data['salary_type'] == EmpSalary::$basic) {
            EmpSalary::updateOrCreate([
                'com_id'      => $company->id,
                'emp_id'      => $emp->id,
                'month'       => $data['month'],
                'year'        => $data['year'],
                'salary_type' => EmpSalary::$basic,
            ],
                $this->empSalaryParseForDB($data, $company, $emp)
            );
        } else {
            EmpSalary::Create($this->empSalaryParseForDB($data, $company, $emp));
        }
        return $this->sslcommerzcreateSession($data, $emp);
    }
    public function successMethod(Request $request)
    {
        if(!$request->tran_id) return error_response("Tranx Key Not FOund");
        $empSalary = EmpSalary::where('trx_key',$request->tran_id)->first();
        if(isset($empSalary->salary_status) && $empSalary->salary_status=="PENDING"){
            if(is_bool($this->validateSuccess($request))){
                return success_response('Payment Successfully Done');
            }else{
                return $this->validateSuccess($request);
            }
        }else{
            return error_response("Payment already processed");
        }
    }
    public function ipnMethod(Request $request)
    {
        Log::error("INNNN");
        return "Heelo ipn";
    }
    private function empSalaryParseForDB($request, $com, $emp)
    {
        return [
            'emp_id'          => $emp->id,
            'com_id'          => $com->id,
            'trx_key'         => $request['trx'],
            'month'           => $request['month'],
            'year'            => $request['year'],
            'salary_amount'   => $request['salary_amount'],
            'salary_type'     => $request['salary_type'],
            'salary_status'   => $request['salary_status'],
            'salary_currency' => "BDT"
        ];
    }
}
