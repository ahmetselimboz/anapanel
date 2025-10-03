<?php

namespace App\Http\Controllers;

use App\Models\Biography;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BiographyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $biographies = Biography::select('id','title','created_at')->paginate(30);
        return view('backend.biography.index', compact('biographies'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.biography.create');
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

        $biography = new Biography();
        $biography->title = strip_tags($request->title);
        $biography->slug = slug_format($request->title);
        $biography->detail = $request->detail;
        $biography->publish = strip_tags($request->publish);

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $biography->slug.'-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $biography->images = $images_name;
            }
        }


        if($biography->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('biography.index'));
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
        $biography = Biography::where('id', $id)->first();
        if($biography!=null){
            return view('backend.biography.edit', compact('biography'));
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

        $biography = Biography::find($id);
        $biography->title = strip_tags($request->title);
        $biography->slug = slug_format($request->title);
        $biography->detail = $request->detail;
        $biography->publish = strip_tags($request->publish);

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $biography->slug.'-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $biography->images = $images_name;
            }
        }

        if($biography->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('biography.edit', $biography->id));
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
        $result = Biography::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('biography.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $biographies = Biography::onlyTrashed()->get();
        return view('backend.biography.trashed', compact('biographies'));
    }

    public function restore(Request $request, string $id)
    {
        $biography = Biography::where('id', $id);
        $biography->restore();

        if($biography->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('biography.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }
}
