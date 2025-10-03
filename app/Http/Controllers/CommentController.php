<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::select('id','title','email','created_at','publish','type')->with('post')->latest()->paginate(30);
        return view('backend.comment.index', compact('comments'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $comment = Comment::where('id', $id)->first();
        if($comment!=null){
            return view('backend.comment.edit', compact('comment'));
        }else{
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'title' => 'required',
            'detail' => 'required',
            'publish' => 'required',
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
        ];
        $this->validate($request, $rules, $customMessages);

        $comment = Comment::find($id);
        $comment->title = strip_tags($request->title);
        $comment->slug = slug_format($request->title);
        $comment->email = strip_tags($request->email);
        $comment->detail = strip_tags($request->detail);
        $comment->publish = strip_tags($request->publish);

        if($comment->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('comment.edit', $comment->id));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = Comment::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('comment.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $comments = Comment::onlyTrashed()->get();
        return view('backend.comment.trashed', compact('comments'));
    }

    public function restore(Request $request, string $id)
    {
        $comment = Comment::where('id', $id);
        $comment->restore();

        if($comment->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('comment.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }
}
