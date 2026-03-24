<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD

            // Basic info
            $table->string('name');
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();

            // Verification & Status
            $table->timestamp('email_verified_at')->nullable();
            $table->string('otp')->nullable()->index(); // Uncommented for your OTP logic
            $table->boolean('is_verified')->default(false); // Added to match your controller checks
            $table->boolean('is_active')->default(true);

            // Auth
            $table->string('password');
            $table->rememberToken();

            // Role - Added 'instructor' to fix the "Data truncated" error
            $table->enum('role', ['student', 'admin', 'instructor'])->default('student');

            // Profile fields
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();

            $table->timestamps();
        });

        // Password reset tokens table
=======
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

<<<<<<< HEAD
        // Sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
=======
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<< HEAD
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
=======
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
