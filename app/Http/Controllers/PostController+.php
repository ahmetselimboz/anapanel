<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\PhotoGallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {

       // $this->categoryslicePostChange();

        $archive = false;

        $posts = Post::select('id', 'title', 'slug', 'category_id', 'meta_title', 'meta_description', 'hit', 'publish', 'created_at')
            ->with('category')
            ->orderBy('id', 'desc')->paginate(30);
        Artisan::call('post:archive');

        return view('backend.post.index', compact('posts', 'archive'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('category_type', 0)->select('id', 'title')->get();
        $related_news = Post::where('publish', 0)->select('id', 'title')->get();
        $related_photos = PhotoGallery::where('publish', 0)->select('id', 'title')->limit(33)->get();

        return view('backend.post.create', compact('categories', 'related_news', 'related_photos'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @location 0 => dortlu_manset, 1 => ana_manset, 2 => mini_manset, 3 => standart_haberler, 4 => sondakika_manset
     */

    public function positionUpdate($locations = null)
    {

        if (blank($locations)) {
            $locations = [0, 1, 2, 3, 4, 5];
        }
        $take = 4;

        foreach ($locations as $type) {
            $type = intval($type);

            if ($type == 0) {
                $jsonfile = "dortlu_manset";
                $take = 4;
            } elseif ($type == 1) {
                $jsonfile = "ana_manset";
                $take = 15;
            } elseif ($type == 2) {
                $jsonfile = "mini_manset";
                $take = 15;
            } elseif ($type == 3) {
                $jsonfile = "standart_haberler";
                $take = 300;
            } elseif ($type == 4) {
                $jsonfile = "sondakika_manset";
                $take = 10;
            } elseif ($type == 5) {
                $jsonfile = "altili_manset";
                $take = 6;
            }
            $posts = Post::whereHas('locations', function ($query) use ($type) {
                $query->where('location_id', $type);
            })->join('category', 'category.id', '=', 'post.category_id')
                ->where("post.publish" ,0)
                ->whereDate('created_at', '<=', now())
                ->select(
                    'post.id',
                    'post.title',
                    'post.description',
                    'post.slug',
                    'post.images',
                    'post.sortby',
                    'post.created_at',
                    'post.category_id',
                    'post.redirect_link',
                    'post.show_title_slide',
                    'post.extra',
                    'category.slug as categoryslug',
                    'category.title as categorytitle',
                    'category.color'
                )
                ->orderBy('post.id', 'desc')->limit($take)->get();
            Storage::disk('public')->put('main/' . $jsonfile . '.json', $posts);
        }
    }

    public function MainJsonFileUpdate()
    {
        $this->positionUpdate();
    }

    public function MainJsonFileUpdateButton()
    {
        $this->positionUpdate();

        toastr()->success('İşlem başarıyla gerçekleşti.', 'BAŞARILI', ['timeOut' => 5000]);
        return back();
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'detail' => 'required',
            'publish' => 'required',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif',
            'fb_image' => 'image|mimes:jpeg,png,jpg,gif',
        ];
        $customMessages = [
            'title.required' => 'Başlık zorunlu alan doldurun.',
            'category_id.required' => 'Kategori zorunlu alan doldurun.',
            'description.required' => 'Açıklama zorunlu alan doldurun. Boş bırakmak için nokta koyun.',
            'detail.required' => 'Detay zorunlu alan doldurun.',
            'images.image' => 'Ana resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
            'fb_image.image' => 'Facebook resim alanı resim dosyası olmalıdır',
            'fb_image.mimes' => 'Resim formatı hatalı',
        ];
        $this->validate($request, $rules, $customMessages);

        $editor_state = json_decode($request->editor_state);

        $post = new Post();
        $post->category_id = strip_tags($request->category_id);
        $post->title = strip_tags($request->title);
        $post->slug = slug_format($request->title);
        $post->keywords = strip_tags($request->keywords);
        $post->description = strip_tags($request->description);
        $post->meta_title = strip_tags($request->meta_title);
        $post->meta_description = strip_tags($request->meta_description);
        $post->detail = $request->detail;
        $post->redirect_link = strip_tags($request->redirect_link);
        $post->show_title_slide = strip_tags($request->show_title_slide);
        $post->editor_state = json_encode($editor_state);
        $post->hit = strip_tags($request->hit == "" ? 0 : $request->hit);
        if ($request->datetime != null) {
            $post->created_at = date("Y-m-d h:i:s", strtotime($request->datetime));
        }
        $post->publish = strip_tags($request->publish);
        $fb_image = "";

        if ($request->hasFile('images')) {
            if ($request->file('images')->isValid()) {
                $images_name = $post->slug . '-' . time() . '.' . $request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $post->images = $images_name;
            }
        }

        if ($request->hasFile('fb_image')) {
            if ($request->file('fb_image')->isValid()) {
                $fb_image = $post->slug . '-facebook-' . time() . '-resmi.' . $request->fb_image->extension();
                $request->fb_image->move(public_path('uploads'), $fb_image);
            }
        }

        $extra_array = [
            "news_source" => strip_tags($request->news_source),
            "related_news" => strip_tags($request->related_news),
            "related_photo" => strip_tags($request->related_photo),
            "comment_status" => strip_tags($request->comment_status),
            "video_embed" => $request->video_embed,
            "fb_image" => $fb_image,
            "show_post_ads" => strip_tags($request->show_post_ads),
            "author" => auth()->user()->id,
        ];

        $post->extra = json_encode($extra_array);
        if ($post->save()) {
            $post->postLocation()->attach($request->location);
            $this->positionUpdate($request->location);
            $this->categoryPostCount($post->category_id);

            toastr()->success('İşlem başarıyla gerçekleşti.', 'BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('post.index'));
            // return redirect(route('post.edit', $post->id));
        } else {
            toastr()->error('Bir hata meydana geldi.', 'BAŞARISIZ', ['timeOut' => 5000]);
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
        $post = Post::where('id', $id)->with('locations')->first();


        // $this->positionUpdate($post->locations?->pluck('location_id')?->toArray());

        $related_news = Post::where('publish', 0)->whereNotIn('id', [$id])->select('id', 'title')->limit(33)->get();
        $related_photos = PhotoGallery::where('publish', 0)->select('id', 'title')->limit(33)->get();
        $categories = Category::where('category_type', 0)->select('id', 'title')->get();
        if ($post != null) {
            return view('backend.post.edit', compact('post', 'categories', 'related_news', 'related_photos'));
        } else {
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
            'category_id' => 'required',
            'description' => 'required',
            'detail' => 'required',
            'publish' => 'required',
            'images' => 'image|mimes:jpeg,png,jpg,gif',
            'fb_image' => 'image|mimes:jpeg,png,jpg,gif',
        ];
        $customMessages = [
            'title.required' => 'Başlık zorunlu alan doldurun.',
            'category_id.required' => 'Kategori zorunlu alan doldurun.',
            'description.required' => 'Açıklama zorunlu alan doldurun. Boş bırakmak için nokta koyun.',
            'detail.required' => 'Detay zorunlu alan doldurun.',
            'images.image' => 'Ana resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
            'fb_image.image' => 'Facebook resim alanı resim dosyası olmalıdır',
            'fb_image.mimes' => 'Resim formatı hatalı',

        ];
        $this->validate($request, $rules, $customMessages);

        $editor_state = json_decode($request->editor_state);

        $post = Post::withoutGlobalScopes()->find($id);
        $post->category_id = strip_tags($request->category_id);
        $post->title = strip_tags($request->title);
        $post->slug = slug_format($request->title);
        $post->keywords = strip_tags($request->keywords);
        $post->description = strip_tags($request->description);
        $post->meta_title = strip_tags($request->meta_title);
        $post->meta_description = strip_tags($request->meta_description);
        $post->detail = $request->detail;
        $post->redirect_link = strip_tags($request->redirect_link);
        $post->editor_state = json_encode($editor_state);
        $post->show_title_slide = strip_tags($request->show_title_slide);
        $post->hit = strip_tags($request->hit == "" ? 0 : $request->hit);
        if ($request->datetime != null) {
            $post->created_at = date("Y-m-d H:i:s", strtotime($request->datetime));
        }
        $post->publish = strip_tags($request->publish);
        $fb_image = json_decode($post->extra)->fb_image;


        if ($request->hasFile('images')) {
            if ($request->file('images')->isValid()) {
                $images_name = $post->slug . '-' . time() . '.' . $request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $post->images = $images_name;
            }
        }

        if ($request->hasFile('fb_image')) {
            if ($request->file('fb_image')->isValid()) {
                $fb_image = $post->slug . '-facebook-' . time() . '-resmi.' . $request->fb_image->extension();
                $request->fb_image->move(public_path('uploads'), $fb_image);
            }
        }

        $extra_array = [
            "news_source" => strip_tags($request->news_source),
            "related_news" => strip_tags($request->related_news),
            "related_photo" => strip_tags($request->related_photo),
            "comment_status" => strip_tags($request->comment_status),
            "video_embed" => $request->video_embed,
            "show_post_ads" => strip_tags($request->show_post_ads),
            "fb_image" => $fb_image,
        ];

        $post->extra = json_encode($extra_array);
        if ($post->save()) {
            toastr()->success('İşlem başarıyla gerçekleşti.', 'BAŞARILI', ['timeOut' => 5000]);
            $post->postLocation()->sync($request->location);
            $this->positionUpdate($request->location);
            $this->categoryPostCount($post->category_id);
            return redirect(route('post.edit', $id));
        } else {
            toastr()->error('Bir hata meydana geldi.', 'BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = Post::destroy($id);
        if ($result) {
            toastr()->success('İşlem başarıyla gerçekleşti.', 'BAŞARILI', ['timeOut' => 5000]);
            $this->MainJsonFileUpdate();
            $this->categoryPostCount();

            return redirect(route('post.index'));
        } else {
            toastr()->error('Bir hata meydana geldi.', 'BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $posts = Post::onlyTrashed()->get();
        return view('backend.post.trashed', compact('posts'));
    }

    public function restore(Request $request, string $id)
    {
        $post = Post::where('id', $id);
        $post->restore();

        if ($post->restore() == 0) {
            toastr()->success('İşlem başarıyla gerçekleşti.', 'BAŞARILI', ['timeOut' => 5000]);
            $this->MainJsonFileUpdate();
            return redirect(route('post.trashed'));
        } else {
            toastr()->error('Bir hata meydana geldi.', 'BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function ajaxUpdate(Request $request)
    {
        $post = Post::find($request->pk);
        $post->title = strip_tags($request->value);
        $post->slug = slug_format($post->title);

        if ($post->save()) {
            return response()->json("İşlem başarıyla gerçekleşti.", 200);
        } else {
            return response()->json("İşlem başarısız.", 500);
        }
    }

    public function ajaxAllProcess(Request $request)
    {
        if ($request->processtype == "active" or $request->processtype == "passive") {
            foreach ($request->newsid as $item) {
                $post = Post::find($item);
                $post->publish = ($request->processtype == "active") ? 0 : 1;
                $post->save();
            }
            $this->MainJsonFileUpdate();
            return response()->json("ok", 200);
        } elseif ($request->processtype == "restore") {
            foreach ($request->newsid as $item) {
                $post = Post::where('id', $item);
                $post->restore();
            }
            $this->MainJsonFileUpdate();
            return response()->json("ok", 200);
        } elseif ($request->processtype == "delete") {
            foreach ($request->newsid as $item) {
                Post::destroy($item);
            }
            $this->MainJsonFileUpdate();
            return response()->json("ok", 200);
        }
    }

    public function ckeditorimageupload(Request $request)
    {
        if ($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();
            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif') {
                //filename to store
                $filenametostore = $filename . '_google_ers_' . time() . '.' . $extension;
                //Upload File
                $request->file('upload')->move(public_path('uploads'), $filenametostore);

                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $url = asset('/uploads/' . $filenametostore);
                $msg = 'Resim yüklendi';
                $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            } else {
                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $url = asset('/uploads/');
                $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', 'HATA')</script>";
            }
            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }
    }

    public function postSearch(Request $request)
    {
        $search = strtolower(strip_tags($request->search));
        $archive = $request->isArchived;
        $posts = Post::query();
        if ($archive == 1) {
            $posts->where('is_archived', 1);
        }
        $posts =   $posts->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
            ->orwhereRaw('LOWER(detail) LIKE ?', ["%{$search}%"])
            ->orwhereRaw('LOWER(keywords) LIKE ?', ["%{$search}%"])
            // ->where(['publish'=>0,'deleted_at'=>NULL])
            ->select('id', 'title', 'slug', 'category_id', 'meta_title', 'meta_description', 'hit', 'publish', 'created_at')
            ->orderBy('id', 'desc')->paginate(30);

        return view('backend.post.search', compact('posts', "search", "archive"));
    }

    public function postArchive(Request $request)
    {
        $archive = true;
        $posts = Post::select('id', 'title', 'slug', 'category_id', 'meta_title', 'meta_description', 'hit', 'publish', 'created_at')
            ->with('category')->where('is_archived', 1)
            ->orderBy('id', 'desc')->paginate(30);

        return view('backend.post.archive_index', compact('posts', 'archive'));
    }


    private function categoryPostCount($id = null)
    {
        if (!blank($id)) {
            $category =  Category::find($id);
            $category->countnews = Post::where('category_id', $category->id)->count();
            $category->save();
        } else {
            Category::all()->each(function ($category) {
                $category->countnews = Post::where('category_id', $category->id)->count();
                $category->save();
            });
        }
    }

    // editör için fonksiyonlar
    public function uploadEditorImages(Request $request)
    {
        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                try {
                    $image_name = time() . '.' . $request->file('image')->extension();
                    $request->file('image')->move(public_path('uploads/editor'), $image_name);

                    return response()->json([
                        'success' => true,
                        'message' => 'Resim başarıyla yüklendi.',
                        'image_url' => asset('uploads/editor/' . $image_name)
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Dosya yüklenirken bir hata oluştu.'
                    ], 500);
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Lütfen geçerli bir dosya yükleyin.'
        ], 400);
    }

    public function deleteEditorImages(Request $request)
    {
        if ($request->has('url')) {
            $imagePath = str_replace(asset('/'), '', $request->input('url'));
            $fullPath = public_path($imagePath);

            if (file_exists($fullPath)) {
                try {
                    unlink($fullPath);
                    return response()->json([
                        'success' => true,
                        'message' => 'Resim başarıyla silindi.'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Resim silinirken bir hata oluştu.'
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Resim bulunamadı.'
                ], 404);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Lütfen geçerli bir resim URL\'si gönderin . '
        ], 400);
    }

    public function listEditorImages()
    {
        $directory = public_path('uploads/editor'); // Doğru dizin
        $files = File::files($directory);

        if (empty($files)) {
            return response()->json([]);
        }

        $images = [];
        foreach ($files as $file) {
            $images[] = [
                'url' => asset('uploads/editor/' . $file->getFilename())
            ];
        }

        return response()->json($images);
    }

    public function originalSaveEditorImages(Request $request)
    {
        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                try {
                    $image_name = time() . '.' . $request->file('image')->extension();
                    $request->file('image')->move(public_path('uploads/editor_original_images'), $image_name);

                    return response()->json([
                        'success' => true,
                        'message' => 'Resim başarıyla yüklendi.',
                        'image_url' => $image_name
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Dosya yüklenirken bir hata oluştu.'
                    ], 500);
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Lütfen geçerli bir dosya yükleyin.'
        ], 400);
    }


    public function categoryslicePostChange()
    {
        $locationData = [];
        Category::with('posts')->chunk(50, function ($categories) use (&$locationData) {
            foreach ($categories as $category) {
                // Her kategoriden en fazla 15 post al
                $posts = $category->posts->take(15);
                if ($posts->isNotEmpty()) {
                    $postIds = $posts->pluck('id')->toArray();
                    // Toplu işlem için veriyi hazırla
                    $locationData = [];
                    foreach ($postIds as $postId) {
                        $locationData[] = [
                            'post_id' => $postId,
                            'location_id' => 1,
                        ];
                        $locationData[] = [
                            'post_id' => $postId,
                            'location_id' => 2,
                        ];
                        $locationData[] = [
                            'post_id' => $postId,
                            'location_id' => 3,
                        ];
                        $locationData[] = [
                            'post_id' => $postId,
                            'location_id' => 0,
                        ];
                    }

                    if (!empty($locationData)) {
                        DB::table('post_location')->whereIn('post_id', $postIds)->delete();
                        DB::table('post_location')->insert($locationData);
                    }
                }
            }
        });
        return "İşlem tamamlandı. Her kategoriden 15 post için location ID'leri güncellendi.";
    }
}
