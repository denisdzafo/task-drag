<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\EditTaskRequest;
use App\Repository\TaskRepository;

class TaskController extends Controller
{
    public function __construct(private TaskRepository $taskRepository)
    {

    }

    public function getAllTasks()
    {
        $data = $this->taskRepository->getTasks();

        return response()->json(['data' => $data], 200);
    }

    public function createTask(CreateTaskRequest $request)
    {
        $this->taskRepository->createTask($request->all());

        return response()->json(['message' => 'success'], 200);
    }

    public function updateTask($id, EditTaskRequest $request)
    {
        $this->taskRepository->updateTask($id, $request->all());

        return response()->json(['message' => 'success'], 200);
    }

    public function deleteTask($id)
    {
        $this->taskRepository->deleteTask($id);
    }
}
