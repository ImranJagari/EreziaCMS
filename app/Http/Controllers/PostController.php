<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Cache;

use App\Post;
use App\Comment;

class PostController extends Controller {

	const CACHE_EXPIRE_MINUTES = 10;
	const POSTS_PER_PAGE = 6;
	const COMMENTS_PER_PAGE = 10;

	public function index()
	{
		if (Cache::has('posts'))
		{
			$posts = Cache::get('posts');
		}
		else
		{
			$posts = Post::orderBy('date', 'desc')->paginate(self::POSTS_PER_PAGE);
			Cache::add('posts', $posts, self::CACHE_EXPIRE_MINUTES);
		}

		return view('posts.index', compact('posts'));
	}

	public function show($id, $slug)
	{
		if (Cache::has("posts.$id"))
		{
			$post = Cache::get("posts.$id");
		}
		else
		{
			$post = Post::findOrFail($id);
			Cache::add("posts.$id", $post, self::CACHE_EXPIRE_MINUTES);
		}

		if (Cache::has("posts.$id.comments"))
		{
			$comments = Cache::get("posts.$id.comments");
		}
		else
		{
			$comments = Comment::where('post_id', $id)->orderBy('date', 'desc')->paginate(self::COMMENTS_PER_PAGE);
			Cache::add("posts.$id.comments", $comments, self::CACHE_EXPIRE_MINUTES);
		}

		return view('posts.show', compact('post', 'comments'));
	}

}
