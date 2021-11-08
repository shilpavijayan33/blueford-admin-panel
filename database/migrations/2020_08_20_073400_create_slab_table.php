<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slab', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->double('start')->nullable();
            $table->double('end')->nullable();  
            $table->double('value');              
            $table->string('type')->default('dealer');                  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slab');
    }
}
