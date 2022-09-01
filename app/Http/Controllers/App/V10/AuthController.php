<?php

namespace App\Http\Controllers\App\V10;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
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
        // TODO :: use Resource
        return response()->json([
            "name" => $user->name,
            "email" => $user->email,
            "token" => $user->createToken("auth")->plainTextToken,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);
        $user = $this->userRepository->findOneBy([
            "email" => strtolower($request->email),
        ]);
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                "email" => ["The provided credentials are incorrect."],
            ]);
        }
        // TODO :: use Resource
        return response()->json([
            "name" => $user->name,
            "email" => $user->email,
            "token" => $user->createToken("auth")->plainTextToken,
        ]);
    }
}
