<?php

namespace Sheva\Cart\Actions;

use Sheva\Cart\Facades\Cart;

class FinishCart extends BaseCartUpdate
{
    public function handle(array $data, bool $paymentWidget = false): bool
    {
        if (Cart::getProductsListCount() === 0){
            return false;
        }

        $cart = $this->refreshCartModel();

        if (!$paymentWidget) {
            $cart->cart_at = null;
            $cart->cart_finish_at = now();
            $cart->status_sync1c = \Sheva\Cart\Models\Cart::STATUS_SYNC1C_WAITING;
        }

        $cart->products_total_price = Cart::getProductsTotalPrice()->getMinorAmount()->toInt();
        $cart->products_discount_total_price = Cart::getProductsDiscountTotalPrice()->getMinorAmount()->toInt();
        $cart->total_price = Cart::getTotalPrice()->getMinorAmount()->toInt();
        $cart->delivery_price = Cart::getDeliveryPrice()->getMinorAmount()->toInt();

        return $cart->save();
    }
}
