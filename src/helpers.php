<?php

use Illuminate\Contracts\Auth\Authenticatable;

if (! function_exists('trail_causer')) {
    function trail_causer(): ?Authenticatable
    {
        try {
            return auth()->user();
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (! function_exists('trail_event')) {
    /**
     * سجل حدثًا مخصصًا بسرعة.
     *
     * @param string $type
     * @param array $properties
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \Obelaw\Trail\Models\Trail
     */
    function trail_event(string $type, array $properties = [], $model = null)
    {
        /** @var \Obelaw\Trail\TrailLogger $logger */
        $logger = app('obelaw.trail.logger');
        return $logger->for($model)->type($type)->props($properties)->save();
    }
}
