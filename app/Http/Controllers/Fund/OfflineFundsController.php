<?php namespace App\Http\Controllers\fund;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OfflineFund;
use App\Models\DepositeShop;
use App\Http\Controllers\AuthController;

use DateTime;
class OfflineFundsController extends Controller {

  public function fundslist(){
    $supplier=AuthController::me();

    $fund=OfflineFund::where('supplier_id', $supplier->id)->get();
    return response()->json($fund);
  }
  public function reload(Request $request){
    $supplier=AuthController::me();
      $funds=OfflineFund::where('supplier_id', $supplier->id)->get();
      foreach($funds as $fund){
      if((new DateTime) >= $fund->paiment_date ){
        $fund->validation=1;
        $fund->save();

        $order=Order::where('id',$fund->order_id)->update(['status_id'=>1]);
        $amount=DepositeShop::where([['supplier_id', $supplier->id],['shop_owner_id', $fund->shop_owner_id]])->decrement('amount', $fund->amount);
        
   
      
       return response()->json(["msg"=>"all done"]);
  }
  return response()->json(["msg"=>"error"]);
  }
}
}
