<?php

namespace Sheva\Cart\Http\Responses;

use Sheva\Cart\Contracts\CartsViewResponse;
use Illuminate\Contracts\Support\Responsable;

class SimpleViewResponse implements CartsViewResponse
{
    /**
     * The name of the view or the callable used to generate the view.
     *
     * @var callable|string
     */
    protected $view;

    /**
     * Create a new response instance.
     *
     * @param  callable|string  $view
     */
    public function __construct($view)
    {
        $this->view = $view;
    }

    public function toResponse($request)
    {
        if (! is_callable($this->view) || is_string($this->view)) {
            return view($this->view, ['request' => $request]);
        }

        $response = call_user_func($this->view, $request);

        if ($response instanceof Responsable) {
            return $response->toResponse($request);
        }

        return $response;
    }
}
