<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::select('id','title','slug','created_at')->paginate(30);
        return view('backend.page.index', compact('pages'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'detail' => 'required',
            'publish' => 'required'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.'
        ];
        $this->validate($request, $rules, $customMessages);

        $page = new Page();
        $page->title = strip_tags($request->title);
        $page->slug = slug_format($request->title);
        $page->detail = $request->detail;
        $page->publish = strip_tags($request->publish);

        if($page->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('page.index'));
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
        $page = Page::where('id', $id)->first();
        if($page!=null){
            return view('backend.page.edit', compact('page'));
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
            'publish' => 'required'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.'
        ];
        $this->validate($request, $rules, $customMessages);

        $page = Page::find($id);
        $page->title = strip_tags($request->title);
        $page->slug = slug_format($request->title);
        $page->detail = $request->detail;
        $page->publish = strip_tags($request->publish);

        if($page->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('page.edit', $page->id));
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
        $result = Page::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('page.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $pages = Page::onlyTrashed()->get();
        return view('backend.page.trashed', compact('pages'));
    }

    public function restore(Request $request, string $id)
    {
        $page = Page::where('id', $id);
        $page->restore();

        if($page->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('page.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }
}
