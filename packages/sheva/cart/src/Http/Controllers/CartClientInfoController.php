<?php

namespace Sheva\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Sheva\Cart\Actions\UpdateClientInformation;
use Sheva\Cart\Http\Resources\CartResource;

class CartClientInfoController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function update(Request $request)
    {
        try {
            $status = app(UpdateClientInformation::class)->handle($request->all());
        } catch (ValidationException $validationException){
            throw $validationException;
        } catch (\Exception $exception){
            if (!app()->isProduction()){
                throw $exception;
            }
            app(Handler::class)->report($exception);

            return CartResource::make(null)->additional(['success' => false]);
        }

        return CartResource::make(null)->additional(['success' => $status]);
    }
}
