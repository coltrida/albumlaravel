@extends('templates.layout')
@section('content')


@include('partials.inputerrors')

    @if($photo->id)  {{--siamo in modifica--}}
    <h1>Edit Image</h1>
            <form action="{{route('photos.update', $photo->id)}}" method="POST" enctype="multipart/form-data">

                {{method_field('PATCH')}}
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" required name="name" id="name" class="form-control" value="{{$photo->name}}"
                           placeholder="Image Name">
                </div>

                <div class="form-group">
                    <select name="album_id" id="album_id">
                        <option value="">SELECT</option>
                        @foreach($albums as $item)
                            <option {{$item->id==$album->id?'selected':''}}
                                    value="{{$item->id}}">{{$item->album_name}}</option>
                        @endforeach
                    </select>
                </div>


                {{csrf_field()}}
                @include('images.partials.fileupload')

                <div class="form-group">
                    <label for="">Description</label>
                    <textarea required  name="description" id="description" class="form-control"
                               placeholder="Description">{{$photo->description}}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
    @else    {{--siamo in creazione--}}
    <h1>New Image</h1>
            <form action="{{route('photos.store')}}" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" required name="name" id="name" class="form-control" value="{{$photo->name}}"
                           placeholder="Image Name">
                </div>

                <div class="form-group">
                    <select name="album_id" id="album_id">
                        <option value="">SELECT</option>
                        @foreach($albums as $item)
                            <option {{$item->id==$album->id?'selected':''}}
                                    value="{{$item->id}}">{{$item->album_name}}</option>
                        @endforeach
                    </select>
                </div>

                {{--<input type="hidden" name="album_id" value="{{$album->id}}">--}}
                {{csrf_field()}}
                @include('images.partials.fileupload')

                <div class="form-group">
                    <label for="">Description</label>
                    <textarea required name="description" id="description" class="form-control"
                               placeholder="Description">{{$photo->description}}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

    @endif

@stop