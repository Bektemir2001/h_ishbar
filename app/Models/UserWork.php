<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWork extends Model
{
    use HasFactory;
    protected $table = 'user_works';
    protected $guarded = false;
}
