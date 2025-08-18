# Obelaw Trail

Activity trail/audit log for Eloquent models. Captures model changes and request context, supports optional queueing, and includes a prune command.

## Features

- Observes model events: created, updated, deleted, restored, forceDeleted
- Stores attribute changes and optional snapshot
- Captures request context: IP, user agent, method, URL, guard, request ID
- Masks sensitive fields by default: password, token, secret
- Optional queueing for non-blocking writes
- Artisan command to prune old records

## Installation

Publish config and migrations, then migrate:

```powershell
php artisan vendor:publish --tag=trail-config
php artisan vendor:publish --tag=trail-migrations
php artisan migrate
```

Middleware is auto-registered to the `web` and `api` groups to capture request context. An alias `trail.capture` is also available if you want to apply it manually.

## Configuration

`config/trail.php` keys:

- `connection`: database connection (null = default)
- `table`: table name (default: trails)
- `events`: model events to observe
- `ignore_attributes`: attributes to ignore when computing diffs (e.g. updated_at)
- `redact_attributes`: attributes to mask in logs (password, token, secret)
- `store_snapshot`: whether to store a full snapshot
- `queue`: write via queue job when true
- `broadcast`: fire TrailRecorded event after save
- `retention_days`: default prune retention
- `watch`: optional list of model classes to auto-observe

## Usage

Attach to models via trait or config:

- Use the `TracksTrail` trait on your Eloquent model; or
- Add model classes to `trail.watch` in config.

Manual logging via facade/builder:

```php
use Obelaw\Trail\Facades\Trail;

Trail::for($model)
	->event('custom')
	->changes(['name' => ['old' => 'A', 'new' => 'B']])
	->snapshot($model->toArray())
	->save();
```

Request context (IP, user agent, URL, method, guard, request ID) is captured automatically by the middleware.

## Querying

```php
use Obelaw\Trail\Models\Trail as TrailModel;

// All trails for a model
TrailModel::forTrailed($model->getMorphClass(), $model->getKey())->get();

// Filter by event
TrailModel::event('updated')->get();
```

## Pruning

Run manually with a custom retention:

```powershell
php artisan trail:prune --days=180
```

Or schedule daily using the value from `retention_days`:

```php
$schedule->command('trail:prune')->daily();
```
