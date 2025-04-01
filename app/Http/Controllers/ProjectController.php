<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Repository\ProjectRepository;

class ProjectController extends Controller
{
    public function __construct(private ProjectRepository $projectRepository)
    {

    }

    public function getAllProjects()
    {
        $data = $this->projectRepository->getAllProjects();

        return response()->json(['data' => $data], 200);
    }
    public function createProject(CreateProjectRequest $request)
    {
        $this->projectRepository->createProject($request->all());

        return response()->json(['message' => 'success'], 200);
    }

    public function updateProject($id, UpdateProjectRequest $request)
    {
        $this->projectRepository->updateProject($id, $request->all());

        return response()->json(['message' => 'success'], 200);
    }

    public function deleteProject($id)
    {
        $this->projectRepository->deleteProject($id);
    }

}
