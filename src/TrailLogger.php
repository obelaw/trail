<?php

namespace Obelaw\Trail;

use Illuminate\Database\Eloquent\Model;
use Obelaw\Trail\Recording\TrailBuilder;

class TrailLogger
{
    public function make(): TrailBuilder
    {
        return TrailBuilder::make();
    }

    public function for(?Model $model = null): TrailBuilder
    {
        return TrailBuilder::make()->for($model);
    }

    public function event(string $event): TrailBuilder
    {
        return TrailBuilder::make()->event($event);
    }

    // alias for ergonomics
    public function type(string $event): TrailBuilder
    {
        return $this->event($event);
    }

    /**
     * Store a payload directly (already computed in observer)
     */
    public function store(array $payload)
    {
        if (config('trail.queue', true)) {
            \Obelaw\Trail\Jobs\StoreTrail::dispatch($payload);
            return null;
        }

        $record = \Obelaw\Trail\Models\Trail::query()->create($payload);

        if (config('trail.broadcast', false)) {
            event(new \Obelaw\Trail\Events\TrailRecorded($record));
        }

        return $record;
    }
}
