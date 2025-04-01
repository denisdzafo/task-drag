<?php

namespace App\Repository;

use App\Models\Project;
class ProjectRepository
{

    public function getAllProjects()
    {
        return Project::with('tasks')->get();
    }

    public function createProject($data)
    {
        $newProject = new Project();
        $newProject->name = $data['name'];
        $newProject->save();
        $newProject->tasks()->attach($data['tasks']);
    }

    public function updateProject($id, $data)
    {
        $project = Project::find($id);
        $project->name = $data['name'];
        $project->save();
        $project->tasks()->sync($data['tasks']);
    }

    public function deleteProject($id)
    {
        $project = Project::find($id);
        $project->tasks()->detach();
        $project->delete();
    }

}
