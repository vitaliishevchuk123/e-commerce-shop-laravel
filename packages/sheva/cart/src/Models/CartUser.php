<?php

namespace Sheva\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartUser extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'last_name',
        'surname',
        'phone_number',
        'email',
        'another_recipient',
        'recipient_name',
        'recipient_last_name',
        'recipient_surname',
        'recipient_phone_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('cart.user_model'), 'user_id');
    }

    public function hasAnotherRecipient(): bool
    {
        return (bool)$this->another_recipient;
    }

    public function getClientFullName(): ?string
    {
        $nameArr = [
            $this->last_name,
            $this->name,
            $this->surname,
        ];

        $clearNameArr = array_filter($nameArr);

        return implode(' ', $clearNameArr);
    }
}
