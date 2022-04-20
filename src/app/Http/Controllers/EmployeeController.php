<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\EmpDetailsTrait;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends CentralController
{
    use EmpDetailsTrait;
    public function empStatCreate($companyId)
    {
        try {
            $company = Company::find($companyId);
            return success_response(null, ['month' => month_list(), 'year' => last_three_year(), 'employees' => $company->employees]);
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }
    public function empStatDetails($companyId, $employeeId, $month, $year)
    {
        try {
            return $this->empStat($companyId, $employeeId, $month, $year);
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }

    public function getEmployee()
    {
        if (Auth::guard('employee_api')->user()) {
            return response()->json([
                'status' => 'ok',
                'data' => Auth::guard('employee_api')->user()
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }
    public function logout()
    {
        if (Auth::guard('employee_api')->user()) {
            Auth::guard('employee_api')->user()->AauthAcessToken()->delete();
            return true;
        } else {
            return null;
        }
    }
}
