<?php

namespace App\Repositories;

use App\Models\Board;
use App\Models\BoardUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BoardRepository
{
    protected $board;
    protected $boardUser;

    public function __construct(Board $board, BoardUser $boardUser)
    {
        $this->board = $board;
        $this->boardUser = $boardUser;
    }

    public function findOrFail(int $board_id, array $relation = [])
    {
        return $this->board->with($relation)->findOrFail($board_id);
    }

    /**
     * @param  bool  $paginate
     * @param  array  $filters
     * @param  array  $relation
     * @return LengthAwarePaginator|Collection
     */
    public function all(bool $paginate = false, array $filters = [], array $relation = [])
    {
        $boards = $this->board->with($relation);

        return $paginate ? $boards->paginate(config('enum.page_limit') ?? 20) : $boards->get();
    }

    public function create(array $value)
    {
        return $this->board->create($value);
    }

    public function update(int $board_id, array $value)
    {
        $board = $this->findOrFail($board_id);

        return $board->update($value);
    }

    public function owned(bool $paginate = false, array $relation = [])
    {
        $boards = $this->board->with($relation)
            ->where(function ($query) {
                $query->whereHas('boardUsers', function ($subquery) {
                    $subquery->where('user_id', auth()->user()->id)
                    ->where('active', true)
                    ;
                });
            })
            ->orderBy('created_at', 'desc');

        return $paginate ? $boards->paginate(config('enum.page_limit') ?? 20) : $boards->get();
    }

    public function indexBoard(bool $paginate = false, array $relation = [])
    {
        $boards = $this->board->with($relation)
            ->where(function ($query) {
                $query->where('created_by', auth()->user()->id)
                    ->orWhereHas('boardUsers', function ($subquery) {
                        $subquery->where('user_id', auth()->user()->id);
                    });
            })
            ->orderBy('created_at', 'desc');

        return $paginate ? $boards->paginate(config('enum.page_limit') ?? 20) : $boards->get();
    }
}
