<?php namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Supplier_Salesmanager_ShopOwner;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class salesmanagersController extends Controller {

    // const MODEL = "App\supplier/salesmanager";

    // use RESTActions;
    public function getSupplierOrderShop()
    {
        $supplier = AuthController::me();
        return response()->json($supplier->getShopsThroughOrder()->distinct()->with('salesmanagerToShop')->distinct('salesmanagerToShop')->get(), 200);
    }

    public function getSupplierSalesmanagerShop()
    {
        $supplier = AuthController::me();
        return response()->json($supplier->salesmanagerToSupplier()->with(['shopOwners' => function ($query) use ($supplier) {
            $query->wherePivot('supplier_id', $supplier->id)->distinct();
        }])->distinct()->get());
    }

    

    public function addSalesManagerToSupplier(Request $request)
    {
        $supplier = AuthController::me();
        
        $salesManagerId = $request->input('salesmanager_id');
        $salesmanager = User::where('id',$salesManagerId)->first();
        
        if ($supplier->salesmanagerToSupplier()->attach($salesmanager)) {
            return response()->json(["msg" => "All Is Good"]);
        }
    }

    public function deleteSalesManager($id)
    {
        $supplier = AuthController::me();
        $salesmanager = User::find($id);
        if ($supplier->salesmanagerToSupplier()->detach($salesmanager)) {
            return response()->json(["msg" => "All Is Good"]);
        }
    }

    public function updateSMCommesion(Request $request,$id)
    {
        $supplier = AuthController::me();
        $salesmanager = User::find($id);
        $commision_amount=$request->input("commission");
        
        $commission=Supplier_Salesmanager_ShopOwner::where('supplier_id',$supplier->id)
        ->where('salesmanager_id',$id)->update(array('commission_amount' => $commision_amount));
        
            return response()->json(["msg" => "All Is Good"]);
        
    }


    public function linkSalesManagerToShop(Request $request)
    {
        $supplier = AuthController::me();
        $salesManagerId = $request->input('salesmanager_id');
        $shop_owner_id = $request->input('shop_owner_id');
        $commission_amount = $request->input('commission_amount');
        // $shop_owner = User::find($shop_owner_id);
        // $salesmanager = User::find($salesManagerId)->first();
        $row = Supplier_Salesmanager_ShopOwner::where([
            "supplier_id" => $supplier['id'],
            "salesmanager_id" => $salesManagerId,
            "shop_owner_id" => null
        ])->first();
        if ($row) {
            $row->shop_owner_id = $shop_owner_id;
            $row->commission_amount = $commission_amount;
            if ($row->save()) {
                return response()->json(["msg" => 'data updated'], 200);
            }
            return response()->json(["msg" => 'erreur while updating'], 500);
        }
        return response()->json(['msg' => 'no data found'], 404);
    }
    public function getSalesManagerList()
    {
        $supplier = AuthController::me();
        return response()->json($supplier->salesmanagerToSupplier);
    }

}
