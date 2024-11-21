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

        return view('admin.pages.home.index', compact('tasks'));
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
        ], [
            'title.required' => 'O campo titulo é obrigatório!',
            'title.string' => 'O campo titulo deve ser uma string válida!'
            ]);

        // criando tarefa
        $task = Task::create( [
            'title' => $request->title
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

    
    public function update(Task $task, Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',            
        ], [
            'title.required' => 'O campo titulo é obrigatório!',
            'title.string' => 'O campo titulo deve ser uma string válida!'    
        ]);

        // pegando a categoria atual antes de atualizar a atarefa
        $task->update([
            'title'=> $request->title,
            'order'=> (int)$request->order,
        ]);
        
        return back()->with('menssage', 'Tarefa atualizada com sucesso!');
       
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