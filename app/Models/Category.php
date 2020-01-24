<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function getParentCategory() {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function getChildCategories() {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
    
    public function subCategories() {
        return $this->hasMany(Category::class, 'parent_category_id');
    }


    public function supplier() {
        return $this->belongsToMany(user::class,'category_user','supplier_id','category_id');
    }
}
