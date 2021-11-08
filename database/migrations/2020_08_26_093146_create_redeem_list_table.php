<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedeemListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redeem_list', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->double('requested_amount');  
            $table->string('status')->default('pending');
            $table->integer('transaction_ids')->nullable(); 
            $table->integer('product_id');   
            $table->string('serial_number');        
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
        Schema::dropIfExists('redeem_list');
    }
}
