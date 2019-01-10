<?php

namespace App\Resources;

class UserResource extends AbstractResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
/*    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email, // короткая форма чтобы не писать ()->get()
            'hasVerifiedEmail' => $this->hasVerifiedEmail(),
            'createdAt' => (string) $this->created_at,
            'updatedAt' => (string) $this->updated_at,
        ];
    }*/
}
