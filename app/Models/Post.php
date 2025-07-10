<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
 
    use HasUuids;
    protected $fillable = [
        'title',
        'thumbnail',
        'category',
        'service_description',
        'content',
        'author',
        'date',
        'post_publish',
    ];
}
