<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $nuevos = collect();
        $totalProductos = 0;

        if (Schema::hasTable('productos')) {
            $nuevos = Producto::orderByDesc('id')->take(8)->get();
            $totalProductos = Producto::count();
        } else {
            $nuevos = collect();
            $totalProductos = 0;
        }

        return view('home', compact('nuevos', 'totalProductos'));
    }
}
