<?php

namespace Obelaw\Trail\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Obelaw\Trail\Models\Trail;

trait HasTrail
{
    public function trails(): MorphMany
    {
        return $this->morphMany(Trail::class, 'causer');
    }
}
