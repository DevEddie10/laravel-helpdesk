<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request  
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    
    public function toArray($request)
    {
        return [
            'statusCode' => 200,
            'data' => UserResource::collection($this->collection),
            'links' => [
                'selfs' => 'http://helpdesk.test/api/usuarios',
            ],
            'meta' => [
                'users_count' => $this->collection->count()
            ]
        ];
    }
}