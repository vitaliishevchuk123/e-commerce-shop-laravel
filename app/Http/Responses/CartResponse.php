<?php

namespace App\Http\Responses;

use App\Helpers\Breadcrumbs;
use Inertia\Inertia;
use Sheva\Cart\Contracts\CartsViewResponse;
use Sheva\Cart\Facades\Cart;
use Sheva\Cart\Http\Resources\CartResource;

class CartResponse implements CartsViewResponse
{
    public function toResponse($request)
    {
        $cart = Cart::getCartModel();

        if ($cart->cartProducts->count() === 0) {
            return redirect()->route('home');
        }

        return Inertia::render('Cart/Index', [
            'cart' => CartResource::make($cart),
            'breadcrumbs' => $this->getBreadcrumbs()->crumbs(),
        ])->toResponse($request);
    }

    private function getBreadcrumbs(): Breadcrumbs
    {
        return app(Breadcrumbs::class)
            ->add(__('Home'), route('home'))
            ->add(__('Cart'));
    }
}
