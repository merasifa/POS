<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function sales()
    {
        return view('sales.sales');
    }
}
