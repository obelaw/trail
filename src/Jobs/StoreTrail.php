<?php

namespace Obelaw\Trail\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Obelaw\Trail\Events\TrailRecorded;
use Obelaw\Trail\Models\Trail;

class StoreTrail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $payload)
    {
        // no-op
    }

    public function handle(): void
    {
        $record = Trail::query()->create($this->payload);

        if (config('trail.broadcast', false)) {
            event(new TrailRecorded($record));
        }
    }
}
