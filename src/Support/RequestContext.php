<?php

namespace Obelaw\Trail\Support;

use Illuminate\Http\Request;

class RequestContext
{
    protected static array $data = [];

    public static function capture(Request $request): void
    {
        static::$data = [
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 512),
            'url'        => substr($request->fullUrl() ?? '', 0, 1024),
            'method'     => $request->getMethod(),
            'guard'      => method_exists(app('auth'), 'getDefaultDriver') ? app('auth')->getDefaultDriver() : null,
            'request_id' => (string) ($request->headers->get('X-Request-Id') ?? \Str::uuid()),
        ];
    }

    public static function get(?string $key = null, $default = null)
    {
        return $key ? (static::$data[$key] ?? $default) : static::$data;
    }
}
