<?php

namespace App\Repositories;

use App\Models\Status;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class StatusRepository
{
    protected $status;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function findOrFail(int $status_id, array $relation = [])
    {
        return $this->status->with($relation)->findOrFail($status_id);
    }

    /**
     * @param  bool  $paginate
     * @param  array  $filters
     * @param  array  $relation
     * @return LengthAwarePaginator|Collection
     */
    public function all(bool $paginate = false, array $filters = [], array $relation = [])
    {
        $statuses = $this->status->with($relation);

        return $paginate ? $statuses->paginate(config('enum.page_limit') ?? 20) : $statuses->get();
    }

    public function create(array $value)
    {
        return $this->status->create($value);
    }

    public function update(int $status_id, array $value)
    {
        $status = $this->findOrFail($status_id);

        $status->update($value);
    }

    public function owned(bool $paginate = false, array $relation = [])
    {
        $statuses = $this->status->with($relation)
        ->where('created_by', auth()->user()->id);

        return $paginate ? $statuses->paginate(config('enum.page_limit') ?? 20) : $statuses->get();
    }
}
