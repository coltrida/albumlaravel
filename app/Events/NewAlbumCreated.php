<?php

namespace App\Events;

use App\Models\Album;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewAlbumCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $album;         // voglio passare al costruttore l'album che è stato creato
    /**@var $album
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Album $album)   //quando instanziamo questa classe, gli passiamo l'album
    {
        $this->album = $album;                  //lo salviamo nell'attributo pubblico. Questo server per far passare ai
    }                                           //listeners l'album e tutti i suoi attributi

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
