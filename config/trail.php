<?php

return [

    // Which model events to capture
    'events' => ['created', 'updated', 'deleted', 'restored', 'forceDeleted'],

    // Attributes ignored when computing diffs
    'ignore_attributes' => ['updated_at', 'remember_token'],

    // Attributes to redact in logs
    'redact_attributes' => ['password', 'token', 'secret'],

    // Store full snapshot after the event
    'store_snapshot' => true,

    // Use queue for writing
    'queue' => false,

    // Broadcast (e.g., to websockets/webhooks)
    'broadcast' => false,

    // Retention policy in days
    'retention_days' => 365,

    // Watch specific models (optional). Otherwise use the TracksTrail trait on models.
    'watch' => [
        // App\Models\Order::class,
    ],

    // Tenant support removed
];
