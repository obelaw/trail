<?php

namespace Obelaw\Trail\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Obelaw\Trail\Recording\TrailBuilder make()
 * @method static \Obelaw\Trail\Recording\TrailBuilder for(?\Illuminate\Database\Eloquent\Model $model = null)
 * @method static \Obelaw\Trail\Recording\TrailBuilder event(string $type)
 * @method static \Obelaw\Trail\Recording\TrailBuilder type(string $type)
 * @method static mixed store(array $payload)
 */
class Trail extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'obelaw.trail.logger';
    }
}
