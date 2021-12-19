<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(){

        $post = Post::all();
        return response()->json($post,201);
    }
    public function store(StoreRequest $request){

        DB::beginTransaction();

        try {

            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content_post;
            $post->image = $request->image;
            $post->desc = $request->desc;
            $post->access_modifier= $request->access_modifier;
            $post->user_id = $request->user_id;
            $post->category_id = $request->category_id;
            $post->save();
            return response()->json('Đã thêm thành công bài post' );

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    }
    public function destroy($id)
    {

        $song = Post::findOrFail($id);
        $song->delete();
        $songs = Post::all();
        return response()->json($songs);
    }

    public function update (StoreRequest $request){

        DB::beginTransaction();

        try {

            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content_post;
            $post->image = $request->image;
            $post->desc = $request->desc;
            $post->access_modifier= $request->access_modifier;
            $post->user_id = $request->user_id;
            $post->category_id = $request->category_id;
            $post->save();
            return response()->json('Đã update thành công bài post' );

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function findPost($id)
    {
        $Post = Post::findOrFail($id);
        return response()->json($Post);
    }
    public function showPublic()
    {
        $post = Post::where('access_modifier', 0 )->with('user')->orderBy('id')->get();
        return response()->json($post);
    }
    public function showPublicWithAuthor()
    {
        $post = Post::where('created_at', 'desc')->limit(5)->get();
        return response()->json($post);
    }

    public function showDetailPost(Request $request)
    {
        $post = Post::findOrFail($request->id);
        $user = User::where('id',$post->user_id)->get();
        $data = [
          'post' => $post,
          'user' => $user
        ];
        return response()->json($data);
    }
}
