@extends('layouts.auth')
@section('title', 'Задать новый пароль')
@section('content')
    <x-forms.auth-forms title="Задать новый пароль" action="{{route('reset.handle')}}"  method="POST">

        <input type="hidden" value="{{$token}}" name="token">
        <x-forms.text-input 
            type="email" 
            name="email" 
            placeholder="Электронная почта" 
            value="{{request('email')}}"
            riquired="true" 
            :isError="$errors->has('email')" />
        @error('email')
            <x-forms.error :message="$message" />
        @enderror

        <x-forms.text-input 
            type="password" 
            name="password" 
            placeholder="Пароль" 
            riquired="true" 
            :isError="$errors->has('password')" />
        @error('password')
            <x-forms.error :message="$message" />
        @enderror
        
        <x-forms.text-input 
            type="password" 
            name="password_confirmation" 
            placeholder="Повторите пароль" 
            riquired="true" 
            :isError="$errors->has('password_confirmation')" />
        @error('password_confirmation')
            <x-forms.error :message="$message" />
        @enderror

        <x-forms.primary-button>Сохранить</x-forms.primary-button>

    </x-forms.auth-forms>


@endsection