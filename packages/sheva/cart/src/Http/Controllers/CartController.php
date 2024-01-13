<?php

namespace Sheva\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Sheva\Cart\Actions\FinishCart;
use Sheva\Cart\Actions\StoreOneClick;
use Sheva\Cart\Actions\UpdateClientInformation;
use Sheva\Cart\Actions\UpdateDelivery;
use Sheva\Cart\Actions\UpdatePayment;
use Sheva\Cart\Contracts\CartsOneClickResponse;
use Sheva\Cart\Contracts\CartsStorage;
use Sheva\Cart\Contracts\CartsSuccessViewResponse;
use Sheva\Cart\Contracts\CartsViewResponse;
use Sheva\Cart\Events\CartWasFinished;
use Sheva\Cart\Facades\Cart;
use Sheva\Cart\Http\Resources\CartResource;
use Sheva\PackagesContracts\Contracts\WithWidget;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Sheva\PackagesContracts\Contracts\FinishedCart;

class CartController extends Controller
{
    /**
     * Cart page
     */
    public function index()
    {
        return app(CartsViewResponse::class);
    }

    /**
     * Displaying a complete order information
     */
    public function show()
    {
        return app(CartsSuccessViewResponse::class);
    }

    /**
     * Saving the order through the shopping cart
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $requestData = $request->all();

        if (count($requestData) !== 0) {
            app(UpdateClientInformation::class)->handle($requestData);
            app(UpdateDelivery::class)->handle($requestData);
            app(UpdatePayment::class)->handle($requestData);
        }

        if (Cart::getPaymentMethod() instanceof WithWidget){
            $status = app(FinishCart::class)->handle($requestData, true);
            return CartResource::make(null)
                ->additional([
                    'success' => $status,
                    'widget' => true,
                    'paymentMethod' => Cart::getPaymentMethod()->getDriver(),
                    'widgetData' => Cart::getPaymentMethod()->getWidgetData(Cart::getCartModel()),
                ]);
        } else {
            $status = app(FinishCart::class)->handle($requestData);
            $redirect = Cart::getPaymentMethod()->redirect();
        }
        app(CartsStorage::class)->setUserUuid(Str::uuid()->toString());

        if (is_null($redirect)) {
            event(new CartWasFinished(Cart::getCartModel(), FinishedCart::TYPE_BY_BASKET));
        }
        return CartResource::make(null)
            ->additional([
                'success' => $status,
                'redirect' => $redirect ?: route('cart.show', [$locale]),
            ]);
    }

    /**
     * Create an order in one click
     * @throws \Exception
     */
    public function storeOneClick(Request $request)
    {
        $cartModel = app(StoreOneClick::class)->handle($request->all());

        event(new CartWasFinished($cartModel, FinishedCart::TYPE_BY_ONE_CLICK));

        return app(CartsOneClickResponse::class, ['locale' => $locale]);
    }
}
