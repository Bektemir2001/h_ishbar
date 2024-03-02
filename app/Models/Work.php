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

    protected $with = ['tags', 'category'];
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'work_tags')->select('tags.id', 'tags.name');
    }

    public function category()
    {
        return $this->belongsTo(WorkCategory::class)->select('id', 'name');
    }
    public function getStatusAttribute($value)
    {
        return self::$STATUS[$value];
    }
}
