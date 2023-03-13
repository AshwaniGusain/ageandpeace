<?php

namespace App\Http\Controllers;

use App\Mail\InviteCreated;
use App\Models\Category;
use App\Models\Invite;
use Illuminate\Http\Request;
use App\Models\Post;

class NewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    public function index(Request $request)
    {
        $categoryId = $request->input('category');
        $keyword = $request->input('keyword');

        $category = Category::find($categoryId);
        $categories = Post::categories()->get()->pluck('name', 'id');
        $paginationNum = 9;

        $query = Post::orderBy('publish_date', 'desc');

        if ($category || $keyword) {
            if ($category) {
                $query->where('category_id', $categoryId);
            }

            if ($keyword) {
                $query->where('title', 'like',
                    '%'.$keyword.'%')->where('body', 'like', '%'.$keyword.'%'
                );
            }

        } elseif (empty($request->input('page')) || (!empty($request->input('page')) && $request->input('page') == 1)) {
            $featured = Post::featured()->orderBy('publish_date', 'desc')->first();
            $query->where('id', '!=', $featured->id);
        }

        $posts = $query->paginate($paginationNum);

        return view('news.index', compact('posts', 'categories', 'featured', 'category', 'keyword'));
    }

    public function detail(Post $post)
    {
        $related = $post->relatedPostsAttribute(5);

        return view('news.detail', compact('post', 'related'));
    }
}
