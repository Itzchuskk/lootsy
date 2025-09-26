<?php

namespace App\Http\Controllers;

use App\Models\Producto;

class HomeController extends Controller
{
    public function index()
    {
        $nuevos = Producto::orderByDesc('id')->take(8)->get();
        $totalProductos = Producto::count();

        return view('home', compact('nuevos', 'totalProductos'));
    }
}
