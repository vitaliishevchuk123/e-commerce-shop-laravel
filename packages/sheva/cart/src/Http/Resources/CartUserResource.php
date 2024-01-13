<?php

namespace Sheva\Cart\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Sheva\Cart\Models\CartUser */
class CartUserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var JsonResource $userResource */
        $userResource = config('cart.user_resource');

        $data = [
            'id' => $this->id,
            'user' => $userResource::make($this->whenLoaded('user')),
            'name' => $this->name,
            'last_name' => $this->last_name,
            'surname' => $this->surname,
            'phone_number' => $this->phone_number,
            'another_recipient' => $this->hasAnotherRecipient(),
        ];

        if ($this->hasAnotherRecipient()) {
            $data += [
                'recipient_name' => $this->recipient_name,
                'recipient_last_name' => $this->recipient_last_name,
                'recipient_surname' => $this->recipient_surname,
                'recipient_phone_number' => $this->recipient_phone_number,
            ];
        }

        return $data;
    }
}
