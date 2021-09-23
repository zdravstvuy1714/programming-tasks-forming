<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Policies\TaskPolicy;
use App\Repositories\Contracts\TaskRepository;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $taskService;

    private $taskRepository;

    public function __construct
    (
        TaskService $taskService,
        TaskRepository $taskRepository
    )
    {
        $this->middleware('auth:sanctum');
        $this->taskService = $taskService;
        $this->taskRepository = $taskRepository;
    }

    public function index(Request $request)
    {
        $tasks = $this->taskService->getCurrentTasks($request->user()->id);

        return response(compact('tasks'), 200);
    }

    public function complete(Request $request, int $taskID)
    {
        $task = $this->taskRepository->find($taskID);

        $this->authorize(TaskPolicy::COMPLETE, $task);

        $this->taskService->complete($request->user()->id, $taskID);

        return response([
            'message' => trans('Task has been successfully completed.'),
        ], 200);
    }

    public function skip(Request $request, int $taskID)
    {
        $task = $this->taskRepository->find($taskID);

        $this->authorize(TaskPolicy::SKIP, $task);

        $this->taskService->skip($request->user()->id, $taskID);

        return response([
            'message' => trans('Task has been successfully skipped.'),
        ], 200);
    }
}
