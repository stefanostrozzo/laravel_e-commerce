<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Models\Category;
use App\Models\Product;
use App\Models\Contact;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where('status', 1)->get()->take(3);
        $categories = Category::get();
        $sproducts = Product::whereNotNull('sale_price')->where('sale_price', '<>', '')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured', 1)->inRandomOrder()->get()->take(8);
        $rcatecories = Category::inRandomOrder()->get()->take(2);
        return view('index', compact('slides', 'categories', 'sproducts' , 'fproducts' , 'rcatecories'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactStore(Request $request){
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:10',
            'comment' => 'required',
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->comment = $request->comment;
        $contact->save();
        
        return redirect()->back()->with('success', 'Your message has been sent successfully.');
    }
}
