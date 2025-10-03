<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $videos = Video::whereHas('category')->select('id','title','slug','category_id','created_at')->orderBy('id','desc')->paginate(30);
         return view('backend.video.index', compact('videos'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id','title')->where('category_type', 2)->get();
        return view('backend.video.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'category_id' => 'required|exists:category,id', // Bu kural, categories tablosunda id'si olan bir kategori olup olmadığını kontrol eder.
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

        $video = new Video();
        $video->category_id = strip_tags($request->category_id);
        $video->title = strip_tags($request->title);
        $video->slug = slug_format($request->title);
        $video->detail = $request->detail;
        $video->embed = $request->embed;
        $video->hit = strip_tags($request->hit =="" ? 0 : $request->hit);
        $video->publish = strip_tags($request->publish);

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $video->slug.'-'.time().'-video-galeri.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $video->images = $images_name;
            }
        }


        if($video->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            $videos = Video::join('category', 'category.id', '=', 'video.category_id')->where('video.publish',0)->select('video.id','video.title','video.slug','video.images','video.detail','category.title as categorytitle','category.slug as categoryslug')->orderBy('video.id','desc')->take(7)->get();
            Storage::disk('public')->put('main/videos.json', $videos);
            return redirect(route('video.index'));
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
        $categories = Category::select('id','title')->where('category_type', 2)->get();
        $video = Video::where('id', $id)->first();
        if($video!=null){
            return view('backend.video.edit', compact('categories','video'));
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
            'category_id' => 'required|exists:category,id', // Bu kural, categories tablosunda id'si olan bir kategori olup olmadığını kontrol eder.
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

        $video = Video::find($id);
        $video->category_id = strip_tags($request->category_id);
        $video->title = strip_tags($request->title);
        $video->slug = slug_format($request->title);
        $video->detail = $request->detail;
        $video->embed = $request->embed;
        $video->hit = strip_tags($request->hit =="" ? 0 : $request->hit);
        $video->publish = strip_tags($request->publish);

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $video->slug.'-'.time().'-video-galeri.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $video->images = $images_name;
            }
        }

        if($video->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            $videos = Video::join('category', 'category.id', '=', 'video.category_id')->where('video.publish',0)->select('video.id','video.title','video.slug','video.images','video.detail','category.title as categorytitle','category.slug as categoryslug')->orderBy('video.id','desc')->take(7)->get();
            Storage::disk('public')->put('main/videos.json', $videos);
            return redirect(route('video.edit', $video->id));
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
        $result = Video::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('video.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $videos = Video::onlyTrashed()->get();
        return view('backend.video.trashed', compact('videos'));
    }

    public function restore(Request $request, string $id)
    {
        $video = Video::where('id', $id);
        $video->restore();

        if($video->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('video.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }
}
