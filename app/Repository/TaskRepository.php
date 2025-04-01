<?php

namespace App\Repository;

use App\Models\Task;
class TaskRepository {

    public function getTasks()
    {
        return Task::with('projects')
            ->orderBy('priority', 'asc')
            ->get();
    }

    public function createTask($data)
    {
        $newTask = new Task();
        $newTask->create($data);
    }

    public function updateTask($id, $data)
    {
        $newTask = Task::find($id);
        $newTask->update($data);


    }

    public function deleteTask($id)
    {
        $task = Task::find($id);
        $tmpPriority = $task->priority;
        $task->delete();
        $this->updatePriority($tmpPriority);
    }

    private function updatePriority($priority)
    {
        $tasks = Task::where('priority', '>', $priority)->get();
        foreach ($tasks as $task) {
            $task->priority = $task->priority -1;
            $task->save();
        }
    }
}
