<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagStoreRequest;
use App\Http\Resources\TagsResource;
use App\Models\Tag;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TagsResource::collection(
            Tag::all()
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagStoreRequest $request)
    {
        $data = $request->validated($request->all());
        $tag = Tag::create($data);
        return new TagsResource($tag);
    }

    /**
     * Display the specified resource.
     *
     * @param  Tag  $tag
     * @return TagsResource
     */
    public function show(Tag $tag) : TagsResource
    {
        return new TagsResource($tag);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Tag $tag
     * @return TagsResource
     */
    public function update(Request $request, Tag $tag) : TagsResource
    {
        $tag->update($request->all());
        return new TagsResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tag $tag
     * @return string
     */
    public function destroy(Tag $tag): string
    {
        If($tag->posts()->exists()){
            $posts = $tag->posts()->get();
            foreach ($posts as $post){
                $post->tags()->detach($tag);
            }
        }
        return $tag->delete() ? $this->success('', 200, "Deleted Successfully") : $this->error("", 404, "Not Found");
    }
}
