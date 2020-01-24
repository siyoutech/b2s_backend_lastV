<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    // public function products() {
    //     return $this->belongsToMany(Product::class, 'product_order', 'order_id', 'product_id');
    // }

    public function products() {
        return $this->belongsToMany(Product::class, 'product_order', 'order_id', 'product_id')->withPivot(['quantity']);;
    }
    // public function statuts() {
    //     return $this->belongsTo(Statut::class, 'statut_id');
    // }
    public function suppliers()
    {
        return $this->belongsTo(User::class,'supplier_id');
    }
    
    public function shopOwners()
    {
        return $this->belongsTo(User::class,'shop_owner_id');
    } 
    
     public function commissions()
    {
        return $this->hasOne(Commission::class);
    }
    public function depositesiyou(){
        return $this->belongsTo(Depositesiyou::class, 'supplier_id');
    }
    
    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
