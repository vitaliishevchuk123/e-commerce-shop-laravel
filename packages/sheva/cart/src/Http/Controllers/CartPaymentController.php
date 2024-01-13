<?php

namespace Sheva\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Sheva\Cart\Actions\FinishCart;
use Sheva\Cart\Contracts\CartsStorage;
use Sheva\Cart\Events\CartWasFinished;
use Sheva\Cart\Facades\Cart;
use Sheva\Cart\Http\Resources\CartResource;
use CartPaymentModule\PaymentLog;
use CartPaymentModule\PaymentManager;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Sheva\PackagesContracts\Contracts\FinishedCart;

class CartPaymentController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function update(Request $request)
    {
        try {
            $status = app(\Sheva\Cart\Actions\UpdatePayment::class)->handle($request->all());
        } catch (ValidationException $validationException){
            throw $validationException;
        } catch (\Exception $exception){
            if (!app()->isProduction()){
                throw $exception;
            }

            app(Handler::class)->report($exception);

            return CartResource::make(null)->additional(['success' => false]);
        }

        return CartResource::make(null)->additional(['success' => $status,]);
    }

    public function callback(string $locale, string $method, Request $request)
    {
        try {
            $cart = Cart::getCartModel();
            app(PaymentManager::class)
            ->driver($method)
            ->checkCallback($request->all(), $cart);
        } catch (ValidationException $validationException){
            (new PaymentLog())->log($request->all(), 'paymentCallbackException');
            throw $validationException;
        } catch (\Exception $exception){
            (new PaymentLog())->log($request->all(), 'paymentCallbackException');
            if (!app()->isProduction()){
                throw $exception;
            }
            app(Handler::class)->report($exception);
            return CartResource::make(null)->additional(['success' => false]);
        }

        app(FinishCart::class)->handle($request->all());
        app(CartsStorage::class)->setUserUuid(Str::uuid()->toString());
        event(new CartWasFinished($cart, FinishedCart::TYPE_BY_BASKET));
        return response()->json([
            'success' => true,
            'redirect' => route('cart.show', [$locale]),
        ]);
    }
}
