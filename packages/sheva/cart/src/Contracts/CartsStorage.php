<?php

namespace Sheva\Cart\Contracts;

interface CartsStorage
{
    public function getUserUuid(): string;

    public function setUserUuid(string $uuid): self;
}
