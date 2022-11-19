<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostsResource;
use App\Models\Post;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Scalar\String_;

class PostsController extends Controller
{
    use HttpResponses;

    public function index() : AnonymousResourceCollection
    {
        $user = auth('sanctum')->user();
//        return response()->json(["user"=>$user]);
        return PostsResource::collection(
            Post::where('user_id', $user->id)->orderBy('pinned', 'desc')->get()
        );
    }

    public function store(PostStoreRequest $request) : PostsResource
    {
        $request->validated($request->all());

        $data = $request->all();

        $user = auth('sanctum')->user();

        $data['user_id'] = $user->id;
//        $validatedData['image'] = $request->file('image')->store('posts');
        $data['pinned'] = $request->pinned ?? false;
        $post = Post::create($data);

        if($request->has('tags')){
            $post->tags()->attach($request->tags);
        }

        return new PostsResource($post);
    }

    public function show(Post $post) : string | PostsResource
    {
        return $this->isNotAuthorized($post) ? $this->isNotAuthorized($post) : new PostsResource($post);
    }

    public function update(Request $request, Post $post) : PostsResource | string
    {
//        $data = $request->all();
//        if($request->hasFile('image')){
//            Storage::delete($post->image);
//            $data['image'] = $request->file('image')->store('posts');
//        }
        $post->update($request->all());
        if($request->has('tags')){
            $post->tags()->sync($request->tags);
        }
        return new PostsResource($post);
    }

    public function destroy(Post $post)
    {
        return $this->isNotAuthorized($post) ? $this->isNotAuthorized($post) : $post->delete();
    }

    public function restore($id) : string
    {
        $isRestored = Post::withTrashed()->find($id)->restore();
        if($isRestored){
            return $this->success("", 200, "Restored successfully");
        }else{
            return $this->error("", 404, "Couldn't restore the post");
        }
    }

    public function trashed() : string
    {
        $user_id = $user = auth('sanctum')->user()->id;
        $trashedPosts = Post::onlyTrashed()->where('user_id', $user_id)->get();
        return $this->success(["trashed"=>$trashedPosts], 200);

    }

    private function isNotAuthorized($post) : string | false{
        if($post->user_id !== auth('sanctum')->user()->id){
            return $this->error('', 403, "You are not authorized to make this request");
        }
        else{
            return false;
        }
    }
}
