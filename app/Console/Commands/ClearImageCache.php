<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearImageCache extends Command
{
    protected $signature = 'cache:clear-images';
    protected $description = 'public/cache/images klasöründeki resimleri temizler';

    public function handle()
    {
        $path = public_path('cache/images');

        if (File::exists($path)) {
            $files = File::files($path);

            foreach ($files as $file) {
                File::delete($file->getRealPath());
            }

            $this->info(count($files) . " dosya silindi.");
        } else {
            $this->info("Klasör bulunamadı: " . $path);
        }
    }
}
