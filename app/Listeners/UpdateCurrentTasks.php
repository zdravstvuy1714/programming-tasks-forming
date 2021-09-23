<?php

namespace App\Listeners;

use App\Services\TaskService;
use Illuminate\Auth\Events\Registered;

class UpdateCurrentTasks
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function handle(Registered $event): void
    {
        $this->taskService->updateCurrentTasks($event->user->id);
    }
}
