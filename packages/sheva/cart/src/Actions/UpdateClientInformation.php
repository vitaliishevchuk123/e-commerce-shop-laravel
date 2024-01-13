<?php

namespace Sheva\Cart\Actions;

use Sheva\Cart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

class UpdateClientInformation extends BaseCartUpdate
{
    public function handle(array $data): bool
    {
        $validated = Validator::make($data, [
            'phone_number' => ['required', 'phone:AUTO'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'last_name' => [config('cart.fields.client.last_name') ? 'required' : 'nullable', 'string', 'min:1', 'max:255'],
            'surname' => [config('cart.fields.client.surname') ? 'required' : 'nullable', 'string', 'min:1', 'max:255'],
            'email' => [config('cart.fields.client.email') ? 'required' : 'nullable', 'email'],
            'comment' => ['nullable', 'string'],
        ])->validate();

        $cart = $this->refreshCartModel();

        $client = $cart->cartUser->fill($validated);
        $status = $client->save();

        Cart::fresh();

        return $status;
    }
}
