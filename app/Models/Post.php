<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array'
    ];

    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id', 'id');
    }
}
