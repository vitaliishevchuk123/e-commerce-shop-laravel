<?php

namespace Sheva\Cart\Models;

use Brick\Money\Money;
use CartPaymentModule\PaymentManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Sheva\PackagesContracts\Contracts\FinishedCart;
use Sheva\Cart\Facades\Cart as CartFacade;

class Cart extends Model implements FinishedCart
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'cart_user_uuid',
        'cart_at',
        'cart_finish_at',
        'products_total_price',
        'products_discount_total_price',
        'total_price',
        'delivery_price',
        'comment',
    ];

    protected $casts = [
        'cart_at' => 'datetime',
        'cart_finish_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::addGlobalScope('currentCart', function (Builder $builder) {
            $builder
                ->whereNotNull('cart_at')
                ->whereNull('cart_finish_at');
        });
    }

    public function cartUser(): BelongsTo
    {
        return $this->belongsTo(CartUser::class)->withDefault([
            'uuid' => $this->cart_user_uuid,
        ]);
    }

    public function cartProducts(): HasMany
    {
        return $this->hasMany(CartProduct::class);
    }

    public function cartDelivery(): HasOne
    {
        return $this->hasOne(CartDelivery::class)->withDefault();
    }

    public function cartPayment(): HasOne
    {
        return $this->hasOne(CartPayment::class)->withDefault();
    }

    public function getClientName(): ?string
    {
        return $this->cartUser->name;
    }

    public function getClientLastName(): ?string
    {
        return $this->cartUser->last_name;
    }

    public function getClientSurName(): ?string
    {
        return $this->cartUser->surname;
    }

    public function getClientFullName(): ?string
    {
        $nameArr = [
            $this->getClientLastName(),
            $this->getClientName(),
            $this->getClientSurName()
        ];

        $clearNameArr = array_filter($nameArr);

        return implode(' ', $clearNameArr);
    }

    public function getClientPhoneNumber(): ?string
    {
        return $this->cartUser->phone_number;
    }

    public function getClientEmail(): ?string
    {
        return $this->cartUser->email;
    }

    public function getClientComment(): ?string
    {
        return $this->cartUser->comment;
    }

    public function getProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function getTotalCount(): int
    {
        return $this->cartProducts->sum->getQuantity();
    }

    public function getDeliveryAddressName(): ?string
    {
        return $this->cartDelivery->address_name;
    }

    public function getDeliveryCityName(): ?string
    {
        return $this->cartDelivery->city_name;
    }

    public function getDeliveryCarrier(): string
    {
        return $this->cartDelivery->carrier;
    }

    public function getDeliveryType(): string
    {
        return $this->cartDelivery->type;
    }

    public function getDeliveryCityCode(): ?string
    {
        return $this->cartDelivery->city_code;
    }

    public function getDeliveryAddressCode(): ?string
    {
        return $this->cartDelivery->address_code;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setOrderId(): int
    {
        $maxOrderId = Cart::withoutGlobalScope('currentCart')->max('order_id') ?? 9999;
        $orderId = $maxOrderId + 1;

        $this->update(['order_id' => $orderId]);

        return $orderId;
    }

    public function getPaymentAttributes(): array
    {
        return app(PaymentManager::class)
            ->driver($this->cartPayment->method)
            ->setMinorAmount($this->cartPayment->amount)
            ->setStatus($this->cartPayment->status)
            ->getAttributes();
    }

    /**
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */
    public function getTotalPrice(): Money
    {
        return Money::ofMinor($this->total_price, CartFacade::getSiteCurrency());
    }

    public function getPaymentAmountPrice(): Money
    {
        return Money::ofMinor($this->cartPayment->amount, CartFacade::getSiteCurrency());
    }
}
