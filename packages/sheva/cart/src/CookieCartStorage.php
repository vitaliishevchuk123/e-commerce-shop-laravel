<?php

namespace Sheva\Cart;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class CookieCartStorage implements Contracts\CartsStorage
{
    protected string $uuid;

    public function __construct(?string $uuid)
    {
        $this->uuid = is_null($uuid) ? Str::uuid()->toString() : $uuid;
    }

    public function getUserUuid(): string
    {
        return $this->uuid;
    }

    public function setUserUuid(string $uuid): Contracts\CartsStorage
    {
        $this->uuid = $uuid;

        Cookie::queue('uuid', $uuid, 99999);

        return $this;
    }
}
