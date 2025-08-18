<?php

namespace Obelaw\Trail\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Obelaw\Trail\Models\Trail;

class PruneTrailsCommand extends Command
{
    protected $signature = 'trail:prune {--days= : Retention days; default from config(trail.retention_days)}';
    protected $description = 'Prune old trail records based on retention policy.';

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?: config('trail.retention_days', 365));
        $cutoff = Carbon::now()->subDays(max(1, $days));

        $query = Trail::query()->where('created_at', '<', $cutoff);

        $count = $query->count();
        $deleted = 0;
        if ($count > 0) {
            // chunk to avoid large deletes
            $query->chunkById(1000, function ($rows) use (&$deleted) {
                $ids = $rows->pluck('id');
                $deleted += Trail::whereIn('id', $ids)->delete();
            });
        }

        $this->info("Deleted {$deleted} of {$count} old trail records older than {$days} days.");
        return self::SUCCESS;
    }
}
