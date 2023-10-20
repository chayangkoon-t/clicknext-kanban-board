<?php

namespace App\Repositories;

use App\Models\TaskBoardUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskBoardUserRepository
{
    protected $taskTaskBoardUser;

    public function __construct(TaskBoardUser $task_board_user)
    {
        $this->taskTaskBoardUser = $task_board_user;
    }

    public function findOrFail(int $task_board_user_id, array $relation = [])
    {
        return $this->taskTaskBoardUser->with($relation)->findOrFail($task_board_user_id);
    }

    /**
     * @param  bool  $paginate
     * @param  array  $filters
     * @param  array  $relation
     * @return LengthAwarePaginator|Collection
     */
    public function all(bool $paginate = false, array $filters = [], array $relation = [])
    {
        $task_board_users = $this->taskTaskBoardUser->with($relation);

        return $paginate ? $task_board_users->paginate(config('enum.page_limit') ?? 20) : $task_board_users->get();
    }

    public function create(array $value)
    {
        return $this->taskTaskBoardUser->firstOrCreate($value);
    }

    public function update(int $id, array $value)
    {
        $task_board_user = $this->findOrFail($id);

        return $task_board_user->update($value);
    }
}
