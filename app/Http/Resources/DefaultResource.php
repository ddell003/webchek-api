<?php


namespace App\Http\Resources;


class DefaultResource extends Resource
{
    public function toArray($request)
    {
        $entry = parent::toArray($request);
        return $entry;
    }
}
