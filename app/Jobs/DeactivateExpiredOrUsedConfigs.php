<?php

namespace App\Jobs;

use App\Models\VConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeactivateExpiredOrUsedConfigs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = now();

        VConfig::where('status', true)
            ->where(function ($query) use ($now) {
                $query->whereNotNull('expired_at')->where('expired_at', '<', $now)
                      ->orWhereColumn('usage', '>=', 'total');
            })
            ->update(['status' => false]);
    }
}
