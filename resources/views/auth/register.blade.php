@extends('layouts.master')

@section('content')

    <article id="register" class="grid container-md py-5 px-4" style="box-shadow: none;">
        
        <div>           
            <hgroup>    
                <h1>Register</h1>
                @if (session('menssage'))
                    <div style="color: green;">
                        {{ session('menssage') }}
                    </div>
                @endif
            </hgroup> 
            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <!-- First Name -->
                <input type="text" name="firstname" value="{{ old('firstname') }}" placeholder="Nome">
                @error('firstname')
                    <div style="color: red;">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Email -->
                <input type="text" name="email" value="{{ old('email') }}" placeholder="E-mail">
                @error('email')
                    <div style="color: red;">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Password -->
                <input type="password" name="password" placeholder="Senha">
                @error('password')
                    <div style="color: red;">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Password Confirmed -->
                <input type="password" name="password_confirmation" placeholder="Confirme sua Senha">
                @error('password_confirmation')
                    <div style="color: red;">
                        {{ $message }}
                    </div>
                @enderror


                <!-- Submit -->
                <button type="submit">Cadastrar</button>

                <hgroup>
                    <span class="d-block text-center">Já se cadastrou? 
                        <a href="{{ route('login.index') }}">Vá para o login</a></span>
                </hgroup>
            </form>
        </div>
        <div></div>
    </article>
@endsection



