# Prompt for Copilot – Obelaw Trail

I am building a Laravel package named **Obelaw Trail**.  
This package provides an **activity trail / audit log** system for Eloquent models.  
I want Copilot to help me generate and complete code files for this package.

## Requirements for Copilot:

- Always use the namespace `Obelaw\Trail\...` for all classes.
- The package must contain:
  - `TrailServiceProvider` that publishes config and migrations.
  - A `Trail` model to store activity records.
  - A `TracksTrail` trait to attach to Eloquent models.
  - An `Observer` that records create, update, delete, restore, forceDelete events.
  - Middleware to capture request context (IP, URL, method, user agent).
  - An `Event` (`TrailRecorded`) fired when a new record is saved.
  - An Artisan command `trail:prune` to delete old records.
  - Config file `trail.php` to control options (ignored fields, redacted fields, queue, broadcast, retention days).
- Use `trail_causer()` helper to get the current authenticated user.
- Respect Laravel 10/11 standards.
- Prefer `dispatch()` for queued jobs when `queue=true`.
- Always mask `password`, `token`, `secret` in logs.

## Example Copilot task

- When I create a new file `src/Observers/TrailObserver.php`, suggest the full class that listens to model events and saves a `Trail` record.
- When I add the `TracksTrail` trait to a model, Copilot should suggest the correct boot method.
- When I type queries with `Trail::query()`, suggest filters by `trailable_type` and `trailable_id`.
- When writing migrations, Copilot should add JSON fields (`changes`, `snapshot`) and polymorphic relations (`trailable`, `causer`).

## Goal

Help me quickly scaffold and extend the **Obelaw Trail** package with correct Laravel conventions and consistent naming.
