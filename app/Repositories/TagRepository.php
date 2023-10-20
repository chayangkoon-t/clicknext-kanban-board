<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TagRepository
{
    protected $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function findOrFail(int $tag_id)
    {
        return $this->tag->findOrFail($tag_id);
    }

    /**
     * @param  bool  $paginate
     * @param  array  $filters
     * @param  array  $relation
     * @return LengthAwarePaginator|Collection
     */
    public function all(bool $paginate = false, array $filters = [], array $relation = [])
    {
        $tags = $this->tag->with($relation);

        if (isset($filters['title'])) {
            $tags = $tags->where('title', $filters['title']);
        }

        return $paginate ? $tags->paginate(config('enum.page_limit')) : $tags->get();
    }

    public function find(string $column, string $value)
    {
        return $this->tag->find($column, $value);
    }

    public function create(array $value)
    {
        return $this->tag->create($value);
    }

    public function update(int $tag_id, array $value)
    {
        $tag = $this->findOrFail($tag_id);

        $tag->update($value);
    }
}
