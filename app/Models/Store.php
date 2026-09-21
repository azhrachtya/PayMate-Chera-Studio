<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $primaryKey = 'store_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['store_id', 'store_name', 'address', 'umr'];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'store_id', 'store_id');
    }
    
    public function getRouteKeyName()
    {
        return 'store_id';
    }
}