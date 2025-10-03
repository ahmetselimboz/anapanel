<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::select('id','title','slug','author_id','created_at','publish')
        ->orderBy('id','desc')->paginate(30);

        return view('backend.article.index', compact('articles'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $author = User::where('status',3)->select(['id','name','status'])->get();
        return view('backend.article.create', compact('author'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255|unique:article,title',
            'detail' => 'required',
            'publish' => 'required',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3000',

        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'image' => 'Resim dosyası seçiniz.',
            'mimes' => 'Resim dosyası jpeg, png, jpg, webp, svg formatında olmalıdır.',
            'max' => 'Resim dosyası 3 MB\'den büyük olamaz.',
            'unique' => 'Bu başlık daha önce kullanılmıştır.',
            'title' => 'Bu başlık daha önce kullanılmıştır.',
        ];
        $this->validate($request, $rules, $customMessages);






        $articleDetail = $request->input('detail');
        $articleDetail = str_replace('<script>', '<ers-script>', $articleDetail);
        $articleDetail = str_replace('</script>', '</ers-script>', $articleDetail);

        $article = new Article();
        $article->title = strip_tags($request->title);
        $article->slug = slug_format($request->title);
        $article->detail = $articleDetail;
        $article->publish = strip_tags($request->publish);
        $article->author_id = $request->input('author') ?: auth()->user()->id;
        if ($request->publish  == 2) {
            $article->created_at = $request->input('publish_date');
        }
        if ($request->hasFile('images')) {
            if ($request->file('images')->isValid()) {
                $images_name = $article->slug . '-' . time() . '.ana-manset.' . $request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $article->images = $images_name;
            }
        }

        if($article->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            updateAuthor();
            return redirect(route('article.index'));
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
        $author = User::where('status', 3)->select(['id','name','status'])->get();
        $article = Article::where('id', $id)->first();
        if($article!=null){
            return view('backend.article.edit', compact('article','author'));
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
            'title' => 'required|unique:article,title,'.$id,
            'detail' => 'required',
            'publish' => 'required',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3000',
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'image' => 'Resim dosyası seçiniz.',
            'mimes' => 'Resim dosyası jpeg, png, jpg, webp, svg formatında olmalıdır.',
            'title' => 'Bu başlık daha önce kullanılmıştır.',
        ];
        $this->validate($request, $rules, $customMessages);

        $articleDetail = $request->input('detail');
        $articleDetail = str_replace('<script>', '<ers-script>', $articleDetail);
        $articleDetail = str_replace('</script>', '</ers-script>', $articleDetail);

        $article = Article::find($id);
        $article->title = strip_tags($request->title);
        $article->slug = slug_format($request->title);
        $article->detail = $articleDetail;
        $article->publish = strip_tags($request->publish);
        if ($request->publish  == 2) {
            $article->created_at = $request->input('publish_date');
        } else {
            if ($article->publish == 2) {
                $article->created_at = now();
            }
        }


        if ($request->hasFile('images')) {
            if ($request->file('images')->isValid()) {
                $images_name = $article->slug . '-' . time() . '.ana-manset.' . $request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $article->images = $images_name;
            }
        }

        if($article->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            updateAuthor();
            return redirect(route('article.edit', $id));
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
        $result = Article::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            updateAuthor();
            return redirect(route('article.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $articles = Article::onlyTrashed()->get();
        return view('backend.article.trashed', compact('articles'));
    }

    public function restore(Request $request, string $id)
    {
        $article = Article::where('id', $id);
        $article->restore();

        if($article->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('article.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }
}
