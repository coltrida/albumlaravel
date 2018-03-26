<?php

namespace App\Policies;

use App\User;
use App\Models\Photo;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhotoPolicy
{
    use HandlesAuthorization;


    public function view(User $user, Photo $photo)
    {
        return $user->id === $photo->album->user_id;
    }


    public function create(User $user)
    {
        return 1;
    }


    public function update(User $user, Photo $photo)
    {
        return $user->id === $photo->album->user_id;
    }

    public function edit(User $user, Photo $photo)
    {
        return $user->id === $photo->album->user_id;
    }

    public function delete(User $user, Photo $photo)
    {
        return $user->id === $photo->album->user_id;
    }
}
