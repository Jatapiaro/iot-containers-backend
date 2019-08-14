<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Container extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['measures'] = $this->measures->take(60);
        return $data;
    }
}
