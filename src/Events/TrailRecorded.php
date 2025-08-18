<?php

namespace Obelaw\Trail\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Obelaw\Trail\Models\Trail;

class TrailRecorded
{
    use Dispatchable, SerializesModels;

    public function __construct(public Trail $trail) {}
}
