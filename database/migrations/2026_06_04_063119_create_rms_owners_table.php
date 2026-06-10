<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rms_owners', function (Blueprint $table) {
            $table->id();

            $table->string('owner_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();

            $table->string('business_name');
            $table->string('business_type');
            $table->text('address')->nullable();

            $table->string('password');
            $table->string('pass');
            
            $table->string('status')->default('active');
            
            $table->string('is_subscribed')->default("false");
            $table->date('subscription_expiry')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rms_owners');
    }
};
