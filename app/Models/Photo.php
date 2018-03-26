<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use function strtoupper;

class Photo extends Model
{

    public function album()                // relazione: una foto appartiene ad un album
    {
//        $this->belongsTo(Album::class,'album_id', 'id');      // MODO ESTESO
        return $this->belongsTo(Album::class);      // MODO COMPATTO
    }

    public function getPathAttribute(){                // 1° MODO PER ESTRARRE L'ATTRIBUTO PATH
        $url = $this->attributes['img_path'];
        if(stristr($url, 'http')===false){
            $url = 'storage/'.$url;
        }
        return $url;
    }

    public function getImgPathAttribute($value){                // 2° MODO PER ESTRARRE L'ATTRIBUTO PATH
        if(stristr($value, 'http')===false){            // con il get...... modifica dopo
            $value = 'storage/'.$value;
        }
        return $value;
    }

    public function setNameAttribute($value)                // con il set...... modifica prima
    {
        $this->attributes['name'] = strtoupper($value);
    }
}
