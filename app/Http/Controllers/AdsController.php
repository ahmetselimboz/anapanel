<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ads = Ads::all();
        return view('backend.ads.index', compact('ads'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ads = Ads::where('id', $id)->first();
        if($ads!=null){
            return view('backend.ads.edit', compact('ads'));
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
            'publish' => 'required',
            'images' => 'image|mimes:jpeg,png,jpg,gif'
        ];
        $customMessages = [
            'images.image' => 'Resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
        ];
        $this->validate($request, $rules, $customMessages);

        $ads = Ads::find($id);
        $ads->url = strip_tags($request->url);
        $ads->type = strip_tags($request->type);
        $ads->width = strip_tags($request->width);
        $ads->height = strip_tags($request->height);
        $ads->code = $request->code;
        $ads->publish = strip_tags($request->publish);

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = time().'-reklam.'.$request->images->extension();
                $request->images->move(public_path('uploads/ads'), $images_name);
                $ads->images = 'uploads/ads/'.$images_name; // Yol veritabanına böyle kaydedilir
            }
        }

        if($ads->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            Cache::forget('all_ads');
            return redirect(route('ads.edit', $ads->id));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

}
