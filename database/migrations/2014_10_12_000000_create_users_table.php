<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->string('name');
            $table->string('emails')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('owner')->default(0);
            $table->string('api_token', 80);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->string('name');
            $table->boolean('status')->default(1);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('app_id')->constrained('apps');
            $table->string('name');
            $table->string('url')->nullable();
            $table->text('curl')->nullable();
            $table->string('expected_status_code')->default(200);
            $table->string('frequency')->default("minutes");
            $table->integer('frequency_amount')->default(15);

            $table->boolean('active')->default(1);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('test_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('test_id')->constrained('tests');
            $table->string('status')->default("running"); //failed, passed
            $table->text('message')->nullable();
            $table->boolean('latest')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        //pivot table
        Schema::create('test_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('test_id')->constrained('tests');
            $table->foreignId('user_id')->constrained('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_users');
        Schema::dropIfExists('test_logs');
        Schema::dropIfExists('tests');
        Schema::dropIfExists('apps');
        Schema::dropIfExists('users');
        Schema::dropIfExists('accounts');
    }
}
