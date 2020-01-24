<?php namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company;
class CompaniesController extends Controller {

public function AddCompany(Request $request){
    $company=new Company();
    $company->name=$request->input('name');
    $company->description=$request->input('description');
    $company->email=$request->input('email');
    $company->phone=$request->input('phone');
    $company->adress=$request->input('adress');
    if($company->save()){
return response()->json('Company has been added successfully');
    }
    return response()->json('error');

}
public function UpdateCompany($id,Request $request){
    $company=Company::find($id);
    $company->name=$request->input('name');
    $company->description=$request->input('description');
    $company->email=$request->input('email');
    $company->phone=$request->input('phone');
    $company->adress=$request->input('adress');
    if($company->save()){
return response()->json('Company has been Updated successfully');
    }
    return response()->json('error');

}
public function DeleteCompany($id){
    $company=Company::find($id);
    $company->delete();
    return response()->json('Deleted');
    return response()->json('error');
}
public function GetCompany($id){
 
    $company=Company::find($id);
    return response()->json($company);
    }
    public function GetCompaniesList(){
     
        $companies_list=Company::all();
        return response()->json($companies_list);
        }
    
}
