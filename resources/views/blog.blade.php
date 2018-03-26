@extends('templates.layout')
@section('title', 'Blog')

<h1>Blog</h1>

@section('content')


    @component('components.card',
        [
        'img_title' => 'Prima Immagine del mio blog',
        'img_url' => 'http://lorempixel.com/g/400/200/'
        ]
        )
            <p>questa è una descrizione della prima foto</p>

    @endcomponent

    @component('components.card')
        @slot('img_url', 'http://lorempixel.com/g/400/200/')
        @slot('img_title', 'Seconda immagine del mio blog')

        <p>questa è una descrizione della seconda foto</p>

    @endcomponent
@endsection

{{--PRIMO MODO DI UTILIZZO DI INCLUDE con i dati globali passati dal controller --}}
@include('components.card')

{{--SECONDO MODO DI UTILIZZO DI INCLUDE --}}
{{--@include('components.card',
    [
    'img_title' => 'Prima Immagine del mio blog',
    'img_url' => 'http://lorempixel.com/g/400/200/'
    ]
    )--}}

@section('footer')
    @parent

@endsection