<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('service'); // identity, memo, settings
            $table->string('action'); // login, register, reset_password, memo_created, etc.
            $table->string('type')->default('email'); // email, sms, push (future)
            $table->string('subject');
            $table->text('body');
            $table->json('placeholders')->nullable(); // available placeholders
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['service', 'action', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};