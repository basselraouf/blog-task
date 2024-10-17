<?php

namespace App\Http\Controllers;

use App\Interfaces\PostRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    protected $userRepository;
    protected $postRepository;

    public function __construct(UserRepositoryInterface $userRepository, PostRepositoryInterface $postRepository)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }

    public function stats()
    {
        $stats = Cache::remember('stats', 60 * 60, function () {
            $totalUsers = $this->userRepository->countAllUsers();
            $totalPosts = $this->postRepository->countAllPosts();
            $usersWithZeroPosts = $this->userRepository->countUsersWithNoPosts();

            return [
                'total_users' => $totalUsers,
                'total_posts' => $totalPosts,
                'users_with_zero_posts' => $usersWithZeroPosts,
            ];
        });

        return response()->json($stats, 200);
    }
}
