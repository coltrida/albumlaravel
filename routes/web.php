<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Events\NewAlbumCreated;
use App\Models\Album;
use App\Models\Photo;
use App\User;

Route::get('/', 'HomeController@index');

//  PRIMO MODO PER DARE UNA CONDIZIONE DI TIPO AI PARAMETRI PASSATI -----FUNZIONE ANONIMA------
//  Route::get('welcome/{name?}/{lastname?}/{age?}', function($name='', $lastname='', $age=0){
//  return '<h1>hello world'.$name.' '.$lastname.' you are '.$age.' old</h1>';
//  })
//  ->where('name', '[a-zA-Z]+'),
//  ->where('lastname', '[a-zA-Z]+'),
//  ->where('age', '[0-9]{0,3}')


//SECONDO MODO PER DARE UNA CONDIZIONE DI TIPO AI PARAMETRI PASSATI ---------AD UN CONTROLLER----------
Route::get('welcome/{name?}/{lastname?}/{age?}', 'WelcomeController@welcome')
    //return view('welcome');
->where([
    'name' => '[a-zA-Z]+',
    'lastname' => '[a-zA-Z]+',
    'age' => '[0-9]{0,3}'
]);

//  MODO PER VISUALIZZARE I DATI DEGLI ALBUMS IN ARRAY DI OGGETTI -----FUNZIONE ANONIMA------
/*Route::get('/albums', function(){
    return Album::all();
});*/

Route::group(
    [
        'middleware' => 'auth',
        'prefix' => 'dashboard'
    ],
    function(){
        Route::get('/', 'AlbumsController@index')->name('albums');
        //Route::get('/home', 'AlbumsController@index')->name('albums');
        Route::get('/albums/create', 'AlbumsController@create')->name('album.create');
        Route::get('/albums', 'AlbumsController@index')->name('albums');

        Route::get('/albums/{album}', 'AlbumsController@show')
            ->where('id', '[0-9]+');  //inseriamo una regular expression per il campo id
       //     ->middleware('can:view, album');

        Route::get('/albums/{id}/edit', 'AlbumsController@edit')
            ->name('album.edit')
            ->where('id', '[0-9]+');


        Route::patch('/albums/{id}', 'AlbumsController@store');
        Route::post('/albums', 'AlbumsController@save')->name('album.save');

        Route::get('/albums/{album}/images', 'AlbumsController@getImages')
            ->name('album.getimages')
            ->where('album', '[0-9]+');

        Route::delete('/albums/{album}', 'AlbumsController@delete')
            ->name('album.delete')
            ->where('album', '[0-9]+');

        Route::get('usersnoalbums', function(){
            $usersnoalbum = DB::table('users as u')
                ->leftJoin('albums as a', 'u.id', 'a.user_id')
                ->select('u.id','email','name', 'album_name')
                ->whereNull('album_name')
                ->get();
            return $usersnoalbum;
        });
        // IMAGES
        Route::resource('photos', 'PhotosController');
    });

//Route::get('/albums', 'AlbumsController@index')->name('albums');
// ->name('albums') è per dare un NOME alla rotta
//->middleware('auth') è per proteggerlo con l'autorizzazione

//Route::get('/albums/{id}/delete', 'AlbumsController@delete');     // PRIMO MODO PER FARE UNA ROTTA DI DELETE
//Route::delete('/albums/{album}', 'AlbumsController@delete')   // SECONDO MODO PER FARE UNA ROTTA DI DELETE
//    ->where('album', '[0-9]+');                             //PROTEGGIAMOLO DICENDO CHE E' NUMERICO

//Route::get('/albums/{id}', 'AlbumsController@show')->where('id', '[0-9]+');  //inseriamo una regular expression per il campo id
//Route::get('/albums/{id}/edit', 'AlbumsController@edit');
//Route::patch('/albums/{id}', 'AlbumsController@store');

//Route::post('/albums', 'AlbumsController@save')->name('album.save');
//Route::get('/albums/{album}/images', 'AlbumsController@getImages')->name('album.getimages')
//    ->where('album', '[0-9]+');



// IMAGES
//Route::resource('photos', 'PhotosController');

Route::get('/photos', function(){
    return Photo::all();
});

//USERS
Route::get('/users', function(){
    return User::all();
});
Auth::routes();

//Route::get('/home', 'AlbumsController@index');

//Rotta di prova per gli event e listener
Route::get('testEvent', function(){
    $album = Album::first();             // prendiamo il primo album
    event(new NewAlbumCreated($album));  // abbiamo un metodo globale chiamato event() che scatena il metodo handler su cui                                              lanciamo l'evento NewAlbumCreated che si aspetta un album
});