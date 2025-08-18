<?php

namespace Obelaw\Trail\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Obelaw\Trail\Facades\Trail;

class TrailObserver
{
    protected array $events;

    public function __construct()
    {
        $this->events = config('trail.events', ['created', 'updated', 'deleted', 'restored', 'forceDeleted']);
    }

    public function created(Model $model): void
    {
        $this->record('created', $model);
    }

    public function updated(Model $model): void
    {
        $this->record('updated', $model);
    }

    public function deleted(Model $model): void
    {
        $this->record('deleted', $model);
    }

    public function restored(Model $model): void
    {
        $this->record('restored', $model);
    }

    public function forceDeleted(Model $model): void
    {
        $this->record('forceDeleted', $model);
    }

    protected function record(string $type, Model $model): void
    {
        if (! in_array($type, $this->events, true)) {
            return;
        }

        $ignore = array_map('strval', config('trail.ignore_attributes', []));

        $attributes = Arr::except($model->getAttributes(), $ignore);

        $original = Arr::except($model->getOriginal(),   $ignore);

        $redact = array_map('strval', config('trail.redact_attributes', []));

        foreach ($redact as $k) {
            if (array_key_exists($k, $attributes)) {
                $attributes[$k] = '*****';
            }

            if (array_key_exists($k, $original)) {
                $original[$k] = '*****';
            }
        }

        $changes = $attributes;

        $snapshot = null;
        if (config('trail.store_snapshot', false)) {
            $snapshot = $original;
        }

        Trail::for($model)
            ->event($type)
            ->props($changes)
            ->changes($changes)
            ->snapshot($snapshot)
            ->save();
    }
}
