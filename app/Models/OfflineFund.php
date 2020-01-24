<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineFund extends Model {

    protected $fillable = [];

    protected $dates = [];
    protected $table = 'offline_funds';

    public static $rules = [
        // Validation rules
    ];

    public function supplier(){
        return $this->belongsTo(User::class, 'supplier_id');
    }
    public function shops(){
        return $this->belongsTo(User::class, 'shop_owner_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function deposite()
    {
        return $this->belongsTo(DepositeShop::class,'supplier_id','shop_owner_id');
    }
}
