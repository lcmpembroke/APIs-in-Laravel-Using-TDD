<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        // don't want updated_at field
        return [
            'id' => $this->id,
            'name' => $this->name,            
            'slug' => $this->slug,
            'price' => (int)$this->price,
            'created_at' => $this->created_at,
        ];
    }
}
