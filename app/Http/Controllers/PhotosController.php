<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Photo;
use App\Policies\PhotoPolicy;
use Auth;
use function compact;
use function config;
use const false;
use Illuminate\Http\Request;
use Storage;
use function view;


class PhotosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Photo::class);
    }

    protected $rules = [
        'album_id' => 'required|exists:albums,id',
        'name' => 'required|unique:photos,name',
        'description' => 'required',
        'img_path' => 'required|image'
    ];

    protected $errorMessages = [
        'album_id.required' => 'Il campo Album è obbligatorio',
        'name.required' => 'Il campo Nome è obbligatorio',
        'description.required' => 'Il campo Descrizione è obbligatorio',
        'img_path.required' => 'Il campo Immagine è obbligatorio'
    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Photo::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $id = $req->has('album_id') ? $req->input('album_id') : null;
        $album = Album::firstOrNew(['id' => $id]);
        //dd($album);
        $photo = new Photo();
        $albums = $this->getAlbums();
        return view('images.editimage', compact('album','photo', 'albums'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->errorMessages);

        $photo = new Photo();
        $name = $request->input('name');
        $photo->name = request()->input('name');
        $photo->album_id = request()->input('album_id');
        $photo->description = request()->input('description');
        $photo->img_path = '';

        $res = $photo->save();

        if($res){
            if($this->processFile($photo, request())){
                $photo->save();
            }
        }

        $messaggio = $res ? 'foto '.$name.' creata' : 'foto '.$name.' non creato';
        session()->flash('message', $messaggio);
        return redirect()->route('album.getimages', $photo->album_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Photo $photo)
    {
        dd($photo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)           // MODEL BINDING ($photo si deve chiamare come nella rotta)
    {
        $albums = $this->getAlbums();
        $album = $photo->album;
        return view('images.editimage', compact('album','albums','photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
       $this->validate($request, $this->rules, $this->errorMessages);

        //dd($request->only('name','description'));
       $this->processFile($photo);
       $photo->album_id = $request->album_id;
       $photo->name = $request->input('name');
       $photo->description = $request->input('description');
       $res = $photo->save();

       $messaggio = $res ? 'Immagine '.$photo->name.' modificata' : 'Immagine '.$photo->name.' non modificata';
       session()->flash('message', $messaggio);
       return redirect()->route('album.getimages', $photo->album_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)    // MODEL BINDING ($photo si deve chiamare come nella rotta)
    {
        $res = $photo->delete();
        if ($res){
            $this->processFile($photo);
        }
        //return Photo::destroy($id);
        return ''.$res;
    }

    public function processFile(Photo $photo, Request $req = null)
    {
        if(!$req){
            $req = request();
        }
        if (!$req->hasFile('img_path')) {
            return false;
        }

        $file = $req->file('img_path');

        if(!$file->isValid()){
            return false;
        }

//      $filename = $file->store(env('ALBUM_THUMB_DIR'));
        $filename = $photo->id.'.'.$file->extension();
        $file->storeAs(env('IMG_DIR').'/'.$photo->album_id, $filename);
        $photo->img_path = env('IMG_DIR').'/'.$photo->album_id."/".$filename;
        return true;

    }

    public function deleteFile(Photo $photo)
    {
        $disk = config('filesystems.default');
        if($photo->img_path && Storage::disk($disk)->has($photo->img_path)){
           return Storage::disk($disk)->delete($photo->img_path);
        }
        return false;
    }

    public function getAlbums()
    {
        return Album::orderBy('album_name')->where('user_id', Auth::user()->id)
            ->get();
    }
}
