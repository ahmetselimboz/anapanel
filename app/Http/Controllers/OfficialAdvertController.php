<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Clsfad;
use App\Models\OfficialAdvert;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfficialAdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $cast = [
        'clsfadmagicbox' => 'array',
     ];

    public function index()
    {
        $clsfads = OfficialAdvert::select('id','title','created_at','ilan_id')->latest()->paginate(30);
        return view('backend.official_advert.index', compact('clsfads'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('category_type', 5)->select('id','title')->get();
        return view('backend.official_advert.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'detail' => 'nullable',
            'publish' => 'required',
            'ilan_id' => 'nullable|string',
            'category_id' => 'required|exists:category,id',
            'images' => 'image|mimes:jpeg,png,jpg,gif'
        ],[],[
            'title' => 'Başlık',
            'detail' => 'Detay ',
            'publish' => 'Yayın Durumu',
            'category_id' => 'Kategori',
            'images' => 'Resim',
            'ilan_id' => 'Basın İlan Numarası',
        ]);

        $clsfads = new OfficialAdvert();
        $clsfads->title = strip_tags($request->title);
        $clsfads->slug = slug_format($request->title);
        $clsfads->category_id = strip_tags($request->category_id);
        $clsfads->detail = $request->detail;
        $clsfads->ilan_id = $request->ilan_id;
        $clsfads->create_date = now();
        $clsfads->publish = strip_tags($request->publish);


        if ($request->hasFile('images')) {
            if ($request->file('images')->isValid()) {
                $images_name = $clsfads->slug.'-resmi-ilan-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('official_advert'), $images_name);
                $clsfads->images = 'official_advert/'.$images_name; // Yol veritabanına böyle kaydedilir
            }
        }
        $clsfads->clsfadmagicbox = $clsfads;

        if($clsfads->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('official_advert.index'));
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
        $clsfad = OfficialAdvert::where('id', $id)->first();
        $categories = Category::where('category_type', 5)->select('id','title')->get();
        $clsfadmagicbox = $clsfad->clsfadmagicbox;

        if($clsfad!=null){
            return view('backend.official_advert.edit', compact('clsfad','categories','clsfadmagicbox'));
        }else{
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'detail' => 'nullable',
            'publish' => 'required',
            'ilan_id' => 'nullable|string',
            'category_id' => 'required|exists:category,id',
            'images' => 'image|mimes:jpeg,png,jpg,gif'
        ],[],[
            'title' => 'Başlık',
            'detail' => 'Detay ',
            'publish' => 'Yayın Durumu',
            'category_id' => 'Kategori',
            'images' => 'Resim',
            'ilan_id' => 'Basın İlan Numarası',
        ]);


        $clsfads = OfficialAdvert::find($id);
        $clsfads->title = strip_tags($request->title);
        $clsfads->slug = slug_format($request->title);
        $clsfads->category_id = strip_tags($request->category_id);
        $clsfads->detail = $request->detail;
        $clsfads->ilan_id = $request->ilan_id;
        $clsfads->publish = strip_tags($request->publish);

        if ($request->hasFile('images')) {
            if ($request->file('images')->isValid()) {
                $images_name = $clsfads->slug.'-resmi-ilan-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('official_advert'), $images_name);
                $clsfads->images = 'official_advert/'.$images_name; // Yol veritabanına böyle kaydedilir
            }
        }

        $clsfads->clsfadmagicbox = $clsfads;

        if($clsfads->save()){

            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return back();
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
        $result = OfficialAdvert::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('official_advert.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $clsfads = OfficialAdvert::onlyTrashed()->get();
        return view('backend.official_advert.trashed', compact('clsfads'));
    }

    public function restore(Request $request, string $id)
    {
        $clsfads = OfficialAdvert::where('id', $id);
        $clsfads->restore();

        if($clsfads->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('official_advert.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }
}
