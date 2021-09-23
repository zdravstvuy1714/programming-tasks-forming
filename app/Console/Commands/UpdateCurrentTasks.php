<?php

namespace App\Console\Commands;

use App\Repositories\Contracts\UserRepository;
use App\Services\TaskService;
use Illuminate\Console\Command;

class UpdateCurrentTasks extends Command
{
    protected $signature = 'update:current:tasks';

    protected $description = 'Updates the user current tasks with new ones.';

    private $taskService;

    private $userRepository;

    public function __construct(TaskService $taskService, UserRepository $userRepository)
    {
        parent::__construct();
        $this->taskService = $taskService;
        $this->userRepository = $userRepository;
    }

    public function handle(): void
    {
        $users = $this->userRepository->all();

        foreach ($users as $user) {
            $this->taskService->updateCurrentTasks($user->id);
        }
    }
}
