<?php

namespace Sheva\Cart\Models;

use CartPaymentModule\PaymentManager;
use CartPaymentModule\Payments\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class CartPayment extends Model
{
    protected $fillable = [
        'cart_id',
        'payment_id',
        'method',
        'amount',
        'currency',
        'status',
    ];

    public function getDriver(): PaymentMethod
    {
        return app(PaymentManager::class)
            ->driver($this->method);
    }

    public function setPaymentId(): int
    {
        $maxPaymentId = self::max('payment_id') ?? 100000;
        $paymentId = $maxPaymentId + 1;
        $this->update(['payment_id' => $paymentId]);
        return $paymentId;
    }
}
