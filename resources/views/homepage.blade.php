@extends('layouts.app')

@section('content')
    @auth
    <x-forms.auth-forms title="Выйти" action="{{route('logout')}}"  method="post">
        @method('DELETE')
        <button type="submit" class="w-full btn btn-pink">Выйти</button>
    </x-forms.auth-forms>
    @endauth

    @guest
        <a href="{{route('login')}}" class="btn btn-pink">Войти</a>
    @endguest

@endsection