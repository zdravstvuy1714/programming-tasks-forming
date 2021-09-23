<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class TaskPolicy
{
    use HandlesAuthorization;

    const COMPLETE = 'complete';
    const SKIP = 'skip';

    public function complete(User $user, Task $task): bool
    {
        return DB::table('current_user_task')->where([
            'user_id' => $user->id,
            'task_id' => $task->id,
        ])->exists();
    }

    public function skip(User $user, Task $task): bool
    {
        return DB::table('current_user_task')->where([
            'user_id' => $user->id,
            'task_id' => $task->id,
        ])->exists();
    }
}
