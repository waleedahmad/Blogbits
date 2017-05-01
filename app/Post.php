<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = ['caption', 'file_name', 'uri', 'tags', 'type'];
}
