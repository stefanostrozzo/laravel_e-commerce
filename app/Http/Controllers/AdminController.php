<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function brands(){
        $brands = Brand::orderBy('id','desc')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function addBrand(){
        return view('admin.brand-add');
    }

    public function brandStore(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $fileExtension = $request->file('image')->extension();
        $fileName = Carbon::now()->timestamp.'.'.$fileExtension;
        $this->generateBrandThumbnailsImage($image,  $fileName);
        $brand->image = $fileName;
        $brand->save();

        return redirect()->route('admin.brands')->with('status','Brand added succesfully');
    }

    public function brandEdit($id){
        $brand = Brand::find($id);
        return view('admin.brand-edit',compact('brand'));
    }

    public function brandUpdate(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = Brand::find($request->id);

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }

            $image = $request->file('image');
            $fileExtension = $request->file('image')->extension();
            $fileName = Carbon::now()->timestamp.'.'.$fileExtension;
            $this->generateBrandThumbnailsImage($image,  $fileName);
            $brand->image = $fileName;
        }
        
        $brand->save();

        return redirect()->route('admin.brands')->with('status','Brand updated succesfully');
    }

    public function brandDelete($id){
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','Brand deleted succesfully');
    }




    public function categories(){
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view('admin.categories',compact('categories'));
    }

    public function addCategory(){
        return view('admin.category-add');
    }

    public function categoryStore(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $image = $request->file('image');
        $fileExtension = $request->file('image')->extension();
        $fileName = Carbon::now()->timestamp.'.'.$fileExtension;
        $this->generateCategoryThumbnailsImage($image,  $fileName);
        $category->image = $fileName;
        $category->save();

        return redirect()->route('admin.categories')->with('status','Cateogry added succesfully');
    }

    public function categoryEdit($id){
        $category = Category::find($id);
        return view('admin.category-edit',compact('category'));
    }

    public function categoryUpdate(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = Category::find($request->id);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/categories').'/'.$category->image)){
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }

            $image = $request->file('image');
            $fileExtension = $request->file('image')->extension();
            $fileName = Carbon::now()->timestamp.'.'.$fileExtension;
            $this->generateCategoryThumbnailsImage($image,  $fileName);
            $category->image = $fileName;
        }
        
        $category->save();

        return redirect()->route('admin.categories')->with('status','Category updated succesfully');
    }

    public function categoryDelete($id){
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image)){
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Category deleted succesfully');
    }


    public function products(){
        $products = Product::orderBy('created_at','desc')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function addProduct(){
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();

        return view('admin.products-add', compact('categories','brands'));
    }

    public function productStore(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $currentTs = Carbon::now()->timestamp;

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = $currentTs.'.'.$image->getClientOriginalExtension();
            $this->generateProductThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images')){
            $allowedfileExtension = ['png','jpg','jpeg'];
            $files = $request->file('images');
            foreach($files as $file){
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if($check){
                    $gFileName = $currentTs.'-'.$counter.'.'.$extension;
                    $this->generateProductThumbnailsImage($file, $gFileName);
                    array_push($gallery_arr, $gFileName);
                    $counter++;
                }
            }
            $gallery_images = implode(',',$gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status','Product added succesfully');
    }

    public function productEdit($id){
        $product = Product::find($id);
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();

        return view('admin.products-edit', compact('product','categories','brands'));
    }

    public function productUpdate(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,'.$request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $currentTs = Carbon::now()->timestamp;

        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/products').'/'.$product->image)){
                File::delete(public_path('uploads/products').'/'.$product->image);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
            }
            $image = $request->file('image');
            $imageName = $currentTs.'.'.$image->getClientOriginalExtension();
            $this->generateProductThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images')){
            foreach(explode(',',$product->images) as $ofile){
                if(File::exists(public_path('uploads/products').'/'.$ofile)){
                    File::delete(public_path('uploads/products').'/'.$ofile);
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)){
                    File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
                }
            }

            $allowedfileExtension = ['png','jpg','jpeg'];
            $files = $request->file('images');
            foreach($files as $file){
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if($check){
                    $gFileName = $currentTs.'-'.$counter.'.'.$extension;
                    $this->generateProductThumbnailsImage($file, $gFileName);
                    array_push($gallery_arr, $gFileName);
                    $counter++;
                }
            }
            $gallery_images = implode(',',$gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status','Product updated succesfully');
    }

    public function productDelete($id){
        $product = Product::find($id);
        if(File::exists(public_path('uploads/products').'/'.$product->image)){
            File::delete(public_path('uploads/products').'/'.$product->image);
        }
        if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
        }

        foreach(explode(',',$product->images) as $ofile){
            if(File::exists(public_path('uploads/products').'/'.$ofile)){
                File::delete(public_path('uploads/products').'/'.$ofile);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)){
                File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
            }
        }

        $product->delete();

        return redirect()->route('admin.products')->with('status','Product deleted succesfully');
    }


    public function generateBrandThumbnailsImage($image, $imageName){
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124,124,'top');
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function generateCategoryThumbnailsImage($image, $imageName){
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124,124,'top');
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function generateProductThumbnailsImage($image, $imageName){

        $destinationPathForThumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        $img = Image::read($image->path());
        $img->cover(540,689,'top');
        $img->resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->resize(104,104,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathForThumbnail.'/'.$imageName);
    }

    public function coupons(){

        $coupons = Coupon::orderBy('expiry_date','DESC')->paginate(12);   
        return view('admin.coupons', compact('coupons'));    
    }

    public function addCoupon(){
        return view('admin.coupon-add');
    }

    public function storeCoupon(Request $request){
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);

        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('status','Coupon added succesfully');
    }

    public function editCoupon($id){
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit', compact('coupon'));
    }

    public function updateCoupon(Request $request){
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);

        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('status','Coupon updated succesfully');
    }

    public function deleteCoupon($id){
        $coupon = Coupon::find($id);
        $coupon->delete();

        return redirect()->route('admin.coupons')->with('status','Coupon deleted succesfully');
    }

    public function orders(){
        $orders = Order::orderBy('created_at','DESC')->paginate(12);
        return view('admin.orders', compact('orders'));
    }

    public function orderDetails($id){
        $order = Order::find($id);
        $orderItems  = OrderItem::where('order_id',$id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id',$id)->first();
        
        return view('admin.order-details', compact('order','orderItems','transaction'));
    }

}
