<?php namespace App\Http\Controllers;
use App\Models\Depositesiyou;

class DepositesiyousController extends Controller 
{
public function addDeposite(){
    $user=AuthController::me();
    $Depositesiyou=new Depositesiyou();
    $Depositesiyou->supplier_id=$request->input('supplier_id');
    $Depositesiyou->amount=0;
    $Depositesiyou->save();
}

public function UpdateDeposite($id,Request $request)
{
    $user=AuthController::me();

    $Depositesiyou= Depositesiyou::findOrFail($id);
    $Depositesiyou->amount=$request->input('amount');
    if($Depositesiyou->save())
    {
        return response()->json(["msg" => "success!!"]);
    }
    return response()->json(["msg" => "error!!"]);
}
public function DeleteDeposite($id){
    $user=AuthController::me();
    $Depositesiyou=Depositesiyou::findorfail($id);
            $Depositesiyou->delete();
        return response()->json("Deposite siyou has been Deleted Successfully !");
    return response()->json("ERROR !!");    
}
public function getDepositesiyou(){
$supplier=AuthController::me();
$Depositesiyou=Depositesiyou::where('supplier_id', $supplier->id)->get();
return response()->json($Depositesiyou);
return response()->json(['msg'=>'error']);
}
}
