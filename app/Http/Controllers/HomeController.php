<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index($category = null, $currentCategory = null)
    {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->get();
        $totalTasks = Task::count(); 

        // se uma categoria for selecionada, busca todas as tasks daquela categoria
        if ($category) {
            $currentCategory = Category::find($category);
            $tasks = Task::where('user_id', $user->id)
                ->where('category_id', $category)
                ->orderBy('done')
                ->get();
        } else {
            // busca todas as tarefas normalmente daquele usuário e traz
            $tasks = Task::where('user_id', $user->id)
                ->orderBy('done')
                ->get();
            $currentCategory = null;
        }
        

        return view('tasks.index', compact('categories', 'currentCategory', 'tasks', 'totalTasks'));
    }
}