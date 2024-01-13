<?php

namespace Sheva\Cart\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Sheva\Cart\Models\CartDelivery */
class CartDeliveryResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'carrier' => $this->carrier,
            'type' => $this->type,
            'delivery_price' => $this->delivery_price,
            'delivery_at' => $this->delivery_at,
            'country_code' => $this->country_code,
            'city_name' => $this->city_name,
            'city_code' => $this->city_code,
            'street' => $this->street,
            'address_code' => $this->address_code,
            'house' => $this->house,
            'flat' => $this->flat,
            'floor' => $this->floor,
            'has_elevator' => $this->has_elevator,
            'company' => $this->company,
            'vat' => $this->vat,
        ];
    }
}
