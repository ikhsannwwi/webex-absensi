<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilePembinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_pembina', function (Blueprint $table) {
            $table->id();
            $table->string('pembina_kode');
            $table->foreignId('kelas_id')->nullable();
            $table->foreignId('jurusan_id')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('sosial_media')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('foto')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_pembina');
    }
}
