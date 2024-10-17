<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Cache;

class PostRepository implements PostRepositoryInterface
{
    public function allPostsForUser($userId)
    {
        return Post::with(['tags:id,name'])->where('user_id', $userId)->orderBy('pinned', 'desc')->get();
    }

    public function createPost(array $data, $userId)
    {
        $data['user_id'] = $userId;
        
        Cache::forget('stats');

        return Post::create($data);
    }

    public function updatePost($userId, $postId, array $data)
    {
        try {
            $post = Post::where('id', $postId)->where('user_id', $userId)->firstOrFail();

            $post->update($data);

            if (isset($data['tags'])) {
                $post->tags()->sync($data['tags']);
            }

            Cache::forget('stats');

            return $post;

        }catch (Exception $e) {
            throw new Exception('Post not found or unauthorized');
        }
    }

    public function findPostById($userId, $postId)
    {
        try{
            return Post::with('tags:id,name')->where('id', $postId)->where('user_id', $userId)->firstOrFail();

        } catch (Exception $e) {

            throw new Exception('Post not found!');
        }
    }

    public function softDeletePost($userId, $postId)
    {
        try {
            $post = Post::where('id', $postId)->where('user_id', $userId)->firstOrFail();

            $post->delete();

            Cache::forget('stats');

            return true;

        } catch (Exception $e) {

            throw new Exception('Post not found or unauthorized');
        }
    }

    public function getDeletedPostsForUser(int $userId)
    {
        try {
            return Post::onlyTrashed()->where('user_id', $userId)->get();

        }catch(Exception $e){

            throw new Exception('Error retrieving deleted posts: ' . $e->getMessage());
        }
    }

    public function restorePost($userId, $postId)
    {
        try {
            $post = Post::onlyTrashed()->where('id', $postId)->where('user_id', $userId)->firstOrFail();
            $post->restore();

            return $post;
        }catch(Exception $e){

            throw new Exception('Post not found or unauthorized: ' . $e->getMessage());
        }
    }

    public function countAllPosts()
    {
        return Post::count();
    }

}
