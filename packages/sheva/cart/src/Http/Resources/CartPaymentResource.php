<?php

namespace Sheva\Cart\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Sheva\Cart\Models\CartPayment */
class CartPaymentResource extends JsonResource
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
            'payment_id' => $this->payment_id,
            'method' => $this->method,
            'status' => $this->status,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
        ];
    }
}
