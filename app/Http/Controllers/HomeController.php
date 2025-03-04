<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where('status', 1)->get()->take(3);
        $categories = Category::get();
        return view('index', compact('slides', 'categories'));
    }
}
