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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username');
            $table->string('mobile')->unique();
            $table->boolean('admin')->default(0);
            $table->string('status')->default('active'); 
            $table->integer('sponsor')->default(1);           
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('user_type'); 
            // $table->integer('scan_count')->default(0);           
            // $table->integer('slab')->default(0);  
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
