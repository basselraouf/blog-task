<?php

namespace App\Repositories;

use App\Interfaces\TagRepositoryInterface;
use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
    public function all()
    {
        return Tag::all();
    }

    public function create(array $data)
    {
        return Tag::create($data);
    }

    public function update(int $id, array $data)
    {
        $tag = Tag::findOrFail($id);

        $tag->update($data);

        return $tag;
    }

    public function delete(int $id)
    {
        $tag = Tag::findOrFail($id);
        return $tag->delete();
    }
}
