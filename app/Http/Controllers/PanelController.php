<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panel;
use Carbon\Carbon;
use App\Models\Reader;
use Illuminate\Support\Facades\Validator;


class PanelController extends Controller
{
    
    public function getPanelInfo($domain){
 
       $panel = Panel::select('title', 'domain', 'slug', 'status', 'status_date')
        ->where('domain', $domain)
        ->orderBy("created_at", 'desc')
        ->first();
        
        if($panel === null){
            $panel = new Panel();
            $panel->title = $domain;
            $panel->domain = $domain;
            $panel->status = true;
            $panel->save();
            
            //$panel = $panel->fresh(['title', 'domain', 'slug', 'status', 'status_date']);
            
            return response()->json($panel);
        }

        if ($panel && $panel->status == 1 && $panel->status_date && Carbon::parse($panel->status_date)->isPast()) {
      
          Panel::where('domain', $domain)->update([
                'status' => 0,
                'status_date' => null,
            ]);
            $panel->refresh(); // modeli güncelle
        }
        

        return response()->json($panel);
    }
    
    public function index()
    {
        $panels = Panel::orderBy('created_at', 'desc')->get();
        return view("backend.panel.index", compact("panels"));
    }
    public function createPage()
    {
        return view("backend.panel.create");
    }


    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
        ]);

        Panel::create($request->only('title', 'domain'));

        return redirect()->route('panels.index')->with('success', 'Kayıt başarıyla eklendi!');
    }

    public function edit($slug)
    {
        $panel = Panel::where('slug', $slug)->first();
    
        
        return view("backend.panel.edit", compact("panel"));
    }

    public function update(Request $request, $slug)
    {
        $panel = Panel::where('slug', $slug)->first();
        $panel->update($request->only('title', 'domain', 'status', 'status_date'));
        return redirect()->route('panels.index')->with('success', 'Panel güncellendi.');
    }

    public function delete($slug)
    {
        $panel = Panel::where('slug', $slug)->first();

        if ($panel) {
            $panel->delete();
            return redirect()->route('panels.index')->with('success', 'Site silindi.');
        }

                return redirect()->route('panels.index')->with('error', 'Site bulunamadı.');
    }

    /**
     * Status değerini switch ile değiştir (AJAX)
     */
    public function toggleStatus(Request $request)
    {
        $request->validate([
            'panel_id' => 'required|exists:customer,id',
            'status' => 'required|boolean'
        ]);

        try {
            $panel = Panel::findOrFail($request->panel_id);
            $panel->status = $request->status;
            $panel->save();

            

            return response()->json([
                'success' => true,
                'message' => 'Status başarıyla güncellendi.',
                'status' => $panel->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function postReaderInfo(Request $request){
        
      
        
         // 1. Validasyon (email veya phone_number zorunlu, sadece biri gelecek)
        $validator = Validator::make($request->all(), [
            'domain'        => 'required|string|exists:panel,domain',
            'email'         => 'nullable|email|required_without:phone_number',
            'phone_number'  => 'nullable|string|required_without:email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            // 2. Domain üzerinden panel bul
            $panel = Panel::where('domain', $request->domain)->first();

            if (!$panel) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Panel bulunamadı.'
                ], 404);
            }

            // 3. Reader kaydı oluştur
            Reader::create([
                'customer_id'  => $panel->id,
                'email'        => $request->email,
                'phone_number' => $request->phone_number
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Kayıt başarıyla oluşturuldu.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function readerIndex($slug){
        $panel = Panel::where("slug", $slug)->first();
        $readers = Reader::where("customer_id", $panel->id)->paginate(10);
        return view("backend.reader.index", compact('readers'));
    }
    
    public function readerDelete($id){
   
        $reader = Reader::where("id", $id)->first();
           if ($reader) {
            $reader->delete();
            return redirect()->back()->with('success', 'Okuyucu silindi.');
        }
        return redirect()->back()->with('error', 'Okuyucu bulunamadı.');
        

    }


}
