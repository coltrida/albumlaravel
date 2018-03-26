<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;      //avendo creato il controller con Artisan, ha messo direttamente questa use per la Request

class welcomeController extends Controller   //avendo creato il controller con Artisan, estende il Controller base
{
    public function welcome($name = '', $lastname = '', $age = 0, Request $req){
        $lingua = $req->input('lang');
        $res = "<h1>Ciao caro $name $lastname you are $age old. Your language is $lingua</h1>";
        return $res;
    }
}
