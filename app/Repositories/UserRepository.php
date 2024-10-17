<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function register(array $data)
    {
        try{
            $data['password'] = Hash::make($data['password']);

            $data['verification_code'] = random_int(100000, 999999);

            $user = User::create($data);
            Cache::forget('stats');

            return $user;

        }catch(Exception $e){

            throw new Exception('Error creating user: ' . $e->getMessage());
        }
    }

    public function confirmVerificationCode(array $data)
    {
        $user = User::where('phone', $data['phone'])->first();

        if(!$user){
            throw new Exception('This phone number is not existed!');
        }

        if($data['verification_code'] === $user->verification_code ){

            $user->is_verified = true;
            $user->save();

            return true;
        }

        return false;
    }

    public function login(array $data)
    {
        $user = User::where('phone', $data['phone'])->first();

        if($user && $user->is_verified && Hash::check($data['password'], $user->password) ){
            return $user;
        }

        return null;
    }

    public function logout()
    {
        $user = Auth::user();

        if($user){
            $user->tokens()->delete();

            return true;
        }

        return false;
    }

    public function countAllUsers(): int
    {
        return User::count();
    }

    public function countUsersWithNoPosts(): int
    {
        return User::doesntHave('posts')->count();
    }
}
