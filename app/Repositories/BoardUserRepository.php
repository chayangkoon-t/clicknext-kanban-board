<?php

namespace App\Repositories;

use App\Models\BoardUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BoardUserRepository
{
    protected $boardUser;

    public function __construct(BoardUser $board_user)
    {
        $this->boardUser = $board_user;
    }

    public function findOrFail(int $board_user_id, array $relation = [])
    {
        return $this->boardUser->with($relation)->findOrFail($board_user_id);
    }

    /**
     * @param  bool  $paginate
     * @param  array  $filters
     * @param  array  $relation
     * @return LengthAwarePaginator|Collection
     */
    public function all(bool $paginate = false, array $filters = [], array $relation = [])
    {
        $board_users = $this->boardUser->with($relation);

        return $paginate ? $board_users->paginate(config('enum.page_limit') ?? 20) : $board_users->get();
    }

    public function create(array $value)
    {
        return $this->boardUser->firstOrCreate($value);
    }

    public function update(int $id, array $value)
    {
        $board_user = $this->findOrFail($id);

        return $board_user->update($value);
    }
}
