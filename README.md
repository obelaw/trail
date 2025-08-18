# Obelaw Trail

Minimal activity trail for Eloquent models, with request context, optional queueing, tenant support, and a prune command.

## Features

- Observe model events (created, updated, deleted, restored, forceDeleted)
- Stores attribute changes and optional snapshot
- Captures request context: ip, user agent, method, url, guard, request id
- Optional tenant column and resolver
- Queue support via `StoreTrail` job
- `trail:prune` command to purge old records

## Install

- Publish config: php artisan vendor:publish --tag=trail-config
- Publish migrations: php artisan vendor:publish --tag=trail-migrations
- Run migrations.

## Usage

- Add `TracksTrail` trait to models, or list models in `config/trail.php` under `watch`.
- Optionally set `TRAIL_TENANT_COLUMN` and a resolver.

## Prune

- php artisan trail:prune --days=180
