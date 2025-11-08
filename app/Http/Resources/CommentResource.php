<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at->diffForHumans(),
            'author' => new UserResource($this->whenLoaded('user')),
            'commentable_type' => $this->commentable_type,
            'commentable_id' => $this->commentable_id,
            'commentable' => $this->whenLoaded('commentable'),
        ];
    }
}
