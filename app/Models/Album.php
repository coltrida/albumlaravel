<?php

namespace App\Models;

use App\User;
use const false;
use Illuminate\Database\Eloquent\Model;
use function stristr;
use App\Models\Photo;

class Album extends Model{

//    protected $table = 'Album';       //NEL CASO IL NOME DELLA CLASSE NON COINCIDA CON IL NOME DELLA TABELLA NEL DB
//    protected $primaryKey = 'album_id';  //NEL CASO LA CHIAVE PRIMARIA NON FOSSE ID DOBBIAMO SPECIFICARLA

    protected $fillable = ['album_name', 'description', 'user_id'];

    public function getPathAttribute(){
        $url = $this->album_thumb;
        if(stristr($this->album_thumb, 'http')===false){
            $url = 'storage/'.$this->album_thumb;
        }
        return $url;
    }

    public function photos()        //QUI DOBBIAMO DIRE QUAL è LA RELAZIONE FRA ALBUM E FOTO
    {
        return $this->hasMany(Photo::class, 'album_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}