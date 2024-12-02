<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public readonly Task $task;
    public function __construct()
    {
        $this->task = new Task();
    }

    /**
     * Display a listing of the resource.
     */
    public function index($category = null)
    {

        // trazendo apenas tarefas daquela categoria!
        $tasks = Task::fromUser()->orderBy('order')->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // recuperando todos os status
        $statusTasks = Status::orderBy('name', 'asc')->get();

        $user = Auth::id();
        //recuperando categorias daquele usuário
        $categories = Category::where('user_id', $user)->orderBy('name', 'asc')->get();

        return view('actions.task.create', [
            'statusTasks' => $statusTasks,
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id', 
        ], [
            'title.required' => 'O campo titulo é obrigatório!',
            'title.string' => 'O campo titulo deve ser uma string válida!',
            'category_id.exists' => 'A categoria selecionada não é válida!',
            ]);

        // criando tarefa
        $task = Task::create( [
            'title' => $request->title,
            'category_id' => $request->category_id
        ]
        );

        return back()->with('menssage', 'Tarefa criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {

        return view('actions.task.show', [
            'task' => $task
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        // recuperando todos os status
        $statusTasks = Status::orderBy('name', 'asc')->get();

        $user = Auth::id();
        //recuperando categorias daquele usuário
        $categories = Category::where('user_id', $user)->orderBy('name', 'asc')->get();

        return view('actions.task.edit', [
            'task' => $task,
            'statusTasks' => $statusTasks,
            'categories' => $categories,
        ]);
    }

        
        public function update(Request $request, Task $task)
    {
            // Validação do título e do category_id (se fornecido)
            $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id', 
                'done' => 'nullable|boolean',
            ], [
                'title.required' => 'O campo título é obrigatório!',
                'title.string' => 'O campo título deve ser uma string válida!',
                'category_id.exists' => 'A categoria selecionada não é válida!', // Mensagem caso category_id seja inválido
            ]);

            // Atualizando a tarefa com os dados fornecidos
            $task->update([
                'title' => $request->title,
                'category_id' => $request->category_id ? (int) $request->category_id : null, 
                'order' => (int) $request->order, 
                'done' => (bool) $request->done,  
            ]);

            return back()->with('message', 'Tarefa atualizada com sucesso!');
        }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('menssage', 'Tarefa excluida com sucesso!');
       
    }

    public function changeSituation(Task $task)
    {
        // Alternar entre os status, se estiver pendente vira em progresso, se estiver em progresso vira concluida!
        $nextStatus = $task->status_id == 1 ? 2 : ($task->status_id == 2 ? 3 : 1);

        // Atualizar somente o campo status_id
        $task->update(['status_id' => $nextStatus]);
        return redirect()->back()->with('menssage', 'Status da tarefa atualizado com sucesso!');
    }
}