<?php

namespace Sheva\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Sheva\Cart\Actions\AddProductToCart;
use Sheva\Cart\Actions\ChangeProductQuantityInCart;
use Sheva\Cart\Actions\RemoveProductFromCart;
use Sheva\Cart\Http\Resources\CartProductResource;
use Sheva\Cart\Http\Resources\CartResource;

class CartProductController extends Controller
{
    /**
     * Adding a product to the cart
     *
     * @throws Exception|\Throwable
     */
    public function store(Request $request)
    {
        try {
            $product = app(AddProductToCart::class)->handle($request->all());
        } catch (ValidationException $validationException) {
            throw $validationException;
        } catch (Exception $exception) {
            if (!app()->isProduction()) {
                throw $exception;
            }

            app(Handler::class)->report($exception);
            throw new RuntimeException('Failed add product to cart');

        }
        return CartProductResource::make($product);
    }

    /**
     * Updating the number of products in the cart
     * @throws Exception|\Throwable
     */
    public function update(Request $request)
    {
        try {
            $product = app(ChangeProductQuantityInCart::class)->handle($request->all());
        } catch (ValidationException $validationException) {
            throw $validationException;
        } catch (Exception $exception) {
            if (!app()->isProduction()) {
                throw $exception;
            }

            app(Handler::class)->report($exception);

            return CartResource::make(null)->additional(['success' => false]);
        }

        return CartResource::make($product)->additional(['success' => true]);
    }

    /**
     * Removing the product from the cart
     *
     * @throws Exception|\Throwable
     */
    public function destroy(Request $request)
    {
        try {
            $status = app(RemoveProductFromCart::class)->handle($request->all());
        } catch (ValidationException $validationException) {
            throw $validationException;
        } catch (Exception $exception) {
            if (!app()->isProduction()) {
                throw $exception;
            }

            app(Handler::class)->report($exception);

            return CartResource::make(null)->additional(['success' => false]);
        }
        return CartResource::make(null)->additional(['success' => $status]);
    }
}
