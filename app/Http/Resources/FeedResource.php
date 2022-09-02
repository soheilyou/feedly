<?php

namespace App\Http\Resources;

use App\Repositories\Feed\FeedRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class FeedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = Auth::user();
        return [
            "id" => $this->id,
            "name" => $this->name,
            "url" => $this->url,
            "image" => $this->image,
            "unread_items_count" => FeedRepository::getUnReadItemsCount(
                $user->id,
                $this->id
            ),
        ];
    }
}
