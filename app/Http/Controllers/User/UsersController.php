<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Supplier_Salesmanager_ShopOwner;
use App\Models\User;
use App\Models\DepositeShop;
use App\Models\Depositesiyou;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

// use Tymon\JWTAuth\JWTAuth;

class UsersController extends Controller
{

    // const MODEL = "App\Users";

    // use RESTActions;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['addSupplier', 'addSalesManager','addShop_Owner']]);
    }

    public function getInvalidUsers(Request $request)
    {
        $invalidList = user::where('validation', 0)->get();
        return response()->json($invalidList, 200);
    }

    public function validateUser($user_id)
    {
        $user = user::findorfail($user_id);
        $user->validation = 1;
        $user->save();
        return response()->json(['msg' => 'user acount has been validated'], 200);
    }

    public function addSupplier(Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $user = new User();
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $role = Role::where('name', 'Supplier')->first();
        $role->users()->save($user);
        $user->save();

        return response()->json(["msg" => "user added successfully !"], 200);

        return response()->json(["msg" => "ERROR !"], 500);
    }

    public function addShop_Owner(Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $user = new User();
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $role = Role::where('name', 'Shop_Owner')->first();
        $role->users()->save($user);
        $user->save();
        return response()->json(["msg" => "user added successfully !"], 200);

        return response()->json(["msg" => "ERROR !"], 500);
    }

    public function addSalesManager(Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');

        $user = new User();
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = Hash::make($password);

        $role = Role::where('name', 'salesmanager')->first();
        $role->users()->save($user);
        $user->save();
        return response()->json(["msg" => "user added successfully !"], 200);

        return response()->json(["msg" => "Error !"], 500);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(["msg" => "the user has been deleted successfully !!"]);
    }
    public function ShowUser($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }
    public function UsersList()
    {
        $userlist = User::all();
        return response()->json($userlist);
    }


    public function GetUserByRole($id)
    {
        $user_role = User::where('role_id', $id)->get();
        return response()->json($user_role);
    }


    //function for supplier
    public function getSupplierOrderShop()
    {
        // if (!$user = JWTAuth::parseToken()->authenticate()) {
        //     return response()->json(["msg" => 'user_not_found'], 404);
        // }
        // $data = compact('user');
        // $supplier = User::where('id', $data['user']['id'])->first();
        $supplier = AuthController::me();
        return response()->json($supplier->getShopsThroughOrder()->distinct()->with('salesmanagerToShop')->distinct('salesmanagerToShop')->get(), 200);
    }

    public function getSupplierSalesmanagerShop()
    {
        $supplier = AuthController::me();
        $row = Supplier_Salesmanager_ShopOwner::where( 'supplier_id' , $supplier['id'])->first();
        return response()->json($row);
    }

     public function addSalesManagerToSupplier(Request $request) {
         
        $supplier = AuthController::me();
        $salesmanager_id=$request->input('salesmanager_id');
        $salesmanager = User::where('id',$salesmanager_id)->first();
        $found=Supplier_Salesmanager_ShopOwner::where('supplier_id', $supplier->id)->where('salesmanager_id',$salesmanager_id)->count();
        if($salesmanager == 0){
        $supplier->salesmanagerToSupplier()->attach($salesmanager);
            return response()->json(["msg" => "All Is Good"],200);
        }
        return response()->json(["msg"=>"Error"],500);
     }

     public function addShopToSupplier(Request $request) {
         
        $supplier = AuthController::me();
        $shop_owner_id=$request->input('shop_owner_id');
        $shopowner = User::where('id',$shop_owner_id)->first();
        $relation=new Supplier_Salesmanager_ShopOwner();
       $supplier->shopsToSupplier()->attach($shopowner);
       if($relation){
            $deposite=new DepositeShop();
            $deposite->supplier_id=$supplier->id;
            $deposite->shop_owner_id=$shop_owner_id;
            $deposite->amount=$request->input('amount');
            $deposite->save();  
            return response()->json(["msg" => "All Is Good"],200);
       }
        return response()->json(["msg"=>"Error"],500);
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
        
        return response()->json(["msg" => "commission has been update!!"]);
        
    }


    public function linkSalesManagerToShop(Request $request)
    {
        $supplier = AuthController::me();
        $salesmanager_id = $request->input('salesmanager_id');
        $shop_owner_id = $request->input('shop_owner_id');
        $commission_amount = $request->input('commission_amount');
       
        $row = Supplier_Salesmanager_ShopOwner::where([
            "supplier_id" => $supplier['id'],
            "shop_owner_id" => $shop_owner_id,
            "salesmanager_id" => null
        ])->first();
       
        if ($row) {
            $row->salesmanager_id = $salesmanager_id;
            $row->commission_amount = $commission_amount;
            if ($row->save()) {
                return response()->json(["msg" => 'data updated'], 200);
            }
            return response()->json(["msg" => 'erreur while updating'], 500);
        }
        $row = new Supplier_Salesmanager_ShopOwner();
        $row->supplier_id = $supplier->id;
        $row->salesmanager_id = $salesmanager_id;
        $row->shop_owner_id = $shop_owner_id;
        $row->commission_amount = $commission_amount;
        if($row->save())
        {
            return response()->json(['msg' => 'data updated'], 404);
        }


       return response()->json(["msg"=>"error"],500);
    }
    
    public function getSalesManagerList()
    {
        $supplier = AuthController::me();
        return response()->json($supplier->salesmanagerToSupplier);
    }

    // public function getSupplierShop()
    // {

    // }


    // public function 
    public function accounSupplier(){
        $supplier = AuthController::me();

        $user=user::where('id',$supplier->id)->get();
        return response()->json($user);

}
public function suppliercategories(){
$supplier=AuthController::me();
return response()->json($supplier->getcategoriesThroughProducts()->distinct()->get(), 200);

}
}