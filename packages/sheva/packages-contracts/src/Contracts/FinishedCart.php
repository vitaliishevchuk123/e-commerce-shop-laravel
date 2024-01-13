<?php

namespace Sheva\PackagesContracts\Contracts;

use Brick\Money\Money;
use Illuminate\Support\Collection;

interface FinishedCart
{
    public const TYPE_BY_BASKET = 1;
    public const TYPE_BY_ONE_CLICK = 2;
    public const TYPE_BY_ADMIN = 3;

    public function getClientName(): ?string;

    public function getClientLastName(): ?string;

    public function getClientSurName(): ?string;

    public function getClientPhoneNumber(): ?string;

    public function getClientEmail(): ?string;

    public function getClientComment(): ?string;

    public function getProducts(): Collection;

    public function getDeliveryAddressName(): ?string;

    public function getDeliveryCityName(): ?string;

    public function getDeliveryCarrier(): string;

    public function getDeliveryType(): string;

    public function getDeliveryCityCode(): ?string;

    public function getDeliveryAddressCode(): ?string;

    public function getId(): int;

    public function getTotalCount(): int;

    public function getPaymentAttributes(): array;

    public function getTotalPrice(): Money;
}
