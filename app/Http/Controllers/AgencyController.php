<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Article;
use App\Models\Category;
use App\Models\PhotoGallery;
use App\Models\Post;
use App\Models\PostIha;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgencyController extends Controller
{
    protected $summary_length = 150;
    protected $attributes = [ 'limit' => '20' ];


    public function postIha()
    {
        $posts = PostIha::orderBy('id', 'desc')->paginate(100);
        $categories = Category::all();
        $agency = Agency::find(1);

        return view('admin.post_iha.index', compact('posts','categories','agency'));
    }

    public function post_dha()
    {

    }

    public function ihadantest()
    {
        $agency = Agency::find(1);
        if(empty($agency->agency_one_user_code) or empty($agency->agency_one_user_name) or empty($agency->agency_one_user_pass)){
            return "Ajans bilgileri kayıt edilmemiş. Genel Ayarlar > Ajanslar seçeneği altından kontrol edin.";
        }

        return redirect("http://abonerss.iha.com.tr/xml/standartrss?UserCode=".$agency->agency_one_user_code ."&UserName=".$agency->agency_one_user_name ."&UserPassword=" .$agency->agency_one_user_pass."&tip=1&UstKategori=0&Kategori=0&Sehir=0&wp=0&tagp=0");
    }


    public function ihaRun()
    {
        $agency = Agency::find(1);
        if(empty($agency->agency_one_user_code) or empty($agency->agency_one_user_name) or empty($agency->agency_one_user_pass)){
            return "Ajans bilgileri kayıt edilmemiş. Genel Ayarlar > Ajanslar seçeneği altından kontrol edin.";
        }

        $url = "http://abonerss.iha.com.tr/xml/standartrss?UserCode=".$agency->agency_one_user_code ."&UserName=".$agency->agency_one_user_name ."&UserPassword=" .$agency->agency_one_user_pass."&tip=1&UstKategori=0&Kategori=0&Sehir=0&wp=0&tagp=0";

        $response = $this->fetchUrl($url);
        $xml = new \SimpleXMLElement($response);
        $result = [];
        $i = 0;
        foreach ($xml->channel->item as $item) {

            // Otomatik yayınlanma seçeneği aktif ise  0, Değilse 1
            if($agency->auto_publish==0){
                $check = Post::where('haberkodu', $item->HaberKodu)->first();
            }else{
                $check = PostIha::where('haberkodu', $item->HaberKodu)->first();
            }

            if($check==NULL){
                if ($this->attributes['limit'] > $i) {
                    if($agency->auto_publish==0){
                        $news = new Post();
                    }else{
                        $news = new PostIha();
                    }

                    $news->haberkodu = (string)$item->HaberKodu;
                    $news->title = (string)$item->title;
                    $news->slug = slug_format(strip_tags($item->title));
                    $news->short_detail = strip_tags((string)$this->shortenString($item->description, $this->summary_length));
                    $news->created_at = date('d.m.Y H:i', strtotime($item->pubDate));
                    $news->category_id = getCategoryId($this->titleShort($item->Kategori));
                    $news->city = (!empty($item->Sehir) ? $this->titleCase($item->Sehir) : '');
                    $news->agency = "iha";
                    if($agency->location=="am"){$news->am=1;}elseif($agency->location=="um"){$news->um=1;}elseif($agency->location=="fh"){$news->fh=1;}elseif($agency->location=="gm"){$news->gm=1;}elseif($agency->location=="bm"){$news->bm=1;}elseif($agency->location=="sd"){$news->sd=1;}
                    if (isset($item->images) && count($item->images->image) > 0) {
                        $newsimages = "";
                        $imgkey = 0;
                        foreach ($item->images->image as $image) {
                            $i_title = md5($news->haberkodu).$imgkey.'-'.$news->slug.'.jpg';
                            copy((string)$image, 'uploads/'.$i_title);
                            $newsimages .= '<img src="/uploads/'.$i_title.'" class="w-100">';
                            $imgkey++;
                        }

                        $news->image = '/uploads/'.$i_title;
                        $news->image_manset = '/uploads/'.$i_title;
                        $news->image_ust_manset = '/uploads/'.$i_title;
                        $news->image_besli_manset = '/uploads/'.$i_title;
                        $news->image_facebook = '/uploads/'.$i_title;




                        unset($news->images);
                    }
                    if(!empty($newsimages)){
                        $news->detail = (string)$item->description.(string)$newsimages;
                    }else{
                        $news->detail = (string)$item->description;
                    }

                    $news->save();
                    $result[] = $news;
                    $i++;
                }
            }
        }
        toastr()->success('Bot çalıştırıldı ve havuza eklendi.');
        activity()->causedBy(auth()->user()->id)->on(new PostIha())->useLog("ihabotrun")->withProperties(['IP' => GetIP()])->log('IHA Bot Çalıştırıldı');
        return back();
    }

    public function dhaRun()
    {

    }

    public function fetchUrl($url, $method = 'GET', $options = [])
    {
        $client = new Client();
        $res = $client->request($method, $url, $options);
        if ($res->getStatusCode() == 200) {
            return (string)$res->getBody();
        }
        return '';
    }

    protected function shortenString($str, $len)
    {
        if (strlen($str) > $len) {
            $str = rtrim(mb_substr($str, 0, $len, 'UTF-8'));
            $str = substr($str, 0, strrpos($str, ' '));
            $str .= '...';
            $str = str_replace(',...', '...', $str);
        }
        return $str;
    }

    protected function titleCase($str)
    {
        $str = mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
        return $str;
    }

    protected function titleShort($str)
    {
        return mb_strtolower($str, 'UTF-8');
    }

    public function publishdelete(Request $request)
    {
        foreach ($request->postid as $item){ PostIha::destroy($item); }
        toastr()->success('İşlem başarılı şekilde tamamlanmıştır.');
        activity()->causedBy(auth()->user()->id)->on(new PostIha())->useLog("publish_delete")->withProperties(['IP' => GetIP()])->log('Toplu Silindi');

        return back();
    }

    public function postbotedit($id)
    {
        $post = PostIha::find($id);
        $categories = Category::all();
        $sources = Source::all();
        $photogalleries = PhotoGallery::where('publish', 0)->orderBy('id', 'desc')->take(10)->get();
        $videogalleries = VideoGallery::where('publish', 0)->orderBy('id', 'desc')->take(10)->get();
        $related_posts = PostIha::where('publish', 0)->whereNotIn('id', [$id])->orderBy('id', 'desc')->take(20)->get();
        $related_post = PostIha::where('id', $id)->pluck('related_news_ids')->first();
        $articles = Article::where('publish', 0)->orderBy('id', 'desc')->take(20)->get();
        $generalsetting = GeneralSetting::find(1);
        $g_result = $generalsetting->select('site_category_headline_description_show','site_headline_width','site_headline_height','site_headline_quality')->first();

        return view('admin.post_iha.edit', compact('post', 'categories', 'photogalleries', 'videogalleries','sources','related_posts','related_post','articles','g_result'));
    }

    public function postbotupdate(Request $request, $id)
    {
        $request->validate(
            [
                'title' => 'required',
                'detail' => 'required',
            ],
            [
                'title.required' => 'Başlık gereklidir.',
                'detail.required' => 'Haber detayı gereklidir.',
            ]
        );

        $post = PostIha::findOrFail($id);
        $post->category_id = request('category_id');
        $post->source_id = request('source_id');
        $now = strtotime(date('Y-m-d H:i:s'));
        $newdate = strtotime(date('Y-m-d H:i:s', strtotime($request->created_at)));
        if($newdate>$now){ $post->created_at = date('Y-m-d H:i:s', strtotime($request->created_at)); }
        $post->title_color = request('title_color');
        $post->title = strip_tags(request('title'));
        $post->slug = slug_format(strip_tags(request('title')));
        $post->keywords = strip_tags(request('keywords'));
        ($request->title_bold=="on") ? $post->title_bold = 1 : $post->title_bold = 0 ;
        $post->short_detail = strip_tags(request('short_detail'));
        ($request->am=="on") ? $post->am = 1 : $post->am = 0 ;
        ($request->um=="on") ? $post->um = 1 : $post->um = 0 ;
        ($request->fh=="on") ? $post->fh = 1 : $post->fh = 0 ;
        ($request->gm=="on") ? $post->gm = 1 : $post->gm = 0 ;
        ($request->oc=="on") ? $post->oc = 1 : $post->oc = 0 ;
        ($request->bm=="on") ? $post->bm = 1 : $post->bm = 0 ;
        ($request->hb=="on") ? $post->hb = 1 : $post->hb = 0 ;
        ($request->sd=="on") ? $post->sd = 1 : $post->sd = 0 ;
        ($request->oh=="on") ? $post->oh = 1 : $post->oh = 0 ;
        $post->detail = request('detail');
        if(!empty($request->related_news_ids)): $post->related_news_ids = implode(',', $request->related_news_ids); endif;
        $post->article_id = request('article_id');
        $post->photogallery_id = request('photogallery_id');
        $post->videogallery_id = request('videogallery_id');
        ($request->comment_close=="on") ? $post->comment_close = 1 : $post->comment_close = 0;
        ($request->ads_close=="on") ? $post->ads_close = 1 : $post->ads_close = 0;
        ($request->only_show_sitemap=="on") ? $post->only_show_sitemap = 1 : $post->only_show_sitemap = 0;
        $post->video_embed = request('video_embed');
        ($request->publish=="on") ? $post->publish = 0 : $post->publish = 1;
        $post->hit = (!empty(request('hit'))) ? request('hit') : 0 ;
        $post->user_id = Auth::user()->id;
        $post->redirect_url = strip_tags(request("redirect_url"));
        $post->image_source = strip_tags(request("image_source"));


        if (request()->hasFile('image')) {
            $this->validate(request(), array('image' => 'sometimes|mimes:png,jpg,jpeg,gif,webp|max:4096'));
            $image = request()->file('image');
            $filename = time() . '-' . $post->slug . '.' . $image->extension();
            if ($image->isValid()) {
                $endfolder = 'uploads';
                $file_dir = "/uploads/".$filename;
                $image->move($endfolder, $filename);
                $post->image = $file_dir;
            }

        }

        if (request()->hasFile('image_besli_manset')) {
            $this->validate(request(), array('image_besli_manset' => 'sometimes|mimes:png,jpg,jpeg,gif,webp|max:4096'));
            $image_besli_manset = request()->file('image_besli_manset');
            $filename_image_besli_manset = time() . '-besli-manset-' . $post->slug . '.' . $image_besli_manset->extension();
            if ($image_besli_manset->isValid()) {
                $endfolder = 'uploads';
                $file_dir = "/uploads/".$filename_image_besli_manset;
                $image_besli_manset->move($endfolder, $filename_image_besli_manset);
                $post->image_besli_manset = $file_dir;
            }

        }

        if (request()->hasFile('image_manset')) {
            $generalsetting = GeneralSetting::find(1);
            $g_result = $generalsetting->select('site_category_headline_description_show','site_headline_width','site_headline_height','site_headline_quality')->first();
            $this->validate(request(), array('image_manset' => 'sometimes|mimes:png,jpg,jpeg,gif,webp|max:4096'));
            $image_image_manset = request()->file('image_manset');
            $filename_image_manset = time() . '-manset-' . $post->slug . '.' . $image_image_manset->extension();
            if ($image_image_manset->isValid()) {
                $endfolder = 'uploads';
                $file_dir = "/uploads/".$filename_image_manset;
                $image_image_manset->move($endfolder, $filename_image_manset);
                $post->image_manset = $file_dir;
            }

        }

        if (request()->hasFile('image_ust_manset')) {
            $this->validate(request(), array('image_ust_manset' => 'sometimes|mimes:png,jpg,jpeg,gif,webp|max:4096'));
            $image_image_ust_manset = request()->file('image_ust_manset');
            $filename_image_ust_manset = time() . '-ust-manset-' . $post->slug . '.' . $image_image_ust_manset->extension();
            if ($image_image_ust_manset->isValid()) {
                $endfolder = 'uploads';
                $file_dir = "/uploads/".$filename_image_ust_manset;
                $image_image_ust_manset->move($endfolder, $filename_image_ust_manset);
                $post->image_ust_manset = $file_dir;
            }

        }

        if (request()->hasFile('image_facebook')) {
            $this->validate(request(), array('image_facebook' => 'sometimes|mimes:png,jpg,jpeg,gif,webp|max:4096'));
            $image_image_facebook = request()->file('image_facebook');
            $filename_image_facebook = time() . '-facebook-' . $post->slug . '.' . $image_image_facebook->extension();
            if ($image_image_facebook->isValid()) {
                $endfolder = 'uploads';
                $file_dir = "/uploads/".$filename_image_facebook;
                $image_image_facebook->move($endfolder, $filename_image_facebook);
                $post->image_facebook = $file_dir;
            }

        }

        if($post->save()){
            toastr()->success('İşlem başarılı şekilde tamamlanmıştır.');
            activity()->causedBy(auth()->user()->id)->on(PostIha::find($post->id))->useLog("edit_success")->withProperties(['IP' => GetIP()])->log('Düzenleme Yapıldı');
            return redirect()->route('postbot.edit', ['id' => $id]);
        }else {
            activity()->causedBy(auth()->user()->id)->on(PostIha::find($id))->useLog("edit_error")->withProperties(['IP' => GetIP()])->log('Düzenleme Yapılamadı');
            return back();
        }
    }


    public function postbotdestroy($id)
    {
        $post = PostIha::destroy($id);
        if($post){
            toastr()->success('İşlem başarılı şekilde tamamlanmıştır.');
            activity()->causedBy(auth()->user()->id)->on(new PostIha())->useLog("delete_success")->withProperties(['IP' => GetIP(),'destroy_id'=>$id])->log('Silindi');
            return back();
        }else {
            toastr()->error('İşlem sırasında bir hata meydana gelmiştir.');
            activity()->causedBy(auth()->user()->id)->on(new PostIha())->useLog("delete_error")->withProperties(['IP' => GetIP(),'destroy_id'=>$id])->log('Silinemedi');
            return back();
        }
    }

    public function switchsave(Request $request)
    {
        ($request->old_value==1) ? $new_value = 0 : $new_value = 1;

        $result = DB::table('post_iha')->where('id', $request->post_id)->update([$request->input_name => $new_value ]);
        activity()->causedBy(auth()->user()->id)->on(PostIha::find($request->post_id))->useLog("edit_success")->withProperties(['IP' => GetIP()])->log('Düzenleme Yapıldı');

        return response($result);
    }

    public function botdatamove(Request $request)
    {
        foreach (PostIha::all() as $key => $item){
            $check = Post::where('haberkodu', $item->haberkodu)->first();
            if($check==NULL){
                $post = new Post();
                $post->haberkodu = $item->haberkodu;
                $post->title = $item->title;
                $post->slug = $item->slug;
                $post->short_detail = $item->short_detail;
                $post->detail = $item->detail;
                $post->created_at = $item->created_at;
                $post->category_id = $item->category_id;
                $post->city = $item->city;
                $post->image = $item->image;
                $post->agency = $item->agency;
                $post->source_id = $item->source_id;
                $post->title_color = $item->title_color;
                $post->keywords = $item->keywords;
                $post->title_bold = $item->title_bold;
                $post->am =  $item->am;
                $post->um =  $item->um;
                $post->fh =  $item->fh;
                $post->gm =  $item->gm;
                $post->oc =  $item->oc;
                $post->bm =  $item->bm;
                $post->hb =  $item->hb;
                $post->sd =  $item->sd;
                $post->oh =  $item->oh;
                $post->related_news_ids =  $item->related_news_ids;
                $post->article_id = $item->article_id;
                $post->photogallery_id = $item->photogallery_id;
                $post->videogallery_id = $item->videogallery_id;
                $post->comment_close = $item->comment_close;
                $post->ads_close = $item->ads_close;
                $post->only_show_sitemap = $item->only_show_sitemap;;
                $post->video_embed = $item->video_embed;
                $post->hit = $item->hit;
                $post->user_id = $item->user_id;
                $post->redirect_url = $item->redirect_url;
                $post->image_source = $item->image_source;
                $post->image = $item->image;

                $post->preview_image = $item->preview_image;
                $post->image_besli_manset = $item->image_besli_manset;
                $post->image_manset = $item->image_manset;
                $post->image_ust_manset = $item->image_ust_manset;
                $post->image_facebook = $item->image_facebook;
                $post->publish = $item->publish;
                $post->save();
                PostIha::destroy($item->id);
            }else{
                PostIha::destroy($item->id);
            }
        }
        toastr()->success('İşlem başarılı şekilde tamamlanmıştır.');
        activity()->causedBy(auth()->user()->id)->on(new PostIha())->useLog("botdatamove")->withProperties(['IP' => GetIP()])->log('Tümü Haberlere Taşındı');

        return back();
    }

    public function botdataselectmove(Request $request)
    {
        foreach ($request->postid as $itemId){
            foreach (PostIha::where('id', strip_tags($itemId))->get() as $item){
                $check = Post::where('haberkodu', $item->haberkodu)->first();
                if($check==NULL){
                    $post = new Post();
                    $post->haberkodu = $item->haberkodu;
                    $post->title = $item->title;
                    $post->slug = $item->slug;
                    $post->short_detail = $item->short_detail;
                    $post->detail = $item->detail;
                    $post->created_at = $item->created_at;
                    $post->category_id = $item->category_id;
                    $post->city = $item->city;
                    $post->image = $item->image;
                    $post->agency = $item->agency;
                    $post->publish = $item->publish;
                    $post->source_id = $item->source_id;
                    $post->title_color = $item->title_color;
                    $post->keywords = $item->keywords;
                    $post->title_bold = $item->title_bold;
                    $post->am =  $item->am;
                    $post->um =  $item->um;
                    $post->fh =  $item->fh;
                    $post->gm =  $item->gm;
                    $post->oc =  $item->oc;
                    $post->bm =  $item->bm;
                    $post->hb =  $item->hb;
                    $post->sd =  $item->sd;
                    $post->oh =  $item->oh;
                    $post->related_news_ids =  $item->related_news_ids;
                    $post->article_id = $item->article_id;
                    $post->photogallery_id = $item->photogallery_id;
                    $post->videogallery_id = $item->videogallery_id;
                    $post->comment_close = $item->comment_close;
                    $post->ads_close = $item->ads_close;
                    $post->only_show_sitemap = $item->only_show_sitemap;;
                    $post->video_embed = $item->video_embed;
                    $post->hit = $item->hit;
                    $post->user_id = $item->user_id;
                    $post->redirect_url = $item->redirect_url;
                    $post->image_source = $item->image_source;
                    $post->image = $item->image;

                    $post->image_besli_manset = $item->image_besli_manset;
                    $post->image_manset = $item->image_manset;
                    $post->image_ust_manset = $item->image_ust_manset;
                    $post->image_facebook = $item->image_facebook;
                    $post->save();
                    PostIha::destroy($item->id);
                }else{
                    PostIha::destroy($item->id);
                }
            }
        }
        toastr()->success('İşlem başarılı şekilde tamamlanmıştır.');
        activity()->causedBy(auth()->user()->id)->on(new PostIha())->useLog("botdataselectmove")->withProperties(['IP' => GetIP()])->log('Seçilenler Haberlere Taşındı');

        return back();
    }

    public function postbotfilter(Request $request)
    {
        if($request->getMethod()!="POST"){ return back(); exit(); }

        $routename = \Illuminate\Support\Facades\Request::route()->getName();
        $posts = PostIha::orderBy('id','desc');
        if($request->category_id!=NULL) { $posts = PostIha::where('category_id', $request->category_id); }
        if($request->keyword!=NULL) { $posts = PostIha::where('title','LIKE','%'.strip_tags($request->keyword).'%'); }
        if($request->limit==NULL){$posts = $posts->paginate(20);}else{$posts = $posts->get();}

        $categories = Category::all();

        return view('admin.post_iha.index', compact('posts','categories','routename'));
    }
}


























