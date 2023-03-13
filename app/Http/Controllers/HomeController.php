<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


    public function index()
    {
        $featuredPosts = Post::featured()->with('category')->orderBy('precedence', 'asc')->orderBy('updated_at', 'desc')->take(3)->get();
        $recentPosts = Post::notFeatured()->with('category')->orderBy('precedence', 'asc')->orderBy('updated_at', 'desc')->take(3)->get();

        return view('home', ['featuredPosts' => $featuredPosts, 'recentPosts' => $recentPosts]);
    }
}
