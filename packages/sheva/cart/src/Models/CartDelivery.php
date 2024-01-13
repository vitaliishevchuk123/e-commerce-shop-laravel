<?php

namespace Sheva\Cart\Models;

use DeliveryModule\DeliveryManager;
use Illuminate\Database\Eloquent\Model;

class CartDelivery extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'carrier',
        'type',
        'delivery_price',
        'country_code',
        'city_name',
        'city_code',
        'address_name',
        'address_code',
    ];

    public function getDriver(): \DeliveryModule\Deliveries\Delivery
    {
        return app(DeliveryManager::class)
            ->driver($this->carrier)
            ->setType($this->type)
            ->setCity($this->city_code, $this->city_name)
            ->setCity($this->address_code, $this->address_name);
    }
}
