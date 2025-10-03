<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostArchive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Süresi geçen haberlerin arşive taşınması için kullanılır.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Post:archive İşlemleri başlıyor...');

        $settings = Settings::select('magicbox')->find(1);
        $magicboxAarray = json_decode($settings->magicbox, true);
        $recording_time = $magicboxAarray['archive_recording_time'] ?? 1000;

        if ($recording_time != null) {
            $archiveDate = Carbon::now()->subDays($recording_time)->format('Y-m-d H:i:s');

            $count = Post::where('is_archived',false)->where('created_at', '<', $archiveDate)->count();
            DB::transaction(function() use ($archiveDate) {
                Post::where('is_archived',false)->where('created_at', '<', $archiveDate)->update(['is_archived' => true]);
            });

            $this->info('Post:archive  tamamlandı... ' .$count. '  haber arşive taşındı .');
            Log::info('Post:archive  tamamlandı ... ' .$count. ' haber arşive taşındı .');
        }
        else {
            $this->info(' post archive time Null ');
            Log::info(' Post archive time Null ');
        }
    }
}
