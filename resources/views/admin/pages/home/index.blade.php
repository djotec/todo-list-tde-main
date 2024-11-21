@extends('master')

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
    <div x-data="todoList" >
        <ul  x-sort="updateOrder($item, $position )" >
            @foreach ($tasks as $task)
                
                <li x-sort:item="{{ $task->id }}" data-id="{{ $task->id }}">  
                    <span x-sort:handle> 
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8.5 7C9.32843 7 10 6.32843 10 5.5C10 4.67157 9.32843 4 8.5 4C7.67157 4 7 4.67157 7 5.5C7 6.32843 7.67157 7 8.5 7ZM8.5 13.5C9.32843 13.5 10 12.8284 10 12C10 11.1716 9.32843 10.5 8.5 10.5C7.67157 10.5 7 11.1716 7 12C7 12.8284 7.67157 13.5 8.5 13.5ZM10 18.5C10 19.3284 9.32843 20 8.5 20C7.67157 20 7 19.3284 7 18.5C7 17.6716 7.67157 17 8.5 17C9.32843 17 10 17.6716 10 18.5ZM15.5 7C16.3284 7 17 6.32843 17 5.5C17 4.67157 16.3284 4 15.5 4C14.6716 4 14 4.67157 14 5.5C14 6.32843 14.6716 7 15.5 7ZM17 12C17 12.8284 16.3284 13.5 15.5 13.5C14.6716 13.5 14 12.8284 14 12C14 11.1716 14.6716 10.5 15.5 10.5C16.3284 10.5 17 11.1716 17 12ZM15.5 20C16.3284 20 17 19.3284 17 18.5C17 17.6716 16.3284 17 15.5 17C14.6716 17 14 17.6716 14 18.5C14 19.3284 14.6716 20 15.5 20Z"></path></svg>
                         </span>     
                    <form class="update-form" action="{{ @route('tasks.update', $task) }}" method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" value="{{ $task->order }}" name="order">
                        <input type="text" value="{{ $task->title }}" name="title" placeholder="Digite aqui...">
                    </form>
    
                    <form action="{{ @route('tasks.destroy', $task) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM9 11H11V17H9V11ZM13 11H15V17H13V11ZM9 4V6H15V4H9Z"></path></svg>
                        </button>
                    </form>
    
                    
                </li>
    
            @endforeach
        </ul>

    </div>

    <form action="{{ @route('tasks.store') }}" method="post">
        @csrf
        <input type="text" name="title" placeholder="Digite aqui...">
    </form>

{{-- 
    <div class="card">
        <div class="card-header">
            <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
        @if (session()->has('menssage'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('menssage') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="container mt-4">
            <h2>Lista de Todas as Tarefas desta Categoria:</h2>
            @if ($tasks->isEmpty())
                <div class="alert alert-warning" role="alert">
                    Nenhuma tarefa encontrada.
                </div>
            @else
                <div class="row">
                    @foreach ($tasks as $task)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}</h5>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <p class="card-text">Data de criação:
                                        {{ $task->created_at->format('d/m/Y') }} às
                                        {{ $task->created_at->subHours(3)->format('H:i') }}</p>
                                    <p class="card-text">Data de encerramento:
                                        {{ $task->due_date->format('d/m/Y') }}</p>
                                    <p class="card-text">Status:
                                        <a href="{{ route('task.change-situation', ['task' => $task->id]) }}">
                                            <span
                                                class="badge bg-{{ $task->status->color }}">{{ $task->status->name }}</span>
                                        </a>
                                    </p>
                                    <a href="{{ route('tasks.show', $task->id) }}"
                                        class="btn btn-secondary btn-sm">Visualizar</a>
                                    <a href="{{ route('tasks.edit', $task->id) }}"
                                        class="btn btn-primary btn-sm">Editar</a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')"
                                            type="submit" class="btn btn-danger btn-sm">Remover</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div> --}}
@endsection
