<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function about()
    {
        return Inertia::render('User/About');
    }
}
