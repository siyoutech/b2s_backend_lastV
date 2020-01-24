<?php namespace App\Models\Traits;
use App\Models\User;
trait SalesManager
{
    
    public function salesmanagerToSupplier()
    {
        return $this->belongsToMany(User::class, 'supplier_salesmanager_shop_owner', 'supplier_id', 'salesmanager_id')->withTimestamps()->withPivot('shop_owner_id');
    }

    public function salesmanagerToShop()
    {
        return $this->belongsToMany(User::class, 'supplier_salesmanager_shop_owner', 'shop_owner_id', 'salesmanager_id')->withTimestamps()->withPivot('supplier_id');
    }
}