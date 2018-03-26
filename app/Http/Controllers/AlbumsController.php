<?php

namespace App\Http\Controllers;

use function abort;
use App\Http\Requests\AlbumRequest;
use App\Http\Requests\AlbumUpdateRequest;
use App\Models\Album;
use App\Models\Photo;
use Auth;
use function compact;
use function config;
use function dd;
use function env;
use const false;
use Gate;
use function get_magic_quotes_gpc;
use Illuminate\Http\Request;
use DB;
use function redirect;
use function session;
use Storage;
use const true;
use function view;


class AlbumsController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth')->except(['index']);
    }


    public function index(Request $request)    //suggeriamo che al metodo venga iniettata la request, l'url
    {
        //return Album::all();    //PER VISUALIZZARE I DATI IN FORMA DI ARRAY DI OGGETTI

//      $sql = 'select * from albums'; //PER VISUALIZZARE I DATI IN FORMA DI ARRAY DI OGGETTI
//      return DB::select($sql);

//      $queryBuilder = DB::table('albums')->orderBy('id', 'DESC');  //con i Model questa facade DB non ci serve più
        $queryBuilder = Album::orderBy('id', 'DESC')->withCount('photos');
        //dd($request->user()->name);
        $queryBuilder->where('user_id', Auth::user()->id);

        if ($request->has('id')){
            $queryBuilder->where('id', '=', $request->input('id'));
        }
        if ($request->has('album_name')){
            $queryBuilder->where('album_name', 'like', '%'.$request->input('album_name').'%');
        }
        $albums = $queryBuilder->paginate(env('IMG_PER_PAGE'));
        return view('albums.albums', ['albums' => $albums]);




//      $sql = 'select * from albums WHERE 1=1';       //query grezza --------------------------------
/*      $where = [];
        if ($request->has('id')) {
            $where['id'] = $request->get('id');
            $sql .= " AND id=:id";
        }
        if ($request->has('album_name')) {
            $where['album_name'] = $request->get('album_name');
            $sql .= " AND album_name=:album_name";
        }

        //$sql .= ' ORDER BY album_name';
        $sql .= ' ORDER BY id desc';

        //dd($sql);
        //return DB::select($sql, $where);              ------------------------------------------------





        $albums = DB::select($sql, $where);
        return view('albums.albums', ['albums' => $albums]);*/
    }

    public function delete(Album $album)                        //MODEL BINDING
    {
//        $res = DB::table('albums')->where('id', $id)->delete();  //con il Model questa facede DB non ci serve più
//        $res = Album::where('id', $id)->delete();           // 1° MODALITA' --------------------

//        $res = Album::find($id)->delete();                   // 2° MODALITA' --------------------

        //$album = Album::findOrFail($id);

        $thumbNail = $album->album_thumb;
        $disk = config('filesystems.default');
        $res = $album->delete();
        if($res){
            if($thumbNail && Storage::disk($disk)->has($thumbNail)){
                Storage::disk($disk)->delete($thumbNail);
            }
        }
        return ''.$res;                     //dobbiamo fare il cast perchè devo tornare una stringa e non un boolean
        //$sql = 'DELETE FROM albums where id= :id';
        //return DB::delete($sql, ['id' => $id] );
        //return DB::delete($sql, ['id' => $id]);
        // return redirect()->back();
        //dd($id);
    }

    /*public function show($id)
    {
        $sql = 'select * FROM albums where id= :id';
        //return DB::delete($sql, ['id' => $id] );
        return DB::select($sql, ['id' => $id]);
        // return redirect()->back();
        //dd($id);
    }*/

    public function show(Album $album)
    {
        echo "show";
        dd($album);
    }

    public function edit($id)
    {
        /*$sql = 'select id, album_name, description from albums where id =:id';   // 1° MODALITA'-----------
        $album = DB::select($sql,['id' => $id]);
        //DD($album[0]);
        return view('albums.editalbum')->with('album',$album[0]);*/               //------------------------

        $album = Album::find($id);
        //Auth::user()->can('update', $album);
        $this->authorize('edit', $album);

        /*if(Gate::denies('manage-album', $album)){
            abort(401, 'Non autorizzato');
        }*/

        /*if($album->user->id !== Auth::user()->id){
            abort(401, 'Non autorizzato');
        }*/
        return view('albums.editalbum')->with('album',$album);
    }

    public function store($id, AlbumUpdateRequest $req)
    {
//        $res = DB::table('albums')->where('id',$id)->update(     //non ci serve più la facade DB, usiamo i Model
/*        $res = Album::where('id',$id)->update(                  // 1° MODALITA' --------------------
            ['album_name' => request()->input('name'),
            'description' => request()->input('description')
            ]
        ); */                                                             //        -----------------



        $album = Album::find($id);                                // 2° MODALITA' --------------------

        $this->authorize('update', $album);

/*        if(Gate::denies('manage-album', $album)){
            abort(401, 'Non autorizzato');
        }*/

        $album->album_name = request()->input('name');
        $album->description = request()->input('description');
        $album->user_id = $req->user()->id;
        $this->processFile($id, $req, $album);
        $res = $album->save();

        //DD( request()->all());                           //USANDO L'HELPER request()
//        $data = request()->only('name','description');   //mi servono solo questi 2 valori dalla request
//        $data['id']=$id;                              //utilizzerò l'array data quindi ci metto anche l'id
//        //dd($data);
//        $sql='UPDATE albums SET album_name=:name, description=:description WHERE id=:id';
//        $res = DB::update($sql, $data);           //utilizziamo la facade DB passando la query e l'array dei valori
        $messaggio = $res ? 'Album con id = '.$id. ' Aggiornato' : 'Album '.$id.' non Aggiornato';
        session()->flash('message', $messaggio);
        return redirect()->route('albums');
    }

    public function create()
    {
        $album = new Album();
        return view('albums.createalbum', ['album' => $album]);
    }

    public function save(AlbumRequest $request)
    {
//        $res = DB::table('albums')->insert(           //non ci serve più la facade DB, usiamo i Model

/*        $res = Album::insert(                                            // 1° MODALITA' --------------------
            ['album_name' => request()->input('name'),
             'description' => request()->input('description'),
             'user_id' => 1
            ]
        );*/                                                                //        -----------------

/*        $res = Album::create(                                             // 2° MODALITA' -----------------
            ['album_name' => request()->input('name'),
                'description' => request()->input('description'),
                'user_id' => 1
            ]
        );*/                                                              //-----------------------------------

        $album = new Album();                                              // 3° MODALITA' ------------------
        $name = request()->input('name');
        $album->album_name = $request->input('name');
        $album->album_thumb = '';
        $album->description = $request->input('description');
        $album->user_id = $request->user()->id;

        $res = $album->save();                                              //-----------------------------------

//        $data = request()->only(['name', 'description']);
//        $data['user_id'] = 1;
//        $sql = 'INSERT INTO albums (album_name, description, user_id) VALUES (:name, :description, :user_id)';
//        $res = DB::insert($sql, $data);
        if($res){
            if($this->processFile($album->id, request(), $album)){
                $album->save();
            }
        }

        $messaggio = $res ? 'Album '.$name.' creato' : 'Album '.$name.' non creato';
        session()->flash('message', $messaggio);
        return redirect()->route('albums');
    }


    public function processFile($id, Request $req, &$album)
    {
        if (!$req->hasFile('album_thumb')) {
            return false;
        }

        $file = $req->file('album_thumb');

        if(!$file->isValid()){
            return false;
        }

//      $filename = $file->store(env('ALBUM_THUMB_DIR'));
        $filename = $id . '.' . $file->extension();
        $file->storeAs(env('ALBUM_THUMB_DIR'), $filename);
        $album->album_thumb = env('ALBUM_THUMB_DIR') . "/" . $filename;
        return true;

    }

    public function getImages(Album $album){                                //MODEL BINDING
 //       $images = Photo::where('album_id', $album->id)->get();        //PHOTO è IL MODEL LEGATO ALLE FOTO
        $images = Photo::orderBy('id', 'DESC')
            ->where('album_id', $album->id)
            ->paginate(env('IMG_PER_PAGE'));
        return view('images.albumimages', compact('album','images'));
    }



}
