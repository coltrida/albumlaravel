<?php

namespace App\Listeners;

use App\Events\NewAlbumCreated;
use App\User;
use function dd;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAdminNewAlbum
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewAlbumCreated  $event
     * @return void
     */
    public function handle(NewAlbumCreated $event)
    {
        $admins = User::where('name', 'admin')->get();    // mi prendo tutti gli utenti con nome = admin
        /*foreach ($admins as $admin){
            $admin->email;
        }*/
        dd($event->album->album_name);
    }
}
