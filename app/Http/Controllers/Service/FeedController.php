<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Repositories\Feed\FeedRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    private FeedRepositoryInterface $feedRepository;

    public function __construct(FeedRepositoryInterface $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    public function addNewItems(Request $request): JsonResponse
    {
        $request->validate([
            "items" => "required|array",
            "items.*.feed_id" => "required|exists:feeds,id",
            "items.*.pub_date" => "required",
            "items.*.title" => "required|max:500",
            "items.*.link" => "required",
            "items.*.description" => "required",
        ]);
        if ($this->feedRepository->saveBulkItems($request->items)) {
            return response()->json(["success" => true]);
        }
        return response()->json(
            ["success" => false],
            Response::HTTP_BAD_GATEWAY
        );
    }
}
