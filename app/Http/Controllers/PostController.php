<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Interfaces\PostRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    public function index()
    {
        $userId = Auth::id();

        $posts = $this->postRepository->allPostsForUser($userId);

        return response()->json($posts, 200);
    }

    public function store(StorePostRequest $request)
    {
        $userId = Auth::id();

        $validatedData = $request->validated();

        $post = $this->postRepository->createPost($validatedData, $userId);

        if ($request->has('tags')) {
            $post->tags()->attach($request->input('tags'));
        }

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
        ], 201);
    }

    public function show($id)
    {
        $userId = Auth::id();

        try {
            $post = $this->postRepository->findPostById($userId, $id);

            return response()->json($post, 200);

        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage()], 404);
        }
    }


    public function update(UpdatePostRequest $request, $id)
    {
        $userId = Auth::id();

        $validatedData = $request->validated();
        // dd($request->all());
        try {
            $post = $this->postRepository->updatePost($userId, $id, $validatedData);

            return response()->json([
                'message' => 'Post updated successfully',
                'post' => $post
            ], 200);

        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy($id)
    {
        $userId = Auth::id();

        try {

            $this->postRepository->softDeletePost($userId, $id);

            return response()->json(['message' => 'Post deleted successfully'], 200);

        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function viewDeletedPosts()
    {
        $userId = Auth::id();

        try {
            $deletedPosts = $this->postRepository->getDeletedPostsForUser($userId);

            return response()->json($deletedPosts, 200);

        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function restore($id)
    {
        $userId = Auth::id();

        try {
            $post = $this->postRepository->restorePost($userId, $id);

            return response()->json([
                'message' => 'Post restored successfully',
                'post' => $post
            ], 200);

        } catch (Exception $e) {
            
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
