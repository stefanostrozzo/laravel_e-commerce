<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

    public function removeCoupon()
    {
        Session::forget('coupon');
        Session::forget('discount');
        return redirect()->back()->with('success','Coupon has been removed');
    }

    public function checkout()
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }

        $address = Address::where('user_id',Auth::id())->where('isdefault',1)->first();
        return view('checkout',compact('address'));
    }

    public function placeOrder(Request $request)
    {
        $userId = Auth::user()->id;
        $address = Address::where('user_id',$userId)->where('isdefault',1)->first();

        if(!$address){
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:10',
                'zip' => 'required|numeric|digits:6',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required',
            ]);

            $newAddress = new Address();
            $newAddress->name = $request->name;
            $newAddress->phone = $request->phone;
            $newAddress->zip = $request->zip;
            $newAddress->state = $request->state;
            $newAddress->city = $request->city;
            $newAddress->address = $request->address;
            $newAddress->locality = $request->locality;
            $newAddress->landmark = $request->landmark;
            $newAddress->country = 'Italy';
            $newAddress->user_id = $userId;
            $newAddress->isdefault = 1;
            $newAddress->save();
        }

        $this->setAmountForCheckout();
        $order = new Order();
        $order->user_id = $userId;
        $order->subtotal = Session::get('checkout')['subtotal'];
        $order->discount = Session::get('checkout')['discount'];
        $order->tax = Session::get('checkout')['tax'];
        $order->total = Session::get('checkout')['total'];
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->locality = $address->locality;
        $order->address = $address->address;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->country = $address->country;
        $order->landmark = $address->landmark;
        $order->zip = $address->zip;

        $order->save();

        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }

        if($request->mode == 'card'){
            //TODO
        }elseif($request->mode == 'paypal'){
            //TODO
        }elseif($request->mode == 'cod'){
            $transaction = new Transaction();
            $transaction->user_id = $userId;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
            $transaction->save();
        }

        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discount');
        Session::put('orderId',$order->id);

        return view('confirm-order', compact('order'));
    }

    public function setAmountForCheckout()
    {
        if(Cart::instance('cart')->content()->count() == 0){
            Session::forget('checkout');
            return;
        }

        if(Session::has('coupon')){
            Session::put('checkout',[
                'discount' => Session::get('discount')['discount'],
                'subtotal' => Session::get('discount')['subtotal'],
                'tax' => Session::get('discount')['tax'],
                'total' => Session::get('discount')['total']
            ]);
        }else{
            Session::put('checkout',[
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total()
            ]);
        }
    }

    public function orderConfirmation()
    {
        if(Session::has('orderId')){
            $order = Order::find(Session::get('orderId'));
            return view('confirm-order', compact('order'));
        }
        
        return redirect()->route('cart.index');
    }
}
