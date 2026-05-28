<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;

class DashboardPagesController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
