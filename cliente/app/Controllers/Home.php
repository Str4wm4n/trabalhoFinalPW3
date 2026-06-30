<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('inicio');
    }

    public function produtos(): string
    {
        return view('produtos');
    }

    public function carrinho(): string
    {
        return view('carrinho');
    }

    public function checkout(): string
    {
        return view('checkout');
    }

    public function nota(): string
    {
        return view('nota');
    }
}
