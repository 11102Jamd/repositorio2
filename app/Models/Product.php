<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * Definimos la propiedad table que es la tabla a la que el modelo se conectara
     */
    protected $table = 'product';


    /**
     * Las propiedades asignables en masa
     */
    protected $fillable = [
        'ProductName',
        'InitialQuantity',
        'CurrentStock',
        'UnityPrice'
    ];

    public function OrderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

}
