<?php 
namespace App\Http\Controllers;


use App\Models\Siyoucommission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use App\Models\User;

class SiyoucommissionsController extends Controller {

 

    public function addCommission(Request $request)
    {
        $supplier=AuthController::me();
        $siyoucommission=new Siyoucommission();
        $siyoucommission->commission_percent=$request->input('commission_percent');
        $siyoucommission->supplier_id=$supplier->id;
        if($siyoucommission->save())
        {
            return response()->json(["msg" => "success!!"]);
        }
        return response()->json(["msg" => "error!!"]);
    }

    public function updateCommission($id,Request $request)
    {
        $supplier=AuthController::me();

        $siyoucommission= Siyoucommission::findOrFail($id);
        $siyoucommission->commission_percent=$request->input('commission_percent');
        if($siyoucommission->save())
        {
            return response()->json(["msg" => "success!!"]);
        }
        return response()->json(["msg" => "error!!"]);
    }
    public function DeleteCommission($id){
        $supplier=AuthController::me();
        $siyoucommission=Siyoucommission::findorfail($id);
                $siyoucommission->delete();
            return response()->json("Commission has been Deleted Successfully !");
        return response()->json("ERROR !!");    
}
public function getcommision(){
    $supplier=AuthController::me();
    $siyoucommission=Siyoucommission::where('supplier_id', $supplier->id)->get();
    return response()->json($siyoucommission);
    return response()->json(['msg'=>'error']);
}
}
