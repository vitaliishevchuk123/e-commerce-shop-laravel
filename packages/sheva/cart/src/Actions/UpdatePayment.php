<?php

namespace Sheva\Cart\Actions;

use Sheva\Cart\Facades\Cart;
use CartPaymentModule\PaymentManager;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdatePayment extends BaseCartUpdate
{
    public function handle(array $data): bool
    {
        $validated = Validator::make($data, [
            'payment_method' => [
                'required',
                Rule::in(app(PaymentManager::class)->getAvailableDrivers())
            ],
        ])->validate();

        $cart = $this->refreshCartModel();
        $cartPayment = $cart->cartPayment->fill(
            app(PaymentManager::class)
                ->driver($validated['payment_method'])
                ->getAttributes()
        );
        $status = $cartPayment->save();

        Cart::fresh();

        return $status;
    }
}
