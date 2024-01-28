<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePembina extends Model
{
    use HasFactory;

    protected $table = 'profile_pembina';

    protected $guarded = ['id'];
}
