<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $fillable = [
        'name',
        'email',
        'Addres',
        'Phone'
    ];

    public function PurchaseOrders():HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
