<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Settings;
use App\Models\Enewspaper;
use App\Models\PostArchive;
use Illuminate\Support\Str;
use App\Models\PhotoGallery;
use Illuminate\Http\Request;
use App\Models\OfficialAdvert;
use App\Models\PhotoGalleryImages;
use GPBMetadata\Google\Type\Datetime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Europe/Istanbul');
    }

    public function index()
    {
        $ads21 = Ads::find(21);
        $ads22 = Ads::find(22);

        return view('theme::index', compact('ads21', 'ads22'));
    }

    // public function categoryOLD(Request $request, $slug, $id)
    // {
    //     $category = Category::findOrFail($id);
    //     $posts_slider = Post::where(['publish' => 0, 'category_id' => $id, ['created_at', '<', date('Y-m-d H:i:s')]])
    //         ->whereHas('locations', function ($query) {
    //             $query->where('location_id', 1);
    //         })
    //         ->select('id', 'title', 'description', 'category_id', 'slug', 'images', 'position', 'redirect_link', 'show_title_slide')
    //         ->orderBy('id', 'desc')->paginate(19);
    //     $posts_other = Post::where(['publish' => 0, 'category_id' => $id, ['created_at', '<', date('Y-m-d H:i:s')]])
    //         ->whereHas('locations', function ($query) {
    //             $query->whereNot('location_id', 1);
    //         })
    //         ->select('id', 'title', 'description', 'category_id', 'slug', 'images', 'position', 'redirect_link')
    //         ->orderBy('id', 'desc')->paginate(24);
    //     $hit_popups = Post::where(['publish' => 0, 'category_id' => $id, ['created_at', '<', date('Y-m-d H:i:s')]])->select('id', 'title', 'slug', 'category_id', 'images', 'redirect_link')->orderBy('hit', 'desc')->take(5)->get();
    //     $ads6 =  Ads::where(['id' => 6])->select('id')->first();
    //     $ads7 =  Ads::where(['id' => 7])->select('id')->first();

    //     return view('theme::category', compact('category', 'posts_slider', 'posts_other', 'hit_popups', 'ads6', 'ads7'));
    // }

    public function category(Request $request, $slug)
    {
        $categoryExists = Category::where('slug', $slug)->exists();

        if (!$categoryExists) {

            if (config('app.OLD_AUTHOR') == 1003) {
                //wordpress haber url'lerini yönlendirme
                $postExists = Post::withoutGlobalScopes()->where('slug', $slug)->where('created_at', '<=', now())->exists();
                if ($postExists) {
                    $post = Post::withoutGlobalScopes()->where('slug', $slug)->where('created_at', '<=', now())->with('category')->first();
                    return redirect(route('frontend.post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]), 301);
                } else {

                    return redirect(route('frontend.index'));
                }
            } elseif (config('app.OLD_AUTHOR') == 1004) {
                //Tebilişim Link Yapısı dönüşüm
                $parseUrl = preg_match('/h(\d+)\.htm$/', $slug, $matches);
                $id = $parseUrl ? intval($matches[1]) : null;
                $postExists = Post::withoutGlobalScopes()->where('created_at', '<=', now())->whereId($id)->exists();

                $articleExists = Article::whereId($id)->exists();

                if ($postExists) {

                    $post = Post::with('category')->where('created_at', '<=', now())->findOrFail($id);
                    return redirect(route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]), 301);
                }
                if ($articleExists) {

                    $article = Article::findOrFail($id);
                    return redirect(route('article', ['slug' => $article->slug, 'id' => $article->id]), 301);
                }

                return redirect(route('frontend.index'));
            }
        }
        $category = Category::where('slug', $slug)->first();

        if ($category == null) {
            return redirect(route('frontend.index'));
        }

        $slider_ids = Post::where(['publish' => 0, 'category_id' => $category->id])
            ->where('created_at', '>=', now()->subMonth())
            ->whereHas('locations')
            ->orderBy('id', 'desc')
            ->limit('19')
            ->pluck('id');

        $posts_slider = Post::whereIn('id', $slider_ids)
            ->select('id', 'title', 'description', 'category_id', 'slug', 'images', 'position', 'redirect_link', 'show_title_slide')
            ->orderBy('id', 'desc')
            ->with('category')
            ->get();

        $posts_other = Post::where(['publish' => 0, 'category_id' => $category->id, ['created_at', '<', date('Y-m-d H:i:s')]])
            ->whereNotIn('id', $slider_ids)
            ->select('id', 'title', 'description', 'category_id', 'slug', 'images', 'position', 'redirect_link', 'created_at')
            ->with('category')
            ->orderBy('id', 'desc')->simplePaginate(24);

        $hit_popups = Post::where(['publish' => 0, 'category_id' => $category->id, ['created_at', '<', date('Y-m-d H:i:s')]])
            ->select('id', 'title', 'slug', 'category_id', 'images', 'redirect_link', 'created_at')
            // ->orderBy('hit', 'desc')
            ->with('category')
            ->inRandomOrder()
            ->limit(5)->get();


        $ads6 = Ads::where(['id' => 6])->first();
        $ads7 = Ads::where(['id' => 7])->first();

        return view('theme::category', compact('category', 'posts_slider', 'posts_other', 'hit_popups', 'ads6', 'ads7'));
    }

    private function teBilisimLinkFormat($categoryslug = null, $url)
    {

        if (config('app.OLD_AUTHOR') == 1001) {
            //Tebilişim Link Yapısı dönüşüm
            $parseUrl = preg_match('/h(\d+)\.html$/', $url, $matches);
            $id = $parseUrl ? intval($matches[1]) : null;
            $post = Post::withoutGlobalScopes()->with('category')->where('created_at', '<=', now())
                ->where(['deleted_at' => NULL, 'publish' => 0])
                ->findOrFail($id);

            return redirect(route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]), 301);
        } else if (config('app.OLD_AUTHOR') == 1002) {

            //ÖnemSoft Link Yapısı dönüşüm

            $parseUrl = preg_match('/-(\d+)$/', $url, $matches);
            $id = $parseUrl ? intval($matches[1]) : null;
            if ($categoryslug == "haber" && !blank($id)) {
                $post = Post::withoutGlobalScopes()->with('category')->where('created_at', '<=', now())
                    ->where(['deleted_at' => NULL, 'publish' => 0])
                    ->findOrFail($id);
                return redirect(route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]), 301);
            } else if ($categoryslug == "makale" && !blank($id)) {
                $makale = Article::where('id', $id)->first();
                return redirect(route('article', ['slug' => $makale->slug, 'id' => $makale->id]), 301);
            } else {
                return back();
            }
        } else {
            return back();
        }
    }

    public function post(Request $request, $categoryslug, $slug, $id = null)
    {

        if (!blank($id)) {

            $post = Post::withoutGlobalScopes()->where('created_at', '<=', now())->with('author')
                ->where(['deleted_at' => NULL, 'publish' => 0])
                ->select(
                    'id',
                    'title',
                    'slug',
                    'created_at',
                    'updated_at',
                    'detail',
                    'images',
                    'category_id',
                    'extra',
                    'description',
                    'hit',
                    'keywords',
                    'redirect_link',
                    "author_id"
                )
                ->findOrFail($id);
        } else {
            return $this->teBilisimLinkFormat($categoryslug, $slug);
        }
        $post->updateHitWithoutTimestamp($post->hit);

        if (!blank($post->redirect_link)) {
            return redirect($post->redirect_link);
        }


        $extra = json_decode($post->extra);

        $comments = Comment::where('type', 0)
            ->where('post_id', $id)
            ->where('publish', 0)
            ->get();


        $post_mini_slides = Post::where('publish', 0)
            ->where('category_id', $post->category_id)
            ->where(function ($query) {
                $query->whereHas('locations', function ($query) {
                    $query->whereNot('location_id', [1, 2]);
                });
            })
            ->where('created_at', '<=', now())
            ->whereNotIn('id', [$id])
            ->orderBy('id', 'desc')
            ->with('category')
            ->select(
                'slug',
                'id',
                'images',
                'title',
                'extra',
                'created_at',
                'updated_at',
                'category_id',
            )->limit(20)
            ->get();

            $results = Post::where('publish', 0)
                ->where('category_id', $post->category_id)
                ->whereNotIn('id', [$id])
                ->where(function ($query) {
                    $query->where('created_at', '>=', now()->subDays(2))
                        ->orWhere('created_at', '>=', now()->subWeek());
                })
                ->select('id', 'title', 'slug', 'category_id', 'created_at', 'hit', 'images', 'extra', 'updated_at')
                ->with('category')
                ->orderByRaw('CASE WHEN created_at >= ? THEN 0 ELSE 1 END', [now()->subDays(2)])
                ->inRandomOrder()
                ->limit(19)
                ->get();


        $hit_news = $results->take(10); // İlk 10 haber
        // Kalan 9 haber
        $remaining_posts = $results->slice(10)->take(9);
        // $same_post ve $infiniteurl ayırma
        $same_post = $remaining_posts->take(8); // İlk 8 aynı post
        $infiniteurl = $remaining_posts->last();


        $adsIds = [8, 9, 10];
        $ads = Ads::whereIn('id', $adsIds)->select('id')->get();

        // Elde edilen reklamları id'lerine göre ayıralım
        $ads8 = $ads->where('id', 8)->first();
        $ads9 = $ads->where('id', 9)->first();
        $ads10 = $ads->where('id', 10)->first();

        return view('theme::post', compact('post', 'comments', 'post_mini_slides', 'hit_news', 'extra', 'same_post', 'infiniteurl', 'ads8', 'ads9', 'ads10'));
    }


    public function amppost(Request $request, $categoryslug, $slug, $id = null)
    {

        if (!blank($id)) {

            $post = Post::withoutGlobalScopes()->where('created_at', '<=', now())->with('author')
                ->where(['deleted_at' => NULL, 'publish' => 0])
                ->select(
                    'id',
                    'title',
                    'slug',
                    'created_at',
                    'updated_at',
                    'detail',
                    'images',
                    'category_id',
                    'extra',
                    'description',
                    'hit',
                    'keywords',
                    'redirect_link',
                    "author_id"
                )
                ->findOrFail($id);
            return redirect(route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]), 301);

        } else {
            return $this->teBilisimLinkFormat($categoryslug, $slug);
        }

        $post->updateHitWithoutTimestamp($post->hit);
        $postData = Post::query();

        $categories = Category::where('parent_category', 0)->get();
        $same_post = $postData->whereNotIn('id', [$id])->where(['publish' => 0, 'category_id' => $post->category_id, ['created_at', '<', date('Y-m-d H:i:s')]])->take(6)->orderBy('id', 'desc')->get();

        return view('theme::post_amp', compact('post', 'categories', 'same_post'));
    }

    public function page(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)->first();

        if ($page == null) {
            return redirect(route('frontend.index'));
            exit();
        }


        $hit_popups = Post::where(['publish' => 0, ['created_at', '<', date('Y-m-d H:i:s')]])->select('id', 'title', 'slug', 'images', 'category_id', 'redirect_link', 'created_at')->orderBy('hit', 'desc')->take(5)->get();

        return view('theme::page', compact('page', 'hit_popups'));
    }

    public function photo_galleries()
    {
        $photo_galleries = PhotoGallery::select('id', 'category_id', 'slug', 'title', 'images', 'detail')->where('publish', 0)->orderBy('id', 'desc')->paginate(27);
        $ads11 = Ads::where(['id' => 11])->select('id')->first();
        $ads12 = Ads::where(['id' => 12])->select('id')->first();

        return view('theme::photo_galleries', compact('photo_galleries', 'ads11', 'ads12'));
    }

    public function photo_gallery(Request $request, $categoryslug, $slug, $id)
    {
        $photo_gallery = PhotoGallery::findOrFail($id);
        $photo_gallery_images = PhotoGalleryImages::where('photogallery_id', $id)->orderBy('sortby', 'desc')->get();
        $ads13 = Ads::where(['id' => 13])->select('id')->first();

        return view('theme::photo_gallery', compact('photo_gallery', 'photo_gallery_images', 'ads13'));
    }

    public function video_galleries()
    {
        $video_galleries = Video::select('id', 'category_id', 'slug', 'title', 'images', 'detail')->where('publish', 0)->whereNotIn('category_id', [0])->orderBy('id', 'desc')->paginate(25);
        $ads14 = Ads::where(['id' => 14])->select('id')->first();
        $ads15 = Ads::where(['id' => 15])->select('id')->first();

        return view('theme::video_galleries', compact('video_galleries', 'ads14', 'ads15'));
    }

    public function video_gallery(Request $request, $categoryslug, $slug, $id)
    {
        $video_gallery = Video::findOrFail($id);
        $other_videos = Video::whereNotIn('id', [$id])->where(['publish' => 0, 'category_id' => $video_gallery->category_id])->orderBy('id', 'desc')->take(30)->get();
        $comments = Comment::where(['type' => 2, 'post_id' => $id])->get();
        $ads16 = Ads::where(['id' => 16])->select('id')->first();

        return view('theme::video_gallery', compact('video_gallery', 'other_videos', 'comments', 'ads16'));
    }

    public function authors()
    {
        $authors = User::select('id', 'name', 'status', 'avatar', 'created_at')
            ->whereHas('latestArticle', function ($query) {
                $query->where('publish', 0);
            })
            ->with([
                'latestArticle' => function ($query) {
                    $query->select('article.id', 'article.author_id', 'title', 'slug', 'images', 'detail', 'created_at')
                        ->where('publish', 0);
                }
            ])
            ->joinSub(
                DB::table('article')
                    ->select('author_id', DB::raw('MAX(created_at) as latest_article_created_at'))
                    ->where('publish', 0)
                    ->groupBy('author_id'),
                'latest_articles',
                'users.id',
                '=',
                'latest_articles.author_id'
            )
            ->orderByDesc('latest_articles.latest_article_created_at')
            ->paginate(5);


        $hit_popups = Post::where(['publish' => 0, ['created_at', '<', date('Y-m-d H:i:s')]])->select('id', 'title', 'slug', 'images', 'category_id', 'redirect_link', 'created_at')
            ->with('category:id,title')
            ->orderBy('hit', 'desc')->take(5)->get();
        $ads17 = Ads::where(['id' => 17])->select('id')->first();

        return view('theme::authors', compact('authors', 'hit_popups', 'ads17'));
    }

    public function article(Request $request,$author= '', $slug, $id)
    {
        $article = Article::where(['publish' => 0, 'id' => $id])->first();
        $other_articles = Article::where(['publish' => 0, 'author_id' => $article->author_id])
            ->where('created_at', '<=', now())
            ->whereNotIn('id', [$id])->orderBy('id', 'desc')->get();
        $comments = Comment::where(['type' => 3, 'post_id' => $id])->get();

        $adsIds = [18, 19, 20];
        $ads = Ads::whereIn('id', $adsIds)->select('id')->get();

        // Elde edilen reklamları burada sorgulayıp alalım
        $ads18 = $ads->where('id', 18)->first();
        $ads19 = $ads->where('id', 19)->first();
        $ads20 = $ads->where('id', 20)->first();


        return view('theme::article', compact('article', 'other_articles', 'comments', 'ads18', 'ads19', 'ads20'));
    }


        public function newArticle(Request $request,$author, $slug)
    {
        $article = Article::where(['publish' => 0, 'slug' => $slug])->with('author')->firstOrFail();
        $other_articles = Article::where(['publish' => 0, 'author_id' => $article->author_id])
            ->where('created_at', '<=', now())->with('author')
            ->whereNotIn('id', [$article->id])->orderBy('id', 'desc')->get();
        $comments = Comment::where(['type' => 3, 'post_id' => $article->id])->with('user')->get();

        $adsIds = [18, 19, 20];
        $ads = Ads::whereIn('id', $adsIds)->select('id')->get();

        // Elde edilen reklamları burada sorgulayıp alalım
        $ads18 = $ads->where('id', 18)->first();
        $ads19 = $ads->where('id', 19)->first();
        $ads20 = $ads->where('id', 20)->first();


        return view('theme::article', compact('article', 'other_articles', 'comments', 'ads18', 'ads19', 'ads20'));
    }


    public function articlesend(Request $request)
    {
        $rules = [
            'articletitle' => 'required',
            'articledetail' => 'required',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3000',
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'image' => 'Lütfen geçerli bir resim dosyası seçin.',
            'mimes' => 'Lütfen geçerli bir resim dosyası seçin.',

        ];
        $this->validate($request, $rules, $customMessages);
        $articleDetail = $request->input('articledetail');
        $articleDetail = str_replace('<script>', '<ers-script>', $articleDetail);
        $articleDetail = str_replace('</script>', '</ers-script>', $articleDetail);

        $article = new Article();
        $article->title = strip_tags($request->articletitle);
        $article->slug = slug_format($request->articletitle);
        $article->detail = $articleDetail;
        $article->created_at = !blank($request->publish_date) ? $request->publish_date : now();
        $article->publish = !blank($request->publish_date) ? 2 : 1;
        $article->author_id = auth()->user()->id;

        if ($request->hasFile('images')) {
            if ($request->file('images')->isValid()) {
                $images_name = $article->slug . '-' . time() . '.ana-manset.' . $request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $article->images = $images_name;
            }
        }

        if ($article->save()) {
            return redirect(route('userprofile'));
        } else {
            return back();
        }
    }

    public function commentsubmit(Request $request, $type, $post_id)
    {
         // reCAPTCHA kontrolü
         $recaptchaResponse = $request->input('g-recaptcha-response');
         $fullUrl = $request->fullUrl(); // Tüm URL'yi alır
    $lastSegment = collect(explode('/', parse_url($fullUrl, PHP_URL_PATH)))->last();

        if (!$recaptchaResponse) {
            $previousUrl = url()->previous() . '#yorumlar' . $lastSegment;
            return redirect($previousUrl)->with('captcha_error', 'Lütfen robot olmadığınızı doğrulayın!');
        }

        $settings = Settings::first();
        $recaptcha_secret_key = null;
        if ($settings && isset($settings->magicbox)) {
            $jsondata = json_decode($settings->magicbox, true);
            $recaptcha_secret_key = $jsondata['google_recaptcha_secret_key'] ?? null;
        }


         $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => $recaptcha_secret_key,
        'response' => $recaptchaResponse,
        'remoteip' => $request->ip(),
        ]);

        $body = $response->json();

        if (!($body['success'] ?? false)) {
            $previousUrl = url()->previous() . '#yorumlar' . $lastSegment;
            return redirect($previousUrl)->with('captcha_error', 'reCAPTCHA doğrulaması başarısız oldu.');
        }


        $comment = new Comment();
        $comment->title = strip_tags($request->name);
        $comment->slug = Str::slug(strip_tags($request->name));
        $comment->email = strip_tags($request->email);
        $comment->detail = strip_tags($request->detail);
        $comment->type = intval(strip_tags($type));
        $comment->post_id = intval(strip_tags($post_id));

        $settings = json_decode(Storage::disk('public')->get("settings.json"), TRUE);
        if (json_decode($settings["magicbox"], TRUE)["generalcomment"] == 0) {
            $comment->publish = 1;
        } else {
            $comment->publish = 0;
        }

        if ($comment->save()) {
             $previousUrl = url()->previous() . '#yorumlar' . $lastSegment;
            return redirect($previousUrl)->with('success_comment', 'Yorumunuz alınmıştır!');
        }
    }

    public function userloginfrontend(Request $request)
    {
        $email = strip_tags($request->usermail);
        $check = User::where(['email' => $email])->select('id', 'status', 'password')->first();

        if (Hash::check($request->userpassword, $check->password) == true and ($check->status == 0 or $check->status == 3)) {
            Auth::loginUsingId($check->id);
            return response()->json("ok");
        } else {
            return response()->json("err");
        }
    }

    public function userregisterfrontend(Request $request)
    {
        $check = User::where(['email' => strip_tags($request->usermailreg)])->first();

        if (isset($check)) {
            return response()->json("err");
        } else {
            $user = new User();
            $user->name = strip_tags($request->usernamereg);
            $user->email = strip_tags($request->usermailreg);
            $user->password = Hash::make($request->userpasswordreg);
            $user->status = 0;
            $user->active = 1;
            if ($user->save()) {
                Auth::loginUsingId($user->id);
                return response()->json("ok");
            }
        }
    }

    public function userprofile()
    {
        if (auth()->user() != null) {
            $userprofile = auth()->user();
            $articles = Article::where('author_id', $userprofile->id)->get();
            return view('theme::userprofile', compact('userprofile', 'articles'));
        } else {
            return redirect(route('frontend.index'));
        }
    }

    public function userprofileupdate(Request $request)
    {
        $rules = [
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'image' => 'Resim formatını kontrol edin.',
            'mimes' => 'Resim desteklenen formatlar şunlardır: png,jpg,gif,svg',
        ];
        $this->validate($request, $rules, $customMessages);

        if (!isset(auth()->user()->id)) {
            return redirect(route('frontend.index'));
        }

        $user = User::find(auth()->user()->id);
        $user->name = strip_tags($request->name);
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->phone = strip_tags($request->phone);
        $user->about = strip_tags($request->about);

        if ($request->hasFile('avatar')) {
            if ($request->file('avatar')->isValid()) {
                $avatar_name = slug_format($user->name, '-') . '-' . time() . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('uploads'), $avatar_name);
            }
            $user->avatar = $avatar_name;
        }

        if ($user->save()) {
            return redirect(route('userprofile'));
        } else {
            return back();
        }
    }

    public function userlogout()
    {
        if (auth()->check()) {
            Auth::logout();
            return redirect(route('frontend.index'));
        } else {
            return redirect(route('frontend.index'));
        }
    }

    public function search(Request $request)
    {
        $slugSearch = $request->query('search');

        if (!$slugSearch) {
            return redirect()->back()->with('error', 'Arama terimi giriniz.');
        }

        $posts = Post::where(function ($query) use ($slugSearch) {
            $formatSlug = slug_format($slugSearch);

            $query->whereRaw("
                REPLACE(
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        REPLACE(LOWER(keywords), ' ', '-'),
                                                    'ı','i'),
                                                'ğ','g'),
                                            'ü','u'),
                                        'ş','s'),
                                    'ö','o'),
                                'ç','c'),
                            'İ','i'),
                        'Ğ','g'),
                    'Ü','u'),
                'Ş','s') LIKE ?
            ", ["%{$formatSlug}%"]);
        })
            ->where(['publish' => 0, 'deleted_at' => null])
            ->orderBy('created_at', 'desc')
            ->paginate(40)
            ->appends(['search' => $slugSearch]);


        return view('theme::search', compact('posts'));
    }

    public function sitemap()
    {
        $posts = Post::where('publish', 0)->where('created_at', '<=', now())
            ->select('id', 'title', 'slug', 'category_id', 'created_at')
            ->orderBy('id', 'desc')
            ->limit(1000)->get();

        return response()->view('theme::sitemap', compact('posts'))->header('Content-Type', 'application/xml');
    }

    public function sitemapNews()
    {
        $posts = Post::where('publish', 0)->where('created_at', '<=', now())
            ->select('id', 'title', 'slug', 'category_id', 'created_at')
            ->orderBy('id', 'desc')->take(1000)->get();

        return response()->view('theme::sitemap-news', compact('posts'))->header('Content-Type', 'application/xml');
    }


    public function sitemapgoogle()
    {
        $posts = Post::where('publish', 0)->where('created_at', '<=', now())
            ->select('id', 'title', 'slug', 'category_id', 'created_at')
            ->orderBy('id', 'desc')->take(1000)->get();

        return response()->view('theme::sitemapgoogle', compact('posts'))->header('Content-Type', 'application/xml');
    }

    public function maintenance()
    {
        $settings = json_decode(Storage::disk('public')->get("settings.json"), TRUE);
        if ($settings["maintenance"] == 0) {
            return redirect(route('frontend.index'));
            exit();
        }

        return view('themes.' . json_decode($settings["magicbox"])->sitetheme . '.maintenance', compact('settings'));
    }



    public function rss($category = null)
    {

        $posts = Post::where('publish', 0)->where('created_at', '<=', now());

        if (!blank($category)) {

            $category = Category::where('slug', $category)->firstOrFail();
            $posts = $posts->where('category_id', $category->id);
        }

        $posts = $posts->select('id', 'title', 'slug', 'category_id', 'created_at', 'description')
            ->orderBy('id', 'desc')
            // ->take(1000)
            ->get();

        return response()->view('theme::rss', compact('posts'))->header('Content-Type', 'application/xml');
    }


    public function officialAdvert(Request $request)
    {

        $query = OfficialAdvert::where('publish', 0)->select('id', 'title', 'created_at', 'images')->orderBy('id', 'desc');

        if ($request->filled('date')) {
            $date = $request->date;
            $query->whereDate('create_date', $date);
        } else {
            $query->whereDate('create_date', Carbon::today());
        }

        if ($request->has('ilan_id') && $request->ilan_id) {
            $ilanId = $request->ilan_id;
            $query->orWhere('id', $ilanId);
        }
        $officialAdverts = $query->paginate(30);
        // ->appends($request->all());
        return view('theme::official_adverts', compact('officialAdverts'));
    }

    public function officialAdvertDetail($id)
    {
        $officialAdvert = OfficialAdvert::where('publish', 0)->with('category')->findOrFail($id);
        return view('theme::official_advert_detail', compact('officialAdvert'));
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



    public function enews()
    {

        $selectedDate = request('date') ?? null;
        if ($selectedDate) {
            $enews = Enewspaper::where('date', $selectedDate)
                ->select('id', 'title', 'images', 'date')
                ->get();
        } else {
            $enews = Enewspaper::select(['id', 'title', 'images', 'date'])->orderBy('date', 'desc')->limit(9)->get();
        }


        return view('theme::enews', compact('enews', 'selectedDate'));
    }
    public function eNewsDetail($id)
    {

        $news = Enewspaper::with('getImages')->find($id);
        return view('theme::enews_detail', compact('news'));
    }

    public function enewsImages($id)
    {

        $enews = Enewspaper::with('getImages')->find($id);
        return response()->json(['data' => $enews]);
    }

    public function generateCategorySitemap()
    {
        $categories = Category::all();

        $xmlDeclaration = '<?xml version="1.0" encoding="UTF-8"?>';
        $xmlStylesheet = '<?xml-stylesheet type="text/xsl" href="' . asset('sitemap-style.xsl') . '"?>';

        // Kategorileri view'a gönderiyoruz
        $content = view('theme::sitemap-for-category', compact('categories', 'xmlDeclaration', 'xmlStylesheet'))->render();

        // XML dosyasını kaydet
        $path = public_path('sitemaps/sitemap-category.xml');
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        file_put_contents($path, $content);

        return 'Kategori sitemap dosyası oluşturuldu!';
    }

    public function generateArticleSitemap($year = null)
    {
        $months = [];

        if ($year !== null && $year !== 'all') {
            // Belirli bir yıl verildiyse, o yılın TÜM aylarını işle
            for ($month = 1; $month <= 12; $month++) {
                $months[] = [
                    'start' => Carbon::create($year, $month, 1)->startOfMonth(),
                    'end' => Carbon::create($year, $month, 1)->endOfMonth(),
                    'name' => Carbon::create($year, $month, 1)->format('Y-m')
                ];
            }
        } elseif ($year === 'all') {
            // Tüm yılları işle
            $years = Article::withoutGlobalScopes()->where('publish', 0)
                ->where('created_at', '<=', now())
                ->with(['author:id,name']) // Eğer kategori ilişkisi varsa
                ->selectRaw('YEAR(created_at) as year')
                ->distinct()
                ->orderBy('year', 'asc')
                ->pluck('year')
                ->toArray();

            foreach ($years as $yearValue) {
                for ($month = 1; $month <= 12; $month++) {
                    $months[] = [
                        'start' => Carbon::create($yearValue, $month, 1)->startOfMonth(),
                        'end' => Carbon::create($yearValue, $month, 1)->endOfMonth(),
                        'name' => Carbon::create($yearValue, $month, 1)->format('Y-m')
                    ];
                }
            }
        } else {
            // Yıl verilmediyse, SADECE geçerli ayı işle
            $months[] = [
                'start' => now()->startOfMonth(),
                'end' => now()->endOfMonth(),
                'name' => now()->format('Y-m')
            ];
        }
        // Her ay için XML oluştur
        foreach ($months as $month) {
            $articles = Article::where('created_at', '<=', now())
                ->whereBetween('created_at', [$month['start'], $month['end']])
                ->with(['author:id,name']) // Eğer kategori ilişkisi varsa
                ->select(['id', 'title', 'slug', 'created_at', 'author_id']) // Dizi şeklinde yazılmalı
                ->orderBy('id', 'desc');

            $chunkIndex = 1;

            $articles->chunk(1000, function ($countArticles) use (&$chunkIndex, $month) {
                if (blank($countArticles)) {
                    return;
                }
                $xmlDeclaration = '<?xml version="1.0" encoding="UTF-8"?>';
                $xmlStylesheet = '<?xml-stylesheet type="text/xsl" href="' . asset('sitemap-style.xsl') . '"?>';
                $content = view('theme::sitemap-for-article', [
                    'articles' => $countArticles,
                    'xmlDeclaration' => $xmlDeclaration,
                    'xmlStylesheet' => $xmlStylesheet
                ])->render();

                // $path = public_path('sitemaps/sitemap-article-' . $month['name'] . '.xml');
                $filename = 'sitemap-article-' . $month['name'] . '-' . $chunkIndex . '.xml';
                $path = public_path('sitemaps/' . $filename);

                if (!file_exists(dirname($path))) {
                    mkdir(dirname($path), 0755, true);
                }
                file_put_contents($path, $content);
                $chunkIndex++;
            });
        }

        return 'Makale sitemap dosyaları oluşturuldu!';
    }


    public function generateNewsSitemap($year = null)
    {
        $months = [];

        if ($year !== null && $year !== 'all') {
            // Belirli bir yıl verildiyse, o yılın TÜM aylarını işle
            for ($month = 1; $month <= 12; $month++) {
                $months[] = [
                    'start' => Carbon::create($year, $month, 1)->startOfMonth(),
                    'end' => Carbon::create($year, $month, 1)->endOfMonth(),
                    'name' => Carbon::create($year, $month, 1)->format('Y-m')
                ];
            }
        } elseif ($year === 'all') {
            // Tüm yılları işle
            $years = Post::withoutGlobalScopes()->where('publish', 0)
                ->where('created_at', '<=', now())
                ->where('deleted_at', NULL)
                ->selectRaw('YEAR(created_at) as year')
                ->distinct()
                ->orderBy('year', 'asc')
                ->pluck('year')
                ->toArray();

            foreach ($years as $yearValue) {
                for ($month = 1; $month <= 12; $month++) {
                    $months[] = [
                        'start' => Carbon::create($yearValue, $month, 1)->startOfMonth(),
                        'end' => Carbon::create($yearValue, $month, 1)->endOfMonth(),
                        'name' => Carbon::create($yearValue, $month, 1)->format('Y-m')
                    ];
                }
            }
        } else {
            // Yıl verilmediyse, SADECE geçerli ayı işle
            $months[] = [
                'start' => now()->startOfMonth(),
                'end' => now()->endOfMonth(),
                'name' => now()->format('Y-m')
            ];
        }

        foreach ($months as $month) {
            $posts = Post::withoutGlobalScopes()->where('publish', 0)
                ->where('created_at', '<=', now())->where('deleted_at', NULL)
                ->whereBetween('created_at', [$month['start'], $month['end']])
                ->with('category')
                ->select(['id', 'title', 'slug', 'keywords', 'category_id', 'created_at'])
                ->orderBy('id', 'desc');
            $chunkIndex = 1;

            $posts->chunk(1000, function ($countPosts) use (&$chunkIndex, $month) {
                if (blank($countPosts)) {
                    return;
                }
                $xmlDeclaration = '<?xml version="1.0" encoding="UTF-8"?>';
                $xmlStylesheet = '<?xml-stylesheet type="text/xsl" href="' . asset('sitemap-style.xsl') . '"?>';

                $content = view('theme::sitemap-for-news', [
                    'posts' => $countPosts,
                    'xmlDeclaration' => $xmlDeclaration,
                    'xmlStylesheet' => $xmlStylesheet
                ])->render();

                // XML dosyasını kaydet
                $filename = 'sitemap-news-' . $month['name'] . '-' . $chunkIndex . '.xml';
                $path = public_path('sitemaps/' . $filename);

                if (!file_exists(dirname($path))) {
                    mkdir(dirname($path), 0755, true);
                }
                file_put_contents($path, $content);
                $chunkIndex++;
            });
        }

        if ($year === 'all') {
            return "Tüm yılların sitemap'leri oluşturuldu!";
        } elseif ($year !== null) {
            return "$year yılına ait tüm ayların sitemap'leri oluşturuldu!";
        } else {
            return "Geçerli ayın sitemap'i oluşturuldu!";
        }
    }

    public function sitemapList(Request $request)
    {
        $sitemapPath = public_path('sitemaps');
        $xmlFiles = [];

        if (is_dir($sitemapPath)) {
            $files = scandir($sitemapPath);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'xml') {

                    $timestamp = filemtime($sitemapPath . '/' . $file); // Varsayılan timestamp
                    $year = 0;
                    $month = 0;
                    $order = 0;
                    if (preg_match('/sitemap-news-(\d{4})-(\d{2})-(\d+)\.xml/', $file, $matches)) {
                        $year = $matches[1];
                        $month = $matches[2];
                        $order = +$matches[3];

                        // O ayın son gününü timestamp olarak kullan
                        $timestamp = Carbon::create($year, $month, 1)->endOfMonth()->timestamp;
                        // $order = $order + 1;
                    }


                    // Dosya adından tarihi çıkar
                    // if (preg_match('/sitemap-news-(\d{4})-(\d{2})\.xml/', $file, $matches)) {
                    //     $year = $matches[1];
                    //     $month = $matches[2];

                    //     // O ayın son gününü timestamp olarak kullan
                    //     $timestamp = Carbon::create($year, $month, 1)->endOfMonth()->timestamp;
                    // }

                    $xmlFiles[] = [
                        'loc' => url('sitemaps/' . $file),
                        'title' => url('sitemaps/' . $file),
                        'lastmod' => date('Y-m-d H:i', filemtime($sitemapPath . '/' . $file)),
                        'timestamp' => $timestamp,
                        'year' => $year,
                        'month' => $month,
                        'order' => $order,

                    ];
                }
            }
        }
        usort($xmlFiles, function ($a, $b) {
            if ($a['year'] != $b['year']) {
                return $b['year'] > $a['year']; // Yeni yıllar önce
            }

            if ($a['month'] != $b['month']) {
                return $b['month'] > $a['month']; // Yeni aylar önce
            }

            return $b['order'] > $a['order']; // Yüksek numaralar önce
        });

        $xmlDeclaration = '<?xml version="1.0" encoding="UTF-8"?>';
        $xmlStylesheet = '<?xml-stylesheet type="text/xsl" href="' . asset('sitemap-list-style.xsl') . '"?>';

        return response()->view('theme::sitemap-list', compact('xmlFiles', 'xmlDeclaration', 'xmlStylesheet'))
            ->header('Content-Type', 'application/xml');
    }

    public function loadMore(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $page = $request->input('page', 1);

        // Slider'daki ID'leri alıyoruz ki tekrar göstermeyelim
        $slider_ids = Post::where(['publish' => 0, 'category_id' => $category->id])
            ->where('created_at', '<', now())
            ->whereHas('locations', function ($query) {
                $query->whereIn('location_id', [1, 2]);
            })
            ->pluck('id');

        $posts_other = Post::where([
            ['publish', '=', 0],
            ['category_id', '=', $category->id],
            ['created_at', '<', now()]
        ])
            ->whereNotIn('id', $slider_ids)
            ->select('id', 'title', 'description', 'category_id', 'slug', 'images', 'position', 'redirect_link', 'created_at')
            ->with('category')
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'page', $page);

        $html = view('themes.v3.main._posts_list', compact('posts_other'))->render();

        return response()->json([
            'html' => $html,
            'nextPage' => $posts_other->currentPage() < $posts_other->lastPage() ? $posts_other->currentPage() + 1 : null
        ]);
    }
}
