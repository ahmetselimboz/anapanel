<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function League\Flysystem\move;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::select('id','title','category_id','hit','price','publish','created_at')->orderBy('id','desc')->paginate(30);
        return view('backend.product.index', compact('products'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id','title')->get();
        return view('backend.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'detail' => 'required',
            'publish' => 'required'
        ];
        $customMessages = [
            'title.required' => 'Başlık zorunlu alan doldurun.',
            'category_id.required' => 'Kategori zorunlu alan doldurun.',
            'description.required' => 'Açıklama zorunlu alan doldurun.',
            'detail.required' => 'Detay zorunlu alan doldurun.'
        ];
        $this->validate($request, $rules, $customMessages);

        $product = new Product();
        $product->category_id = strip_tags($request->category_id);
        $product->title = strip_tags($request->title);
        $product->slug = slug_format($request->title);
        $product->description = strip_tags($request->description);
        $product->detail = strip_tags($request->detail);
        $product->hit = strip_tags($request->hit =="" ? 0 : $request->hit);
        $product->price = strip_tags($request->price);
        $product->showcase = strip_tags($request->showcase);
        $product->publish = strip_tags($request->publish);


        if($product->save()){
            if($request->hasFile('images')){
                foreach ($request->file('images') as $imagekey => $itemimage) {
                    if ($itemimage->isValid()) {
                        $image_name= 'urun-'.$imagekey.'-'.$product->slug.'-'.time().'.'.$itemimage->extension();
                        $itemimage->move(public_path("uploads"),$image_name);
                        $imagesave = new ProductImages();
                        $imagesave->product_id = $product->id;
                        $imagesave->image = $image_name;
                        $imagesave->save();
                    }
                }
            }

            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('product.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::where('id', $id)->first();
        $productimages = ProductImages::where("product_id", $id)->get();
        $categories = Category::select('id','title')->get();
        if($product!=null){
            return view('backend.product.edit', compact('product','categories','productimages'));
        }else{
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'detail' => 'required',
            'publish' => 'required'
        ];
        $customMessages = [
            'title.required' => 'Başlık zorunlu alan doldurun.',
            'category_id.required' => 'Kategori zorunlu alan doldurun.',
            'description.required' => 'Açıklama zorunlu alan doldurun.',
            'detail.required' => 'Detay zorunlu alan doldurun.'
        ];
        $this->validate($request, $rules, $customMessages);

        $product = Product::find($id);
        $product->category_id = strip_tags($request->category_id);
        $product->title = strip_tags($request->title);
        $product->slug = slug_format($request->title);
        $product->description = strip_tags($request->description);
        $product->detail = strip_tags($request->detail);
        $product->hit = strip_tags($request->hit =="" ? 0 : $request->hit);
        $product->price = strip_tags($request->price);
        $product->showcase = strip_tags($request->showcase);
        $product->publish = strip_tags($request->publish);

        if($product->save()){
            if($request->hasFile('images')){
                foreach ($request->file('images') as $imagekey => $itemimage) {
                    if ($itemimage->isValid()) {
                        $image_name= 'urun-'.$imagekey.'-'.$product->slug.'-'.time().'.'.$itemimage->extension();
                        $itemimage->move(public_path("uploads"),$image_name);
                        $imagesave = new ProductImages();
                        $imagesave->product_id = $product->id;
                        $imagesave->image = $image_name;
                        $imagesave->save();
                    }
                }
            }

            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('product.edit', $id));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = Product::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('product.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $products = Product::onlyTrashed()->get();
        return view('backend.product.trashed', compact('products'));
    }

    public function restore(Request $request, string $id)
    {
        $product = Product::where('id', $id);
        $product->restore();

        if($product->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('product.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function product_image_delete(Request $request, $imageid)
    {
        if(ProductImages::destroy($imageid)){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return back();
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

}





























