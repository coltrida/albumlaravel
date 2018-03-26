@extends('templates.layout')

{{--è il titolo del tab della pagina--}}
@section('title', $title)

@section('content')

    <h1>
        With Blade
        {{$title}}
    </h1>

    @if($staff)
        <ul>
        @foreach ($staff as $person)
            <li style="{{$loop->first ? 'color:red':''}}"> {{$loop->remaining}} {{$person['name']}} {{$person['lastname']}}</li>
        @endforeach
        </ul>
    @endif

@endsection
{{--
@section('footer')
    @parent
    <script>
        alert('footer');
    </script>
    @stop--}}
