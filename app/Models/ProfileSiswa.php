<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileSiswa extends Model
{
    use HasFactory;

    protected $table = 'profile_siswa';

    protected $guarded = ['id'];
}
