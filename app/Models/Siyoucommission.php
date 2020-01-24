<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siyoucommission extends Model {

    protected $fillable = [];

    protected $dates = [];
protected $table = 'siyoucommission';
    public static $rules = [
        // Validation rules
    ];


    public function users()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}
