<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;

class ImageGalleryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $path = $request->input('path', '');
    
        $baseDirectory = public_path('uploads');
        $currentDirectory = $baseDirectory . ($path ? DIRECTORY_SEPARATOR . $path : '');
    
        // Tüm klasörleri al
        $allDirs = File::directories($currentDirectory);
    
        // Sadece klasör ismini al (uploads/.../... yapısı)
        $folderNames = collect($allDirs)->map(function ($dir) use ($baseDirectory) {
            return str_replace($baseDirectory . DIRECTORY_SEPARATOR, '', $dir);
        });
    
        // Sadece resim dosyalarını al
        $allFiles = File::files($currentDirectory);
        $images = collect($allFiles)->filter(function ($file) {
            return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });
    
        if ($search) {
            $images = $images->filter(function ($file) use ($search) {
                return str_contains(strtolower($file->getFilename()), strtolower($search));
            });
        }
    
        $images = $images->reverse();
    
        $perPage = 12;
        $page = $request->get('page', 1);
        $pagedImages = new LengthAwarePaginator(
            $images->slice(($page - 1) * $perPage, $perPage),
            $images->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

    
        return view('backend.image_gallery.gallery', [
            'images' => $pagedImages,
            'folders' => $folderNames,
            'search' => $search,
            'path' => $path,
        ]);
    }
    public function destroy($filename)
    {
        $path = public_path('uploads/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
            return back()->with('success', 'Görsel silindi.');
        }

        return back()->with('error', 'Görsel bulunamadı.');
    }

    public function modalContent(Request $request)
    {
        $search = $request->input('search');
        $directory = public_path('uploads');
        $files = File::files($directory);

        $images = collect($files)->filter(function ($file) {
            return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });

        if ($search) {
            $images = $images->filter(function ($file) use ($search) {
                return str_contains(strtolower($file->getFilename()), strtolower($search));
            });
        }

        // Önce dosya isimlerini al ve koleksiyonu ters çevir
        $images = $images->map(fn($file) => $file->getFilename())->values()->reverse();

        $perPage = 9;
        $page = $request->get('page', 1);
        $pagedImages = new \Illuminate\Pagination\LengthAwarePaginator(
            $images->slice(($page - 1) * $perPage, $perPage)->values(),
            $images->count(),
            $perPage,
            $page,
            ['path' => route('gallery.modal.content'), 'query' => $request->query()]
        );

        return response()->json([
            'images' => $pagedImages,
            'search' => $search
        ]);
    }

}
