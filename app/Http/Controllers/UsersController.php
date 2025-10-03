<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereNot('id',1)->select('id','name','email','status','created_at','avatar')->paginate(30);
        return view('backend.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
	        'email' => 'required|email:rfc,dns|unique:users,email,'.$request->user()->id,
            'password' => 'required',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'email' => 'E posta gereklidir.',
            'unique' => 'E posta kayıtlarda mevcut görünüyor.',
            'image' => 'Resim formatını kontrol edin.',
            'mimes' => 'Resim desteklenen formatlar şunlardır: jpeg,png,jpg,gif,svg',
        ];
        $this->validate($request, $rules, $customMessages);

        $user = new User();
        $user->name = strip_tags($request->name);
        $user->email = strip_tags($request->email);
        $user->password = Hash::make($request->password);
        $user->phone = strip_tags($request->phone);
        $user->about = strip_tags($request->about);
        $user->status = !blank($request->status) ? strip_tags($request->status) : 2 ;

        if($request->hasFile('avatar')){
            if ($request->file('avatar')->isValid()) {
                $avatar_name = slug_format($user->name, '-').'-'.time().'.'.$request->avatar->extension();
                $request->avatar->move(public_path('uploads'), $avatar_name);
            }
            $user->avatar = 'uploads/'.$avatar_name;
        }

        if($user->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            $article = User::where('status', 3)
            ->with(['latestArticle' => function ($query) {
                $query->where('publish', 0)->select('article.id', 'article.title', 'article.slug', 'article.detail', 'article.created_at', 'article.author_id');}])
            ->select('users.id', 'users.name', 'users.avatar')->get();
            Storage::disk('public')->put('main/authors.json', $article);
            return redirect(route('users.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
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
        if(auth()->user()->status==1 || auth()->id()==$id ){
            $user = User::where('id', $id)->first();
            if($user!=null){
                return view('backend.users.edit', compact('user'));
            }else{
                return back();
            }
        }else{
            toastr()->warning('SENİ GİDİ SENİ, SANA DÜNYALIKLAR ELLESİN :)','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(auth()->user()->status==1 || auth()->id()== $id ){
            $rules = [
                'name' => 'required',
                //'email' => 'required|email',
                'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
            $customMessages = [
                'required' => 'Zorunlu alanları doldurun.',
                'email' => 'E posta gereklidir.',
                'image' => 'Resim formatını kontrol edin.',
                'mimes' => 'Resim desteklenen formatlar şunlardır: jpeg,png,jpg,gif,svg',
            ];
            $this->validate($request, $rules, $customMessages);

            $user = User::find($id);
            $user->name = strip_tags($request->name);
            $user->email = strip_tags($request->email);
            if($request->password!=null){ $user->password = Hash::make($request->password); }
            $user->phone = strip_tags($request->phone);
            $user->about = strip_tags($request->about);
            $user->status = !blank($request->status) ? strip_tags($request->status) : $user->status ;

            if($request->hasFile('avatar')){
                if ($request->file('avatar')->isValid()) {
                    $avatar_name = slug_format($user->name, '-').'-'.time().'.'.$request->avatar->extension();
                    $request->avatar->move(public_path('uploads'), $avatar_name);

                }
                $user->avatar = 'uploads/'.$avatar_name;
            }

            if($user->save()){
                toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
                if(auth()->user()->status==3){
                    updateAuthor();
                }
                return redirect(route('users.edit', $id));
            }else{
                toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
                return back();
            }
        }else{
            toastr()->warning('SENİ GİDİ SENİ, SANA DÜNYALIKLAR ELLESİN :)','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if($id!=1){
            $result = User::destroy($id);
            if($result){
                updateAuthor();
                toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
                return redirect(route('users.index'));
            }else{
                toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
                return back();
            }
        }else{
            toastr()->error('Ana kullanıcı silinemez','BAŞARISIZ', ['timeOut' => 10000]);
            return back();
        }

    }

    public function trashed()
    {
        $users = User::onlyTrashed()->get();

        return view('backend.users.trashed', compact('users'));
    }

    public function restore(Request $request, string $id)
    {
        $users = User::where('id', $id);
        $users->restore();

        if($users->restore()==0){
            updateAuthor();
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('users.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function deleted(string $id)
    {
        if($id!=1){
            DB::transaction(function () use ($id) {
                $result = User::onlyTrashed()->where('id',$id)->first();
                $result->article()->delete();
                $result->forceDelete();
                if($result){
                    toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
                    updateAuthor();
                    // return redirect(route('users.index'));
                }else{
                    toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
                    // return back();
                }
            });

            DB::commit();
            return back();


        }else{
            toastr()->error('Ana kullanıcı silinemez','BAŞARISIZ', ['timeOut' => 10000]);
            return back();
        }

    }

}



















