<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Validator;
use DB;

class TaskController extends Controller
{

    public function getOne(Request $request, $task_id){
        $task = Task::find($task_id);
        if (!$task) {
            return "Task not found";
        }
        return $task;
    }

    public function getAll(Request $request) {
        return Task::all();
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'due_date' => 'date',
            'difficulty' => 'required|integer',
            'is_completed' => 'required|boolean',
            'task_title' => 'required|string',
            'description' => 'string'
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $task = new Task();
        $task->due_date = $request->due_date;
        $task->difficulty = $request->difficulty;
        $task->is_completed = $request->is_completed;
        $task->task_title = $request->task_title;
        $task->description = $request->description;
        $task->save();
        return $task;
    }

    public function update(Request $request, $task_id) {
        $validator = Validator::make($request->all(), [
            'due_date' => 'date',
            'difficulty' => 'required|integer',
            'is_completed' => 'required|boolean',
            'task_title' => 'required|string',
            'description' => 'string'
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $task = Task::find($task_id);
        if (!$task) {
            return "Task not found";
        }
        $task->due_date = $request->due_date;
        $task->difficulty = $request->difficulty;
        $task->is_completed = $request->is_completed;
        $task->task_title = $request->task_title;
        $task->description = $request->description;
        $task->save();
        return $task;
    }

    public function delete(Request $request, $task_id) {
        $task = Task::find($task_id);
        if (!$task) {
            return "Task not found";
        }
        $task->delete();
        return "Task deleted!";
    }

    public function search(Request $request) {
        $validator = validator()->make($request->all(), [
            'task_title' => 'required|string'
        ]);

        if ($validator->fails()) {
            return "Title must be string";
        }

        $title = $request->task_title;

        $tasks = DB::table('tasks')
            ->where('task_title', 'LIKE', '%'.$title.'%')
            ->get();
        
        return $tasks;
    }
}
