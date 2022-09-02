<?php

namespace App\Repositories\Feed;

use App\Models\BookmarkedItem;
use App\Models\Feed;
use App\Models\FeedUser;
use App\Models\Item;
use App\Models\ReadItem;
use App\Models\User;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FeedRepository extends BaseRepository implements FeedRepositoryInterface
{
    const FEED_USER_CACHE_KEY = "feed_user_%s_%s";

    public function __construct(Feed $model)
    {
        parent::__construct($model);
    }

    /**
     * @param string $url
     * @param ?string $name
     * @param ?string $rssPath
     * @param ?string $image
     * @return Feed
     */
    public function addFeed(
        string $url,
        ?string $name = null,
        ?string $rssPath = null,
        ?string $image = null
    ): Feed {
        return $this->create([
            "name" => $name,
            "url" => $url,
            "rss_path" => $rssPath,
            "image" => $image,
        ]);
    }

    public function addItem(
        int $feedId,
        string $title,
        string $link,
        string $image,
        string $description,
        $pubDate
    ): Item {
        return Item::create([
            "feed_id" => $feedId,
            "title" => $title,
            "link" => $link,
            "image" => $image,
            "description" => $description,
            "pub_date" => $pubDate,
        ]);
    }

    public function saveBulkItems(array $items)
    {
        return Item::insert($items);
    }

    public function getFeeds(User $user)
    {
        return $user->feeds()->get();
    }

    /**
     * @param int $userId
     * @param int $feedId
     * @return mixed
     */
    public function subscribe(int $userId, int $feedId)
    {
        return FeedUser::firstOrCreate([
            "user_id" => $userId,
            "feed_id" => $feedId,
        ]);
    }

    /**
     * @param int $userId
     * @param int $itemId
     * @return mixed
     */
    public function markAsRead(int $userId, int $itemId)
    {
        return ReadItem::firstOrCreate([
            "user_id" => $userId,
            "item_id" => $itemId,
        ]);
    }

    /**
     * @param int $userId
     * @param int $itemId
     * @return mixed
     */
    public function bookmark(int $userId, int $itemId)
    {
        return BookmarkedItem::firstOrCreate([
            "user_id" => $userId,
            "item_id" => $itemId,
        ]);
    }

    public static function getFeedUserCacheKey(int $userId, int $feedId)
    {
        return sprintf(self::FEED_USER_CACHE_KEY, $userId, $feedId);
    }

    public static function forgetFeedUserCacheKey(int $userId, int $feedId)
    {
        return Cache::delete(self::getFeedUserCacheKey($userId, $feedId));
    }

    public static function getUnReadItemsCount(int $userId, int $feedId): int
    {
        $cacheKey = self::getFeedUserCacheKey($userId, $feedId);
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }
        $feed = Feed::find($cached);
        $subscribedAt = FeedUser::where("user_id", $userId)
            ->where("feed_id", $feedId)
            ->firstOrFail()->created_at;
        $count = Item::where("feed_id", $feedId)
            ->where("created_at", ">", $subscribedAt)
            ->whereNotExists(function ($query) use ($userId) {
                $query
                    ->select(DB::raw(1))
                    ->from("read_items")
                    ->whereRaw("read_items.item_id = items.id")
                    ->where("user_id", $userId);
            })
            ->limit(1001) // TODO :: read from config
            ->count();
        // huge feeds must not be cached
        if ($feed->is_huge) {
            return $count;
        }
        return Cache::rememberForever(
            self::getFeedUserCacheKey($userId, $feedId),
            $count
        );
    }

    public function getFeedSubscribersCount(int $feedId): int
    {
        return FeedUser::where("feed_id", $feedId)->count();
    }
}
