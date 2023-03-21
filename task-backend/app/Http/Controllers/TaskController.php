<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Validator;
use DB;

class TaskController extends Controller
{

    public function getOne(Request $request, $task_id){
        // Return a single task by id
        $task = Task::find($task_id);
        if (!$task) {
            return "Task not found";
        }
        return $task;
    }

    public function getAll(Request $request) {
        // Return all tasks
        return Task::all();
    }

    public function store(Request $request) {
        // Check that all fields are correct
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
        // Create new task
        $task = new Task();
        $task->due_date = $request->due_date;
        $task->difficulty = $request->difficulty;
        $task->is_completed = $request->is_completed;
        $task->task_title = $request->task_title;
        $task->description = $request->description;

        // Store task
        $task->save();
        return $task;
    }

    public function update(Request $request, $task_id) {
        // Check that all fields are correct
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

        // Check that task exists
        $task = Task::find($task_id);
        if (!$task) {
            return "Task not found";
        }

        // Update task
        $task->due_date = $request->due_date;
        $task->difficulty = $request->difficulty;
        $task->is_completed = $request->is_completed;
        $task->task_title = $request->task_title;
        $task->description = $request->description;

        // Save updated version
        $task->save();
        return $task;
    }

    public function delete(Request $request, $task_id) {
        // Check that task exists
        $task = Task::find($task_id);
        if (!$task) {
            return "Task not found";
        }

        // Delete task
        $task->delete();
        return "Task deleted!";
    }

    public function search(Request $request) {
        // Make sure query exists as string
        $validator = validator()->make($request->all(), [
            'task_title' => 'required|string'
        ]);
        if ($validator->fails()) {
            return "Title must be string";
        }

        // Search DB for task titles that match query
        $title = $request->task_title;
        $tasks = DB::table('tasks')
            ->where('task_title', 'LIKE', '%'.$title.'%')
            ->get();
        
        // Return all matches
        return $tasks;
    }
}
