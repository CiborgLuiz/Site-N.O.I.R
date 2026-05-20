<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_invite_keys', function (Blueprint $table) {
            $table->id();
            $table->string('code_hash')->unique();
            $table->string('role')->default('admin');
            $table->boolean('active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('admin_accounts')->nullOnDelete();
            $table->foreignId('used_by')->nullable()->constrained('admin_accounts')->nullOnDelete();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_invite_keys');
    }
};
