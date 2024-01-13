<?php

namespace Sheva\Cart\Http\Responses;

class CartOneClickResponse implements \Sheva\Cart\Contracts\CartsOneClickResponse
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): \Symfony\Component\HttpFoundation\Response
    {
        return $request->wantsJson()
            ? response()->json(['success' => true])
            : redirect()->intended(route('cart.show'));
    }
}
