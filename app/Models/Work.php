<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;
    protected $table = 'works';
    protected $guarded = false;

    static $STATUS = ['OPEN', 'CLOSED', 'FINISHED'];
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'work_tags');
    }

    public function getStatusAttribute($value)
    {
        return self::$STATUS[$value];
    }
}
