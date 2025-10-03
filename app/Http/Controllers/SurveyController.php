<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::select('id','title','created_at')->paginate(30);
        return view('backend.survey.index', compact('surveys'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.survey.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'publish' => 'required',
            'images' => 'image|mimes:jpeg,png,jpg,gif'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'images.image' => 'Resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
        ];
        $this->validate($request, $rules, $customMessages);

        $survey = new Survey();
        $survey->title = strip_tags($request->title);
        $survey->slug = slug_format($request->title);
        $survey->publish = strip_tags($request->publish);

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $survey->slug.'-anketi-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $survey->images = $images_name;
            }
        }


        if($survey->save()){
            $add_answer = new SurveyAnswer();
            $add_answer->survey_id = $survey->id;
            $add_answer->save();

            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('survey.edit', $survey->id));
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
        $survey = Survey::where('id', $id)->first();
        $surveyanswer = SurveyAnswer::where('survey_id',$id)->first();

        if($survey!=null){
            return view('backend.survey.edit', compact('survey', 'surveyanswer'));
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
            'publish' => 'required',
            'images' => 'image|mimes:jpeg,png,jpg,gif'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'images.image' => 'Resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
        ];
        $this->validate($request, $rules, $customMessages);

        $survey = Survey::find($id);
        $survey->title = strip_tags($request->title);
        $survey->slug = slug_format($request->title);
        $survey->publish = strip_tags($request->publish);

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $survey->slug.'-anketi-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $survey->images = $images_name;
            }
        }

        // Anket seçenekleri
        $surveyanswer = SurveyAnswer::where('survey_id',$id)->get();
        if(!count($surveyanswer)>0){
            $add_answer = new SurveyAnswer();
            $add_answer->survey_id = $id;
            $add_answer->answer1 = strip_tags($request->answer1);
            $add_answer->answerhit1 = strip_tags($request->answerhit1);
            $add_answer->answer2 = strip_tags($request->answer2);
            $add_answer->answerhit2 = strip_tags($request->answerhit2);
            $add_answer->answer3 = strip_tags($request->answer3);
            $add_answer->answerhit3 = strip_tags($request->answerhit3);
            $add_answer->answer4 = strip_tags($request->answer4);
            $add_answer->answerhit4 = strip_tags($request->answerhit4);
            $add_answer->save();
        }else{
            $update_answer = SurveyAnswer::where('survey_id',$id)->first();
            $update_answer->answer1 = strip_tags($request->answer1);
            $update_answer->answerhit1 = strip_tags($request->answerhit1);
            $update_answer->answer2 = strip_tags($request->answer2);
            $update_answer->answerhit2 = strip_tags($request->answerhit2);
            $update_answer->answer3 = strip_tags($request->answer3);
            $update_answer->answerhit3 = strip_tags($request->answerhit3);
            $update_answer->answer4 = strip_tags($request->answer4);
            $update_answer->answerhit4 = strip_tags($request->answerhit4);
            $update_answer->save();
        }
        // Anket seçenekleri


        if($survey->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('survey.edit', $survey->id));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function EskiUpdate(Request $request, string $id)
    {
        $rules = [
            'title' => 'required',
            'publish' => 'required',
            'images' => 'image|mimes:jpeg,png,jpg,gif'
        ];
        $customMessages = [
            'required' => 'Zorunlu alanları doldurun.',
            'images.image' => 'Resim alanı resim dosyası olmalıdır',
            'images.mimes' => 'Resim formatı hatalı',
        ];
        $this->validate($request, $rules, $customMessages);

        $survey = Survey::find($id);
        $survey->title = strip_tags($request->title);
        $survey->slug = slug_format($request->title);
        $survey->publish = strip_tags($request->publish);

        if($request->hasFile('images')){
            if ($request->file('images')->isValid()) {
                $images_name = $survey->slug.'-anketi-'.time().'.'.$request->images->extension();
                $request->images->move(public_path('uploads'), $images_name);
                $survey->images = $images_name;
            }
        }

        if(count($request->answer)>0){
            // SurveyAnswer::where('survey_id', $id)->delete();
            foreach ($request->answer as $item) {
                // önce seçenekleri sil
                $surveyanswer_connect = new SurveyAnswer();
                $surveyanswer_connect->survey_id = $id;
                $surveyanswer_connect->title = $item;
                $surveyanswer_connect->save();
            }
        }

        if($survey->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('survey.edit', $survey->id));
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
        $result = Survey::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('survey.index'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function trashed()
    {
        $surveys = Survey::onlyTrashed()->get();
        return view('backend.survey.trashed', compact('surveys'));
    }

    public function restore(Request $request, string $id)
    {
        $survey = Survey::where('id', $id);
        $survey->restore();

        if($survey->restore()==0){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('survey.trashed'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }
}
