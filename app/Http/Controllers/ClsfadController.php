<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Clsfad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClsfadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clsfads = Clsfad::select('id','title','created_at')->paginate(30);
        return view('backend.clsfad.index', compact('clsfads'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('category_type', 4)->select('id','title')->get();
        return view('backend.clsfad.create', compact('categories'));
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

        $clsfads = new Clsfad();
        $clsfads->title = strip_tags($request->title);
        $clsfads->slug = slug_format($request->title);
        $clsfads->category_id = strip_tags($request->category_id);
        $clsfads->detail = $request->detail;
        $clsfads->publish = strip_tags($request->publish);

        $clsfads->clsfadmagicbox = json_encode($request->all());

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $clsfads->slug.'-seri-ilan-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $clsfads->images = $images_name;
            }
        }

        if($clsfads->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('clsfad.index'));
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
        $clsfad = Clsfad::where('id', $id)->first();
        $categories = Category::where('category_type', 3)->select('id','title')->get();
        $clsfadmagicbox = json_decode($clsfad->clsfadmagicbox);

        if($clsfad!=null){
            return view('backend.clsfad.edit', compact('clsfad','categories','clsfadmagicbox'));
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

        $clsfads = Clsfad::find($id);
        $clsfads->title = strip_tags($request->title);
        $clsfads->slug = slug_format($request->title);
        $clsfads->category_id = strip_tags($request->category_id);
        $clsfads->detail = $request->detail;
        $clsfads->publish = strip_tags($request->publish);

        $clsfads->clsfadmagicbox = json_encode($request->all());

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $clsfads->slug.'-seri-ilan-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $clsfads->images = $images_name;
            }
        }

        if($clsfads->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('clsfad.edit', $clsfads->id));
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
        $result = Clsfad::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('clsfad.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $clsfads = Clsfad::onlyTrashed()->get();
        return view('backend.clsfad.trashed', compact('clsfads'));
    }

    public function restore(Request $request, string $id)
    {
        $clsfads = Clsfad::where('id', $id);
        $clsfads->restore();

        if($clsfads->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('clsfad.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }
}
