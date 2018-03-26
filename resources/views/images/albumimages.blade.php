@extends('templates.layout')
@section('content')

    <h1>Images for {{$album->album_name}}</h1>
    @if(session()->has('message'))
        @component('components.alert-info')
            {{session()->get('message')}}
        @endcomponent
    @endif

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>CREATED DATE</th>
            <th>TITLE</th>
            <th>ALBUM</th>
            <th>THUMBNAIL</th>
        </tr>
        @forelse($images as $image)
            <tr>
                <td>{{$image->id}}</td>
                <td>{{$image->created_at->diffForHumans()}} <br> {{$image->created_at->format('d/m/Y')}} </td>
                <td>{{$image->name}}</td>
                <td><a href="{{route('album.edit', $image->album_id)}}"> {{$album->album_name}}</a></td>
                <td><img width="120" src="{{asset($image->img_path)}}"></td>
                <td>
                    <a title="Update Image" href="{{route('photos.edit', $image->id)}}" class="btn btn-primary">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                    <a title="Delete Image" href="{{route('photos.destroy', $image->id)}}" class="btn btn-danger">
                        <i class="fa fa-minus-square"></i>
                    </a>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="6">NO IMAGES FOUNT</td>
                </tr>
        @endforelse

        <tr>
            <td colspan="6" class="text-center">
                <div class="row">
                    <div class="col-md-8 push-2">
                        {{$images->links()}}
                    </div>
                </div>

            </td>
        </tr>

    </table>
@endsection

@section('footer')
    @parent
    <script>
        $('document').ready(function () {

            //$('div.alert').fadeOut(2000);

            $('table').on('click', 'a.btn-danger', function (ele) {
                ele.preventDefault();
                //alert(ele);               //QUESTO è L'ALERT DEL TARGET LINK
                var urlImg = $(this).attr('href');  //QUESTO è UN ALTRO MODO PER CATTURARE IL LINK (con jQuery)
                var tr = ele.target.parentNode.parentNode;
                //alert(tr);
                $.ajax(urlImg,
                    {
                        method: 'DELETE',
                        data : {
                            '_token' : '{{csrf_token()}}'
                        },
                        complete : function (resp) {
                            console.log(resp);        //COSì POSSIAMO VEDERE IL VALORE ( = 1) NELLA CONSOLE DEL BROWSER
                                //alert(resp.responseText);
                                tr.parentNode.removeChild(tr);        //QUESTO è CON JAVASCRIPT
                                // $(li).remove();                     //QUESTO è CON JQUERY

                        }
                    })
            })
        });
    </script>
@endsection