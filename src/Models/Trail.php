<?php

namespace Obelaw\Trail\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Obelaw\Twist\Base\BaseModel;

class Trail extends BaseModel
{
    protected $fillable = [
        'trailable_type',
        'trailable_id',
        'causer_type',
        'causer_id',
        'event',
        'changes',
        'snapshot',
        'ip_address',
        'user_agent',
        'method',
        'url',
        'guard',
        'request_id',
        // tenant column may be added dynamically; allow mass-assignment via guarded=false
    ];

    protected $casts = [
        'changes'  => 'array',
        'snapshot' => 'array',
    ];

    protected $guarded = [];



    public function trailable(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeForTrailed($query, string $type, $id)
    {
        return $query->where('trailable_type', $type)->where('trailable_id', $id);
    }

    public function scopeForCauser($query, string $type, $id)
    {
        return $query->where('causer_type', $type)->where('causer_id', $id);
    }

    public function scopeEvent($query, string $event)
    {
        return $query->where('event', $event);
    }
}
