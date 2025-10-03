<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FirmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $firms = Firm::select('id','title','created_at')->paginate(30);
        return view('backend.firm.index', compact('firms'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('category_type', 3)->select('id','title')->get();
        return view('backend.firm.create', compact('categories'));
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

        $firm = new Firm();
        $firm->title = strip_tags($request->title);
        $firm->slug = slug_format($request->title);
        $firm->category_id = strip_tags($request->category_id);
        $firm->sector_category = strip_tags($request->sector_category);
        $firm->detail = $request->detail;
        $firm->publish = strip_tags($request->publish);

        $firm->firmmagicbox = json_encode($request->all());

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $firm->slug.'-firma-rehberi-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $firm->images = $images_name;
            }
        }

        if($firm->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('firm.index'));
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
        $firm = Firm::where('id', $id)->first();
        $categories = Category::where('category_type', 3)->select('id','title')->get();
        $firmmagicbox = json_decode($firm->firmmagicbox);

        if($firm!=null){
            return view('backend.firm.edit', compact('firm','categories','firmmagicbox'));
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
            'images' => 'image|mimes:jpeg,png,jpg,gif'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'images.image' => 'Resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
        ];
        $this->validate($request, $rules, $customMessages);

        $firm = Firm::find($id);
        $firm->title = strip_tags($request->title);
        $firm->slug = slug_format($request->title);
        $firm->category_id = strip_tags($request->category_id);
        $firm->sector_category = strip_tags($request->sector_category);
        $firm->detail = $request->detail;
        $firm->publish = strip_tags($request->publish);

        $firm->firmmagicbox = json_encode($request->all());

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $firm->slug.'-firma-rehberi-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $firm->images = $images_name;
            }
        }

        if($firm->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('firm.edit', $firm->id));
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
        $result = Firm::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('firm.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $firms = Firm::onlyTrashed()->get();
        return view('backend.firm.trashed', compact('firms'));
    }

    public function restore(Request $request, string $id)
    {
        $firm = Firm::where('id', $id);
        $firm->restore();

        if($firm->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('firm.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }
}
