<?php

namespace App\Jobs;

use Exception;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\PhotoGallery;
use App\Models\PostLocation;
use Illuminate\Bus\Queueable;
use App\Models\PhotoGalleryImages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TransferDataJob implements ShouldQueue
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


        try {
            if ($this->sourceTable == "haberkat") {
                $sourceData = $data->table($this->sourceTable)->get();
                // HABER KATEGORİLERİ YORUM SATIRI
                # haber kategorileri
                foreach ($sourceData as $haberkat_item) {

                    Category::firstOrCreate(
                        [
                            'id' => $haberkat_item->id,
                            'category_type' => 0,
                        ],[
                            'parent_category' => ($haberkat_item->parent != null) ? strip_tags($haberkat_item->parent) : 0,
                            'title' => strip_tags($haberkat_item->baslik),
                            'slug' => $haberkat_item->hta,
                            'description' => strip_tags($haberkat_item->description),
                            'color' => strip_tags($haberkat_item->renk),
                            'keywords' => strip_tags($haberkat_item->keywords),
                            'show_category_ads' => 0,
                            'countnews' => $haberkat_item->habersayisi,
                            'created_at' => $haberkat_item->tarih,
                            'updated_at' => $haberkat_item->tarih,
                        ]
                    );

                }
                $message[] =  "HABER KATEGORİLERİ EKLENDİ";
                ## haber kategorileri
            }

            if ($this->sourceTable == "haber") {
                PostLocation::truncate();
                Post::truncate();

                $sayy = 0;
                $data->table($this->sourceTable)
                    ->orderBy('id')
                    ->where('id', '>', 79844)
                    ->chunk(500, function ($sourceData) use ($data, $sayy) {
                        $haberSay = 0;
                        foreach ($sourceData as $haber) {

                            $post = new Post();
                            $post->id = $haber->id;
                            $post->category_id = $haber->catid;
                            $post->title = strip_tags($haber->baslik);
                            $post->slug = slug_format($haber->baslik);
                            $anahtar_yapistir = [];
                            foreach ($data->table("haber_keywords_iliski")->where('haber_id', $haber->id)->get() as $keywords_id) {
                                foreach ($data->table("haber_keywords")->where('id', $keywords_id->keyword_id)->get() as $keyword) {
                                    $anahtar_yapistir[] = $keyword->keyword;
                                }
                            }
                            $post->keywords = implode(",", $anahtar_yapistir);
                            $post->description = strip_tags($haber->spot);
                            $post->meta_title = strip_tags($haber->baslik);
                            $post->meta_description = strip_tags($haber->spot);
                            $post->detail = $haber->detay;
                            $post->position = 3; // hepsini standart haber ekle
                            $post->redirect_link = strip_tags($haber->direct_link);
                            $post->show_title_slide = 0;
                            $post->hit = intval($data->table("haber_hit")->where('content_id', $haber->id)->first()->hit);
                            $post->created_at = date("Y-m-d h:i:s", strtotime($haber->tarih));
                            $post->updated_at =date("Y-m-d h:i:s", strtotime($haber->tarih));
                            $post->publish = ($haber->aktif == 1) ? 0 : 1;
                            $post->images = $haber->resim;
                            $fb_image = ($haber->facebook_resim != null) ? $haber->facebook_resim : $haber->resim;

                            $extra_array = [
                                "comment_status" => strip_tags($haber->yorum),
                                "video_embed" => $haber->video_kodu ?? "",
                                "fb_image" => $fb_image ?? "",
                                "show_post_ads" => strip_tags($haber->reklam_kapali),
                                "author" => ($haber->ekleyen != "") ? $haber->ekleyen : 2,
                            ];
                            $post->extra = json_encode($extra_array);
                            // $post->old_data = json_encode($haber);
                            $post->save();

                            //@location 0 => dortlu_manset, 1 => ana_manset, 2 => mini_manset, 3 => standart_haberler, 4 => sondakika_manset

                            $post->locations()->create([
                                    'post_id' => $haber->id,
                                    'location_id' => 3,
                            ]);


                            $haberSay++;
                        }
                        $sayy += $haberSay;
                        Log::info('HABER   ' .  $sayy);
                    });
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
                                    'category_type' => 2],

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
                    $article->id = strip_tags($request->id);
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
            if ($this->sourceTable == "yazarlar") {
                $sourceData =  $data->table($this->sourceTable)
                    ->orderBy('id')
                    // ->whereNotNull('email')
                    ->get();

                $saveCount = 0;
                foreach ($sourceData as $editor) {
                    $editorUser = new User();
                    $editorUser->id = $editor->id;
                    $editorUser->name = strip_tags($editor->baslik);
                    $editorUser->email = $editor->email ? strip_tags($editor->email) : slug_format($editor->baslik . "-" . $saveCount) . '@yuzhaber.com';
                    $editorUser->about = strip_tags($editor->aciklama);
                    $editorUser->status = 2;
                    $editorUser->avatar = $editor->resim;
                    $editorUser->password = Hash::make('asd.123456');
                    $editorUser->save();

                    Article::where('author_id', strip_tags($editor->id))->update([
                        'author_id' => $editorUser->id,
                    ]);
                    $saveCount++;
                }
                dump($saveCount);
                $message[] = "MAKALELER EKLENDİ<br>";
            }


            Log::info("Database Aktarım Tamamlandı -", $message);
        } catch (Exception $e) {
            // Hata durumunda logla
            Log::error('Error in TransferDataJob: ' . $e->getMessage());
            throw $e; // Hatanın yukarıya fırlatılması
        }
    }

    public function failed(\Exception $exception)
    {
        Log::error('Job Failed: ', [
            'sourceTable' => $this->sourceTable,
            'destinationTable' => $this->destinationTable,
            'exception' => $exception->getMessage(),
        ]);
    }
}
