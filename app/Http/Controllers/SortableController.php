<?php

namespace App\Http\Controllers;


use App\Models\Ads;
use App\Models\Category;
use App\Models\Menus;
use App\Models\Post;
use App\Models\Sortable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class SortableController extends Controller
{
    public function sortableSlide(Request $request)
    {
        $slides = Post::where("publish", 0)
        ->whereHas('locations', function($query) {
            $query->where('location_id', 1);
        })
        ->select('id', 'title', 'images', 'sortby')
        ->orderBy('created_at', 'DESC')
        ->limit(50)
        ->get()->sortBy('sortby');


        return view('backend.sortable.sortableslides', compact('slides'));
    }

    public function sortableSlidePost(Request $request)
    {



        if ( !blank($request->sortedData)){

            DB::transaction(function () use ($request){

                foreach ($request->sortedData as $key => $value) {
                    $itemID = $value['itemID'];
                    $itemIndex = $value['itemIndex'];
                    Post::where('id',$itemID)->update(['sortby' => $itemIndex]);
                }
            });

            $ids =  collect($request->sortedData)->take(14)->pluck('itemID');

        }


        return $this->positionUpdate($ids);
    }

    public function positionUpdate($ids = null)
    {

        if (blank($ids)) {
            $ids = Post::where("publish", 0)
            ->whereHas('locations', function($query) {
                $query->where('location_id', 1);
            })
            ->select('id')
            ->orderBy('created_at', 'DESC')
            ->limit(14)
            ->pluck('id');
        }

        $posts = Post::whereIn('post.id',$ids)->whereHas('locations',function($query){
            $query->where('location_id', 1);
        })
        ->join('category', 'category.id', '=', 'post.category_id')
        ->where(["post.publish" => 0 ])
        ->select('post.id','post.title','post.description','post.slug','post.images','post.sortby','post.created_at','post.category_id','post.redirect_link',
        'post.show_title_slide','category.slug as categoryslug','category.title as categorytitle','category.color', 'post.extra')
        ->orderBy('post.created_at', 'desc')
        ->limit(15)
       ->get()->sortby('sortby')->values();

        Storage::disk('public')->put('main/ana_manset.json', $posts);
    }


    public function sortableHomePage()
    {
        $sortable = Sortable::orderBy('sortby','ASC')->get();
        $categories = Category::where('category_type', 0)->select('id','title')->get();
        $ads = Ads::where('publish',0)->select('id','title')->where('id',"!=",1)->get();
        $menus = Menus::select('id','title')->get();

        return view('backend.sortable.sortablemain', compact('sortable','categories','ads','menus'));
    }

    public function sortableHomePagePost(Request $request)
    {
        $itemID = $request->itemID;
        $itemIndex = $request->itemIndex;

        return DB::table('sortable')->where('id', '=', $itemID)->update(array('sortby' => $itemIndex)).$this->main_json_update();
    }

    public function sortableHomePagePostOtherSetting(Request $request)
    {
        return DB::table('sortable')->where('id', '=', $request->parent_id)->update(array($request->name => $request->value)).$this->main_json_update();
    }

    public function main_json_update()
    {
        $sortable = Sortable::select('id','type','title','category','ads','menu','limit','file','design','color','sortby')->orderBy('sortby','asc')->get();
        Storage::disk('public')->put('main/sortable_list.json', $sortable);

    }

}































