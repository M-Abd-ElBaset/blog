<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'attributes'=>[
                'title'=>$this->title,
                'body'=>$this->body,
                'image'=>$this->image,
                'pinned'=>$this->pinned,
                'created_at'=>$this->created_at,
                'updated_at'=>$this->updated_at,
            ]
        ];
    }
}
