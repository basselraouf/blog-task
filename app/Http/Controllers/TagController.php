<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Interfaces\TagRepositoryInterface;
use Exception;

class TagController extends Controller
{
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function index()
    {
        $tags = $this->tagRepository->all();

        return response()->json($tags, 200);
    }

    public function store(StoreTagRequest $request)
    {
        $validatedData = $request->validated();
        $tag =$this->tagRepository->create($validatedData);

        return response()->json([
            'message' => 'Tag created successfully',
            'tag' => $tag], 201);
    }

    public function update(UpdateTagRequest $request, int $id)
    {
        $validatedData = $request->validated();
        $tag = $this->tagRepository->update($id, $validatedData);

        return response()->json([
            'message' => 'Tag updated successfully',
            'tag' => $tag], 200);
    }

    public function destroy(int $id)
    {
        try{
            $this->tagRepository->delete($id);

            return response()->json(['message' => 'Tag deleted successfully'], 200);
        }catch(Exception $e){
            return response()->json(['message' => 'Can not find this tag'], 404);
        }
    }
}
