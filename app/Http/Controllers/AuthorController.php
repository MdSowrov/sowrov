<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function profile($user_name)
    {
        $author = User::where('user_name', $user_name)->first();
        $posts = $author->posts()->approved()->published()->get();
        return view('profile', compact('author', 'posts'));
    }
}
