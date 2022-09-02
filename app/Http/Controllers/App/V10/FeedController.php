<?php

namespace App\Http\Controllers\App\V10;

use App\Http\Controllers\Controller;
use App\Repositories\Feed\FeedRepository;
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
        $feed = $this->feedRepository->findOneBy(["url" => $request->url]);
        if (!$feed) {
            $feed = $this->feedRepository->addFeed($request->url);
        }
        $this->feedRepository->subscribe($user->id, $feed->id);
        return response()->json(["success" => true]);
    }

    public function markAsRead(Request $request): JsonResponse
    {
        // TODO :: check user is subscriber of the feed
        $user = Auth::user();
        $request->validate([
            "item_id" => "required|exists:items,id",
        ]);
        $this->feedRepository->markAsRead($user->id, $request->item_id);
        return response()->json(["success" => true]);
    }

    public function bookmark(Request $request): JsonResponse
    {
        // TODO :: check user is subscriber of the feed
        $user = Auth::user();
        $request->validate([
            "item_id" => "required|exists:items,id",
        ]);
        $this->feedRepository->bookmark($user->id, $request->item_id);
        return response()->json(["success" => true]);
    }

    public function getUnReadItemsCount(Request $request)
    {
        // TODO :: check user is subscriber of the feed
        $user = Auth::user();
        $request->validate([
            "feed_id" => "required",
        ]);
        FeedRepository::forgetFeedUserCacheKey($user->id, $request->feed_id);
        return $this->feedRepository->getUnReadItemsCount(
            $user->id,
            $request->feed_id
        );
    }
}
