<?php

namespace Sheva\Cart\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Sheva\Cart\Models\CartProduct */
class CartProductResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'product_type' => $this->product_type,
            'quantity' => $this->quantity,
            'available_quantity' => $this->available_quantity,
            'name' => $this->name,
            'image' => $this->image,
            'buy_price' => $this->buy_price,
            'buy_delivery_price' => $this->buy_delivery_price,
            'buy_url' => $this->buy_url,
            'site_price' => $this->site_price,
            'site_delivery_price' => $this->site_delivery_price,
            'site_discount' => $this->site_discount,
            'created_at' => $this->created_at,
        ];
    }
}
