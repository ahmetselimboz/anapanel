<?php

namespace App\Http\Controllers;

use App\Models\Enewspaper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EnewspaperImages;

class EnewspaperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enewspapers = Enewspaper::select('id','title','created_at','date')->orderBy('date','desc')->paginate(30);
        return view('backend.enewspaper.index', compact('enewspapers'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.enewspaper.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'publish' => 'required',
            'images' => 'image|mimes:jpeg,png,jpg,gif,webp'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'images.image' => 'Resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
        ];
        $this->validate($request, $rules, $customMessages);

        $enewspaper = new Enewspaper();
        $enewspaper->title = strip_tags($request->title);
        $enewspaper->slug = slug_format($request->title);
        $enewspaper->redirect_link = strip_tags($request->redirect_link);
        $enewspaper->publish = strip_tags($request->publish);
        $enewspaper->date = $request->date;

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $enewspaper->slug.'-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $enewspaper->images = $images_name;
            }
        }


        if($enewspaper->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('enewspaper.index'));
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
        $enewspaper = Enewspaper::where('id', $id)->first();
        if($enewspaper!=null){
            return view('backend.enewspaper.edit', compact('enewspaper'));
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
            'publish' => 'required',
            'images' => 'image|mimes:jpeg,png,jpg,gif,webp'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'images.image' => 'Resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
        ];
        $this->validate($request, $rules, $customMessages);

        $enewspaper = Enewspaper::find($id);
        $enewspaper->title = strip_tags($request->title);
        $enewspaper->slug = slug_format($request->title);
        $enewspaper->redirect_link = strip_tags($request->redirect_link);
        $enewspaper->publish = strip_tags($request->publish);
        $enewspaper->date = $request->date;


        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $enewspaper->slug.'-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $enewspaper->images = $images_name;
            }
        }

        if($enewspaper->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('enewspaper.edit', $enewspaper->id));
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
        $result = Enewspaper::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('enewspaper.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $enewspapers = Enewspaper::onlyTrashed()->get();
        return view('backend.enewspaper.trashed', compact('enewspapers'));
    }

    public function restore(Request $request, string $id)
    {
        $enewspaper = Enewspaper::where('id', $id);
        $enewspaper->restore();

        if($enewspaper->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('enewspaper.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }


    public function enewspaperimages(Request $request, string $id)
    {
        $photogallery = Enewspaper::with('getImages')->find($id);
        $getimages = $photogallery->getImages;

        return view('backend.enewspaper.galleryimages', compact('photogallery','getimages'));
    }

    public function enewspaperimagespost(Request $request, string $id)
    {
        $photogallery = Enewspaper::find($id);
        $count = EnewspaperImages::count() +1 ;
        // $request->file resim dosyası olarak geliyor
        if($request->hasFile('file')){
            if ($request->file('file')->isValid()) {
                $images = $photogallery->slug.'-'.$count.'-foto-galeri-resmi.'.$request->file->extension();
                $images_name = $request->file->getClientOriginalName();
                $request->file->move(public_path('uploads'), $images);



                $photogallery->getImages()->create([
                    'gallery_id' => $photogallery->id,
                    'images' => $images,
                    'title' => $images_name,
                    'model_path'=>Enewspaper::class
                ]);
                return response(['status'=>'true','image'=>$images_name]);
            }
        }

        return response(['status'=>'false']);

    }


    public function enewspaperimageupdate(Request $request, string $id)
    {
        $result = EnewspaperImages::find($id);
        $result->title = strip_tags($request->imagetext);

        $result->save();

        return response(['status'=>'true','image'=>$result->title]);

    }

    public function enewspaperimageupdateNotID(Request $request)
    {
        $result = EnewspaperImages::find($request->imageid);
        $result->title = strip_tags($request->imagetext);
        $result->save();
        return response(['status'=>'true','image'=>$result->title]);

    }

    public function enewspaperimagesortby(Request $request)
    {
        $result= EnewspaperImages::where('id', $request->imageid)->update(['sortby' => (int)$request->sortby]);
        return response()->json(['status'=>'true','image'=>$result]);


    }

    public function enewspaperimagedelete(Request $request, string $id)
    {
        $image = EnewspaperImages::find($id);

        $image_path = public_path('uploads/'.$image->images);

        if(file_exists($image_path)) { unlink(public_path('uploads/'.$image->images)); }

        if(EnewspaperImages::destroy($id)){
            return response()->json("İşlem başarıyla gerçekleşti.", 200);
        }else{
            return response()->json("İşlem başarısız.", 200);
        }
    }

    public function enewspaperimagedeleteNotID(Request $request)
    {
        $image = EnewspaperImages::find($request->imageid);

        $image_path = public_path('uploads/'.$image->images);

        if(file_exists($image_path)) { unlink(public_path('uploads/'.$image->images)); }

        if(EnewspaperImages::destroy($request->imageid)){
            return response()->json("İşlem başarıyla gerçekleşti.", 200);
        }else{
            return response()->json("İşlem başarısız.", 200);
        }
    }

}
