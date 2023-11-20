<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwitchLocaleController extends Controller
{
    public function __invoke(Request $request, $language)
    {
        session()->put('locale', $language);
        return redirect()->back();
    }
}
