<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PostcodeResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'pcd'  => $this->pcd,
          'lat'  => $this->lat,
          'long' => $this->long,
        ];
    }
}
