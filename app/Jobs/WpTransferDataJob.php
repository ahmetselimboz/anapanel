<?php

namespace App\Jobs;

use App\Models\Page;
use App\Models\Post;
use App\Models\Video;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\PhotoGallery;
use Illuminate\Bus\Queueable;
use App\Models\PhotoGalleryImages;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;



class WpTransferDataJob implements ShouldQueue
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
        if ($this->sourceTable == "wpnl_terms") {

            $sourceData = $data->table($this->sourceTable)->get();
            // HABER KATEGORİLERİ YORUM SATIRI
            # haber kategorileri
            DB::transaction(function () use ($sourceData) {
                foreach ($sourceData as $haberkat_item) {
                    Category::firstOrCreate(
                        [
                            'id' => $haberkat_item->term_id,
                            'category_type' => 0,
                        ],
                        [
                            'parent_category' =>  0,
                            'title' => strip_tags($haberkat_item->name),
                            'slug' => $haberkat_item->slug,
                            'show_category_ads' => 0,
                            'countnews' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });

            $message[] =  "HABER KATEGORİLERİ EKLENDİ";
            ## haber kategorileri
        }

        if ($this->sourceTable == "wpnl_posts") {


            $sayy = 0;

            // DB::transaction(function () use ($data, $sayy) {
            DB::beginTransaction();
                $data->table($this->sourceTable)
                ->where('ID','>',758115090817)
                    ->orderBy('id')
                    ->where('post_type', 'post')
                    ->chunk(500, function ($sourceData) use ($data, $sayy) {
                        $haberSay = 0;
                        foreach ($sourceData as $haber) {
                            $existingPost = DB::table('post')->whereId($haber->ID)->exists();

                            if (!$existingPost) {

                                // $category = $data->table("wpnl_postmehta")->where(function ($query) use ($haber) {
                                //     $query->where('post_id', $haber->ID);
                                //     // ->where('meta_key', 'menu_item_object_id');
                                //     // })->value('meta_value') ?? 1 ;
                                // })->get();

                                // $termRelation = $data->table("wpnl_term_relationships")->where(function ($query) use ($haber) {
                                //     $query->where('object_id', $haber->ID);
                                // })->get()?? null;

                                $category = Category::inRandomOrder()->first();




                                $images = $data->table($this->sourceTable)
                                    // ->where('post_parent', 758115090574)
                                    ->where('post_parent', $haber->ID)
                                    ->where('post_type', 'attachment')
                                    ->pluck('guid') ?? null;

                                if ($images) {

                                    $haber->images_old = $images;
                                    $newImagePath  = NULL;
                                    foreach ($images as $image) {
                                        $imagePath = parse_url($image, PHP_URL_PATH);
                                        $newImagePath[] = preg_replace('/^.*?\/uploads\//', '', $imagePath);
                                    }
                                } else {
                                    $images = null;
                                    $newImagePath = null;
                                }

                                // isset($category[1]) ? $newImagePath[1] : null;


                                $post = new Post();
                                $post->id = $haber->ID;
                                $post->category_id =  $category->id;
                                $post->title = strip_tags($haber->post_title);
                                $post->slug = $haber->post_name ? $haber->post_name : slug_format($haber->post_title);

                                $post->keywords = NULL;
                                $post->description =( $haber->post_excerpt) > 0  ? strip_tags($haber->post_excerpt) : null;
                                $post->meta_title = strip_tags($haber->post_title);
                                $post->meta_description = strip_tags($haber->post_excerpt);
                                $post->detail = html_entity_decode($haber->post_content);
                                $post->position = 3; // hepsini standart haber ekle ?
                                $post->redirect_link =  null;
                                $post->show_title_slide = 0;
                                $post->hit = 50;
                                $post->created_at = date("Y-m-d h:i:s", strtotime($haber->post_date));
                                $post->updated_at = date("Y-m-d h:i:s", strtotime($haber->post_date));
                                $post->publish = ($haber->post_status == "publish") ? 0 : 1;
                                $post->images =  isset($newImagePath[0]) ? $newImagePath[0] : null;
                                $fb_image =  isset($newImagePath[1]) ? $newImagePath[1] : null;
                                $extra_array = [
                                    "comment_status" => $haber->comment_status,
                                    "fb_image" => $fb_image,
                                    "show_post_ads" => 1,
                                    "author" => $haber->post_author == 1 ? 2 : $haber->post_author ,
                                    "news_source" => "İSG HABER AJANSI",
                                ];
                                $post->extra = json_encode($extra_array);
                                $post->save();

                                // @location 0 => dortlu_manset, 1 => ana_manset, 2 => mini_manset, 3 => standart_haberler, 4 => sondakika_manset
                                $post->locations()->create([
                                    'post_id' => $post->id,
                                    'location_id' => 3,
                                ]);

                                // if (!blank($haber->news_position_slider)) {
                                    $post->locations()->create([
                                        'post_id' => $post->id,
                                        'location_id' => 1,
                                    ]);
                                // }
                                // if (!blank($haber->news_position_headline)) {
                                    $post->locations()->create([
                                        'post_id' => $post->id,
                                        'location_id' => 0,
                                    ]);
                                // }
                                // if (!blank($haber->news_position_small_headline)) {
                                    $post->locations()->create([
                                        'post_id' => $post->id,
                                        'location_id' => 2,
                                    ]);
                                // }
                                // if (!blank($haber->news_position_headline_leftright)) {
                                    $post->locations()->create([
                                        'post_id' => $post->id,
                                        'location_id' => 4,
                                    ]);
                                // }

                                DB::table('post')
                                ->where('id', $post->id)
                                ->update([
                                    'author_id' => $haber->post_author == 1 ? 2 : $haber->post_author
                                ]);
                                $haberSay++;
                            }
                            $sayy += $haberSay;
                            Log::info('HABER   ' .  $sayy);
                        }



                    });






                    DB::commit();
            // });
            echo $sayy. " ADET HABER  EKLENDİ SON EKLENEN HABER br>";
            $message[] = ($sayy) . " ADET HABER  EKLENDİ SON EKLENEN HABER br>";
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
            dump('VİDEO KATEGORİLERİ  ' . $vKaySay);
            $message[] = "VİDEO KATEGORİLERİ EKLENDİ<br>";
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
            $message[] =  "VİDEO GALERİLER EKLENDİ<br>";
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
            $message[] = "FOTO KATEGORİLERİ EKLENDİ<br>";
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
            $message[] = "FOTO GALERİLER KAT EKLENDİ<br>";
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
            $message[] = "İÇERİK EKLENDİ<br>";
        }
        if ($this->sourceTable == "makaleler") {

            $sourceData =  $data->table($this->sourceTable)
                ->orderBy('id')->get();

            foreach ($sourceData as $request) {
                $article = new Article();
                // $article->id = strip_tags($request->id);
                $article->title = strip_tags($request->baslik);
                $article->slug = slug_format($request->baslik);
                $article->detail = $request->detay;
                $article->publish = ($request->aktif == 1) ? 0 : 1;
                $article->author_id = $request->yid;
                $article->created_at = $request->tarih;
                $article->save();
            }
            $message[] = "MAKALELER EKLENDİ<br>";
        }
        if ($this->sourceTable == "wpnl_users") {
            $sourceData =  $data->table($this->sourceTable)
                ->orderBy('ID')
                ->whereNotNull('user_email')
                ->get();

            $saveCount = 0;

            foreach ($sourceData as $editor) {
                $editorUser = new User();
                $editorUser->id = $editor->ID;
                $editorUser->name = strip_tags($editor->user_nicename);
                $editorUser->email = $editor->user_email ? strip_tags($editor->user_email) : slug_format($editor->user_nicename . "-" . $saveCount) . '@isghaber.com';
                $editorUser->about = "";
                $editorUser->status = 2;
                $editorUser->avatar = "";
                $editorUser->password = Hash::make('isgHaber.100001#');
                $editorUser->save();

                // Article::where('author_id', strip_tags($editor->id))->update([
                //     'author_id' => $editorUser->id,
                // ]);
                $saveCount++;
            }
            dump($saveCount);
            $message[] = "MAKALELER EKLENDİ<br>";
        }


        Log::info("Database Aktarım Tamamlandı -", $message);
        // } catch (Exception $e) {
        //     // Hata durumunda logla
        //     Log::error('Error in TransferDataJob: ' . $e->getMessage());
        //     throw $e; // Hatanın yukarıya fırlatılması
        // }
    }

    // public function failed(Exception $exception)
    // {
    //     return $exception->getMessage();
    //     Log::error('Job Failed: ', [
    //         'sourceTable' => $this->sourceTable,
    //         'destinationTable' => $this->destinationTable,
    //         'exception' => $exception->getMessage(),
    //     ]);
    // }
}
