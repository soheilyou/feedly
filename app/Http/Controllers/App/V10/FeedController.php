<?php

namespace App\Http\Controllers\App\V10;

use App\Http\Controllers\Controller;
use App\Repositories\Feed\FeedRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    private FeedRepositoryInterface $feedRepository;

    public function __construct(FeedRepositoryInterface $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    public function addFeed(Request $request): JsonResponse
    {
        $user = Auth::user();
        $request->validate([
            "url" => "required",
        ]);
        if (!$this->feedRepository->findOneBy(["url" => $request->url])) {
            $this->feedRepository->addFeed($request->url);
        }
        return response()->json(["success" => true]);
    }
}
