<?php

namespace Sheva\Cart\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Sheva\Cart\Facades\Cart;

/** @mixin \Sheva\Cart\Models\Cart */
class CartResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'products_list_count' => Cart::getProductsListCount(),
            'products_quantity_count' => Cart::getProductsQuantityCount(),
            'products_total_price' => Cart::getProductsTotalPrice()->formatWith(app('money-formatter')),
            'products_total_discount_price' => Cart::getProductsDiscountTotalPrice()->formatWith(app('money-formatter')),
            'delivery_total_price' => Cart::getDeliveryPrice()->formatWith(app('money-formatter')),
            'total_price' => Cart::getTotalPrice()->formatWith(app('money-formatter')),
            'comment' => $this->comment,
            'cartUser' => CartUserResource::make($this->whenLoaded('cartUser')),
            'cartProducts' => CartProductResource::collection($this->whenLoaded('cartProducts')),
        ];
    }
}
