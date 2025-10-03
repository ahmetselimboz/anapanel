<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panel;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function getNotifications($domain)
    {

        $notifications = Notification::select('id', 'title', 'message')
            ->where(function ($query) use ($domain) {
                $query->whereHas('panel', function ($q) use ($domain) {
                    $q->where('domain', $domain);
                })->orWhereNull('customer_id');
            })->orderBy("created_at", 'desc')
            ->get();



            
        return response()->json($notifications);
    }


    public function index()
    {

        $notifications = Notification::with('panel')->orderBy('created_at', 'desc')->get();
        //dd($notifications);

        $notifications->transform(function ($notification) {
            $notification->created_at_formatted = Carbon::parse($notification->created_at)->format('d.m.Y H:i');
            return $notification;
        });

        return view("backend.notifications.index", compact('notifications'));
    }

    public function createPage()
    {

        $customers = Panel::orderBy('created_at', 'desc')->get();


        return view("backend.notifications.create", compact("customers"));
    }

    public function create(Request $request)
    {

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

    public function delete($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->delete();
            return redirect()->back()->with('success', 'Bildirim silindi.');
        }

        return redirect()->back()->with('error', 'Bildirim bulunamadı.');
    }

    public function updatePage($id)
    {
        $notification = Notification::with('panel')->find($id);
        $customers = Panel::get();

        if (!$notification) {
            return redirect()->back()->with('error', 'Bir hata oluştu!');
        }

        return view("backend.notifications.update", compact("notification", "customers"));
    }

    public function update(Request $request)
    {


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

}
