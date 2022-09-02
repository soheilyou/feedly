<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function addNewItems(Request $request): JsonResponse
    {
        $request->validate([
            "items" => "required|array",
        ]);
        if ($this->userRepository->saveBulkItems($request->items)) {
            return response()->json(["success" => true]);
        }
        return response()->json(
            ["success" => false],
            Response::HTTP_BAD_GATEWAY
        );
    }
}
