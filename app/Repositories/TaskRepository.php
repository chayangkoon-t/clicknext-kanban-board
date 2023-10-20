<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function find(int $task_id, array $relation = [])
    {
        return $this->task->with($relation)->find($task_id);
    }

    public function findOrFail(int $task_id, array $relation = [])
    {
        return $this->task->with($relation)->findOrFail($task_id);
    }

    /**
     * @param  bool  $paginate
     * @param  array  $filters
     * @param  array  $relation
     * @return LengthAwarePaginator|Collection
     */
    public function all(bool $paginate = false, array $filters = [], array $relation = [])
    {
        $tasks = $this->task->with($relation);

        return $paginate ? $tasks->paginate(config('enum.page_limit') ?? 20) : $tasks->get();
    }

    public function create(array $value)
    {
        return $this->task->create($value);
    }

    public function update(int $task_id, array $value)
    {
        $task = $this->findOrFail($task_id);

        $task->update($value);
    }

    public function owned(bool $paginate = false, array $relation = [])
    {
        $tasks = $this->task->with($relation)
        ->where('created_by', auth()->user()->id)
        ->orderby('created_at', 'desc');

        return $paginate ? $tasks->paginate(config('enum.page_limit') ?? 20) : $tasks->get();
    }
}
