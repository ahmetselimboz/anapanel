<?php

namespace Database\Seeders;

use App\Models\Sortable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SortableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sortable::truncate();

        DB::table('sortable')->insert([
            ["type" => "menu", "title" => "Menü 1", "category" => "1", "ads" => null, "menu" => "1", "limit" => null, "file" => "block_main_menu", "design" => "0", "color" => null, "sortby" => "0"],
            ["type" => "ads", "title" => "Reklam 1", "category" => "1", "ads" => "2", "menu" => null, "limit" => null, "file" => "block_main_ads", "design" => null, "color" => null, "sortby" => "1"],
            ["type" => "main", "title" => "Dörtlü Manşet", "category" => "1", "ads" => null, "menu" => null, "limit" => null, "file" => "block_dortlu_manset", "design" => null, "color" => null, "sortby" => "2"],
            ["type" => "main", "title" => "Son Dakika Bandı", "category" => "1", "ads" => null, "menu" => null, "limit" => null, "file" => "block_son_dakika", "design" => null, "color" => null, "sortby" => "3"],
            ["type" => "main", "title" => "Ana ve Mini Manşet", "category" => "1", "ads" => null, "menu" => null, "limit" => null, "file" => "block_ana_manset", "design" => "2", "color" => null, "sortby" => "4"],
            ["type" => "main", "title" => "Haberler 1", "category" => "1", "ads" => null, "menu" => null, "limit" => "12", "file" => "block_global", "design" => "default", "color" => null, "sortby" => "6"],
            ["type" => "main", "title" => "Videolar", "category" => "1", "ads" => null, "menu" => null, "limit" => null, "file" => "block_videos", "design" => null, "color" => null, "sortby" => "7"],
            ["type" => "main", "title" => "Haberler 2", "category" => "11", "ads" => null, "menu" => null, "limit" => "12", "file" => "block_global", "design" => "default", "color" => null, "sortby" => "8"],
            ["type" => "ads", "title" => "Reklam 2", "category" => "1", "ads" => "3", "menu" => null, "limit" => null, "file" => "block_main_ads", "design" => null, "color" => null, "sortby" => "9"],
            ["type" => "main", "title" => "Yazarlar", "category" => "1", "ads" => null, "menu" => null, "limit" => null, "file" => "block_authors", "design" => null, "color" => null, "sortby" => "5"],
            ["type" => "ads", "title" => "Reklam 3", "category" => "1", "ads" => "4", "menu" => null, "limit" => null, "file" => "block_main_ads", "design" => null, "color" => null, "sortby" => "10"],
            ["type" => "main", "title" => "Haberler 3 ve Çok Okunanlar", "category" => "1", "ads" => null, "menu" => null, "limit" => "12", "file" => "block_global", "design" => "default", "color" => null, "sortby" => "11"],
            ["type" => "main", "title" => "Foto Galeri", "category" => "1", "ads" => null, "menu" => null, "limit" => null, "file" => "block_galleries", "design" => null, "color" => null, "sortby" => "12"],
            ["type" => "main", "title" => "Haberler 4", "category" => "1", "ads" => null, "menu" => null, "limit" => "8", "file" => "block_global", "design" => "default", "color" => null, "sortby" => "13"],
            ["type" => "ads", "title" => "Reklam 4", "category" => "1", "ads" => "5", "menu" => null, "limit" => null, "file" => "block_main_ads", "design" => null, "color" => null, "sortby" => "14"],
            ["type" => "menu", "title" => "Menü 2", "category" => "1", "ads" => null, "menu" => "2", "limit" => null, "file" => "block_main_menu", "design" => "1", "color" => null, "sortby" => "16"],
            ["type" => "menu", "title" => "Menü 3", "category" => "1", "ads" => null, "menu" => "3", "limit" => null, "file" => "block_main_menu", "design" => "2", "color" => null, "sortby" => "17"],
            ["type" => "menu", "title" => "Özel Haberler", "category" => "1", "ads" => null, "menu" => null, "limit" => null, "file" => "with_hit_news", "design" => "default", "color" => null, "sortby" => "15"]
        ]);
        


    }
}
