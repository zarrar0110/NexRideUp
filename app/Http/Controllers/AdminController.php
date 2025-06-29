<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() { return view('admin.dashboard'); }
    public function users() { return view('admin.users'); }
    public function drivers() { return view('admin.drivers'); }
    public function passengers() { return view('admin.passengers'); }
    public function trips() { return view('admin.trips'); }
    public function payments() { return view('admin.payments'); }
    public function reports() { return view('admin.reports'); }
    public function settings() { return view('admin.settings'); }
} 