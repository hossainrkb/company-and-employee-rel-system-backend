<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\EmpAddScheme;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends CentralController
{
    public function index()
    {
        $companies = Company::all();
        return success_response(null,$companies);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $companies = Company::create($this->parseParam($request));
        return response()->json([
            'status' => 'ok',
            'data' => $companies
        ]);
    }

    public function show(Company $company)
    {
        //
    }

    public function edit($company)
    {
        $company = Company::find($company);
        return success_response(null,$company);
    }
    public function update(Request $request, $company)
    {
        $company = Company::find($company);
        $company->update($this->parseParam($request));
        return response()->json([
            'status' => 'ok',
            'data' => $company
        ]);
    }

    public function destroy($company)
    {
        $company = Company::find($company);
        $company->delete();
        return success_response('Deleted Successfully');
    }
    public function addEmployee(Request $request, $companyId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'nullable|unique:employees',
            'phone_number' => 'nullable|unique:employees',
        ]);
        if ($validator->fails()) return error_response($validator->messages());
        $company = Company::find($companyId);
        $sub_end_date = $company->current_sub_when_end;
        if (!isset($sub_end_date)) return error_response('Not Subscribe');
        $todays_date = Carbon::now();
        if ($sub_end_date < $todays_date) return error_response("Your Subscription Period Over");
        $emp_add_scheme = null;
        if (isset($company->subcription_id)) {
            $emp_add_scheme = EmpAddScheme::find($company->subcription_id);
        }
        if (!empty($emp_add_scheme)) {
            $number_of_emp_on_scheme = $emp_add_scheme->qty;
            if ($number_of_emp_on_scheme > count($company->employees)) {
                //can add employee;
                $employee = $company->employees()->create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                ]);
                return success_response("Employee added successfully", ['employee' => $employee]);
            } else {
                return error_response("Employee adding quota filled up");
            }
        } else {
            return error_response("No Subcription Scheme Found");
        }
    }
    public function companyEmployee($companyId)
    {
        $company = Company::find($companyId);
        return success_response('Employee List', ['company' => $company, 'employees' => $company->employees]);
    }
    public function editEmployee($companyId, $employeeId)
    {
        $employee = Employee::find($employeeId);
        return success_response('Successfull', $employee);
    }
    public function updateEmployee(Request $request, $companyId, $employeeId)
    {
        $employee = Employee::find($employeeId);
        $employee->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
        ]);
        return success_response('Update Successfull', $employee);
    }
    public function destroyEmployee($companyId, $employeeId)
    {
        $employee = Employee::find($employeeId);
        $employee->delete();
        return success_response('Delete Successfull');
    }
    public function companyDashboard($companyId)
    {
        $company = Company::find($companyId);
        $returnAbleArray = [];
        $returnAbleArray['total_emp'] = count($company->employees);
        $returnAbleArray['remain_quota'] = $company->empAddScheme->qty - count($company->employees);
        $returnAbleArray['total_attendance_on_day'] = $company->empAttendance()->where('on_date', date("Y-m-d"))->count();
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
        $totalEmpWorkingHrs = 0;
        $startDay = date("Y") . "-" . date("m") . "-" . "1";
        $endDay = date("Y") . "-" . date("m") . "-" . $daysInMonth;
        $currentMonthAttendance =  $company->empAttendance()->whereBetween('on_date', [$startDay, $endDay])->get();
        foreach ($currentMonthAttendance as $key => $attendance) {
            $totalEmpWorkingHrs += isset($attendance->check_out) && isset($attendance->check_in) ? Carbon::parse($attendance->check_out)->diffInHours(Carbon::parse($attendance->check_in)) : null;
        }
        $returnAbleArray['total_emp_working_hrs'] = $totalEmpWorkingHrs;
        $returnAbleArray['latest_five_pending_leave_application'] = $company->employees()->has('currentLeave')->with('currentLeave')->where('current_leave_status', PENDING_LEAVE)->whereNotNull('current_leave_id')->get();

        return success_response(null, ['company' => $company, 'status' => $returnAbleArray]);
    }
    public function getCompany()
    {
        if (Auth::guard('company_api')->user()) {
            return response()->json([
                'status' => 'ok',
                'data' => Auth::guard('company_api')->user()
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }
    public function logout()
    {
        if (Auth::guard('company_api')->user()) {
            Auth::guard('company_api')->user()->AauthAcessToken()->delete();
            return true;
        } else {
            return null;
        }
    }
    private function parseParam($request)
    {
        return [
            'email' => $request->email,
            'name' => $request->name,
            'username' => $request->username,
            'current_sub_start_from' => $request->current_sub_start_from,
            'current_sub_when_end' => $request->current_sub_when_end,
            'sub_fee' => $request->sub_fee,
            'password' => $request->password
        ];
    }
}
