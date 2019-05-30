<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAccessKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('user_access_key', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
        */
        Schema::table('users', function (Blueprint $table) {
            $table->longtext('access_key', 15)->nullable();    
        });
        $user = new \Illuminate\Foundation\Auth\User();
        $user->name = "admin";
        $user->email = "admin";
        $user->password = "admin";
        $user->access_key = "25e384169d5e6e4b359747ef7e8932b7b38210ae87ed33c017891e07f610127b";
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
        Schema::dropIfExists('user_access_key');
        */
    }
}
