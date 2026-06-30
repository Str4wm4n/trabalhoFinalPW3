<?php

namespace App\Controllers\Page;

use App\Controllers\BaseController;

class AdminController extends BaseController
{
    public function login()
    {
        return view('admin/login');
    }

    public function dashboard()
    {
        return view('admin/dashboard');
    }

    public function vendas()
    {
        return view('admin/vendas');
    }

    public function consumo()
    {
        return view('admin/consumo');
    }

    public function usuarios()
    {
        return view('admin/usuarios');
    }
}
