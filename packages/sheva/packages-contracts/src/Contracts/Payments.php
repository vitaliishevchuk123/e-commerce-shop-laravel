<?php

namespace Sheva\PackagesContracts\Contracts;

use Sheva\PackagesContracts\Contracts\FinishedCart;

interface Payments
{
    /**
     * Назва оплати
     */
    public function getName(): string;

    /**
     * Перевірка обов'язкових даних від користувача при виборі цього способу оплати
     */
    public function validateAdditionalData(array $data): Payments;

    /**
     * Масив даних про оплату
     */
    public function getAttributes(): array;

    /**
     * Встановити статус оплати
     */
    public function setStatus(string $status): Payments;

    /**
     * Статус оплати
     */
    public function getStatus(): string;

    /**
     * Задати суму оплати в копійках
     */
    public function setMinorAmount(int $minorAmount): Payments;

    /**
     * Сума оплати в копійках
     */
    public function getMinorAmount(): int;

    /**
     * Урл переходу на систему оплати
     */
    public function redirect(): ?string;

    public function getDriver(): ?string;

    /**
     * Обробка даних які пришли від системи оплати
     */
    public function checkCallback(array $data, FinishedCart $cart): bool;
}
