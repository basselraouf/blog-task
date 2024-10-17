<?php

namespace App\Http\Controllers;

use App\Http\Requests\confirmVerificationCodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        try{
            $user = $this->userRepository->register($validatedData);

            Log::info("Verification code for {$user->name}: {$user->verification_code}");

            return response()->json([
                'message'=> 'User Created Successfully',
                'user' => $user,
                'access token' => $user->createToken('Mytoken')->plainTextToken,
            ],201);

        }catch(Exception $e){

            return response()->json(['message' => 'Something went wrong while creating the new user: ' ,'error'=> $e->getMessage()], 500);
        }
    }

    public function confirmVerificationCode(confirmVerificationCodeRequest $request)
    {
        $validatedData = $request->validated();

        $verification = $this->userRepository->confirmVerificationCode($validatedData);

        if($verification){
            return response()->json(['message' => 'User verified successfully'], 200);
        }

        return response()->json(['message' => 'Verification failed'], 422);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $user = $this->userRepository->login($validatedData);

        if($user){
            return response()->json([
                'message' => 'Login successful',
                'access_token' => $user->createToken('Mytoken')->plainTextToken,
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials or user not verified'], 422);
    }

    public function logout()
    {
       $logout = $this->userRepository->logout();

       if($logout){
            return response()->json(['message' => 'Logout successful'], 200);
       }

       return response()->json(['message' => 'Failed to logout'], 500);
    }
}
