<?php

namespace App\Http\Controllers;

use App\Jobs\OnemsoftTransferDataJob;
use App\Jobs\TransferDataJob;
use App\Jobs\WpTransferDataJob;
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Models\PhotoGallery;
use App\Models\PhotoGalleryImages;
use App\Models\Post;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AktarController extends Controller
{
    public function __construct() { date_default_timezone_set('Europe/Istanbul'); set_time_limit(600); }

    public function aktarte()
    {

        if ((!auth()->check() || auth()->id() != 1) && env("APP_DEBUG",false)  ) {
            toastr()->error( 'Yetkisiz işlem. İşlemleriniz kayıt altına alındı ','Hata', ['timeOut' => 5000]);
            // activity()->causedBy(auth()->user()->id)->on(new AktarController)->useLog("ihabotrun")->withProperties(['IP' => GetIP()])->log('IHA Bot Çalıştırıldı');
            return redirect()->back();
        }


        $message = [];

        $tables = [
            // [
            //     'source'=>'news',
            //     'destination'=>'post',
            // ],
    //    [
    //             'source'=>'category',
    //             'destination'=>'category',
    //     ],

        //  [
        //      'source'=>'users',
        //      'destination'=>'users',
        //  ],
        //   [
        //     'source'=>'resmi_ilanlar',
        //     'destination'=>'official_advert',
        // ],
        // [
        //     'source'=>'makaleler',
        //     'destination'=>'article',
        // ],

        // [
        //       'source'=>'video_kategori',
        //       'destination'=>'category',
        //   ],
        //   [
        //         'source'=>'video',
        //         'destination'=>'video',
        //     ],
        //     [
        //         'source'=>'albumkat_asil',
        //         'destination'=>'category',
        //     ],
        //     [
        //         'source'=>'albumkat',
        //         'destination'=>'photogallery',
        //     ],
        //     [
        //         'source'=>'album',
        //         'destination'=>'photogalleryimages',
        //     ],
        //    [
        //         'source'=>'icerik_icerik',
        //         'destination'=>'page',
        //     ],
        //    [
        //         'source'=>'gazete',
        //         'destination'=>'enewspaper',
        //     ],


        ];


      /** Tamamlandı */ // Category::truncate();
      /** Tamamlandı */ // Post::truncate();
      /** Tamamlandı */ // Video::truncate();
      /** Tamamlandı */ // PhotoGallery::truncate();
      /** Tamamlandı */ // PhotoGalleryImages::truncate();
      /** Tamamlandı */ // Page::truncate();
      /** Tamamlandı */ // Article::truncate();

    $wp_tables = [
        // [
        //     'source'=>'wpnl_terms',
        //     'destination'=>'category',
        // ],
        // [
        //     'source'=>'wpnl_users',
        //     'destination'=>'users',
        // ],
        [
            'source'=>'wpnl_posts',
            'destination'=>'post',
        ],
        // [
        //     'source'=>'wpnl_postmeta',
        //     'destination'=>'postmeta',
        // ],
        // [
        //     'source'=>'wp_term_taxonomy',
        //     'destination'=>'term_taxonomy',
        // ],
        // [
        //     'source'=>'wp_term_relationships',
        //     'destination'=>'term_relationships',
        // ],
    ];

      if (true){

        // foreach ($tables as $table) {
        //     $sourceTable = $table['source'];
        //     $destinationTable = $table['destination'];
        //     // Önem soft haber taşıma işlemi
        //     OnemsoftTransferDataJob::dispatch($sourceTable, $destinationTable);

        //     // Tebilişim  haber taşıma işlemi
            // TransferDataJob::dispatch($sourceTable, $destinationTable);
        // }


        foreach ($wp_tables as $table) {
            $sourceTable = $table['source'];
            $destinationTable = $table['destination'];
            WpTransferDataJob::dispatch($sourceTable, $destinationTable);
        }

        toastr()->success( 'İşlem başladı birsüre sonra tekrar kontrol edin','BAŞARILI', ['timeOut' => 5000]);
        return $message;
    }else {
        toastr()->error( 'İşlem sırasında sorun oluştu ','BAŞARISIZ', ['timeOut' => 5000]);
    }


    //    return back();


    } // Aktar TE method bitişi


}















































