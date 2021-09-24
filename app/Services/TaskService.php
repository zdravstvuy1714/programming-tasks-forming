<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepository;
use App\Repositories\Contracts\TaskRepository;
use Illuminate\Support\Facades\DB;

class TaskService
{
    private CategoryRepository $categoryRepository;

    private TaskRepository $taskRepository;

    public function __construct
    (
        CategoryRepository $categoryRepository,
        TaskRepository $taskRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->taskRepository = $taskRepository;
    }

    public function getCurrentTasks(int $userID): array
    {
        $tasks = DB::select('
            SELECT DISTINCT tasks.*, (CASE WHEN (SELECT task_id FROM completed_user_task WHERE user_id = :user_id AND task_id = tasks.id) THEN true ELSE false END) AS is_completed
            FROM current_user_task
            INNER JOIN tasks
            on tasks.id = current_user_task.task_id
        ', ['user_id' => $userID]);

        return $tasks;
    }

    public function getNewTasks(int $userID): array
    {
        $tasks = DB::select('
            SELECT DISTINCT
            tasks.id,
            tasks.category_id,
            (CASE WHEN (SELECT task_id FROM completed_user_task WHERE user_id = ? AND task_id = tasks.id) THEN true ELSE false END) AS completed
                FROM tasks
                WHERE tasks.id NOT IN
                (
                    SELECT task_id FROM skipped_user_task WHERE user_id = ?
                    UNION
                    SELECT task_id FROM past_user_task WHERE user_id = ?
                    UNION
                    SELECT task_id FROM current_user_task WHERE user_id = ?
                );
        ', array_fill(0, 4, $userID));

        return $tasks;
    }

    public function moveCurrentTasksToPastTasks(int $userID): void
    {
        $currentTasks = $this->getCurrentTasks($userID);

        foreach ($currentTasks as $currentTask) {
            DB::transaction(function () use ($userID, $currentTask) {
                DB::table('current_user_task')->where([
                    'user_id'       => $userID,
                    'task_id'       => $currentTask->id,
                ])->delete();

                DB::table('past_user_task')->insert([
                    'user_id'       => $userID,
                    'task_id'       => $currentTask->id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }, 3);
        }
    }

    public function updateCurrentTasks($userID): void
    {
        $this->moveCurrentTasksToPastTasks($userID);

        $categories = $this->categoryRepository->all();

        $newTasks = collect($this->getNewTasks($userID));

        foreach ($categories as $category) {
            if ($newTasks->contains('category_id', $category->id)) {
                $task = $newTasks->where('category_id', $category->id)->random();

                DB::table('current_user_task')->insert([
                    'user_id'       => $userID,
                    'task_id'       => $task->id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }

    public function complete(int $userID, int $taskID): void
    {
        if
        (
            DB::table('completed_user_task')->where([
                'user_id' => $userID,
                'task_id' => $taskID,
            ])->doesntExist()
        )
        {
            DB::table('completed_user_task')->insert([
                'user_id'       => $userID,
                'task_id'       => $taskID,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    public function moveCurrentTaskToSkippedTasks(int $userID, int $taskID): void
    {
        DB::transaction(function () use ($userID, $taskID) {
            DB::table('current_user_task')->where([
                'user_id' => $userID,
                'task_id' => $taskID,
            ])->delete();

            DB::table('skipped_user_task')->insert([
                'user_id' => $userID,
                'task_id' => $taskID,
            ]);
        }, 3);
    }

    public function skip(int $userID, int $taskID): void
    {
        $task = $this->taskRepository->find($taskID);

        $category = $this->categoryRepository->find($task->category_id);

        $this->moveCurrentTaskToSkippedTasks($userID, $taskID);

        $newTasks = collect($this->getNewTasks($userID));

        if ($newTasks->contains('category_id', $category->id)) {
            $task = $newTasks->where('category_id', $category->id)->random();

            DB::table('current_user_task')->insert([
                'user_id' => $userID,
                'task_id' => $task->id,
            ]);
        }
    }

    public function hype()
    {
        return 1;
    }
}
