<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositeShop extends Model {

    protected $fillable = [];

    protected $dates = [];
    protected $table = 'deposit_shops';

    public static $rules = [
        // Validation rules
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'supplier_id','shop_owner_id');
    }
    public function funds()
    {
        return $this->belongsTo(OfflineFund::class, 'supplier_id','shop_owner_id');
    }
}
