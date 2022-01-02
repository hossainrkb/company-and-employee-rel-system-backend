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
        // dd($request->all());
        $explode_verify_key = explode(",",$request->verify_key);
        $data = [];
        foreach ($explode_verify_key as $key => $value) {
            $data[$value] = $request->$value;
        }
        $data['store_passwd'] = md5(env('SANDBOX_STORE_PASSWORD'));
        ksort($data);
        $hash_string = "";
        foreach ($data as $key => $value) {
            $hash_string .= $key . '=' . ($value) . '&';
        }
        if(md5(trim($hash_string,"&")) == $request->verify_sign){
            return true;
        }else{
            return false;
        }
        return $request->all();
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
