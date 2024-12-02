@extends('layouts.master')

@section('content')
    <script>
        function todoList() {
            return {
                updateOrder: function(item, position) {
                    item = document.querySelector('[data-id="' + item +'"] .update-form');
                    currentOrder = item.querySelector('input[name=order]'); 

                    console.log(item);
        
                    currentOrder.value = position;
                    item.submit();
                }
                
            }
        }
    </script>

    
    <div class="grid">
        {{-- Side bar --}}    
        <div class="sidebar-main rounded-3 p-4" style="grid-column: span 1;">
            <div x-data="categoryList" >
               <aside>
                <nav>
                    <ul x-sort="updateOrder($item, $position )" >
                        <h4 class="fs-6 px-2">Categorias</h4>
                        <li class="mb-3 @if ( !isset($currentCategory->id) )current @endif rounded-2">
                            <a class="update-form text-decoration-none" href="{{ route('home') }}">
                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" value="Home" name="done" class="form-check-input" value="1">
                                    <label class="form-check-label text-dark" >Home</label>
                                </div>

                                @if ($totalTasks > 0) 
                                    <div class="counter-box d-flex align-items-center justify-content-center text-light-emphasis bg-body-tertiary">
                                        {{ $totalTasks }}
                                    </div>
                                @endif
                            </a>
                        </li>
                        @foreach ($categories as $category)                            
                        <li class="mb-3 @if (isset($currentCategory->id) && $category->id == $currentCategory->id)current @endif rounded-2">
                            <div class="update-form " href="{{ route('home.category',$category->id) }}">
                                <div class="form-check d-flex align-items-center">
                                    <a class=" text-decoration-none" href="{{ route('home.category',$category->id) }}">
                                    <input type="checkbox" value="{{ $category->name }}" name="Name" 
                                    class="form-check-input" value="1"> 
                                    <label class="form-check-label text-dark" >{{ $category->name }}</label>
                                    
                                 </a>
                                </div>

                                @if ($category->taskCount() > 0) 
                                    <div class="counter-box d-flex align-items-center justify-content-center text-light-emphasis bg-body-tertiary">{{ $category->taskCount() }}</div>
                                @else
                                    <div class="counter-box d-flex align-items-center justify-content-center text-light-emphasis bg-body-tertiary">
                                        <a class="border p-1 px-2 text-light-emphasis bg-body-tertiary rounded-5" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $category->id }}').submit();">
                                            <svg xmlns="http://www.w3.org/2000/svg" width=18 height=18 viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="post" id="delete-form-{{ $category->id }}" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>

                                @endif
                                    </div>
                        </li>
                
                        @endforeach
                    </ul>  
                    </nav>
               </aside>
                         
            </div>

            <form action="{{ @route('categories.store') }}" method="post" >
                @csrf
                <input type="text" class="rounded-2" name="name" placeholder="Digite aqui..."> 
            </form>
        </div>        
        
        <div style="grid-column: span 3;" class="px-5   "> 
           <section class="pt-4 pb-2">
                @if ($currentCategory)                        
                    <h3>{{ $currentCategory->name }}</h3>
                @else   
                    @if (Auth::check())
                        <h3>Olá, {{ Auth::user()->name }}!</h3>
                    @endif   
                @endif   
            </section>     

            <div x-data="todoList" >
                <ul  x-sort="updateOrder($item, $position )" class="p-0 list-group" >

                    @foreach ($tasks as $task)
                        
                        <li class="bg-white rounded-2 list-group-item list-group-item-action py-0" x-sort:handle role="group"   x-sort:item="{{ $task->id }}" data-id="{{ $task->id }}">  
                          
                            <form id="update-category-form-{{ $task->id }}" class="update-form d-flex align-items-center w-100" action="{{ route('tasks.update', $task) }}" method="post">
                                @csrf
                                @method('PUT')
                                
                                <!-- Checkbox para Done -->
                                <div>
                                    <input type="hidden" name="done" value="0"> <!-- Garantindo que o valor 0 seja enviado quando não marcado -->
                                    <input type="checkbox" name="done" class="form-check-input" value="1" {{ $task->done ? 'checked' : '' }} onchange="document.getElementById('update-category-form-{{ $task->id }}').submit();">
                                </div>
                                
                                <input type="hidden" value="{{ $task->order }}" name="order">
                                <input type="text" value="{{ $task->title }}" name="title" placeholder="Digite aqui..." class="border-0 bg-transparent auto-width" onblur="updateTask({{ $task->id }})">
                                
                                <!-- Select de Categoria -->
                                <select name="category_id" id="category_select" class="border-0 bg-body-secondary text-primary rounded-3 py-1 px-3 auto-width" onchange="updateTask({{ $task->id }})">
                                    <option value="">Categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $task->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <details class="dropdown mb-0">
                                    <summary class="square-summary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 128 512">
                                            <path d="M64 360a56 56 0 1 0 0 112 56 56 0 1 0 0-112zm0-160a56 56 0 1 0 0 112 56 56 0 1 0 0-112zM120 96A56 56 0 1 0 8 96a56 56 0 1 0 112 0z"/>
                                        </svg>
                                    </summary>
                                    <ul class="p-2">
                                        <li>
                                            <a href="{{ route('tasks.destroy', $task) }}" class="button btn--white px-3" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $task->id }}').submit();">Delete</a>
                                        </li>
                                    </ul>
                                </details>
                            </form>
                            
                            
                            <form action="{{ route('tasks.destroy', $task) }}" method="post" id="delete-form-{{ $task->id }}" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            
                            <script>
                                function updateTask(taskId) {
                                    document.getElementById('update-category-form-' + taskId).submit();
                                }
                            </script>
                            

                            <div></div>
                            
                                
                            <form id="delete-form" action="{{ route('tasks.destroy', $task) }}" method="post">
                                @csrf
                                @method('DELETE')
                            </form>   
                            
                        </li>
            
                    @endforeach
                </ul>
        
            </div>
        
            <form action="{{ @route('tasks.store') }}" method="post">
                @csrf
                <input type="text" name="title" placeholder="Digite aqui..." > 
                @isset($currentCategory)
                    <input type="hidden" name="category_id" value="{{ $currentCategory->id }}">
                @endisset
            </form>

        </div>
    </div>

    
    <style>
        nav .current {
            background: #f4f3f5;
        }

        details.dropdown summary::after {
            display: none;
        }      
        
        #category_select {
            -webkit-appearance: none; /* Safari e Chrome */
            -moz-appearance: none; /* Firefox */
            appearance: none; /* Padrão */
            background: none; /* Remover o fundo */
        }
        
        .auto-width {
            width: auto;
            max-width: 100%; /* Para evitar que os elementos fiquem maiores que o contêiner */
            display: inline-block; /* Garante que a largura automática seja aplicada corretamente */
        }

        /* Estilos adicionais para alinhamento e layout */
        .update-form {
            display: flex;
            align-items: center;
            gap: 10px; /* Espaçamento entre os elementos do formulário */
        }

        .auto-width::placeholder {
            color: #6c757d; /* Cor do placeholder */
        }

        .dropdown summary {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px !important;
            height: auto !important;
        }

        .dropdown ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .dropdown ul li {
            margin: 5px 0;
        }

        .button.btn--white {
            background-color: #fff;
            color: #000;
            border: 1px solid #ccc;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .counter-box {
            width: 24px; /* Ajuste o tamanho conforme necessário */
            height: 24px; /* Ajuste o tamanho conforme necessário */        
            border: 1px solid #dee2e6; /* Borda */
            border-radius: 50%; /* Forma de círculo, ajuste conforme necessário */
            text-align: center;
            font-weight: bold;
            margin-left: auto; /* Alinha o contador à direita */
            font-size: 0.8rem;
        }

    </style>

@endsection
