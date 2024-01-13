<?php

namespace Sheva\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Sheva\Cart\Facades\Cart;
use Sheva\Cart\Helpers\DeliveryPrice;
use Sheva\Cart\Http\Resources\CartResource;
use DeliveryModule\DeliveryManager;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CartDeliveryController extends Controller
{
    /**
     * Оновлення всіх даних доставки
     *
     * @throws \Exception|\Throwable
     */
    public function update(string $locale, Request $request)
    {
        try {
            $status = app(\Sheva\Cart\Actions\UpdateDelivery::class)->handle($request->all());
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

    /**
     * Обрахунок вартості доставки для вибраного типу
     *
     * @throws \Exception|\Throwable
     */
    public function calculate(string $locale, Request $request)
    {
        try {
            $data = $request->all();
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
            $cart = Cart::getCartModel();
            $delivery_total_price = app()->make(DeliveryPrice::getDeliveryPriceAbstract($data['delivery_carrier']))->handle($cart, 'UAH', $data['delivery_type']);
        } catch (ValidationException $validationException){
            throw $validationException;
        } catch (\Exception $exception){
            if (!app()->isProduction()){
                throw $exception;
            }

            app(Handler::class)->report($exception);

            return  response()->json(['success' => false]);
        }

        return response()->json([
            'success' => true,
            'delivery_total_price' => $delivery_total_price->formatWith(app('money-formatter')),
            'total_price' => Cart::getProductsTotalPrice()->plus($delivery_total_price)->getAmount()->toFloat(),
        ]);
    }
}
