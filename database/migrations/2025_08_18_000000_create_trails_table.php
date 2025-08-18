<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Obelaw\Twist\Base\BaseMigration;

return new class extends BaseMigration
{
    public function up(): void
    {
        Schema::create($this->prefix . 'trails', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('causer');
            $table->morphs('trailable');
            $table->string('event');
            $table->json('changes')->nullable();
            $table->json('snapshot')->nullable();
            // request context
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->string('method', 16)->nullable();
            $table->string('url', 1024)->nullable();
            $table->string('guard', 64)->nullable();
            $table->uuid('request_id')->nullable();

            // tenant support removed
            $table->timestamps();
            $table->index(['trailable_type', 'trailable_id'], 'trailable_index');
            $table->index(['causer_type', 'causer_id'], 'causer_index');
            $table->index('request_id', 'request_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'trails');
    }
};
