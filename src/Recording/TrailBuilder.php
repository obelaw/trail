<?php

namespace Obelaw\Trail\Recording;

use Illuminate\Database\Eloquent\Model;
use Obelaw\Trail\Models\Trail;
use Obelaw\Trail\Support\RequestContext;

class TrailBuilder
{
    protected ?Model $model = null;
    protected ?Model $causer = null;
    protected ?string $event = null;
    protected array $properties = [];
    protected array $context = [];

    protected array $changes = [];

    protected ?array $snapshot = null;

    protected ?string $guard = null;

    public static function make(): self
    {
        return new self();
    }

    public function for(?Model $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function by(?Model $user): self
    {
        $this->causer = $user;

        return $this;
    }

    public function event(string $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function changes(array $changes): self
    {
        $this->changes = $changes;

        return $this;
    }

    public function snapshot(?array $snapshot): self
    {
        $this->snapshot = $snapshot;

        return $this;
    }

    public function props(array $properties): self
    {
        $this->properties = $properties;

        return $this;
    }


    public function context(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function guard(?string $guard): self
    {
        $this->guard = $guard;

        return $this;
    }

    public function save(): Trail
    {
        $payload = [
            'event'       => $this->event ?? 'custom',
            'changes'    => $this->changes,
            'snapshot'   => $this->snapshot,
            'guard'      => $this->guard ?? (RequestContext::get('guard')),
            'ip_address' => RequestContext::get('ip_address'),
            'user_agent' => RequestContext::get('user_agent'),
            'url'        => RequestContext::get('url'),
            'method'     => RequestContext::get('method'),
            'request_id' => RequestContext::get('request_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($this->model) {
            $payload['trailable_type'] = $this->model->getMorphClass();
            $payload['trailable_id']   = $this->model->getKey();
        }

        if ($this->causer) {
            $payload['causer_type'] = $this->causer->getMorphClass();
            $payload['causer_id'] = $this->causer->getKey();
        }

        
        
        $logger = app('obelaw.trail.logger');
        return $logger->store($payload);
    }
}
