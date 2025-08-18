<?php

namespace Obelaw\Trail\Traits;

use Obelaw\Trail\Observers\TrailObserver;

trait TracksTrail
{
    public static function bootTracksTrail(): void
    {
        static::observe(app(TrailObserver::class));
    }
}
