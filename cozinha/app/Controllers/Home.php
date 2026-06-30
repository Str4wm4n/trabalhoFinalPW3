<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('pedidos');
    }

    public function detalhes($id): string
    {
        return view('detalhes', ['id' => $id]);
    }
}
