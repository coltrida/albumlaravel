<?php

namespace App\Policies;

use App\User;
use App\Models\Album;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlbumPolicy
{
    use HandlesAuthorization;


    public function view(User $user, Album $album)
    {
        dd($album);
        return $user->id === $album->user_id;
    }


    public function create(User $user)
    {
        return 1;
    }


    public function update(User $user, Album $album)
    {
        return $user->id === $album->user_id;
    }

    public function edit(User $user, Album $album)
    {

        return $user->id === $album->user_id;
    }

    public function delete(User $user, Album $album)
    {
        return $user->id === $album->user_id;
    }
}
