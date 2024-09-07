<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderByDesc('created_at')
            ->get()
            ->reverse()
            ->values();
        
        return response()->json([
            'post' => $posts
        ]);
    }
}