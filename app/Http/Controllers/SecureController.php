<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Message;
use App\Models\Category;
use App\Models\Settings;
use App\Models\Sortable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\Console\Output\BufferedOutput;
use App\Models\Panel;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\Information;

class SecureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $count_post = DB::table('post')->count();
        $todayNewsCount = Post::whereDate('created_at', today())->count();
        $count_photo_gallery = DB::table('photogallery')->count();
        $count_video_gallery = DB::table('video')->count();
        $count_user = DB::table('users')->count();
        $seocheck = DB::table('seocheck')->count();

        $activitylogs = Activity::orderBy('id', 'desc')->with('causer:id,name')->limit(10)->get();

        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);
        $posts = Post::select('id', 'title', 'category_id', 'meta_title', 'meta_description', 'hit', 'publish', 'created_at','extra')
            ->with('category')->orderBy('id', 'desc')->whereDate('created_at', today())->paginate(10);
        ## ÖNCEKİ KATEGORİ CHART YAPISI BÜYÜK HABER RAKAMLARINDA KASIYOR FARKLI BİR YAPI KURMAK İÇİN KAPATIYORUM
        ## $category = Category::where('category_type', 0)->select('id','title')->get();
        ## foreach ($category as $value){ $serie[] = "'".(count(Post::where('category_id', $value->id)->get()))."'"; $label[] = "'".$value->title."'"; }
        ## $labels = implode(",",$label);
        ## $series = implode(",",$serie);

        $category = Category::where('category_type', 0)->select('id', 'title', 'countnews')->get();
        foreach ($category as $value) {
            $serie[] = "'" . $value->countnews . "'";
            $label[] = "'" . $value->title . "'";
        }
        if (isset($label)) {
            $labels = implode(",", $label);
            $series = implode(",", $serie);
        } else {
            $labels = "no";
            $series = "no";
        }




        $trends = [];


        // $seocheck = DB::table('seocheck')->count();

        return view('backend.index', compact('category', 'posts', 'count_post', 'count_user', 'count_video_gallery','todayNewsCount',
         'count_photo_gallery', 'activitylogs', 'labels', 'series', 'settings', 'jsondata', 'seocheck', "trends"));
    }

    public function optimize()
    {
        Artisan::call('optimize:clear');
        cache()->forget('posts');
        cache()->forget('count_data');
        cache()->forget('post_position_json');
        cache()->forget('all_ads');
        cache()->forget('all_categories');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        // saat 22:00 dan sabah 09:00 arası çalışsın
        $now = now()->format('H:i');
        if ($now >= '22:00' || $now < '09:00') {
            Artisan::call('cache:clear-images');
        }
        toastr()->success('OPTİMİZE İŞLEMİ TAMAMLANDI', 'BAŞARILI', ['timeOut' => 5000]);

        return back();
    }

    public function jsonsystemcreate()
    {
        $sortable = Sortable::select('id', 'type', 'title', 'category', 'ads', 'menu', 'limit', 'file', 'design', 'color', 'sortby')
        ->orderBy('sortby', 'asc')->get();
        Storage::disk('public')->put('main/sortable_list.json', $sortable);
        $hit_news = Post::where('publish', 0)->whereHas('category')
            ->where('created_at', '<=', now())
            ->where('created_at', '>=', now()->subMonth())
            ->select('id', 'title', 'slug', 'category_id', 'images', 'publish', 'created_at','hit', 'extra')
            ->orderBy('hit', 'desc')
            ->limit(50)->get();

        Storage::disk('public')->put('main/hit_news.json', $hit_news);

        // Artisan::call('optimize:clear');
        toastr()->success('ANASAYFA YAPILANDIRMA TAMAMLANDI', 'BAŞARILI', ['timeOut' => 5000]);

        return back();
    }

    public function activitylogs()
    {
        $activitylogs = Activity::orderBy('id', 'desc')->simplePaginate(30);

        return view('backend.activitylogs', compact('activitylogs'));
    }

    public function message()
    {
        $messages = Message::orderBy('id', 'desc')->simplePaginate(30);

        return view('backend.message', compact('messages'));
    }

    public function seocheck(Request $request, $type)
    {
        if ($type == 1) {
            $query = "meta_title";
            $count_text = 58;
        } elseif ($type == 2) {
            $query = "meta_description";
            $count_text = 150;
        } elseif ($type == 3) {
            $query = "keywords";
            $count_text = 20;
        }

        foreach (Post::select("$query","id")->where('publish',0)->limit(1000)->get() as $meta){
            if($meta->$query==null or strlen($meta->$query)<$count_text){
                if(count(DB::table('seocheck')->where('post_id', $meta->id)->get())==0){
                    DB::table('seocheck')->insert([ 'post_id' => $meta->id ]);
                }
            }else{
                DB::table('seocheck')->where([ 'post_id' => $meta->id ])->delete();
            }
        }

        $seochecks =  DB::table('seocheck')->join("post","seocheck.post_id","post.id")->get();

        // tüm seodaki tablodakileri silip kontrolü manuelde sagla
        return view('backend.seocheck', compact('seochecks', 'type'));
    }


    public function migrate()
    {
        $message = "";
        if(Auth::check() && Auth::id() == 1){
            if (App::isProduction()) {
                abort(404);
            }
            $output = new BufferedOutput();
            try {
                Artisan::call('migrate', [], $output);
                $result = $output->fetch();
                $status = 'success';
                $message = 'Migration işlemi başarıyla tamamlandı.';
            } catch (\Exception $e) {
                $result = $output->fetch();
                $status = 'error';
                $message = 'Hata oluştu: ' . $e->getMessage();
            }
            return $result;
        }
        toastr()->success($message, 'BAŞARILI', ['timeOut' => 5000]);
        return redirect()->back();
    }
    
    public function getNotifications($domain){
        
$notifications = Notification::select('id', 'title', 'message')
    ->where(function($query) use ($domain) {
        $query->whereHas('panel', function($q) use ($domain) {
            $q->where('domain', $domain);
        })->orWhereNull('customer_id');
    })->orderBy("created_at", 'desc')
    ->get();

        
        
         return response()->json($notifications);
    }
    

    public function notificationList(){
        
          $notifications = Notification::with('panel')->orderBy('created_at', 'desc')->get();
            //dd($notifications);
            
           $notifications->transform(function ($notification) {
                $notification->created_at_formatted = Carbon::parse($notification->created_at)->format('d.m.Y H:i');
                return $notification;
            });
        
        return view("backend.notifications.index", compact('notifications'));
    }
    
    public function notificationCreate(){
        
         $customers = Panel::orderBy('created_at', 'desc')->get();
        
        
        return view("backend.notifications.create_notification", compact("customers"));
    }
    
    public function notificationStore(Request $request){
        
        $request->validate([
            'customer_id' => 'required',
            'title' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);
    
        Notification::create([
            'customer_id' => $request->customer_id == 0 ? null : $request->customer_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);
    
        return redirect()->back()->with('success', 'Bildirim başarıyla eklendi!');
    }
    
    public function notificationDelete($id){
        $notification = Notification::find($id);
    
        if ($notification) {
            $notification->delete();
            return redirect()->back()->with('success', 'Bildirim silindi.');
        }
    
        return redirect()->back()->with('error', 'Bildirim bulunamadı.');
    }
    
    public function notificationUpdatePage($id){
        $notification = Notification::with('panel')->find($id);
        $customers = Panel::get();
        
        if (!$notification) {
            return redirect()->back()->with('error', 'Bir hata oluştu!');
        }
    
        return view("backend.notifications.update_notifications", compact("notification","customers"));
    }
    
    public function notificationUpdate(Request $request){
        
        
        // Geçerli notification ID'si formda gizli alan olarak gönderilmediyse, başka bir yolla alınmalı
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
           
        ]);
    
        // Örnek: notification ID'si hidden input olarak gelsin diye varsayalım
        $notification = Notification::findOrFail($request->input('id'));
    
        $notification->title = $request->input('title');
        $notification->message = $request->input('message');
    
        // Eğer 0 ise tüm müşterilere gönderilmek üzere (null olarak kaydedilebilir)
        if ($request->input('customer_id') == 0) {
            $notification->customer_id = null;
        } else {
            $notification->customer_id = $request->input('customer_id');
        }
    
        $notification->save();
    
        return redirect()->back()->with('success', 'Bildirim başarıyla güncellendi.');
    }
    
    
    
    
    public function customers(){
         $customers = Panel::orderBy('created_at', 'desc')->get();
        return view("backend.notifications.customer_list", compact("customers"));
    }
    public function customerCreate(){
        return view("backend.notifications.create_site");
    }
    
     
    public function customerStore(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
        ]);
    
        Panel::create($request->only('title', 'domain'));
    
        return redirect()->back()->with('success', 'Kayıt başarıyla eklendi!');
    }
    
    public function customerDelete($id){
       $panel = Panel::find($id);
    
        if ($panel) {
            $panel->delete();
            return redirect()->back()->with('success', 'Site silindi.');
        }
    
        return redirect()->back()->with('error', 'Site bulunamadı.');
    }
        public function getInformation(){
        $info = Information::select('message')->get();
        $info = $info ? $info : "";
        return response()->json($info);
    }
    
    public function information(){
        $info = Information::get();
        $info = $info ? $info : "";
        
        return view("backend.information", compact('info'));
    }
    
public function informationUpdate(Request $request)
{
    // Validasyon (isteğe bağlı)
    $request->validate([
        'message' => 'required|string'
    ]);

    // İlk kaydı al
    $info = Information::first();

    // Eğer kayıt varsa güncelle, yoksa oluştur
    if ($info) {
        $info->update([
            'message' => $request->message
        ]);
    } else {
        Information::create([
            'message' => $request->message
        ]);
    }

    return redirect()->back()->with('success', 'Bilgi güncellendi.');
}
}
