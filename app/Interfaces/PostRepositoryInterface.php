<?php

namespace App\Interfaces;


interface PostRepositoryInterface
{
    public function allPostsForUser($userId);
    public function createPost(array $data, $userId);
    public function updatePost($userId, $postId, array $data);
    public function findPostById($userId, $postId);
    public function softDeletePost($userId, $postId);
    public function getDeletedPostsForUser(int $userId);
    public function restorePost($userId, $postId);
    public function countAllPosts();

}
