<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart',compact('items'));
    }

    public function addToCart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increaseCartQty($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function decreaseCartQty($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function removeItem($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function emptyCart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    public function applyCoupon(Request $request)
    {
        $couponCode = $request->coupon_code;
        if(isset($couponCode)){
            $coupon = Coupon::where('code',$couponCode)->where('expiry_date','>=', Carbon::today())
            ->where('cart_value','<=',Cart::instance('cart')->subtotal())->first();	
            if(!$coupon){
                return redirect()->back()->with('error','Coupon has expired or invalid');
            }else{
                Session::put('coupon',[
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('success','Coupon has been applied');
            }
        }else{
            return redirect()->back()->with('error','Invalid Coupon Code');
        }
    }

    public function calculateDiscount(){
        $discount = 0;
        if(Session::has('coupon')){
            if(Session::get('coupon')['type'] == 'fixed'){
                $discount = Session::get('coupon')['value'];
            }else{
                $discount = (Cart::instance('cart')->subtotal() * Session::get('coupon')['value'])/100;
            }

            $discountedTotal = Cart::instance('cart')->subtotal() - $discount;
            $discountTaxes = ($discountedTotal * config('cart.tax'))/100;
            $totalDiscountedPrice = $discountedTotal + $discountTaxes;

            Session::put('discount',[
                'discount' => number_format(floatval($discount), 2, '.', ''),
                'subtotal' => number_format(floatval($discountedTotal), 2, '.', ''),
                'tax' => number_format(floatval($discountTaxes), 2, '.', ''),
                'total' => number_format(floatval($totalDiscountedPrice), 2, '.', '')
            ]);
        }
    }
}
