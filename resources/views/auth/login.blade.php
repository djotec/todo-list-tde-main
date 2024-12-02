@extends('layouts.master')


@section('content')
    <style>
        :root{
            --block-spacing-vertical: calc(var(--spacing) * 2);
        }
    </style>

    <article class="grid container-md py-5 px-4" style="box-shadow: none;">
        <div>
                
            <hgroup>
                <h1>Login</h1>
            </hgroup>
            
            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                {{-- Email --}}
                <input type="text" class="form-control" name="email" placeholder="Login...">
                @error('email')
                    <div style="color: red;">
                        {{ $message }}
                    </div>
                @enderror

                {{-- Password --}}
                <input type="password" name="password" placeholder="Password">
                @error('password')
                    <div style="color: red;">
                        {{ $message }}
                    </div>
                @enderror


                <input class="contrast" name="submit" type="submit"  value="Login">
            </form>

            
            @error('error')
                <span>{{ $message }}</span>
            @enderror

            @if (session('menssage'))
                <div style="color: green;">
                    {{ session('menssage') }}
                </div>
            @endif
                    
            <div>
               <span class="text-center d-block"> Não tem uma conta? Cadastre-se 
                <a  href="{{ route('register.index') }}">Registrar-se</a></span>
            </div>
        </div>
        <div></div>
    </article>

@endsection
