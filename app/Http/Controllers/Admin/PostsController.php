<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPostsStoreRequest;
use App\Http\Requests\AdminPostsUpdateRequest;
use App\Post;
use App\Tag;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view("admin.posts.index", ["posts" => $posts]);
    }

    public function create()
    {
        $categories = Category::pluck('title', 'id')->all();
        $tags = Tag::pluck('title', 'id')->all();

        return view("admin.posts.create", compact("categories", "tags"));
    }

    public function store(AdminPostsStoreRequest $request)
    {
        $post = Post::add($request->all());
        $post->uploadImage($request->file("image"));
        $post->setCategory($request->get("category_id"));
        $post->setTags($request->get("tags"));
        $post->toggleStatus($request->get("status"));
        $post->toggleFeatured($request->get("is_featured"));

        return redirect()->route("posts.index");
    }

    public function edit($id)
    {
        $post = Post::find($id);
        $categories = Category::pluck('title', 'id')->all();
        $tags = Tag::pluck('title', 'id')->all();
        $selectedTags = $post->tags->pluck("id")->all();

        return view("admin.posts.edit", compact("post", "categories", "tags", "selectedTags"));
    }

    public function update(AdminPostsUpdateRequest $request, $id)
    {
        $post = Post::find($id);

        $post->edit($request->all());
        $post->uploadImage($request->file("image"));
        $post->setCategory($request->get("category_id"));
        $post->setTags($request->get("tags"));
        $post->toggleStatus($request->get("status"));
        $post->toggleFeatured($request->get("is_featured"));

        return redirect()->route("posts.index");
    }
}
