<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eskul_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('no_telepon');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->boolean('confirm');
            $table->string('kode');
            $table->foreignId('user_group_id');
            $table->uuid('uuid')->after('id');
            $table->integer('status');
            $table->timestamps();
            $table->SoftDeletes();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembina');
    }
}
