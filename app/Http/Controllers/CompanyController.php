<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

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
        $companies = Company::create([
            'email' => $request->email,
            'name' => $request->name,
            'username' => $request->username,
        ]);
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
        $company->update([
            'email' => $request->email,
            'name' => $request->name,
            'username' => $request->username,
        ]);
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
}
