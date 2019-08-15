<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class Measure extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        $data['id'] = $this->id;
        $data['height'] = $this->height;
        $data['volume'] = $this->volume;
        $data['container_id'] = $this->container_id;
        $createdAt = new Carbon($this->created_at);
        $data['created_at'] = $createdAt->toDateTimeString();
        $data['updated_at'] = $this->updated_at;
        return $data;
    }
}
