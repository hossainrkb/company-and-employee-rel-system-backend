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
    public function empSalaryDetails(Request $request, $companyId, $employeeId)
    {
        $returnAbleArray = [];
        $current_month_total_salary_stat = EmpSalary::where([
            'emp_id' => $employeeId,
            'com_id' => $companyId,
            'month' => $request->month,
            'year' => $request->year,
        ])->get();
        $selected_month_year_salary_status_basic = EmpSalary::where([
            'emp_id' => $employeeId,
            'com_id' => $companyId,
            'month' => $request->month,
            'year' => $request->year,
            'salary_type' => EmpSalary::$basic
        ])->first();

        $total_salary_status = EmpSalary::where([
            'emp_id' => $employeeId,
            'com_id' => $companyId,
            'salary_type' => EmpSalary::$basic
        ])->get();
        $returnAbleArray['total_salary_status'] = [];
        foreach ($total_salary_status as $key => $value) {
            $month = isset($value->month) ? (strlen($value->month) > 1 ? $value->month : "0$value->month") : "";
            $strMonth = "";
            if (strlen($month)) {
                $strMonth = array_flip(month_list())[$month];
            }
            array_push($returnAbleArray['total_salary_status'], [
                'trx' => $value->trx_key ?? "",
                'company'    => $value->company->name ?? "",
                'month'    => $strMonth ?? "",
                'year'     => $value->year ?? "",
                'amount'   => $value->salary_amount ?? "",
                'type'     => $value->salary_type ?? "",
                'status'   => $value->salary_status ?? "",
                'currency' => $value->salary_currency ?? "",
                'details'  => $value->misc_details ?? "N\A",
                'is_already_given_basic_salary' => is_null($selected_month_year_salary_status_basic) ? false : true
            ]);
        }
        $returnAbleArray['current_month_total_salary_stat'] = [];
        foreach ($current_month_total_salary_stat as $key => $value) {
            $month = isset($value->month) ? (strlen($value->month) > 1 ? $value->month : "0$value->month") : "";
            $strMonth = "";
            if (strlen($month)) {
                $strMonth = array_flip(month_list())[$month];
            }
            array_push($returnAbleArray['current_month_total_salary_stat'], [
                'trx' => $value->trx_key ?? "",
                'company'    => $value->company->name ?? "",
                'month'    => $strMonth ?? "",
                'year'     => $value->year ?? "",
                'amount'   => $value->salary_amount ?? "",
                'type'     => $value->salary_type ?? "",
                'status'   => $value->salary_status ?? "",
                'currency' => $value->salary_currency ?? "",
                'details'  => $value->misc_details ?? "N\A",
            ]);
        }
        $returnAbleArray['salary_type'][] = [
            'val' => EmpSalary::$basic,
            'text' => EmpSalary::$basic,
        ];
        $returnAbleArray['salary_type'][] = [
            'val' => EmpSalary::$misc,
            'text' => EmpSalary::$misc,
        ];
        $returnAbleArray['select_employee'] = Employee::find($employeeId);
        return success_response('Successfull', $returnAbleArray);
    }
    public function createSession(Request $request, $companyId, $employeeId)
    {
        $company = Company::find($companyId);
        $emp     = Employee::find($employeeId);
        $trx     = "SSLCZ_TEST_" . generateRandomString();
        $data = $request->all();
        $data['trx']   = $trx;
        $data['acc_no']   = $request->acc_no;
        if ($data['salary_type'] == EmpSalary::$basic) {
            EmpSalary::Create(
                array_merge([
                    'com_id'      => $company->id,
                    'emp_id'      => $emp->id,
                    'month'       => $data['month'],
                    'year'        => $data['year'],
                    'salary_type' => EmpSalary::$basic,
                ], $this->empSalaryParseForDB($data, $company, $emp))
            );
        } else {
            EmpSalary::Create($this->empSalaryParseForDB($data, $company, $emp));
        }
        return $this->sslcommerzcreateSession($data, $emp);
    }
    public function successMethod(Request $request)
    {
        if (!$request->tran_id) return error_response("Tranx Key Not FOund");
        $empSalary = EmpSalary::where('trx_key', $request->tran_id)->first();
        $request['acc_no'] = $empSalary->employee->acc_no;
        if (isset($empSalary->salary_status) && $empSalary->salary_status == "PENDING") {
            if (is_bool($this->validateSuccess($request))) {
                // return success_response('Payment Successfully Done');
                return redirect("http://localhost:3000/company/$empSalary->com_id/disburse-salary");
            } else {
                return $this->validateSuccess($request);
            }
        } else {
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
            'misc_details'   => $request['misc'],
            'salary_currency' => "BDT"
        ];
    }
}
