<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\EmpAddScheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return response()->json([
            'status' => 'ok',
            'data' => $companies
        ]);
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

    public function edit(Company $company)
    {
        //
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
        return response()->json([
            'status' => 'ok',
            'message' => 'Delete Successfully'
        ]);
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
