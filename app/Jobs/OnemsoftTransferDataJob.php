<?php

namespace App\Jobs;

use App\Models\Page;
use App\Models\Post;
use App\Models\Video;
use App\Models\Article;
use App\Models\Category;
use App\Models\Enewspaper;
use App\Models\OfficialAdvert;
use Illuminate\Support\Str;
use App\Models\PhotoGallery;
use Illuminate\Bus\Queueable;
use App\Models\PhotoGalleryImages;
use App\Models\User;
use Carbon\Carbon;
use Egulias\EmailValidator\Result\ValidEmail;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class OnemsoftTransferDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $sourceTable;
    protected $destinationTable;
    /**
     * Create a new job instance.
     */
    public function __construct($sourceTable, $destinationTable)
    {

        $this->sourceTable = $sourceTable;
        $this->destinationTable = $destinationTable;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {


        // $sourceData = DB::connection('hadibaglan')->table($this->sourceTable)->count();
        $data = DB::connection('hadibaglan');


        // try {
        if ($this->sourceTable == "category") {

            $sourceData = $data->table($this->sourceTable)->get();
            // HABER KATEGORİLERİ YORUM SATIRI
            # haber kategorileri
            $say = 0;
            foreach ($sourceData as $haberkat_item) {

                if (Category::where(['id'=>$haberkat_item->id,'title' => strip_tags($haberkat_item->category_name)])->first())
                {
                    continue;
                }
                Category::insert(
                    [
                        'id' => $haberkat_item->id,
                        'category_type' => 0,
                        'parent_category' => $haberkat_item->main_cat_id,
                        'title' => strip_tags($haberkat_item->category_name),
                        'slug' => $haberkat_item->sef,
                        'description' => strip_tags($haberkat_item->seo_description),
                        'color' => strip_tags($haberkat_item->category_color),
                        'keywords' => strip_tags($haberkat_item->seo_keywords),
                        'show_category_ads' => 0,
                        'countnews' => 0,
                        'symbol' => $haberkat_item->font_awesome,

                    ]
                );
                $say ++;
            }
            echo  "HABER KATEGORİLERİ EKLENDİ Toplma: ". $say;
            ## haber kategorileri
        }

        if ($this->sourceTable == "news") {
            $sayy = 0;
            $data->table($this->sourceTable)
                ->orderBy('id')
                ->whereNot('news_cat_id',10)
                ->orderBy('id','desc')
                // ->limit(10)
                ->where('id', '<', 179266)
                ->chunk(500, function ($sourceData) use ($data, $sayy, &$updatedCount) {
                    $haberSay = 0;

                    $updatedCount = 0;

                    foreach ($sourceData as $haber) {
                        $existingPost =DB::table('post')->whereId($haber->id)->exists();
                        if (!$existingPost) {
                          // Verileri sırayla kontrol et
                            $news_date = !empty($haber->news_date) && $haber->news_date !== "0000-00-00" ? $haber->news_date : null;
                            $yayin_tarihi = !empty($haber->yayin_tarihi) && $haber->yayin_tarihi !== "0000-00-00" ? $haber->yayin_tarihi : null;
                            $news_update = !empty($haber->news_update) && $haber->news_update !== "0000-00-00" ? $haber->news_update : null;

                            // İlk geçerli tarihi bul
                            $first_valid_date = $news_date ?? $yayin_tarihi ?? $news_update ?? Carbon::now();

                            $post = new Post();
                            $post->id = $haber->id;
                            $post->category_id = intval($haber->news_cat_id);
                            $post->title = html_entity_decode($haber->news_title);
                            $post->description = html_entity_decode($haber->news_short);
                            $post->detail = html_entity_decode($haber->news_content);
                            $post->created_at = date("Y-m-d h:i:s", strtotime($haber->news_date));
                            $post->meta_title = strip_tags($haber->news_title);
                            $post->meta_description = strip_tags($haber->news_short);
                            $post->show_title_slide = 0;
                            $post->hit =  $haber->news_views;
                            $post->is_archived = false;
                            $post->author_id = $haber->user_id ;
                            $post->slug = $haber->sef ?? slug_format($haber->news_title, '-');
                            $post->keywords = $haber->news_tags_sef;
                            $post->redirect_link = isset($haber->link) ? html_entity_decode($haber->link) : null;
                            $post->created_at = $post->created_at ?? $first_valid_date;
                            $post->updated_at = $post->updated_at ?? $post->created_at ?? $first_valid_date;


                            $post->publish = 0;
                            $post->images = $haber->news_img ? "news/" .$haber->news_img : "news/" .$haber->news_large_img;
                            $fb_image = "news/" . $haber->news_large_img;
                            $post->position = 3; // hepsini standart haber ekle

                            $extra_array = [
                                "comment_status" => strip_tags($haber->news_comment_status),
                                "video_embed" => "",
                                "fb_image" => $fb_image ?? "",
                                "show_post_ads" => 1,
                                "author" => ($haber->user_id != "") ? $haber->user_id : 1,
                                "news_large_images" => $haber->news_large_img,
                                "news_right_images" => $haber->news_right_img,
                                "news_surmanset_images" => $haber->news_surmanset_img,
                                "mini_images" => "news/" . $haber->news_large_img,

                            ];
                            // $anahtar_yapistir = [];
                            // foreach ($data->table("haber_keywords_iliski")->where('haber_id', $haber->id)->get() as $keywords_id) {
                            //     foreach ($data->table("haber_keywords")->where('id', $keywords_id->keyword_id)->get() as $keyword) {
                            //         $anahtar_yapistir[] = $keyword->keyword;
                            //     }
                            // }
                            // $post->keywords = implode(",", $anahtar_yapistir);

                            // $post->hit = intval($data->table("haber_hit")->where('content_id', $haber->id)->first()->hit);
                            $post->extra = json_encode($extra_array);

                            $post->save();

                        //    @location 0 => dortlu_manset, 1 => ana_manset, 2 => mini_manset, 3 => standart_haberler, 4 => sondakika_manset
                            // $post->locations()->create([
                            //     'post_id' => $haber->id,
                            //     'location_id' => 3,
                            // ]);

                            // if (!blank($haber->news_position_slider)) {
                            //     $post->locations()->create([
                            //         'post_id' => $haber->id,
                            //         'location_id' => 1,
                            //     ]);
                            // }
                            // if (!blank($haber->news_position_headline)) {
                            //     $post->locations()->create([
                            //         'post_id' => $haber->id,
                            //         'location_id' => 0,
                            //     ]);
                            // }
                            // if (!blank($haber->news_position_small_headline)) {
                            //     $post->locations()->create([
                            //         'post_id' => $haber->id,
                            //         'location_id' => 2,
                            //     ]);
                            // }
                            // if (!blank($haber->news_position_headline_leftright)) {
                            //     $post->locations()->create([
                            //         'post_id' => $haber->id,
                            //         'location_id' => 4,
                            //     ]);
                            // }

                            $haberSay++;
                        } else {

                            $existingPost = Post::whereId($haber->id)->first();

                            DB::table('post')
                            ->where('id', $haber->id)
                            ->update([
                                'author_id' => $haber->post_author == 1 ? 2 : $haber->post_author,
                                'images'=> $haber->news_img ? "news/".$haber->news_img : "news/".$haber->news_large_img,
                            ]);

                            // Update existing post

                            // $existingPost->category_id = intval($haber->news_cat_id);
                           // $existingPost->title = html_entity_decode($haber->news_title);
                           // $existingPost->description = html_entity_decode($haber->news_short);
                            // $existingPost->detail = html_entity_decode($haber->news_content);
                            // $existingPost->meta_title = strip_tags($haber->news_title);
                           // $existingPost->meta_description = html_entity_decode($haber->news_short);
                            // $existingPost->hit = $haber->news_views;
                            // $existingPost->slug = $haber->sef;
                            // $existingPost->keywords = $haber->news_tags_sef;
                            // $existingPost->redirect_link = isset($haber->link) ? strip_tags($haber->link) : null;
                            // $existingPost->updated_at = date("Y-m-d h:i:s", strtotime($haber->news_date));

                            // $existingPost->images = $haber->news_img ? "news/".$haber->news_img : "news/".$haber->news_large_img;

                            // $fb_image = $haber->news_large_img;
                            // //
                            // $extra_array = [
                            //     "comment_status" => strip_tags($haber->news_comment_status),
                            //     "video_embed" => "",
                            //     "fb_image" => $fb_image ?? "",
                            //     "show_post_ads" => 1,
                            //     "author" => ($haber->user_id != "") ? $haber->user_id : 1,
                            //     "news_large_images" => $haber->news_large_img,
                            //     "mini_images" => "news/" . $haber->news_large_img,
                            //     "news_right_images" => $haber->news_right_img,
                            //     "news_surmanset_images" => $haber->news_surmanset_img,
                            // ];

                            // Get existing extra data and update it
                            // $extra_data = json_decode($existingPost->extra, true) ?: [];
                            // $extra_data["comment_status"] = strip_tags($haber->news_comment_status);
                            // $extra_data["fb_image"] = $fb_image ?? "";
                            // $extra_data["author"] = !blank($haber->user_id) ? $haber->user_id : 1;
                            //  $extra_data["mini_images"] = $haber->news_large_img;
                            // $extra_data["news_right_images"] = $haber->news_right_img;
                            // $extra_data["news_surmanset_images"] = $haber->news_surmanset_img;

                            // $existingPost->extra = json_encode($extra_array);
                            // $existingPost->save();
                            // $updatedCount++;
                        }

                        $sayy += $haberSay;
                        Log::info('HABER   ' . $sayy . ' | GÜNCELLENEN: ' . $updatedCount);
                    }
                });
            echo ($sayy) . " ADET HABER EKLENDİ, " . $updatedCount . " ADET HABER GÜNCELLENDİ SON İŞLEM TARİHİ: " . date('Y-m-d H:i:s');
        }
        if ($this->sourceTable == "video_kategori") {
            $vKaySay = 0;
            $data->table($this->sourceTable)
                ->orderBy('id')
                ->chunk(500, function ($sourceData) use ($vKaySay) {

                    foreach ($sourceData as $haberkat_item) {

                        Category::firstOrCreate(
                            [
                                'id' => $haberkat_item->id,
                                'category_type' => 2
                            ],

                            [
                                'parent_category' => ($haberkat_item->parent != null) ? strip_tags($haberkat_item->parent) : 0,
                                'title' => strip_tags($haberkat_item->baslik),
                                'slug' => slug_format($haberkat_item->baslik),
                                'description' => strip_tags($haberkat_item->baslik),
                                'show_category_ads' => 0,
                                'created_at' => $haberkat_item->tarih,
                                'updated_at' => $haberkat_item->tarih,
                            ]
                        );
                        $category = new Category();
                        $category->id = $haberkat_item->id;
                        $category->parent_category = ($haberkat_item->parent != null) ? strip_tags($haberkat_item->parent) : 0;
                        $category->title = strip_tags($haberkat_item->baslik);
                        $category->slug = slug_format($haberkat_item->baslik);
                        $category->description = strip_tags($haberkat_item->baslik);
                        $category->category_type = 2;
                        $category->show_category_ads = 0;
                        $category->created_at = $haberkat_item->tarih;
                        $category->save();
                        $vKaySay++;
                    }
                });

            echo  "VİDEO KATEGORİLERİ EKLENDİ<br>";
        }
        if ($this->sourceTable == "video") {
            $vSay = 0;
            $data->table($this->sourceTable)
                ->orderBy('id')
                ->chunk(500, function ($sourceData) use ($vSay) {
                    foreach ($sourceData as $request) {
                        $video = new Video();
                        $video->id = strip_tags($request->id);
                        $video->category_id = strip_tags($request->catid);
                        $video->title = strip_tags($request->baslik);
                        $video->slug = slug_format($request->baslik);
                        $video->detail = $request->aciklama;
                        $video->embed = $request->video_kodu;
                        $video->hit = strip_tags($request->hit == "" ? 0 : $request->hit);
                        $video->publish = ($request->aktif == 1) ? 0 : 1;
                        $video->images = $request->resim;
                        $video->created_at = $request->tarih;
                        $video->save();
                        $vSay++;
                    }
                });
            dump("VİDEO GALERİLER " . $vSay);
            echo   "VİDEO GALERİLER EKLENDİ<br>";
        }
        if ($this->sourceTable == "albumkat_asil") {

            $vSay = 0;
            $data->table($this->sourceTable)
                ->orderBy('id')
                ->chunk(500, function ($sourceData) use ($vSay) {
                    foreach ($sourceData as $haberkat_item) {
                        $category = new Category();
                        $category->id = $haberkat_item->id;
                        $category->parent_category = ($haberkat_item->parent != null) ? strip_tags($haberkat_item->parent) : 0;
                        $category->title = strip_tags($haberkat_item->baslik);
                        $category->slug = slug_format($haberkat_item->baslik);
                        $category->description = strip_tags($haberkat_item->baslik);
                        $category->category_type = 1;
                        $category->show_category_ads = 0;
                        $category->created_at = $haberkat_item->tarih;
                        $category->save();
                        $vSay++;
                    }
                });
            dump("VİDEO GALERİLER " . $vSay);
            echo  "FOTO KATEGORİLERİ EKLENDİ<br>";
        }
        if ($this->sourceTable == "albumkat") {
            $data->table($this->sourceTable)
                ->orderBy('id')
                ->chunk(500, function ($sourceData) {
                    foreach ($sourceData as $request) {
                        $photogallery = new PhotoGallery();
                        $photogallery->id = strip_tags($request->id);
                        //$photogallery->category_id = strip_tags($request->catid);
                        $photogallery->category_id = 2;
                        $photogallery->title = strip_tags($request->baslik);
                        $photogallery->slug = slug_format($request->hta);
                        $photogallery->detail = "";
                        $photogallery->hit = strip_tags($request->hit == "" ? 0 : $request->hit);
                        $photogallery->publish = ($request->aktif == 1) ? 0 : 1;
                        $photogallery->images = $request->kapak;
                        $photogallery->created_at = $request->tarih;
                        $photogallery->save();
                    }
                });
            echo  "FOTO GALERİLER KAT EKLENDİ<br>";
        }
        if ($this->sourceTable == "album") {
            $data->table($this->sourceTable)
                ->orderBy('id')
                ->chunk(500, function ($sourceData) {
                    foreach ($sourceData as $request) {
                        $result = new PhotoGalleryImages();
                        $result->id = $request->id;
                        $result->photogallery_id = $request->catid;
                        $result->images = $request->resim;
                        $result->title = $request->aciklama;
                        $result->created_at = $request->tarih;
                        $result->save();
                    }
                });
            $message[] = "FOTO GALERİLER ALBÜM EKLENDİ<br>";
        }
        if ($this->sourceTable == "icerik_icerik") {
            $data->table($this->sourceTable)
                ->orderBy('id')
                ->chunk(500, function ($sourceData) {
                    foreach ($sourceData as $request) {
                        $page = new Page();
                        $page->id = strip_tags($request->id);
                        $page->title = strip_tags($request->baslik);
                        $page->slug = slug_format($request->hta);
                        $page->detail = $request->detay;
                        $page->publish = strip_tags($request->aktif);
                        $page->created_at = $request->tarih;
                        $page->save();
                    }
                });
            echo  "İÇERİK EKLENDİ<br>";
        }
        if ($this->sourceTable == "makaleler") {

            $sourceData =  $data->table($this->sourceTable)
                // ->whereIn('user_id',[323,316,312])
                ->where('news_cat_id',999)

                ->orderBy('id')->get();

            foreach ($sourceData as $request) {
                if (!Article::whereId($request->id)->exists()) {
                    $article = new Article();
                    $article->id = $request->id;
                    $article->title = html_entity_decode($request->news_title);
                    $article->slug = slug_format($request->sef);
                    $article->detail = html_entity_decode($request->news_content);
                    $article->publish = 0;
                    $article->author_id = intval($request->user_id);
                    $article->images =  "";
                    $article->created_at = date("Y-m-d h:i:s", strtotime($request->news_date));
                    $article->save();
                }
            }
            echo  "MAKALELER EKLENDİ<br>";
        }
        if ($this->sourceTable == "users") {
            $saveCount = 0;

            $sourceData =  $data->table($this->sourceTable)
                ->orderBy('id')
                ->get();

            $status =  [
                "editor" => 2,
                "admin" => 1,
                "user" => 0
            ];

            foreach ($sourceData  as $key=> $editor) {
                if (User::where('id', $editor->id)->exists())
                {
                    continue;
                }
                $email  = $editor->user_email ? strip_tags($editor->user_email) : slug_format($editor->user_name_lastname) . '@liderhaber.com';
                if (User::where('email',$editor->user_email)->exists()){
                    $email  = $editor->user_email ? strip_tags($editor->user_email. $key) : slug_format($editor->user_name_lastname. $key) . '@liderhaber.com';
                }

                $editorUser = new User();
                $editorUser->id = $editor->id;
                $editorUser->name = strip_tags($editor->user_name_lastname);
                $editorUser->email = $email;
                $editorUser->about = isset($editor->user_about) ? strip_tags($editor->user_about) : null;
                $editorUser->status = !blank($editor->user_status) ?  $status[$editor->user_status] : 0;
                $editorUser->avatar = $editor->user_avatar;
                $editorUser->password = Hash::make($email);
                $editorUser->save();
                $saveCount++;
            }
            // dump($saveCount);
            echo   $saveCount . "ADET  KULLANICI  EKLENDİ <br>";
        }



        if ($this->sourceTable == "resmi_ilanlar") {
            $sayy = 0;
            $data->table($this->sourceTable)
                ->orderBy('id')
                ->where('news_cat_id',10)
                ->orderBy('id','desc')
                // ->limit(10)
                // ->where('id', '>', 179265)
                ->chunk(500, function ($sourceData) use ($data, $sayy, &$updatedCount) {
                    $haberSay = 0;
                    $updatedCount = 0;

                    foreach ($sourceData as $haber) {
                        $existingPost =DB::table('official_advert')->whereId($haber->id)->exists();
                        if (!$existingPost) {

                          // Verileri sırayla kontrol et
                            $news_date = !empty($haber->news_date) && $haber->news_date !== "0000-00-00" ? $haber->news_date : null;
                            $yayin_tarihi = !empty($haber->yayin_tarihi) && $haber->yayin_tarihi !== "0000-00-00" ? $haber->yayin_tarihi : null;
                            $news_update = !empty($haber->news_update) && $haber->news_update !== "0000-00-00" ? $haber->news_update : null;
                            // İlk geçerli tarihi bul
                            $first_valid_date = $news_date ?? $yayin_tarihi ?? $news_update ?? Carbon::now();

                            $post = new OfficialAdvert();
                            $post->id = $haber->id;
                            $post->category_id = intval($haber->news_cat_id);
                            $post->title = html_entity_decode($haber->news_title);
                            // $post->description = html_entity_decode($haber->news_short);
                            $post->detail = html_entity_decode($haber->news_content);
                            // $post->created_at = date("Y-m-d h:i:s", strtotime($haber->news_date));
                            // $post->meta_title = strip_tags($haber->news_title);
                            // $post->meta_description = strip_tags($haber->news_short);

                            $post->slug = $haber->sef ?? slug_format($haber->news_title, '-');
                            $post->create_date  =  $first_valid_date ? Carbon::parse( $first_valid_date) :    null;

                            $post->created_at =  $first_valid_date ? Carbon::parse( $first_valid_date) :    null;
                            $post->updated_at = $first_valid_date ? Carbon::parse($first_valid_date) : null;

                            $post->publish = 0;
                            $post->images = "news/" . $haber->news_img ? $haber->news_img : $haber->news_large_img;
                            $extra_array = [
                                "title"=>  html_entity_decode($haber->news_title),
                                "detail"=> html_entity_decode($haber->news_content) ?? html_entity_decode($haber->Link) ?? null ,
                                "images"=> "news/" . $haber->news_img ? $haber->news_img : $haber->news_large_img,
                                "ilan_id"=> "",
                                "publish"=> 0,
                                "category_id"=> intval($haber->news_cat_id),
                                "news_large_images" => $haber->news_large_img,
                                "news_right_images" => $haber->news_right_img,
                                "news_surmanset_images" => $haber->news_surmanset_img,
                                "mini_images" => "news/" . $haber->news_large_img,
                                'news_tags'=> $haber->news_tags_sef,
                                'link'=> isset($haber->Link) ? html_entity_decode($haber->Link) : null,

                            ];

                            $post->clsfadmagicbox = $extra_array;

                            $post->save();

                            $haberSay++;
                        } else {

                            // $existingPost = Post::whereId($haber->id)->where('is_archived', 0)->first();

                            // Update existing post

                            // $existingPost->category_id = intval($haber->news_cat_id);
                           // $existingPost->title = html_entity_decode($haber->news_title);
                           // $existingPost->description = html_entity_decode($haber->news_short);
                            // $existingPost->detail = html_entity_decode($haber->news_content);
                            // $existingPost->meta_title = strip_tags($haber->news_title);
                           // $existingPost->meta_description = html_entity_decode($haber->news_short);
                            // $existingPost->hit = $haber->news_views;
                            // $existingPost->slug = $haber->sef;
                            // $existingPost->keywords = $haber->news_tags_sef;
                            // $existingPost->redirect_link = isset($haber->link) ? strip_tags($haber->link) : null;
                            // $existingPost->updated_at = date("Y-m-d h:i:s", strtotime($haber->news_date));
                           // $existingPost->images = $haber->news_img ? $haber->news_img : $haber->news_large_img;
                            // $fb_image = $haber->news_large_img;

                            // //
                            // $extra_array = [
                            //     "comment_status" => strip_tags($haber->news_comment_status),
                            //     "video_embed" => "",
                            //     "fb_image" => $fb_image ?? "",
                            //     "show_post_ads" => 1,
                            //     "author" => ($haber->user_id != "") ? $haber->user_id : 1,
                            //     "news_large_images" => $haber->news_large_img,
                            //     "mini_images" => "news/" . $haber->news_large_img,
                            //     "news_right_images" => $haber->news_right_img,
                            //     "news_surmanset_images" => $haber->news_surmanset_img,
                            // ];

                            // Get existing extra data and update it
                            // $extra_data = json_decode($existingPost->extra, true) ?: [];
                            // $extra_data["comment_status"] = strip_tags($haber->news_comment_status);
                            // $extra_data["fb_image"] = $fb_image ?? "";
                            // $extra_data["author"] = !blank($haber->user_id) ? $haber->user_id : 1;
                            //  $extra_data["mini_images"] = $haber->news_large_img;
                            // $extra_data["news_right_images"] = $haber->news_right_img;
                            // $extra_data["news_surmanset_images"] = $haber->news_surmanset_img;

                            // $existingPost->extra = json_encode($extra_array);
                            // $existingPost->save();
                            // $updatedCount++;
                        }

                        $sayy += $haberSay;
                        Log::info('EKLENEN RESMİ İLAN   ' . $sayy . ' | GÜNCELLENEN: ' . $updatedCount);
                    }
                });
            echo ($sayy) . " ADET RESMİ İLAN eKLENDİ, " . $updatedCount . " ADET RESMİ İLAN GÜNCELLENDİ SON İŞLEM TARİHİ: " . date('Y-m-d H:i:s');
        }




        if ($this->sourceTable == "gazete") {
            $sayy = 0;
            $data->table($this->sourceTable)
                ->orderBy('id')
                ->orderBy('id','desc')
                ->chunk(500, function ($sourceData) use ($data, $sayy,) {
                    $sayy = 0;

                    foreach ($sourceData as $gazete) {
                        if (DB::table('enewspaper')->whereId($gazete->id)->exists()) {
                            continue;
                        }

                        $id = $gazete->id;
                        $sayfalar = [
                            "sayfa1"=>[
                                'sortby'=>1,
                                'images'=>$gazete->sayfa1,
                                'title'=>$gazete->sayfa1,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class

                                        ],
                            "sayfa2"=>[
                                'sortby'=>2,
                                'images'=>$gazete->sayfa2,
                                'title'=>$gazete->sayfa2,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class

                                        ],
                            "sayfa3"=>[
                                'sortby'=>3,
                                'images'=>$gazete->sayfa3,
                                'title'=>$gazete->sayfa3,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class

                                        ],
                            "sayfa4"=>[
                                'sortby'=>4,
                                'images'=>$gazete->sayfa4,
                                'title'=>$gazete->sayfa4,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class

                                        ],
                            "sayfa5"=>[
                                'sortby'=>5,
                                'images'=>$gazete->sayfa5,
                                'title'=>$gazete->sayfa5,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class

                                        ],
                            "sayfa6"=>[
                                'sortby'=>6,
                                'images'=>$gazete->sayfa6,
                                'title'=>$gazete->sayfa6,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class

                                        ],
                            "sayfa7"=>[
                                'sortby'=>7,
                                'images'=>$gazete->sayfa7,
                                'title'=>$gazete->sayfa7,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class

                                        ],
                            "sayfa8"=>[
                                'sortby'=>8,
                                'images'=>$gazete->sayfa8,
                                'title'=>$gazete->sayfa8,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class

                                        ],
                            "sayfa9"=>[
                                'sortby'=>9,
                                'images'=>$gazete->sayfa9,
                                'title'=>$gazete->sayfa9,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class

                                        ],
                            "sayfa10"=>[
                                'sortby'=>10,
                                'images'=>$gazete->sayfa10,
                                'title'=>$gazete->sayfa10,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class
                            ],
                            "sayfa11"=>[
                                'sortby'=>11,
                                'images'=>$gazete->sayfa11,
                                'title'=>$gazete->sayfa11,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class
                            ],
                            "sayfa12"=>[
                                'sortby'=>12,
                                'images'=>$gazete->sayfa12,
                                'title'=>$gazete->sayfa12,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class
                            ],
                            "sayfa13"=>[
                                'sortby'=>13,
                                'images'=>$gazete->sayfa13,
                                'title'=>$gazete->sayfa13,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class

                                        ],
                            "sayfa14"=>[
                                'sortby'=>14,
                                'images'=>$gazete->sayfa14,
                                'title'=>$gazete->sayfa14,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class
                            ],
                            "sayfa15"=>[
                                'sortby'=>15,
                                'images'=>$gazete->sayfa15,
                                'title'=>$gazete->sayfa15,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class
                            ],
                            "sayfa16"=>[
                                'sortby'=>16,
                                'images'=>$gazete->sayfa16,
                                'title'=>$gazete->sayfa16,
                                'gallery_id'=>$gazete->id,
                                'model_path'=>Enewspaper::class
                            ],


                        ];
                            $sayfa1 = $gazete->sayfa1; // sayfa1
                            $formattedDate = $gazete->tarih ?  Carbon::createFromFormat('Ymd', $gazete->tarih)->format('Y-m-d') : null;

                            $newsPaper =  Enewspaper::firstOrcreate([
                                'id'=>$id,
                                'title'=>"Gazete ". $formattedDate,
                                'slug'=> slug_format("Gazete ". $formattedDate),
                                'images'=> $sayfa1,
                                'date'=>$formattedDate,
                            ]);


                            // $newsPaper->getImages()->create($sayfalar);
                            $sayfalar = collect($sayfalar)->filter(function ($sayfa) {
                                return !empty($sayfa['images']); // sadece images olanları ekle
                            })->values()->toArray();

                            $newsPaper->getImages()->createMany($sayfalar);

                        $sayy ++;

                        Log::info('EKLENEN GAZETE SAYISI   ' . $sayy );
                    }
                });
            echo ($sayy) . " ADET GAZETE EKLENDİ,  SON İŞLEM TARİHİ: " . date('Y-m-d H:i:s');
        }





        echo "İşlem Tamamlandı<br>";
        Log::info("Database Aktarım Tamamlandı");
        // } catch (Exception $e) {
        //     // Hata durumunda logla
        //     Log::error('Error in TransferDataJob: ' . $e->getMessage());
        //     throw $e; // Hatanın yukarıya fırlatılması
        // }
    }

    // public function failed(Exception $exception)
    // {
    //     Log::error('Job Failed: ', [
    //         'sourceTable' => $this->sourceTable,
    //         'destinationTable' => $this->destinationTable,
    //         'exception' => $exception->getMessage(),
    //     ]);
    // }
}
