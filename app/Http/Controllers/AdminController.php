<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;


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
            'slug' => 'required|unique:brands,slug'.$request->id,
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

    public function generateBrandThumbnailsImage($image, $imageName){
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124,124,'top');
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }
}
