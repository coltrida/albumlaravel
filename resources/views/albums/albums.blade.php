@extends('templates.layout')

@section('content')
    <h1>Albums</h1>
    @if(session()->has('message'))
        @component('components.alert-info')
            {{session()->get('message')}}
        @endcomponent
    @endif
    <form>
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    <table class="table-stripped" width="100%">
        <thead>
        <tr>
            <th>Album name</th>
            <th>Thumb</th>
            <th>Creator</th>
            <th>Created Date</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        @foreach($albums as $album)
            <tr>
                <td>({{$album->id}}) {{$album->album_name}} - {{$album->photos_count}} pictures</td>
                <td>
                    @if($album->album_thumb)
                        <img width="100"  src="{{asset($album->path)}}" title="{{$album->album_name}}" alt="{{$album->album_name}}">
                    @endif
                </td>
                <td>{{$album->user->fullName}}</td>
                <td>{{$album->created_at->diffForHumans()}} <br> {{$album->created_at->format('d/m/Y')}}</td>
                <td>
                    @if($album->photos_count)
                        <a title="View Images" href="{{route('album.getimages', $album->id)}}" class="btn btn-default" style="float: right">
                            <i class="fa fa-search"></i>
                        </a>
                    @endif
                    <a title="Add picture" href="{{route('photos.create')}}?album_id={{$album->id}}" class="btn btn-success" style="float: right">
                        <i class="fa fa-plus"></i>
                    </a>
                    <a title="Update Album" href="{{route('album.edit', $album->id)}}" class="btn btn-primary" style="float: right">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                    <a title="Delete Album" href="{{route('album.delete', $album->id)}}" class="btn btn-danger" style="float: right">
                        <i class="fa fa-minus-square"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        <tr>
            <td class="row" colspan="4">
                <div>
                    {{$albums->links()}}
                </div>
            </td>
        </tr>

    </table>
    </form>
@endsection

@section('footer')
    @parent
    <script>
        $('document').ready(function () {

            $('div.alert').fadeOut(2000);

            $('ul').on('click', 'a.btn-danger', function (ele) {  //è consigliato mettere il listener su ul e non sui li
                ele.preventDefault();
                //alert(ele);
                // ele.target.href = $(this).attr('href')
                //alert(ele.target.href);               //QUESTO è L'ALERT DEL TARGET LINK
                var urlAlbum = ($(this).attr('href'));  //QUESTO è UN ALTRO MODO PER CATTURARE IL LINK (con jQuery)
                var li = ele.target.parentNode;
               // alert(li);
                $.ajax(urlAlbum,
                    {
                        method: 'DELETE',
                        data : {
                          '_token' : $('#_token').val()
                        },
                        complete : function (resp) {
                            console.log(resp);        //COSì POSSIAMO VEDERE IL VALORE ( = 1) NELLA CONSOLE DEL BROWSER
                            if(resp.responseText == 1){
                                //alert(resp.responseText);
                                li.parentNode.removeChild(li);        //QUESTO è CON JAVASCRIPT
                               // $(li).remove();                     //QUESTO è CON JQUERY
                            }else{
                                alert('problemi');
                            }
                        }
                    })
            })
        });
    </script>
@endsection