<?php

namespace Sheva\Cart\Actions;

use Sheva\Cart\Facades\Cart;
use DeliveryModule\DeliveryManager;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateDelivery extends BaseCartUpdate
{
    public function handle(array $data): bool
    {
        Validator::make($data, [
            'delivery_carrier' => [
                'required',
                Rule::in(app(DeliveryManager::class)->getAvailableDrivers())
            ],
            'delivery_type' => [
                Rule::requiredIf(function () use ($data) {
                    return app(DeliveryManager::class)->driver($data['delivery_carrier'] ?? null)->types();
                }),
                Rule::in(array_keys(app(DeliveryManager::class)->driver($data['delivery_carrier'] ?? null)->types()))
            ],
        ])->validate();

        $validated = app(DeliveryManager::class)
            ->driver($data['delivery_carrier'])
            ->setType($data['delivery_type'])
            ->validateAdditionalData($data)
            ->getAttributes();

        $cart = $this->refreshCartModel();

        $cartDelivery = $cart->cartDelivery->fill($validated);
        $cartDelivery->delivery_price = Cart::getDeliveryPrice()->getMinorAmount();
        $status = $cartDelivery->save();

        Cart::fresh();

        return $status;
    }
}
