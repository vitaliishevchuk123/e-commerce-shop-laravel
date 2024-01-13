<?php

namespace App\Http\Responses;

use Sheva\Cart\Contracts\CartsSuccessViewResponse;

class CartSuccessResponse implements CartsSuccessViewResponse
{
    public function toResponse($request)
    {
        $orderId = $request->session()->get('orderId');

        if (is_null($orderId)) {
            return redirect(get_main_page_url());
        }

        return response()->view('site.pages.cart-success.index', compact('orderId'));
    }
}
