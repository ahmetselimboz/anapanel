<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PhotoGallery;
use App\Models\PhotoGalleryImages;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

class PhotoGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photogalleries = PhotoGallery::select('id','title','slug','category_id','created_at')->orderBy('id','desc')->paginate(30);
        return view('backend.photogallery.index', compact('photogalleries'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id','title')->where('category_type', 1)->get();
        return view('backend.photogallery.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'detail' => 'required',
            'publish' => 'required',
            'images' => 'image|mimes:jpeg,png,jpg,gif'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'images.image' => 'Resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
        ];
        $this->validate($request, $rules, $customMessages);

        $photogallery = new PhotoGallery();
        $photogallery->category_id = strip_tags($request->category_id);
        $photogallery->title = strip_tags($request->title);
        $photogallery->slug = slug_format($request->title);
        $photogallery->detail = $request->detail;
        $photogallery->hit = strip_tags($request->hit =="" ? 0 : $request->hit);
        $photogallery->publish = strip_tags($request->publish);

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $photogallery->slug.'-'.time().'-foto-galeri.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $photogallery->images = $images_name;
            }
        }


        if($photogallery->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            $photo_galleries = PhotoGallery::join('category', 'category.id', '=', 'photogallery.category_id')->where('photogallery.publish',0)->select('photogallery.id','photogallery.title','photogallery.slug','photogallery.images','photogallery.detail','category.title as categorytitle','category.slug as categoryslug')->orderBy('photogallery.id','desc')->take(3)->get();
            Storage::disk('public')->put('main/photo_galleries.json', $photo_galleries);
            return redirect(route('photogalleryimages', $photogallery->id));
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
        $categories = Category::select('id','title')->where('category_type', 1)->get();
        $photogallery = PhotoGallery::where('id', $id)->first();
        if($photogallery!=null){
            return view('backend.photogallery.edit', compact('categories','photogallery'));
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
            'detail' => 'required',
            'publish' => 'required',
            'images' => 'image|mimes:jpeg,png,jpg,gif'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'images.image' => 'Resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
        ];
        $this->validate($request, $rules, $customMessages);

        $photogallery = PhotoGallery::find($id);
        $photogallery->category_id = strip_tags($request->category_id);
        $photogallery->title = strip_tags($request->title);
        $photogallery->slug = slug_format($request->title);
        $photogallery->detail = $request->detail;
        $photogallery->hit = strip_tags($request->hit =="" ? 0 : $request->hit);
        $photogallery->publish = strip_tags($request->publish);

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $photogallery->slug.'-'.time().'-foto-galeri.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $photogallery->images = $images_name;
            }
        }

        if($photogallery->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            $photo_galleries = PhotoGallery::join('category', 'category.id', '=', 'photogallery.category_id')->where('photogallery.publish',0)->select('photogallery.id','photogallery.title','photogallery.slug','photogallery.images','photogallery.detail','category.title as categorytitle','category.slug as categoryslug')->orderBy('photogallery.id','desc')->take(3)->get();
            Storage::disk('public')->put('main/photo_galleries.json', $photo_galleries);
            return redirect(route('photogallery.edit', $photogallery->id));
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
        $result = PhotoGallery::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('photogallery.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $photogalleries = PhotoGallery::onlyTrashed()->get();
        return view('backend.photogallery.trashed', compact('photogalleries'));
    }

    public function restore(Request $request, string $id)
    {
        $photogallery = PhotoGallery::where('id', $id);
        $photogallery->restore();

        if($photogallery->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('photogallery.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function photogalleryimages(Request $request, string $id)
    {
        $photogallery = PhotoGallery::find($id);
        $getimages = PhotoGalleryImages::where('photogallery_id', $id)->orderBy('sortby','asc')->get();

        return view('backend.photogallery.photogalleryimages', compact('photogallery','getimages'));
    }

    public function photogalleryimagespost(Request $request, string $id)
    {
        $photogallery = PhotoGallery::find($id);
        // $request->file resim dosyası olarak geliyor

        if($request->hasFile('file')){
            if ($request->file('file')->isValid()) {
                $images_name = $photogallery->slug.'-'.time().'-foto-galeri-resmi.'.$request->file->extension();
                $request->file->move(public_path('uploads'), $images_name);
                $images_name;
            }
        }

        $result = new PhotoGalleryImages();
        $result->photogallery_id = $id;
        $result->images = $images_name;
        $result->save();
    }


    public function photogalleryimageupdate(Request $request, string $id)
    {
        $result = PhotoGalleryImages::find($id);
        $result->title = strip_tags($request->imagetext);

        $result->save();
    }

    public function photogalleryimageupdateNotID(Request $request)
    {
        $result = PhotoGalleryImages::find($request->imageid);
        $result->title = strip_tags($request->imagetext);

        $result->save();
    }

    public function photogalleryimagesortby(Request $request)
    {
        PhotoGalleryImages::where('id', $request->imageid)->update(['sortby' => $request->sortby]);

    }

    public function photogalleryimagedelete(Request $request, string $id)
    {
        $image = PhotoGalleryImages::find($id);

        $image_path = public_path('uploads/'.$image->images);

        if(file_exists($image_path)) { unlink(public_path('uploads/'.$image->images)); }

        if(PhotoGalleryImages::destroy($id)){
            return response()->json("İşlem başarıyla gerçekleşti.", 200);
        }else{
            return response()->json("İşlem başarısız.", 200);
        }
    }

    public function photogalleryimagedeleteNotID(Request $request)
    {
        $image = PhotoGalleryImages::find($request->imageid);

        $image_path = public_path('uploads/'.$image->images);

        if(file_exists($image_path)) { unlink(public_path('uploads/'.$image->images)); }

        if(PhotoGalleryImages::destroy($request->imageid)){
            return response()->json("İşlem başarıyla gerçekleşti.", 200);
        }else{
            return response()->json("İşlem başarısız.", 200);
        }
    }

}













