<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function findOrFail(int $user_id)
    {
        return $this->user->findOrFail($user_id);
    }

    /**
     * @param  bool  $paginate
     * @param  array  $filters
     * @param  array  $relation
     * @return LengthAwarePaginator|Collection
     */
    public function all(bool $paginate = false, array $filters = [], array $relation = [])
    {
        $users = $this->user->with($relation);

        return $paginate ? $users->paginate(config('enum.page_limit')) : $users->get();
    }

    public function create(array $value)
    {
        return $this->user->create($value);
    }

    public function exclude(array $relation = [])
    {
        $users = $this->user->with($relation)
            ->where('id', '!=', auth()->user()->id);
            // $users = $this->user->with($relation);

        return $users->get();
    }
}
