<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::select('id','title','slug','category_type','created_at')->paginate(30);
        return view('backend.category.index', compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parent_categories = Category::select('id','title','category_type')->get();
        return view('backend.category.create',compact('parent_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'category_type' => 'required',
            'publish' => 'required'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.'
        ];
        $this->validate($request, $rules, $customMessages);

        $category = new Category();
        $category->parent_category = ($request->parent_categoryResult!=null) ? strip_tags($request->parent_categoryResult) : 0;
        $category->title = strip_tags($request->title);
        $category->slug = slug_format($request->title);
        $category->description = strip_tags($request->description);
        $category->color = strip_tags($request->color);
        $category->text_color = strip_tags($request->text_color);
        $category->keywords = strip_tags($request->keywords);
        $category->category_type = strip_tags($request->category_type);
        $category->show_category_ads = strip_tags($request->show_category_ads);
        $category->publish = strip_tags($request->publish);

        if($category->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('category.index'));
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
        $category = Category::where('id', $id)->first();
        $categories = Category::select('id','title')->get();
        if($category!=null){
            return view('backend.category.edit', compact('category','categories'));
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
            'category_type' => 'required',
            'publish' => 'required'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.'
        ];
        $this->validate($request, $rules, $customMessages);

        $category = Category::find($id);
        $category->parent_category = ($request->parent_categoryResult!=null) ? strip_tags($request->parent_categoryResult) : 0;
        $category->title = strip_tags($request->title);
        $category->slug = slug_format($request->title);
        $category->keywords = strip_tags($request->keywords);
        $category->color = strip_tags($request->color);
        $category->text_color = strip_tags($request->text_color);
        $category->description = strip_tags($request->description);
        $category->category_type = strip_tags($request->category_type);
        $category->show_category_ads = strip_tags($request->show_category_ads);
        $category->publish = strip_tags($request->publish);

        if($category->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);





             $menus = Menus::all();

            foreach ($menus as $menu) {
                $menuarray = json_decode($menu->jsonmenu,true);

                foreach ($menuarray as $key => $item) {
                    if($item["type"]=="category"  && $item["id"]==$id){
                        // unset($menuarray[$key]);
                        $menuarray[$key]['url'] = "/".$category->slug;
                        $menuarray[$key]['name'] = $category->title;
                        $menuuptade = Menus::findOrFail($menu->id);
                        $menuuptade->jsonmenu = json_encode($menuarray);
                        $menuuptade->save();
                        Storage::disk('public')->put('menu'.$menu->id.'.json', $menuuptade->jsonmenu);
                    }
                }
            }

            $postController =   new PostController();
            $postController->positionUpdate();


            return redirect(route('category.edit', $category->id));
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
        $menu_check_id = $id;

        $result = Category::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            ## kategori silme işlemi sonrası menu json güncelleme işlemi için sonradan eklenmiştir.
            $menus = Menus::all();
            foreach ($menus as $menu) {
                $menuarray = json_decode($menu->jsonmenu,true);
                foreach ($menuarray as $key => $item) {
                    if($item["type"]=="category" and $item["id"]==$menu_check_id){
                        unset($menuarray[$key]);
                        $menuuptade = Menus::findOrFail($menu->id);
                        $menuuptade->jsonmenu = json_encode($menuarray);
                        $menuuptade->save();
                        Storage::disk('public')->put('menu'.$menu->id.'.json', $menuuptade->jsonmenu);
                    }
                }
            }
            ## kategori silme işlemi sonrası menu json güncelleme işlemi için sonradan eklenmiştir.

            return redirect(route('category.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $categories = Category::onlyTrashed()->get();
        return view('backend.category.trashed', compact('categories'));
    }

    public function restore(Request $request, string $id)
    {
        $category = Category::where('id', $id);
        $category->restore();

        if($category->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('category.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }
}
