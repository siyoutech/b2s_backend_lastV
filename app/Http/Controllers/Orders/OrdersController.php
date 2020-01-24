<?php namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OfflineFund;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use App\Models\commission;
use App\Models\Product;
use App\Models\User;
use App\Models\Tarif;
use App\Models\Supplier_Salesmanager_ShopOwner;
use App\Models\Siyoucommission;
use App\Models\Depositesiyou;


use App\Http\Controllers\AuthController;


use App\Http\Controllers\Controller;
class OrdersController extends Controller {

  public function addOrder(Request $request)
  {
    $shop=AuthController::me();
      $payment_type=$request->input('payment_type');
      $company_id=$request->input('company_id');
      $supplier_id=$request->input('supplier_id');
      $payment_date=$request->input('payment_date');
      $delivery_date=$request->input('delivery_date');
      $delivery_adress=$request->input('delivery_adress');
      $delivery_country=$request->input('delivery_country');
      $delivery_type=$request->input('delivery_type');
      $order_weight=$request->input('order_weight');
      $order_price=$request->input('order_price');
      $orderlist = $request->input('orderlist');
     
      
      foreach($orderlist as $order)
      {  
          $newOrder= new Order();
          $newOrder->shop_owner_id=$shop->id;
          $newOrder->payment_type=$payment_type;
          $newOrder->supplier_id=$supplier_id;
          $newOrder->company_id=$company_id;
          $newOrder->payment_date=$payment_date;
          $newOrder->delivery_date=$delivery_date;
          $newOrder->delivery_adress=$delivery_adress;
          $newOrder->delivery_country=$delivery_country;
          $newOrder->delivery_type=$delivery_type;
          $newOrder->order_weight=$order_weight;
          $newOrder->order_price=$order_price;
          $newOrder->status_id=2;
          $newOrder->order_date= Carbon::now();
  
          $tarif=Tarif::where('company_id',$newOrder->company_id)
          ->where('kg',$newOrder->order_weight)->value('price');
          $newOrder->delivery_price=$tarif;
                  
          if($newOrder->save())
          {
      
              foreach ($order['items'] as $products) 
              {
                  $newOrder->products()->attach($products['id'],['quantity'=>$products['quantity']]);
              }
              $fund=new OfflineFund(); 
            if($newOrder->payment_type == 'offline'){
              $fund->supplier_id=$newOrder->supplier_id;
              $fund->shop_owner_id=$newOrder->shop_owner_id;
              $fund->paiment_date=$newOrder->payment_date;
              $fund->amount=$newOrder->order_price;
              $fund->order_id=$newOrder->id;
              $fund->validation=0;
              $fund->save();
            } if($newOrder->payment_type != 'offline'){
            $newOrder->status_id=1;
            $per=$newOrder->order_price/100;
            $SMCommission=Supplier_Salesmanager_ShopOwner::where('shop_owner_id',$shop->id)
            ->where('supplier_id',$newOrder->supplier_id)->value('commission_amount');
            $newOrder->commission=$SMCommission*$per;
            $SiyouCommission=Siyoucommission::where('supplier_id',$newOrder->supplier_id)->value('commission_percent');
            $newOrder->siyoucommission=$SiyouCommission*$per;  
            $newOrder->save();
            $amount=Depositesiyou::where('supplier_id',$newOrder->supplier_id)->decrement('amount', $newOrder->siyoucommission);
            }
          }  

      }  

     
          return response()->json(["msg"=>"order has been sent to supplier !"], 200);
      
      return response()->json(["msg" => "ERROR !!"]);
  }
  public function ValidateOrderBySupplier($order_id)
    {
      $supplier=AuthController::me();
        $order= Order::with('products')->find($order_id);
        $order->status_id=3;//shipped//
        $per=$order->order_price/100;
        $SMCommission=Supplier_Salesmanager_ShopOwner::where('shop_owner_id',$order->shop_owner_id)
        ->where('supplier_id',$supplier->id)->value('commission_amount');
        $order->commission=$SMCommission*$per;
        $SiyouCommission=Siyoucommission::where('supplier_id',$supplier->id)->value('commission_percent');
        $order->siyoucommission=$SiyouCommission*$per;
        $amount=Depositesiyou::where('supplier_id',$newOrder->supplier_id)->decrement('amount', $newOrder->siyoucommission);
        $order->save();

        foreach($order['products'] as $orderProduct)
        {
            $product=product::find($orderProduct['pivot']->product_id);
            $product->quantity= $product->quantity-$orderProduct['pivot']->quantity;
            $product->save();
        }
        return response()->json(['msg'=>'order has been validated '], 200);
        return response()->json(['msg'=>'thereis a problem'],500);
    }

public function GetSupplierorderlist(){
  $supplier = AuthController::me();
          $order=Order::where([['delivery_date', '<=', (new DateTime)],['supplier_id',$supplier->id]])->update(['status_id'=>4]);//delivered//
        $orderlist=Order::where('supplier_id',$supplier->id)->get();
        return response()->json($orderlist);
        
        return response()->json("ERROR");

}
public function ValidateOrderByShop($order_id)
{
    $order= Order::with('products')->find($order_id);
    $order->status_id=4;//delivered//
    $order->save();

    return response()->json(['msg'=>'the order has been delivered'], 200);
}

public function Getpaidorderlist(){
  $supplier = AuthController::me();

        $paidorderList=Order::with('products')->where('supplier_id',$supplier->id)->where('status_id', 1)->get();
 
        return response()->json($paidorderList);
        
        return response()->json("ERROR");
}
public function tobevalidatedorders(){
  $supplier = AuthController::me();

        $tobevalidated=Order::with('products')->where('supplier_id',$supplier->id)->where('status_id', 2 )->get();
 
        return response()->json($tobevalidated);
        
        return response()->json("ERROR");
}

}
