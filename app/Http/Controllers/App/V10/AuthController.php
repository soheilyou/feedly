<?php

namespace App\Http\Controllers\App\V10;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|min:3",
            "email" => "required|email|unique:users,email",
            "password" => "required|confirmed|min:6",
        ]);
        $user = $this->userRepository->createUser(
            $request->name,
            $request->email,
            $request->password
        );
        return response()->json([
            "name" => $user->name,
            "email" => $user->email,
            "token" => $user->createToken("auth")->plainTextToken,
        ]);
    }
}
